<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseMultiVolume;

class MultiVolume extends BaseMultiVolume
{
    public function postSave(\PropelPDO $con = null){
       $this->getPublication()->save($con);
    }

    public function getTitleString(){
        return $this->getPublication()->getTitleString();
    }
}
