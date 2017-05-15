<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\SubteamRole;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubteamRoleControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/subteam-role/create');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_description"', $crawler->html());
        $this->assertContains('name="create[description]"', $crawler->html());
        $this->assertContains('id="create_subteamMembers"', $crawler->html());
        $this->assertContains('name="create[subteamMembers][]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/subteam-role/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $crawler = $this->client->submit($form);

        $this->assertContains('The subteam role name must not be empty.', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/subteam-role/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'test role';
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();
        $this->assertContains('Subteam Role successfully created!', $this->client->getResponse()->getContent());

        $subteamRole = $this
            ->em
            ->getRepository(SubteamRole::class)
            ->findOneBy([
                'name' => 'test role',
            ])
        ;
        $this->em->remove($subteamRole);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var SubteamRole $subteamRole */
        $subteamRole = $this->createSubteamRole('test role');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/subteam-role/%d/edit', $subteamRole->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Subteam Role successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var SubteamRole $subteamRole */
        $subteamRole = $this->createSubteamRole('test role');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/subteam-role/%d/edit', $subteamRole->getId()));

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_description"', $crawler->html());
        $this->assertContains('name="create[description]"', $crawler->html());
        $this->assertContains('id="create_subteamMembers"', $crawler->html());
        $this->assertContains('name="create[subteamMembers][]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->em->remove($subteamRole);
        $this->em->flush();

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var SubteamRole $subteamRole */
        $subteamRole = $this->createSubteamRole('test role');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/subteam-role/%d/edit', $subteamRole->getId()));

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = '';
        $crawler = $this->client->submit($form);

        $this->assertContains('The subteam role name must not be empty.', $crawler->html());

        $subteamRole = $this
            ->em
            ->getRepository(SubteamRole::class)
            ->findOneBy([
                'name' => 'test role',
            ])
        ;
        $this->em->remove($subteamRole);
        $this->em->flush();

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var SubteamRole $subteamRole */
        $subteamRole = $this->createSubteamRole('test role');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/subteam-role/%d/edit', $subteamRole->getId()));

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = 'lorem role';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Subteam Role successfully edited!', $this->client->getResponse()->getContent());

        $subteamRole = $this
            ->em
            ->getRepository(SubteamRole::class)
            ->findOneBy([
                'name' => 'lorem role',
            ])
        ;
        $this->em->remove($subteamRole);
        $this->em->flush();

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/subteam-role/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="name"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var SubteamRole $subteamRole */
        $subteamRole = $this->createSubteamRole('test role');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/subteam-role/%d/show', $subteamRole->getId()));

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());
        $this->assertEquals(3, $crawler->filter('.table-striped tr')->count());
        $this->assertContains('test role', $crawler->html());

        $this->em->remove($subteamRole);
        $this->em->flush();

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
