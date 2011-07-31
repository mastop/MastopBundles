<?php

namespace Mastop\MenuBundle\Controller\Backend;

use Mastop\SystemBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mastop\MenuBundle\Document\Menu;
use Mastop\MenuBundle\Document\MenuItem;

class MenuController extends BaseController {

    /**
     * @Route("/", name="admin_menu_menu_index")
     * @Template()
     */
    public function indexAction() {
        $title = 'Administração de Menus';
        $menus = $this->mongo('MastopMenuBundle:Menu')->findAll();
        $ret = array();
        if ($menus) {
            foreach ($menus as $menu) {
                if ($this->hasRole($menu->getRole())) {
                    $children = $menu->getChildren();
                    $ret[$menu->getBundle() . '-' . $menu->getCode()]['id'] = $menu->getId();
                    $ret[$menu->getBundle() . '-' . $menu->getCode()]['name'] = $menu->getName();
                    $ret[$menu->getBundle() . '-' . $menu->getCode()]['code'] = $menu->getCode();
                    $ret[$menu->getBundle() . '-' . $menu->getCode()]['bundle'] = $menu->getBundle();
                    if ($children) {
                        $children = $children->toArray();
                        usort($children, function($a, $b) {
                                    return $a->getOrder() > $b->getOrder() ? 1 : -1;
                                });
                        $ret[$menu->getBundle() . '-' . $menu->getCode()]['children'] = $children;
                    }
                }
            }
        }
        return array('menus' => $ret, 'title' => $title);
    }

    /**
     * @Route("/salvar", name="admin_menu_menu_save")
     */
    public function saveAction() {
        $menu = $this->mongo('MastopMenuBundle:Menu');
        $request = $this->getRequest();
        $post = $request->request;
        $dm = $this->dm();
        $routes = array_keys($this->get('router')->getRouteCollection()->all());
        if ('POST' == $request->getMethod()) {
            $menuName = $post->get('menuName');
            $menuRole = $post->get('menuRole') ? $post->get('menuRole') : 'ROLE_ADMIN';
            $menuItemName = $post->get('menuItemName');
            $menuItemTitle = $post->get('menuItemTitle');
            $menuItemNewWindow = $post->get('menuItemNewWindow');
            $menuItemUrl = $post->get('menuItemUrl');
            $menuItemOrder = $post->get('menuItemOrder');
            $menuItemRole = $post->get('menuItemRole');
            if ($menu->has('name', $menuName)) {
                $this->get('session')->setFlash('error', 'Já existe um menu com o nome <strong>"' . $menuName . '"</strong>');
                return $this->redirect($this->generateUrl('admin_menu_menu_index'));
            }
            $myMenu = new Menu();
            $myMenu->setName($menuName);
            $myMenu->setCode($this->get('mastop')->slugify($menuName));
            $myMenu->setBundle('created');
            if ($this->hasRole('ROLE_SUPERADMIN')) {
                $myMenu->setRole($menuRole);
            } else {
                $myMenu->setRole('ROLE_ADMIN');
            }
            foreach ($menuItemName as $k => $v) {
                if (!empty($v) && !empty($menuItemUrl[$k])) { // Obrigatório o preenchimento do Texto e do Link
                    $myMenuItem = new MenuItem();
                    $myMenuItem->setName($v);
                    $myMenuItem->setCode($this->get('mastop')->slugify($v));
                    if (!empty($menuItemTitle[$k])) {
                        $myMenuItem->setTitle($menuItemTitle[$k]);
                    }
                    $myMenuItem->setOrder(intval($menuItemOrder[$k]));
                    if ($menuItemRole[$k] != 'all') {
                        $myMenuItem->setRole($menuItemRole[$k]);
                    }
                    $myMenuItem->setUrl($menuItemUrl[$k]);
                    $myMenuItem->setRoute(in_array($menuItemUrl[$k], $routes));
                    $myMenuItem->setNewWindow((bool) $menuItemNewWindow[$k]);
                    $myMenu->addChildren($myMenuItem);
                }
            }
            $dm->persist($myMenu);
            $dm->flush();
            $this->get('session')->setFlash('ok', 'Menu Criado!');
            return $this->redirect($this->generateUrl('admin_menu_menu_index'));
        } else {
            $this->get('session')->setFlash('error', 'Você não pode acessar esta página.');
            return $this->redirect($this->generateUrl('admin_menu_menu_index'));
        }
    }

