<?php

namespace AppBundle\Serializer\EventListener;

use AppBundle\Entity\Log;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
use AppBundle\Repository\ProjectUserRepository;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\JsonSerializationVisitor;

class LogSubscriber implements EventSubscriberInterface
{
    /**
     * @var ProjectUserRepository
     */
    private $projectUserRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * LogSubscriber constructor.
     *
     * @param ProjectUserRepository $projectUserRepository
     * @param UserRepository        $userRepository
     * @param EntityManager         $em
     */
    public function __construct(
        ProjectUserRepository $projectUserRepository,
        UserRepository $userRepository,
        EntityManager $em
    ) {
        $this->projectUserRepository = $projectUserRepository;
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'class' => Log::class,
                'format' => 'json',
            ],
        ];
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        /** @var Log $log */
        $log = $event->getObject();

        /** @var JsonSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        if (WorkPackage::class === $log->getClass()) {
            $this->processWorkPackageLog($visitor, $log);
        }
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param Log                      $log
     */
    private function processWorkPackageLog(JsonSerializationVisitor $visitor, Log $log)
    {
        /** @var WorkPackage $wp */
        $wp = $this->getLogObject($log);
        if (!$wp) {
            return;
        }

        if ($log->isResponsibilityAdded()) {
            $this->addResponsibility($visitor, $log, $wp);
        }

        $this->addProjectUser($visitor, $log, $wp);
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param Log                      $log
     * @param WorkPackage              $wp
     */
    private function addResponsibility(JsonSerializationVisitor $visitor, Log $log, WorkPackage $wp)
    {
        $newValue = $log->getNewValue();
        if (!empty($newValue['responsibility'])) {
            list($class, $id) = $newValue['responsibility'];

            /** @var User $user */
            $user = $this->userRepository->find($id);
            if ($user) {
                $visitor->setData('newResponsibility', $this->serializeResponsibility($user, $wp->getProject()));
            }
        }

        $oldValue = $log->getNewValue();
        if (!empty($oldValue['responsibility'])) {
            list($class, $id) = $oldValue['responsibility'];

            /** @var User $user */
            $user = $this->userRepository->find($id);
            if ($user) {
                $visitor->setData('oldResponsibility', $this->serializeResponsibility($user, $wp->getProject()));
            }
        }
    }

    /**
     * @param User    $user
     * @param Project $project
     *
     * @return array
     */
    private function serializeResponsibility(User $user, Project $project)
    {
        $projectUser = $this
            ->projectUserRepository
            ->findOneBy(['user' => $user, 'project' => $project])
        ;

        return [
            'id' => $projectUser->getId(),
            'userId' => $user->getId(),
            'userFullName' => $user->getFullName(),
        ];
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param Log                      $log
     * @param WorkPackage              $wp
     */
    private function addProjectUser(JsonSerializationVisitor $visitor, Log $log, WorkPackage $wp)
    {
        $user = $log->getUser();

        /** @var ProjectUser $projectUser */
        $projectUser = $this
            ->projectUserRepository
            ->findOneBy(['user' => $user, 'project' => $wp->getProject()])
        ;
        if (!$projectUser) {
            return;
        }

        $visitor->setData('projectUserId', $projectUser->getId());
    }

    /**
     * @param Log $log
     *
     * @return null|object
     */
    private function getLogObject(Log $log)
    {
        return $this
            ->em
            ->getRepository($log->getClass())
            ->find($log->getObjId())
        ;
    }
}
