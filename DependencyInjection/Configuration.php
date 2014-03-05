<?php

namespace Infinity\Bundle\TestBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('infinity_test');

        $rootNode
            ->fixXmlConfig('substitution')
            ->children()
                ->booleanNode('enabled')
                    ->defaultFalse()
                ->end()
                ->arrayNode('substitutions')
                    ->info('Substitutions definitions')
                    ->useAttributeAsKey('service')
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifString()
                            ->then(function($v) { return['class'=> $v]; })
                        ->end()
                        ->children()
                            ->scalarNode('class')
                                ->isRequired()
                                ->info('a\namespaced\classname')
                            ->end()
                            ->booleanNode('inherit_arguments')
                                ->defaultTrue()
                                ->info('true to keep all arguments of the original service')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
