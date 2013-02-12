<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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
        $builder->add('title', new TitleType(), array(
            'label' => 'Titel'
        ));
//        $builder->add('publishingcompanyId', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
//            'class' => '\DTA\MetadataBundle\Model\Publishingcompany',
//            'label' => 'Verlag'
//        ));
//        $builder->add('placeId');
//        $builder->add('datespecificationId');
//        $builder->add('printrun');
//        $builder->add('printruncomment');
//        $builder->add('edition');
//        $builder->add('numpages',null,array('label'=>'Anzahl Seiten'));
//        $builder->add('numpagesnormed');
//        $builder->add('bibliographiccitation');
    }
}
