<?php

/**
 * Mastop/SystemBundle/Request/ParamConverter.php
 *
 * ParamConverter converte uma variável na chamada de um action em um document via typehint.
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


namespace Mastop\SystemBundle\Request;

use Doctrine\ODM\MongoDB\DocumentManager;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ODM\MongoDB\MongoDBException;


class ParamConverter implements ParamConverterInterface
{
    protected $mongo;

    public function __construct(DocumentManager $dm)
    {
        $this->mongo = $dm;
    }

    public function apply(Request $request, ConfigurationInterface $configuration)
    {
        $class = $configuration->getClass();
        $options = $this->getOptions($configuration);

        // tenta localizar pelo ID
        if (false === $object = $this->find($class, $request, $options)) {
            // tenta localizar por critério no request
            if (false === $object = $this->findOneBy($class, $request, $options)) {
                throw new \LogicException('Impossível definir como pegar o regitro no banco de dados à partir das informações requisitadas.');
            }
        }
        // Se não encontrou o objeto, só retorna 404 se o typehint não for opcional
        if (null === $object && false === $configuration->isOptional()) {
            throw new NotFoundHttpException(sprintf('%s objeto não encontrado.', $class));
        }
        // Deixa o objeto acessível também via $request->attributes->get('minhaVar')
        $request->attributes->set($configuration->getName(), $object);
    }

    protected function find($class, Request $request, $options)
    {
        if (!$request->attributes->has('id')) {
            return false;
        }
        if(is_numeric($request->attributes->get('id'))) return $this->mongo->find($class, (int) $request->attributes->get('id'));
        return $this->mongo->find($class, $request->attributes->get('id'));
    }

    protected function findOneBy($class, Request $request, $options)
    {
        $criteria = array();
        $metadata = $this->mongo->getClassMetadata($class);
        foreach ($request->attributes->all() as $key => $value) {
            if ($metadata->hasField($key)) {
                $criteria[$key] = $value;
            }
        }

        if (!$criteria) {
            return false;
        }

        return $this->mongo->getRepository($class)->findOneBy($criteria);
    }

    public function supports(ConfigurationInterface $configuration)
    {
        if (null === $configuration->getClass()) {
            return false;
        }

        $options = $this->getOptions($configuration);

        // Está mapeado via Doctrine MongoDB?
        try {
            $this->mongo->getClassMetadata($configuration->getClass());

            return true;
        } catch (ReflectionException $e) {
            return false;
        } catch (MongoDBException $e) {
            return false;
        }
    }

    protected function getOptions(ConfigurationInterface $configuration)
    {
        return $configuration->getOptions();
    }
}