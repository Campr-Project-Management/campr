<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\ProjectDepartment;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectDepartmentControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-department/create');

        $this->assertContains('id="admin_name"', $crawler->html());
        $this->assertContains('name="admin[name]"', $crawler->html());
        $this->assertContains('id="admin_abbreviation"', $crawler->html());
        $this->assertContains('name="admin[abbreviation]"', $crawler->html());
        $this->assertContains('id="admin_sequence"', $crawler->html());
        $this->assertContains('name="admin[sequence]"', $crawler->html());
        $this->assertContains('id="admin_rate"', $crawler->html());
        $this->assertContains('name="admin[rate]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-department/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['admin[sequence]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('This value should not be blank.', $crawler->html());
        $this->assertContains('The abbreviation should not be blank', $crawler->html());
        $this->assertContains('The sequence field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testSequenceIsValidOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-department/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['admin[name]'] = 'project-department';
        $form['admin[abbreviation]'] = 'abbreviation';
        $form['admin[sequence]'] = 'sequence';

        $crawler = $this->client->submit($form);

        $this->assertContains('The sequence field should contain numbers greater than or equal to 0', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-department/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['admin[name]'] = 'project-department3';
        $form['admin[abbreviation]'] = 'pd3';
        $form['admin[sequence]'] = '3';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project department successfully created!', $this->client->getResponse()->getContent());

        $projectDepartment = $this
            ->em
            ->getRepository(ProjectDepartment::class)
            ->findOneBy([
                'name' => 'project-department3',
            ])
        ;
        $this->em->persist($projectDepartment);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $projectDepartment = (new ProjectDepartment())
            ->setName('project-department4')
            ->setAbbreviation('pd4')
            ->setSequence('1')
        ;
        $this->em->persist($projectDepartment);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/project-department/%d/edit', $projectDepartment->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project department successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-department/1/edit');

        $this->assertContains('id="admin_name"', $crawler->html());
        $this->assertContains('name="admin[name]"', $crawler->html());
        $this->assertContains('id="admin_abbreviation"', $crawler->html());
        $this->assertContains('name="admin[abbreviation]"', $crawler->html());
        $this->assertContains('id="admin_sequence"', $crawler->html());
        $this->assertContains('name="admin[sequence]"', $crawler->html());
        $this->assertContains('id="admin_rate"', $crawler->html());
        $this->assertContains('name="admin[rate]"', $crawler->html());
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
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-department/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['admin[name]'] = '';
        $form['admin[sequence]'] = '';
        $form['admin[abbreviation]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('This value should not be blank.', $crawler->html());
        $this->assertContains('The abbreviation should not be blank', $crawler->html());
        $this->assertContains('The sequence field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testSequenceIsValidOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-department/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['admin[sequence]'] = 'sequence';

        $crawler = $this->client->submit($form);

        $this->assertContains('The sequence field should contain numbers greater than or equal to 0', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-department/2/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['admin[name]'] = 'project-department2';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Project department successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-department/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="name"', $crawler->html());
        $this->assertContains('data-column-id="projectName"', $crawler->html());
        $this->assertContains('data-column-id="abbreviation"', $crawler->html());
        $this->assertContains('data-column-id="sequence"', $crawler->html());
        $this->assertContains('data-column-id="rate"', $crawler->html());
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

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project-department/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
