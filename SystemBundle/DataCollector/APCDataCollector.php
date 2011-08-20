<?php

/**
 * Mastop/SystemBundle/DataCollector/APCDataCollector.php
 *
 * Data Collector que mostra o uso do APC pelo sistema.
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

namespace Mastop\SystemBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Mastop\SystemBundle\Mastop;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Data collector for the Doctrine MongoDB ODM.
 *
 * @author Kris Wallsmith <kris@symfony.com>
 */
class APCDataCollector extends DataCollector
{
    protected $mastop;

    public function __construct(Mastop $mastop)
    {
        $this->mastop = $mastop;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $cache = apc_cache_info('user');
        $this->data['apc'] = array('total'=> count($cache['cache_list']), 'hits'=>$cache['num_hits'], 'misses' => $cache['num_misses']);
        $this->data['apcinfo'] = $cache['cache_list'];
    }
    
    /**
     * Pega o APC Array.
     *
     * @return array
     */
    public function getApc()
    {
        return $this->data['apc'];
    }
    
    /**
     * Pega o APC Info.
     *
     * @return array
     */
    public function getApcinfo()
    {
        return $this->data['apcinfo'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'apc';
    }
}
