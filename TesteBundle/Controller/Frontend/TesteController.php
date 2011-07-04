<?php

namespace Mastop\TesteBundle\Controller\Frontend; // Confira o namespace!

use Mastop\SystemBundle\Controller\BaseController;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;
use Mastop\TesteBundle\Entity\Teste;

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
}