    /**
     * @Route("/salvaritem", name="admin_menu_menu_saveitem")
     */
    public function saveItemAction() {
        $menu = $this->mongo('MastopMenuBundle:Menu');
        $request = $this->getRequest();
        $post = $request->request;
        $dm = $this->dm();
        $routes = array_keys($this->get('router')->getRouteCollection()->all());
        if ('POST' == $request->getMethod()) {
            $id = $post->get('id');
            $code = $post->get('code');
            $codeEdit = $post->get('codeEdit');
            $myMenu = $menu->findOneById($id);
            if (!$myMenu) {
                $this->get('session')->setFlash('error', 'Menu não encontrado.');
                return $this->redirect($this->generateUrl('admin_menu_menu_index'));
            }
            $menuItemRole = $post->get('menuItemRole') == 'all' ? null : $post->get('menuItemRole');
            $menuItemName = $post->get('menuItemName');
            $menuItemTitle = $post->get('menuItemTitle');
            $menuItemNewWindow = $post->get('menuItemNewWindow');
            $menuItemUrl = $post->get('menuItemUrl');
            $menuItemOrder = $post->get('menuItemOrder');
            if (!empty($menuItemName) && !empty($menuItemUrl)) { // Obrigatório o preenchimento do Texto e do Link
                if($codeEdit){
                    $myMenuItem = $menu->getChildrenByCode($myMenu, $codeEdit);
                }else{
                    $myMenuItem = new MenuItem();
                }
                $myMenuItem->setName($menuItemName);
                if (!empty($menuItemTitle)) {
                    $myMenuItem->setTitle($menuItemTitle);
                }
                $myMenuItem->setOrder(intval($menuItemOrder));
                $myMenuItem->setRole($menuItemRole);
                $myMenuItem->setUrl($menuItemUrl);
                $myMenuItem->setRoute(in_array($menuItemUrl, $routes));
                $myMenuItem->setNewWindow((bool) $menuItemNewWindow);
                if($codeEdit){
                    list($mapping, $menuMain, $propertyPath) = $this->dm()->getUnitOfWork()->getParentAssociation($myMenuItem);
                    $this->get('session')->setFlash('ok', 'Link <strong>' . $myMenuItem->getName() . '</strong> editado!');
                    $this->mastop()->getCache()->remove(array($myMenu->getBundle().'-'.$myMenu->getCode(), $myMenu->getBundle().'-'.$myMenu->getCode().'.'.$menuMain->getCode()));
                    if($codeEdit == $code){
                        $myMenuItem->setCode($this->get('mastop')->slugify($menuItemName));
                        $dm->persist($myMenu);
                        $dm->flush();
                        return $this->redirect($this->generateUrl('admin_menu_menu_index'));
                    }else{
                        $myMenuItem->setCode($menuMain->getCode().'.'.$this->get('mastop')->slugify($menuItemName));
                        $dm->persist($myMenu);
                        $dm->flush();
                        return $this->redirect($this->generateUrl('admin_menu_menu_subs', array('id' => $id, 'code' => $code)));
                    }
                }elseif (!empty($code)) {
                    $myChild = $menu->getChildrenByCode($myMenu, $code);
                    if ($myChild) {
                        $myMenuItem->setCode($myChild->getCode() . '.' . $this->get('mastop')->slugify($menuItemName));
                        $myChild->addChildren($myMenuItem);
                        $dm->persist($myMenu);
                        $dm->flush();
                        $this->get('session')->setFlash('ok', 'Link criado dentro de <strong>' . $myChild->getName() . '</strong>!');
                        return $this->redirect($this->generateUrl('admin_menu_menu_subs', array('id' => $id, 'code' => $code)));
                    }
                } else {
                    $myMenuItem->setCode($this->get('mastop')->slugify($menuItemName));
                    $myMenu->addChildren($myMenuItem);
                    $dm->persist($myMenu);
                    $dm->flush();
                    $this->get('session')->setFlash('ok', 'Link criado para o menu <strong>' . $myMenu->getName() . '</strong>!');
                    return $this->redirect($this->generateUrl('admin_menu_menu_index'));
                }
            } else {
                $this->get('session')->setFlash('error', 'O preenchimento do texto e do link são obrigatórios.');
                return $this->redirect($this->generateUrl('admin_menu_menu_index'));
            }
        } else {
            $this->get('session')->setFlash('error', 'Você não pode acessar esta página.');
            return $this->redirect($this->generateUrl('admin_menu_menu_index'));
        }
    }

