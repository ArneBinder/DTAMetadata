<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseTitlefragment;
use DTA\MetadataBundle\Model;

class Titlefragment extends BaseTitlefragment
{
    /**
     * @param String $value Titelfragment content e.g. 'Geschichte der frühen Neuzeit'
     * @param ENUM_VALUE $type Title fragment type e.g. 'Haupttitel' = TitlefragmentPeer::TYPE_MAIN_TITLE
     * @return \DTA\MetadataBundle\Model\Data\Titlefragment
     */
    public static function create($value, $type = TitlefragmentPeer::TYPE_MAIN_TITLE){
        $result = new Titlefragment();
        $result->setType($type);
        $result->setName($value);
        return $result;
    }
}
