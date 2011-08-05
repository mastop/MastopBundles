<?php

namespace Mastop\SystemBundle\Document;

use Mastop\SystemBundle\Document\BaseRepository;


class ParametersRepository extends BaseRepository {

    /**
     * Pega todos ordenado por ORDER
     *
     * @return Parameters ou null
     **/
    public function findAllByOrder()
    {
        return $this->findBy(array(), array('order'=>'asc'));
    }

}