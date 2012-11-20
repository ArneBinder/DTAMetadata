<?php

namespace DTA\MetadataBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PublicationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('publishingcompanyId');
        $builder->add('placeId');
        $builder->add('datespecificationId');
        $builder->add('printrun');
        $builder->add('printruncomment');
        $builder->add('edition');
        $builder->add('numpages');
        $builder->add('numpagesnormed');
        $builder->add('bibliographiccitation');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DTA\MetadataBundle\Model\Publication',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'publication';
    }
}
