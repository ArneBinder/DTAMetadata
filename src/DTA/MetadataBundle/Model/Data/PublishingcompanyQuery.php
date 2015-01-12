<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePublishingcompanyQuery;
use DTA\MetadataBundle\Model\the;

class PublishingcompanyQuery extends BasePublishingcompanyQuery implements \DTA\MetadataBundle\Model\SQLSortable
{
    /**
     * Adds a sorting clause to the database query that orders the entities by a default order.
     * @param $query the query object that is used to retrieve the entities
     * @return the modified query object that will return entities in an ordered way
     */
    public static function sqlSort(\ModelCriteria $query, $direction)
    {
        return $query->orderByName($direction);
    }

}
