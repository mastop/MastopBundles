<?php

/**
 * Mastop/SystemBundle/Cache/Cacheable.php
 *
 * Interface padrão para cache.
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


namespace Mastop\SystemBundle\Cache;

interface Cacheable
{

    const DEFAULT_TTL = 86400; // Um dia

    /**
     * @param string $key
     * @param mixed $var
     * @param Integer $ttl
     * @return boolean
     */
    function set($key, $var, $ttl = self::DEFAULT_TTL);

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get($key, $default = null);

    /**
     * @param string $key
     * @return boolean
     */
    function has($key);

    /**
     * @param string $key
     * @return boolean
     */
    function remove($key);
    
    /**
     * @param string $key
     * @return boolean
     */
    function clear($type = 'user');
}