<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AuthorType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Author',
        'name'       => 'author',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('person',new \DTA\MetadataBundle\Form\Type\PersonType(), array(
                    'label' => ' ',
                ));
//        $builder->add('name', null, array('label'=>' '));
//        $builder->add('sortableRank','hidden');             // this is controlled via javascript generated ui elements    
        
    }
}
