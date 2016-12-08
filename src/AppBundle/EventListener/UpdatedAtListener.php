<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Class UpdatedAtListener
 * Sets the updatedAt field for all entities on update.
 */
class UpdatedAtListener
{
    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if (method_exists($entity, 'setUpdatedAt') && is_callable([$entity, 'setUpdatedAt'])) {
            $entity->setUpdatedAt(new \DateTime());
        }
    }
}
