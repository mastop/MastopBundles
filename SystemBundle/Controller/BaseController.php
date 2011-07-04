<?php

/*
 * This file is part of the Mastop/SystemBundle
 *
 * (c) Mastop Iternet Development
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mastop\SystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class BaseController extends Controller {

    private $dm, $mastop;
    
    /**
    * @return Mastop\SystemBundle\Mastop
    */
    public function mastop() {
        if (is_null($this->mastop)) {
            $this->mastop = $this->get('mastop');
        }
        return $this->mastop;
    }
    
    /**
     * @return Doctrine\ODM\MongoDB\DocumentManager
     */
    public function dm($name = null) {
        if (is_null($this->dm)) {
            $this->dm = $this->get('mastop')->getDocumentManager($name);
        }
        return $this->dm;
    }
    /**
     * @return Doctrine\ODM\MongoDB\DocumentRepository
     */
    public function mongo($repository, $dm = null) {
        if (is_null($this->dm)) {
            $this->dm = $this->get('mastop')->getDocumentManager($dm);
        }
        return $this->dm->getRepository($repository);
    }
}