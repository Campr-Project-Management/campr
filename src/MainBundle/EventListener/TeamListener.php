<?php

namespace MainBundle\EventListener;

use AppBundle\Entity\Team;
use AppBundle\Entity\TeamSlug;
use Doctrine\ORM\Event\OnFlushEventArgs;

/**
 * Class TeamListener
 * Creates a new TeamSlug entity when update a team.
 */
class TeamListener
{
    /**
     * @param OnFlushEventArgs $event
     */
    public function onFlush(OnFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uok = $em->getUnitOfWork();

        foreach ($uok->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof Team) {
                $changeSet = $uok->getEntityChangeSet($entity);
                if (isset($changeSet['slug'])) {
                    $teamSlug = new TeamSlug();
                    $teamSlug->setTeam($entity);
                    $teamSlug->setSlug($changeSet['slug'][0]);
                    $em->persist($teamSlug);
                    $uok->computeChangeSets();
                }
            }
        }
    }
}
