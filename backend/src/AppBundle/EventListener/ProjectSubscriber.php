<?php

namespace AppBundle\EventListener;

use AppBundle\Command\RedisQueueManagerCommand;
use AppBundle\Entity\DistributionList;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\WorkPackage;
use AppBundle\Event\ProjectEvent;
use AppBundle\Repository\ProjectRoleRepository;
use AppBundle\Repository\WorkPackageStatusRepository;
use Component\Project\ProjectEvents;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Predis\Client;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\TranslatorInterface;

class ProjectSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $env;

    /**
     * @var Client
     */
    private $redis;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var WorkPackageStatusRepository
     */
    private $workPackageStatusRepository;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ProjectRoleRepository
     */
    private $projectRoleRepository;

    /**
     * ProjectSubscriber constructor.
     *
     * @param string                      $env
     * @param Client                      $redis
     * @param LoggerInterface             $logger
     * @param WorkPackageStatusRepository $workPackageStatusRepository
     * @param TranslatorInterface         $translator
     * @param ProjectRoleRepository       $projectRoleRepository
     */
    public function __construct(
        string $env,
        Client $redis,
        LoggerInterface $logger,
        WorkPackageStatusRepository $workPackageStatusRepository,
        TranslatorInterface $translator,
        ProjectRoleRepository $projectRoleRepository
    ) {
        $this->env = $env;
        $this->redis = $redis;
        $this->logger = $logger;
        $this->workPackageStatusRepository = $workPackageStatusRepository;
        $this->translator = $translator;
        $this->projectRoleRepository = $projectRoleRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ProjectEvents::ON_CLONE => 'onClone',
            ProjectEvents::PRE_CREATE => 'onPreCreate',
        ];
    }

    /**
     * @param ProjectEvent $event
     */
    public function onClone(ProjectEvent $event)
    {
        $this->logger->info(
            'Cloning project',
            [
                'project' => $event->getProject()->getId(),
                'user' => $event->getUser()->getId(),
                'name' => $event->getName(),
                'env' => $this->env,
            ]
        );

        $this->redis->rpush(
            RedisQueueManagerCommand::DEFAULT,
            [
                sprintf(
                    '--env=%s app:clone-project %s %s \'%s\'',
                    $this->env,
                    $event->getProject()->getId(),
                    $event->getUser()->getId(),
                    $event->getName()
                ),
            ]
        );

        $this->logger->info(
            'Cloning project. Redis command pushed',
            [
                'project' => $event->getProject()->getId(),
                'user' => $event->getUser()->getId(),
                'name' => $event->getName(),
                'env' => $this->env,
            ]
        );
    }

    /**
     * @param GenericEvent $event
     */
    public function onPreCreate(GenericEvent $event)
    {
        $project = $event->getSubject();
        if (!($project instanceof Project)) {
            return;
        }

        $this->addManager($project);
        $this->addDistributionList($project);
        $this->addTutorialTasks($project);
    }

    /**
     * @param Project $project
     *
     * @return ProjectUser
     */
    private function addManager(Project $project): ProjectUser
    {
        $projectUser = new ProjectUser();
        $projectUser->setUser($project->getCreatedBy());

        /** @var ProjectRole $role */
        $role = $this->projectRoleRepository->findOneBy(['name' => ProjectRole::ROLE_MANAGER]);
        if ($role) {
            $projectUser->addProjectRole($role);
        }

        $project->addProjectUser($projectUser);

        return $projectUser;
    }

    /**
     * @param Project $project
     *
     * @return DistributionList
     */
    private function addDistributionList(Project $project): DistributionList
    {
        $distributionList = new DistributionList();
        $distributionList->setName(DistributionList::STATUS_REPORT_DISTRIBUTION);
        $distributionList->setSequence(-1);
        $distributionList->setPosition(0);
        $distributionList->setProject($project);
        $distributionList->addUser($project->getCreatedBy());
        $distributionList->setCreatedBy($project->getCreatedBy());

        $project->addDistributionList($distributionList);

        return $distributionList;
    }

    /**
     * @param Project $project
     *
     * @return WorkPackage[]
     */
    private function addTutorialTasks(Project $project): array
    {
        if (count($project->getWorkPackages()) > 0) {
            return [];
        }

        $status = $this->workPackageStatusRepository->getDefault();
        $tasks = [];
        foreach ($this->getTasksFromTranslation() as $task) {
            $wp = new WorkPackage();
            $wp->setResponsibility($project->getCreatedBy());
            $wp->setType(WorkPackage::TYPE_TUTORIAL);
            $wp->setName($task['title']);
            $wp->setContent($task['description']);
            $wp->setWorkPackageStatus($status);

            $project->addWorkPackage($wp);
            $tasks[] = $wp;
        }

        return $tasks;
    }

    /**
     * @return array
     */
    private function getTasksFromTranslation(): array
    {
        /** @var MessageCatalogue $catalogue */
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
