<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PublicationMmType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\PublicationMM',
        'name'       => 'publicationmm',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('publicationId');
        $builder->add('volumeId');
    }
}
