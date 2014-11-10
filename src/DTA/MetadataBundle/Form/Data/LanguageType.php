<?php

namespace DTA\MetadataBundle\Form\Data;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Finder\Expression\Regex;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LanguageType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Data\Language',
        'name'       => 'language',
    );

    /*public function setDefaultOptions(OptionsResolverInterface $resolver) {

        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
                'constraints' => new Regex(array('pattern' => '/^[A-Z]/', 'match'=> true, 'message' => 'Sprachen müssen mit einem Großbuchstaben beginnen.')))
        );
    }*/

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
    }
}
