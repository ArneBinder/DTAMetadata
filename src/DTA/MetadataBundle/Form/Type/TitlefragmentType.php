<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TitlefragmentType extends BaseAbstractType {

    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Titlefragment',
        'name' => 'titlefragment',
    );
    
    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('titlefragmenttype', 'model', array(
            'property' => 'name',
            'class' => 'DTA\MetadataBundle\Model\Titlefragmenttype',
        ));
        
        $builder->add('name', 'text');
        $builder->add('sortableRank', 'hidden');             // this is controlled via javascript generated ui elements    
        $builder->add('NameIsReconstructed', 'checkbox', array(
            'required'=>false,
            'label'=>'ist aus anderen Quellen rekonstruiert'
            ));
    }
    
}
