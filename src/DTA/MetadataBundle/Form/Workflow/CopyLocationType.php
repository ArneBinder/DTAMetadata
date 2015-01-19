<?php

namespace DTA\MetadataBundle\Form\Workflow;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CopyLocationType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Workflow\CopyLocation',
        'name'       => 'copylocation',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('partner', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\Workflow\Partner',
            'property' => 'Name',
            'label' => 'Bibliothek/Archiv/Anbieter',
            'searchable' => true,
            'query' => \DTA\MetadataBundle\Model\Workflow\Partner::getRowViewQueryObject()
        ));
        
        $builder->add('catalogue_signature');
        $builder->add('catalogue_internal');
        $builder->add('catalogue_url');
        
        $builder->add('numfaksimiles');
        $builder->add('catalogue_extent');
        
        
        $builder->add('available', null, array(
            'label' => 'Verfügbar'
        ));
        $builder->add('comments');
        
        $builder->add('imageurl');
        $builder->add('imageurn');
        $builder->add('license', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => 'DTA\MetadataBundle\Model\Workflow\License',
            'property' => 'Name',
            'label' => 'Licence',
        ));
        
        
//        <column name="catalogue_signature" type="LONGVARCHAR"/>
//        <column name="catalogue_internal" type="LONGVARCHAR"/>
//        <column name="catalogue_url" type="LONGVARCHAR"/>
//        <column description="Anzahl Faksimiles" name="numfaksimiles" type="INTEGER"/>
//        <column description="Umfang laut Katalog" name="catalogue_extent" type="LONGVARCHAR"/>
        
//        <column name="available" type="BOOLEAN"/>
//        <column name="comments" type="LONGVARCHAR"/>
//        <column description="URL der Bilddigitalisate" name="imageurl" type="LONGVARCHAR"/>
//        <column description="URN der Bilddigitalisate" name="imageurn" type="LONGVARCHAR"/>
//        <column name="license_id" description="Lizenz" type="INTEGER"/>
        
//        $builder->add('cataloguesignature', 'text', array('required'=>false));
//        $builder->add('catalogueurl', 'text', array('required'=>false));
//        
//        $builder->add('numfaksimiles', 'number', array('required'=>false));
//        $builder->add('faksimilerefrange', 'text', array('required'=>false));
//        $builder->add('originalrefrange', 'text', array('required'=>false));
//        
//        $builder->add('imageurl', 'text', array('required'=>false));
//        $builder->add('imageurn', 'text', array('required'=>false));
//        $builder->add('license', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
//            'class' => 'DTA\MetadataBundle\Model\Workflow\License',
//            'property' => 'Name',
//            'label' => 'Lizenz Bilddigitalisate',
//        ));
    }
}
