<?php

namespace AppBundle\Services;

use AppBundle\Entity\FileSystem;
use AppBundle\Repository\FileSystemRepository;
use Component\Model\FileSystemAwareInterface;

class FileSystemResolver
{
    /**
     * @var FileSystemRepository
     */
    private $repository;

    /**
     * FileSystemResolver constructor.
     *
     * @param FileSystemRepository $repository
     */
    public function __construct(FileSystemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FileSystemAwareInterface $object
     *
     * @return FileSystem
     */
    public function resolve(FileSystemAwareInterface $object)
    {
        $fs = $object->getFileSystem();
        if (!$fs) {
            $fs = $this->getDefaultFileSystem();
        }

        return $fs;
    }

    /**
     * @return FileSystem
     */
    private function getDefaultFileSystem(): FileSystem
    {
        /** @var FileSystem $fs */
        $fs = $this
            ->repository
            ->findOneBy(
                [
                    'driver' => FileSystem::LOCAL_ADAPTER,
                ]
            )
        ;

        return $fs;
    }
}
