<?php

namespace DTA\MetadataBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use DTA\MetadataBundle\Form\DerivedType\SelectOrAddType;
use DTA\MetadataBundle\Form\DerivedType\DynamicCollectionType;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SelectOrAddForType extends BaseAbstractType
{
    protected $options = array(
        'name'       => 'SelectOrAddFor',
    );

    /**
     * @var string The accessor of the model class to fill the form data into.
     * If none given, the forClass is used as default.
     */
    private $attribute; 
    /**
     * @var array The data as if passed to the select or add form type directly.
     */
    private $selectOrAddOptions = array();
    
//    public function SelectOrAddForType($selectOrAddOptions){
//        $this->attribute = $attribute;
//        $this->selectOrAddOptions = $selectOrAddOptions;
//    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('Categories', new SelectOrAddType(), $options);
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        
        parent::setDefaultOptions($resolver);
        
        $resolver->setDefaults(array(
            'name' => '2',
        ));
    }
}
