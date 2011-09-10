<?php

/**
 * Mastop/SystemBundle/Controller/Backend/ParametersController.php
 *
 * Controller para administrar as preferências do site.
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

namespace Mastop\SystemBundle\Controller\Backend;

use Mastop\SystemBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mastop\SystemBundle\Document\Parameters;

class ParametersController extends BaseController {

    /**
     * @Route("/", name="admin_system_parameters_index")
     * @Template()
     */
    public function indexAction() {
        $parameters = $this->mongo('MastopSystemBundle:Parameters')->findAllByOrder();
        $form = $this->createFormBuilder();
        $ret = array();
        foreach ($parameters as $p => $param) {
            if ($this->hasRole($param->getRole())) { // Só renderiza as preferências que este user tem permissão para ver
                $ret[$p]['title'] = $param->getTitle();
                $ret[$p]['desc'] = $param->getDesc();
                $childs = $param->getChildren();
                $form->add($param->getId(), 'collection');
                foreach ($childs as $child) {
                    if ($child->getUser() == 'system') {
                        $form->get($param->getId())->add($child->getName(), $child->getFieldtype(), array_merge(array('required' => false, 'label' => $child->getTitle(), 'data' => (($child->getFieldtype() != 'checkbox') ? $child->getValue() : (bool) $child->getValue()), 'attr' => array('title' => $child->getDesc())), $child->getOpts()));
                    }
                }
            }
        }
        $form = $form->getForm();
        return array('form' => $form->createView(), 'tabs' => $ret);
    }

    /**
     * @Route("/salvar", name="admin_system_parameters_save")
     */
    public function saveAction() {
        $parameters = $this->mongo('MastopSystemBundle:Parameters');
        $request = $this->getRequest();
        $form = $request->request->get('form');
        $dm = $this->dm();
        if ('POST' == $request->getMethod()) {
            $this->mastop()->clearCache();
            foreach ($form as $tk => $tv) {
                if (is_array($tv)) {
                    $param = $parameters->findOneById($tk);
                    if ($param && $this->hasRole($param->getRole())) { // Só salva o que este user tem permissão para editar
                        $childs = $param->getChildren();
                        foreach ($childs as $c => $cv) {
                            if (isset($tv[$cv->getName()])) {
                                $cv->setValue($tv[$cv->getName()]);
                            } elseif ($cv->getFieldtype() == 'checkbox' && $cv->getUser() == 'system') {
                                $cv->setValue('0'); // Se for checkbox, desmarca pois o mesmo não é enviado no POST
                            }
                        }
                        $dm->persist($param);
                    }
                }
            }
            $dm->flush();
            $this->get('session')->setFlash('ok', 'Preferências Atualizadas!');
            return $this->redirect($this->generateUrl('admin_system_parameters_index'));
        } else {
            $this->get('session')->setFlash('error', 'Você não pode acessar esta página.');
            return $this->redirect($this->generateUrl('admin_system_parameters_index'));
        }
    }

    /**
     * @Route("/clearcache", name="admin_system_parameters_clearcache")
     */
    public function clearcacheAction() {
        $this->mastop()->clearCache('user');
        $this->mastop()->clearCache('system');
        $this->get('session')->setFlash('ok', 'Cache Limpo!');
        return $this->redirect($this->generateUrl('admin_system_parameters_index'));
    }

    /**
     * @Route("/installthemes", name="admin_system_parameters_installthemes")
     */
    public function installthemesAction() {
        $mt = $this->get('mastop.themes');
        $filesystem = $this->get('filesystem');
        $origem = $mt->getDir();
        $temas = $mt->getAllowedThemes();
        $target = $this->get('kernel')->getRootDir() . '/../web/';
        // Cria o diretório de temas
        $filesystem->mkdir($target . 'themes/', 0777);
        foreach ($temas as $tema) {
            $originDir = $origem . '/' . $tema . '/Frontend';
            $finder = new \Symfony\Component\Finder\Finder();
            $finder->in($originDir);
            $finder->files()->notName('*.twig');
            if (is_dir($originDir)) {
                $targetDir = $target . 'themes/' . $tema;

                $filesystem->remove($targetDir);
                $filesystem->mkdir($targetDir, 0777);
                $filesystem->mirror($originDir, $targetDir, $finder);
            }
            $originDirAdmin = $origem . '/' . $tema . '/Backend';
            $finder = new \Symfony\Component\Finder\Finder();
            $finder->in($originDirAdmin);
            $finder->files()->notName('*.twig');
            if (is_dir($originDirAdmin)) {
                $targetDir = $target . 'themes/' . $tema . '/admin';

                $filesystem->remove($targetDir);
                $filesystem->mkdir($targetDir, 0777);
                $filesystem->mirror($originDirAdmin, $targetDir, $finder);
            }
        }
        $this->get('session')->setFlash('ok', 'Temas Instalados!');
        return $this->redirect($this->generateUrl('admin_system_parameters_index'));
    }

}
