<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class WorkType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Work',
        'name'       => 'work',
    );

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
}
