<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePersonalname;

class Personalname extends BasePersonalname
{
    public function getNameFragments($criteria = NULL, PropelPDO $con = NULL){
        $collection = parent::getNamefragments($criteria);
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
        $allNF = $this->getNameFragments();
        $firstNames = array();
        $lastNames  = array();
        $simpleName = true;
        foreach($allNF as $nameFragment){
            switch ($nameFragment->getType()) {
                case NamefragmentPeer::TYPE_FIRST_NAME:
                    $firstNames[] = $nameFragment->getName();
                    break;
                case NamefragmentPeer::TYPE_LAST_NAME:
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
    
    /** This is required since otherwise, modifying title fragments won't affect isModified() at all! */
    public function isModified()
    {
        $modified = !empty($this->modifiedColumns);
        if( ! $modified ){
            foreach (parent::getNamefragments() as $nf) {
                if($nf->isModified()) return true;
            }
        }
        return false;
    }
 
}
