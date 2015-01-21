<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseMultiVolume;
use DTA\MetadataBundle\Model\Data\om\BaseVolume;

class MultiVolume extends BaseMultiVolume
{
    public function postSave(\PropelPDO $con = null){
       $this->getPublication()->save($con);
    }

    public function getTitleString(){
        $publication = $this->getPublication();
        return $publication->getFirstAuthorName().": ".$publication->getTitleString();
    }

    public function getVolumes(){
        $children = $this->getPublication()->getChildren();
        $result = array();
        foreach($children as $child) {
            $result[] = $child->getSpecialization();
        }
        return $result;
    }

    /**
     * @param Volume $newVolume
     */
    public function addVolume($newVolume){
        $publication = $this->getPublication();
        $newVolume->getPublication()
                ->setType(PublicationPeer::TYPE_VOLUME)
                ->setScopeValue($publication->getId())
                ->insertAsLastChildOf($publication)
                ->save();

        $this->setVolumesTotal($this->getPublication()->countChildren())->save();
    }

    /**
     * @param Volume $oldVolume
     */
    public function removeVolume($oldVolume){
        /*$oldPublication = $oldVolume->getPublication();
        $oldPublication->delete();
        $oldPublication->
        $oldVolume->getPublication()
            ->setScopeValue($publication->getId())
            ->insertAsLastChildOf($publication)
            ->save();

        $this->setVolumesTotal($this->getPublication()->countChildren())->save();
        */
    }
}
