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
        return 'sortableCollection';
    }

    public function finishView(FormView $view, FormInterface $form, array $options){
        
        // extract unqualified class name and pass it to the view
        // this allows more accurate control elements (instead of "add component",
        // one can use "add modelClass")
        $dataClassStr = $options['type']->getOption('data_class');
        $parts = explode('\\', $dataClassStr);
        $modelClass = array_pop($parts);
        
        $view->vars['modelClass'] = $modelClass;
    }
}
?>