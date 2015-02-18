<?php

namespace DTA\MetadataBundle\Form\Data;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use DTA\MetadataBundle\Form\DerivedType\DynamicCollectionType;
use DTA\MetadataBundle\Form\DerivedType\SelectOrAddType;
use DTA\MetadataBundle\Form\Master;

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
            'allow_add' => false,
            'options' => array('showInSelectBox'=>true),
        ));

       /* $builder->add('volumes', new DynamicCollectionType(), array(
            'type' => new Master\PublicationVolumeType(),
            'inlineLabel' => false,
            'sortable' => true,
            //'label' => 'Autor/Verleger/Übersetzer/Drucker/Herausgeber',
            'options' => array('isPublicationSelectable'=>false),  // the work is implied by the context (the work that is currently edited)
        ));
        */
    }
}