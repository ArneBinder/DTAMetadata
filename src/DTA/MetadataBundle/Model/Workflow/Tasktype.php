<?php

namespace DTA\MetadataBundle\Model\Workflow;

use DTA\MetadataBundle\Model\Workflow\om\BaseTasktype;

class Tasktype extends BaseTasktype
{
    public function save(\PropelPDO $con = NULL){
        
        if($this->isInTree()){
            $parent = $this->getParent();
            if( $parent !== null && ! $parent->getChildren()->contains($this) ){
                $this->moveToLastChildOf($this->getParent());
            }
        } else {
            $this->insertAsLastChildOf($this->getParent());
        }
        
        parent::save();
    }
    
    public function __toString(){
        return $this->getName();
    }
}