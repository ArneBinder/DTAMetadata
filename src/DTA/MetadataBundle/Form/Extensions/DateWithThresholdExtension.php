<?php
/**
 * Created by PhpStorm.
 * User: binder
 * Date: 05.11.14
 * Time: 17:19
 */

namespace DTA\MetadataBundle\Form\Extensions;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateWithThresholdExtension extends AbstractTypeExtension{

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array('date_ref', 'threshold'));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('date_ref', $options) and array_key_exists('threshold', $options)) {
            $view->vars['date_ref'] = $options['date_ref'];
            $view->vars['threshold'] = $options['threshold'];
        }
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return 'date';
    }
}