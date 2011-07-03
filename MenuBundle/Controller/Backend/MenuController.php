<?php

namespace Mastop\MenuBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Mastop\MenuBundle\Form\MenuForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mastop\MenuBundle\Document\Menu;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends Controller{

    /**
     * @Route("/", name="_menu_admin")
     * @Template()
     */
    public function indexAction()
    {
        $mongo = $this->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('MastopMenuBundle:Menu');
        $menu = $mongo->findAll();
        $ret = array();
        foreach($menu as $k => $v){
            $ret[$k]['menuName'] = $v->getMenuName();
            $ret[$k]['name'] = $v->getName();
            $ret[$k]['role'] = $v->getRole();
            $ret[$k]['url'] = $v->getUrl();
        }
        return array(
            'menu' => $ret,
            );
    }
    
    /**
     * @Route("/novo", name="_menu_admin_new")
     * @Template()
     */
    public function newAction()
    {
        $mongo = $this->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('MastopMenuBundle:Menu');
        $fatherArray = $mongo->findAll();
        foreach($fatherArray as $k => $v){
            $father[$v->getId()] = $v->getMenuName();
        }
        $menu = new Menu();
        $form = $this->createFormBuilder()
                ->add('menuName', 'text')
                ->add('name', 'text')
                ->add('role', 'text')
                ->add('url', 'text')
                ->add('father', 'choice', array(
                    'choices' => $father
                ))
                ->getForm();
        return array(
            'form' => $form->createView(),
            );
    }
    
    /**
     * @Route("/editar/{id}", name="_menu_admin_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $mongo = $this->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('MastopMenuBundle:Menu');
        $menu = $mongo->find($id);
        if(!$menu){
            $this->get('session')->setFlash('notice', "Erro ao editar o menu de id: $id");
            return true;
        }else{
            $form = $this->get('form.factory')
                    ->create(new MenuForm());
            $form->setData($menu);
            return array(
                'form' => $form->createView(),
                'id'   => $id,
            );
        }
    }
    
    /**
     * @Route("/armazenar", name="_menu_admin_store")
     */
    public function storeAction()
    {
        $request = $this->get('request');
        $id = $request->request->get('id');
        $mongo = $this->get('doctrine.odm.mongodb.document_manager');
        if($id){
            $menu = $mongo->getRepository('MastopMenuBundle:Menu')->find($id);
        }else{
            $menu = new Menu();
        }
        $form = $this->get('form.factory')->create(new MenuForm(), $menu);
        if($request->getMethod() == 'POST'){
            $form->bindRequest($request);
            if ($form->isValid()) {
                $mongo->persist($menu);
                $mongo->flush();
                $this->get('session')->setFlash('notice', 'Menu cadastrado com sucesso');
            }else{
                $this->get('session')->setFlash('notice', 'Erro! Poha!');
                return $this->render('MastopMenuBundle:Menu:new.html.twig', array('form' => $form->createView()));
            }
            return $this->redirect($this->generateUrl('_menu_admin'));
        }
    }
    
    /**
     * @Route("/confirma/deletar/{id}", name="_menu_admin_confirm_delete")
     * @Template()
     */
    public function confirmDeleteAction($id)
    {
        $mongo = $this->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('MastopMenuBundle:Menu')
                ->find($id);
                
        return array(
            'name' => $mongo->getName(),
            'id'   => $mongo->getId(),
        );
        
    }
    
    /**
     * @Route("/deletar/{id}", name="_menu_admin_delete")
     */
    public function deleteAction($id)
    {
        $mongo = $this->get('doctrine.odm.mongodb.document_manager');
        $menu = $mongo->getRepository('MastopMenuBundle:Menu')
                ->find($id);
        $mongo->remove($menu);
        $teste = $mongo->flush();
        if($mongo->flush()){
            $this->get('session')->setFlash('notice', 'Erro ao deletar menu!');
        }else{
            $this->get('session')->setFlash('notice', 'Menu deletado com sucesso!');
        }
            return $this->redirect($this->generateUrl('_menu_admin'));
    }

    /**
     * @Route("/renderizar/{menuName}", name="_menu_admin_render")
     * @Template()
     */
    public function renderAction($menuName)
    {
        $mongo = $this->get('doctrine.odm.mongodb.document_manager');
        $menu = $mongo->getRepository('MastopMenuBundle:Menu');
        $menus = $menu->findByMenuName($menuName);
        $ret = array();
        foreach($menus as $k => $v){
            $ret[$k]['menuName'] = $v->getMenuName();
            $ret[$k]['name'] = $v->getName();
            $ret[$k]['role'] = $v->getRole();
            $ret[$k]['url'] = $v->getUrl();
        }
        return array(
            'menu' => $ret,
            );
    }
}