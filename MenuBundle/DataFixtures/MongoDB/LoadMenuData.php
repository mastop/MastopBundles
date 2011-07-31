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
        // Menu Administrativo
        $menu = new Menu();
        $menu->setName('Admin');
        $menu->setCode('admin');
        $menu->setBundle('system');
        $child = new MenuItem();
        $child->setCode('dashboard');
        $child->setName('Dashboard');
        $child->setTitle('Administração');
        $child->setRole('ROLE_ADMIN');
        $child->setUrl('admin_system_home_index');
        $child->setRoute(true);
        $menu->addChildren($child);
        
        $child = new MenuItem();
        $child->setCode('preferences');
        $child->setName('Preferências');
        $child->setRole('ROLE_ADMIN');
        $child->setUrl('admin_system_parameters_index');
        $child->setRoute(true);
        $child->setOrder(99);
        $menu->addChildren($child);
        
        $child = new MenuItem();
        $child->setCode('limpar-cache');
        $child->setName('Limpar Cache');
        $child->setRole('ROLE_SUPERADMIN');
        $child->setUrl('admin_system_parameters_clearcache');
        $child->setRoute(true);
        $child->setOrder(999);
        $menu->addChildren($child);
        
        $child = new MenuItem();
        $child->setCode('menus');
        $child->setName('Menus');
        $child->setRole('ROLE_ADMIN');
        $child->setUrl('admin_menu_menu_index');
        $child->setRoute(true);
        $child->setOrder(98);
        $menu->addChildren($child);
        
        
        $manager->persist($menu);
        
        // Menu Principal
        $menu = new Menu();
        $menu->setName('Menu Principal');
        $menu->setCode('main');
        $menu->setBundle('system');
        $menu->setRole('ROLE_ADMIN');
        $child = new MenuItem();
        $child->setCode('home');
        $child->setName('Home');
        $child->setUrl('_home');
        $child->setRoute(true);
        $menu->addChildren($child);
        $child = new MenuItem();
        $child->setCode('teste');
        $child->setName('Teste');
        $child->setTitle('Título de Teste!');
        $child->setOrder(1);
        $child->setUrl('_teste');
        $child->setRoute(true);
        $menu->addChildren($child);
        $child = new MenuItem();
        $child->setCode('testeadmin');
        $child->setName('Admin');
        $child->setTitle('Admin Teste!');
        $child->setOrder(2);
        $child->setUrl('_teste_admin');
        $child->setRole('ROLE_ADMIN');
        $child->setRoute(true);
        $menu->addChildren($child);
        $child = new MenuItem();
        $child->setCode('mastop');
        $child->setName('Mastop');
        $child->setTitle('Site da Mastop');
        $child->setOrder(3);
        $child->setUrl('http://www.mastop.com.br');
        $child->setRole('ROLE_SUPERADMIN');
        $child->setNewWindow(true);
        $menu->addChildren($child);
        $child = new MenuItem();
        $child->setCode('contato');
        $child->setName('Contato');
        $child->setOrder(4);
        $child->setUrl('/contato');
        $menu->addChildren($child);
        $manager->persist($menu);
        
        // Menu Topo
        $menu = new Menu();
        $menu->setName('Menu Topo');
        $menu->setCode('head');
        $menu->setBundle('system');
        $menu->setRole('ROLE_ADMIN');
        $child = new MenuItem();
        $child->setCode('login');
        $child->setName('Login');
        $child->setUrl('_login');
        $child->setRole('IS_AUTHENTICATED_ANONYMOUSLY');
        $child->setRoute(true);
        $menu->addChildren($child);
        $child = new MenuItem();
        $child->setCode('cadastro');
        $child->setName('Cadastro');
        $child->setUrl('/usuario/novo');
        $child->setRole('IS_AUTHENTICATED_ANONYMOUSLY');
        $child->setOrder(1);
        $menu->addChildren($child);
        $child = new MenuItem();
        $child->setCode('logout');
        $child->setName('Logout');
        $child->setUrl('_logout');
        $child->setRole('ROLE_USER');
        $child->setOrder(2);
        $child->setRoute(true);
        $menu->addChildren($child);
        $child = new MenuItem();
        $child->setCode('admin');
        $child->setName('Admin');
        $child->setUrl('admin_system_home_index');
        $child->setRole('ROLE_ADMIN');
        $child->setOrder(99);
        $child->setRoute(true);
        $menu->addChildren($child);
        $manager->persist($menu);
        
        // Menu Rodapé
        $menu = new Menu();
        $menu->setName('Menu Rodapé');
        $menu->setCode('foot');
        $menu->setBundle('system');
        $menu->setRole('ROLE_ADMIN');
        $child = new MenuItem();
        $child->setCode('empresa');
        $child->setName('Empresa');
            $child2 = new MenuItem();
            $child2->setCode('empresa.sobre');
            $child2->setName('Sobre');
            $child2->setUrl('/empresa/sobre');
        $child->addChildren($child2);
            $child2 = new MenuItem();
            $child2->setCode('empresa.contato');
            $child2->setName('Contato');
            $child2->setUrl('/contato');
            $child2->setOrder(1);
        $child->addChildren($child2);
            $child2 = new MenuItem();
            $child2->setCode('empresa.privacidade');
            $child2->setName('Privacidade');
            $child2->setUrl('/empresa/privacidade');
            $child2->setOrder(2);
        $child->addChildren($child2);
            $child2 = new MenuItem();
            $child2->setCode('empresa.termos-e-condicoes');
            $child2->setName('Termos e Condições');
            $child2->setUrl('/empresa/termos-e-condicoes');
            $child2->setOrder(3);
        $child->addChildren($child2);
        $menu->addChildren($child);
        
        $child = new MenuItem();
        $child->setCode('saiba-mais');
        $child->setName('Saiba Mais');
            $child2 = new MenuItem();
            $child2->setCode('saiba-mais.faq');
            $child2->setName('FAQ');
            $child2->setUrl('/saiba-mais/faq');
        $child->addChildren($child2);
            $child2 = new MenuItem();
            $child2->setCode('saiba-mais.como-comprar');
            $child2->setName('Como Comprar');
            $child2->setUrl('/saiba-mais/como-comprar');
            $child2->setOrder(1);
        $child->addChildren($child2);
            $child2 = new MenuItem();
            $child2->setCode('saiba-mais.como-vender');
            $child2->setName('Como Vender');
            $child2->setUrl('/saiba-mais/como-vender');
            $child2->setOrder(2);
        $child->addChildren($child2);
        $menu->addChildren($child);
        
        $manager->persist($menu);
        
        
        $manager->flush();
    }
    public function getOrder()
    {
        return 1;
    }
}