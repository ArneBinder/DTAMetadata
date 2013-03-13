<?php

namespace DTA\MetadataBundle\Twig;

class CurrentTimestampExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'getCurrentTimestamp' => new \Twig_SimpleFunction('getCurrentTimestamp', function(){
//                sleep(1);
                return time();
            }),
        );
    }

    public function getName()
    {
        return 'currentTimeStamp_extension';
    }
}

?>