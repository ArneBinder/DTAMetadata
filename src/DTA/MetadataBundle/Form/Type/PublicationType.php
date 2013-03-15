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
        $builder->add('work', new WorkType());
        
        $builder->add('title', new TitleType());

        $builder->add('personPublications', new DynamicCollectionType(), array(
            'type' => new PersonPublicationType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'inlineLabel' => false,
            'sortable' => false,
            'label' => 'Publikationsbezogene Personalia',
        ));
        
        $builder->add('place', new SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\Place',
//            'property' => 'Name',
            'property' => 'Name',
            'label' => 'Druckort',
            'searchable' => true,
            
        ));

        $builder->add('DatespecificationRelatedByPublicationdateId', new DatespecificationType(), array(
            'label' => 'Erscheinungsjahr (f.a.)'
        ));
        
        $builder->add('DatespecificationRelatedByFirstpublicationdateId', new DatespecificationType(), array(
            'label' => 'Erscheinungsjahr der Erstausgabe (f.a.)'
        ));
        
        $builder->add('status', new SelectOrAddType(), array(
            'class' => "DTA\MetadataBundle\Model\Status",
            'property'   => 'Name',
            'label' => 'Status',
//            'query' => \DTA\MetadataBundle\Model\StatusQuery::create()->orderByName()
        ));
        
//        $builder->add('publishingcompanyId', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
//            'class' => '\DTA\MetadataBundle\Model\Publishingcompany',
//            'label' => 'Verlag'
//        ));
//        $builder->add('printrun');
//        $builder->add('printruncomment');
//        $builder->add('edition');
//        $builder->add('numpages',null,array('label'=>'Anzahl Seiten'));
//        $builder->add('numpagesnormed');
//        $builder->add('bibliographiccitation');
    }
}
