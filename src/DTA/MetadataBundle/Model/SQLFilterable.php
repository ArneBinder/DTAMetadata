<?php

namespace DTA\MetadataBundle\Model;

/** Entities implementing this interface can be filtered by modifying the SQL query used to retrieve them. */
interface SQLFilterable{
 
    /**
     * Adds a filtering clause to the database query that filters the entities by a given string.
     * @param $filterString the string which the entities have to contain
     * @return ModelCriteria the modified query object that will return the filtered entities
     */
    public static function sqlFilter(\ModelCriteria $query, $filterString);
            
}