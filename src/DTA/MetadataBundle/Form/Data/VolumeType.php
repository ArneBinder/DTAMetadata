<?php

namespace DTA\MetadataBundle\Form\Data;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use DTA\MetadataBundle\Form\DerivedType\SelectOrAddType;

class VolumeType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Data\Volume',
        'name'       => 'volume',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('publication', new PublicationType());
        $builder->add('volume_description');
        $builder->add('volume_numeric');
        $builder->add('parentPublication', new SelectOrAddType(), array(
                'class' => 'DTA\MetadataBundle\Model\Data\MultiVolume',
                'property' => 'TitleString',
                'required' => false,
            ));
    }
}
