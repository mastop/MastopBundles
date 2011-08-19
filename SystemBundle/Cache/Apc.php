<?php

/**
 * Mastop/SystemBundle/Cache/Apc.php
 *
 * Gerenciador de cache APC
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

class Apc implements Cacheable
{

    public function __construct()
    {
        if (!function_exists('apc_store')) {
            throw new \Exception("Extensão APC não está instalada.");
        }
    }

    public function set($key, $var, $ttl = self::DEFAULT_TTL)
    {
        return apc_store($key, $var, $ttl);
    }

    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return apc_fetch($key);
        }
        return $default;
    }

    public function has($key)
    {
        if (!function_exists('\apc_exists')) {
            $success = false;
            apc_fetch($key, $success);
            return $success;
        }
        return apc_exists($key);
    }

    public function remove($key)
    {
        if(is_array($key)){
            foreach ($key as $v) apc_delete($v);
        }else{
            apc_delete($key);
        }
        return true;
    }
    
    public function clear($type = 'user')
    {
        return apc_clear_cache ($type);
    }

}