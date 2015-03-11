<?php

namespace DTA\MetadataBundle\Form\Data;

use Symfony\Component\Form\AbstractType;

class GndType extends AbstractType
{

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'gnd';
    }
}
