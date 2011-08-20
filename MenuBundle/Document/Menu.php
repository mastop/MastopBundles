<?php
<<<<<<< HEAD
=======

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

>>>>>>> 8199136e6d71bfd9371a8df7ce9e3b78be00578a
namespace Mastop\MenuBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

<<<<<<< HEAD
/** 
=======
/**
>>>>>>> 8199136e6d71bfd9371a8df7ce9e3b78be00578a
 * @ODM\Document(
 *   collection="menu",
 *   repositoryClass="Mastop\MenuBundle\Document\MenuRepository"
 * )
<<<<<<< HEAD
 * @ODM\UniqueIndex(keys={"bundle"="asc", "name"="asc"})
 */
class Menu {
    
    /**
     * Id
     * 
     * @ODM\Id
     */
    protected $id;
    
    /**
     * menuName
     * 
     * @var string
     * @ODM\String
     */
    protected $menuName;
    
    /**
     * Children
     *
     * @var array
     * @ODM\EmbedMany(targetDocument="Mastop\MenuBundle\Document\Children")
     */
    protected $children = array();
    
    /**
     * name
     * 
=======
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
>>>>>>> 8199136e6d71bfd9371a8df7ce9e3b78be00578a
     * @var string
     * @ODM\String
     */
    protected $name;
    
    /**
<<<<<<< HEAD
     * role
     * 
     * @var string
     * @ODM\String
     */
    protected $role;
    
    /**
     * url
     * 
     * @var string
     * @ODM\String
     */
    protected $url;
   
=======
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
>>>>>>> 8199136e6d71bfd9371a8df7ce9e3b78be00578a
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
<<<<<<< HEAD
     * Set menuName
     *
     * @param string $menuName
     */
    public function setMenuName($menuName)
    {
        $this->menuName = $menuName;
    }

    /**
     * Get menuName
     *
     * @return string $menuName
     */
    public function getMenuName()
    {
        return $this->menuName;
    }

    /**
     * Add children
     *
     * @param Mastop\MenuBundle\Document\Children $children
     */
    public function addChildren(\Mastop\MenuBundle\Document\Children $children)
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
=======
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
>>>>>>> 8199136e6d71bfd9371a8df7ce9e3b78be00578a
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
<<<<<<< HEAD
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
=======
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
>>>>>>> 8199136e6d71bfd9371a8df7ce9e3b78be00578a
    }
}