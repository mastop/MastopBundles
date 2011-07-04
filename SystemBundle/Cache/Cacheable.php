<?php

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