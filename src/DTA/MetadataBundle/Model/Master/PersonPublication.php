<?php

namespace DTA\MetadataBundle\Model\Master;

use DTA\MetadataBundle\Model\Master\om\BasePersonPublication;
use DTA\MetadataBundle\Model;

class PersonPublication extends BasePersonPublication
{
    
    public static function create($personId, $personRole, $publicationId = NULL){
        $result = new PersonPublication();
        $result->setRole($personRole);
        $result->setPersonId($personId);
        $result->setPublicationId($publicationId);
        return $result;
    }
}
