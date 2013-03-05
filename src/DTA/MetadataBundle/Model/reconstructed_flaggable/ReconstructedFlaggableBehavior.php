<?php

/**
 * The reconstructed flaggable behavior adds an extra column to each column specified in its parameters.
 * Additionally, getters and setters are generated that allow polling the flaggable and flagged attributes of a database record.
 * 
 * !! IMPORTANT:
 * When specifying multiple columns, use parameter names in the form of
 *  <parameter name="column1" value="year"/>
 *  <parameter name="column2" value="title"/>
 * because multiple parameters with the same name are ignored, except for the last.
 *
 * The template code for the php methods to generate is located in templates/reconstructedFlaggableObjectMethods.php
 * 
 * @author Carl Witt
 */
class ReconstructedFlaggableBehavior extends Behavior {

    // to generate the name of the database column from the attribute
    public static $flagColumnSuffix = "_is_reconstructed";
    // to generate the name of the accessor functions
    public static $phpFlagColumnSuffix = "IsReconstructed";
    
    // default parameters value
    protected $parameters = array(
            // use column1, column2, column3 to identify the columns that shall be flaggable
    );

    /**
     * The PHP names of the columns of the base object which are now flaggable, i.e. for which a flag column has been created.
     */
    protected $flaggableColumns = array();
    
    /* Constructor-like method */
    public function modifyTable() {
        $table = $this->getTable();

        // multiple column can be specified as flaggable
        foreach ($this->getParameters() as $key => $forColumn) {

            // check whether the parameter starts with the "column" prefix
            if (!strncmp($key, "column", strlen("column"))) {

                // check whether the column that shall be flaggable exists
                if (!$table->containsColumn($forColumn))
                    throw new InvalidArgumentException(sprintf(
                                    'The column \'%s\' referenced by the parameter \'%s\' in the reconstructed_flaggable behavior of table \'%s\' doesn\'t exist. ', $forColumn, $key, $table->getName()));

                // generate the additional column name
                $flagColumn = $forColumn . ReconstructedFlaggableBehavior::$flagColumnSuffix;
                
                // check whether the user has defined an own, customized column for this, otherwise create it
                if (!$table->containsColumn($flagColumn))  // this refers to the table as defined in the schema, not in the DB
                    $table->addColumn(array(
                        'name' => $flagColumn,
                        'type' => "BOOLEAN",
                        'size' => "1",
                        'required' => "false",
                        'defaultValue' => "false"
                    ));
                
                // remember that this column is now flaggable
                $this->flaggableColumns[] = $table->getColumn($forColumn)->getPhpName();
                
            }//if key starts with column
        }//for each parameter
    }
    
    public function objectMethods($builder) {
        return $this->renderTemplate('reconstructedFlaggableObjectMethods', array(
            'flaggableColumns' => $this->flaggableColumns,
            'flagColumnSuffix' => ReconstructedFlaggableBehavior::$flagColumnSuffix,
            'phpFlagColumnSuffix' => ReconstructedFlaggableBehavior::$phpFlagColumnSuffix,
        ));
    }
    
    // add interface implementation declaration
    public function objectFilter(&$script) {
        $pattern = '/abstract class (\w+) extends (\w+) implements (\w+)/i';
        $replace = 'abstract class ${1} extends ${2} implements ${3}, \DTA\MetadataBundle\Model\reconstructed_flaggable\ReconstructedFlaggableInterface';
        $script = preg_replace($pattern, $replace, $script);
    }


}

?>
