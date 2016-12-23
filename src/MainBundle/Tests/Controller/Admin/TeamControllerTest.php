<?php

namespace MainBundle\Tests\Controller\Admin;

use AppBundle\Entity\Team;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/create');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_slug"', $crawler->html());
        $this->assertContains('name="create[slug]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/create');

        $form = $crawler->filter('#create-team')->first()->form();
        $crawler = $this->client->submit($form);

        $this->assertContains('Please enter a team name!', $crawler->html());
        $this->assertContains('Please enter a slug!', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testSlugUniqueOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/create');

        $form = $crawler->filter('#create-team')->first()->form();
        $form['create[name]'] = 'team-x';
        $form['create[slug]'] = 'team-1';
        $crawler = $this->client->submit($form);

        $this->assertContains('The provided slug is already used!', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testSlugValidatorOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/create');

        $form = $crawler->filter('#create-team')->first()->form();
        $form['create[name]'] = 'team-x';
        $form['create[slug]'] = 'slu#g';
        $crawler = $this->client->submit($form);

        $this->assertContains('The slug is invalid. Slug must start/end only with alphanumeric values and contain alphanumeric or \'-\' characters', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/create');

        $form = $crawler->filter('#create-team')->first()->form();
        $form['create[name]'] = 'tname';
        $form['create[slug]'] = 'tslug';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Team successfully created!', $this->client->getResponse()->getContent());

        $team = $this
            ->em
            ->getRepository(Team::class)
            ->findOneBy([
                'name' => 'tname',
                'slug' => 'tslug',
            ])
        ;
        $this->em->remove($team);
        $this->em->flush();
    }

    public function testDeleteAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $team = (new Team())
            ->setName('team_test')
            ->setSlug('slug_team_test')
        ;
        $this->em->persist($team);
        $this->em->flush();

        $this->client->request(Request::METHOD_GET, sprintf('/admin/team/%d/delete', $team->getId()));
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        $this->assertContains('Team successfully deleted!', $this->client->getResponse()->getContent());
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/1/edit');

        $this->assertContains('id="create_name"', $crawler->html());
        $this->assertContains('name="create[name]"', $crawler->html());
        $this->assertContains('id="create_slug"', $crawler->html());
        $this->assertContains('name="create[slug]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/1/edit');

        $form = $crawler->filter('#edit-team')->first()->form();
        $form['create[name]'] = '';
        $form['create[slug]'] = 'team-2';

        $crawler = $this->client->submit($form);

        $this->assertContains('Please enter a team name!', $crawler->html());
        $this->assertContains('The provided slug is already used!', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/1/edit');

        $form = $crawler->filter('#edit-team')->first()->form();
        $form['create[name]'] = 'new-team';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Team successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testListActionPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/list');

        $this->assertEquals(1, $crawler->filter('table.table-condensed.table-responsive')->count());
        $this->assertEquals(6, $crawler->filter('table.table-condensed.table-responsive th')->count());
        $this->assertEquals(1, $crawler->filter('.glyphicon-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
