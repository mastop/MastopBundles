<?php

namespace Mastop\SystemBundle\Controller\Backend; // Confira o namespace!

use Mastop\SystemBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomeController extends BaseController {
    
    /**
     * @Route("/", name="admin_system_home_index")
     */
    public function indexAction() {
        return $this->render('MastopSystemBundle:Backend\Home:index.html.twig');
    }

}