<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\User',
        'name'       => 'user',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username');
        $builder->add('mail');
        $builder->add('phone');
        $builder->add('admin', 'checkbox', array(
            'label' => 'Administratorenrechte',
            'attr' => array('expanded'=>true)
        ));
    }
}
