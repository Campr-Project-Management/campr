<?php

namespace PortalBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideablePass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('app.overrideable') as $id => $params) {
            foreach ($params as $param) {
                $overriderId = sprintf('portal.%s', $param['id']);
                if (!$container->hasDefinition($overriderId)) {
                    return;
                }

                $container->removeDefinition($id);
                $container->setAlias($id, $overriderId);
            }
        }
    }
}
