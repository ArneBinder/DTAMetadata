<?php

/**
 * Description of ReconstructedFlaggableBehavior
 *
 * @author stud
 */
class ReconstructedFlaggableBehavior extends Behavior {

    public static $flagColumnSuffix = "_is_reconstructed";
    public static $phpFlagColumnSuffix = "IsReconstructed";
    
    // default parameters value
    protected $parameters = array(
            // use column1, column2, column3 to identify the columns that shall be flaggable
    );

    /**
     * The PHP names of the columns of the base object which are now flaggable, i.e. for which a flag column has been created.
     */
    protected $flaggableColumns = array();
    
    public function objectMethods($builder) {
        return $this->renderTemplate('reconstructedFlaggableObjectMethods', array(
            'flaggableColumns' => $this->flaggableColumns,
            'flagColumnSuffix' => ReconstructedFlaggableBehavior::$flagColumnSuffix,
            'phpFlagColumnSuffix' => ReconstructedFlaggableBehavior::$phpFlagColumnSuffix,
        ));
    }

    public function modifyTable() {
        $table = $this->getTable();

        // multiple column can be specified as flaggable
        foreach ($this->getParameters() as $key => $forColumn) {

            // check whether the parameter starts with the "column" prefix
            if (!strncmp($key, "column", strlen("column"))) {

                // check whether the column that shall be flaggable as reconstructed exists
                if (!$table->containsColumn($forColumn))
                    throw new InvalidArgumentException(sprintf(
                                    'The column \'%s\' referenced by the parameter \'%s\' in the reconstructed_flaggable behavior of table \'%s\' doesn\'t exist. ', $forColumn, $key, $table->getName()));

                // create the flag column name
                $flagColumn = $forColumn . ReconstructedFlaggableBehavior::$flagColumnSuffix;
                
                // check whether the user has defined a customized column for this, otherwise create it
                if (!$table->containsColumn($flagColumn))  // this refers to the table as defined in the schema, not in the DB
                    $table->addColumn(array(
                        'name' => $flagColumn,
                        'type' => "BOOLEAN",
                        'size' => "1",
                        'required' => "false",
                        'defaultValue' => "false"
                    ));
                
                // remember that this column is now flaggable (at least now)
                $this->flaggableColumns[] = $table->getColumn($forColumn)->getPhpName();
                
            }//if key starts with column
        }//for each parameter
    }

}

?>
