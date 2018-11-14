<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Media;
use AppBundle\Entity\FileSystem as FileSystemEntity;
use AppBundle\Entity\Project;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaControllerTest extends BaseController
{
    public function testFormIsDisplayedOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/media/create');

        $this->assertContains('id="media_upload_fileSystem"', $crawler->html());
        $this->assertContains('name="media_upload[fileSystem]"', $crawler->html());
        $this->assertContains('id="media_upload_file"', $crawler->html());
        $this->assertContains('name="media_upload[file]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnCreatePage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/media/create');

        $form = $crawler->filter('#create-form')->first()->form();

        $crawler = $this->client->submit($form);

        $this->assertContains('You must select a filesystem', $crawler->html());
        $this->assertContains('You must select a file', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->login();

        $container = self::$kernel->getContainer();

        /**
         * Create a test folder and a file within.
         */
        $fs = new Filesystem();
        try {
            $oldmask = umask(0);
            if (!$fs->exists($container->getParameter('media_upload_folder_test'))) {
                $fs->mkdir($container->getParameter('media_upload_folder_test'));
            }
            if (!$fs->exists($container->getParameter('media_upload_folder_test').'/fs')) {
                $fs->mkdir($container->getParameter('media_upload_folder_test').'/fs');
            }
            if (!$fs->exists($container->getParameter('media_upload_folder_test').'/file2.txt')) {
                $fs->touch($container->getParameter('media_upload_folder_test').'/file2.txt');
            }
            umask($oldmask);
        } catch (IOExceptionInterface $e) {
            sprintf($e->getMessage());
        }
        $file = new File($container->getParameter('media_upload_folder_test').'/file2.txt');
        $fd = $file->openFile('w');
        $fd->fwrite('Test document');
        $fd->fflush();

        /** @var Project $project */
        $project = $this->em->getRepository(Project::class)->find(1);
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/'.$project->getId().'/media/create');

        $form = $crawler->filter('#create-form')->first()->form();
        $form['media_upload[fileSystem]'] = 1;
        $form['media_upload[file]'] = $file;

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Media file successfully created!', $this->client->getResponse()->getContent());

        try {
            $fs->remove($container->getParameter('media_upload_folder').'/file2.txt');
            $fs->remove($container->getParameter('media_upload_folder_test'));
        } catch (IOExceptionInterface $e) {
            sprintf($e->getMessage());
        }
    }

    public function testDeleteAction()
    {
        $this->login();

        $container = self::$kernel->getContainer();

        /** @var Project $project */
        $project = $this->em->getRepository(Project::class)->find(1);
        $fileSystem = (new FileSystemEntity())
            ->setName('fs_test')
            ->setProject($project)
            ->setDriver(FileSystemEntity::LOCAL_ADAPTER)
            ->setConfig(['path' => $container->getParameter('media_upload_folder_test').'/fs']);
        $this->em->persist($project);
        $this->em->persist($fileSystem);

        $fs = new Filesystem();
        try {
            $oldmask = umask(0);
            if (!$fs->exists($container->getParameter('media_upload_folder_test'))) {
                $fs->mkdir($container->getParameter('media_upload_folder_test'));
            }
            if (!$fs->exists($container->getParameter('media_upload_folder_test').'/fs')) {
                $fs->mkdir($container->getParameter('media_upload_folder_test').'/fs');
            }
            if (!$fs->exists($container->getParameter('media_upload_folder_test').'/file2.txt')) {
                $fs->touch($container->getParameter('media_upload_folder_test').'/file2.txt');
            }
            umask($oldmask);
        } catch (IOExceptionInterface $e) {
            sprintf($e->getMessage());
        }
        $file = new File($container->getParameter('media_upload_folder_test').'/file2.txt');

        $media = (new Media())
            ->setFileSystem($fileSystem)
            ->setUser($this->user)
            ->setFile($file)
            ->setPath($file->getFilename())
            ->setMimeType($file->getMimeType())
            ->setFileSize($file->getSize());
        $this->em->persist($media);
        $this->em->flush();

        $this->client->request(
            Request::METHOD_GET,
            sprintf('/admin/project/%d/media/%d/delete', $project->getId(), $media->getId())
        );
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Media file successfully deleted!', $this->client->getResponse()->getContent());

        try {
            $fs->remove($container->getParameter('media_upload_folder_test'));
        } catch (IOExceptionInterface $e) {
            sprintf($e->getMessage());
        }
    }

    public function testFormIsDisplayedOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/media/1/edit');

        $this->assertContains('id="media_upload_fileSystem"', $crawler->html());
        $this->assertContains('name="media_upload[fileSystem]"', $crawler->html());
        $this->assertContains('id="media_upload_file"', $crawler->html());
        $this->assertContains('name="media_upload[file]"', $crawler->html());
        $this->assertContains('type="submit"', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testFormValidationOnEditPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/media/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $crawler = $this->client->submit($form);

        $this->assertContains('You must select a file', $crawler->html());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditPage()
    {
        $this->login();

        $media = $this
            ->em
            ->getRepository(Media::class)
            ->find(1);

        $container = self::$kernel->getContainer();
        $fs = new Filesystem();
        try {
            $oldmask = umask(0);
            if (!$fs->exists($container->getParameter('media_upload_folder_test'))) {
                $fs->mkdir($container->getParameter('media_upload_folder_test'));
            }
            if (!$fs->exists($container->getParameter('media_upload_folder_test').'/file2.txt')) {
                $fs->touch($container->getParameter('media_upload_folder_test').'/file2.txt');
            }
            umask($oldmask);
        } catch (IOExceptionInterface $e) {
            sprintf($e->getMessage());
        }
        $file = new File($container->getParameter('media_upload_folder_test').'/file2.txt');
        $fd = $file->openFile('w');
        $fd->fwrite('Test document');
        $fd->fflush();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/media/1/edit');

        $form = $crawler->filter('#edit-form')->first()->form();
        $form['media_upload[fileSystem]'] = $media->getFileSystem()->getId();
        $form['media_upload[file]'] = $file;

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
        $this->assertContains('Media file successfully edited!', $this->client->getResponse()->getContent());

        try {
            $fs->remove($container->getParameter('media_upload_folder_test'));
        } catch (IOExceptionInterface $e) {
            sprintf($e->getMessage());
        }
    }

    public function testDataTableOnListPage()
    {
        $this->login();

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/media/list');

        $this->assertEquals(1, $crawler->filter('#data-table-command')->count());
        $this->assertContains('data-column-id="id"', $crawler->html());
        $this->assertContains('data-column-id="fileSystemName"', $crawler->html());
        $this->assertContains('data-column-id="userFullName"', $crawler->html());
        $this->assertContains('data-column-id="path"', $crawler->html());
        $this->assertContains('data-column-id="mimeType"', $crawler->html());
        $this->assertContains('data-column-id="fileSize"', $crawler->html());
        $this->assertContains('data-column-id="commands"', $crawler->html());
        $this->assertEquals(1, $crawler->filter('.zmdi-plus')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTableStructureOnShowAction()
    {
        $this->login();

        $crawler = $this->client->request(Request::METHOD_GET, '/admin/project/1/media/1/show');

        $this->assertEquals(1, $crawler->filter('.dropdown-menu-right')->count());
        $this->assertEquals(1, $crawler->filter('.table-responsive')->count());
        $this->assertEquals(1, $crawler->filter('.table-striped')->count());

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testDownloadAction()
    {
        $this->login();

        $container = self::$kernel->getContainer();
        $filename = 'file2.txt';
        $file = new File(sprintf('/tmp/%s', $filename), false);

        try {
            $fs = new Filesystem();
            $oldmask = umask(0);
            if (!$fs->exists($container->getParameter('media_upload_folder_test'))) {
                $fs->mkdir($container->getParameter('media_upload_folder_test'));
            }
            if (!$fs->exists($container->getParameter('media_upload_folder_test').'/file2.txt')) {
                $fs->touch($container->getParameter('media_upload_folder_test').'/file2.txt');
            }
            umask($oldmask);

            $file = new File($container->getParameter('media_upload_folder_test').'/file2.txt');
            $fd = $file->openFile('w');
            $fd->fwrite('Test document');
            $fd->fflush();

            $fileSystem = $this->em->find(FileSystemEntity::class, 1);

            $media = new Media();
            $media->setFile($file);
            $media->setFileSystem($fileSystem);
            $media->setMimeType('plain/text');
            $media->setOriginalName($filename);
            $media->setFileSize($file->getSize());

            $this->em->persist($media);
            $this->em->flush();

            $this->client->request(Request::METHOD_GET, sprintf('/admin/project/media/%d/download', $media->getId()));
            $headers = $this->client->getResponse()->headers->all();

            $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
            $this->assertEquals('mime/type', $headers['content-type'][0]);
            $this->assertEquals(
                sprintf('attachment;filename="%s', $media->getPath()),
                $headers['content-disposition'][0]
            );
            $this->assertEquals('Test document', $this->client->getResponse()->getContent());
        } finally {
            unlink($file->getRealPath());
        }
    }
}
