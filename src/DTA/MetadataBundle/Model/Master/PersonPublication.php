<?php

namespace DTA\MetadataBundle\Model\Master;

use DTA\MetadataBundle\Model\Master\om\BasePersonPublication;
use DTA\MetadataBundle\Model;

class PersonPublication extends BasePersonPublication
{
    //    Personrole_1:
//        name: Autor
//    Personrole_2:
//        name: Verleger
//    Personrole_3:
//        name: Übersetzer
//    Personrole_4:
//        name: Drucker
        
    /** array of format ('Autor' => <id of personrole>, 'Verleger' => ...) */
    private static $personRoleIds = null;
    public static function getPersonRoleIds(){
        // retrieve IDs of namefragment types only once
        if(PersonPublication::$personRoleIds === null){
            $personRoles = Model\Classification\PersonroleQuery::create()->setFormatter('PropelArrayFormatter')->find();
            foreach ($personRoles as $nft) {
                PersonPublication::$personRoleIds[$nft['Name']] = $nft['Id'];
            }
        }
        return PersonPublication::$personRoleIds;
    }
    
    public function __construct($personId = 0, $personRole = "Autor", $publicationId=0){
        $roleIds = PersonPublication::getPersonRoleIds();
        $this->setPersonroleId($roleIds[$personRole]);
        $this->setPersonId($personId);
        if($publicationId !== 0)
            $this->setPublicationId ($publicationId);
    }
}
