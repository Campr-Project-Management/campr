<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\ProjectUser;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProjectListener
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * ProjectListener constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Project && $this->tokenStorage->getToken() !== null) {
            $projectUser = new ProjectUser();
            $projectUser->setProject($entity);
            $projectUser->setUser($this->tokenStorage->getToken()->getUser());
            $projectUser->setShowInResources(true);
            $projectUser->setShowInOrg(true);

            $role = $args->getEntityManager()->getRepository(ProjectRole::class)->findOneByName(ProjectRole::ROLE_MANAGER);
            $projectUser->addProjectRole($role);

            $args->getEntityManager()->persist($projectUser);
        }
    }
}
