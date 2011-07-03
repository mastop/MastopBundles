<?php

/**
 * Generates Admin with AdminGeneration configuration
 *
 * @author André Simões <afsdede92@gmail.com>
 */

namespace Mastop\AdminBundle\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Mastop\AdminBundle\Admin\AdminGen;
use Mastop\AdminBundle\Admin\AdminGeneration;

use Symfony\Component\DependencyInjection\ContainerAware;

use Mastop\AdminBundle\Admin\DataType;
use Mastop\AdminBundle\Admin\Field;
use Mastop\AdminBundle\Admin\Action;

abstract class Admin {
    
    private $dataClass;
    
    private $fields;
    private $actions;
    
    private $name;
    private $adminPath;
    private $baseTemplate;
    
    private $dm;
    
    public function __construct($dm) {
        
        $this->dm = $dm;
        
    }
    
    /**
     * Configures the admin.
     */
    abstract protected function configure(array $configs);

    /**
     * Pega dentro das entidades de mapeamento o 'get' correto
     * @param Mandango|Doctrine $data
     * @param String $fieldName
     * @return Mandango|Doctrine Object
     */
    public function getDataField($data, $fieldName){
        
        return $data->{'get'.ucfirst($fieldName)}();
        
    }
    
    public function addField($name, $type = 'string', array $options = array())
    {
        $this->fields[$name] = new Field($name, $type, $options);
        return $this;
    }
    
    public function addAction($name, $route,  array $options = array())
    {
        $this->actions[$name] = new Action($name, $route, $options);

        return $this;
    }

    public function addFields(array $fields)
    {
        foreach ($fields as $name => $options) {
            if (is_integer($name) && is_string($options)) {
                $name = $options;
                $options = array();
            }
            $this->addField($name, $options['type'], $options);
        }

        return $this;
    }
    
    public function addActions(array $actions)
    {
        foreach ($actions as $name => $options) {
            if (is_integer($name) && is_string($options)) {
                $name = $options;
                $options = array();
            }
            $this->addAction($name, $options['route'], $options);
        }

        return $this;
    }
    
    public function getFields()
    {
        return $this->fields;
    }

    public function hasField($name)
    {
        return isset($this->fields[$name]);
    }

    public function getField($name)
    {
        if (!$this->hasField($name)) {
            throw new \InvalidArgumentException(sprintf('The field "%s" does not exist.', $name));
        }

        return $this->fields[$name];
    }
    
    public function getActions()
    {
        return $this->actions;
    }

    public function hasAction($name)
    {
        return isset($this->actions[$name]);
    }

    public function getAction($name)
    {
        if (!$this->hasAction($name)) {
            throw new \InvalidArgumentException(sprintf('The field "%s" does not exist.', $name));
        }

        return $this->actions[$name];
    }
  
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getAdminPath() {
        return $this->adminPath;
    }

    public function setAdminPath($adminPath) {
        $this->adminPath = $adminPath;
    }

    public function getDataClass() {
        return $this->dataClass;
    }

    public function setDataClass($dataClass) {
        $this->dataClass = $dataClass;
    }
    
    public function getBaseTemplate() {
        return $this->baseTemplate;
    }

    public function setBaseTemplate($baseTemplate) {
        $this->baseTemplate = $baseTemplate;
    }
    
    public function getDm() {
        return $this->dm;
    }

    public function setDm($dm) {
        $this->dm = $dm;
    }
    
    /**
     *  Função utilizada para setar os campos que aparecerão na administração
     * 
     * @TODO: Define the $variables
     */
    
    public function add($child, $type = 'string', array $options = array()){
        
        $this->addField($name, $type, $options);
        
        return $this;
    }
    
