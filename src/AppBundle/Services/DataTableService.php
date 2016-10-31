<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;

class DataTableService
{
    /** @var Serializer */
    private $serializer;

    /** @var RequestParserService */
    private $requestParser;

    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em, RequestParserService $requestParser, Serializer $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->requestParser = $requestParser;
    }

    public function paginateByColumn($persistentObjectName, $searchField, $requestParams)
    {
        $this->requestParser->parse($requestParams);

        $entriesNumber = $this
            ->em
            ->getRepository($persistentObjectName)
            ->countTotal()
        ;

        $order = [];
        if (!empty($this->requestParser->field)) {
            $order = [
                $this->requestParser->field => $this->requestParser->order ?: 'asc',
            ];
        }

        $entries = $this
            ->em
            ->getRepository($persistentObjectName)
            ->findByWithLike(
                [$searchField => $this->requestParser->searchPhrase],
                $order,
                $this->requestParser->limit,
                $this->requestParser->offset
            )
        ;

        $response = [
            'current' => intval($requestParams['current']),
            'rowCount' => intval($requestParams['rowCount']),
            'rows' => json_decode($this->serializer->serialize($entries, 'json')),
            'total' => intval($entriesNumber),
        ];

        return $response;
    }
}
