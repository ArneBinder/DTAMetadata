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

    /**
     * @return MultiVolume
     */
    public function getParentPublication(){
        return $this->getPublication()->getParent()->getSpecialization();
    }

    /**
     * @param MultiVolume $multiVolume
     * @throws \Exception
     * @throws \PropelException
     */
    public function setParentPublication($multiVolume){
        if($multiVolume!=null) {
            $oldParent = $this->getPublication()->getParent();
            $newParent = $multiVolume->getPublication();

            //$newParent.isAncestorOf
            if (($oldParent != $newParent) and !($newParent->isAncestorOf($this->getPublication()))) {
                if (!$newParent->isRoot()) {
                    $newParent->makeRoot();

                }
                if (!$newParent->getScopeValue()) {
                    //throw new \Exception("TESTA ". $newParent->getScopeValue());
                    $newParent->setScopeValue($newParent->getId());
                }

                $multiVolume->setVolumesTotal($multiVolume->getVolumesTotal() + 1);
                $this->getPublication()
                    //->setScopeValue($newParent->getScopeValue())
                    ->insertAsLastChildOf($newParent)
                    ->save();
                //$multiVolume->save();
                //throw new \Exception("TEST ". $this->getPublication()->getParent());


                //$newParent->insertAsLastChildOf($this->getPublication())->save();
                //$multiVolume->setVolumesTotal($newParent->countChildren())->save();
                if ($oldParent != null and $oldParent->countChildren() == 0) {
                    //$parent->delete();
                    $oldParent->getPeer()->deleteTree($oldParent->getScopeValue());
                }
            }
        }
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
