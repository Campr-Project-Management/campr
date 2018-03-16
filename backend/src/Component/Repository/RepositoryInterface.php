<?php

namespace Component\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface RepositoryInterface extends ObjectRepository
{
    /**
     * @param object $entity
     */
    public function add($entity);

    /**
     * @param object $entity
     */
    public function remove($entity);
}
