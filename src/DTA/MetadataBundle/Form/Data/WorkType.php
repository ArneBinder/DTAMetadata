<?php

namespace DTA\MetadataBundle\Form\Data;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use DTA\MetadataBundle\Form\DerivedType\SelectOrAddType;
use DTA\MetadataBundle\Form\DerivedType\DynamicCollectionType;

use DTA\MetadataBundle\Form\Master;
use DTA\MetadataBundle\Form\Data;
use DTA\MetadataBundle\Form\Workflow;
use DTA\MetadataBundle\Form\Classification;

class WorkType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Data\Work',
        'name'       => 'work',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', new TitleType());
        $builder->add('datespecification', new Data\DatespecificationType(), array(
            'label' => 'Entstehungsjahr des Textes'
        ));
        $builder->add('PersonWorks', new DynamicCollectionType(), array(
            'type' => new Master\PersonWorkType(),
            'inlineLabel' => false,
            'sortable' => false,
            'label' => 'Werkbezogene Personalia',
            'options' => array('isWorkSelectable'=>false),  // the work is implied by the context (the work that is currently edited)
        ));
//        $builder->add('genreId');
//        $builder->add('subgenreId');
//        $builder->add('dwdsgenreId');
//        $builder->add('dwdssubgenreId');

//        $builder->add('Categories', new DynamicCollectionType(), array(
//            'type' => new CategoryType(),
//            'inlineLabel' => false,
//            'sortable' => false,
//            'label' => 'Kategorien',
//        ));
        $builder->add('CategoryWorks', new DynamicCollectionType(), array(
            'type' => new Master\CategoryWorkType(),
            'inlineLabel' => false,
            'sortable' => false,
            'label' => 'Kategorien',
            'options' => array('isWorkSelectable'=>false),  // the work is implied by the context (the work that is currently edited)
        ));
        
//        $builder->add('Categories', new SelectOrAddForType(array(
//                    'class' => '\DTA\MetadataBundle\Model\Data\Category',
//                    'property' => 'Name',
//                    'multiple' => true,
//                )),array());
        
/**
 * @todo A more compact version using the select2 capabilities is strongly desirable.
 */
//        $builder->add('categories', new SelectOrAddType(), array(
//            'class' => '\DTA\MetadataBundle\Model\Data\Category',
//            'property' => 'Name',
//            'multiple' => true,
//        ));
        
        
        $builder->add('doi', 'text', array('required' => false));
        $builder->add('comments');
        $builder->add('format', 'text', array('required' => false));
        $builder->add('directoryname', 'text', array('required' => false));
    }
}
