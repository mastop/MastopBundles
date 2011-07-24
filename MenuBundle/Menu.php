<?php

/*
* This file is part of the Mastop/MenuBundle
*
* (c) Mastop Iternet Development
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
* 
* @author   Fernando Santos <o@fernan.do>
*/

namespace Mastop\MenuBundle;
use Mastop\SystemBundle\Mastop;

class Menu
{
    private $mastop;

/**
* @param string Mastop $mastop
*/
    public function __construct(Mastop $mastop)
    {
        $this->mastop = $mastop;
    }
}

