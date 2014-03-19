<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseJournal;

class Journal extends BaseJournal
{
    public function postSave(\PropelPDO $con = null){
       $this->getPublication()->save($con);
    }
}