    /**
     * @Route("/deletar", name="admin_menu_menu_delete")
     */
    public function deleteAction() {
        $request = $this->getRequest();
        $id = $request->get('id');
        $code = $request->get('code');
        $menu = $this->mongo('MastopMenuBundle:Menu')->findOneById($id);
        if ($code) {
            $menuChild = $this->mongo('MastopMenuBundle:Menu')->getChildrenByCode($menu, $code);
            if (empty($menuChild)) {
                $this->get('session')->setFlash('error', 'Link não encontrado.');
                return $this->redirect($this->generateUrl('admin_menu_menu_index'));
            }
        }
        if ('POST' == $request->getMethod()) {
            if ($code) {
                if (!$menu || !$this->hasRole($menu->getRole())) {
                    $this->get('session')->setFlash('error', 'Você não pode remover este link.');
                    return $this->redirect($this->generateUrl('admin_menu_menu_index'));
                }
                list($mapping, $menuMain, $propertyPath) = $this->dm()->getUnitOfWork()->getParentAssociation($menuChild);
                $menuMain->getChildren()->removeElement($menuChild);
                $this->dm()->flush();
                $this->get('session')->setFlash('ok', 'Link <strong>' . $menuChild->getName() . '</strong> deletado.');
                
            } else {
                if (!$menu || !$this->hasRole($menu->getRole()) || $menu->getBundle() != 'created') {
                    $this->get('session')->setFlash('error', 'Você não pode remover este menu.');
                    return $this->redirect($this->generateUrl('admin_menu_menu_index'));
                }
                $this->dm()->remove($menu);
                $this->dm()->flush();
                $this->get('session')->setFlash('ok', 'Menu <strong>' . $menu->getName() . '</strong> deletado.');
            }
            return $this->redirect($this->generateUrl('admin_menu_menu_index'));
        }
        if ($code) {
            list($mapping, $menuMain, $propertyPath) = $this->dm()->getUnitOfWork()->getParentAssociation($menuChild);
            return $this->confirm('Tem certeza de que deseja remover o link "' . $menuChild->getName() . '" de ' . $menuMain->getName() . ' e todos os seus submenus?', array('id' => $menu->getId(), 'code' => $code));
        }
        return $this->confirm('Tem certeza de que deseja remover o menu "' . $menu->getName() . '" e todos os seus submenus?', array('id' => $menu->getId()));
    }

    /**
     * @Route("/submenus/{id}/{code}", name="admin_menu_menu_subs")
     * @Template()
     */
    public function subsAction($id, $code) {
        $menuMain = $this->mongo('MastopMenuBundle:Menu')->findOneById($id);
        if (!$menuMain || !$this->hasRole($menuMain->getRole())) {
            $this->get('session')->setFlash('error', 'Você não pode acessar esta página.');
            return $this->redirect($this->generateUrl('admin_menu_menu_index'));
        }
        $menuChild = $this->mongo('MastopMenuBundle:Menu')->getChildrenByCode($menuMain, $code);
        $ret = array();
        $title = 'Administração de Submenus';
        if ($menuChild) {
            $title .= ' -> ' . $menuChild->getName();
            $ret['main'] = $menuMain->getId();
            $ret['name'] = $menuChild->getName();
            $ret['code'] = $menuChild->getCode();
            $children = $menuChild->getChildren();
            if ($children) {
                $children = $children->toArray();
                usort($children, function($a, $b) {
                            return $a->getOrder() > $b->getOrder() ? 1 : -1;
                        });
                $ret['children'] = $children;
            }
        }
        return array('menu' => $ret, 'title' => $title);
    }
    /**
     * @Route("/link/{id}/{code}", name="admin_menu_menu_link")
     * @Template()
     */
    public function linkAction($id, $code) {
        $request = $this->getRequest();
        $id = $request->get('id');
        $codeEdit = $request->get('codeEdit');
        $code = $request->get('code');
        $menu = $this->mongo('MastopMenuBundle:Menu')->findOneById($id);
        $linkMain = $this->mongo('MastopMenuBundle:Menu')->getChildrenByCode($menu, $code);
        $ret = array();
        if($codeEdit){
            $link = $this->mongo('MastopMenuBundle:Menu')->getChildrenByCode(($code == $codeEdit) ? $menu : $linkMain, $codeEdit);
            $title = 'Editar link '.$link->getName();
        }else{
            $link = new MenuItem();
            $title = 'Novo link em '.$linkMain->getName();
        }
        return array('link' => $link, 'title' => $title, 'code'=>$code, 'id'=>$id);
    }

}