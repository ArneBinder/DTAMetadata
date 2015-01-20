<?php

namespace DTA\MetadataBundle\Form\DerivedType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DynamicCollectionType extends \Symfony\Component\Form\Extension\Core\Type\CollectionType {

    /**
     * @var string contains the unqualified model class name to use in the view (for translation, dynamic controls, etc.)
     */
    public $modelClass;
    public $package;
    protected $themeBlockName;

    public function getName() {
//  this is only necessary if registering the type as a service, because the symfony form type registry runs additional checks.
//        if( 0 == strlen($this->themeBlockName) ) return 'dynamicCollection';
//        else
        return $this->themeBlockName;
    }

    public function getParent(){
        return 'collection';
    }
    
    /**
     * Builds the prototype with modified placeholders to allow nested collections.
     * @todo The themeBlockName parameter might be unnecessary. I think symfony offers enough tools to achieve this without custom logic?
     * @param $options['themeBlockName']    allows individual styling of a collection, 
     *                                      e.g. based on the entity that's stored in it. Pass any string under the themeBlockName 
     *                                      option to override the dynamic collection form type name.
     * @param $options['sortable']          determines whether the order of collection elements shall be changeable
     * @param $options['inlineLabel']       render an extra column with label or not
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        parent::buildForm($builder, $options);

        // modify prototype as to allow nested sortable collections

        // extract unqualified class name and pass it to the view
        // this allows more accurate control elements (instead of "add component",
        // one can use "add modelClass")
        $dataClassStr = $options['type']->getOption('data_class');
        $parts = explode('\\', $dataClassStr);
        $this->modelClass = array_pop($parts);
        $this->package = array_pop($parts);

        $prototypeName = '__' . $this->modelClass . 'ID__';

        if ($options['allow_add'] && $options['prototype']) {
            $prototype = $builder->create($prototypeName, $options['type'], array_replace(array(
                        'label' => $options['prototype_name'] . 'label__',
                            ), $options['options']));
            $builder->setAttribute('prototype', $prototype->getForm());
        }
        
        // push themeBlockName for access via getName
        if ($options['themeBlockName'] !== null)
            $this->themeBlockName = $options['themeBlockName'];
        else
            $this->themeBlockName = 'dynamicCollection';
    }

    public function finishView(FormView $view, FormInterface $form, array $options) {
        $view->vars['modelClass'] = $this->modelClass;
        $view->vars['package'] = $this->package;
        
        $listAdditionalCssClasses = '';
        if($options['sortable'] === true)
            $listAdditionalCssClasses .= ' sortable';
        
        $view->vars['sortable'] = $options['sortable'];
        $view->vars['listAdditionalCssClasses'] = $listAdditionalCssClasses;
        $view->vars['inlineLabel'] = $options['inlineLabel'];
        $view->vars['displayAs'] = $options['displayAs'];
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        parent::setDefaultOptions($resolver);
        
        $resolver->setDefaults(array(
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'themeBlockName' => null,   // see build form for parameter explanation
            'sortable' => true,
            'inlineLabel' => true,
            'required' => false,
            'displayAs' => 'list',
            'modelClass' => $this->modelClass,
            'package' => $this->package,
        ));
    }

    
}

?>