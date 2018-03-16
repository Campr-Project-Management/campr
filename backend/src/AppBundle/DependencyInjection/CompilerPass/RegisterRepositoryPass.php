<?php

namespace AppBundle\DependencyInjection\CompilerPass;

use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterRepositoryPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $repositories = [];
        $em = new Reference('doctrine.orm.default_entity_manager');
        foreach ($container->findTaggedServiceIds('app.repository') as $id => $params) {
            foreach ($params as $param) {
                $repositories[$param['class']] = $id;
                $repository = $container->findDefinition($id);

                $metadata = new Definition(ClassMetadata::class);
                $metadata->setFactory([$em, 'getClassMetadata']);
                $metadata->setArguments([$param['class']]);

                $repository->setArguments([$em, $metadata]);
            }
        }
    }
}
