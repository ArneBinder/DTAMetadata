<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use \DTA\MetadataBundle\Form;

class PersonalnameType extends BaseAbstractType {

    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Personalname',
        'name' => 'personalname',
    );

    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('nameFragments', new Form\DerivedType\SortableCollectionType(), array(
//        $builder->add('nameFragments', 'collection', array(
        'allow_add' => true,
        'by_reference' => false,
        'type' => new NamefragmentType(),
        ));
    }

}
