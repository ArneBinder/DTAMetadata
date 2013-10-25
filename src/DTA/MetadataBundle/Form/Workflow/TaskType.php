<?php

namespace DTA\MetadataBundle\Form\Workflow;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Workflow\Task',
        'name'       => 'task',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tasktype', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => '\DTA\MetadataBundle\Model\Workflow\Tasktype',
            'property' => 'name',
        ));
        $builder->add('start_date', null, array('years'=>array('2013','2014','2015'),'widget' => 'choice'));
        $builder->add('end_date');
        $builder->add('comments');
        $builder->add('DTAUser', 'model', array(
            'property' => 'username',
            'class' => 'DTA\MetadataBundle\Model\Master\DTAUser',
            'label' => 'verantwortlich'
        ));
        $builder->add('Partner', 'model', array(
            'property' => 'name',
            'class' => 'DTA\MetadataBundle\Model\Workflow\Partner',
            'label' => 'Partner'
        ));
        $builder->add('done', null, array(
            'label' => 'Abgeschlossen',
            'attr' => array('expanded'=>true),
        ));
    }
}