<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\MeetingParticipant;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MeetingParticipantControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-participant/create');

        $this->assertContains('id="create_meeting"', $crawler->html());
        $this->assertContains('name="create[meeting]"', $crawler->html());
        $this->assertContains('id="create_user"', $crawler->html());
        $this->assertContains('name="create[user]"', $crawler->html());
        $this->assertContains('id="create_remark"', $crawler->html());
        $this->assertContains('name="create[remark]"', $crawler->html());
        $this->assertContains('id="create_isPresent"', $crawler->html());
        $this->assertContains('name="create[isPresent]"', $crawler->html());
        $this->assertContains('id="create_isExcused"', $crawler->html());
        $this->assertContains('name="create[isExcused]"', $crawler->html());
        $this->assertContains('id="create_inDistributionList"', $crawler->html());
        $this->assertContains('name="create[inDistributionList]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-participant/create');

        $form = $crawler->filter('#create-form')->first()->form();

        $crawler = $this->client->submit($form);

        $this->assertContains('A meeting should be selected', $crawler->html());
        $this->assertContains('An user should be selected', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $user = $this->login();

        /** @var Meeting $meeting */
        $meeting = $this->em->getRepository(Meeting::class)->find(1);

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-participant/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[meeting]'] = $meeting->getId();
        $form['create[user]'] = $user->getId();
        $form['create[remark]'] = 'test-meeting-participant';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Meeting participant successfully created!', $this->client->getResponse()->getContent());

        $meetingParticipant = $this
            ->em
            ->getRepository(MeetingParticipant::class)
            ->findOneBy(
                [
                    'remark' => 'test-meeting-participant',
                ]
            );
        $this->em->remove($meetingParticipant);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->login();

        /** @var Meeting $meeting */
        $meeting = $this->em->getRepository(Meeting::class)->find(1);

        $meetingParticipant = (new MeetingParticipant())
            ->setMeeting($meeting)
            ->setUser($this->user);
        $this->em->persist($meetingParticipant);
        $this->em->flush();

        $this->client->request(
            Request::METHOD_GET,
            sprintf('/admin/meeting-participant/%d/delete', $meetingParticipant->getId())
        );

        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Meeting participant successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-participant/1/edit');

        $this->assertContains('id="create_meeting"', $crawler->html());
        $this->assertContains('name="create[meeting]"', $crawler->html());
        $this->assertContains('id="create_user"', $crawler->html());
        $this->assertContains('name="create[user]"', $crawler->html());
        $this->assertContains('id="create_remark"', $crawler->html());
        $this->assertContains('name="create[remark]"', $crawler->html());
        $this->assertContains('id="create_isPresent"', $crawler->html());
        $this->assertContains('name="create[isPresent]"', $crawler->html());
        $this->assertContains('id="create_isExcused"', $crawler->html());
        $this->assertContains('name="create[isExcused]"', $crawler->html());
        $this->assertContains('id="create_inDistributionList"', $crawler->html());
        $this->assertContains('name="create[inDistributionList]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-participant/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[meeting]'] = '';
        $form['create[user]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('A meeting should be selected', $crawler->html());
        $this->assertContains('An user should be selected', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-participant/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['create[remark]'] = 'remark1';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Meeting participant successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-participant/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="meetingName"', $crawler->html());
        $this->assertContains('data-column-id="userFullName"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-participant/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
