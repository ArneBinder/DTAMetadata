<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryWorkType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\CategoryWork',
        'name'       => 'categorywork',
    );

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        
        parent::setDefaultOptions($resolver);
        
        $resolver->setDefaults(array(
            'isWorkSelectable'  => true,
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('category', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
                'class' => '\DTA\MetadataBundle\Model\Category',
                'property' => 'Name',
                'searchable' => true,
                'addButton' => true,
                'label' => " "
            ));
        // display the work selection input only if the work id was not specified (e.g. via the embedding work form)
        if($options['isWorkSelectable'] === true){
            $builder->add('work', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
                'class' => '\DTA\MetadataBundle\Model\Work',
                'property' => 'SelectBoxString',
                'searchable' => true,
                'addButton' => true,
            ));
        }
        
    }
}
