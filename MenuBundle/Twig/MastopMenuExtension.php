<?php

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
            'mastop_menu'           => 'menu'
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
