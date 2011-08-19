<?php

/**
 * Mastop/MenuBundle/Document/MenuRepository.php
 *
 * Repositório do document Menu.
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

namespace Mastop\MenuBundle\Document;

use Mastop\SystemBundle\Document\BaseRepository;

class MenuRepository extends BaseRepository {

    /**
     * Pega um pelo Bundle e Code
     * 
     * @param string $bundle
     * @param string $code
     * @return Menu or null
     */
    public function findByBundleCode($bundle, $code) {
        return $this->findOneBy(array('bundle' => $bundle, 'code' => $code));
    }

    public function getChildrenByCode($menu, $code) {
        $childs = $menu->getChildren();
        if (count($childs) > 0) {
            foreach ($childs as $m) {
                if($m->getCode() == $code){
                    return $m;
                }
                if($m2 = $this->getChildrenByCode($m, $code)){
                    return $m2;
                }
            }
        }
        return false;
    }

}