    public function prepareAdministration(){

        $retDom = array();
        $retDom['title']     = $this->getName();
        $retDom['adminPath'] = $this->getAdminPath();
        $retDom['actions']   = array();
        $retDom['doms']      = array();
        $retDom['header']    = array();

        $dm = $this->getDm();
        $dataClass = $this->getDataClass();
        $dataType = new DataType();
        $filterType = new FilterType();
        
        $repoName = explode('\\', $dataClass);
        $repoName = $repoName[0].$repoName[1].":".$repoName[count($repoName)-1];
        
        $allDom = $dm->getRepository($repoName)
                     ->findBy(array(), array('id' => 'asc'));
        
        if (isset($_GET['sort'])){
            if (isset($_GET['order'])){
                $orderCtrl = $_GET['order'];
            }else{
                $orderCtrl = 'asc';
            }
            $allDom = $dm->getRepository($repoName)
                         ->findBy(array(), array($_GET['sort'] => $orderCtrl));
        }
        
        /*$allDom = $dm->getRepository($repoName)
                     ->createQueryBuilder()
                     ->field('title')->equals('342fddffd')
                     ->getQuery()
                     ->execute();

        foreach($allDom as $id => $v){
            echo "<pre>";
                print_r($id);
                print_r($v);
            echo "</pre>";
        }
        count($allDom);
        exit();*/
        $i = 1;
        foreach($allDom as $idDom => $v){
            
            foreach ($this->getFields() as $key => $val){
                $orderCtrl = 'asc';
                
                $fieldOptions = $val->getOptions();
                
                if ($i == 1){
                    if (isset($fieldOptions['label'])){
                        $retDom['header'][$key]['label'] = $fieldOptions['label'];
                    }else {
                        $retDom['header'][$key]['label'] = ucfirst($key);
                    }
                    $filterType->setName($key);
                    $filterType->setType('text');
                    if (isset($fieldOptions['filter'])){
                        $filterType->setType($fieldOptions['filter']);
                    }else {
                        $filterType->setType('text');
                    }
                    
                    /*echo "<pre>";
                        print_r($this->getFields());
                    echo "</pre>";
                    
                    exit();*/
                    $retDom['filter'][$key] = $filterType->getDataType();
                    if(isset($_GET['sort'])){
                        if ($_GET['sort']  == $key){
                            if(isset($_GET['order'])){
                                $orderCtrl = $_GET['order'];
                            }
                            if ($orderCtrl == 'asc'){
                                $orderCtrl = 'desc';
                            }else {
                                $orderCtrl = 'asc';
                            }
                        }
                    }
                    $retDom['header'][$key]['order'] = $orderCtrl;
                }
                
                $valReturn = $this->getDataField($v, $key);
                $dataType->setType($val->getType());
                $dataType->setData($valReturn);
                
                if (isset($fieldOptions['show'])){
                    $valReturn = eval("return ".$fieldOptions['show'].";");
                    //$valReturn = eval ("return \"".$val['show']."\";");
                    
                }else {
                
                    $valReturn = $dataType->getDataType();
                
                }
                
                if ($valReturn != false || $valReturn == null){
                    $retDom['doms'][$idDom][$key] = $valReturn;
                }else {
                    throw new \InvalidArgumentException(sprintf('The field "%s" has a incorrect value.', $key));
                }
            }
            
            if (count($this->getActions()) > 0){
                
                foreach ($this->getActions() as $key => $val){
                
                    $actionOptions = $val->getOptions();
                    $retAct['route'] = $val->getRoute();
                    
                    if (count($actionOptions['parameters']) > 0){
                        if ($actionOptions['label'] != ""){
                            $retActLabel = $actionOptions['label'];
                        }else {
                            $retActLabel = $key;
                        }
                    }else {
                        $retActLabel = $key;
                    }
                    $retAct['label'] = $retActLabel;
                    
                    $retDom['actions'][$idDom][$key] = $retAct;
                
                }
            }
            
            $i++;
            
        }
        return array(
            'title'     => $retDom['title'],
            'adminPath' => $retDom['adminPath'],
            'header'    => $retDom['header'],
            'doms'      => $retDom['doms'],
            'acts'      => $retDom['actions'],
            'filter'    => $retDom['filter']
        );
        
    }
    
    public function renderAdministration($templatePath = "MastopAdminBundle:Admin:default.html.twig"){
        
        if ($this->getBaseTemplate() != ""){
            
            return $this->getBaseTemplate();
            
        }else{
            
            return $templatePath;
            
        }
        
    }
    
}

?>