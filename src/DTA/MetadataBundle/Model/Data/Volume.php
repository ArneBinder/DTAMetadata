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
        $parent = $this->getPublication()->getParent();
        if($parent !== null) {
            return $this->getPublication()->getParent()->getSpecialization();
        }else{
            return new MultiVolume();
        }
    }

    public function getParentPublicationTitleString(){
        $parent = $this->getPublication()->getParent();
        if($parent !== null) {
            return $this->getPublication()->getParent()->getShortTitle();
        }else{
            return "WARNING: no parent set!";
        }
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

            if (($oldParent != $newParent) and !($newParent->isAncestorOf($this->getPublication()))) {
                $this->getPublication()->deleteFromTree();
                if (!$newParent->isRoot()) {
                    $newParent->makeRoot();
                }
                if (!$newParent->getScopeValue()) {
                    //throw new \Exception("TESTA ". $newParent->getScopeValue());
                    $newParent->setScopeValue($newParent->getId());
                }

                $newParent->save();

                //$multiVolume->setVolumesTotal($multiVolume->getVolumesTotal() + 1);
                $this->getPublication()
                    //->setScopeValue($newParent->getScopeValue())
                    ->insertAsLastChildOf($newParent)
                    ->save();
                $multiVolume->setVolumesTotal($newParent->countChildren())->save();
                //$multiVolume->save();
                //throw new \Exception("TEST ". $this->getPublication()->getParent());


                //$newParent->insertAsLastChildOf($this->getPublication())->save();
                //$multiVolume->setVolumesTotal($newParent->countChildren())->save();
                if ($oldParent!==null and $oldParent->countChildren() == 0) {
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
