<?php

/**
 * Mastop/SystemBundle/DependencyInjection/Configuration.php
 *
 * Gera árvore de configuração para o SystemBundle
 *  
 * 
 * @copyright 2011 Mastop Internet Development.
 * @link http://www.mastop.com.br
 * @author Fernando Santos <o@fernan.do>
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */


namespace Mastop\SystemBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
* @return \Symfony\Component\DependencyInjection\Configuration\NodeInterface
*/
class Configuration
{
/**
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