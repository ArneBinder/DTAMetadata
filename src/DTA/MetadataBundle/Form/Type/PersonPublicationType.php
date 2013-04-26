<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use DTA\MetadataBundle\Form\DerivedType\SelectOrAddType;

class PersonPublicationType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\PersonPublication',
        'name'       => 'personpublication',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('personrole','model', array(
            'property' => 'name',
            'class' => 'DTA\MetadataBundle\Model\Personrole',
            'label' => 'Rolle',
        ));
        $builder->add('person', new SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\Person',
            'property' => 'SelectBoxString', // use personEntity->getSelectBoxString() to fill the select box
            'label' => 'Person',
            'searchable' => true,      
            
        ));
//        $builder->add('publicationId');
    }
}
