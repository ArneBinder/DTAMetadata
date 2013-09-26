<?php

namespace DTA\MetadataBundle\Form\Master;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class WorkTagType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Master\WorkTag',
        'name'       => 'worktag',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tag', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => '\DTA\MetadataBundle\Model\Classification\Tag',
            'property' => 'Name',
        ));
    }
}
