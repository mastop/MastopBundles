<?php

/**
 * Mastop/SystemBundle/DependencyInjection/Compiler/ThemeCompilerPass.php
 *
 * Troca o localizador de template padrão para o file_locator do systembundles
 * para o sistema de temas funcionar adequadamente.
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

namespace Mastop\SystemBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ThemeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // Replace templating.
        /*$container->getDefinition('templating.locator.uncached')
            ->replaceArgument(0, new Reference('mastop.themes.file_locator'))
        ;*/

        $container->getDefinition('templating.locator')
            ->replaceArgument(0, new Reference('mastop.themes.file_locator'))
        ;
    }
}