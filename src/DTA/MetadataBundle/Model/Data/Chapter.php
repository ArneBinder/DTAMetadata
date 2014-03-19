<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseChapter;

class Chapter extends BaseChapter
{
    public function postSave(\PropelPDO $con = null){
       $this->getPublication()->save($con);
    }
}
