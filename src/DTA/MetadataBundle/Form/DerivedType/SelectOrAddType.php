<?php

namespace DTA\MetadataBundle\Form\DerivedType;

use Symfony\Bridge\Propel1\Form\ChoiceList\ModelChoiceList;
use Symfony\Bridge\Propel1\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Using this type in a formbuilder allows selection of an existing entity or creation of a new one. 
 * The gui element (button) for creating a new entity is added via form theming (see dtaFormExtensions.html.twig).
 * The javascript is located in 
 *
 * @author carlwitt
 */

class SelectOrAddType extends \Symfony\Bridge\Propel1\Form\Type\ModelType {
    
    public function getName() {
        return 'selectOrAdd';
    }
    
    public function finishView(FormView $view, FormInterface $form, array $options){
        
        // fully qualified class name (e.g. DTA\MetadataBundle\Model\Status)
        $className = $options['class'];
        
        // extract the class name (e.g. Status)
        $parts = explode('\\', $className);
        $modelClass = array_pop($parts);
        
        $view->vars['modelClass'] = $modelClass;
    }
    
//    public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//        // retrieve the choice list from the database
//        $choiceList = function (Options $options) {
//            return new ModelChoiceList(
//                $options['class'],
//                $options['property'],
//                $options['choices'],
//                $options['query'],
//                $options['group_by'],
//                $options['preferred_choices']
//            );
//        };
//        
//        // add an option for explicit class naming (to trigger an appropriate input form on clicking the add button)
//        $resolver->setDefaults(array(
//            'template'          => 'choice',
//            'multiple'          => false,
//            'expanded'          => false,
//            'class'             => null,
//            'property'          => null,
//            'query'             => null,
//            'choices'           => null,
//            'choice_list'       => $choiceList,
//            'group_by'          => null,
//            'by_reference'      => false,
//        ));
//    }
}

?>
