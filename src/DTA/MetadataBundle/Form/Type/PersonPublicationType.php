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
        $builder->add('publicationrole','model', array(
            'property' => 'name',
            'class' => 'DTA\MetadataBundle\Model\Publicationrole',
            'label' => 'Rolle',
        ));
        $builder->add('person', new SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\Person',
            'property' => 'selectBoxString',
            'label' => 'Person'
            
        ));
//        $builder->add('publicationId');
    }
}
