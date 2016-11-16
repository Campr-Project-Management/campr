<?php

namespace AppBundle\Tests\Services;

use AppBundle\Services\RequestParserService;

class RequestParserServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var RequestParserService */
    private $requestParser;

    public function setUp()
    {
        $this->requestParser = new RequestParserService();
    }

    /**
     * @dataProvider getDataForTestParse
     *
     * @var array params
     */
    public function testParse($params)
    {
        $this->requestParser->parse($params);

        $this->assertSame(0, $this->requestParser->offset);
        $this->assertSame(10, $this->requestParser->limit);
        $this->assertSame('name', $this->requestParser->field);
        $this->assertSame('ASC', $this->requestParser->order);
        $this->assertSame('FirstName', $this->requestParser->searchPhrase);
    }

    /**
     * return array.
     */
    public function getDataForTestParse()
    {
        return [
            [
                [
                    'current' => '1',
                    'rowCount' => '10',
                    'sort' => [
                        'name' => 'asc',
                    ],
                    'searchPhrase' => 'FirstName',
                ],
            ],
        ];
    }

    public function tearDown()
    {
        $this->requestParser = null;
    }
}
