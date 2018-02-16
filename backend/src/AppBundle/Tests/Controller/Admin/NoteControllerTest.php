<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Note;
use AppBundle\Entity\Project;
use AppBundle\Entity\Company;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NoteControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->markTestSkipped();
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/note/create');

        $this->assertContains('id="create_title"', $crawler->html());
        $this->assertContains('name="create[title]"', $crawler->html());
        $this->assertContains('id="create_project"', $crawler->html());
        $this->assertContains('name="create[project]"', $crawler->html());
        $this->assertContains('id="create_meeting"', $crawler->html());
        $this->assertContains('name="create[meeting]"', $crawler->html());
        $this->assertContains('id="create_description"', $crawler->html());
        $this->assertContains('name="create[description]"', $crawler->html());
        $this->assertContains('id="create_responsibility"', $crawler->html());
        $this->assertContains('name="create[responsibility]"', $crawler->html());
        $this->assertContains('id="create_date"', $crawler->html());
        $this->assertContains('name="create[date]"', $crawler->html());
        $this->assertContains('id="create_dueDate"', $crawler->html());
        $this->assertContains('name="create[dueDate]"', $crawler->html());
        $this->assertContains('id="create_status"', $crawler->html());
        $this->assertContains('name="create[status]"', $crawler->html());
        $this->assertContains('id="create_showInStatusReport"', $crawler->html());
        $this->assertContains('name="create[showInStatusReport]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->markTestSkipped();
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/note/create');

        $form = $crawler->filter('#create-form')->first()->form();

        $crawler = $this->client->submit($form);

        $this->assertContains('The title field should not be blank', $crawler->html());
        $this->assertContains('The description field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->markTestSkipped();
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $company = (new Company())
            ->setName('company4')
        ;
        $this->em->persist($company);

        $project = (new Project())
            ->setName('project4')
            ->setNumber('project-number-4')
            ->setCompany($company)
        ;

        $this->em->persist($project);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/note/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[title]'] = 'note3';
        $form['create[description]'] = 'description3';
        $form['create[project]'] = $project->getId();

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Note successfully created!', $this->client->getResponse()->getContent());

        $note = $this
            ->em
            ->getRepository(Note::class)
            ->findOneBy([
                'title' => 'note3',
            ])
        ;
        $this->em->remove($note);

        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy([
                'number' => 'project-number-4',
            ])
        ;

        $this->em->remove($project);

        $company = $this
            ->em
            ->getRepository(Company::class)
            ->findOneBy([
                'name' => 'company4',
            ])
        ;
        $this->em->remove($company);

        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->markTestSkipped();
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $company = (new Company())
            ->setName('company4')
        ;
        $this->em->persist($company);

        $project = (new Project())
            ->setName('project4')
            ->setNumber('project-number-4')
            ->setCompany($company)
        ;
        $this->em->persist($project);

        $note = (new Note())
            ->setTitle('note4')
            ->setDescription('description4')
            ->setProject($project)
        ;
        $this->em->persist($note);

        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/note/%d/edit', $note->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Note successfully deleted!', $this->client->getResponse()->getContent());

        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy([
                'number' => 'project-number-4',
            ])
        ;
        $this->em->remove($project);

        $company = $this
            ->em
            ->getRepository(Company::class)
            ->findOneBy([
                'name' => 'company4',
            ])
        ;
        $this->em->remove($company);

        $this->em->flush();
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->markTestSkipped();
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/note/1/edit');

        $this->assertContains('id="create_title"', $crawler->html());
        $this->assertContains('name="create[title]"', $crawler->html());
        $this->assertContains('id="create_project"', $crawler->html());
        $this->assertContains('name="create[project]"', $crawler->html());
        $this->assertContains('id="create_meeting"', $crawler->html());
        $this->assertContains('name="create[meeting]"', $crawler->html());
        $this->assertContains('id="create_description"', $crawler->html());
        $this->assertContains('name="create[description]"', $crawler->html());
        $this->assertContains('id="create_responsibility"', $crawler->html());
        $this->assertContains('name="create[responsibility]"', $crawler->html());
        $this->assertContains('id="create_date"', $crawler->html());
        $this->assertContains('name="create[date]"', $crawler->html());
        $this->assertContains('id="create_dueDate"', $crawler->html());
        $this->assertContains('name="create[dueDate]"', $crawler->html());
        $this->assertContains('id="create_status"', $crawler->html());
        $this->assertContains('name="create[status]"', $crawler->html());
        $this->assertContains('id="create_showInStatusReport"', $crawler->html());
        $this->assertContains('name="create[showInStatusReport]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->markTestSkipped();
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/note/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[title]'] = '';
        $form['create[description]'] = '';
        $crawler = $this->client->submit($form);

        $this->assertContains('The title field should not be blank', $crawler->html());
        $this->assertContains('The description field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->markTestSkipped();
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/note/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[title]'] = 'note1';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Note successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->markTestSkipped();
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/note/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="title"', $crawler->html());
        $this->assertContains('data-column-id="projectName"', $crawler->html());
        $this->assertContains('data-column-id="meetingName"', $crawler->html());
        $this->assertContains('data-column-id="responsibilityFullName"', $crawler->html());
        $this->assertContains('data-column-id="date"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->markTestSkipped();
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/note/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
