<?php

namespace PortalBundle\Command;

use AppBundle\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

class WorkspaceCreatedWebhookCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('portal:webhook:workspace:created')
            ->setDescription('Portal webhook for workspace creation notification')
            ->addArgument('uuid', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'Workspace UUID')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $uuids = $input->getArgument('uuid');
        $teams = $this->getTeams($uuids);

        if (empty($teams)) {
            $io->warning('No teams found.');

            return 0;
        }

        $count = count($teams);
        $progress = new ProgressBar($output, $count);

        $errors = [];
        foreach ($teams as $team) {
            $progress->advance(1);

            try {
                $this->callWebhook($team);
            } catch (\Throwable $e) {
                if (1 === $count) {
                    throw $e;
                }

                $errors[] = [$team->getUuid(), $e->getMessage()];
            }
        }

        $progress->finish();

        if (!empty($errors)) {
            $io->newLine(2);
            $io->error(sprintf('%d error(s)', count($errors)));

            $table = new Table($output);
            $table->setHeaders(['UUID', 'Error']);
            $table->setRows($errors);
            $table->render();

            return 1;
        }

        $io->newLine(2);
        $io->success('Webhook call completed.');

        return 0;
    }

    /**
     * @param array $uuids
     *
     * @return Team[]
     */
    private function getTeams(array $uuids): array
    {
        Assert::notEmpty($uuids);

        return $this
            ->getContainer()
            ->get('app.repository.team')
            ->findBy(['uuid' => $uuids]);
    }

    /**
     * @param Team $team
     *
     * @throws \Exception
     *
     * @return bool
     */
    private function callWebhook(Team $team): bool
    {
        return $this
            ->getContainer()
            ->get('portal.client.http.workspace')
            ->createdWebhook($team)
        ;
    }
}
