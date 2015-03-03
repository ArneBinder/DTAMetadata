<?php

namespace DTA\MetadataBundle\Model\Master;

use DTA\MetadataBundle\Model\Master\om\BaseDtaUserQuery;
use DTA\MetadataBundle\Model\ModelCriteria;
use DTA\MetadataBundle\Model\the;

class DtaUserQuery extends BaseDtaUserQuery implements \DTA\MetadataBundle\Model\SQLSortable
{

    /**
     * Adds a filtering clause to the database query that filters the entities by a given string.
     * @param DtaUserQuery $query
     * @param $filterString the string which the entities have to contain
     * @return ModelCriteria the modified query object that will return the filtered entities
     */
    /*public static function sqlFilter(\ModelCriteria $query, $filterString)
    {
        return $query->filterByUsername("$filterString*");
    }*/

    /**
     * Adds a sorting clause to the database query that orders the entities by a default order.
     * @param DtaUserQuery $query the query object that is used to retrieve the entities
     * @return the modified query object that will return entities in an ordered way
     */
    public static function sqlSort(\ModelCriteria $query, $direction)
    {
        return $query->orderByUsername($direction);
    }
}
