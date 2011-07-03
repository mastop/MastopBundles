<?php

namespace Mastop\TesteBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use Mastop\TesteBundle\Document\Teste;

class TesteRepository extends DocumentRepository {

    /**
     * Lista os registros ordenados por determinada coluna
     * @param string $sort
     * @param string $order
     * @return object 
     */
    public function findByOrder($sort = "id", $order = "asc") {
        
        return $this->findBy(array(), array($sort => $order));
        
    }

}