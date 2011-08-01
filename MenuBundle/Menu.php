<?php

/*
 * This file is part of the Mastop/MenuBundle
 *
 * (c) Mastop Iternet Development
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 * 
 * @author   Fernando Santos <o@fernan.do>
 */

namespace Mastop\MenuBundle;

use Mastop\SystemBundle\Mastop;
use Symfony\Bundle\TwigBundle\TwigEngine;

class Menu {

    private $mastop;
    private $engine;

    /**
     * @param string Mastop $mastop
     */
    public function __construct(Mastop $mastop, TwigEngine $engine) {
        $this->mastop = $mastop;
        $this->engine = $engine;
    }

    public function render($menu, $item = null, $current = null, $depth = 0, $template = 'list', $attributes = array()) {
        list($bundle, $code) = explode('-', $menu);
        $repo = $this->mastop->getDocumentManager()->getRepository('MastopMenuBundle:Menu');
        $cache = $this->mastop->getCache();
        if (!$item) {
            if ($cache->has($menu)) {
                $document = $cache->get($menu);
            } else {
                $menuMain = $repo->findByBundleCode($bundle, $code);
                if (!$menuMain) {
                    throw new \Exception("Menu " . $menu . " não encontrado.");
                }
                $document = $this->prepareLinks($menuMain);
                $cache->set($menu, $document, 604800); // Uma semana
            }
        } else {
            if ($cache->has($menu . '.' . $item)) {
                $document = $cache->get($menu . '.' . $item);
            } else {
                $menuMain = $repo->findByBundleCode($bundle, $code);
                $menuItem = $repo->getChildrenByCode($menuMain, $item);
                if (!$menuItem) {
                    throw new \Exception("Menu " . $item . " de " . $menu . " não encontrado.");
                }
                $document = $this->prepareLinks($menuItem);
                $cache->set($menu . '.' . $item, $document, 604800); // Uma semana
            }
        }
        return $this->engine->render('MastopMenuBundle:Templates:' . $template . '.html.twig', array('menu' => $document, 'current' => $current, 'attrs' => $attributes, 'root' => true, 'depth' => $depth));
    }

    public function breadcrumbs($title = null, $current = null, $itens = false, $attrs = array(), $area = 'admin') {
        if ($current == '_home' || $current == 'admin_system_home_index') {
            return null; // Vazio se o current é home do site ou home da admin
        }
        $cache = $this->mastop->getCache();
        if ($cache->has('system-' . $area)) {
            $menu = $cache->get('system-' . $area); // Pega o menu main ou menu admin do cache
        } else { // Pega o menu main ou menu admin do DB e salva no cache
            $repo = $this->mastop->getDocumentManager()->getRepository('MastopMenuBundle:Menu');
            $menuMain = $repo->findByBundleCode('system', $area);
            if (!$menuMain) {
                throw new \Exception("Menu system-" . $area . " não encontrado.");
            }
            $menu = $this->prepareLinks($menuMain);
            $cache->set('system-' . $area, $menu, 604800); // Uma semana
        }
        $ret = array();
        $count = 0; // Contador de itens do menu
        $thisUrl = explode('_', $current); // Exemplo de current: admin_menu_menu_index (admin_bundle_controller_action)
        $thisBundle = ($area == 'admin') ? 'admin_' . $thisUrl[1] : $thisUrl[0];
        $thisMenu = null;
        foreach ($menu as $k => $v)
            if ($k != 'dashboard' && strpos($v['url'], $thisBundle) !== false) {
                $thisMenu = $v;
                break;
            }
        if($thisMenu){
            $ret[$count]['name'] = $thisMenu['name'];
            $ret[$count]['url'] = $thisMenu['url'];
            $ret[$count]['route'] = $thisMenu['route'];
            $count++;
            if(is_array($thisMenu['children'])){
                // Procura pelo current nos filhos
                foreach ($thisMenu['children'] as $k => $v){
                    if($v['url'] == $current){
                            $ret[$count]['name'] = $v['name'];
                            $ret[$count]['url'] = $v['url'];
                            $ret[$count]['route'] = $v['route'];
                            $count++;
                            break;
                    }
                }
            }
        }
        if(is_array($itens)){
            foreach ($itens as $k => $v){
                if(isset ($v['name'])){
                    $ret[$count]['name'] = $v['name'];
                    $ret[$count]['url'] = (isset ($v['url'])) ? $v['url'] : null;
                    $ret[$count]['route'] = (isset ($v['route'])) ? true : false;
                    $count++;
                }
            }
        }
        return $this->engine->render('MastopMenuBundle:Templates:breadcrumbs.html.twig', array('title' => $title, 'area' => $area, 'attrs' => $attrs, 'crumbs' => $ret));
    }

    private function prepareLinks($menu) {
        $childs = $menu->getChildren();
        $ret = array();
        if (count($childs) > 0) {
            $childs = $childs->toArray();
            usort($childs, function($a, $b) {
                        return $a->getOrder() > $b->getOrder() ? 1 : -1;
                    });
            foreach ($childs as $child) {
                $ret[$child->getCode()]['name'] = $child->getName();
                $ret[$child->getCode()]['title'] = $child->getTitle();
                $ret[$child->getCode()]['role'] = $child->getRole();
                $ret[$child->getCode()]['url'] = $child->getUrl();
                $ret[$child->getCode()]['newwindow'] = $child->getNewWindow();
                $ret[$child->getCode()]['route'] = $child->getRoute();
                if (count($child->getChildren()) > 0) {
                    $ret[$child->getCode()]['children'] = $this->prepareLinks($child);
                } else {
                    $ret[$child->getCode()]['children'] = null;
                }
            }
        }
        return $ret;
    }

}