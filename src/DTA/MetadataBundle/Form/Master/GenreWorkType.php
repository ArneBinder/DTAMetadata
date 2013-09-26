<?php

namespace DTA\MetadataBundle\Form\Master;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use DTA\MetadataBundle\Model\Classification\GenreQuery;

class GenreWorkType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'DTA\MetadataBundle\Model\Master\GenreWork',
        'name'       => 'genrework',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('genre', new \DTA\MetadataBundle\Form\DerivedType\SelectOrAddType(), array(
            'class' => '\DTA\MetadataBundle\Model\Classification\Genre',
            'property' => 'Name',
            'label' => 'Genre',
            'query' => GenreQuery::create()->filterByChildof(null, GenreQuery::ISNOTNULL),
        ));
    }
}
