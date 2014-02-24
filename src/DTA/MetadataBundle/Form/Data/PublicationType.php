<?php

namespace DTA\MetadataBundle\Form\Data;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use DTA\MetadataBundle\Form\DerivedType\DynamicCollectionType;
use DTA\MetadataBundle\Form\DerivedType\SelectOrAddType;

use DTA\MetadataBundle\Form\Master;
use DTA\MetadataBundle\Form\Data;
use DTA\MetadataBundle\Form\Workflow;
use DTA\MetadataBundle\Form\Classification;

class PublicationType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Data\Publication',
        'name'       => 'publication',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        // ----------------------------------------------------------------
        // WORK 
        // ----------------------------------------------------------------
        $builder->add('title', new TitleType());
        $builder->add('dirname','text',array(
            'required' => false
        ));
        $builder->add('PersonPublications', new DynamicCollectionType(), array(
            'type' => new Master\PersonPublicationType(),
            'inlineLabel' => false,
            'sortable' => true,
            'label' => 'Publikationsbezogene Personalia',
            'options' => array('isPublicationSelectable'=>false),  // the work is implied by the context (the work that is currently edited)
        ));
        $builder->add('wwwready');
        $builder->add('doi', 'text', array('required' => false,'read_only' => true,));
        $builder->add('format', 'text', array('required' => false));
        $builder->add('citation');
        
        // ----------------------------------------------------------------
        // PUBLICATION
        // ----------------------------------------------------------------
         
        $builder->add('place', new SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\Data\Place',
            'property' => 'Name',
            'label' => 'Druckort',
            'required' => false,
            
        ));
        $builder->add('DatespecificationRelatedByPublicationdateId', new Data\DatespecificationType(), array(
            'label' => 'Erscheinungsjahr'
        ));
        $builder->add('publishingcompany', new SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\Data\Publishingcompany',
            'property' => 'Name',
            'label' => 'Verlag',
            'required' => false,
        ));
        
        $builder->add('numpages');
        $builder->add('numpagesnumeric');
        $builder->add('firstpage');
         
        // ----------------------------------------------------------------
        // CLASSIFICATION 
        // ----------------------------------------------------------------
        
        $builder->add('LanguagePublications', new DynamicCollectionType(), array(
            'type' => new Master\LanguagePublicationType(),
            // TODO remove inlineLabel option from the dynamic collection type.
            'inlineLabel' => false,
            'sortable' => true,
            'label' => 'vorherrschende Sprache',
//            'options' => array('isPublicationSelectable'=>false),  // the work is implied by the context (the work that is currently edited)
        ));
        $builder->add('GenrePublications', new DynamicCollectionType(), array(
            'type' => new Master\GenrePublicationType(),
            'inlineLabel' => false,
            'sortable' => false,
            'label' => 'Genres',
        ));
        
        $builder->add('legacy_dwds_category1');
        $builder->add('legacy_dwds_subcategory1');
        $builder->add('legacy_dwds_category2');
        $builder->add('legacy_dwds_subcategory2');
        
        $builder->add('CategoryPublications', new DynamicCollectionType(), array(
            'type' => new Master\CategoryPublicationType(),
            'inlineLabel' => false,
            'sortable' => false,
            'label' => 'Kategorien',
            'options' => array('isPublicationSelectable'=>false),
        ));
        $builder->add('PublicationTags', new DynamicCollectionType(), array(
            'type' => new Master\PublicationTagType(),
            'inlineLabel' => false,
            'sortable' => false,
            'label' => 'Schlagworte',
        ));
        
        // ----------------------------------------------------------------
        // SOURCES 
        // ----------------------------------------------------------------
            
        $builder->add('ImageSources', new DynamicCollectionType(), array(
            'type' => new Workflow\ImagesourceType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'inlineLabel' => true,
            'sortable' => false,
            'label' => false
        ));
        
        $builder->add('TextSources', new DynamicCollectionType(), array(
            'type' => new Workflow\TextsourceType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'inlineLabel' => true,
            'sortable' => false,
            'label' => false
        ));
        
        // ----------------------------------------------------------------
        // EDITION
        // ----------------------------------------------------------------
        
        $builder->add('editiondescription', 'textarea', array('required'=>false));
        $builder->add('printrun');
        $builder->add('digitaleditioneditor', 'text', array('required'=>false));
        
        $builder->add('comment');
        $builder->add('editioncomment');
        $builder->add('transcriptioncomment');
        $builder->add('encodingcomment','textarea', array('required'=>false));
        $builder->add('firsteditioncomment','textarea', array('required'=>false));
        
        $builder->add('Tasks', new DynamicCollectionType(), array(
            'type' => new Workflow\TaskType(),
            'inlineLabel' => false,
            'sortable' => false,
            'label' => 'Tasks',
//            'options' => array('isPublicationSelectable'=>false),  // the work is implied by the context (the work that is currently edited)
        ));
        
    }
}
