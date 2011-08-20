<?php

/**
 * Mastop/SystemBundle/Themes.php
 *
 * Serviço "mastop.themes"
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


namespace Mastop\SystemBundle;

/**
* Contém o tema atual e permite a alteração.
*/
class Themes
{
    private $name;
    private $themes;
    private $dir;

/**
* @param string $name
* @param array $allowedThemes
*/
    public function __construct($name, array $allowedThemes, $themesDir)
    {
        $this->themes = $allowedThemes;
        $this->dir = $themesDir;
        $this->setName($name);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        if (! in_array($name, $this->themes)) {
            throw new \InvalidArgumentException(sprintf('O tema atual não está na lista de temas permitidos.'));
        }
        $this->name = $name;
    }
    public function getDir(){
        return $this->dir;
    }
    public function getAllowedThemes(){
        return $this->themes;
    }
}

