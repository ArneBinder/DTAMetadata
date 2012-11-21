<?php

namespace DTA\MetadataBundle\Model\Description;

use DTA\MetadataBundle\Model\Description\om\BasePersonalname;

class Personalname extends BasePersonalname
{
    public static function generateByFirstAndLastName($firstName, $lastName){
        
        // It is assumed that at least these two name fragment types always exist and are exactly named like this.
        $firstNameType = NamefragmenttypeQuery::create()->findOneByName("Vorname");
        $lastNameType = NamefragmenttypeQuery::create()->findOneByName("Nachname");
        
        $result = new Personalname();
               
        $first = new Namefragment();
        $first->setName($firstName);
        $first->setNamefragmenttype($firstNameType);
        $first->setPersonalname($result);
        $first->save();
        
        $last = new NameFragment();
        $last->setName($lastName);
        $last->setNamefragmenttype($lastNameType);
        $last->setPersonalname($result);
        $last->save();       

        return $result;
    }
}
