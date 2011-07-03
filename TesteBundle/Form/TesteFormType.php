<?php

namespace Mastop\TesteBundle\Form;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class TesteFormType extends AbstractType {

    protected $title;
    protected $description;
    protected $order;
    protected $createdAt;

    public function buildForm(FormBuilder $builder, array $options) {
        $builder
                ->add('title', 'text', array('max_length' => 100, 'label' => 'Título'))
                ->add('description', 'text', array('label' => 'Descrição'))
                ->add('order', 'text', array('label' => 'Ordem'))
        ;
    }

    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'Mastop\TesteBundle\Document\Teste',
        );
    }

    public function getName() {
        return 'testeform';
    }

}