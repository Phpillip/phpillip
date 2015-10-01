<?php

namespace Phpillip\Config\Definition;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
                    ->info('Provides default controllers for contents')
                    ->defaultFalse()
                ->end()
                ->scalarNode('route_class')
                    ->info('Route class name')
                    ->defaultValue('Phpillip\Routing\Route')
                ->end()
                ->scalarNode('parsedown_class')
                    ->info('Parsedown service class name')
                    ->defaultValue('Phpillip\Service\Parsedown')
                ->end()
                ->scalarNode('pygments_class')
                    ->info('Pygments service class name')
                    ->defaultValue('Phpillip\Service\Pygments')
                ->end()
                ->scalarNode('informator_class')
                    ->info('Informator service class name')
                    ->defaultValue('Phpillip\Service\Informator')
                ->end()
                ->scalarNode('content_repository_class')
                    ->info('Content Repository service class name')
                    ->defaultValue('Phpillip\Service\ContentRepository')
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
                ->arrayNode('commands')
                    ->info('A list of Command classnames to add to Phpillip console')
                    ->prototype('scalar')
                        ->cannotBeEmpty()
                        ->validate()
                        ->ifTrue(function ($value) { return !$this->isAValidCommand($value); })
                            ->thenInvalid('"%s" is not a valid Command.')
                        ->end()
                    ->end()
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

    /**
     * Is the given class name a valid Command?
     *
     * @param string $className
     *
     * @return boolean
     */
    public function isAValidCommand($className)
    {
        return class_exists($className) && is_subclass_of($className, 'Symfony\Component\Console\Command\Command');
    }
}
