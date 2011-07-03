<?php

/**
 * Description of AdminGen
 *
 * @author         André Simões <andre@mastop.com.br>
 * @copyright      André Simões - 29/05/11
 * @link           ~
 * @package        vendor\bundles\Mastop\AdminBundle\Admin
 * @version        1.0
 * @todo           Controlar a criação da administração
 * 
 * Classe criada para realizar o controle da criação dinamica das administrações
 */

namespace Mastop\AdminBundle\Admin;

use Mastop\AdminBundle\Admin\Admin;

use Mandango\Cache\FilesystemCache;
use Mandango\Connection;
use Mandango\Mandango;

class AdminGeneration extends Admin{
    
    public function __construct($dm) {
        
        $this->setDm($dm);
        
    }

    public function configure(array $configs)
    {
        if (!$configs['dataClass']) {
            throw new \RuntimeException('Please set a dataClass.');
        }
        if(!$configs['adminPath']){
            throw new \RuntimeException('Please set a adminPath.');
        }
        if (!$configs['name']){
            $configs['name'] = $configs['dataClass'];
        }
        $this->setName($configs['name']);
        $this->setDataClass($configs['dataClass']);
        $this->setAdminPath($configs['adminPath']);

    }
    
}

?>