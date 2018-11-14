<?php

namespace AppBundle\DependencyInjection\CompilerPass;

use Component\Resource\Cloner\ResourceClonerRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterClonersPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $registry = $this->createRegistry($container);
        foreach ($container->findTaggedServiceIds('app.clone.cloner') as $id => $params) {
            $cloner = new Reference($id);

            foreach ($params as $param) {
                $class = $param['class'] ?? null;
                if (empty($class)) {
                    $registry->addMethodCall('registerDefault', [$cloner]);
                    continue 2;
                }

                $registry->addMethodCall('register', [$class, $cloner]);
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return Definition
     */
    private function createRegistry(ContainerBuilder $container): Definition
    {
        $def = new Definition(ResourceClonerRegistry::class);
        $def->setLazy(true);
        $container->setDefinition('app.clone.cloner_registry', $def);

        return $def;
    }
}
