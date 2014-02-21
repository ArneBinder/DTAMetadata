<?php

namespace DTA\MetadataBundle\Form\Data;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TitlefragmentType extends BaseAbstractType {

    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Data\Titlefragment',
        'name' => 'titlefragment',
    );
    
    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('type');
        
        $builder->add('name', 'textarea', array(
            'attr' => array('style'=>"width: 80%;"),
        ));
        $builder->add('NameIsReconstructed', 'checkbox', array(
            'required'=>false,
            'label'=>'ist aus anderen Quellen rekonstruiert'
            ));
        $builder->add('sortableRank', 'hidden');             // this is controlled via javascript generated ui elements    
    }
    
}
