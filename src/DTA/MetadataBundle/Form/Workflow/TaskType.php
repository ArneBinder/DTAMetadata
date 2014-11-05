<?php

namespace DTA\MetadataBundle\Form\Workflow;

use DTA\MetadataBundle\Form\Extensions\DateTypeExtension;
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
            'addButton'  => false,
            'class' => '\DTA\MetadataBundle\Model\Workflow\Tasktype',
            'property' => 'name',
            'required' => true
        ));

        $builder->add('start_date', null, array('years'=>range(2005,2020), 'widget' => 'single_text', 'date_ref' => 'end_date', 'threshold' => 'min')); //$builder->getName()
        //$builder->add('start_date', null, array('years'=>range(2005,2020), 'widget' => 'single_text', 'attr' => array('threshold_id'=> '_end_date', 'threshold' => 'Max')));
        $builder->add('end_date', null, array('years'=>range(2005,2020),'widget' => 'single_text', 'date_ref' => 'start_date', 'threshold' => 'max'));
        $builder->add('comments');
        $builder->add('DTAUser', 'model', array(
            'property' => 'username',
            'class' => 'DTA\MetadataBundle\Model\Master\DTAUser',
            'label' => 'verantwortlich'
        ));
        $builder->add('Partner', 'model', array(
            'property' => 'name',
            'class' => 'DTA\MetadataBundle\Model\Workflow\Partner',
            'label' => 'Partner',
            'query' => \DTA\MetadataBundle\Model\Workflow\Partner::getRowViewQueryObject()
        ));
        $builder->add('closed', null, array(
            'label' => 'Abgeschlossen',
            'attr' => array('expanded'=>true),
        ));
    }
}
