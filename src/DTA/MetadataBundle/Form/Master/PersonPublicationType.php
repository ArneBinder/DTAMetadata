<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonPublicationType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\PersonPublication',
        'name'       => 'personpublication',
    );

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        
        parent::setDefaultOptions($resolver);
        
        $resolver->setDefaults(array(
            'isPublicationSelectable'  => true,
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('person', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => '\DTA\MetadataBundle\Model\Person',
            'property' => 'SelectBoxString',        // actually, this is thought for attributes, but if the attribute isn't found, the getter function is tried.
            'searchable' => true,
        ));
        $builder->add('personrole', 'model', array(
            'class' => '\DTA\MetadataBundle\Model\Personrole',
            'query' => \DTA\MetadataBundle\Model\PersonroleQuery::create()->filterByApplicableToPublication(true),
            'property' => 'name',
        ));

        // display the work selection input only if the work id was not specified (e.g. via the embedding work form)
        if($options['isPublicationSelectable'] === true){
            $builder->add('work', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
                'class' => '\DTA\MetadataBundle\Model\Publication',
                'property' => 'SelectBoxString',
                'searchable' => true,
                'addButton' => false,   // only a searchable select 
            ));
        }
    }
}
