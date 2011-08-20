<?php

/**
 * Mastop/TesteBundle/Controller/Frontend/TesteController.php
 *
 * Frontend de teste.
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

namespace Mastop\TesteBundle\Controller\Frontend; // Confira o namespace!

use Mastop\SystemBundle\Controller\BaseController;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("", requirements={"_scheme" = "https"})
 */
class TesteController extends BaseController {

    /**
     * @Route("/", name="_teste")
     * @Template()
     */
    public function indexAction() {
        $mastopThemes = $this->get('mastop.themes');
        //$mastopThemes->setName("outro");
        $this->get('session')->setFlash('ok', 'Tudo Certo!');
        $this->get('session')->setFlash('error', 'Tudo Errado!');
        $this->get('session')->setFlash('notice', 'Só avisando!');
        return array('fer' => $mastopThemes->getName());
    }
    /**
     * @Route("/cookie", name="_teste_cookie")
     */
    public function cookieAction() {
        $this->setCookie('teste', 'teste', time() + (3600 * 48));
        return $this->redirect($this->generateUrl('_teste'));
    }
    
    /**
     * @Route("/login", name="login")
     * @Template()
     */
    public function loginAction()
    {
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        );
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/logout", name="_logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }
}