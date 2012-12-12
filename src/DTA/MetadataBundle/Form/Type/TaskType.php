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
        $builder->add('tasktypeId');
        $builder->add('done');
        $builder->add('start');
        $builder->add('end');
        $builder->add('comments');
        $builder->add('writgroupId');
        $builder->add('writId');
        $builder->add('responsibleuserId');
    }
}
