<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePublicationQuery;

class PublicationQuery extends BasePublicationQuery implements \DTA\MetadataBundle\Model\SQLSortable
{
    public static function sqlSort(\ModelCriteria $query, $direction = \ModelCriteria::ASC){
        return TitleQuery::sqlSort($query->useTitleQuery(), $direction)->endUse();
    }
    /** Publication/getFirstAuthorName()
    // TODO first person publication might not be the first author
     * $personPublications = $this->getPersonPublications();
    if(count($personPublications) == 0 ) return NULL;
    $firstPersonPublication = $personPublications[0];
    $personalNames = $firstPersonPublication->getPerson()->getPersonalnames();
    if(count($personalNames) == 0 ) return NULL;
    return $personalNames[0];
     */
    public function orderByFirstAuthorName($direction){
        return PersonQuery::sqlSort($this->usePersonPublicationQuery()->usePersonQuery(),$direction)->endUse()->endUse();
    }

    public function orderByTitleString($direction){
        return TitleQuery::sqlSort($this->useTitleQuery(), $direction)->endUse();
    }

    public function orderByDatespecificationRelatedByPublicationdateId($direction){
        return DatespecificationQuery::sqlSort($this->useDatespecificationRelatedByPublicationdateIdQuery(), $direction)->endUse();
    }

    public function orderByPublishingCompany($direction){
        return PublishingcompanyQuery::sqlSort($this->usePublishingcompanyQuery(), $direction)->endUse();
    }

    public function orderByPlace($direction){
        return PlaceQuery::sqlSort($this->usePlaceQuery(), $direction)->endUse();
    }

    public function filterByTitleString($filterString){
        return TitleQuery::sqlFilter($this->useTitleQuery(), $filterString)->endUse();
    }

    public function filterByFirstAuthorName($direction){
        return PersonQuery::sqlFilter($this->usePersonPublicationQuery()->usePersonQuery(),$direction)->endUse()->endUse();
    }

    // adding "String" to the function name prevents collision with propel filter method
    public function filterByDatespecificationRelatedByPublicationdateIdString($direction){
        return DatespecificationQuery::sqlFilter($this->useDatespecificationRelatedByPublicationdateIdQuery(), $direction)->endUse();
    }



}
