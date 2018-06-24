<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function __construct($environment, $debug)
    {
        $environment = str_replace('-', '_', $environment);
        parent::__construct($environment, $debug);
    }

    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            //additional
            new Snc\RedisBundle\SncRedisBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Gregwar\CaptchaBundle\GregwarCaptchaBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new WhiteOctober\BreadcrumbsBundle\WhiteOctoberBreadcrumbsBundle(),
            new Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new TSS\AutomailerBundle\TSSAutomailerBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new Gos\Bundle\WebSocketBundle\GosWebSocketBundle(),
            new Gos\Bundle\PubSubRouterBundle\GosPubSubRouterBundle(),
            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle(),
            new Scheb\TwoFactorBundle\SchebTwoFactorBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Translation\PlatformAdapter\Loco\Bridge\Symfony\TranslationAdapterLocoBundle(),
            new Translation\Bundle\TranslationBundle(),

            //internals
            new AppBundle\AppBundle(),
            new MainBundle\MainBundle(),
        ];

        if (in_array($this->getRealEnvironment(), ['prod', 'qa'], true)) {
            $bundles[] = new Nelmio\CorsBundle\NelmioCorsBundle();
        }

        if (in_array($this->getRealEnvironment(), ['dev', 'test', 'qa'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
        }

        if (in_array($this->getRealEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getRealEnvironment().'.yml');
    }

    private function getRealEnvironment()
    {
        $env = explode('_', $this->getEnvironment());
        $env = end($env);

        return $env;
    }

    public function getTeamSlug()
    {
        $env = explode('_', $this->getEnvironment());
        if (1 === count($env)) {
            return null;
        }

        array_pop($env);

        return implode('_', $env);
    }

    public function getRequestContext()
    {
        $sfEnv = $this->getEnvironment();
        $sfEnvParts = explode('_', $sfEnv);
        $realEnv = array_pop($sfEnvParts);
        $scheme = 'prod' === $realEnv
            ? 'https'
            : 'http'
        ;

        if (!$sfEnvParts) {
            $out = [
                'router.request_context.host' => '%domain%',
                'router.request_context.scheme' => $_SERVER['REQUEST_SCHEME'] ?? $scheme,
            ];
        } else {
            $out = [
                'router.request_context.host' => sprintf('%s.%%domain%%', implode('_', $sfEnvParts)),
                'router.request_context.scheme' => $_SERVER['REQUEST_SCHEME'] ?? $scheme,
            ];
        }

        return $out;
    }

    public function getKernelParameters()
    {
        return array_merge(
            [
                'kernel.team_slug' => $this->getTeamSlug() ? $this->getTeamSlug() : 'team',
                'kernel.real_environment' => $this->getRealEnvironment(),
            ],
            parent::getKernelParameters()
        );
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        if ($this->isVagrantEnvironment()) {
            return '/dev/shm/campr/cache/'.$this->getEnvironment();
        }

        return dirname(__DIR__).'/../var/cache/'.$this->getEnvironment();
    }

    /**
     * @return string
     */
    public function getLogDir(): string
    {
        if ($this->isVagrantEnvironment()) {
            return '/dev/shm/campr/logs';
        }

        return dirname(__DIR__).'/../var/logs';
    }

    /**
     * @return bool
     */
    protected function isVagrantEnvironment(): bool
    {
        return ('/home/vagrant' === getenv('HOME') || 'VAGRANT' === getenv('VAGRANT')) && is_dir('/dev/shm');
    }
}
