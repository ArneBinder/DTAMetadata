<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseVolume;

class Volume extends BaseVolume
{
    
    public function getVolumesTotal(){
        
        return $this->getPublication()->getSiblings()->count();
        
    }
}
