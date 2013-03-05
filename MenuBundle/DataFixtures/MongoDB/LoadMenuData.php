<?php

/**
 * Mastop/MenuBundle/DataFixtures/MongoDB/loadMenuData.php
 *
 * Data fixtures para o bundle de menu
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


namespace Mastop\MenuBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Mastop\MenuBundle\Document\Menu;
use Mastop\MenuBundle\Document\MenuItem;

class LoadMenuData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {
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
        $child->setCode('mastop');
        $child->setName('Mastop');
        $child->setRole('ROLE_SUPERADMIN');
        $child->setUrl('javascript:void(0)');
        $child->setOrder(999);
            $child2 = new MenuItem();
            $child2->setCode('mastop.limpar-cache');
            $child2->setName('Limpar Cache');
            $child2->setRole('ROLE_SUPERADMIN');
            $child2->setUrl('admin_system_parameters_clearcache');
            $child2->setRoute(true);
        $child->addChildren($child2);
            $child3 = new MenuItem();
            $child3->setCode('mastop.instalar-temas');
            $child3->setName('Instalar Temas');
            $child3->setRole('ROLE_SUPERADMIN');
            $child3->setUrl('admin_system_parameters_installthemes');
            $child3->setRoute(true);
        $child->addChildren($child3);
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
        $manager->persist($menu);
        
        
        $manager->flush();
    }
    public function getOrder()
    {
        return 1;
    }
}
