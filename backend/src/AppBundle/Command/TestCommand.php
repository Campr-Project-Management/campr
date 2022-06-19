<?php

namespace AppBundle\Command;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('test');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $wpms = $this->getContainer()->get('app.service.workpackage_mailer');

        $project = new Project(1);
        $project->setName('project');

        $oldUser = new User();
        $oldUser->setEmail('old@email.com');
        $oldUser->setFirstName('Old');
        $oldUser->setLastName('Old');

        $user = new User();
        $user->setEmail('new@email.com');
        $user->setFirstName('New');
        $user->setLastName('New');

        $task = new WorkPackage(1);
        $task->setProject($project);
        $task->setResponsibility($oldUser);
        $task->setCreatedBy($oldUser);
        $task->setForecastStartAt(new \DateTime());
        $task->setForecastFinishAt(new \DateTime());

        //$this->getContainer()->get('translator')->setLocale('de');

//        'project_id' => $wp->getProject()->getId(),
//        'task_id' => $wp->getId(),
//        'project_name' => $wp->getProject()->getName(),
//        'task_name' => $wp->getName(),
//        'task_responsible' => $wp->getResponsibilityFullName(),
//        'former_task_responsibility' => $formerTaskResponsible->getFullName(),
//        'workspace_name' => $workspaceName,

//        $wpms->sendTaskResponsibilityChangedEmail(
//            $task,
//            $user
//        );

        $wpms->sendNewTaskEmail($task);
    }
}
