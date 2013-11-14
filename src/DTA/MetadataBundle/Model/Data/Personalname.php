<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePersonalname;

class Personalname extends BasePersonalname
{
    public function getNameFragments($criteria = NULL, PropelPDO $con = NULL){
        $collection = parent::getNamefragments();
         // Re-sort them by Sequence, numerically
        $collection->uasort(function($a, $b) {
            return $a->getSortableRank() - $b->getSortableRank();
        });
        return $collection;
    }
    

    /**
     * Converts the name to a single string. Formats 
     * last_name, first_name_1 first_name_2 ...
     * if there are only first and last name fragments and 
     * namefragment1 namefragment2 namefragment3 ...
     * otherwise.
     */
    public function __toString(){
// TODO: If there should be any issue with the order, switch to the more complicated query structure.
        $allNF = NamefragmentQuery::create()
                ->filterByPersonalnameId($this->getId())
                ->joinWith('Namefragmenttype')  // no further queries needed to hydrate the namefragment types 
                ->orderByRank('asc')            // the rank represents the order of the fragments (i.e. first name 1, last name 1, first name 2)
                ->find();
        
        $firstNames = array();
        $lastNames  = array();
        $simpleName = true;
        foreach($allNF as $nameFragment){
            $nft = $nameFragment->getNamefragmenttype()->getName();
            switch ($nft) {
                case "Vorname":
                    $firstNames[] = $nameFragment->getName();
                    break;
                case "Nachname":
                    $lastNames[] = $nameFragment->getName();
                    break;
                default: // if there are other types, the simple scheme won't fit
                    $simpleName = false;
                    break 2;
            }
        }
        
        if(count($lastNames) == 0) $simpleName = false;
        
        if( $simpleName ){
            return implode(" ", $lastNames) . ", " . implode(" ", $firstNames);
        } else {
            $result = "";
            foreach($allNF as $nameFragment){
                $result .= $nameFragment->getName() . " ";
            }
            return $result;
        }
    }
 
}
