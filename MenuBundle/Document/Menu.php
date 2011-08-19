<?php

/**
 * Mastop/MenuBundle/Document/Menu.php
 *
 * Document que representa um menu.
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
 * @ODM\Document(
 *   collection="menu",
 *   repositoryClass="Mastop\MenuBundle\Document\MenuRepository"
 * )
 * @ODM\UniqueIndex(keys={"bundle"="asc", "code"="asc"})
 */
class Menu
{
    /**
     * ID
     *
     * @var string
     * @ODM\Id
     */
    protected $id;

    /**
     * Código
     *
     * @var string
     * @ODM\String
     */
    protected $code;
    
    /**
     * Bundle
     *
     * @var string
     * @ODM\String
     */
    protected $bundle;
    
    /**
     * Nome
     *
     * @var string
     * @ODM\String
     */
    protected $name;
    
    /**
     * Role para EDITAR na Administração
     *
     * @var string
     * @ODM\String
     */
    protected $role = 'ROLE_SUPERADMIN';
    
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
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
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
     * Set bundle
     *
     * @param string $bundle
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;
    }

    /**
     * Get bundle
     *
     * @return string $bundle
     */
    public function getBundle()
    {
        return $this->bundle;
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
}