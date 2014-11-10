<?php
/**
 * Created by PhpStorm.
 * User: binder
 * Date: 05.11.14
 * Time: 23:58
 */

namespace DTA\MetadataBundle\Form\Extensions;


use Symfony\Component\Form\AbstractTypeExtension;
//use Symfony\Component\Form\FormError;
//use Symfony\Component\Form\FormView;
//use Symfony\Component\Form\FormInterface;
//use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
//use Symfony\Component\Validator\Constraints\Length;
//use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;

class TextExtension extends AbstractTypeExtension{

    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'constraints' => new Regex(array('pattern' => '/\t/', 'match'=> false, 'message' => 'Die Eingabe darf keine Tabstops enthalten.')))
       );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // to merge duplicated spaces in input data (no feedback for the user is implemented yet!)...
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $text = $event->getData();
            $form = $event->getForm();
            if(preg_match('/  /',$text)===1){
                //$form->addError(new FormError('text field contains double spaces'));
                $event->setData(preg_replace('/  +/',' ',$text));
            }

        });
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