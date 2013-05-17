<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PersonroleType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Personrole',
        'name'       => 'personrole',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('applicableToPublication', 'checkbox',array(
            'label' => 'Bezieht sich auf Werke'
        ));
        $builder->add('applicableToWork', 'checkbox',array(
            'label' => 'Bezieht sich auf Publikationen'
        ));
    }
}
