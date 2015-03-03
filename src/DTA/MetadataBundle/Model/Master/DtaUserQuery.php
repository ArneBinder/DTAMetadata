<?php

namespace DTA\MetadataBundle\Model\Master;

use DTA\MetadataBundle\Model\Master\om\BaseDtaUserQuery;
use DTA\MetadataBundle\Model\ModelCriteria;
use DTA\MetadataBundle\Model\the;

class DtaUserQuery extends BaseDtaUserQuery implements \DTA\MetadataBundle\Model\SQLSortable
{
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
