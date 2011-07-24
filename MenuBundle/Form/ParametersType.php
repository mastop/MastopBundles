<?php

namespace Mastop\SystemBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ParametersType extends AbstractType {

    public function buildForm(FormBuilder $builder, array $options) {
        $builder->add('id', 'hidden');
        $builder->add('name');
        $builder->add('special', 'checkbox', array(
            'label' => 'Exibir em "Principais Cidades"?',
            'required' => false,
        ));
        $builder->add('order');
    }

    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'Reurbano\CoreBundle\Document\City',
            'intention'  => 'city_creation',
        );
    }

}

