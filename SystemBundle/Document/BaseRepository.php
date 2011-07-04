<?php

namespace Mastop\SystemBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\Mapping\Types\Type;


class BaseRepository extends DocumentRepository {

    /**
     * Encontra um parâmetro pelo seu ID
     *
     * @param string $id
     * @return Parameters|null
     * */
    public function findOneById($id) {
        return $this->find($id);
    }

    /**
     * Verifica se o parâmetro existe pelo ID
     *
     * @param string $id
     * @return bool
     */
    public function existsById($id) {
        return 1 === $this->createQueryBuilder()
                ->field('id')->equals($id)
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