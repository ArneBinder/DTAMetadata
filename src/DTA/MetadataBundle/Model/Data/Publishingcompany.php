<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePublishingcompany;

class Publishingcompany extends BasePublishingcompany
{
    public function __toString(){
        return $this->getName();
    }
}
