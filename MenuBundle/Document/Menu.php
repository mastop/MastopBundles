<?php
namespace Mastop\MenuBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Menu {
    
    /**
     * @ODM\Id
     */
    protected $id;
    
    /**
     * @ODM\String
     */
    protected $menuName;
    
    /**
     * @ODM\Int
     */
    protected $ordem;
    
    /**
     * @ODM\String
     */
    protected $name;
    
    /**
     * @ODM\String
     */
    protected $role;
    
    /**
     * @ODM\String
     */
    protected $url;
   
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getMenuName() {
        return $this->menuName;
    }

    public function setMenuName($menuName) {
        $this->menuName = $menuName;
    }

    public function getOrdem() {
        return $this->ordem;
    }

    public function setOrdem($ordem) {
        $this->ordem = $ordem;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }
}