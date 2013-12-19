<?php

namespace DTA\MetadataBundle\Form\Workflow;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TextsourceType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Workflow\Textsource',
        'name'       => 'textsource',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('partner', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\Workflow\Partner',
            'property' => 'Name',
            'label' => 'Anbieter Textdigitalisate',
            'searchable' => true,
            'query' => \DTA\MetadataBundle\Model\Workflow\Partner::getRowViewQueryObject()
        ));
        $builder->add('texturl', 'text', array('required'=>false));
        $builder->add('attribution', 'text', array('required'=>false));
        $builder->add('license', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\Workflow\License',
            'property' => 'Name',
            'label' => 'Lizenz Textdigitalisate',
        ));
    }
}
