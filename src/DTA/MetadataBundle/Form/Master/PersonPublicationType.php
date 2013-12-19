<?php

namespace DTA\MetadataBundle\Form\Master;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonPublicationType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Master\PersonPublication',
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
            'class' => '\DTA\MetadataBundle\Model\Data\Person',
            'property' => 'SelectBoxString',        // actually, this is thought for attributes, but if the attribute isn't found, the getter function is tried.
            'searchable' => true,
            'query' => \DTA\MetadataBundle\Model\Data\Person::getRowViewQueryObject()
        ));
        $builder->add('personrole', 'model', array(
            'class' => '\DTA\MetadataBundle\Model\Classification\Personrole',
            'property' => 'name',
        ));

        // display the work selection input only if the work id was not specified (e.g. via the embedding work form)
        if($options['isPublicationSelectable'] === true){
            $builder->add('publication', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
                'class' => '\DTA\MetadataBundle\Model\Data\Publication',
                'property' => 'SelectBoxString',
                'searchable' => true,
                'addButton' => false,   // only a searchable select 
            ));
        }
    }
}
