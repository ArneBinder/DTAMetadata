<?php

namespace DTA\MetadataBundle\Form\Workflow;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PartnerType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Workflow\Partner',
        'name'       => 'partner',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('isOrganization', 'checkbox', array(
            'label' => "Ist eine Organisation",
            'required' => false
        ));
        $builder->add('contactPerson');
        $builder->add('comments');
        $builder->add('contact_data', 'textarea');
    }
}
