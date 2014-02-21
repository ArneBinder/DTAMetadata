<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseTitle;

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
            if($nf instanceof \DTA\MetadataBundle\Model\reconstructed_flaggable\ReconstructedFlaggableInterface){
                $fragmentStrings[] = $nf->getMarkedByName('Name');
            } else {
                $fragmentStrings[] = $nf->getName();
            }
        }
//        won't work because of output escaping.
//        implement a specialized view here.
//        $fragmentStrings = array_map(function($el){return "<span title='select'> $el </span>";}, $fragmentStrings);
        return implode(" ~ ", $fragmentStrings);
    }
    
    /** This is required since otherwise, modifying title fragments won't affect isModified() at all! */
    public function isModified()
    {
        $modified = !empty($this->modifiedColumns);
        if( ! $modified ){
            foreach (parent::getTitlefragments() as $tf) {
                if($tf->isModified()) return true;
            }
        }
        return false;
    }
}
