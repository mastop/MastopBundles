<?php

/**
 * Description of AdminGen
 *
 * @author         André Simões <andre@mastop.com.br>
 * @copyright      André Simões - 29/05/11
 * @link           ~
 * @package        vendor\bundles\Mastop\AdminBundle\Admin
 * @version        1.0
 * @todo           Acertar tipo -> (Sim e Não) | (Select) | (Date)
 * 
 * Classe criada para realizar o controle dos tipos de dados que serão possiveis
 * serão exibidos.
 */

namespace Mastop\AdminBundle\Admin;

class FilterType{
    
    protected $type;
    protected $name;
    
    protected $typeList = array(
        'text' => true, // busca por texto comum
    );
    
    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getTypeList(){
        return $this->typeList;
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function hasDataType(){
        
        $typeList = $this->getTypeList();
        $typeData = $this->getType();
        
        
        if (isset($typeList[$typeData]) && $typeList[$typeData]){
            return true;
        }else {
            return false;
        }
        
    }
    
    public function getDataType(){
        
        
        $typeData = $this->getType();
        
        if ($this->hasDataType()){
            
            if ($typeData == 'text'){ // Verifica se é campo de texto

                return "<input type='text' name='busca[". $this->getName() . "]' />";
                
            }else {
                
                return false;
                
            }
            
        }else {
        
            return false;
        
        }
        
    }
    
}

?>