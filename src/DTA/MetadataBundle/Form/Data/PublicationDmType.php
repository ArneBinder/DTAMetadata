<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PublicationDmType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\PublicationDM',
        'name'       => 'publicationdm',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('parent', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => '\DTA\MetadataBundle\Model\Publication',
            'property' => 'id',
            'label' => 'Übergeordnete Publikation'
        ));
        
        $builder->add('pages', 'text', array(
            'label' => 'Seiten',
        ));
        
        $builder->add('publicationRelatedByPublicationId',new PublicationType(), array(
            'label' => '@suppress'
        ));
    }
}
