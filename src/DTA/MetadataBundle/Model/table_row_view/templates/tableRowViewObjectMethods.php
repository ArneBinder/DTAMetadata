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
    
    $accessor = $this->tableRowViewAccessors[$columnName];

    // don't use propel standard getters for user defined accessors
    // or for representative selector functions 
    if(!strncmp($accessor, "accessor:", strlen("accessor:"))){
        $accessor = substr($accessor, strlen("accessor:"));
        return call_user_func(array($this, $accessor));
    } else {
        return $this->getByName($accessor, \BasePeer::TYPE_PHPNAME);
    }
}    

<?php
    foreach($representativeGetterFunctions as $rgf)
        echo $rgf;
    
    foreach($embeddedGetterFunctions as $egf)
        echo $egf;
    
?>
