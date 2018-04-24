<?php

namespace AppBundle\Services;

use AppBundle\Entity\FileSystem;
use AppBundle\Entity\Project;
use AppBundle\Repository\FileSystemRepository;

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
     * @param Project $project
     *
     * @return FileSystem
     */
    public function resolve(Project $project)
    {
        $fs = $project
            ->getFileSystems()
            ->filter(
                function (FileSystem $fs) {
                    return FileSystem::LOCAL_ADAPTER === $fs->getDriver();
                }
            )
            ->first()
        ;

        if ($fs) {
            return $fs;
        }

        return $this
            ->repository
            ->findOneBy(
                [
                    'driver' => FileSystem::LOCAL_ADAPTER,
                ]
            )
        ;
    }
}
