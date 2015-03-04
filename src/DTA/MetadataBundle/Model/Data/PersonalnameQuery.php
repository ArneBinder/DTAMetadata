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
}
