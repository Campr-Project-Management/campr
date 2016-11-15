<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Portfolio;
use AppBundle\Entity\User;
use AppBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PortfolioControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/portfolio/create');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_description"', $crawler->html());
        $this->assertContains('name="create[description]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/portfolio/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testIfNameIsUniqueOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/portfolio/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'portfolio1';

        $crawler = $this->client->submit($form);

        $this->assertContains('That name is taken', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/portfolio/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'portfolio3';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Portfolio successfully created!', $this->client->getResponse()->getContent());

        $portfolio = $this
            ->em
            ->getRepository(Portfolio::class)
            ->findOneBy([
                'name' => 'portfolio3',
            ])
        ;
        $this->em->remove($portfolio);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $portfolio = (new Portfolio())
            ->setName('portfolio4')
        ;
        $this->em->persist($portfolio);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/portfolio/%d/edit', $portfolio->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Portfolio successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/portfolio/1/edit');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_description"', $crawler->html());
        $this->assertContains('name="create[description]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/portfolio/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testIfNameIsUniqueOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/portfolio/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = 'portfolio2';

        $crawler = $this->client->submit($form);

        $this->assertContains('That name is taken', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/portfolio/2/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = 'portfolio2';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Portfolio successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/portfolio/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="name"', $crawler->html());
        $this->assertContains('data-column-id="description"', $crawler->html());
        $this->assertContains('data-column-id="createdAt"', $crawler->html());
        $this->assertContains('data-column-id="updatedAt"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/portfolio/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
