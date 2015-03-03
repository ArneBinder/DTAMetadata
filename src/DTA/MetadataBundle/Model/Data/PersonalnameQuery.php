<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePersonalnameQuery;

class PersonalnameQuery extends BasePersonalnameQuery implements \DTA\MetadataBundle\Model\SQLSortable
{
    public static function sqlSort(\ModelCriteria $query, $direction = \ModelCriteria::ASC) {
        /* @var $query PersonalnameQuery */
        
        return $query->useNamefragmentQuery()
                        ->filterByType(NamefragmentPeer::TYPE_LAST_NAME)
//                        ->orderByType(\ModelCriteria::DESC)
                        ->orderByName($direction)
                      ->endUse();
    }

    /**
     * Adds a filtering clause to the database query that filters the entities by a given string.
     * @param $filterString the string which the entities have to contain
     * @return the modified query object that will return the filtered entities
     */


    /**
     * Adds a filtering clause to the database query that filters the entities by a given string.
     * @param $filterString the string which the entities have to contain
     * @return ModelCriteria the modified query object that will return the filtered entities
     */
    /*public static function sqlFilter(\ModelCriteria $query, $filterString)
    {
        return NamefragmentQuery::sqlFilter($query->useNamefragmentQuery(),$filterString)->endUse();
    }*/
}
