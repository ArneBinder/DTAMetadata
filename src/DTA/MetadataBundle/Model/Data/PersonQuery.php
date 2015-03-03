<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePersonQuery;

class PersonQuery extends BasePersonQuery implements \DTA\MetadataBundle\Model\SQLSortable
{
    public static function sqlSort(\ModelCriteria $query, $direction = \ModelCriteria::ASC){
        return PersonalnameQuery::sqlSort($query->usePersonalnameQuery(), $direction)->endUse();
    }

    public function orderByRepresentativePersonalname($direction){
        return PersonalnameQuery::sqlSort($this->usePersonalnameQuery(), $direction)->endUse();
    }

   /* public function filterByRepresentativePersonalnameString($filterString){
        return PersonalnameQuery::sqlFilter($this->usePersonalnameQuery(), $filterString)->endUse();
    }*/

    //add filter keyword expansion
    /*public function filterByGnd($gnd = null, $comparison = null){
        if($gnd !== null and substr($gnd,-1)!=="%"){
            return parent::filterByGnd($gnd."%",$comparison);
        }else{
            return parent::filterByGnd($gnd,$comparison);
        }
    }*/
    /**
     * Adds a filtering clause to the database query that filters the entities by a given string.
     * @param $filterString the string which the entities have to contain
     * @return the modified query object that will return the filtered entities
     */
    /*public static function sqlFilter(\ModelCriteria $query, $filterString)
    {
        return PersonalnameQuery::sqlFilter($query->usePersonalnameQuery(),$filterString)->endUse();
    }*/
}
