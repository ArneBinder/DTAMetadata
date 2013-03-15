<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use DTA\MetadataBundle\Form\DerivedType\SelectOrAddType;

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
        $builder->add('workrole','model', array(
            'property' => 'name',
            'class' => 'DTA\MetadataBundle\Model\Workrole',
            'label' => 'Rolle',
        ));
        $builder->add('person', new SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\Person',
            'property' => 'SelectBoxString', // combines all namefragments in a single string @see Person.php
            'label' => 'Person',
            'searchable' => true,      
            
        ));
//        $builder->add('personId');
//        $builder->add('workroleId');
//        $builder->add('workId');
    }
}
