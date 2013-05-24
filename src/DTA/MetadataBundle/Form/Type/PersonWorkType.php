<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PersonWorkType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\PersonWork',
        'name'       => 'personwork',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('person', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\Person',
            'property' => 'SelectBoxString',        // actually, this is thought for attributes, but if the attribute isn't found, the getter function is tried.
            'searchable' => false
        ));
        $builder->add('personroleId');
        $builder->add('workId');
    }
}
