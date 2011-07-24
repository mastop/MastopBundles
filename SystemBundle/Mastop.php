<?php

/*
* This file is part of the Mastop/SystemBundle
*
* (c) Mastop Iternet Development
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
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

