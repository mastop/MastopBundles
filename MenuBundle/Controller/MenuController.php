<?php
namespace Mastop\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Mastop\MenuBundle\Form\MenuForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mastop\MenuBundle\Document\Menu;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends Controller{

    /**
     * @Route("/", name="_menu")
     * @Template()
     */
    public function indexAction()
    {
        $arrayMenu = $this->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('MastopMenuBundle:Menu')->findAll();
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
     * @Route("/new", name="_menu_new")
     * @Template()
     */
    public function newAction()
    {
        $factory = $this->get('form.factory');
        $form = $factory->create(new MenuForm());
        return $this->render('MastopMenuBundle:Menu:new.html.twig', array(
            'form' => $form->createView(),
            ));
    }
    
    /**
     * @Route("/store", name="_menu_store")
     * @Template()
     */
    public function storeAction()
    {
        $mongo = $this->get('doctrine.odm.mongodb.document_manager');
        $request = $this->get('request');
        $form = $this->get('form.factory')->create(new MenuForm());
        if($request->getMethod() == 'POST'){
            $form->bindRequest($request);
            if ($form->isValid()) {
                $menu = $form->getData();
                //$menu = new Menu();
                //exit(print_r($menu));
                $mongo->persist($menu);
                $mongo->flush();
                $this->get('session')->setFlash('notice', 'Menu cadastrado com sucesso');
            }else{
                $this->get('session')->setFlash('notice', 'Erro! Poha!');
                 return $this->render('MastopMenuBundle:Menu:new.html.twig', array('form' => $form->createView()));
            }
            return $this->redirect($this->generateUrl('_menu'));
        }
    }
    
    /**
     * @Route("/render/{menuName}", name="_menu_render")
     * @Template()
     */
    public function renderAction($menuName)
    {
        $mandango = $this->get('mandango');
        $query = $mandango->getRepository('Model\Menu')->createQuery();
        $query->sort(array('menuName' == $menuName));
        echo $query->getMenuName();
    }
}