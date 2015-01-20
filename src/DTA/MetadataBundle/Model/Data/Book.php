<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseBook;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\NotNull;

class Book extends BaseBook
{
    public function postSave(\PropelPDO $con = null){
       $this->getPublication()->save($con);
    }

    /**
     * @param MultiVolume $parent
     * @return Volume
     * @throws \Exception
     * @throws \PropelException
     */
    public function convertToVolume(MultiVolume $parent){
        $newVolume = new Volume();
        $newVolume->setPublication($this->getPublication());
        $newVolume->getPublication()
            ->setType(PublicationPeer::TYPE_VOLUME)
            ->setScopeValue($parent->getPublication()->getId())
            ->insertAsLastChildOf($parent->getPublication());
        $this->delete();
        return $newVolume;
    }
}
