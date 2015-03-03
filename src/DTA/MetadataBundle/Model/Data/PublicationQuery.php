<?php

namespace DTA\MetadataBundle\Model\Data;

use DTA\MetadataBundle\Model\Data\om\BasePublicationQuery;
use DTA\MetadataBundle\Model\ModelCriteria;
use Exception;
use ReflectionMethod;

class PublicationQuery extends BasePublicationQuery implements \DTA\MetadataBundle\Model\SQLSortable, \DTA\MetadataBundle\Model\SQLFilterable
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

    public function filterByTitleStringString($filterString = null, $comparison = null){
        return TitleQuery::sqlFilter($this->useTitleQuery(), $filterString)->endUse();
    }

    public function filterByFirstAuthorNameString($filterString = null, $comparison = null){
        return PersonQuery::sqlFilter($this->usePersonPublicationQuery()->usePersonQuery(),$filterString)->endUse()->endUse();
    }

    // adding "String" to the function name prevents collision with propel filter method
    public function filterByDatespecificationRelatedByPublicationdateIdString($filterString = null, $comparison = null){
        return DatespecificationQuery::sqlFilter($this->useDatespecificationRelatedByPublicationdateIdQuery(), $filterString)->endUse();
    }


    /**
     * Adds a filtering clause to the database query that filters the entities by a given string.
     * @param $filterString the string which the entities have to contain
     * @return ModelCriteria the modified query object that will return the filtered entities
     */
    public static function sqlFilter(\ModelCriteria $query, $filterString)
    {
        // get all methods which start with 'filterBy' and end with 'String'
        $filterMethods = array();
        ob_start();
        foreach(get_class_methods(new PublicationQuery) as $possibleFilterMethod){
            if(!substr_compare($possibleFilterMethod,'filterBy', 0, strlen('filterBy') ) and !substr_compare($possibleFilterMethod,'String',-strlen('String'),strlen('String'))){
                $functionReflection = new ReflectionMethod(new PublicationQuery, $possibleFilterMethod);
                $parameters = $functionReflection->getParameters();
                if(count($parameters)==1 ){
                    //$rp = new \ReflectionParameter(array(new PublicationQuery, $possibleFilterMethod),$($parameters[0]->name));
                    //var_dump($rp->getClass()->getName());
                    //var_dump($rp);
                    $filterMethods[] = $possibleFilterMethod;
                }
            }
        }

        //throw new Exception(ob_get_clean());

        if(!empty($filterMethods)){
            $firstMethod = array_pop($filterMethods);
            $query = $query->$firstMethod($filterString);
        }
        foreach($filterMethods as $filterMethod){
            $query = $query->_or()->$filterMethod($filterString);
        }
        return $query;
    }
}
