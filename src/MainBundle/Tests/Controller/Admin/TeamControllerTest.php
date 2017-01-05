<?php

namespace MainBundle\Tests\Controller\Admin;

use AppBundle\Entity\Team;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
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
        $this->assertContains('id="create_logoFile_file"', $crawler->html());
        $this->assertContains('name="create[logoFile][file]"', $crawler->html());
        $this->assertContains('id="create_description"', $crawler->html());
        $this->assertContains('name="create[description]"', $crawler->html());
        $this->assertContains('id="create_enabled"', $crawler->html());
        $this->assertContains('name="create[enabled]"', $crawler->html());
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

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormFileValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/create');

        $form = $crawler->filter('#create-team')->first()->form();
        $form['create[name]'] = 'tname';

        $container = self::$kernel->getContainer();
        $fs = new Filesystem();
        try {
            $oldmask = umask(0);
            if (!$fs->exists($container->getParameter('media_upload_folder_test'))) {
                $fs->mkdir($container->getParameter('media_upload_folder_test'));
            }
            if (!$fs->exists($container->getParameter('media_upload_folder_test').'/team_logo.txt')) {
                $fs->touch($container->getParameter('media_upload_folder_test').'/team_logo.txt');
            }
            umask($oldmask);
        } catch (IOExceptionInterface $e) {
            sprintf($e->getMessage());
        }
        $file = new File($container->getParameter('media_upload_folder_test').'/team_logo.txt');
        $fd = $file->openFile('w');
        $fd->fwrite('Test document');
        $fd->fflush();
        $form['create[logoFile][file]'] = $file;

        $crawler = $this->client->submit($form);

        $this->assertContains('Uploaded file must be an image!', $crawler->html());

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
        $form['create[description]'] = 'descript';

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Team successfully created!', $this->client->getResponse()->getContent());

        $team = $this
            ->em
            ->getRepository(Team::class)
            ->findOneBy([
                'name' => 'tname',
                'description' => 'descript',
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
            ->setDescription('description team test')
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

        $this->assertContains('id="edit_name"', $crawler->html());
        $this->assertContains('name="edit[name]"', $crawler->html());
        $this->assertContains('id="edit_slug"', $crawler->html());
        $this->assertContains('name="edit[slug]"', $crawler->html());
        $this->assertContains('id="edit_logoFile_file"', $crawler->html());
        $this->assertContains('name="edit[logoFile][file]"', $crawler->html());
        $this->assertContains('id="edit_description"', $crawler->html());
        $this->assertContains('name="edit[description]"', $crawler->html());
        $this->assertContains('id="edit_enabled"', $crawler->html());
        $this->assertContains('name="edit[enabled]"', $crawler->html());
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
        $form['edit[name]'] = '';
        $form['edit[slug]'] = 'team-2';

        $crawler = $this->client->submit($form);

        $this->assertContains('Please enter a team name!', $crawler->html());
        $this->assertContains('The provided slug is already used!', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormFileValidationOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/1/edit');

        $form = $crawler->filter('#edit-team')->first()->form();

        $container = self::$kernel->getContainer();
        $fs = new Filesystem();
        try {
            $oldmask = umask(0);
            if (!$fs->exists($container->getParameter('media_upload_folder_test'))) {
                $fs->mkdir($container->getParameter('media_upload_folder_test'));
            }
            if (!$fs->exists($container->getParameter('media_upload_folder_test').'/team_logo.txt')) {
                $fs->touch($container->getParameter('media_upload_folder_test').'/team_logo.txt');
            }
            umask($oldmask);
        } catch (IOExceptionInterface $e) {
            sprintf($e->getMessage());
        }
        $file = new File($container->getParameter('media_upload_folder_test').'/team_logo.txt');
        $fd = $file->openFile('w');
        $fd->fwrite('Test document');
        $fd->fflush();
        $form['edit[logoFile][file]'] = $file;

        $crawler = $this->client->submit($form);

        $this->assertContains('Uploaded file must be an image!', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/team/1/edit');

        $form = $crawler->filter('#edit-team')->first()->form();
        $form['edit[name]'] = 'new-team';

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
        $this->assertEquals(7, $crawler->filter('table.table-condensed.table-responsive th')->count());
        $this->assertEquals(1, $crawler->filter('.glyphicon-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
