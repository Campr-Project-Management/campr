<?php

namespace AppBundle\EventListener;

use AppBundle\Model\RemovalForbiddenInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class RemovalForbiddenListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof RemovalForbiddenInterface) {
            throw new \LogicException(sprintf('The entity %s must not be deleted.', get_class($entity)));
        }
    }
}
