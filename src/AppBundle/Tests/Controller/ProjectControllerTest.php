<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectCategory;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/create');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_number"', $crawler->html());
        $this->assertContains('name="create[number]"', $crawler->html());
        $this->assertContains('id="create_sponsor"', $crawler->html());
        $this->assertContains('name="create[sponsor]"', $crawler->html());
        $this->assertContains('id="create_manager"', $crawler->html());
        $this->assertContains('name="create[manager]"', $crawler->html());
        $this->assertContains('id="create_company"', $crawler->html());
        $this->assertContains('name="create[company]"', $crawler->html());
        $this->assertContains('id="create_projectComplexity"', $crawler->html());
        $this->assertContains('name="create[projectComplexity]"', $crawler->html());
        $this->assertContains('id="create_projectCategory"', $crawler->html());
        $this->assertContains('name="create[projectCategory]"', $crawler->html());
        $this->assertContains('id="create_projectScope"', $crawler->html());
        $this->assertContains('name="create[projectScope]"', $crawler->html());
        $this->assertContains('id="create_status"', $crawler->html());
        $this->assertContains('name="create[status]"', $crawler->html());
        $this->assertContains('id="create_portfolio"', $crawler->html());
        $this->assertContains('name="create[portfolio]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/create');

        $form = $crawler->filter('#create-form')->first()->form();

        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank', $crawler->html());
        $this->assertContains('The number field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testIfNumberIsUniqueOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');
        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy([
                'name' => 'project1',
            ])
        ;

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'project';
        $form['create[number]'] = $project->getNumber();

        $crawler = $this->client->submit($form);

        $this->assertContains('That number is taken', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[name]'] = 'project3';
        $form['create[number]'] = 'project-number-3';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project successfully created!', $this->client->getResponse()->getContent());

        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy([
                'number' => 'project-number-3',
            ])
        ;
        $this->em->remove($project);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $project = (new Project())
            ->setName('project4')
            ->setNumber('project-number-4')
        ;
        $this->em->persist($project);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/project/%d/edit', $project->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/edit');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_number"', $crawler->html());
        $this->assertContains('name="create[number]"', $crawler->html());
        $this->assertContains('id="create_sponsor"', $crawler->html());
        $this->assertContains('name="create[sponsor]"', $crawler->html());
        $this->assertContains('id="create_manager"', $crawler->html());
        $this->assertContains('name="create[manager]"', $crawler->html());
        $this->assertContains('id="create_company"', $crawler->html());
        $this->assertContains('name="create[company]"', $crawler->html());
        $this->assertContains('id="create_projectComplexity"', $crawler->html());
        $this->assertContains('name="create[projectComplexity]"', $crawler->html());
        $this->assertContains('id="create_projectCategory"', $crawler->html());
        $this->assertContains('name="create[projectCategory]"', $crawler->html());
        $this->assertContains('id="create_projectScope"', $crawler->html());
        $this->assertContains('name="create[projectScope]"', $crawler->html());
        $this->assertContains('id="create_status"', $crawler->html());
        $this->assertContains('name="create[status]"', $crawler->html());
        $this->assertContains('id="create_portfolio"', $crawler->html());
        $this->assertContains('name="create[portfolio]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = '';
        $form['create[number]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The name field should not be blank', $crawler->html());
        $this->assertContains('The number field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testIfNumberIsUniqueOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');
        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy([
                'name' => 'project2',
            ])
        ;

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = 'project';
        $form['create[number]'] = $project->getNumber();

        $crawler = $this->client->submit($form);

        $this->assertContains('That number is taken', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/2/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[name]'] = 'project2';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="name"', $crawler->html());
        $this->assertContains('data-column-id="number"', $crawler->html());
        $this->assertContains('data-column-id="projectCategory"', $crawler->html());
        $this->assertContains('data-column-id="portfolio"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
