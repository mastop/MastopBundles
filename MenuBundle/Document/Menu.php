<?php
namespace Mastop\MenuBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(
 *   collection="menu",
 *   repositoryClass="Mastop\MenuBundle\Document\MenuRepository"
 * )
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
     * @var string
     * @ODM\String
     */
    protected $name;
    
    /**
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
}