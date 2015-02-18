<?php

namespace DTA\MetadataBundle\Form\Data;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use DTA\MetadataBundle\Form\DerivedType\SelectOrAddType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VolumeType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Data\Volume',
        'name'       => 'volume',
    );

    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'showInSelectBox'  => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['showInSelectBox'] === true){
            $builder->add('this', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
                'class' => '\DTA\MetadataBundle\Model\Data\Volume',
                'property' => 'TitleString',        // actually, this is thought for attributes, but if the attribute isn't found, the getter function is tried.
                'searchable' => true,
                //'query' => \DTA\MetadataBundle\Model\Data\Volume::getRowViewQueryObject()
            ));
        }else {
            $builder->add('publication', new PublicationType());
            $builder->add(
                'is_volume',
                'checkbox',
                array(
                    'label' => 'Is part of multi volume',
                    'required' => false,
                )
            );
            $builder->add('volume_description');
            $builder->add('volume_numeric');
            $builder->add(
                'parent_publication',
                new SelectOrAddType(),
                array(
                    'class' => 'DTA\MetadataBundle\Model\Data\MultiVolume',
                    'property' => 'TitleString',
                    'required' => false,
                )
            );
        }
    }
}
