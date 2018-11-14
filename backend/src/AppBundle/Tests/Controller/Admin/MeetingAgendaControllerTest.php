<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\MeetingAgenda;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MeetingAgendaControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-agenda/create');

        $this->assertContains('id="create_meeting"', $crawler->html());
        $this->assertContains('name="create[meeting]"', $crawler->html());
        $this->assertContains('id="create_topic"', $crawler->html());
        $this->assertContains('name="create[topic]"', $crawler->html());
        $this->assertContains('id="create_responsibility"', $crawler->html());
        $this->assertContains('name="create[responsibility]"', $crawler->html());
        $this->assertContains('id="create_duration"', $crawler->html());
        $this->assertContains('name="create[duration]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-agenda/create');

        $meeting = (new Meeting())
            ->setName('meeting3')
            ->setLocation('meeting-location')
            ->setDate(new \DateTime())
            ->setStart(new \DateTime())
            ->setEnd(new \DateTime('+1 hour'));

        $form = $crawler->filter('#create-form')->first()->form();

        $form['create[meeting]'] = $meeting->getId();

        $crawler = $this->client->submit($form);

        $this->assertContains('The topic field should not be blank', $crawler->html());
        $this->assertContains('The duration field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteAction()
    {
        $this->login();

        /** @var Meeting $meeting */
        $meeting = $this->em->getRepository(Meeting::class)->find(1);
        $start = new \DateTime();

        $meetingAgenda = (new MeetingAgenda())
            ->setTopic('topic4')
            ->setStart($start)
            ->setMeeting($meeting);
        $this->em->persist($meetingAgenda);
        $this->em->flush();

        $this->client->request(
            Request::METHOD_GET,
            sprintf('/admin/meeting-agenda/%d/delete', $meetingAgenda->getId())
        );
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Meeting agenda successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testCreateAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-agenda/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[meeting]'] = 1;
        $form['create[topic]'] = 'topic3';
        $form['create[duration]'] = 0;

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Meeting agenda successfully created!', $this->client->getResponse()->getContent());

        $meetingAgenda = $this
            ->em
            ->getRepository(MeetingAgenda::class)
            ->findOneBy(
                [
                    'topic' => 'topic3',
                ]
            );
        $this->em->remove($meetingAgenda);
        $this->em->flush();
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-agenda/1/edit');

        $this->assertContains('id="create_meeting"', $crawler->html());
        $this->assertContains('name="create[meeting]"', $crawler->html());
        $this->assertContains('id="create_topic"', $crawler->html());
        $this->assertContains('name="create[topic]"', $crawler->html());
        $this->assertContains('id="create_responsibility"', $crawler->html());
        $this->assertContains('name="create[responsibility]"', $crawler->html());
        $this->assertContains('id="create_duration"', $crawler->html());
        $this->assertContains('name="create[duration]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-agenda/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[topic]'] = '';
        $form['create[duration]'] = '';
        $crawler = $this->client->submit($form);

        $this->assertContains('The topic field should not be blank', $crawler->html());
        $this->assertContains('The duration field should not be blank', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-agenda/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[topic]'] = 'topic1';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Meeting agenda successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-agenda/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="meetingName"', $crawler->html());
        $this->assertContains('data-column-id="topic"', $crawler->html());
        $this->assertContains('data-column-id="responsibilityFullName"', $crawler->html());
        $this->assertContains('data-column-id="start"', $crawler->html());
        $this->assertContains('data-column-id="duration"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-agenda/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
