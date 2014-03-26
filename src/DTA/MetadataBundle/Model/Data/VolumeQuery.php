<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseVolumeQuery;

class VolumeQuery extends BaseVolumeQuery implements \DTA\MetadataBundle\Model\SQLSortable
{
    public static function sqlSort(\ModelCriteria $query, $direction = \ModelCriteria::ASC){
        return PublicationQuery::sqlSort($query->usePublicationQuery(), $direction)->endUse();
    }
}
