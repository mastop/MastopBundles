<?php

namespace Mastop\AdminBundle\Annotation;

/**
 * The @Debug annotation
 *
 * @author Andre Simões <andre@mastop.com.br>
 */
class Debug {
    
    protected $msg = "Sem mensagem";

    public function __construct(array $values) 
    {
        
        foreach($values as $k => $v){
            
            if ($k == 'msg'){
            
                $this->msg = $v;
                
            }
            
        }
        
        echo $this->msg;
        
    }
    
    public function debug($msg)
    {
        
        if ($msg != ""){
            $this->msg = $msg;
        }
        
        echo $this->msg;
        
    }
    
    
}

?>
