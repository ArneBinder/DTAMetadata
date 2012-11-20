<?php

namespace DTA\MetadataBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WritType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('workId');
        $builder->add('publicationId');
        $builder->add('publisherId');
        $builder->add('printerId');
        $builder->add('translatorId');
        $builder->add('numpages');
        $builder->add('relatedsetId');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DTA\MetadataBundle\Model\Writ',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'writ';
    }
}
