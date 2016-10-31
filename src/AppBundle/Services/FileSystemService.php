<?php

namespace AppBundle\Services;

use AppBundle\Entity\Media;
use Gaufrette\Adapter\Dropbox;
use Gaufrette\Adapter\Local;
use Gaufrette\Filesystem;
use AppBundle\Entity\Filesystem as FileSystemEntity;
use Gaufrette\FilesystemMap;
use Symfony\Component\HttpFoundation\File\File;

class FileSystemService
{
    private $map;

    public function __construct()
    {
        $this->map = new FilesystemMap();
    }

    public function createFileSystem(FileSystemEntity $fileSystem)
    {
        $config = $fileSystem->getConfig();
        $adapter = null;
        switch ($fileSystem->getDriver()) {
            case FileSystemEntity::LOCAL_ADAPTER:
                if (isset($config['path'])) {
                    $adapter = new Local($config['path']);
                }
                break;
            case FileSystemEntity::DROPBOX_ADAPTER:
                if (isset($config['key'])
                    && isset($config['secret'])
                    && isset($config['token'])
                    && isset($config['tokenSecret'])
                ) {
                    $dropboxAuth = new \Dropbox_OAuth_Curl($config['key'], $config['secret']);
                    $dropboxAuth->setToken($config['token'], $config['tokenSecret']);
                    $adapter = new Dropbox(new \Dropbox_API($dropboxAuth, 'sandbox'));
                }
                break;
            default:
                throw new \Exception('Filesystem adapter does not exist!');
                break;
        }

        if ($adapter) {
            $fs = new Filesystem($adapter);
            $this->map->set($fileSystem->getName(), $fs);

            return $fs;
        }
    }

    public function getFileSystemMap()
    {
        return $this->map;
    }

    public function saveMediaFile(Media $media, File $file)
    {
        $fs = $this->getFileSystemMap()->get($media->getFileSystem()->getName());
        $fs->createFile($media->getPath());
        $fs->write($media->getPath(), file_get_contents($file->getPathName()));
    }

    public function removeMediaFile(Media $media)
    {
        $this
            ->getFileSystemMap()
            ->get($media->getFileSystem()->getName())
            ->delete($media->getPath())
        ;
    }
}
