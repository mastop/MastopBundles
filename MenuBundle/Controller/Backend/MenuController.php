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
        $arrayMenu = $this->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('MastopMenuBundle:Menu')
                ->findAll();
        $ret = array();
        foreach($arrayMenu as $k => $v){
            $ret[$k]['menuName'] = $v->getMenuName();
            $ret[$k]['ordem'] = $v->getOrdem();
            $ret[$k]['name'] = $v->getName();
            $ret[$k]['role'] = $v->getRole();
            $ret[$k]['url'] = $v->getUrl();
        }
        return array(
            'menu' => $ret,
            );
    }
    
    /**
     * @Route("/new", name="_menu_admin_new")
     * @Template()
     */
    public function newAction()
    {
        $factory = $this->get('form.factory');
        $form = $factory->create(new MenuForm());
        return array(
            'form' => $form->createView(),
            );
    }
    
    /**
     * @Route("/edit/{id}", name="_menu_admin_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $mongo = $this->get('doctrine.odm.mongodb.document_manager');
        $menu = $mongo->getRepository('MastopMenuBundle:Menu')
                ->find($id);
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
     * @Route("/store", name="_menu_admin_store")
     */
    public function storeAction()
    {
        $mongo = $this->get('doctrine.odm.mongodb.document_manager');
        $request = $this->get('request');
        $form = $this->get('form.factory')->create(new MenuForm());
        if($request->getMethod() == 'POST'){
            $form->bindRequest($request);
            $menu = $form->getData();
            if ($form->isValid()) {
                $id = $request->request->get('id');
                if($id){
                    $menu->setId($id);
                    $mongo->merge($menu);
                }else{
                    $mongo->persist($menu);
                }
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
     * @Route("/confirm/delete/{id}", name="_menu_admin_confirm_delete")
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
     * @Route("/delete/{id}", name="_menu_admin_delete")
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
     * @Route("/render/{menuName}", name="_menu_admin_render")
     * @Template()
     */
    public function renderAction($menuName)
    {
        $mongo = $this->get('doctrine.odm.mongodb.document_manager');
        $menu = $mongo->getRepository('MastopMenuBundle:Menu')
                ->find($menuName);
        $ret = array();
        foreach($menu as $k => $v){
            $ret[$k]['menuName'] = $v->getMenuName();
            $ret[$k]['ordem'] = $v->getOrdem();
            $ret[$k]['name'] = $v->getName();
            $ret[$k]['role'] = $v->getRole();
            $ret[$k]['url'] = $v->getUrl();
        }
        return array(
            'menu' => $ret,
            );
    }
}