<?php

/**
 * Mastop/MenuBundle/Menu.php
 *
 * Serviço "mastop.menu".
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
        // Se o nome do template não é o endereço direto pra o Twig
        if(strpos($template, ':') === false){
            return $this->engine->render('MastopMenuBundle:Templates:' . $template . '.html.twig', array('menu' => $document, 'current' => $current, 'attrs' => $attributes, 'root' => true, 'depth' => $depth));
        }
        return $this->engine->render($template, array('menu' => $document, 'current' => $current, 'attrs' => $attributes, 'root' => true, 'depth' => $depth));
    }

    public function breadcrumbs($title = null, $current = null, $itens = false, $attrs = array(), $area = 'admin', $template = false) {
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
        $thisBundleController = ($area == 'admin') ? 'admin_' . $thisUrl[1] . '_' . $thisUrl[2]: $thisUrl[0] . '_' . $thisUrl[1];
        $thisMenu = null;
        foreach ($menu as $k => $v) {// Procura o bundle e controller no menu 
            if ($k != 'dashboard' && strpos($v['url'], $thisBundleController) !== false) {
                $thisMenu = $v;
                break;
            }
        }
        if(!$thisMenu) {// Se não achar o bundle e controller, procura só o bundle
            foreach ($menu as $k => $v){
                if ($k != 'dashboard' && strpos($v['url'], $thisBundle) !== false) {
                    $thisMenu = $v;
                    break;
                }
            } 
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
        if(!$template){
            return $this->engine->render('MastopMenuBundle:Templates:breadcrumbs.html.twig', array('title' => $title, 'area' => $area, 'attrs' => $attrs, 'crumbs' => $ret));
        }
        return $this->engine->render($template, array('title' => $title, 'area' => $area, 'attrs' => $attrs, 'crumbs' => $ret));
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