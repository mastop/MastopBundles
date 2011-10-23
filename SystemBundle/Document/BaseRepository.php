<?php

/**
 * Mastop/SystemBundle/Document/BaseRepository.php
 *
 * Repositório base para todos os repositórios do sistema.
 * Todos os arquivos de repositório devem herdar esta classe.
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

use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\Mapping\Types\Type;


class BaseRepository extends DocumentRepository {

    /**
     * Encontra um registro pelo seu ID
     *
     * @param string $id
     * @return Parameters|null
     * */
    public function findOneById($id) {
        return $this->find($id);
    }

    /**
     * Verifica se o registro existe pelo ID
     *
     * @param string $id
     * @return bool
     */
    public function hasId($id) {
        return 1 === $this->createQueryBuilder()
                ->field('id')->equals($id)
                ->getQuery()->count();
    }
    
    /**
     * Verifica se o registro existe
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    public function has($field, $value) {
        return 1 === $this->createQueryBuilder()
                ->field($field)->equals($value)
                ->getQuery()->count();
    }
    /**
     * Conta por campo / valor
     *
     * @param string $field
     * @param string $value
     * @return int
     */
    public function count($field, $value) {
        return $this->createQueryBuilder()
                ->field($field)->equals($value)
                ->getQuery()->count();
    }
    
    public function getFieldType($field) {
        return $this->getClassMetadata()->fieldMappings[$field]['type'];
    }
    public function getPHPValue($field, $valor){
        $type = $this->getClassMetadata()->fieldMappings[$field]['type'];
        return Type::getType($type)->convertToPHPValue($valor);
    }
    public function getDatabaseValue($field, $valor){
        $type = $this->getClassMetadata()->fieldMappings[$field]['type'];
        return Type::getType($type)->convertToDatabaseValue($valor);
    }
}