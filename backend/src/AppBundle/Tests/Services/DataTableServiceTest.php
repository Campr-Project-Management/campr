<?php

namespace AppBundle\Tests\Services;

use AppBundle\Entity\Portfolio;
use AppBundle\Repository\PortfolioRepository;
use AppBundle\Services\DataTableService;
use AppBundle\Services\RequestParserService;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;

class DataTableServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var DataTableService */
    private $dataTableService;

    /** @var RequestParserService */
    private $requestParser;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $entityManager;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $serializer;

    public function setUp()
    {
        $this->serializer = $this
            ->getMockBuilder(Serializer::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->requestParser = new RequestParserService();

        $this->dataTableService = new DataTableService(
            $this->entityManager,
            $this->requestParser,
            $this->serializer
        );
    }

    /**
     * @dataProvider getDataForPaginateByColumn()
     *
     * @param string $persistentObjectName
     * @param string $repositoryObjectName
     * @param string $searchField
     * @param array  $requestParams
     */
    public function testPaginateByColumn($persistentObjectName, $repositoryObjectName, $searchField, $requestParams)
    {
        $self = $this;

        $repository = $this
            ->getMockBuilder($repositoryObjectName)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this
            ->entityManager
            ->expects($this->any())
            ->method('getRepository')
            ->willReturn($repository)
        ;

        $repository
            ->expects($this->once())
            ->method('countTotal')
            ->willReturn(1)
        ;

        $repository
            ->expects($this->once())
            ->method('findByWithLike')
            ->willReturnCallback(function ($a, $b, $c, $d) use ($self) {
                $self->assertSame(['name' => 'portfolio'], $a, 'Search field do not match');
                $self->assertSame(['name' => 'ASC'], $b, 'Order do no match');
                $self->assertSame(10, $c, 'Limit do no match');
                $self->assertSame(0, $d, 'Offset do not match');

                return [];
            })
        ;

        $result = $this->dataTableService->paginateByColumn($persistentObjectName, $searchField, $requestParams);

        $this->assertSame(1, $result['current'], 'Current do not match');
        $this->assertSame(10, $result['rowCount'], 'Row count do not match');
        $this->assertSame(array(), $result['rows'], 'Rows do not match');
        $this->assertSame(1, $result['total'], 'Current total do not match');
    }

    /**
     * return array.
     */
    public function getDataForPaginateByColumn()
    {
        return [
            [
                Portfolio::class,
                PortfolioRepository::class,
                'name',
                [
                    'current' => '1',
                    'rowCount' => '10',
                    'sort' => [
                        'name' => 'asc',
                    ],
                    'searchPhrase' => 'portfolio',
                ],
            ],
        ];
    }

    public function tearDown()
    {
        $this->serializer = null;
        $this->entityManager = null;
        $this->dataTableService = null;
        $this->requestParser = null;
    }
}
