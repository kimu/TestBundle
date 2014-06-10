<?php

namespace Infinity\Bundle\TestBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Replaces services classes as specified in the bundle config
 *
 */
class TestEnvironmentPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // This compiler pass acts only in the test environment and if substitutions has been configured
        if ($container->getParameter('kernel.environment') == 'test' && $container->hasParameter('infinity_test.substitutions')) {
            foreach ($container->getParameter('infinity_test.substitutions') as $service => $substitution) {
                if ($container->hasDefinition($service)) {
                    $definition = $container->getDefinition($service);

                    // Normalize the class name
                    $class = preg_replace('/(\/\/|\/|\\\)/', '\\\\', $substitution['class']);
                    $definition->setClass($class);

                    // If requested remove all arguments
                    if (false === $substitution['inherit_arguments']) {
                        $definition->setArguments([]);
                    }

                    $container->setDefinition($service, $definition);
                }
            }
        }
    }
}
