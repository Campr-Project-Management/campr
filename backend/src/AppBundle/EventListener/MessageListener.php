<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Message;
use Doctrine\ORM\Event\LifecycleEventArgs;

class MessageListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Message) {
            if ($entity->getFrom() && $entity->getTo()) {
                $fromId = $entity->getFrom()->getId();
                $toId = $entity->getTo()->getId();
                $chatKey = $fromId < $toId ? $fromId.'-'.$toId : $toId.'-'.$fromId;
                $entity->setChatKey($chatKey);
            }
        }
    }
}
