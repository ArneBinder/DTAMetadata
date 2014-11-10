<?php

namespace DTA\MetadataBundle\Form\Workflow;

//use DTA\MetadataBundle\Form\Extensions\DateWithThresholdExtension;
use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use DTA\MetadataBundle\Model\Workflow\Task;



class TaskType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Workflow\Task',
        'name'       => 'task',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tasktype', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'addButton'  => false,
            'class' => '\DTA\MetadataBundle\Model\Workflow\Tasktype',
            'property' => 'name',
            'required' => true
        ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $task = $event->getData();
            $form = $event->getForm();

            if (!$task || null === $task->getId()) {
                $form->add('start_date', null, array('years'=>range(2005,2020), 'widget' => 'single_text', 'threshold_ref' => 'end_date', 'threshold_type' => 'min', 'data' => new \DateTime('today')));
            }else{
                $form->add('start_date', null, array('years'=>range(2005,2020), 'widget' => 'single_text', 'threshold_ref' => 'end_date', 'threshold_type' => 'min'));
            }
            $form->add('end_date', null, array('years'=>range(2005,2020),'widget' => 'single_text', 'threshold_ref' => 'start_date', 'threshold_type' => 'max'));
            $form->add('comments');
            $form->add('DTAUser', 'model', array(
                'property' => 'username',
                'class' => 'DTA\MetadataBundle\Model\Master\DTAUser',
                'label' => 'verantwortlich'
            ));
            $form->add('Partner', 'model', array(
                'property' => 'name',
                'class' => 'DTA\MetadataBundle\Model\Workflow\Partner',
                'label' => 'Partner',
                'query' => \DTA\MetadataBundle\Model\Workflow\Partner::getRowViewQueryObject()
            ));
            $form->add('closed', null, array(
                'label' => 'Abgeschlossen',
                'attr' => array('expanded'=>true),
            ));
        });
        //$builder->add('start_date', null, array('years'=>range(2005,2020), 'widget' => 'single_text', 'date_ref' => 'end_date', 'threshold' => 'min'));//,
            //'data' => (isset($options['data']) && $options['data']->getStartDate() !== null) ? $options['data']->getStartDate() : new \DateTime('today')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array('collapsed' => false));
    }
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['collapsed'] = $options['collapsed'];
        //echo "TEST";
    }
}
