<?php

namespace MainBundle\Command;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use Component\Resource\Cloner\ProjectCloneScope;
use Component\Resource\Cloner\ResourceClonerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

/**
 * Clone project.
 *
 * Command usage: app:clone-project projectId userId name startDate
 */
class CloneProjectCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:clone-project')
            ->addArgument('projectId', InputArgument::REQUIRED, 'The original projectId is required.')
            ->addArgument('userId', InputArgument::REQUIRED, 'The userId is required.')
            ->addArgument('name', InputArgument::REQUIRED, 'The name is required.')
            ->addArgument('startDate', InputArgument::REQUIRED, 'The startDate is required.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $startDate = $input->getArgument('startDate');
        $projectId = $input->getArgument('projectId');
        $userId = $input->getArgument('userId');

        $io = new SymfonyStyle($input, $output);

        try {
            $project = $this->findProject($projectId);
            $user = $this->findUser($userId);

            $io->section(sprintf('Cloning project "%s" to "%s"...', $project->getName(), $name));

            $newProject = $this->cloneProject($project, $name, $startDate);
            $this->sendNotification($user, $newProject);

            $io->success(sprintf('Project "%s" successfully cloned as "%s"', $project->getName(), $name));
            $this->getLogger()->info(
                'Project successfully cloned',
                [
                    'projectId' => $newProject->getId(),
                    'name' => $newProject->getName(),
                ]
            );
        } catch (\Exception $e) {
            $this->getLogger()->error(
                $e->getMessage(),
                [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            throw $e;
        }
    }

    /**
     * @param int $id
     *
     * @return Project
     */
    private function findProject(int $id): Project
    {
        $project = $this->getContainer()->get('app.repository.project')->find($id);
        Assert::notNull($project, sprintf('Project not found: %d', $id));

        return $project;
    }

    /**
     * @param int $id
     *
     * @return User
     */
    private function findUser(int $id): User
    {
        $user = $this->getContainer()->get('app.repository.user')->find($id);
        Assert::notNull($user, sprintf('User not found: %d', $id));

        return $user;
    }

    /**
     * @return LoggerInterface
     */
    private function getLogger(): LoggerInterface
    {
        return $this->getContainer()->get('monolog.logger.cloner');
    }

    /**
     * @param Project $project
     * @param string  $name
     * @param string  $startDate
     *
     * @return Project
     */
    private function cloneProject(Project $project, string $name, string $startDate): Project
    {
        $_SESSION['projectDates'] = [
            'minStartDate' => $this->getContainer()->get('app.repository.work_package')->getProjectStartAt($project),
            'startDate' => $startDate
        ];

        /** @var Project $clone */
        $clone = $this->getCloner($project)->clone($project, new ProjectCloneScope($project));
        $clone->setName($name);

        $this->getContainer()->get('app.repository.project')->add($clone);

        return $clone;
    }

    /**
     * @param Project $project
     *
     * @return ResourceClonerInterface
     */
    private function getCloner(Project $project): ResourceClonerInterface
    {
        return $this
            ->getContainer()
            ->get('app.clone.cloner_registry')
            ->getForObject($project);
    }

    /**
     * @param User    $user
     * @param Project $project
     */
    private function sendNotification(User $user, Project $project)
    {
        $this->getLogger()->info(
            'Sending project cloning email notification.',
            [
                'email' => $user->getEmail(),
            ]
        );

        $sent = $this->getContainer()->get('app.service.mailer')->sendProjectClonedEmail($user, $project);
        Assert::greaterThan($sent, 0, 'Project cloning email notification could not be sent');

        $this->getLogger()->info(
            'Project cloning notification successfully sent.',
            [
                'email' => $user->getEmail(),
            ]
        );
    }
}
