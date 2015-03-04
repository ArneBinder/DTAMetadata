<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePersonQuery;

class PersonQuery extends BasePersonQuery
{/*
    public function orderByRepresentativePersonalname($direction){
        return PersonalnameQuery::sqlSort($this->usePersonalnameQuery(), $direction)->endUse();
    }
    
    */
      public function orderByRepresentativePersonalname($direction){
        return $this->usePersonalnameQuery()->sqlSort($direction)->endUse();
    }



}
