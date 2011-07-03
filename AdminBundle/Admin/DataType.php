<?php

/**
 * Description of AdminGen
 *
 * @author         André Simões <andre@mastop.com.br>
 * @copyright      André Simões - 29/05/11
 * @link           ~
 * @package        vendor\bundles\Mastop\AdminBundle\Admin
 * @version        1.0
 * @todo           Controlar os tipos de dados que serão permitidos na Administração
 * 
 * Classe criada para realizar o controle dos tipos de dados que serão possiveis
 * serão exibidos.
 */

namespace Mastop\AdminBundle\Admin;

class DataType{
    
    protected $type;
    protected $data;
    
    protected $typeList = array(
        'integer' => true, // Retorna um inteiro comum
        'string' => true, // Retorna uma string comum
        'time' => true, // Retorna o timeStamp(formatado) baseado em um inteiro
        'date' => true, // Retorna o timeStamp(formatado) baseado em uma classe
        'email' => true, // Formata para um mailto()
        'image' => true // Coloca o dado retornado dentro de um SRC
    );
    
    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }
    
    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getTypeList(){
        return $this->typeList;
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
            
            if ($typeData == 'integer'){ // Verifica se são números

                if (is_numeric($this->getData())){
                    
                    return $this->getData();
                }else {
                    
                    return false;
                    
                }
                
            }elseif ($typeData == 'string'){ // Verifica se é um texto
                
                return $this->getData();
                
            }elseif ($typeData == 'time'){ // Verifica se é um timestamp
                
                if (is_numeric($this->getData())){
                
                  //return date('d/m/Y', $this->getData());
                    
                }else {
                    
                    return false;
                    
                }
                
            }elseif ($typeData == 'date'){

                if(is_object($this->getData())){
                    
                    return date("d/m/Y", $this->getData()->getTimestamp());
                    
                }else{
                    
                    return false;
                    
                }
                
            }elseif ($typeData == 'image'){
                
                return "<img src='". $this->getData() ."' />";
                
            }else {
                
                return false;
                
            }
            
        }else {
        
            return false;
        
        }
        
    }
    
}

?>