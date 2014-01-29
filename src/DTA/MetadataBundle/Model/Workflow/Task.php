<?php

namespace DTA\MetadataBundle\Model\Workflow;

use DTA\MetadataBundle\Model\Workflow\om\BaseTask;

class Task extends BaseTask
{
    /**
     * @return Either the publication or the publication group for which the task was created. 
     */
    public function getReferee(){
        
        if($this->getPublication() === NULL){
            return "Publikationsgruppe: ".$this->getPublicationgroup()->getName();
        } else {
            return $this->getPublication()->getTitle();
        }
            
    }
    
    public function getResponsibleUser(){
        $userId = $this->getResponsibleuserId();
        return \DTA\MetadataBundle\Model\Master\DtaUserQuery::create()
                ->findOneById($userId);
    }
    
}
