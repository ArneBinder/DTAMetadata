<?php

namespace DTA\MetadataBundle\Twig;

class SetPlainArrayExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'setIndex' => new \Twig_Filter_Method($this, 'setIndex'),
        );
    }

    public function setIndex($array, $index, $value)
    {
        $array[$index] = $value;
        return $array;
    }

    public function getName()
    {
        return 'dta_setPlainArray';
    }
}

?>