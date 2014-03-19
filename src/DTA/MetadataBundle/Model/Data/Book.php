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
}
