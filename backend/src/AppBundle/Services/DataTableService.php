<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;

/**
 * Class DataTableService
 * Service used to retrieve data for listing table.
 */
class DataTableService
{
    /** @var Serializer */
    private $serializer;

    /** @var RequestParserService */
    private $requestParser;

    /** @var EntityManager */
    private $em;

    /**
     * @var array
     */
    private $options = [
        'countFunction' => 'countTotal',
        'countArguments' => [],
    ];

    /**
     * DataTableService constructor.
     *
     * @param EntityManager        $em
     * @param RequestParserService $requestParser
     * @param Serializer           $serializer
     */
    public function __construct(EntityManager $em, RequestParserService $requestParser, Serializer $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->requestParser = $requestParser;
    }

    /**
     * Paginate with search by column.
     *
     * @param $persistentObjectName
     * @param $searchField
     * @param $requestParams
     * @param $options
     *
     * @return array
     */
    public function paginateByColumn($persistentObjectName, $searchField, $requestParams, array $options = [])
    {
        $this->requestParser->parse($requestParams);

        $countFunction = isset($options['countFunction']) ? $options['countFunction'] : $this->options['countFunction'];
        $countArguments = isset($options['countArguments']) ? $options['countArguments'] : $this->options['countArguments'];
        $repo = $this->em->getRepository($persistentObjectName);
        $entriesNumber = call_user_func_array([$repo, $countFunction], $countArguments);

        $order = [];
        if (!empty($this->requestParser->field)) {
            $order = [
                $this->requestParser->field => $this->requestParser->order ?: 'asc',
            ];
        }

        $criteria = [];
        if (isset($options['findIn'])) {
            $criteria['findIn'] = $options['findIn'];
        }
        $criteria[$searchField] = $this->requestParser->searchPhrase;
        $entries = $this
            ->em
            ->getRepository($persistentObjectName)
            ->findByWithLike(
                $criteria,
                $order,
                $this->requestParser->limit,
                $this->requestParser->offset
            )
        ;

        $response = [
            'current' => intval($requestParams['current']),
            'rowCount' => intval($requestParams['rowCount']),
            'rows' => $entries,
            'total' => intval($entriesNumber),
        ];

        return $response;
    }
}
