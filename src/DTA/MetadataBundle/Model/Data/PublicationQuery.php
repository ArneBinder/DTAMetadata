<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePublicationQuery;

class PublicationQuery extends BasePublicationQuery implements \DTA\MetadataBundle\Model\SQLSortable
{
    public static function sqlSort(\ModelCriteria $query, $direction = \ModelCriteria::ASC){
        return TitleQuery::sqlSort($query->useTitleQuery(), $direction)->endUse();
    }
    
    
}
