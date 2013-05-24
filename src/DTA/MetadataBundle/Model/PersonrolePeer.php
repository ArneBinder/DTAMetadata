<?php

namespace DTA\MetadataBundle\Model;

use DTA\MetadataBundle\Model\om\BasePersonrolePeer;

class PersonrolePeer extends BasePersonrolePeer
{
    // the id of the author person role in the database
//    const AUTHOR = 1;

    // can fixed via constants later if performance lags.
    public static function getAuthorRoleId(){
        $authorRole = PersonroleQuery::create()->filterByName("Autor")->findOne();
        if($authorRole === null)
            throw \Exception("Die Rolle Autor ist noch nicht in der Datenbank vorhanden!");
        else
            return $authorRole->getId();
    }
}
