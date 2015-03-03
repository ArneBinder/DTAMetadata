<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePersonQuery;

class PersonQuery extends BasePersonQuery implements \DTA\MetadataBundle\Model\SQLSortable
{
    public static function sqlSort(\ModelCriteria $query, $direction = \ModelCriteria::ASC){
        return PersonalnameQuery::sqlSort($query->usePersonalnameQuery(), $direction)->endUse();
    }

    public function orderByRepresentativePersonalname($direction){
        return PersonalnameQuery::sqlSort($this->usePersonalnameQuery(), $direction)->endUse();
    }

}
