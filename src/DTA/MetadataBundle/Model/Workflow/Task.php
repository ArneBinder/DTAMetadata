<?php

namespace DTA\MetadataBundle\Model\Workflow;

use DTA\MetadataBundle\Model\Workflow\om\BaseTask;

class Task extends BaseTask
{
    /**
     * @return Either the publication or the publication group for which the task was created. 
     */
    public function getReferee(){
        
        $referee = $this->getPublication();
        if($referee === NULL)
            $referee = $this->getPublicationgroup();
        return $referee;
    }
    
    public function getResponsibleUser(){
        $userId = $this->getResponsibleuserId();
        return \DTA\MetadataBundle\Model\Master\DtaUserQuery::create()
                ->findOneById($userId);
    }
    
}
