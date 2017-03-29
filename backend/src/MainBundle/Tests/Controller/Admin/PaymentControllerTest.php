<?php

namespace MainBundle\Tests\Controller\Admin;

use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentControllerTest extends BaseController
{
    public function testListActionPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/payment/list');

        $this->assertEquals(1, $crawler->filter('table.table-condensed.table-responsive')->count());
        $this->assertEquals(5, $crawler->filter('table.table-condensed.table-responsive th')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
