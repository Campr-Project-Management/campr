<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
use Component\User\Context\UserContext;

/**
 * Class WorkPackageMailerService
 * Service used for sending WorkPackage related emails.
 */
class WorkPackageMailerService
{
    /**
     * @var MailerService
     */
    private $mailer;

    /**
     * @var UserContext
     */
    protected $userContext;

    public function __construct(
        MailerService $mailer,
        UserContext $userContext
    ) {
        $this->mailer = $mailer;
        $this->userContext = $userContext;
    }

    /**
     * @param WorkPackage $wp
     */
    public function sendNewTaskEmail(WorkPackage $wp)
    {
        $this->mailer->sendEmail(
            ':task\emails:new_task_email.html.twig',
            'info',
            $wp->getResponsibilityEmail(),
            [
                'project_id' => $wp->getProject()->getId(),
                'task_id' => $wp->getId(),
                'project_name' => $wp->getProject()->getName(),
                'task_name' => $wp->getName(),
                'task_responsible' => $wp->getResponsibilityFullName(),
                'task_creator' => $wp->getCreatedBy()->getFullName(),
                'base_finished_date' => $wp->getForecastFinishAt()->format('Y-m-d'),
            ],
            [],
            [$wp->getCreatedBy()->getEmail()]
        );
    }

    /**
     * @param WorkPackage $wp
     * @param User|null   $formerTaskResponsible
     */
    public function sendTaskResponsibilityChangedEmail(WorkPackage $wp, User $formerTaskResponsible = null)
    {
        $ccEmails = [];

        if ($formerTaskResponsible) {
            $ccEmails[] = $formerTaskResponsible->getEmail();
        }

        if ($this->userContext->getUser()) {
            $ccEmails[] = $this->userContext->getUser()->getEmail();
        }

        $this->mailer->sendEmail(
            ':task\emails:responsibility_changed_email.html.twig',
            'info',
            $wp->getResponsibilityEmail(),
            [
                'project_id' => $wp->getProject()->getId(),
                'task_id' => $wp->getId(),
                'project_name' => $wp->getProject()->getName(),
                'task_name' => $wp->getName(),
                'task_responsible' => $wp->getResponsibilityFullName(),
                'former_task_responsibility' => $formerTaskResponsible->getFullName(),
            ],
            [],
            $ccEmails
        );
    }
}
