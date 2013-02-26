/** 
 * To specify which columns are to be visible in the user display 
 * (In the view that lists all database records of a class as a table) 
 */
public static function getTableViewColumnNames(){
    $rc = new \ReflectionClass(get_called_class());
    return $rc->getStaticPropertyValue("tableRowViewCaptions");
}

/** 
 * To access the data using the specified column names.
 * @param string columnName 
 */
public function getAttributeByTableViewColumName($columnName){
    $accessor = $this->accessors[$columnName];
    return $this->$accessor;
}    


