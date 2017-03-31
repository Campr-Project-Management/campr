<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DumpRoutesCommand extends ContainerAwareCommand
{
    private $workDir;

    private $consolePath;

    /**
     * @var OutputInterface
     */
    private $output;

    protected function configure()
    {
        $this
            ->setName('app:dump-routes')
            ->addOption('slug', 's', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL)
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->workDir = realpath($this->getContainer()->getParameter('kernel.root_dir').'/../..');
        $this->consolePath = PHP_BINARY.' '.$this->workDir.'/bin/console';
        $this->output = $output;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $slugs = $input->getOption('slug');

        $output->writeln('<info>Dumping routes</info>');

        $env = $this->getContainer()->getParameter('kernel.environment');

        if ($slugs) {
            foreach ($slugs as $slug) {
                $this->runCommand(sprintf(
                    'fos:js-routing:dump --env=%2$s_%1$s --target=web/static/js/fos_js_routes_%2$s.js',
                    $env,
                    $slug
                ));
            }
        } else {
            $this->runCommand(sprintf('fos:js-routing:dump --env=%s --target=web/static/js/fos_js_routes.js', $env));
            $slugs = $this->getSlugs();
            foreach ($slugs as $slug) {
                $this->runCommand(sprintf(
                    'fos:js-routing:dump --env=%2$s_%1$s --target=web/static/js/fos_js_routes_%2$s.js',
                    $env,
                    $slug
                ));
            }
        }
    }

    private function runCommand($cmd)
    {
        $process = new Process($this->consolePath.' '.$cmd, $this->workDir);
        $process->run();
        $this->output->writeln($process->getOutput());
    }

    private function getSlugs()
    {
        $stmt = $this
            ->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getConnection()
            ->prepare('SELECT slug FROM `team`')
        ;
        $stmt->execute();

        return array_column($stmt->fetchAll(), 'slug');
    }
}
