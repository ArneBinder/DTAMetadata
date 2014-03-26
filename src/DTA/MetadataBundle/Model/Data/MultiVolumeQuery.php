<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseMultiVolumeQuery;

class MultiVolumeQuery extends BaseMultiVolumeQuery implements \DTA\MetadataBundle\Model\SQLSortable
{
    public static function sqlSort(\ModelCriteria $query, $direction = \ModelCriteria::ASC){
        return PublicationQuery::sqlSort($query->usePublicationQuery(), $direction)->endUse();
    }
}
