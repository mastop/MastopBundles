<?php

/**
 * Generates Admin with AdminGeneration configuration
 *
 * @author André Simões <afsdede92@gmail.com>
 */

namespace Mastop\AdminBundle\Admin;

class Field {
    
    private $name;
    private $type;
    private $options;
    
    public function __construct($name, $type = 'string', array $options = array()) {
        
        $this->setName($name);
        $this->setType($type);
        $this->setOptions($options);
        
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
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