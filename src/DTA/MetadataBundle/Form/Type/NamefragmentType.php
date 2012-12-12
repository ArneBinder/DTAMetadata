<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class NamefragmentType extends BaseAbstractType {

    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Namefragment',
        'name' => 'titleFragment', // TODO convenience. rename the titleFragment to something more generic.
    );

    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('namefragmenttype', 'model', array(
            'label' => ' ',
            'property' => 'name',
            'class' => 'DTA\MetadataBundle\Model\Namefragmenttype',
        ));
        $builder->add('name', null, array('label' => ' '));
        $builder->add('sortableRank', 'hidden');     
    }

}
