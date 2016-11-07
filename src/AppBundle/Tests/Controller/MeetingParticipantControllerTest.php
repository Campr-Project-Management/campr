<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\MeetingParticipant;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MeetingParticipantControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-participant/create');

        $this->assertContains('id="create_meeting"', $crawler->html());
        $this->assertContains('name="create[meeting]"', $crawler->html());
        $this->assertContains('id="create_user"', $crawler->html());
        $this->assertContains('name="create[user]"', $crawler->html());
        $this->assertContains('id="create_remark"', $crawler->html());
        $this->assertContains('name="create[remark]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $meeting = (new Meeting())
            ->setName('meeting3')
            ->setLocation('meeting-location')
            ->setObjectives('meeting-objectives')
            ->setDate(new \DateTime())
            ->setStart(new \DateTime())
            ->setEnd(new \DateTime('+1 hour'))
        ;
        $this->em->persist($meeting);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-participant/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[meeting]'] = $meeting->getId();
        $form['create[user]'] = $this->user->getId();
        $form['create[remark]'] = 'test-meeting-participant';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Meeting participant successfully created!', $this->client->getResponse()->getContent());

        $meeting = $this
            ->em
            ->getRepository(Meeting::class)
            ->findOneBy([
                'name' => 'meeting3',
            ])
        ;
        $meetingParticipant = $this
            ->em
            ->getRepository(MeetingParticipant::class)
            ->findOneBy([
                'remark' => 'test-meeting-participant',
            ])
        ;
        $this->em->remove($meetingParticipant);
        $this->em->remove($meeting);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $meeting = (new Meeting())
            ->setName('meeting3')
            ->setLocation('meeting-location')
            ->setObjectives('meeting-objectives')
            ->setDate(new \DateTime())
            ->setStart(new \DateTime())
            ->setEnd(new \DateTime('+1 hour'))
        ;
        $this->em->persist($meeting);

        $meetingParticipant = (new MeetingParticipant())
            ->setMeeting($meeting)
            ->setUser($this->user)
        ;
        $this->em->persist($meetingParticipant);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/meeting-participant/%d/edit', $meetingParticipant->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Meeting participant successfully deleted!', $this->client->getResponse()->getContent());

        $meeting = $this
            ->em
            ->getRepository(Meeting::class)
            ->findOneBy([
                'name' => 'meeting3',
            ])
        ;
        $this->em->remove($meeting);
        $this->em->flush();
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-participant/1/edit');

        $this->assertContains('id="edit_meeting"', $crawler->html());
        $this->assertContains('name="edit[meeting]"', $crawler->html());
        $this->assertContains('id="edit_user"', $crawler->html());
        $this->assertContains('name="edit[user]"', $crawler->html());
        $this->assertContains('id="edit_remark"', $crawler->html());
        $this->assertContains('name="edit[remark]"', $crawler->html());
        $this->assertContains('id="edit_isPresent"', $crawler->html());
        $this->assertContains('name="edit[isPresent]"', $crawler->html());
        $this->assertContains('id="edit_isExcused"', $crawler->html());
        $this->assertContains('name="edit[isExcused]"', $crawler->html());
        $this->assertContains('id="edit_inDistributionList"', $crawler->html());
        $this->assertContains('name="edit[inDistributionList]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());
        $this->assertContains('class="zmdi zmdi-delete"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-participant/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['edit[remark]'] = 'remark1';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Meeting participant successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-participant/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="meeting"', $crawler->html());
        $this->assertContains('data-column-id="user"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/meeting-participant/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
