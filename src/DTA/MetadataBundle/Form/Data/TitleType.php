<?php

namespace DTA\MetadataBundle\Form\Data;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use \DTA\MetadataBundle\Form;

class TitleType extends BaseAbstractType {

    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Data\Title',
        'name' => 'title',
    );

    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('titleFragments', new Form\DerivedType\DynamicCollectionType(), array( 
            'type' => new TitlefragmentType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'constraints' => array(  
                new \Symfony\Component\Validator\Constraints\Count(array('min'=>1,'minMessage'=>'Titel darf nicht leer sein.'))
//                , new \Symfony\Component\Validator\Constraints\Valid(array('traverse'=>TRUE)) 
                ),
        ));
    }

}
