<?php

namespace DTA\MetadataBundle\Form\Data;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use DTA\MetadataBundle\Form\DerivedType\DynamicCollectionType;

class MultiVolumeType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Data\MultiVolume',
        'name'       => 'multivolume',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('publication', new PublicationType());
        $builder->add('volumes_total');
        $builder->add('volumes', new DynamicCollectionType(), array(
            'type' => new VolumeType(),
            'inlineLabel' => false,
            'sortable' => false,
            'displayAs' => 'link',
            'label' => 'zugehörige Bände',
        ));
        
    }
}