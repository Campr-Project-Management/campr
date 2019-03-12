<?php

namespace AppBundle\Services;

use AppBundle\Entity\Media;
use AppBundle\Entity\Filesystem as FileSystemEntity;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\MountManager;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;
use Symfony\Component\HttpFoundation\File\File;
use Webmozart\Assert\Assert;

/**
 * Class FileSystemService.
 */
class FileSystemService
{
    /**
     * @var MountManager
     */
    private $mountManager;

    /**
     * FileSystemService constructor.
     */
    public function __construct()
    {
        $this->mountManager = new MountManager();
    }

    /**
     * @param FileSystemEntity $fs
     *
     * @return FilesystemInterface
     */
    public function createFilesystem(FileSystemEntity $fs): FilesystemInterface
    {
        $driver = $fs->getDriver();
        $config = $fs->getConfig();

        if (FileSystemEntity::LOCAL_ADAPTER === $driver) {
            $filesystem = $this->createLocalFilesystem($config['path'] ?? '');
            $this->mountManager->mountFilesystem($this->getMountPrefix($fs), $filesystem);

            return $filesystem;
        }

        if (FileSystemEntity::DROPBOX_ADAPTER === $driver) {
            $filesystem = $this->createDropboxFilesystem($config['accessToken']);
            $this->mountManager->mountFilesystem($this->getMountPrefix($fs), $filesystem);

            return $filesystem;
        }

        throw new \InvalidArgumentException('Unknown file system driver');
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
            $path = $this->generateUniquePath($media);
        }

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
     *
     * @return string
     */
    private function generateUniquePath(Media $media): string
    {
        $dirname = dirname($media->getPath());
        if ('.' === $dirname) {
            $dirname = null;
        }

        $filename = basename($media->getPath());
        if ($media->getOriginalName()) {
            $filename = $media->getOriginalName();
        }

        $index = strrpos($filename, '.');
        $extension = substr($filename, $index + 1);

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
     * @return FilesystemInterface
     */
    private function getFileSystem(Media $media): FilesystemInterface
    {
        return $this->mountManager->getFilesystem($this->getMountPrefix($media->getFileSystem()));
    }

    /**
     * @param FileSystemEntity $fs
     *
     * @return string
     */
    private function getMountPrefix(FileSystemEntity $fs): string
    {
        return sprintf('%s-%d', $fs->getSlug(), $fs->getId());
    }

    /**
     * @param string $path
     *
     * @return FilesystemInterface
     */
    private function createLocalFilesystem(string $path): FilesystemInterface
    {
        Assert::notEmpty($path, 'Local file system path is missing');

        $adapter = new Local($path);

        return new Filesystem($adapter);
    }

    /**
     * @param string $accessToken
     *
     * @return FilesystemInterface
     */
    private function createDropboxFilesystem(string $accessToken): FilesystemInterface
    {
        Assert::notEmpty($accessToken, 'Dropbox access token is missing');

        $client = new Client($accessToken);
        $adapter = new DropboxAdapter($client);

        return new Filesystem($adapter, ['case_sensitive' => false]);
    }
}
