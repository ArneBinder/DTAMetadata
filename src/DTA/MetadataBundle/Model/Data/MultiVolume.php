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
        if($publication!==null) {
            return $publication->getFirstAuthorName() . ": " . $publication->getTitleString();
        }else{
            return "null";
        }
    }

    public function getVolumes(){
        if($this->getPublication()===null){
            return array();
        }
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
        //TODO: implement!
    }

    public function __toString(){
        return $this->getTitleString();
    }
}
