<?php

namespace AppBundle\Tests\Services;

use AppBundle\Entity\FileSystem as FileSystemEntity;
use AppBundle\Entity\Media;
use Gaufrette\Filesystem;
use AppBundle\Services\FileSystemService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;

class FileSystemServiceTest extends KernelTestCase
{
    /**
     * @var FileSystemService
     */
    private $fileSystemService;

    protected function setUp()
    {
        $this->fileSystemService = new FileSystemService();
    }

    protected function tearDown()
    {
        unset($this->fileSystemService);
    }

    /**
     * @dataProvider createFileSystemProvider
     *
     * @param FileSystemEntity $fileSystemEntity
     */
    public function testCreateFileSystem(FileSystemEntity $fileSystemEntity)
    {
        $this->assertInstanceOf(
            Filesystem::class,
            $this->fileSystemService->createFileSystem($fileSystemEntity)
        );

        $this->assertCount(
            1,
            $this
                ->fileSystemService
                ->getFileSystemMap()
                ->all()
        );
    }

    /**
     * @dataProvider saveMediaFileProvider
     *
     * @param array $data
     */
    public function testSaveMediaFile(array $data)
    {
        $this->fileSystemService->createFileSystem($data['fileSystem']);
        $fs = $this->fileSystemService->getFileSystemMap()->get('Fs1');
        $this->assertFalse($fs->has($data['file']->getPath()), 'File exists');

        $media = (new Media())
            ->setFileSystem($data['fileSystem'])
            ->setPath($data['file']->getPath())
        ;

        $path = $this->fileSystemService->saveMediaFile($media, $data['file']);

        $this->assertTrue($fs->has($path), 'File does not exists');
    }

    /**
     * @dataProvider createFileSystemProvider
     *
     * @param array $data
     */
    public function removeMediaFile(array $data)
    {
        $fs = $this->fileSystemService->getFileSystemMap()->get('Fs1');
        $this->assertTrue($fs->has($data['path']));

        $media = (new Media())
            ->setFileSystem($data['fileSystem'])
            ->setPath($data['path'])
        ;
        $this->fileSystemService->removeMediaFile($media);
        $this->assertFalse($fs->has(($data['path'])));
    }

    /**
     * @return array
     */
    public function saveMediaFileProvider()
    {
        return [
            [
                [
                    'fileSystem' => (new FileSystemEntity())
                        ->setName('Fs1')
                        ->setDriver('local_adapter')
                        ->setConfig(['path' => sprintf('%s/../Resources/files', __DIR__)]),
                    'file' => new File(
                        sprintf('%s/../Resources/files/%s', __DIR__, 'file.txt'),
                        'file.txt'
                    ),
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function removeMediaFileProvider()
    {
        return [
            [
                [
                    'fileSystem' => (new FileSystemEntity())
                        ->setName('Fs1')
                        ->setDriver('local_adapter')
                        ->setConfig(['path' => sprintf('%s/../Resources/files', __DIR__)]),
                    'path' => sprintf('%s/../Resources/files', __DIR__),
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function createFileSystemProvider()
    {
        return [
            [
                (new FileSystemEntity())
                    ->setName('Fs1')
                    ->setDriver('local_adapter')
                    ->setConfig(['path' => '/folder/path']),
            ],
            [
                (new FileSystemEntity())
                    ->setName('Fs2')
                    ->setDriver('dropbox_adapter')
                    ->setConfig(
                        [
                            'key' => 'dropboxkey',
                            'secret' => 'secretkey',
                            'token' => 'dropboxtoken',
                            'tokenSecret' => 'secrettokenkey',
                        ]
                    ),
            ],
        ];
    }
}
