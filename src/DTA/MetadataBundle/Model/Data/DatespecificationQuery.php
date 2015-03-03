<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BaseDatespecificationQuery;

class DatespecificationQuery extends BaseDatespecificationQuery  implements \DTA\MetadataBundle\Model\SQLSortable
{
    public static function sqlSort(\ModelCriteria $query, $direction = \ModelCriteria::ASC){
        return $query->orderByYear($direction);
    }

    /**
     * Adds a filtering clause to the database query that filters the entities by a given string.
     * @param $filterString the string which the entities have to contain
     * @return ModelCriteria the modified query object that will return the filtered entities
     */
    /*public static function sqlFilter(\ModelCriteria $query, $filterString)
    {
        if(ctype_digit($filterString)) {
            if(strlen($filterString)<4){
                return $query->filterByYear(array(intval(str_pad($filterString,4,'0')),intval(str_pad($filterString,4,'9'))));
            }
            return $query->filterByYear(intval($filterString));
        }else{
            return $query;
        }
        //return $query->addUsingAlias(DatespecificationPeer::YEAR, $year, $comparison);
        //return $query->where($query->getAliasedColName(DatespecificationPeer::YEAR).' LIKE ?', intval($filterString));
        //$this->getAliasedColName(DatespecificationPeer::YEAR)
    }*/
}
