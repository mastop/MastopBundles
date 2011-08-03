<?php

namespace Mastop\SystemBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
* Generates the configuration tree.
*
* @return \Symfony\Component\DependencyInjection\Configuration\NodeInterface
*/
class Configuration
{
/**
* Generates the configuration tree.
*
* @return \Symfony\Component\DependencyInjection\Configuration\NodeInterface
*/
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mastop', 'array');
        $rootNode
            ->children()
                ->arrayNode('themes')
                    ->useAttributeAsKey('theme')
                    ->prototype('scalar')
                ->end()
            ->end()
            ->scalarNode('active_theme')->end()
            ->scalarNode('themes_dir')->defaultValue('%kernel.root_dir%/../src/Mastop/Resources/themes')->end()
            ->arrayNode('twitter')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('file')->defaultValue('%kernel.root_dir%/../vendor/twitteroauth/twitteroauth/twitteroauth.php')->end()
                        ->scalarNode('consumer_key')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('consumer_secret')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('callback_url')->defaultNull()->end()
                    ->end()
            ->end()
        ->end();
        return $treeBuilder->buildTree();
    }

}