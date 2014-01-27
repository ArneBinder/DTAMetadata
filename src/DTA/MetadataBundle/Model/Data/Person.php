<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePerson;

class Person extends BasePerson {
    
    // overrides the basic function to guarantee order.
    public function getPersonalnames($criteria = NULL, PropelPDO $con = NULL) {
        $collection = parent::getPersonalnames();
        // sort by rank
        $collection->uasort(function($a, $b) {
                    return $a->getSortableRank() - $b->getSortableRank();
                });
        return $collection;
    }

    public function getRepresentativePersonalname() {

        $personalNames = $this->getPersonalNames();
        return $personalNames->count() > 0 ? $personalNames[0] : "Kein Name gesetzt.";
    }

    /**
     * Used to generate a select box of existing person entities.
     * @return String Concatenation of all name fragments of the first name entity
     */
    public function getSelectBoxString() {

        $pn = $this->getPersonalnames();

        // if names exist, pick the first
        if (count($pn) > 0)
            return $pn[0]->__toString();
        else
            return $this->getGnd();
    }
    
    public static function parseFromString($row){
        
        $name = new Personalname();
            
        // also remove the GND string part from the name
        if ($row['gnd'] !== NULL) {
            $row['person'] = str_replace('#'.$row['gnd'], "", $row['person']);
        } 

        // assume a comma is indicating "last name, first name" format
        if (strpos($row['person'], ',') !== FALSE) {
            $parts = explode(',', $row['person']);
            $name->addNamefragment(new Namefragment('Nachname', $parts[0]));
            $name->addNamefragment(new Namefragment('Vorname', $parts[1]));
        // assume a space is indicating "first name last name" format
        } elseif (strpos($row['person'], ' ') !== FALSE) {
            $parts = explode(' ', $row['person']);
            $name->addNamefragment(new Namefragment('Vorname', $parts[0]));
            $name->addNamefragment(new Namefragment('Nachname', $parts[1]));
        // no comma and no space indicates a last name only 
        } else {
            $name->addNamefragment(new Namefragment('Nachname', $row['person']));
        }

        // create person
        $person = new Person();
        $person->setGnd($row['gnd'])
                ->addPersonalname($name)
                ->save();
        return $person;
        
    }

}
