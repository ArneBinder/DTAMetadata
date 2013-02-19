<?php

namespace DTA\MetadataBundle\Model\table_row_view;

/**
 * 
 * @author carlwitt
 */
interface TableRowViewInterface {
   
    /* To specify which columns are to be visible in the user display (view all database records of a class as table)*/
    public function getTableViewColumnNames();
    
    /* To access the data using the specified column names */
    public function getAttributeByTableViewColumName($columnName);    
    
}

?>
