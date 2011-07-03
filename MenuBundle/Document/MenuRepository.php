<?php

namespace Mastop\MenuBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;

class MenuRepository extends DocumentRepository
{
    /**
     * Encontra os menus que o menuName são iguais
     * @param string $menuName
     * @return array 
     */
    public function findByMenuName($menuName) {

        return $this->findBy(array('menuName' => $menuName));
    }
}