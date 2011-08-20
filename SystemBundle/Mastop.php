<?php

/**
 * Mastop/SystemBundle/Mastop.php
 *
 * Serviço "mastop"
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



namespace Mastop\SystemBundle;
use Symfony\Component\HttpKernel\KernelInterface;
use Mastop\SystemBundle\Cache\Apc;

class Mastop
{
    private $kernel;
    private $container;
    private $apc;

/**
* @param string KernelInterface $kernel
*/
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->container = $kernel->getContainer();
    }

    public function log($message, $type = 'info', array $context = array())
    {
        $types = array('info', 'debug', 'notice', 'warn', 'err', 'crit', 'alert', 'emerg');
        if(in_array($type, $types)) $this->container->get('logger')->$type($message, $context);
    }

    public function getDocumentManager($name = null)
    {
        if (!$name) {
            return $this->container->get('mastop.dm');
        }
        $dmServiceName = sprintf('doctrine.odm.mongodb.%s_document_manager', $name);
        if (!$this->container->has($dmServiceName)) {
            throw new \InvalidArgumentException(sprintf('Impossível encontrar o Doctrine ODM DocumentManager "%s"', $dmName));
        }
        return $this->container->get($dmServiceName);
    }
    public function getCache($key = null, $default = null){
        if (is_null($this->apc)) {
            $this->apc = new Apc();
        }
        return (is_null($key)) ? $this->apc : $this->apc->get($key, $default);
    }
    public function setCache($key, $var, $ttl = null){
        return (is_null($ttl)) ? $this->getCache()->set($key, $var) : $this->getCache()->set($key, $var, $ttl);
    }
    public function clearCache($type = 'user'){
        return $this->getCache()->clear($type);
    }
    /**
     *Exemplo: param("bundle.cat.child")
     *Exemplo: param("bundle.cat.child.user")
     * @param type $name
     * @return type string
     */
    public function param($name){
        $cache = $this->getCache();
        if($cache->has($name)){
            return $cache->get($name);
        }else{
            $params = explode('.', $name); // 0 = Bundle, 1 = Name, 2 = ChildName [, 3 = User]
            $paramId = $params[0].'-'.$params[1];
            $parameter = $this->getDocumentManager()->getRepository('MastopSystemBundle:Parameters')->findOneById($paramId);
            if(!$parameter){
                throw new \Exception("Parâmetro ".$paramId." não existe.");
            }
            $childs = $parameter->getChildren();
            foreach ($childs as $child){
                if($child->getUser() == 'system'){
                    $cache->set($parameter->getBundle().'.'.$parameter->getName().'.'.$child->getName(), $child->getValue());
                }else{
                    $cache->set($parameter->getBundle().'.'.$parameter->getName().'.'.$child->getName().'.'.$child->getUser(), $child->getValue());
                }
            }
            return $cache->get($name);
        }
    }
    /**
     * Função para transformar uma string em um slug
     *
     * @param string $str
     * @param array $replace
     * @param string $delimiter
     * @return string Slug 
     */
    public function slugify($str, $replace=array(), $delimiter='-') {
        if (!empty($replace)) {
            $str = str_replace((array) $replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }
}

