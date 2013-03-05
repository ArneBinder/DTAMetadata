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
        $builder->add('titleFragments', new Form\DerivedType\SortableCollectionType(), array( 
            'label' => ' ',
            'type' => new TitlefragmentType(),
            'allow_add' => true,
            'by_reference' => false,
//            'block_name' => 'anotherThemeBlock',
        ));
    }

}
