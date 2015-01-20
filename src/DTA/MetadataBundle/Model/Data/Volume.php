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
        
        return sprintf(" (Band %d)", $this->getVolumeNumeric(), $this->getVolumeDescription());
        
    }

    public function getParentPublication(){
        return $this->getPublication()->getParent();
    }

    public function convertToBook(){
        $publication = $this->getPublication();
        $parent = $publication->getParent();
        $this->delete();

        $newBook = new Book();
        $newBook->setPublication($this->getPublication());
        $newBook->getPublication()
            ->setType(PublicationPeer::TYPE_BOOK)
            ->setScopeValue($publication->getId());
        if($parent->countChildren()==0){
            //$parent->delete();
            $parent->getPeer()->deleteTree($parent->getScopeValue());
        }
        return $newBook;
    }
}
