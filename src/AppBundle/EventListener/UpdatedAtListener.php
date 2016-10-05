<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\PreUpdateEventArgs;

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
