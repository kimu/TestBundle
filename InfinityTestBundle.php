<?php

namespace Infinity\Bundle\TestBundle;

use Infinity\Cms\Bundle\TestBundle\DependencyInjection\Compiler\TestEnvironmentPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class InfinityTestBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TestEnvironmentPass());
    }
}
