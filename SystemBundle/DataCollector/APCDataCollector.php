<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
