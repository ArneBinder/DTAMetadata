<?php

namespace DTA\MetadataBundle\Form\Data;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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
        
        // add special properties of the book class here
        
    }
}