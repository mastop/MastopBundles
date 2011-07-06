<?php

namespace Mastop\SystemBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Mastop\SystemBundle\Document\Parameters;
use Mastop\SystemBundle\Document\Children;

class LoadParametersData implements FixtureInterface, ContainerAwareInterface {

    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load($manager) {
        $param = new Parameters();
        $param->setName('site');
        $param->setTitle('Informações Principais');
        $param->setDesc('Configurações principais do Site');
        $param->setBundle('system');
        $child = new Children();
        $child->setName('name');
        $child->setTitle('Nome do Site');
        $child->setDesc('Digite o Nome do Site');
        $child->setValue('Mastop');
        $param->addChildren($child);
        $child = new Children();
        $child->setName('slogan');
        $child->setTitle('Slogan');
        $child->setDesc('Slogan do Site');
        $child->setValue('Internet Development');
        $param->addChildren($child);
        $child = new Children();
        $child->setName('adminmail');
        $child->setTitle('E-mail do Site');
        $child->setDesc('E-mail para receber notificações do site');
        $child->setValue('fernando@mastop.com.br');
        $param->addChildren($child);
        $manager->persist($param);
        $param = new Parameters();
        $param->setName('seo');
        $param->setTitle('SEO');
        $param->setDesc('Informações para otimização');
        $param->setBundle('system');
        $param->setOrder(2);
        $child = new Children();
        $child->setName('keywords');
        $child->setTitle('Meta Keywords');
        $child->setDesc('Digite as palavras-chave para a home do site.');
        $child->setFieldtype('textarea');
        $child->setValue('mastop, desenvolvimento web');
        $param->addChildren($child);
        $child = new Children();
        $child->setName('description');
        $child->setTitle('Meta Description');
        $child->setDesc('Digite a descrição para a home do site');
        $child->setValue('A Mastop Internet Development é a maior empresa de desenvolvimento web do Brasil.');
        $child->setFieldtype('textarea');
        $param->addChildren($child);
        $manager->persist($param);
        $param = new Parameters();
        $param->setName('script');
        $param->setTitle('Scripts');
        $param->setDesc('Javascript ou CSS para o site.');
        $param->setBundle('system');
        $param->setOrder(3);
        $child = new Children();
        $child->setName('head');
        $child->setTitle('Head');
        $child->setDesc('Scripts que irão antes de </head>');
        $child->setFieldtype('textarea');
        $child->setValue('');
        $param->addChildren($child);
        $child = new Children();
        $child->setName('foot');
        $child->setTitle('Footer');
        $child->setDesc('Scripts que irão antes de </body>');
        $child->setValue('');
        $child->setFieldtype('textarea');
        $param->addChildren($child);
        $manager->persist($param);
        $manager->flush();
    }
}