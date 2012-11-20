<?php

namespace DTA\MetadataBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VolumeType extends AbstractType
{
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

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DTA\MetadataBundle\Model\Volume',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'volume';
    }
}
