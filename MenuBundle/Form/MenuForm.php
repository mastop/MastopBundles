<?php
namespace Mastop\MenuBundle\Form;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Mastop\MenuBundle\Document\Menu;

class MenuForm extends AbstractType{

    public function buildForm(FormBuilder $builder, array $options){
        $builder
            ->add('menuName', 'text')
            ->add('name', 'text')
            ->add('role', 'text')
            ->add('url', 'text');
    }

   public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Mastop\MenuBundle\Document\Menu',
        );
    } 
    
    public function getName()
    {
        return 'menu';
    }
}