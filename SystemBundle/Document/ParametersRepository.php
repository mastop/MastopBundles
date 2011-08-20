<?php

/**
 * Mastop/SystemBundle/Document/ParametersRepository.php
 *
 * Repositório do document Parameters.
 *  
 * 
 * @copyright 2011 Mastop Internet Development.
 * @link http://www.mastop.com.br
 * @author Fernando Santos <o@fernan.do>
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

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