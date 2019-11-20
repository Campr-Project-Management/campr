<?php

namespace AppBundle\Routing;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

class IfExistsYamlLoader extends YamlFileLoader
{
    private $fileName;

    public function __construct(FileLocatorInterface $locator, string $fileName)
    {
        parent::__construct($locator);

        $this->fileName = $fileName;
    }

    public function load($resource, $type = null)
    {
        $fileName = rtrim($resource, '.').$type;

        if (file_exists($fileName)) {
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            return parent::load($fileName, $ext);
        }

        $routes = new RouteCollection();

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return $this->fileName === $type;
    }
}
