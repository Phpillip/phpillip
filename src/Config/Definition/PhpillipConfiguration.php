<?php

namespace Phpillip\Config\Definition;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Phillip configuration reference
 */
class PhpillipConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('phpillip');

        $rootNode
            ->children()
                ->scalarNode('default_controllers')
                    ->defaultTrue()
                    ->info('Provides default controllers for contents')
                ->end()
                ->scalarNode('route_class')
                    ->defaultValue('Phpillip\Routing\Route')
                ->end()
                ->scalarNode('src_path')
                    ->defaultValue('/Resources/data')
                    ->info('Content files directory')
                ->end()
                ->scalarNode('dst_path')
                    ->defaultValue('/../dist')
                    ->info('Build destination path')
                ->end()
                ->scalarNode('public_path')
                    ->defaultValue('/Resources/public')
                    ->info('Public files directory')
                ->end()
                ->scalarNode('twig_path')
                    ->defaultValue('/Resources/views')
                    ->info('Twig views directory')
                ->end()
                ->scalarNode('sitemap')
                    ->defaultTrue()
                    ->info('Enable/Disable the XML sitemap generation')
                ->end()
                ->variableNode('parameters')
                    ->info('Your key/value parameters.')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
