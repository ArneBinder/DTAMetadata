<?php

namespace DTA\MetadataBundle\Form\Workflow;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LicenseType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Workflow\License',
        'name'       => 'license',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('url', 'text');
        
        $builder->add('applicableToText', 'checkbox', array(
            'label' => 'Anwendbar auf Text',
            'required' => false,
        ));
        $builder->add('applicableToImage', 'checkbox', array(
            'label' => 'Anwendbar auf Bild',
            'required' => false,
        ));
    }
}
