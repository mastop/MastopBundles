<?php

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
     * @param string $id
     * @return bool
     */
    public function has($field, $value) {
        return 1 === $this->createQueryBuilder()
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