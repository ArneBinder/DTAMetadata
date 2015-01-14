<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePersonQuery;

class PersonQuery extends BasePersonQuery implements \DTA\MetadataBundle\Model\SQLSortable, \DTA\MetadataBundle\Model\SQLFilterable
{
    public static function sqlSort(\ModelCriteria $query, $direction = \ModelCriteria::ASC){
        return PersonalnameQuery::sqlSort($query->usePersonalnameQuery(), $direction)->endUse();
    }

    public function orderByRepresentativePersonalname($direction){
        return PersonalnameQuery::sqlSort($this->usePersonalnameQuery(), $direction)->endUse();
    }

    /**
     * Adds a filtering clause to the database query that filters the entities by a given string.
     * @param $filterString the string which the entities have to contain
     * @return the modified query object that will return the filtered entities
     */
    public static function sqlFilter(\ModelCriteria $query, $filterString)
    {
        return PersonalnameQuery::sqlFilter($query->usePersonalnameQuery(),$filterString)->endUse();
    }
}
