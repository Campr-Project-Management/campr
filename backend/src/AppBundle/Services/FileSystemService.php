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
     * @param Media $media
     * @param File  $file
     *
     * @return string
     */
    public function saveMediaFile(Media $media, File $file)
    {
        $path = $media->getPath();
        $fs = $this->getFileSystem($media);
        if ($fs->has($path)) {
            $path = $this->generateUniquePath($media, $file);
        }

        $fs->createFile($path);
        $fs->write($path, file_get_contents($file->getPathName()));

        $media->setPath($path);

        return $path;
    }

    /**
     * @param Media $media
     *
     * @return bool
     */
    public function removeMediaFile(Media $media)
    {
        return $this->getFileSystem($media)->delete($media->getPath());
    }

    /**
     * @param Media $media
     * @param File  $file
     *
     * @return string
     */
    private function generateUniquePath(Media $media, File $file): string
    {
        $dirname = dirname($media->getPath());
        if ('.' === $dirname) {
            $dirname = null;
        }

        $filename = basename($media->getPath());
        $extension = $file->guessExtension();
        if (!empty($extension)) {
            $filename = str_replace(sprintf('.%s', $extension), '', $filename);
        }

        $fs = $this->getFileSystem($media);
        $i = 5;
        while ($i > 0) {
            $id = substr(md5(uniqid(sprintf('%s_', $filename), true)), 0, 12);
            $filename = sprintf('%s_%s', $filename, $id);
            if (!empty($extension)) {
                $filename = sprintf('%s.%s', $filename, $extension);
            }

            $path = implode(DIRECTORY_SEPARATOR, array_filter([$dirname, $filename]));
            if (!$fs->has($path)) {
                return $path;
            }

            --$i;
        }

        throw new \RuntimeException('Cannot generate unique file path');
    }

    /**
     * @param Media $media
     *
     * @return Filesystem
     */
    private function getFileSystem(Media $media): Filesystem
    {
        return $this->getFileSystemMap()->get($media->getFileSystem()->getName());
    }
}
