<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use DTA\MetadataBundle\Form\DerivedType\SelectOrAddType;

class TasktypeType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Tasktype',
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
            'class' => 'DTA\MetadataBundle\Model\Tasktype',
            'label' => 'Übergeordneter Arbeitsschritt',
        ));
//        $builder->add('parent', new SelectOrAddType(), array(
//            'class' => 'DTA\MetadataBundle\Model\Tasktype',
//            'property' => 'Name',
//            'label' => 'Übergeordneter Aufgabentyp',
//        ));
    }
}
