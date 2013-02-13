<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use \DTA\MetadataBundle\Form\DerivedType;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class WorkType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Work',
        'name'       => 'work',
        'modelNameGerman'   => 'Werk'
    );
    
    // pass the translated name to the view
    public function finishView(FormView $view, FormInterface $form, array $options){
        $view->vars['modelNameGerman'] = $options['modelNameGerman'];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('status', new DerivedType\SelectOrAddType(), array(
            'class' => "DTA\MetadataBundle\Model\Status",
            'property'   => 'Name',
            'label' => 'Status',
        ));
//        $builder->add('datespecificationId');
//        $builder->add('genreId');
//        $builder->add('subgenreId');
//        $builder->add('dwdsgenreId');
//        $builder->add('dwdssubgenreId');
//        $builder->add('doi');
//        $builder->add('comments');
//        $builder->add('format');
//        $builder->add('directoryname');
    }
}
