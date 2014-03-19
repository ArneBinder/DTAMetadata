<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseArticle;

class Article extends BaseArticle
{
    public function postSave(\PropelPDO $con = null){
       $this->getPublication()->save($con);
    }
}
