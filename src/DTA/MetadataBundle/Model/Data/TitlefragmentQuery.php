<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseTitlefragmentQuery;
use DTA\MetadataBundle\Model\ModelCriteria;
use DTA\MetadataBundle\Model\the;

class TitlefragmentQuery extends BaseTitlefragmentQuery implements \DTA\MetadataBundle\Model\SQLFilterable
{
//    public static function sort(\ModelCriteria $query, $direction = \ModelCriteria::ASC){
//        return $query
//                     ->orderByName($direction)
//                ->orderByType()
//                        ;
//    }
    /**
     * Adds a filtering clause to the database query that filters the entities by a given string.
     * @param $filterString the string which the entities have to contain
     * @return ModelCriteria the modified query object that will return the filtered entities
     */
    public static function sqlFilter(\ModelCriteria $query, $filterString)
    {
        return $query->filterByName($filterString);
    }
}
