<?php

/**
 * Mastop/MenuBundle/Document/MenuItem.php
 *
 * Document que representa um item de menu.
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

namespace Mastop\MenuBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class MenuItem
{
    /**
     * Código
     *
     * @var string
     * @ODM\String
     */
    protected $code;
    
    /**
     * Nome
     *
     * @var string
     * @ODM\String
     */
    protected $name;
    
    /**
     * Título
     *
     * @var string
     * @ODM\String
     */
    protected $title;
    
    /**
     * Ordem
     *
     * @var string
     * @ODM\Int
     */
    protected $order = 0;
    
    /**
     * Role para VISUALIZAÇÃO
     *
     * @var string
     * @ODM\String
     */
    protected $role;
    
    /**
     * URL
     *
     * @var string
     * @ODM\String
     */
    protected $url;
    
    /**
     * Nova Janela?
     *
     * @var string
     * @ODM\Boolean
     */
    protected $newWindow = false;
    
    /**
     * É Route?
     *
     * @var string
     * @ODM\Boolean
     */
    protected $route = false;

    /**
     * Children
     *
     * @var array
     * @ODM\EmbedMany(targetDocument="Mastop\MenuBundle\Document\MenuItem")
     */
    protected $children = array();
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string $code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set order
     *
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * Get order
     *
     * @return int $order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set role
     *
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * Get role
     *
     * @return string $role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set newWindow
     *
     * @param boolean $newWindow
     */
    public function setNewWindow($newWindow)
    {
        $this->newWindow = $newWindow;
    }

    /**
     * Get newWindow
     *
     * @return boolean $newWindow
     */
    public function getNewWindow()
    {
        return $this->newWindow;
    }

    /**
     * Add children
     *
     * @param Mastop\MenuBundle\Document\MenuItem $children
     */
    public function addChildren(\Mastop\MenuBundle\Document\MenuItem $children)
    {
        $this->children[] = $children;
    }

    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection $children
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set route
     *
     * @param boolean $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * Get route
     *
     * @return boolean $route
     */
    public function getRoute()
    {
        return $this->route;
    }
}