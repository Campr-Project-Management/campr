<?php

namespace AppBundle\Routing;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Routing\Loader\AnnotationClassLoader;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;
use Symfony\Component\Routing\RouteCollection;

class Loader extends AnnotationDirectoryLoader
{
    private $teamSlug;

    private $loaded;

    private $env;

    public function __construct(
        FileLocatorInterface $locator,
        AnnotationClassLoader $loader,
        $teamSlug,
        $env
    ) {
        parent::__construct($locator, $loader);

        $this->teamSlug = $teamSlug;
        $this->env = $env;
    }

    public function load($resource, $type = null)
    {
        if ($this->loaded) {
            throw new \Exception('Already loaded.');
        }
        $this->loaded = true;

        if ($this->teamSlug === 'team') {
            $this->teamSlug = null;
        }

        if ($this->teamSlug !== 'team' || $this->env === 'test') {
            return parent::load($resource, 'annotation');
        }

        $routes = new RouteCollection();

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return $type === 'app';
    }
}
