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
        $result = $this->getByName($accessor, \BasePeer::TYPE_PHPNAME);
        if( is_a($result, 'DateTime') )
            $result = $result->format('d/m/Y');
        return $result;
    }
}

/** 
 * @return The propel query object for retrieving the records.
 */
public static function getRowViewQueryObject(){
    $rc = new \ReflectionClass(get_called_class());
    $queryConstructionString = $rc->getStaticPropertyValue("queryConstructionString");
    if($queryConstructionString === NULL){
        $classShortName = $rc->getShortName();
        $package = \DTA\MetadataBundle\Controller\ORMController::getPackageName($rc->getName());
        $queryClass = \DTA\MetadataBundle\Controller\ORMController::relatedClassNames($package, $classShortName)['query'];
        return new $queryClass;
    } else {
        return eval('return '.$queryConstructionString);
    }
}

<?php
    foreach($representativeGetterFunctions as $rgf)
        echo $rgf;
    
    foreach($embeddedGetterFunctions as $egf)
        echo $egf;
    
?>
