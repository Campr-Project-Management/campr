<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Subteam;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MainBundle\Tests\Controller\BaseController;

class SubteamControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('subteam_user', 'subteam_user@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/subteam/create');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_description"', $crawler->html());
        $this->assertContains('name="create[description]"', $crawler->html());
        $this->assertContains('id="create_project"', $crawler->html());
        $this->assertContains('name="create[project]"', $crawler->html());
        $this->assertContains('id="create_parent"', $crawler->html());
        $this->assertContains('name="create[parent]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('subteam_user', 'subteam_user@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/subteam/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $crawler = $this->client->submit($form);

        $this->assertContains('The subteam name should not be blank.', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('subteam_user', 'subteam_user@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/subteam/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'Generic team';
        $form['create[description]'] = 'Lorem ipsum dolor sit amet';
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();
        $this->assertContains('Subteam successfully created!', $this->client->getResponse()->getContent());

        /** @var Subteam $subteam */
        $subteam = $this
            ->em
            ->getRepository(Subteam::class)
            ->findOneBy([
                'name' => 'Generic team',
            ])
        ;
        $this->em->remove($subteam);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('subteam_user', 'subteam_user@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Subteam $subteam */
        $subteam = $this->createSubteam('Generic name', 'Lorem ipsum dolor sit amet');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/subteam/%d/edit', $subteam->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Subteam successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('subteam_user', 'subteam_user@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Subteam $subteam */
        $subteam = $this->createSubteam('Generic name', 'Lorem ipsum dolor sit amet');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/subteam/%d/edit', $subteam->getId()));

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_description"', $crawler->html());
        $this->assertContains('name="create[description]"', $crawler->html());
        $this->assertContains('id="create_project"', $crawler->html());
        $this->assertContains('name="create[project]"', $crawler->html());
        $this->assertContains('id="create_parent"', $crawler->html());
        $this->assertContains('name="create[parent]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->em->remove($subteam);
        $this->em->flush();

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->user = $this->createUser('subteam_user', 'subteam_user@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Subteam $subteam */
        $subteam = $this->createSubteam('Generic name', 'Lorem ipsum dolor sit amet');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/subteam/%d/edit', $subteam->getId()));

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = '';
        $form['create[description]'] = '';
        $crawler = $this->client->submit($form);

        $this->assertContains('The subteam name should not be blank.', $crawler->html());

        $subteam = $this
            ->em
            ->getRepository(Subteam::class)
            ->findOneBy([
                'id' => $subteam->getId(),
            ])
        ;
        $this->em->remove($subteam);
        $this->em->flush();

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('subteam_user', 'subteam_user@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Subteam $subteam */
        $subteam = $this->createSubteam('Generic name', 'Lorem ipsum dolor sit amet');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/subteam/%d/edit', $subteam->getId()));

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = 'Test';
        $form['create[description]'] = 'Test';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Subteam successfully edited!', $this->client->getResponse()->getContent());

        $subteam = $this
            ->em
            ->getRepository(Subteam::class)
            ->findOneBy([
                'id' => $subteam->getId(),
            ])
        ;
        $this->em->remove($subteam);
        $this->em->flush();

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('subteam_user', 'subteam_user@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/subteam/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="name"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->user = $this->createUser('subteam_user', 'subteam_user@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Subteam $subteam */
        $subteam = $this->createSubteam('Generic name', 'Lorem ipsum dolor sit amet');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/subteam/%d/show', $subteam->getId()));

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());
        $this->assertEquals(5, $crawler->filter('.table-striped tr')->count());
        $this->assertContains('Generic name', $crawler->html());
        $this->assertContains('Lorem ipsum dolor sit amet', $crawler->html());

        $this->em->remove($subteam);
        $this->em->flush();

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
