<?php

namespace DTA\MetadataBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SourceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('writId');
        $builder->add('quality');
        $builder->add('name');
        $builder->add('comments');
        $builder->add('available');
        $builder->add('signatur');
        $builder->add('library');
        $builder->add('librarygnd');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DTA\MetadataBundle\Model\Source',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'source';
    }
}
