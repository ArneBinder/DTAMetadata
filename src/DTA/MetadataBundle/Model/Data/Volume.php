<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseVolume;

class Volume extends BaseVolume
{
    public function postSave(\PropelPDO $con = null){
       $this->getPublication()->save($con);
    }

    /** Returns the number of volumes that the parent multi-volume has in reality, as opposed to getNumberOfDigitizedVolumes. */
    public function getNumberOfRealVolumes(){
        return $this->getPublication()->getParent()->getSpecialization()->getVolumesTotal();
        
    }
    
    /** Returns the number of volumes that are in the database, as opposed to getNumberOfRealVolumes. */
    public function getNumberOfDigitizedVolumes(){
        
        return $this->getPublication()->getParent()->countChildren();
        
    }
    
    public function getVolumeSummary(){
        
        return sprintf(" (%d) %s", $this->getVolumeNumeric(), $this->getVolumeDescription());
        
    }
}
