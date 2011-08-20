<?php

/**
 * Mastop/SystemBundle/Document/Parameters.php
 *
 * Document que representa um parâmetro.
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

namespace Mastop\SystemBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(
 *   collection="parameters",
 *   repositoryClass="Mastop\SystemBundle\Document\ParametersRepository"
 * )
 * @ODM\UniqueIndex(keys={"bundle"="asc", "name"="asc"})
 * @ODM\UniqueIndex(keys={"children.name"="asc", "children.user"="asc"})
 */
class Parameters
{
    /**
     * ID
     *
     * @var string
     * @ODM\Id(strategy="none")
     */
    protected $id;

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
     * Descrição
     *
     * @var string
     * @ODM\String
     */
    protected $desc;
    
    /**
     * Bundle
     *
     * @var string
     * @ODM\String
     */
    protected $bundle;
    
    /**
     * Children
     *
     * @var array
     * @ODM\EmbedMany(targetDocument="Mastop\SystemBundle\Document\Children")
     */
    protected $children = array();

    /**
     * Ordem
     *
     * @var string
     * @ODM\Int
     * @ODM\Index(order="asc")
     */
    protected $order = 0;
    
    /**
     * Role
     *
     * @var string
     * @ODM\String
     */
    protected $role = 'ROLE_SUPERADMIN';

    /**
     * Evento para setar o ID antes de inserir
     *
     * @var string
     * @ODM\prePersist
     */
    public function prePersist(){
        $this->setId($this->bundle.'-'.$this->name);
    }
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set id
     *
     * @param custom_id $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return custom_id $id
     */
    public function getId()
    {
        return $this->id;
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
     * Set desc
     *
     * @param string $desc
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    /**
     * Get desc
     *
     * @return string $desc
     */
    public function getDesc()
    {
        return $this->desc;
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
     * Add children
     *
     * @param Mastop\SystemBundle\Document\Children $children
     */
    public function addChildren(\Mastop\SystemBundle\Document\Children $children)
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
}