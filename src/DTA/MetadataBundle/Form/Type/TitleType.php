<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use \DTA\MetadataBundle\Form;

class TitleType extends BaseAbstractType {

    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Title',
        'name' => 'title',
    );

    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        //new Form\DerivedType\SortableCollectionType()
        $builder->add('titleFragments', 'collection', array( 
            'type' => new TitlefragmentType(),
            'allow_add' => true,
            'by_reference' => false,
        ));
    }

}
