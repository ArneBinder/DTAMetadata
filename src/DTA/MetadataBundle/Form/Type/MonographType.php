<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class MonographType extends BaseAbstractType {

    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Monograph',
        'name' => 'monograph',
    );
    
    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('publication', new PublicationType(), array(
            'label' => ' '
        ));
        
//        $builder->add('title', new TitleType(), array(
//		'label' => 'Titel'));
//        
//        $builder->add('printrun', null, array(
//		'label' => 'Auflage'));
//        
//        $builder->add('printruncomment', null, array(
//		'label' => 'Kommentar zur Auflage'));
//        
//        $builder->add('edition', null, array(
//		'label' => 'Auflage'));
//        
//        $builder->add('numpages', null, array(
//		'label' => 'Anzahl Seiten'));
//        
//        $builder->add('numpagesnormed', null, array(
//		'label' => 'Anzahl Seiten, normiert'));
//        
//        $builder->add('bibliographiccitation', null, array(
//		'label' => 'Bibliografische Angabe'));
//        
//        $builder->add('publishingcompanyId', 'model', array(
//		'required' => false, 'label' => 'Verlag', 'class' => 'DTA\MetadataBundle\Model\Publishingcompany'));
//        
//        $builder->add('placeId', null, array(
//		'label' => 'Auflage'));
//        
//        $builder->add('datespecificationId', null, array(
//		'label' => 'Auflage'));
//        $builder->add('relatedsetId', null, array(
//		'label' => 'Auflage'));
//        $builder->add('workId', null, array(
//		'label' => 'Auflage'));
//        $builder->add('publisherId', null, array(
//		'label' => 'Auflage'));
//        $builder->add('printerId', null, array(
//		'label' => 'Auflage'));
//        $builder->add('translatorId', null, array(
//		'label' => 'Auflage'));
    }

}
