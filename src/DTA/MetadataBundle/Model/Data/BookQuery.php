<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseBookQuery;

class BookQuery extends BaseBookQuery implements \DTA\MetadataBundle\Model\SQLSortable
{
    public static function sqlSort(\ModelCriteria $query, $direction = \ModelCriteria::ASC){
        return PublicationQuery::sqlSort($query->usePublicationQuery(), $direction)->endUse();
    }
}
