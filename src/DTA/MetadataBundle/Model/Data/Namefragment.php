<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseNamefragment;
use DTA\MetadataBundle\Model;

class Namefragment extends BaseNamefragment
{
    /**
     * @param String $value Fragment content e.g. 'Hans'
     * @param ENUM_VALUE $type Type, e.g. NamefragmentPeer::TYPE_LAST_NAME
     * @return \DTA\MetadataBundle\Model\Data\Namefragment
     */
    public static function create($value, $type = NamefragmentPeer::TYPE_LAST_NAME){
        $result = new Namefragment();
        $result->setType($type);
        $result->setName($value);
        return $result;
    }
}
