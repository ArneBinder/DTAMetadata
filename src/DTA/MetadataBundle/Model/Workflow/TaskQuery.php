<?php

namespace DTA\MetadataBundle\Model\Workflow;

use DTA\MetadataBundle\Model\Data\PublicationQuery;
use DTA\MetadataBundle\Model\Workflow\om\BaseTaskQuery;
use DTA\MetadataBundle\Model\Master\DtaUserQuery;

class TaskQuery extends BaseTaskQuery
{
    public function orderByResponsibleUser($direction){
        return DtaUserQuery::sqlSort($this->useDtaUserQuery(), $direction)->endUse();
    }

    public function filterByResponsibleUserString($filterString){
        return DtaUserQuery::sqlFilter($this->useDtaUserQuery(), $filterString)->endUse();
    }

    public function orderByReferee($direction){
        //TODO: add PublicationGroups!!
        return PublicationQuery::sqlSort($this->usePublicationQuery(), $direction)->endUse();
    }

    public function filterByRefereeString($filterString){
        //TODO: add PublicationGroups!!
        return PublicationQuery::sqlFilter($this->usePublicationQuery(), $filterString)->endUse();
        //return PublicationQuery::sqlFilter($this->usePublicationgroupQuery()->usePublicationPublicationgroupQuery()->usePublicationQuery(), $filterString)->endUse()->endUse();
    }

}
