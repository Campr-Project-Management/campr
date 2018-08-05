<?php

namespace AppBundle\Command;

use Bazinga\Bundle\JsTranslationBundle\Finder\TranslationFinder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Translation\Loader\FileLoader;

/**
 * Class DumpSSRTranslationsCommand.
 */
class DumpSSRTranslationsCommand extends ContainerAwareCommand
{
    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @var TranslationFinder
     */
    private $finder;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string[]
     */
    private $activeLocales;

    /**
     * @var string
     */
    private $targetFolder;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var FileLoader[]
     */
    private $loaders;

    /**
     * Configure method.
     */
    protected function configure()
    {
        $this->setName('app:dump-ssr-translations');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->engine = $this->getContainer()->get('templating');
        $this->finder = $this->getContainer()->get('bazinga.jstranslation.translation_finder');
        $this->router = $this->getContainer()->get('router');
        $this->filesystem = $this->getContainer()->get('filesystem');
        $this->activeLocales = $this->getContainer()->getParameter('managed_locales');
        $this->defaultLocale = $this->getContainer()->getParameter('locale');
        $this->targetFolder = realpath(
            $this->getContainer()->getParameter('kernel.root_dir').
            '/../../ssr/plugins/translator/'
        );

        // loaders
        $this->loaders = [
            'php' => $this->getContainer()->get('translation.loader.php'),
            'yml' => $this->getContainer()->get('translation.loader.yml'),
            'xlf' => $this->getContainer()->get('php_translation.storage.xlf_loader'),
            'xliff' => $this->getContainer()->get('translation.loader.xliff'),
            'po' => $this->getContainer()->get('translation.loader.po'),
            'mo' => $this->getContainer()->get('translation.loader.mo'),
            'ts' => $this->getContainer()->get('translation.loader.qt'),
            'csv' => $this->getContainer()->get('translation.loader.csv'),
            'res' => $this->getContainer()->get('translation.loader.res'),
            'dat' => $this->getContainer()->get('translation.loader.dat'),
            'ini' => $this->getContainer()->get('translation.loader.ini'),
            'json' => $this->getContainer()->get('translation.loader.json'),
        ];
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf(
            'Will write translations to folder: <info>%s</info>',
            $this->targetFolder
        ));

        $this->writeConfig($output);

        $this->writeTranslations($output);
    }

    /**
     * @param OutputInterface $output
     */
    private function writeConfig(OutputInterface $output)
    {
        $output->writeln('<info>Will now write config.</info>');

        $data = $this->engine->render(
            'BazingaJsTranslationBundle::config.ssr.twig',
            [
                'fallback' => $this->defaultLocale,
                'defaultDomain' => 'messages',
            ]
        );

        file_put_contents($this->targetFolder.'/config.js', $data);

        $output->writeln('<info>Done writing config.</info>');
    }

    /**
     * @param OutputInterface $output
     */
    private function writeTranslations(OutputInterface $output)
    {
        $output->writeln('<info>Will now dump locales.</info>');

        $translations = $this->getTranslations();

        foreach ($this->activeLocales as $locale) {
            $output->writeln(sprintf('Dumping locale: <info>%s</info>', $locale));
            $data = $this->engine->render(
                'BazingaJsTranslationBundle::getTranslations.ssr.twig',
                [
                    'translations' => [$locale => $translations[$locale]],
                    'include_config' => false,
                ]
            );

            file_put_contents($this->targetFolder.'/'.$locale.'.js', $data);
            $output->writeln(sprintf('Done dumping locale: <info>%s</info>', $locale));
        }

        $output->writeln('<info>Done dumping locales.</info>');
    }

    /**
     * @return array
     */
    private function getTranslations()
    {
        $translations = [];

        foreach ($this->finder->all() as $filename) {
            list($extension, $locale, $domain) = $this->getFileInfo($filename);

            if (!isset($translations[$locale])) {
                $translations[$locale] = [];
            }

            if (!isset($translations[$locale][$domain])) {
                $translations[$locale][$domain] = [];
            }

            if (isset($this->loaders[$extension])) {
                $catalogue = $this->loaders[$extension]->load($filename, $locale, $domain);

                $translations[$locale][$domain] = array_replace_recursive(
                    $translations[$locale][$domain],
                    $catalogue->all($domain)
                );
            }
        }

        return $translations;
    }

    /**
     * @param $filename
     *
     * @return array
     */
    private function getFileInfo($filename)
    {
        list($domain, $locale, $extension) = explode('.', basename($filename), 3);

        return array($extension, $locale, $domain);
    }
}
