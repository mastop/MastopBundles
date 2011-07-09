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
     * Exemplo: $this->mongo("MastopSystemBundle:Parameters")
     * Exemplo: $this->mongo("MastopSystemBundle:Parameters", "OutroDocumentManager")
     * 
     * @param string $repository
     * @param string $dm
     * @return Mastop\SystemBundle\Document\BaseRepository
     */
    public function mongo($repository, $dm = null) {
        if (is_null($this->dm)) {
            $this->dm = $this->get('mastop')->getDocumentManager($dm);
        }
        return $this->dm->getRepository($repository);
    }
    /**
     * Exemplo: $this->trans('Fernando Lindo');
     * Exemplo: $this->trans('Fernando %adjetivo%', array('%adjetivo%'=>'Bonito'));
     * Exemplo: $this->trans('Fernando Legal', array(), 'adjetivos'); // Arquivo de tradução será procurado em Resources/translations/adjetivos.ptBR.yml
     * Exemplo: $this->trans('Boa tarde!', array(), 'messages', 'en'); // Arquivo de tradução será procurado em Resources/translations/messages.en.yml
     * 
     * @param type $msg Texto a traduzir
     * @param array $parameters Parâmetros para substituição
     * @param type $domain Arquivo de tradução
     * @param type $locale Linguagem
     * @return type string
     */
    public function trans($msg, array $parameters = array(), $domain = 'messages', $locale = null){
        return $this->get('translator')->trans($msg, $parameters, $domain, $locale);
    }
}