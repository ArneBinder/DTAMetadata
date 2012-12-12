<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SourceType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Source',
        'name'       => 'source',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('writId');
        $builder->add('quality');
        $builder->add('name');
        $builder->add('comments');
        $builder->add('available');
        $builder->add('signatur');
        $builder->add('library');
        $builder->add('librarygnd');
    }
}
