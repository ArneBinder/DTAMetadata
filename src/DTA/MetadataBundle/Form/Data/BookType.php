<?php

namespace DTA\MetadataBundle\Form\Data;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use DTA\MetadataBundle\Form\DerivedType\SelectOrAddType;

class BookType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Data\Book',
        'name'       => 'Book',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('publication', new PublicationType());

        $builder->add('is_volume', 'checkbox', array(
            'label' => 'Is part of multi volume',
            'required' => false,
        ));
        $builder->add('volume_description', null, array('required' => false));
        $builder->add('volume_numeric', 'integer', array('required' => false));
        $builder->add('parent_publication', new SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\Data\MultiVolume',
            'property' => 'TitleString',
            'required' => false,
        ));
        // add special properties of the book class here
        
    }
}