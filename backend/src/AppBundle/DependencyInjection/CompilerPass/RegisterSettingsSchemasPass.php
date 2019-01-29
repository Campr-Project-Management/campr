<?php

namespace AppBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class RegisterSettingsSchemasPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $registries = $this->getRegistries($container);
        if (empty($registries)) {
            return;
        }

        foreach ($container->findTaggedServiceIds('app.settings.schema') as $id => $params) {
            foreach ($params as $param) {
                $schema = $param['schema'] ?? null;
                $service = $container->findDefinition($id);
                if (empty($schema)) {
                    $schema = $service->getClass();
                }

                foreach ($registries as $registry) {
                    $registry->addMethodCall('register', [$schema, $service]);
                }
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return Definition[]
     */
    private function getRegistries(ContainerBuilder $container): array
    {
        $registries = [];
        foreach ($container->findTaggedServiceIds('app.settings.schema.registry') as $id => $params) {
            $registries[] = $container->findDefinition($id);
        }

        return $registries;
    }
}
