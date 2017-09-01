<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\ColorStatus;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ProjectListener
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * ProjectListener constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage, TranslatorInterface $translator)
    {
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Project && $this->tokenStorage->getToken() !== null) {
            $user = $this->tokenStorage->getToken()->getUser();
            $em = $args->getEntityManager();

            $projectUser = new ProjectUser();
            $projectUser->setProject($entity);
            $projectUser->setUser($user);
            $projectUser->setShowInResources(true);
            $projectUser->setShowInOrg(true);

            $role = $args
                ->getEntityManager()
                ->getRepository(ProjectRole::class)
                ->findOneByName(ProjectRole::ROLE_MANAGER)
            ;
            $projectUser->addProjectRole($role);

            $em->persist($projectUser);

            $tasks = $this->getTasksFromTranslation();
            foreach ($tasks as $task) {
                $wp = new WorkPackage();
                $wp->setProject($entity);
                $wp->setResponsibility($user);
                $wp->setType(WorkPackage::TYPE_TASK);
                $wp->setName($task['title']);
                $wp->setContent($task['description']);
                $wp->setWorkPackageStatus(
                    $em->getReference(WorkPackageStatus::class, WorkPackageStatus::OPEN)
                );

                $em->persist($wp);
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(\Doctrine\ORM\Event\LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!($entity instanceof Project)) {
            return;
        }

        $em = $args->getEntityManager();
        $colorStatus = $em->getRepository(ColorStatus::class)->findOneByProject($entity);
        $entity->setColorStatus($colorStatus);
    }

    private function getTasksFromTranslation()
    {
        /** @var \Symfony\Component\Translation\MessageCatalogue $catalogue */
        $catalogue = $this->translator->getCatalogue();
        $tasks = $catalogue->all('tasks');

        $keys = array_keys($tasks);

        $out = [];

        foreach ($keys as $key) {
            $parts = explode('.', $key);

            if (count($parts) !== 3) {
                continue;
            }

            if (!isset($out[$parts[1]])) {
                $out[$parts[1]] = [];
            }

            $out[$parts[1]][$parts[2]] = $tasks[$key];
        }

        return $out;
    }
}
