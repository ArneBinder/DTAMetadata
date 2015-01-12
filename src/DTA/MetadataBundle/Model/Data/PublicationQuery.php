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
    public static function orderByFirstAuthorName(PublicationQuery $query, $direction){
        return PersonQuery::sqlSort($query->usePersonPublicationQuery()->usePersonQuery(),$direction)->endUse()->endUse();
    }

    public static function orderByTitleString(PublicationQuery $query, $direction){
        return TitleQuery::sqlSort($query->useTitleQuery(), $direction)->endUse();
    }

    public static function orderByDatespecificationRelatedByPublicationdateId(PublicationQuery $query, $direction){
        return DatespecificationQuery::sqlSort($query->useDatespecificationRelatedByPublicationdateIdQuery(), $direction)->endUse();
    }

    public static function orderByPublishingCompany(PublicationQuery $query, $direction){
        return PublishingcompanyQuery::sqlSort($query->usePublishingcompanyQuery(), $direction)->endUse();
    }

    public static function orderByPlace(PublicationQuery $query, $direction){
        return PlaceQuery::sqlSort($query->usePlaceQuery(), $direction)->endUse();
    }

}
