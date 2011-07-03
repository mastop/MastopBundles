<?php

/**
 * Description of AdminGen
 *
 * @author         André Simões <andre@mastop.com.br>
 * @copyright      André Simões - 15/05/11
 * @link           ~
 * @package        src\Mastop\AdminBundle\Admin
 * @version        1.0
 * @todo           Processo de envio do e-mail
 * 
 * Classe criada para realizar o envio do e-mail
 */

namespace Mastop\AdminBundle\Admin;

use WhiteOctober\AdminBundle\DataManager\Mandango\Admin\MandangoAdmin;

class AdminGen extends MandangoAdmin{
    
    protected function configure()
    {
        $this
            ->setName("User")
            ->setDataClass('Model\User')
            ->addActions(array(
                'mandango.crud',
            ))
            ->addFields(array(
                'nome',
                'idade',
                'email',
            ))
        ;
    }
    
}

?>
