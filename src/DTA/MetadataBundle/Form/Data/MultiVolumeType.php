<?php

namespace DTA\MetadataBundle\Form\Data;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MultiVolumeType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Data\MultiVolume',
        'name'       => 'multivolume',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('publication', new PublicationType());
        $builder->add('volumes_total');
        
    }
}