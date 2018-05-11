<?php

namespace MainBundle\Tests\Controller;

use AppBundle\Entity\Team;
use AppBundle\Entity\TeamInvite;
use AppBundle\Entity\TeamMember;
use AppBundle\Entity\User;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamControllerTest extends BaseController
{
    /** @var Team */
    private $team;

    /** @var TeamMember */
    private $teamMember;

    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/team/create');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_enabled"', $crawler->html());
        $this->assertContains('name="create[enabled]"', $crawler->html());
        $this->assertContains('id="create_description"', $crawler->html());
        $this->assertContains('name="create[description]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/team/create');

        $form = $crawler->filter('#create-team')->first()->form();
        $crawler = $this->client->submit($form);

        $this->assertContains('Workspace name should not be blank.', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testUniqueNameOnCreatePage()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->createTeam('test-team', $this->user);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/team/create');

        $form = $crawler->filter('#create-team')->first()->form();
        $form['create[name]'] = 'test-team';
        $crawler = $this->client->submit($form);

        $this->assertContains('Workspace name already used.', $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->removeTeam('test-team');
    }

    public function testCreateSuccessfully()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/team/create');

        $form = $crawler->filter('#create-team')->first()->form();
        $form['create[name]'] = 'test-team';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $crawler = $this->client->followRedirect();

        $this->assertContains('Workspace successfully created!', $crawler->html());
        $this->assertContains('test-team', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $team = $this
            ->em
            ->getRepository(Team::class)
            ->findOneBy([
                'name' => 'test-team',
            ])
        ;
        $teamMember = $this
            ->em
            ->getRepository(TeamMember::class)
            ->findOneBy([
                'user' => $this->user,
                'team' => $team,
            ])
        ;
        $this->em->remove($teamMember);
        $this->em->remove($team);
        $this->em->flush();
    }

    public function testTableIsDisplayedOnListPage()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/team/list');

        $this->assertContains('Id', $crawler->html());
        $this->assertContains('Name', $crawler->html());
        $this->assertContains('Slug', $crawler->html());
        $this->assertContains('Members', $crawler->html());
        $this->assertContains('Enabled', $crawler->html());
        $this->assertContains('Actions', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->createTeam('test-team', $this->user);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/team/%d/edit', $this->team->getId()));

        $this->assertContains('id="edit_name"', $crawler->html());
        $this->assertContains('name="edit[name]"', $crawler->html());
        $this->assertContains('id="edit_enabled"', $crawler->html());
        $this->assertContains('name="edit[enabled]"', $crawler->html());
        $this->assertContains('id="edit_description"', $crawler->html());
        $this->assertContains('name="edit[description]"', $crawler->html());
        $this->assertContains('id="edit_slug"', $crawler->html());
        $this->assertContains('name="edit[slug]"', $crawler->html());
        $this->assertContains('id="edit_logoFile_file"', $crawler->html());
        $this->assertContains('name="edit[logoFile][file]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->removeTeam('test-team');
    }

    public function testFormValidationOnEditPage()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->createTeam('test-team', $this->user);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/team/%d/edit', $this->team->getId()));

        $form = $crawler->filter('#edit-team')->first()->form();
        $form['edit[name]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('Workspace name should not be blank.', $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->removeTeam('test-team');
    }

    public function testUniqueNameOnEditPage()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->createTeam('test-team1', $this->user);
        $this->createTeam('test-team2', $this->user);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/team/%d/edit', $this->team->getId()));

        $form = $crawler->filter('#edit-team')->first()->form();
        $form['edit[name]'] = 'test-team1';

        $crawler = $this->client->submit($form);

        $this->assertContains('Workspace name already used.', $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->removeTeam('test-team1');
        $this->removeTeam('test-team2');
    }

    public function testEditSuccessfully()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->createTeam('test-team', $this->user);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/team/%d/edit', $this->team->getId()));

        $form = $crawler->filter('#edit-team')->first()->form();
        $form['edit[name]'] = 'test-team';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $target = $this->client->getResponse()->getTargetUrl();

        $this->assertEquals(sprintf('/team/%d/show', $this->team->getId()), $target);
        $crawler = $this->client->followRedirect();

        $this->assertContains('Workspace successfully edited!', $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->removeTeam('test-team');
    }

    public function testShowAction()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->createTeam('test-team', $this->user);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/team/%d/show', $this->team->getId()));

        $this->assertContains('Name: <strong>test-team</strong>', $crawler->html());
        $this->assertContains('Slug: <strong>testteam</strong>', $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->removeTeam('test-team');
    }

    public function testFormIsDisplayedOnInviteUserPage()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->createTeam('test-team', $this->user);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/team/%d/invite-user', $this->team->getId()));

        $this->assertContains('id="invite_user_email"', $crawler->html());
        $this->assertContains('name="invite_user[email]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->removeTeam('test-team');
    }

    public function testFormValidationOnInviteUserPage()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->createTeam('test-team', $this->user);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/team/%d/invite-user', $this->team->getId()));

        $form = $crawler->filter('#invite-form')->first()->form();
        $crawler = $this->client->submit($form);

        $this->assertContains('The email field should not be blank', $crawler->html());

        $form = $crawler->filter('#invite-form')->first()->form();
        $form['invite_user[email]'] = 'test';

        $crawler = $this->client->submit($form);

        $this->assertContains('Invalid email provided', $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->removeTeam('test-team');
    }

    public function testSelfInviteOnInviteUserPage()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->createTeam('test-team', $this->user);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/team/%d/invite-user', $this->team->getId()));

        $form = $crawler->filter('#invite-form')->first()->form();
        $form['invite_user[email]'] = $this->user->getEmail();

        $crawler = $this->client->submit($form);

        $this->assertContains('You are already part of the team.', $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->removeTeam('test-team');
    }

    public function testActiveTeamMemberOnInviteUserPage()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->createTeam('test-team', $this->user);

        /**
         * Create new user and add it to current team.
         */
        $user = $this->createUser('teammember', 'teammember@trisoft.ro', 'Password1', ['ROLE_USER']);
        $teamMember = (new TeamMember())
            ->setTeam($this->team)
            ->setUser($user)
            ->setRoles([User::ROLE_USER])
        ;
        $this->em->persist($teamMember);
        $this->em->flush();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/team/%d/invite-user', $this->team->getId()));

        $form = $crawler->filter('#invite-form')->first()->form();
        $form['invite_user[email]'] = $user->getEmail();

        $crawler = $this->client->submit($form);

        $this->assertContains('User with this email is already part of the team.', $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'teammember@trisoft.ro',
            ])
        ;

        $this->em->remove($user);
        $this->removeTeam('test-team');
    }

    public function testInvitationSentOnInviteUserPage()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->createTeam('test-team', $this->user);

        /**
         * Create one invitation.
         */
        $token = uniqid(rand(1000, 9999), true);
        $teamInvite = (new TeamInvite())
            ->setTeam($this->team)
            ->setEmail('teammember@trisoft.ro')
            ->setToken($token)
        ;
        $this->em->persist($teamInvite);
        $this->em->flush();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/team/%d/invite-user', $this->team->getId()));

        $form = $crawler->filter('#invite-form')->first()->form();
        $form['invite_user[email]'] = 'teammember@trisoft.ro';

        $crawler = $this->client->submit($form);

        $this->assertContains('You already sent an invitation to this email.', $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $teamInvite = $this
            ->em
            ->getRepository(TeamInvite::class)
            ->findOneBy([
                'token' => $token,
            ])
        ;
        $this->em->remove($teamInvite);
        $this->removeTeam('test-team');
    }

    public function testInviteUserSuccessfully()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->createTeam('test-team', $this->user);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/team/%d/invite-user', $this->team->getId()));

        $form = $crawler->filter('#invite-form')->first()->form();
        $form['invite_user[email]'] = 'teammember@trisoft.ro';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertContains(sprintf('New invitation sent to user with email %s.', 'teammember@trisoft.ro'), $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $teamInvite = $this
            ->em
            ->getRepository(TeamInvite::class)
            ->findOneBy([
                'email' => 'teammember@trisoft.ro',
                'team' => $this->team,
            ])
        ;
        $this->em->remove($teamInvite);
        $this->removeTeam('test-team');
    }

    public function testInvitationAcceptedAction()
    {
        $this->user = $this->createUser('teamowner', 'teamowner@trisoft.ro', 'Password1', ['ROLE_USER']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $this->createTeam('test-team', $this->user);

        /**
         * Create new user and invitation for him.
         */
        $user = $this->createUser('teammember', 'teammember@trisoft.ro', 'Password1', ['ROLE_USER']);
        $token = uniqid(rand(1000, 9999), true);
        $teamInvite = (new TeamInvite())
            ->setUser($user)
            ->setTeam($this->team)
            ->setToken($token)
        ;
        $this->em->persist($teamInvite);
        $this->em->flush();

        $this->logout();
        $this->login($user);

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/team/invitation-accepted/%s', $teamInvite->getToken()));

        $this->assertContains(sprintf('Congratulation! You are now part of the workspace %s.', $this->team->getName()), $crawler->html());
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'teammember@trisoft.ro',
            ])
        ;
        $teamMember = $this
            ->em
            ->getRepository(TeamMember::class)
            ->findOneBy([
                'user' => $user,
                'team' => $this->team,
            ])
        ;
        $this->em->remove($teamMember);
        $this->em->remove($user);
        $this->removeTeam('test-team');
    }

    private function createTeam($name, User $user)
    {
        $this->team = (new Team())
            ->setUser($user)
            ->setName($name)
        ;
        $this->teamMember = (new TeamMember())
            ->setTeam($this->team)
            ->setUser($user)
            ->setRoles([User::ROLE_SUPER_ADMIN])
        ;

        $this->em->persist($this->team);
        $this->em->persist($this->teamMember);
        $this->em->flush();
    }

    private function removeTeam($name)
    {
        $this->team = $this
            ->em
            ->getRepository(Team::class)
            ->findOneBy([
                'name' => $name,
            ])
        ;
        $this->teamMember = $this
            ->em
            ->getRepository(TeamMember::class)
            ->findOneBy([
                'user' => $this->user,
                'team' => $this->team,
            ])
        ;

        $this->em->remove($this->teamMember);
        $this->em->remove($this->team);
        $this->em->flush();
    }

    protected function tearDown()
    {
        foreach (['team_member', 'team']  as $table) {
            $stmt = $this->em->getConnection()->prepare(sprintf('DELETE FROM %s WHERE deleted_at IS NOT NULL', $table));
            $stmt->execute();
        }
        parent::tearDown();
    }
}
