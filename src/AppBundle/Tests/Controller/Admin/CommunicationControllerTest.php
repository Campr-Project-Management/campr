<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Communication;
use AppBundle\Entity\Project;
use AppBundle\Entity\Schedule;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommunicationControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/communication/create');

        $this->assertContains('id="create_project"', $crawler->html());
        $this->assertContains('name="create[project]"', $crawler->html());
        $this->assertContains('id="create_meetingName"', $crawler->html());
        $this->assertContains('name="create[meetingName]"', $crawler->html());
        $this->assertContains('id="create_location"', $crawler->html());
        $this->assertContains('name="create[location]"', $crawler->html());
        $this->assertContains('id="create_content"', $crawler->html());
        $this->assertContains('name="create[content]"', $crawler->html());
        $this->assertContains('id="create_schedule"', $crawler->html());
        $this->assertContains('name="create[schedule]"', $crawler->html());
        $this->assertContains('id="create_participants"', $crawler->html());
        $this->assertContains('name="create[participants][]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/communication/create');

        $form = $crawler->filter('#create-form')->first()->form();

        $crawler = $this->client->submit($form);

        $this->assertContains('The project field should not be blank. Choose one project', $crawler->html());
        $this->assertContains('The meeting name should not be blank', $crawler->html());
        $this->assertContains('The location should not be blank', $crawler->html());
        $this->assertContains('The schedule field should not be blank. Choose one schedule', $crawler->html());
        $this->assertContains('The content field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $project = (new Project())
            ->setName('project4')
            ->setNumber('project-number-4')
        ;
        $this->em->persist($project);

        $schedule = (new Schedule())
            ->setName('schedule3')
        ;
        $this->em->persist($schedule);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/communication/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[meetingName]'] = 'communication2';
        $form['create[location]'] = 'location2';
        $form['create[content]'] = 'content2';
        $form['create[project]'] = $project->getId();
        $form['create[schedule]'] = $schedule->getId();

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Communication successfully created!', $this->client->getResponse()->getContent());

        $communication = $this
            ->em
            ->getRepository(Communication::class)
            ->findOneBy([
                'meetingName' => 'communication2',
            ])
        ;
        $this->em->remove($communication);

        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy([
                'number' => 'project-number-4',
            ])
        ;
        $this->em->remove($project);

        $schedule = $this
            ->em
            ->getRepository(Schedule::class)
            ->findOneBy([
                'name' => 'schedule3',
            ])
        ;
        $this->em->remove($schedule);
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

        $schedule = (new Schedule())
            ->setName('schedule3')
        ;
        $this->em->persist($schedule);

        $communication = (new Communication())
            ->setProject($project)
            ->setSchedule($schedule)
            ->setMeetingName('communication3')
            ->setContent('content3')
            ->setLocation('location3')
        ;
        $this->em->persist($communication);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/communication/%d/edit', $communication->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Communication successfully deleted!', $this->client->getResponse()->getContent());

        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy([
                'number' => 'project-number-4',
            ])
        ;
        $this->em->remove($project);

        $schedule = $this
            ->em
            ->getRepository(Schedule::class)
            ->findOneBy([
                'name' => 'schedule3',
            ])
        ;
        $this->em->remove($schedule);
        $this->em->flush();
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/communication/1/edit');

        $this->assertContains('id="create_project"', $crawler->html());
        $this->assertContains('name="create[project]"', $crawler->html());
        $this->assertContains('id="create_meetingName"', $crawler->html());
        $this->assertContains('name="create[meetingName]"', $crawler->html());
        $this->assertContains('id="create_location"', $crawler->html());
        $this->assertContains('name="create[location]"', $crawler->html());
        $this->assertContains('id="create_content"', $crawler->html());
        $this->assertContains('name="create[content]"', $crawler->html());
        $this->assertContains('id="create_schedule"', $crawler->html());
        $this->assertContains('name="create[schedule]"', $crawler->html());
        $this->assertContains('id="create_participants"', $crawler->html());
        $this->assertContains('name="create[participants][]"', $crawler->html());
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
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/communication/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[project]'] = '';
        $form['create[schedule]'] = '';
        $form['create[meetingName]'] = '';
        $form['create[content]'] = '';
        $form['create[location]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The project field should not be blank. Choose one project', $crawler->html());
        $this->assertContains('The meeting name should not be blank', $crawler->html());
        $this->assertContains('The location should not be blank', $crawler->html());
        $this->assertContains('The schedule field should not be blank. Choose one schedule', $crawler->html());
        $this->assertContains('The content field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/communication/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[meetingName]'] = 'communication1';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Communication successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/communication/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="meetingName"', $crawler->html());
        $this->assertContains('data-column-id="projectName"', $crawler->html());
        $this->assertContains('data-column-id="location"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/communication/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
