<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseTitleQuery;

class TitleQuery extends BaseTitleQuery implements \DTA\MetadataBundle\Model\SQLSortable, \DTA\MetadataBundle\Model\SQLFilterable
{
    public static function sqlSort(\ModelCriteria $query, $direction = \ModelCriteria::ASC){
        return $query->useTitlefragmentQuery()
                        ->filterByType(TitlefragmentPeer::TYPE_MAIN_TITLE)
//                        ->orderByType()
                        ->orderByName($direction)
                      ->endUse()
                ->orderById()
                ;
    }


    /**
     * Adds a filtering clause to the database query that filters the entities by a given string.
     * @param $filterString the string which the entities have to contain
     * @return ModelCriteria the modified query object that will return the filtered entities
     */
    public static function sqlFilter(\ModelCriteria $query, $filterString)
    {
       return TitlefragmentQuery::sqlFilter($query->useTitlefragmentQuery(),$filterString)->endUse();
    }
}
