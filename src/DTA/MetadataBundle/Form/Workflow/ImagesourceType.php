<?php

namespace DTA\MetadataBundle\Form\Workflow;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ImagesourceType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Workflow\Imagesource',
        'name'       => 'imagesource',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('partner', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\Workflow\Partner',
            'property' => 'Name',
            'label' => 'Imagesourcepartner',
            'searchable' => true,
            'query' => \DTA\MetadataBundle\Model\Workflow\Partner::getRowViewQueryObject()
        ));
        $builder->add('cataloguesignature', 'text', array('required'=>false));
        $builder->add('catalogueurl', 'text', array('required'=>false));
        
        $builder->add('numfaksimiles', 'number', array('required'=>false));
        $builder->add('extentasofcatalogue', 'text', array('required'=>false));
        $builder->add('faksimilerefrange', 'text', array('required'=>false));
        $builder->add('originalrefrange', 'text', array('required'=>false));
        
        $builder->add('imageurl', 'text', array('required'=>false));
        $builder->add('imageurn', 'text', array('required'=>false));
        $builder->add('license', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\Workflow\License',
            'property' => 'Name',
            'label' => 'Imagelicence',
        ));
    }
}
