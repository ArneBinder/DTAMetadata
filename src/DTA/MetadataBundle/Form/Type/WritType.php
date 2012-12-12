<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class WritType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Writ',
        'name'       => 'writ',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('workId');
        $builder->add('publicationId');
        $builder->add('publisherId');
        $builder->add('printerId');
        $builder->add('translatorId');
        $builder->add('numpages');
        $builder->add('relatedsetId');
    }
}
