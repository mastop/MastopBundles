<?php

/**
 * Mastop/SystemBundle/DependencyInjection/MastopSystemExtension.php
 *
 * Carrega as configurações para injeção de dependência do bundle de sistema.
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

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Finder\Finder;

class MastopSystemExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        
        $config = $processor->process($configuration->getConfigTree(), $configs);

        if (empty($config['themes_dir']) || empty($config['active_theme'])) {
            throw new \RuntimeException('Mastop\SystemBundle está bichado.');
        }
        $finder = new Finder();
        $finder->directories()
          ->depth('== 0')
          ->in($config['themes_dir']);
        $themes = array();
        foreach($finder as $f){
            $themes[] = $f->getFilename();
        }
        $container->setParameter('mastop.themes.list', $themes);
        $container->setParameter('mastop.themes.active', $config['active_theme']);
        $container->setParameter('mastop.themes.themes_dir', $config['themes_dir']);
        $container->setParameter('mastop.twitter.file', $config['twitter']['file']);
        $container->setParameter('mastop.twitter.consumer_key', $config['twitter']['consumer_key']);
        $container->setParameter('mastop.twitter.consumer_secret', $config['twitter']['consumer_secret']);
        $container->setParameter('mastop.twitter.callback_url', $config['twitter']['callback_url']);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('repository.xml');
        $loader->load('templating.xml');
        $loader->load('twig.xml');
    }
}