<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use DTA\MetadataBundle\Form\DerivedType\DynamicCollectionType;
use DTA\MetadataBundle\Form\DerivedType\SelectOrAddType;

class PublicationType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Publication',
        'name'       => 'publication',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder->add('work', new WorkType());
//        
        $builder->add('title', new TitleType());
//
//        $builder->add('personPublications', new DynamicCollectionType(), array(
//            'type' => new PersonPublicationType(),
//            'allow_add' => true,
//            'allow_delete' => true,
//            'by_reference' => false,
//            'inlineLabel' => false,
//            'sortable' => false,
//            'label' => 'Publikationsbezogene Personalia',
//        ));
//        
//        $builder->add('place', new SelectOrAddType(), array(
//            'class' => 'DTA\MetadataBundle\Model\Place',
//            'property' => 'Name',
//            'label' => 'Druckort',
//            'searchable' => true,
//            
//        ));
//
        $builder->add('DatespecificationRelatedByPublicationdateId', new DatespecificationType(), array(
            'label' => 'Erscheinungsjahr (f.a.)'
        ));
        
//        $builder->add('DatespecificationRelatedByFirstpublicationdateId', new DatespecificationType(), array(
//            'label' => 'Erscheinungsjahr der Erstausgabe (f.a.)'
//        ));
//        
        $builder->add('edition', 'text', array(
            'required' => false
        ));
//        $builder->add('editionNumerical', null, array(
//            'label' => 'Edition (numerisch)',
//        ));
        
        $builder->add('publishingCompany', new SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\PublishingCompany',
            'property' => 'Name',
            'label' => 'Verlag'
        ));
            
        $builder->add('imageSources', new DynamicCollectionType(), array(
            'type' => new ImagesourceType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'inlineLabel' => true,
            'sortable' => false,
            'label' => 'Bildquellen'
        ));
        
    }
}
