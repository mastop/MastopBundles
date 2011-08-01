<?php

namespace Mastop\TesteBundle\Controller\Frontend; // Confira o namespace!

use Mastop\SystemBundle\Controller\BaseController;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

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