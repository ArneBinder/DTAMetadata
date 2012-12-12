<?php

namespace DTA\MetadataBundle\Form\DerivedType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SortableCollectionType extends \Symfony\Component\Form\Extension\Core\Type\CollectionType {
    
    public function getName() {
        return 'mess';
    }
    /**
     * {@inheritdoc}
     */
//    public function buildForm(FormBuilderInterface $builder, array $options)
//    {   
//        var_dump($options);
//        if ($options['allow_add'] && $options['prototype']) {
//            $prototype = $builder->create($options['prototype_name'], $options['type'], array_replace(array(
//                'label' => $options['prototype_name'] . 'label__',
//            ), $options['options']));
//            $builder->setAttribute('prototype', $prototype->getForm());
//        }
//
//        $resizeListener = new ResizeFormListener(
//            $builder->getFormFactory(),
//            $options['type'],
//            $options['options'],
//            $options['allow_add'],
//            $options['allow_delete']
//        );
//
//        $builder->addEventSubscriber($resizeListener);
//    }

    /**
     * {@inheritdoc}
     */
//    public function buildView(FormView $view, FormInterface $form, array $options)
//    {
//        $view->vars = array_replace($view->vars, array(
//            'allow_add'    => $options['allow_add'],
//            'allow_delete' => $options['allow_delete'],
//        ));
//
//        if ($form->getConfig()->hasAttribute('prototype')) {
//            $view->vars['prototype'] = $form->getConfig()->getAttribute('prototype')->createView($view);
//        }
//    }

    /**
     * {@inheritdoc}
     */
//    public function finishView(FormView $view, FormInterface $form, array $options)
//    {
//        if ($form->getConfig()->hasAttribute('prototype') && $view->vars['prototype']->vars['multipart']) {
//            $view->vars['multipart'] = true;
//        }
//    }

    /**
     * {@inheritdoc}
     */
//    public function setDefaultOptions(OptionsResolverInterface $resolver) {
//        $optionsNormalizer = function (Options $options, $value) {
//                    $value['block_name'] = 'entry';
//                    return $value;
//                };
//
//        $resolver->setDefaults(array(
//            'allow_add' => true,
//            'allow_delete' => false,
//            'prototype' => true,
//            'prototype_name' => '__name__',
//            'type' => 'text',
//            'label' => ' ',                 // TODO finalization. workaround. This causes no label to be rendered. Ugly solution! Use theming (see getName).
//            'options' => array(),
//        ));
//
//        $resolver->setNormalizers(array(
//            'options' => $optionsNormalizer,
//        ));
//    }

    /**
     * This determines the HTML that is generated via the according twig template block. 
     * See src/DTA/MetadataBundle/Resources/views/Form/sortableCollection.html.twig 
     * in the sortableCollection_widget block (block name by convention)
     * {@inheritdoc}
     */

}

?>