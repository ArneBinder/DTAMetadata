<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseSeries;

class Series extends BaseSeries
{
    public function postSave(\PropelPDO $con = null){
       $this->getPublication()->save($con);
    }
}
