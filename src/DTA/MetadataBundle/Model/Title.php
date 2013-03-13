<?php

namespace DTA\MetadataBundle\Model;

use DTA\MetadataBundle\Model\om\BaseTitle;

class Title extends BaseTitle
{
    public function getTitleFragments($criteria = NULL, PropelPDO $con = NULL){
         $collection = parent::getTitlefragments();
         // Re-sort them by Sequence, numerically
        $collection->uasort(function($a, $b) {
            return $a->getSortableRank() - $b->getSortableRank();
        });
        return $collection;
    }
    
    public function __toString(){
        
        $fragmentStrings = [];
        // convert the fragment object to strings, possibly marking reconstructed title fragments
        foreach ($this->getTitlefragments() as $nf) {
            if($nf instanceof \DTA\MetadataBundle\Model\reconstructed_flaggable\ReconstructedFlaggableInterface)
                $fragmentStrings[] = $nf->getMarkedByName('Name');
            else
                $fragmentStrings[] = $nf->getName();
        }
        return implode(" ~ ", $fragmentStrings);
    }
}
