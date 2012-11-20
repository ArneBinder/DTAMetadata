<?php

namespace DTA\MetadataBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WorkType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('statusId');
        $builder->add('datespecificationId');
        $builder->add('genreId');
        $builder->add('subgenreId');
        $builder->add('dwdsgenreId');
        $builder->add('dwdssubgenreId');
        $builder->add('doi');
        $builder->add('comments');
        $builder->add('format');
        $builder->add('directoryname');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DTA\MetadataBundle\Model\Work',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'work';
    }
}
