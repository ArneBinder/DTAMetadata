<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Task',
        'name'       => 'task',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tasktype', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => '\DTA\MetadataBundle\Model\Tasktype',
            'property' => 'name',
        ));
        $builder->add('done');
        $builder->add('start');
        $builder->add('end');
        $builder->add('comments');
        $builder->add('User', 'model', array(
            'property' => 'username',
            'class' => 'DTA\MetadataBundle\Model\User',
            'label' => 'verantwortlich'
        ));
    }
}
