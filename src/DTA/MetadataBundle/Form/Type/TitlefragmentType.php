<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TitlefragmentType extends BaseAbstractType {

    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Titlefragment',
        'name' => 'titleFragment',
    );

    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('titlefragmenttype', 'model', array(
            'label' => ' ',
            'property' => 'name',
            'class' => 'DTA\MetadataBundle\Model\Titlefragmenttype',
        ));
        
        $builder->add('name', null, array('label' => ' '));
        $builder->add('sortableRank', 'hidden');             // this is controlled via javascript generated ui elements    
//        $builder->add('titleId', 'hidden', array('data'=>$options['titleId']));
    }
    
}
