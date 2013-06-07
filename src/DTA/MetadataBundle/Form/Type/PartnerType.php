<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PartnerType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Partner',
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
        $builder->add('address');
        $builder->add('person');
        $builder->add('mail');
        $builder->add('web');
        $builder->add('comments');
        $builder->add('phone1');
        $builder->add('phone2');
        $builder->add('phone3');
        $builder->add('fax');
    }
}
