<?php

namespace DTA\MetadataBundle\Form\Data;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DatespecificationType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Data\Datespecification',
        'name'       => 'datespecification',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('year', null, array(
            'label' => false,
        ));
        $builder->add('comments', 'text', array(
            'label' => 'Kommentare',
            'required' => false,
        ));
        $builder->add('yearIsReconstructed', 'checkbox', array(
            'label' => 'ist aus anderen Quellen rekonstruiert',
            'required' => false
        ));
    }
}
