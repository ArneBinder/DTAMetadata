/**
* Returns all columns that can be flagged as reconstructed.
*/
public function getReconstructedFlaggableColumns(){
    return array(<?php foreach ($flaggableColumns as $column) echo "'$column', "; ?>);
}

/**
* Returns whether a column is flagged as reconstructed.
* @param $column the PHP name of the column as defined in the schema
*/
public function isReconstructedByName($column){
    return $this->getByName($column."<?php echo $phpFlagColumnSuffix ?>");
}

/**
* Returns the marked column value, e.g. in brackets to denote that the value is reconstructed.
* e.g. getMarkedByName('name') return '[<name>]'.
* @param $column the PHP name of the column as defined in the schema
*/
public function getMarkedByName($column){
    if($this->isReconstructedByName($column))
        return '[' . $this->getByName($column) . ']';
    else
        return $this->getByName($column);
}

/**
* Returns all columns that are flagged as reconstructed.
*/
public function getReconstructedFlaggedColumns(){
    $flaggableColumns = $this->getReconstructedFlaggableColumns();
    $flaggedColumns = array();
    foreach($flaggableColumns as $column){
        if($this->isReconstructedByName($column))
        $flaggedColumns[] = $column;
    }
    return $flaggedColumns;
}