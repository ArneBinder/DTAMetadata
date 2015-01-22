<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseVolume;

class Volume extends BaseVolume
{
    var $convertToBook = false;

    public function postSave(\PropelPDO $con = null){
        if($this->convertToBook){
            $this->deleteFromTree();
            $this->getPublication()->setType(PublicationPeer::TYPE_BOOK);
            $asBook = new Book();
            $asBook->setPublication($this->getPublication())->save();
            $this->delete();
        }else {
            $this->getPublication()->save($con);
        }
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

    public function getParentPublicationShortTitle(){
        $parent = $this->getPublication()->getParent();
        if($parent !== null) {
            return $this->getPublication()->getParent()->getShortTitle();
        }else{
            return "WARNING: no parent set!";
        }
    }

    public function getIsVolume(){
        return true;
    }

    public function setIsVolume($v){
        $this->convertToBook = !$v;
        return $this;
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
                $this->deleteFromTree();
                if (!$newParent->isRoot()) {
                    $newParent->makeRoot();
                }
                if (!$newParent->getScopeValue()) {
                    $newParent->setScopeValue($newParent->getId());
                }

                $newParent->save();

                $this->getPublication()
                    ->insertAsLastChildOf($newParent)
                    ->save();
                $multiVolume->setVolumesTotal($newParent->countChildren())->save();

                if ($oldParent!==null and $oldParent->countChildren() == 0) {
                   $oldParent->getPeer()->deleteTree($oldParent->getScopeValue());
                }
            }
        }
        return $this;

    }

    public function deleteFromTree(){
        if ($this->isDeleted()) {
            throw new \PropelException("This object has already been deleted.");
        }
        $con = \Propel::getConnection(PublicationPeer::DATABASE_NAME, \Propel::CONNECTION_WRITE);
        $con->beginTransaction();
        try {
            // nested_set behavior
            if ($this->getPublication()->isRoot()) {
                throw new \PropelException('Deletion of a root node is disabled for nested sets. Use PublicationPeer::deleteTree($scope) instead to delete an entire tree');
            }

            // nested_set behavior
            if ($this->getPublication()->isInTree()) {
                // fill up the room that was used by the node
                PublicationPeer::shiftRLValues(-2, $this->getPublication()->getRightValue() + 1, null, $this->getPublication()->getScopeValue(), $con);

                $this->getPublication()
                    ->setTreeLeft(null)
                    ->setTreeRight(null)
                    ->setTreeLevel(null)
                    ->setScopeValue($this->getId())
                    ->save($con);
            }
            $con->commit();
        } catch (\Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }
}
