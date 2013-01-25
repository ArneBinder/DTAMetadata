<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MonographType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Monograph',
        'name'       => 'monograph',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('Numpages', 'integer', array(
            'label' => 'Seitenzahl',
        ));
        
//        $builder->add('', new \DTA\MetadataBundle\Form\DerivedType\SortableCollectionType(), array( 
//            'label' => ' ',
//            'type' => new TitlefragmentType(),
//            'allow_add' => true,
//            'by_reference' => false,
//        ));
    }
}
