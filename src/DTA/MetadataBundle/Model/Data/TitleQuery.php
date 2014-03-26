<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseTitleQuery;

class TitleQuery extends BaseTitleQuery implements \DTA\MetadataBundle\Model\SQLSortable
{
    public static function sqlSort(\ModelCriteria $query, $direction = \ModelCriteria::ASC){
        return $query->useTitlefragmentQuery()
                        ->filterByType(TitlefragmentPeer::TYPE_MAIN_TITLE)
//                        ->orderByType()
                        ->orderByName($direction)
                      ->endUse()
                ->orderById()
                ;
    }
    
}
