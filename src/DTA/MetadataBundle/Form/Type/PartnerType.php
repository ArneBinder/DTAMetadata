<?php

namespace DTA\MetadataBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PartnerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('adress');
        $builder->add('person');
        $builder->add('mail');
        $builder->add('web');
        $builder->add('comments');
        $builder->add('phone1');
        $builder->add('phone2');
        $builder->add('phone3');
        $builder->add('fax');
        $builder->add('logLastChange');
        $builder->add('logLastUser');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DTA\MetadataBundle\Model\Partner',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'partner';
    }
}
