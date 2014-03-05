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
        // This compiler pass acts only in the test environment
        if ($container->getParameter('kernel.environment') == 'test') {
            // Checks if substitutions has been configured and proceeds with the substitution
            if ($container->has('infinity_test.substitutions')) {
                foreach($container->get('infinity_test.substitutions') as $substitution) {
                    // Extracts the name of the service
                    $service = array_keys($substitution)[0];
                    if ($container->hasDefinition($service)) {
                        $definition = $container->getDefinition($service);

                        // Normalize the class name
                        $class = preg_replace('/(\/\/|\/|\\\)/', '\\\\', $substitution[$service]['class']);
                        $definition->setClass($class);

                        // If requested remove all arguments
                        if (false === $substitution[$service]['inherit_arguments']) {
                            $definition->setArguments([]);
                        }

                        $container->setDefinition($service, $definition);
                    }
                }
            }
        }
    }
}
