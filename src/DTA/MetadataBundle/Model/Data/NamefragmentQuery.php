<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseNamefragmentQuery;

class NamefragmentQuery extends BaseNamefragmentQuery implements \DTA\MetadataBundle\Model\SQLSortable, \DTA\MetadataBundle\Model\SQLFilterable
{
    public static function sqlSort(\ModelCriteria $query, $direction = \ModelCriteria::ASC){
        return $query->orderByType($direction)
                     ->orderByName($direction);
    }

    /**
     * Adds a filtering clause to the database query that filters the entities by a given string.
     * @param $filterString the string which the entities have to contain
     * @return the modified query object that will return the filtered entities
     */
    public static function sqlFilter(\ModelCriteria $query, $filterString)
    {
        return $query->filterByName($filterString);
        //return $this->where('Name = ?', $filterString);
    }
}
