<?php

namespace MainBundle\Command;

use AppBundle\Entity\User;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Setup database for beta workspace.
 *
 * Command usage: app:setup-beta beta-user-email team-env
 */
class SetupBetaDatabaseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:setup-beta')
            ->addArgument('email', InputArgument::REQUIRED, 'The beta user email is required')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $email = $input->getArgument('email');

        $env = $this->getContainer()->getParameter('kernel.environment');
        $process = new Process(sprintf('bin/console doctrine:migrations:migrate -n --env=%s', $env));
        $process->run();

        if (!$process->isSuccessful()) {
            $output->writeln(sprintf('<info>Setup failed: %s</info>', $process->getErrorOutput()));
        } else {
            $output->writeln('<info>Migrations ok.</info>');
        }

        $tables = $this->getTables();
        $now = $this->now();

        $min = $this->getMinimumDate('work_package', 'scheduled_start_at');
        $days = $this->calculateDiffInDays($min, $now);
        foreach ($tables as $table) {
            $columns = $this->getDateOrDatetimeColumnsFromTable($table);
            foreach ($columns as $column) {
                $output->writeln(sprintf('Updating table %s, column %s', $table, $column['Field']));
                $this->updateData($table, $column['Field'], $days);
                $output->writeln(sprintf('Finished updating table %s, column %s', $table, $column['Field']));
            }
        }

        /** @var User $user */
        $user = $em->getRepository(User::class)->find(1);

        $user->setEmail($email);
        $user->setUsername($email);

        $em->flush();
        $output->writeln('<info>Setup done.</info>');
    }

    private function calculateDiffInDays($min, $now)
    {
        $nowDate = (new \DateTime($now))->add(new \DateInterval('P7D'));
        $minDate = new \DateTime($min);

        return $nowDate->diff($minDate)->days;
    }

    private function updateData($table, $column, $days)
    {
        $this->runSql(sprintf('UPDATE %2$s SET %1$s = DATE_ADD(%1$s, INTERVAL %3$d DAY) WHERE %1$s IS NOT NULL', $column, $table, $days));
    }

    private function getTables()
    {
        return array_map(function ($i) {
            return reset($i);
        }, $this->runSql('SHOW TABLES'));
    }

    private function getDateOrDatetimeColumnsFromTable($table)
    {
        return array_filter($this->runSql(sprintf('DESCRIBE %s', $table)), function ($item) {
            return in_array(strtolower($item['Type']), ['date', 'datetime']);
        });
    }

    private function getMinimumDate($table, $column)
    {
        $out = $this->runSql(sprintf('SELECT MIN(%1$s) AS `min` FROM %2$s WHERE %1$s IS NOT NULL', $column, $table));

        return $out ? $out[0]['min'] : null;
    }

    private function now()
    {
        return $this->runSql('SELECT NOW() as `now`')[0]['now'];
    }

    private function runSql(string $sql)
    {
        /** @var Connection $con */
        $con = $this->getContainer()->get('doctrine')->getConnection();

        $method = 0 === strpos(strtoupper($sql), 'UPDATE ')
            ? 'executeUpdate'
            : 'executeQuery';

        $stmt = $con->{$method}($sql);

        if (!is_object($stmt)) {
            return $stmt;
        }

        return $stmt->fetchAll();
    }
}
