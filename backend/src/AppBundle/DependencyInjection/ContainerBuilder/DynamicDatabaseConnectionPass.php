<?php

namespace AppBundle\DependencyInjection\ContainerBuilder;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DynamicDatabaseConnectionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $slug = $container->getParameter('kernel.team_slug');
        if (empty($slug) || in_array($slug, ['team', 'www'])) {
            return;
        }
        $definition = $container->getDefinition('doctrine.dbal.default_connection');
        $args = $definition->getArguments();
        $args[0]['dbname'] .= '_'.$container->getParameter('kernel.team_slug');
        $definition->setArguments($args);
    }
}
