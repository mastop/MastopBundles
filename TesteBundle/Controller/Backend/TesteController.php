<?php

namespace Mastop\TesteBundle\Controller\Backend; // Confira o namespace!

use Mastop\SystemBundle\Controller\BaseController;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;
use Mastop\TesteBundle\Entity\Teste;

class TesteController extends BaseController {

    /**
     * @Route("/", name="_teste_admin"),
     * @Route("/super/")
     * @Template()
     */
    public function indexAction() {
        $ret = array();
        $ret['data'] = date("d/m/Y H:i:s");
        $this->get('session')->setFlash('ok', 'Tudo Certo!');
        $this->get('session')->setFlash('error', 'Tudo Errado!');
        $this->get('session')->setFlash('notice', 'Só avisando!');
        $this->mastop()->log('Nome do Site: '.$this->mastop()->param('system.site.name'));
        $form = $this->createFormBuilder(new Teste())
            ->add('name', 'text')
            ->add('price', 'money', array('currency' => 'BRL'))
            ->getForm();
        
        $ret['form'] = $form->createView();
        return $ret;
    }


}