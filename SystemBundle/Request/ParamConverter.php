<?php

namespace Mastop\SystemBundle\Request;

use Doctrine\ODM\MongoDB\DocumentManager;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ODM\MongoDB\MongoDBException;


/**
 * MastopSystemConverter.
 *
 * @author     Fernando Santos <o@fernan.do>
 */
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

        // find by identifier?
        if (false === $object = $this->find($class, $request, $options)) {
            // find by criteria
            if (false === $object = $this->findOneBy($class, $request, $options)) {
                throw new \LogicException('Impossível definir como pegar o regitro no banco de dados à partir das informações requisitadas.');
            }
        }

        if (null === $object && false === $configuration->isOptional()) {
            throw new NotFoundHttpException(sprintf('%s objeto não encontrado.', $class));
        }

        $request->attributes->set($configuration->getName(), $object);
    }

    protected function find($class, Request $request, $options)
    {
        if (!$request->attributes->has('id')) {
            return false;
        }

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

        // Doctrine Entity?
        try {
            $this->mongo->getClassMetadata($configuration->getClass());

            return true;
        } catch (ReflectionException $e) {
            return false;
        }
    }

    protected function getOptions(ConfigurationInterface $configuration)
    {
        return $configuration->getOptions();
    }
}