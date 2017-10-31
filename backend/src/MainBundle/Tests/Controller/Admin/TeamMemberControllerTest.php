<?php

namespace MainBundle\Tests\Controller\Admin;

use AppBundle\Entity\Team;
use AppBundle\Entity\TeamMember;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamMemberControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/1/member/create');

        $this->assertContains('id="create_user"', $crawler->html());
        $this->assertContains('name="create[user]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/1/member/create');

        $form = $crawler->filter('#create-team-member')->first()->form();
        $form['create[user]'] = $this->user->getId();

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Team member successfully created!', $this->client->getResponse()->getContent());

        $teamMember = $this
            ->em
            ->getRepository(TeamMember::class)
            ->findOneBy([
                'user' => $this->user,
            ])
        ;

        $this->em->remove($teamMember);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $team = $this
            ->em
            ->getRepository(Team::class)
            ->find(1)
        ;
        $teamMember = (new TeamMember())
            ->setUser($this->user)
            ->setTeam($team)
        ;
        $this->em->persist($teamMember);
        $this->em->flush();

        $this->client->request(Request::METHOD_GET, sprintf('/admin/team/%d/member/%d/delete', $team->getId(), $teamMember->getId()));
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        $this->assertContains('Team member successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/1/member/1/edit');

        $this->assertContains('id="edit_user"', $crawler->html());
        $this->assertContains('name="edit[user]"', $crawler->html());
        $this->assertContains('id="edit_team"', $crawler->html());
        $this->assertContains('name="edit[team]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $team = $this
            ->em
            ->getRepository(Team::class)
            ->find(2)
        ;

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/1/member/1/edit');

        $form = $crawler->filter('#edit-team-member')->first()->form();
        $form['edit[team]'] = $team->getId();

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Team member successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testListActionPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/1/member/list');

        $this->assertEquals(7, $crawler->filter('div.table-wrapper.table-responsive table.custom-table th')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
