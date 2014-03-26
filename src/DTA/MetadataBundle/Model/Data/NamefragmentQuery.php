<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseNamefragmentQuery;

class NamefragmentQuery extends BaseNamefragmentQuery implements \DTA\MetadataBundle\Model\SQLSortable
{
    public static function sqlSort(\ModelCriteria $query, $direction = \ModelCriteria::ASC){
        return $query->orderByType($direction)
                     ->orderByName($direction);
    }
}
