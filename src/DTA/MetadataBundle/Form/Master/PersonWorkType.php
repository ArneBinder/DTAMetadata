<?php

namespace DTA\MetadataBundle\Form\Master;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonWorkType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Master\PersonWork',
        'name'       => 'personwork',
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
        $builder->add('person', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => '\DTA\MetadataBundle\Model\Data\Person',
            'property' => 'SelectBoxString',        // actually, this is thought for attributes, but if the attribute isn't found, the getter function is tried.
            'searchable' => true,
        ));
        $builder->add('personrole', 'model', array(
            'class' => '\DTA\MetadataBundle\Model\Classification\Personrole',
            'query' => \DTA\MetadataBundle\Model\Classification\PersonroleQuery::create()->filterByApplicableToWork(true),
            'property' => 'name',
        ));

        // display the work selection input only if the work id was not specified (e.g. via the embedding work form)
        if($options['isWorkSelectable'] === true){
            $builder->add('work', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
                'class' => '\DTA\MetadataBundle\Model\Data\Work',
                'property' => 'SelectBoxString',
                'searchable' => true,
                'addButton' => false,   // only a searchable select 
            ));
        }
    }
}
