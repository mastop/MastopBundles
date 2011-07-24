<?php

namespace Mastop\MenuBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Mastop\MenuBundle\Document\Menu;
use Mastop\MenuBundle\Document\MenuItem;

class LoadMenuData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load($manager) {
        $menu = new Menu();
        $menu->setName('Admin');
        $menu->setCode('admin');
        $menu->setBundle('system');
        $child = new MenuItem();
        $child->setCode('dashboard');
        $child->setName('Dashboard');
        $child->setTitle('Home da Administração');
        $child->setRole('ROLE_ADMIN');
        $child->setUrl('admin_system_home_index');
        $menu->addChildren($child);
        $child = new MenuItem();
        $child->setCode('preferences');
        $child->setName('Preferências');
        //$child->setTitle('');
        $child->setRole('ROLE_ADMIN');
        $child->setUrl('admin_system_parameters_index');
        $child2 = new MenuItem();
        $child2->setCode('preferences.clearcache');
        $child2->setName('Limpar Cache');
        $child2->setRole('ROLE_SUPERADMIN');
        $child2->setUrl('admin_system_parameters_clearcache');
        $child->addChildren($child2);
        $menu->addChildren($child);
        $manager->persist($menu);
        
        
        $menu = new Menu();
        $menu->setName('Menu Principal');
        $menu->setCode('main');
        $menu->setBundle('system');
        $menu->setRole('ROLE_ADMIN');
        $child = new MenuItem();
        $child->setCode('home');
        $child->setName('Home');
        $child->setUrl('_home');
        $menu->addChildren($child);
        $child = new MenuItem();
        $child->setCode('blog');
        $child->setName('Blog');
        $child->setTitle('Visite Nosso Blog!');
        $child->setUrl('http://blog.mastop.com.br');
        $child->setNewWindow(true);
        $menu->addChildren($child);
        $manager->persist($menu);
        
        
        $menu = new Menu();
        $menu->setName('Menu Topo');
        $menu->setCode('head');
        $menu->setBundle('system');
        $menu->setRole('ROLE_ADMIN');
        $child = new MenuItem();
        $child->setCode('login');
        $child->setName('Login / Cadastro');
        $child->setUrl('/login');
        $menu->addChildren($child);
        $child = new MenuItem();
        $child->setCode('contact');
        $child->setName('Contato');
        $child->setUrl('/contato');
        $menu->addChildren($child);
        $child = new MenuItem();
        $child->setCode('admin');
        $child->setName('Admin');
        $child->setUrl('admin_system_home_index');
        $child->setRole('ROLE_ADMIN');
        $menu->addChildren($child);
        $child = new MenuItem();
        $child->setCode('logout');
        $child->setName('Sair');
        $child->setUrl('/logout');
        $child->setRole('ROLE_USER');
        $menu->addChildren($child);
        $manager->persist($menu);
        
        
        $menu = new Menu();
        $menu->setName('Menu Rodapé');
        $menu->setCode('foot');
        $menu->setBundle('system');
        $menu->setRole('ROLE_ADMIN');
        $child = new MenuItem();
        $child->setCode('login');
        $child->setName('Login / Cadastro');
        $child->setUrl('/login');
        $menu->addChildren($child);
        $child = new MenuItem();
        $child->setCode('contact');
        $child->setName('Contato');
        $child->setUrl('/contato');
        $menu->addChildren($child);
        $child = new MenuItem();
        $child->setCode('about');
        $child->setName('Sobre');
        $child->setUrl('/about');
        $menu->addChildren($child);
        $child = new MenuItem();
        $child->setCode('mastop');
        $child->setName('Mastop');
        $child->setUrl('http://www.mastop.com.br');
        $child->setRole('ROLE_SUPERADMIN');
        $child->setNewWindow(true);
        $menu->addChildren($child);
        $manager->persist($menu);
        
        
        $manager->flush();
    }
    public function getOrder()
    {
        return 1;
    }
}