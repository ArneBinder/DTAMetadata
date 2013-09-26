<?php

namespace DTA\MetadataBundle\Form\Master;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LanguageWorkType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Master\LanguageWork',
        'name'       => 'languagework',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('language', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => '\DTA\MetadataBundle\Model\Data\Language',
            'property' => 'name',        // actually, this is thought for attributes, but if the attribute isn't found, the getter function is tried.
            'searchable' => true,
        ));
    }
}
