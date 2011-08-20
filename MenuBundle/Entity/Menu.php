<?php

namespace Mastop\MenuBundle\Entity;

/**
 * Description of Menu
 *
 * @author rafael
 */
class Menu {
    
    protected $menuName;
    protected $ordem;
    protected $name;
    protected $role;
    protected $url;
    
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
