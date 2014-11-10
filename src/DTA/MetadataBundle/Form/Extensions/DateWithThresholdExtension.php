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
        $resolver->setOptional(array('threshold_ref', 'threshold_type'));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('threshold_ref', $options) and array_key_exists('threshold_type', $options)) {
            $view->vars['threshold_ref'] = $options['threshold_ref'];
            $view->vars['threshold_type'] = $options['threshold_type'];
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