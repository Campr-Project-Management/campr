<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\SubteamMember;
use Doctrine\ORM\Event\OnFlushEventArgs;
use AppBundle\Entity\ProjectUser;

/**
 * Class ProjectUserListner.
 */
class ProjectUserListener
{
    /**
     * @param OnFlushEventArgs $event
     */
    public function onFlush(OnFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uok = $em->getUnitOfWork();

        foreach ($uok->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof ProjectUser && $entity->hasProjectRole(ProjectRole::ROLE_TEAM_LEADER)) {
                $subteamMembers = $em
                    ->getRepository(SubteamMember::class)
                    ->findByUserAndProject($entity->getUser(), $entity->getProject())
                ;
                foreach ($subteamMembers as $member) {
                    $member->setIsLead(true);
                    $uok->recomputeSingleEntityChangeSet(
                        $em->getClassMetadata(SubteamMember::class),
                        $member
                    );
                }
            }
        }

        foreach ($uok->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof ProjectUser && $entity->hasProjectRole(ProjectRole::ROLE_TEAM_LEADER)) {
                $subteamMembers = $em
                    ->getRepository(SubteamMember::class)
                    ->findByUserAndProject($entity->getUser(), $entity->getProject())
                ;
                foreach ($subteamMembers as $member) {
                    $member->setIsLead(true);
                    $uok->recomputeSingleEntityChangeSet(
                        $em->getClassMetadata(SubteamMember::class),
                        $member
                    );
                }
            }
        }
    }
}
