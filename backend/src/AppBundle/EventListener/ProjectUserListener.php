<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Cost;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\SubteamMember;
use Doctrine\ORM\Event\OnFlushEventArgs;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\Resource;

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

            if ($entity instanceof ProjectUser) {
                $resource = $em
                    ->getRepository(Resource::class)
                    ->findOneBy(['projectUser' => $entity->getId(), 'project' => $entity->getProject()->getId()])
                ;
                if ($entity->getShowInResources()) {
                    if (!$resource) {
                        $resource = new Resource();
                    }
                    $resource->setProject($entity->getProject());
                    $resource->setProjectUser($entity);
                    $resource->setName($entity->getUserFullName());
                    $resource->setRate($entity->getRate() ? $entity->getRate() : 0);
                    $em->persist($resource);
                } else {
                    if ($resource) {
                        $costs = $em
                            ->getRepository(Cost::class)
                            ->countByResource($resource);

                        if ($costs == 0) {
                            $em->remove($resource);
                        }
                    }
                }
                if ($resource) {
                    $uok->computeChangeSet(
                        $em->getClassMetadata(Resource::class),
                        $resource
                    );
                }
            }
        }
    }
}
