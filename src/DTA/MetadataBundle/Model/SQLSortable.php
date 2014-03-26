<?php

namespace DTA\MetadataBundle\Model;

/** Entities implementing this interface can be brought into order by modifying the SQL query used to retrieve them. */
interface SQLSortable{
 
    /**
     * Adds a sorting clause to the database query that orders the entities by a default order.
     * @param $query the query object that is used to retrieve the entities
     * @return the modified query object that will return entities in an ordered way
     */
    public static function sqlSort(\ModelCriteria $query, $direction);
            
}