<?php

namespace DTA\MetadataBundle\Model\table_row_view;

/**
 * 
 * @author carlwitt
 */
interface TableRowViewInterface {
   
    /* To specify which columns are to be visible in the user display (view all database records of a class as table)*/
    public static function getTableViewColumnNames();
    
    /* To access the data using the specified column names */
    public function getAttributeByTableViewColumName($columnName);    
    
    /* Returns the propel query object to retrieve the records. */
    public static function getRowViewQueryObject();

    /* Returns the order function name for the specified column or null if it doesn't exist. */
    public static function getRowViewOrderFunctionName($columnName);

    /* Returns the order column indices regarding TableViewColumnNames */
    public static function getRowViewOrderColumnIndices();
}

?>
