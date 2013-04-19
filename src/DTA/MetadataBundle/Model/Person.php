<?php

namespace DTA\MetadataBundle\Model;

use DTA\MetadataBundle\Model\om\BasePerson;

class Person extends BasePerson
{
    public function getPersonalnames($criteria = NULL, PropelPDO $con = NULL){
        $collection = parent::getPersonalnames();
         // sort by rank
        $collection->uasort(function($a, $b) {
            return $a->getSortableRank() - $b->getSortableRank();
        });
        return $collection;
}
    
    /**
     * Used to generate a select box of existing person entities.
     * @return String Concatenation of all name fragments of the first name entity
     */
    public function getSelectBoxString(){
    
        $pn = $this->getPersonalnames();
        
        // if names exist, pick the first
        if(count($pn)>0)
            return $pn[0]->__toString();
        else 
            return $this->getGnd();
        
    }
}
