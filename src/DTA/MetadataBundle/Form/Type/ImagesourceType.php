<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ImagesourceType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Imagesource',
        'name'       => 'imagesource',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('publicationId');
        $builder->add('cataloguesignature');
        $builder->add('catalogueurl');
        $builder->add('numfaksimiles');
        $builder->add('numpages');
        $builder->add('imageurl');
        $builder->add('imageurn');
        $builder->add('license', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\License',
            'property' => 'Name',
        ));
    }
}
