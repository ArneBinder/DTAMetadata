<?php

namespace DTA\MetadataBundle\Form\Workflow;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use DTA\MetadataBundle\Form\DerivedType\SelectOrAddType;

class TasktypeType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Workflow\Tasktype',
        'name'       => 'tasktype',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('parent', 'model', array(
            'property' => 'name',
            'class' => 'DTA\MetadataBundle\Model\Workflow\Tasktype',
            'label' => 'Übergeordneter Arbeitsschritt',
        ));
//        $builder->add('parent', new SelectOrAddType(), array(
//            'class' => 'DTA\MetadataBundle\Model\Workflow\Tasktype',
//            'property' => 'Name',
//            'label' => 'Übergeordneter Aufgabentyp',
//        ));
    }
}
