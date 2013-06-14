<?php

namespace DTA\MetadataBundle\Model;

use DTA\MetadataBundle\Model\om\BasePublication;

class Publication extends BasePublication
{
    /**
     * Used in the select or add control to add works on the fly.
     * @return string
     */
    public function getSelectBoxString(){
        return $this->getWork()->getTitle();
    }
}
