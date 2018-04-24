<?php

namespace AppBundle\Services;

use AppBundle\Entity\Media;
use Gaufrette\Adapter\Dropbox;
use Gaufrette\Adapter\Local;
use Gaufrette\Filesystem;
use AppBundle\Entity\Filesystem as FileSystemEntity;
use Gaufrette\FilesystemMap;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class FileSystemService
 * Service used to handle operations on Filesystems.
 */
class FileSystemService
{
    private $map;

    /**
     * FileSystemService constructor.
     */
    public function __construct()
    {
        $this->map = new FilesystemMap();
    }

    /**
     * Creates new FileSystem entity based on a selected adapter.
     *
     * @param FileSystemEntity $fileSystem
     *
     * @throws \Exception
     *
     * @return Filesystem
     */
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
            $this->map->set($fileSystem->getSlug(), $fs);

            return $fs;
        }
    }

    /**
     * Returns the map of filesystems.
     *
     * @return FilesystemMap
     */
    public function getFileSystemMap()
    {
        return $this->map;
    }

    /**
     * Saves a media file into a filesystem.
     *
     * @param Media $media
     * @param File  $file
     */
    public function saveMediaFile(Media $media, File $file)
    {
        $path = $this->generateUniqueName($file);
        $fs = $this->getFileSystemMap()->get($media->getFileSystem()->getName());
        $fs->createFile($path);
        $fs->write($path, file_get_contents($file->getPathName()));

        $media->setPath($path);
    }

    /**
     * Removes a specific media from filesystem.
     *
     * @param Media $media
     */
    public function removeMediaFile(Media $media)
    {
        $this
            ->getFileSystemMap()
            ->get($media->getFileSystem()->getName())
            ->delete($media->getPath())
        ;
    }

    /**
     * @param File $file
     *
     * @return string
     */
    private function generateUniqueName(File $file): string
    {
        $filename = $file->getFilename();
        $filename = md5(uniqid($filename, true));
        $ext = $file->guessExtension();
        if ($ext) {
            $filename = sprintf('%s.%s', $filename, $ext);
        }

        return $filename;
    }
}
