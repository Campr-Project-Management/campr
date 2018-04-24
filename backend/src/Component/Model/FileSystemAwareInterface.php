<?php

namespace Component\Model;

use AppBundle\Entity\FileSystem;

interface FileSystemAwareInterface
{
    /**
     * @return FileSystem|null
     */
    public function getFileSystem();

    /**
     * @return bool
     */
    public function hasFileSystem();
}
