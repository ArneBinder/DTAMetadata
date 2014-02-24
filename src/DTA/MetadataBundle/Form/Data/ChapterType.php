<?php

namespace DTA\MetadataBundle\Form\Data;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChapterType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Data\Chapter',
        'name'       => 'chapter',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('publication', new PublicationType());
        $builder->add('pages');
        
    }
}