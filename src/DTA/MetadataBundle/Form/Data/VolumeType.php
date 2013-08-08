<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class VolumeType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Volume',
        'name'       => 'volume',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('volumeindex');
        $builder->add('volumeindexnumerical');
        $builder->add('totalvolumes');
        $builder->add('monographId');
        $builder->add('monographPublicationId');
    }
}
