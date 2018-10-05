<?php

namespace AppBundle\DependencyInjection\ContainerBuilder;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DynamicDatabaseConnectionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $slug = $container->getParameter('kernel.team_slug');
        $slug = str_replace('-', '_', $slug);
        if (empty($slug) || in_array($slug, ['www'])) {
            throw new \LogicException('Workspace app cannot access the portal db, even if it\'s deployed on the same server');
        }
        $definition = $container->getDefinition('doctrine.dbal.default_connection');
        $args = $definition->getArguments();
        $args[0]['dbname'] .= '_'.$slug;
        $definition->setArguments($args);
    }
}
