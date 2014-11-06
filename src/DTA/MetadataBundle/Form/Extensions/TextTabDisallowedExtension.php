<?php
/**
 * Created by PhpStorm.
 * User: binder
 * Date: 05.11.14
 * Time: 23:58
 */

namespace DTA\MetadataBundle\Form\Extensions;


use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
//use Symfony\Component\Validator\Constraints\Length;
//use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class TextTabDisallowedExtension extends AbstractTypeExtension{

    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'constraints' => new Regex(array('pattern' => '/\t/', 'match'=> false, 'message' => 'Die Eingabe darf keine Tabstops enthalten.')))
       );
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        //$view->vars['constraints'] = array(new NotBlank);
        //echo "TEST";
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return 'text';
    }
} 