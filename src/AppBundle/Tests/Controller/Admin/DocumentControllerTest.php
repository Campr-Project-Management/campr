<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Document;
use AppBundle\Entity\Project;
use AppBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/document/create');

        $this->assertContains('id="create_project"', $crawler->html());
        $this->assertContains('name="create[project]"', $crawler->html());
        $this->assertContains('id="create_meetings"', $crawler->html());
        $this->assertContains('name="create[meetings][]"', $crawler->html());
        $this->assertContains('id="create_file"', $crawler->html());
        $this->assertContains('name="create[file]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/document/create');

        $form = $crawler->filter('#create-form')->first()->form();

        $crawler = $this->client->submit($form);

        $this->assertContains('The project field should not be blank. Choose one project', $crawler->html());
        $this->assertContains('A document should be selected', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testUniqueAndEmptyFileOnCreatePage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $container = self::$kernel->getContainer();

        $fs = new Filesystem();
        try {
            $oldmask = umask(0);
            if (!$fs->exists($container->getParameter('documents_upload_folder'))) {
                $fs->mkdir($container->getParameter('documents_upload_folder'));
            }
            if (!$fs->exists($container->getParameter('documents_upload_folder').'/file1.txt')) {
                $fs->touch($container->getParameter('documents_upload_folder').'/file1.txt');
            }
            umask($oldmask);
        } catch (IOExceptionInterface $e) {
            sprintf($e->getMessage());
        }
        $file = new File($container->getParameter('documents_upload_folder').'/file1.txt');

        $project = (new Project())
            ->setName('project4')
            ->setNumber('project-number-4')
        ;
        $this->em->persist($project);
        $this->em->flush();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/document/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[project]'] = $project->getId();
        $form['create[file]'] = $file;

        $crawler = $this->client->submit($form);

        $this->assertContains('Uploaded document already exists in documents folder.', $crawler->html());
        $this->assertContains('An empty file is not allowed.', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy([
                'number' => 'project-number-4',
            ])
        ;
        $this->em->remove($project);

        $this->em->flush();
    }

    public function testCreateAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $container = self::$kernel->getContainer();

        /**
         * Create a test folder and a file within.
         */
        $fs = new Filesystem();
        try {
            $oldmask = umask(0);
            if (!$fs->exists($container->getParameter('documents_upload_folder_test'))) {
                $fs->mkdir($container->getParameter('documents_upload_folder_test'));
            }
            if (!$fs->exists($container->getParameter('documents_upload_folder_test').'/file2.txt')) {
                $fs->touch($container->getParameter('documents_upload_folder_test').'/file2.txt');
            }
            umask($oldmask);
        } catch (IOExceptionInterface $e) {
            sprintf($e->getMessage());
        }
        $file = new File($container->getParameter('documents_upload_folder_test').'/file2.txt');
        $fd = $file->openFile('w');
        $fd->fwrite('Test document');
        $fd->fflush();

        $project = (new Project())
            ->setName('project4')
            ->setNumber('project-number-4')
        ;
        $this->em->persist($project);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/document/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['create[project]'] = $project->getId();
        $form['create[file]'] = $file;

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Document successfully created!', $this->client->getResponse()->getContent());

        $document = $this
            ->em
            ->getRepository(Document::class)
            ->findOneBy([
                'fileName' => 'file2.txt',
            ])
        ;
        $this->em->remove($document);

        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy([
                'number' => 'project-number-4',
            ])
        ;
        $this->em->remove($project);
        $this->em->flush();

        try {
            $fs->remove($container->getParameter('documents_upload_folder').'/file2.txt');
            $fs->remove($container->getParameter('documents_upload_folder_test'));
        } catch (IOExceptionInterface $e) {
            sprintf($e->getMessage());
        }
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

        $container = self::$kernel->getContainer();

        $fs = new Filesystem();
        try {
            $oldmask = umask(0);
            if (!$fs->exists($container->getParameter('documents_upload_folder_test'))) {
                $fs->mkdir($container->getParameter('documents_upload_folder_test'));
            }
            if (!$fs->exists($container->getParameter('documents_upload_folder_test').'/file3.txt')) {
                $fs->touch($container->getParameter('documents_upload_folder_test').'/file3.txt');
            }
            umask($oldmask);
        } catch (IOExceptionInterface $e) {
            sprintf($e->getMessage());
        }
        $file = new File($container->getParameter('documents_upload_folder_test').'/file3.txt');
        $fileName = (preg_split('/[.]/', $file->getFilename()))[0];

        $document = (new Document())
            ->setProject($project)
            ->setFile($file)
            ->setFileName($fileName)
        ;
        $this->em->persist($document);
        $this->em->flush();

        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/admin/document/%d/edit', $document->getId()));

        $link = $crawler->selectLink('Delete')->link();
        $this->client->click($link);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Document successfully deleted!', $this->client->getResponse()->getContent());

        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy([
                'number' => 'project-number-4',
            ])
        ;
        $this->em->remove($project);
        $this->em->flush();

        try {
            $fs->remove($container->getParameter('documents_upload_folder_test'));
        } catch (IOExceptionInterface $e) {
            sprintf($e->getMessage());
        }
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/document/1/edit');

        $this->assertContains('id="edit_project"', $crawler->html());
        $this->assertContains('name="edit[project]"', $crawler->html());
        $this->assertContains('id="edit_meetings"', $crawler->html());
        $this->assertContains('name="edit[meetings][]"', $crawler->html());
        $this->assertContains('id="edit_file"', $crawler->html());
        $this->assertContains('name="edit[file]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/document/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['edit[project]'] = '';

        $crawler = $this->client->submit($form);

        $this->assertContains('The project field should not be blank. Choose one project', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $document = $this
            ->em
            ->getRepository(Document::class)
            ->find(1)
        ;

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/document/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['edit[project]'] = $document->getProject()->getId();

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Document successfully edited!', $this->client->getResponse()->getContent());
    }

    public function testDataTableOnListPage()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/document/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="project"', $crawler->html());
        $this->assertContains('data-column-id="fileName"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);
        $this->assertNotNull($this->user, 'User not found');

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/document/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
