<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use DTA\MetadataBundle\Form\DerivedType\SelectOrAddType;

use DTA\MetadataBundle\Form\DerivedType\DynamicCollectionType;

class WorkType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Work',
        'name'       => 'work',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', new TitleType());
        $builder->add('datespecification', new DatespecificationType(), array(
            'label' => 'Entstehungsjahr'
        ));
        $builder->add('PersonWorks', new DynamicCollectionType(), array(
            'type' => new PersonWorkType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'inlineLabel' => false,
            'sortable' => false,
            'label' => 'Werkbezogene Personalia',
            'options' => array('isWorkSelectable'=>false),  // the work is implied by the context (the work that is currently edited)
        ));
//        $builder->add('genreId');
//        $builder->add('subgenreId');
//        $builder->add('dwdsgenreId');
//        $builder->add('dwdssubgenreId');
        $builder->add('doi', 'text', array('required' => false));
        $builder->add('comments');
        $builder->add('format');
        $builder->add('directoryname');
    }
}
