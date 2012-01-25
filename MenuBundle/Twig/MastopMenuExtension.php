<?php

/**
 * Mastop/MenuBundle/Twig/MastopMenuExtension.php
 *
 * Extensão do Twig para o bundle Menu.
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


namespace Mastop\MenuBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Extension;
use Twig_Function_Method;
use Twig_Filter_Method;
use DateTime;

class MastopMenuExtension extends Twig_Extension
{
    protected $container;

    /**
     * Constructor.
     *
     * @param Router $router A Router instance
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        $mappings = array(
            'mastop_menu'           => 'menu',
            'breadcrumbs'           => 'breadcrumbs'
        );

        $functions = array();
        foreach($mappings as $twigFunction => $method) {
            $functions[$twigFunction] = new Twig_Function_Method($this, $method, array('is_safe' => array('html')));
        }

        return $functions;
    }
    
    public function menu($menu, $item = null, $current = null, $depth = 0, $template = 'list', $attributes = array())
    {
        return $this->container->get('mastop.menu')->render($menu, $item, $current, $depth, $template, $attributes);
    }
    public function breadcrumbs($title = null, $current = null, $itens = false, $attrs = array(), $area = 'admin', $template = false)
    {
        return $this->container->get('mastop.menu')->breadcrumbs($title, $current, $itens, $attrs, $area, $template);
    }

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName()
    {
        return 'mastopmenu';
    }

}
