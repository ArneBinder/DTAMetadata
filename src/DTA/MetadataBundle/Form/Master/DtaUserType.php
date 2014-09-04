<?php

namespace DTA\MetadataBundle\Form\Master;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DtaUserType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Master\DtaUser',
        'name'       => 'dtauser',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username','text');
        $builder->add('mail','text', array(
            'required' => false,
        ));
        $builder->add('password', 'password', array(
            'required' => true,
        ));
        $builder->add('admin', 'checkbox', array(
            'label' => 'Administratorenrechte',
            'required' => false,
        ));
    }
}
