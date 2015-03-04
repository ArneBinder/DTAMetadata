<?php

namespace DTA\MetadataBundle\Model\Workflow;

use DTA\MetadataBundle\Model\Data\PublicationQuery;
use DTA\MetadataBundle\Model\Workflow\om\BaseTaskQuery;
use DTA\MetadataBundle\Model\Master\DtaUserQuery;

class TaskQuery extends BaseTaskQuery
{
    public function orderByResponsibleUser($direction){
        return $this->useDtaUserQuery()->sqlSort($direction)->endUse();
    }

    public function orderByReferee($direction){
        //TODO: add PublicationGroups!!
        return $this->usePublicationQuery()->sqlSort($direction)->endUse();
    }
}
