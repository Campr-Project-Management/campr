<?php

namespace AppBundle\Tests\Controller\Admin;

use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectUserControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-user/create');

        $this->assertContains('id="create_user"', $crawler->html());
        $this->assertContains('name="create[user]"', $crawler->html());
        $this->assertContains('id="create_project"', $crawler->html());
        $this->assertContains('name="create[project]"', $crawler->html());
        $this->assertContains('id="create_projectRoles"', $crawler->html());
        $this->assertContains('name="create[projectRoles][]"', $crawler->html());
        $this->assertContains('id="create_projectDepartments"', $crawler->html());
        $this->assertContains('name="create[projectDepartments][]"', $crawler->html());
        $this->assertContains('id="create_projectTeam"', $crawler->html());
        $this->assertContains('name="create[projectTeam]"', $crawler->html());
        $this->assertContains('id="create_showInRasci"', $crawler->html());
        $this->assertContains('name="create[showInRasci]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-user/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank. Choose one user', $crawler->html());
        $this->assertContains('The project field should not be blank. Choose one project', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-user/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[user]'] = 1;
        $form['create[project]'] = 1;

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project user successfully created!', $this->client->getResponse()->getContent());
    }

    public function testDeleteAction()
    {
        $this->login();

        $this->client->request(Request::METHOD_GET, '/admin/project-user/1/delete');
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project user successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-user/1/edit');

        $this->assertContains('id="create_user"', $crawler->html());
        $this->assertContains('name="create[user]"', $crawler->html());
        $this->assertContains('id="create_project"', $crawler->html());
        $this->assertContains('name="create[project]"', $crawler->html());
        $this->assertContains('id="create_projectRoles"', $crawler->html());
        $this->assertContains('name="create[projectRoles][]"', $crawler->html());
        $this->assertContains('id="create_projectDepartments"', $crawler->html());
        $this->assertContains('name="create[projectDepartments][]"', $crawler->html());
        $this->assertContains('id="create_projectTeam"', $crawler->html());
        $this->assertContains('name="create[projectTeam]"', $crawler->html());
        $this->assertContains('id="create_showInRasci"', $crawler->html());
        $this->assertContains('name="create[showInRasci]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-user/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[user]'] = '';
        $form['create[project]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank. Choose one user', $crawler->html());
        $this->assertContains('The project field should not be blank. Choose one project', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-user/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[user]'] = 1;

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project user successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-user/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="userFullName"', $crawler->html());
        $this->assertContains('data-column-id="projectName"', $crawler->html());
        $this->assertContains('data-column-id="projectTeamName"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-user/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
