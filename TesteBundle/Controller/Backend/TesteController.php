<?php

/**
 * Mastop/TesteBundle/Controller/Backend/TesteController.php
 *
 * Backend de teste.
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
     * @Route("/", name="admin_teste_index"),
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
        $ret['current'] = 'admin_system_home_index';
        return $ret;
    }


}