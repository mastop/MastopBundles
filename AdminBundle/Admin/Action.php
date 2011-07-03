<?php

/**
 * Generates Admin with AdminGeneration configuration
 *
 * @author André Simões <afsdede92@gmail.com>
 */

namespace Mastop\AdminBundle\Admin;

class Action {
    
    private $name;
    private $route;
    private $options;
    
    public function __construct($name, $route,  array $options = array()) {
        
        $this->setName($name);
        $this->setRoute($route);
        $this->setOptions($options);
        
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getRoute() {
        return $this->route;
    }

    public function setRoute($route) {
        $this->route = $route;
    }

    public function getOptions() {
        return $this->options;
    }

    public function setOptions($options) {
        $this->options = $options;
    }

    public function addOption($name, $option){
        $this->options[$name] = $option;
    }
    
}

?>