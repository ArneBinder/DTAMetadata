<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePublicationQuery;
use DTA\MetadataBundle\Model\ModelCriteria;
use Exception;
use ReflectionMethod;

class PublicationQuery extends BasePublicationQuery
{

    /** Publication/getFirstAuthorName()
    // TODO first person publication might not be the first author
     * $personPublications = $this->getPersonPublications();
    if(count($personPublications) == 0 ) return NULL;
    $firstPersonPublication = $personPublications[0];
    $personalNames = $firstPersonPublication->getPerson()->getPersonalnames();
    if(count($personalNames) == 0 ) return NULL;
    return $personalNames[0];
     */

     /*
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
 */

      public function orderByFirstAuthorName($direction){
        return $this->usePersonPublicationQuery()->usePersonQuery()->sqlSort($direction)->endUse()->endUse();
    }

    public function orderByTitleString($direction){
        return $this->useTitleQuery()->sqlSort($direction)->endUse();
    }

    public function orderByDatespecificationRelatedByPublicationdateId($direction){
        return $this->useDatespecificationRelatedByPublicationdateIdQuery()->sqlSort($direction)->endUse();
    }

    public function orderByPublishingCompany($direction){
        return $this->usePublishingcompanyQuery()->sqlSort($direction)->endUse();
    }

    public function orderByPlace($direction){
        return $this->usePlaceQuery()->sqlSort($direction)->endUse();
    }


}
