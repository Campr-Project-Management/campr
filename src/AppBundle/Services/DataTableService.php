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

    public function paginate($persistentObjectName, $requestParams)
    {
        $this->requestParser->parse($requestParams);

        $entriesNumber = $this
            ->em
            ->getRepository($persistentObjectName)
            ->countTotal()
        ;

        $entries = $this
            ->em
            ->getRepository($persistentObjectName)
            ->findByKeyAndField(
                $this->requestParser->key,
                $this->requestParser->field,
                $this->requestParser->order,
                $this->requestParser->offset,
                $this->requestParser->limit
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
