<?php

/**
 * Mastop/SystemBundle/Document/Children.php
 *
 * Representa um Child de Parâmetro.
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



namespace Mastop\SystemBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Children
{
    /**
     * Nome
     *
     * @var string
     * @ODM\String
     */
    protected $name;
    
    /**
     * Título
     *
     * @var string
     * @ODM\String
     */
    protected $title;
    
    /**
     * Descrição
     *
     * @var string
     * @ODM\String
     */
    protected $desc;
    
    /**
     * Valor
     *
     * @var string
     * @ODM\String
     */
    protected $value;
    
    /**
     * Tipo de Campo
     *
     * @var string
     * @ODM\String
     */
    protected $fieldtype = 'text';
    
  
    /**
     * Ordem
     *
     * @var string
     * @ODM\Int
     * @ODM\Index(order="asc")
     */
    protected $order = 0;
    
    /**
     * Opções
     *
     * @var array
     * @ODM\Hash
     */
    protected $opts = array();
    /**
     * Role
     *
     * @var string
     * @ODM\String
     * @ODM\Index
     */
    protected $user = 'system';

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set desc
     *
     * @param string $desc
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    /**
     * Get desc
     *
     * @return string $desc
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Set value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return string $value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set fieldtype
     *
     * @param string $fieldtype
     */
    public function setFieldtype($fieldtype)
    {
        $this->fieldtype = $fieldtype;
    }

    /**
     * Get fieldtype
     *
     * @return string $fieldtype
     */
    public function getFieldtype()
    {
        return $this->fieldtype;
    }

    /**
     * Set order
     *
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * Get order
     *
     * @return int $order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set user
     *
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return string $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set opts
     *
     * @param string $opts
     */
    public function setOpts($opts)
    {
        $this->opts = $opts;
    }

    /**
     * Get opts
     *
     * @return string $opts
     */
    public function getOpts()
    {
        return $this->opts;
    }
}