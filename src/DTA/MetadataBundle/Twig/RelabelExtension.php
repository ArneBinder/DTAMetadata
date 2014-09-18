<?php
/**
 * Created by PhpStorm.
 * User: binder
 * Date: 18.09.14
 * Time: 23:31
 */

namespace DTA\MetadataBundle\Twig;


class RelabelExtension extends \Twig_Extension
{
    private $dict = array(
                        Schlüssel  => Wert,
                        Schlüssel2 => Wert2,
                        Schlüssel3 => Wert3
                        );

    public function getValueOrKey($key)
    {
        $value = $this->dict[$key];
        return $value!=null?$value:$key;
    }


public function getName()
    {
        return 'dta_relabel';
    }
}

?>