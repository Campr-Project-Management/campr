<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class DumpRoutesCommand extends ContainerAwareCommand
{
    const TARGET_PATH_WORKSPACE = 'web/static/js/fos_js_routes_{slug}.js';

    /**
     * @var string
     */
    private $workDir;

    /**
     * @var string
     */
    private $consolePath;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var InputInterface
     */
    private $input;

    protected function configure()
    {
        $this
            ->setName('app:dump-routes')
            ->addOption('slug', 's', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->workDir = realpath($this->getContainer()->getParameter('kernel.root_dir').'/../..');
        $this->consolePath = PHP_BINARY.' '.$this->workDir.'/bin/console';
        $this->output = $output;
        $this->input = $input;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Dumping JS routes...</info>');

        $slugs = $input->getOption('slug');
        if (empty($slugs)) {
            $this->runCommand(
                sprintf('fos:js-routing:dump --env=%s --target=web/static/js/fos_js_routes.js', $this->getEnv())
            );

            $slugs = $this->getSlugs();
        }

        $this->dumpWorkspacesJSRoutes($slugs);

        return 0;
    }

    /**
     * @param string[] $slugs
     */
    private function dumpWorkspacesJSRoutes(array $slugs)
    {
        if (empty($slugs)) {
            throw new \InvalidArgumentException('No workspaces slugs given');
        }

        $firstSlug = array_shift($slugs);
        $this->dumpWorkspaceJSRoutes($firstSlug);

        foreach ($slugs as $slug) {
            $this->createWorkspaceJSRoutesDumpFrom($slug, $firstSlug);
        }
    }

    /**
     * @param string $destSlug
     * @param string $srcSlug
     */
    private function createWorkspaceJSRoutesDumpFrom(string $destSlug, string $srcSlug)
    {
        $src = $this->getWorkspaceTargetPath($srcSlug);
        $dest = $this->getWorkspaceTargetPath($destSlug);
        $cmd = [
            "cp $src $dest",
            "sed -i -e 's/\"subdomain\":\"$srcSlug\"/\"subdomain\":\"$destSlug\"/g' $dest",
            "ls $dest",
        ];

        $cmd = implode(" \\\n && ", $cmd);
        $this->runProcess($cmd);
    }

    /**
     * @param string $slug
     */
    private function dumpWorkspaceJSRoutes(string $slug)
    {
        $env = $this->getEnv();
        $cmd = sprintf(
            'fos:js-routing:dump --env=%s_%s --target=%s',
            $slug,
            $env,
            $this->getWorkspaceTargetPath($slug)
        );

        $this->runCommand($cmd);
    }

    /**
     * @param string $slug
     *
     * @return string
     */
    private function getWorkspaceTargetPath(string $slug): string
    {
        return strtr(implode(DIRECTORY_SEPARATOR, [$this->workDir, self::TARGET_PATH_WORKSPACE]), ['{slug}' => $slug]);
    }

    /**
     * @param string $cmd
     *
     * @return Process
     */
    private function newProcess(string $cmd)
    {
        return new Process($cmd, $this->workDir);
    }

    /**
     * @return array
     */
    private function getSlugs(): array
    {
        $stmt = $this
            ->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getConnection()
            ->prepare('SELECT slug FROM `team`');
        $stmt->execute();

        return array_column($stmt->fetchAll(), 'slug');
    }

    /**
     * @return string
     */
    private function getEnv(): string
    {
        return $this->getContainer()->getParameter('kernel.environment');
    }

    /**
     * @param string $cmd
     */
    private function runCommand(string $cmd)
    {
        $cmd = sprintf('%s %s', $this->consolePath, $cmd);

        $this->runProcess($cmd);
    }

    /**
     * @param string $cmd
     */
    private function runProcess(string $cmd)
    {
        $io = new SymfonyStyle($this->input, $this->output);
        $io->text(sprintf('<info>[RUN]</info> %s', $cmd));

        $process = $this->newProcess($cmd);
        $process->run();

        $output = trim($process->getErrorOutput());
        $exitCode = $process->getExitCode();
        if (!empty($output)) {
            $io->error($output);
        }

        if ($exitCode) {
            throw new \InvalidArgumentException($output, $exitCode);
        }

        $output = trim($process->getOutput());
        if (!empty($output)) {
            $io->success($output);

            return;
        }

        $io->success('');
    }
}
