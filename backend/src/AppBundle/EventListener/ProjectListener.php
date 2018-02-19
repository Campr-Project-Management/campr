<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\ColorStatus;
use AppBundle\Entity\DistributionList;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Helper\ProjectRoleDefaultListBuilder;
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

            $defaultRoles = ProjectRoleDefaultListBuilder::buildDefaultListForProject($entity);
            $managerRoles = array_values(
                array_filter(
                    $defaultRoles,
                    function ($role) {
                        return $role->getName() === ProjectRole::ROLE_MANAGER;
                    }
                )
            );
            $entity->setProjectRoles($defaultRoles);
            // we make sure that manager roles has at least one element and we grab it
            $managerRole = count($managerRoles) ? $managerRoles[0] : null;
            $projectUser->addProjectRole($managerRole);

            $em->persist($projectUser);

            $specialDistribution = new DistributionList();
            $specialDistribution->setName(DistributionList::STATUS_REPORT_DISTRIBUTION);
            $specialDistribution->setSequence(-1);
            $specialDistribution->setPosition(0);
            $specialDistribution->setProject($entity);
            $specialDistribution->addUser($user);
            $specialDistribution->setCreatedBy($user);

            $em->persist($specialDistribution);

            $tasks = $this->getTasksFromTranslation();
            foreach ($tasks as $task) {
                $wp = new WorkPackage();
                $wp->setProject($entity);
                $wp->setResponsibility($user);
                $wp->setType(WorkPackage::TYPE_TUTORIAL);
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
