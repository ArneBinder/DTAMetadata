<?php

namespace DTA\MetadataBundle\Model\Classification;

use DTA\MetadataBundle\Model\Classification\om\BaseGenre;

class Genre extends BaseGenre
{
    /**
     * @return string name of the parent genre, if any
     */
    public function getParent(){
        $parent = $this->getGenreRelatedByChildof();
        return $parent == null ? '' : $parent->getName();
    }
}
