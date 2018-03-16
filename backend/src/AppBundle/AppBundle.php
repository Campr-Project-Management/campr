<?php

namespace AppBundle;

use AppBundle\DependencyInjection\CompilerPass\RegisterRepositoryPass;
use AppBundle\DependencyInjection\ContainerBuilder\DynamicDatabaseConnectionPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DynamicDatabaseConnectionPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION);
        $container->addCompilerPass(new RegisterRepositoryPass());
    }
}
