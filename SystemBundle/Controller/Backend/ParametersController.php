<?php

namespace Mastop\SystemBundle\Controller\Backend;

use Mastop\SystemBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mastop\SystemBundle\Document\Parameters;
use Mastop\SystemBundle\Form\ParametersType;

class ParametersController extends BaseController {

    /**
     * @Route("/", name="admin_system_parameters_index")
     * @Template()
     */
    public function indexAction() {
        $parameters = $this->mongo('MastopSystemBundle:Parameters')->findAllByOrder();
        $form = $this->createFormBuilder();
        $ret = array();
        foreach ($parameters as $p => $param) {
            $ret[$p]['title'] = $param->getTitle();
            $ret[$p]['desc'] = $param->getDesc();
            $childs = $param->getChildren();
            $form->add($param->getId(), 'collection');
            foreach ($childs as $child) {
                if($child->getUser() == 'system'){
                    $form->get($param->getId())->add($child->getName(), $child->getFieldtype(), array_merge(array('required'=>false, 'label' => $child->getTitle(), 'data' => (($child->getFieldtype() != 'checkbox') ? $child->getValue() : (bool) $child->getValue()), 'attr' => array('title' => $child->getDesc())), $child->getOpts()));
                }
            }
        }
        $form = $form->getForm();
        return array('form' => $form->createView(), 'tabs' => $ret);
    }

    /**
     * @Route("/salvar", name="admin_system_parameters_save")
     */
    public function saveAction() {
        $parameters = $this->mongo('MastopSystemBundle:Parameters');
        $request = $this->getRequest();
        $form = $request->request->get('form');
        $dm = $this->dm();
        if ('POST' == $request->getMethod()) {
            $this->mastop()->clearCache();
            foreach ($form as $tk => $tv){
                if(is_array($tv)){
                    $param = $parameters->findOneById($tk);
                    if($param){
                        $childs = $param->getChildren();
                        foreach ($childs as $c => $cv){
                            if(isset ($tv[$cv->getName()])){
                                $cv->setValue($tv[$cv->getName()]);
                            }elseif($cv->getFieldtype() == 'checkbox' && $cv->getUser() == 'system'){
                                $cv->setValue('0'); // Se for checkbox, desmarca pois o mesmo não é enviado no POST
                            }
                        }
                        $dm->persist($param);
                    }
                }
            }
            $dm->flush();
            $this->get('session')->setFlash('ok', 'Preferências Atualizadas!');
            return $this->redirect($this->generateUrl('admin_system_parameters_index'));
        }else{
            $this->get('session')->setFlash('error', 'Você não pode acessar esta página.');
            return $this->redirect($this->generateUrl('admin_system_parameters_index'));
        }
    }

    /**
     * @Route("/clearcache", name="admin_system_parameters_clearcache")
     */
    public function clearcacheAction() {
        $this->mastop()->clearCache('user');
        $this->mastop()->clearCache('system');
        $this->get('session')->setFlash('ok', 'Cache Limpo!');
        return $this->redirect($this->generateUrl('admin_system_parameters_index'));
    }

}
