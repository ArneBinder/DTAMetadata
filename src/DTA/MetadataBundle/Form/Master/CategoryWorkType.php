<?php

namespace DTA\MetadataBundle\Form\Master;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryWorkType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Master\CategoryWork',
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
                'class' => '\DTA\MetadataBundle\Model\Classification\Category',
                'property' => 'Name',
                'searchable' => true,
                'addButton' => true,
                'label' => " "
            ));
        // display the work selection input only if the work id was not specified (e.g. via the embedding work form)
        if($options['isWorkSelectable'] === true){
            $builder->add('work', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
                'class' => '\DTA\MetadataBundle\Model\Data\Work',
                'property' => 'SelectBoxString',
                'searchable' => true,
                'addButton' => true,
            ));
        }
        
    }
}
