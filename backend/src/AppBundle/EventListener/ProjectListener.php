<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\DistributionList;
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
     * @param TranslatorInterface   $translator
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
        if ($entity instanceof Project && null !== $this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();
            $em = $args->getEntityManager();

            $projectUser = new ProjectUser();
            $projectUser->setProject($entity);
            $projectUser->setUser($user);
            $projectUser->setShowInResources(true);
            $projectUser->setShowInOrg(true);

            $managerRole = $em
                ->getRepository(ProjectRole::class)
                ->findOneBy([
                    'name' => ProjectRole::ROLE_MANAGER,
                ])
            ;
            if ($managerRole) {
                $projectUser->addProjectRole($managerRole);
            }

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

    private function getTasksFromTranslation()
    {
        /** @var \Symfony\Component\Translation\MessageCatalogue $catalogue */
        $catalogue = $this->translator->getCatalogue();
        $tasks = $catalogue->all('tasks');

        $keys = array_keys($tasks);

        $out = [];

        foreach ($keys as $key) {
            $parts = explode('.', $key);

            if (3 !== count($parts)) {
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
