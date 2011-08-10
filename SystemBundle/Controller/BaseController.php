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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;

abstract class BaseController extends Controller {

    private $dm, $mastop, $cookie;
    
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
    /**
     * Verifica se o usuário tem determinado ROLE
     * 
     * @param string $role
     * @return bool
     */
    public function hasRole($role){
        return $this->get('security.context')->isGranted($role);
    }
    
    /**
     * Função para tela de confirmação
     * 
     * @param string $msg Mensagem para o usuário
     * @param array $vars Variáveis para passar para o action
     * @param string $action Route do action (null = action atual)
     * @param array $opts Campos ocultos na tela de confirmação
     * @param string $color Cor de BG da tela
     * @param string $img Imagem para a tela
     * @return Response Template renderizado com a tela de confirmação
     */
    public function confirm($msg, $vars = array(), $action = null, $opts = array(), $color = 'grey', $img = null){
        if(!$action){
            $action = $this->get('router')->match($this->get('request')->getPathInfo());
            $action = $action['_route'];
        }
        return $this->render('MastopSystemBundle:Backend:confirm.html.twig', array('msg' => $msg, 'opts' => $opts, 'action' => $action, 'vars' => $vars, 'color' => $color, 'img' => $img));
    }
    public function setCookie($name, $value = null, $expire = 0, $path = '/', $domain = null, $secure = false, $httpOnly = true){
        $this->cookie = new Cookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }
    public function redirect($url, $status = 302)
    {
        $response = new RedirectResponse($url, $status);
        if(is_object($this->cookie)){
            $response->headers->setCookie($this->cookie);
        }
        return $response;
    }
}