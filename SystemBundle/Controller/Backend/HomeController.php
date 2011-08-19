<?php

/**
 * Mastop/SystemBundle/Controller/Backend/HomeController.php
 *
 * Controller padrão para o Dashboard administrativo da aplicação.
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