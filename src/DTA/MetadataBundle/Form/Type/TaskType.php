<?php

namespace DTA\MetadataBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tasktypeId');
        $builder->add('done');
        $builder->add('start');
        $builder->add('end');
        $builder->add('comments');
        $builder->add('writgroupId');
        $builder->add('writId');
        $builder->add('responsibleuserId');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DTA\MetadataBundle\Model\Task',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'task';
    }
}
