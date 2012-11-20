<?php

namespace DTA\MetadataBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatespecificationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('year');
        $builder->add('yearisreconstructed');
        $builder->add('comments');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DTA\MetadataBundle\Model\Datespecification',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'datespecification';
    }
}
