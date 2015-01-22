<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseBook;
use DTA\MetadataBundle\Model\Data\Volume;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\NotNull;

class Book extends BaseBook
{
    var $convertToVolume = false;
    /* @var $asVolume Volume */
    var $asVolume = null;
    var $parentMultiVolume = null;

    public function __construct()
    {
        $this->asVolume = new Volume();
    }

    public function postSave(\PropelPDO $con = null){
        if($this->convertToVolume){
            $this->getPublication()->setType(PublicationPeer::TYPE_VOLUME);
            $this->asVolume->setPublication($this->getPublication())->save();
            $this->delete();
        }else {
            $this->getPublication()->save($con);
        }
    }

    public function getIsVolume(){
        return false;
    }

    public function setIsVolume($v){
        $this->convertToVolume = $v;
        return $this;
    }

    public function getVolumeDescription(){
        return $this->asVolume->getVolumeDescription();
    }

    public function setVolumeDescription($v){
        return $this->asVolume->setVolumeDescription($v);
    }

    public function getVolumeNumeric(){
        return $this->asVolume->getVolumeNumeric();
    }

    public function setVolumeNumeric($v){
        return $this->asVolume->setVolumeNumeric($v);
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

    public function setParentPublication($v){
        $this->asVolume->setPublication($this->getPublication());
        $this->asVolume->setParentPublication($v);
        return $this;
    }


    /**
     * @param MultiVolume $parent
     * @return Volume
     * @throws \Exception
     * @throws \PropelException
     */
    /*public function convertToVolume(MultiVolume $parent){
        $newVolume = new Volume();
        $newVolume->setPublication($this->getPublication());
        $newVolume->getPublication()
            ->setType(PublicationPeer::TYPE_VOLUME)
            ->setScopeValue($parent->getPublication()->getId())
            ->insertAsLastChildOf($parent->getPublication());
        $this->delete();
        return $newVolume;
    }*/
}
