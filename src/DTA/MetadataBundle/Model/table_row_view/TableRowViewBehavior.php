<?php

/**
 * This behavior automatically implements the TableRowViewInterface.
 * It helps the view component to display only those attributes that are relevant to the user.
 * What's relevant is manually specified in the dta_master_schema.xml using the table_row_view behavior tag.
 *
 * @author stud
 */
class TableRowViewBehavior extends Behavior {

    // default parameters value
    protected $parameters = array(
            // use column1, column2, column3 to identify the columns that shall be visible
    );

    /**
     * The strings that will be used in the table header to name the column.
     */
    public $captions = array();
    
    /**
     * Each caption is assigned an accessor string, that can be used to dynamically determine 
     * the value of an object for the given column.
     */
    public $accessors = array(
        // 'caption'=>'accessor',
    );
    
    
    /**
     * Extract local columns and embedded columns from the parameters.
     * @return array Two arrays containing the single column and embedded column parameters.
     *  array(
     *      'localColumns' => array(),
     *      'embeddedColumns' => array()
     *  )
     */
//    private function parseParameters() {
//
//        $table = $this->getTable();
//        
//        foreach ($this->getParameters() as $captionOrIndicator => $columnOrEntity) {
//
//            // check whether parameter name is an indicator to embed columns
//            if (!strncmp($captionOrIndicator, "embedColumns", strlen("embedColumns"))) {
//
//                $entity = $columnOrEntity;
//                
//                echo "gnu";
//                echo "$entity";
//                
//                // add all captions and accessors of the other entity
//                $otherTable = $table->getDatabase()->getTable($entity);
//                
//                if (!$otherTable)
//                    throw new InvalidArgumentException(sprintf(
//                                    'The entity \'%s\' referenced by the parameter \'%s\' in the table_row_view behavior of table \'%s\' doesn\'t exist. Please use the table name as specified by the schema <table> tag.', $entity, $captionOrIndicator, $table->getName()));
//                
//                $otherBehavior = $otherTable->getBehavior('table_row_view');
//                
//                if (!$otherBehavior)
//                    throw new InvalidArgumentException(sprintf(
//                                    'The entity \'%s\' referenced by the parameter \'%s\' in the table_row_view behavior of table \'%s\' doesn\'t implement the table_row_behavior. Please add it before proceeding.', $entity, $captionOrIndicator, $table->getName()));
//                
//                var_dump($otherBehavior->parameters);
//                
//            } else { // simple column taken from the entity itself
//               
//                $column = $columnOrEntity;
//                // check whether the column exists
//                if (!$table->containsColumn($column))
//                    throw new InvalidArgumentException(sprintf(
//                                    'The column \'%s\' referenced by the parameter \'%s\' in the table_row_view behavior of table \'%s\' doesn\'t exist. ', $column, $captionOrIndicator, $table->getName()));
//                
//                $caption = $captionOrIndicator;
//                $accessor = $table->getColumn($column)->getPhpName();
//                
//                $this->captions[] = $caption;
//                $this->accessors[$caption] = $accessor;
//                
//                var_dump($this->captions);
//                var_dump($this->accessors);
//            }
//        }//for each parameter
//    }
    
    /**
     * Extract local columns and embedded columns from the parameters.
     * Check their existance and whether target entities also implement the behavior.
     * @param type $builder
     * @return type
     */
    private function resolveParameters() {

        $table = $this->getTable();
        
        foreach ($this->getParameters() as $captionOrIndicator => $columnOrEntity) {

            // check whether parameter name is an indicator to embed columns
            if (!strncmp($captionOrIndicator, "embedColumns", strlen("embedColumns"))) {

                $entity = $columnOrEntity;
                
                echo "gnu";
                echo "$entity";
                
                // add all captions and accessors of the other entity
                $otherTable = $table->getDatabase()->getTable($entity);
                
                if (!$otherTable)
                    throw new InvalidArgumentException(sprintf(
                                    'The entity \'%s\' referenced by the parameter \'%s\' in the table_row_view behavior of table \'%s\' doesn\'t exist. Please use the table name as specified by the schema <table> tag.', $entity, $captionOrIndicator, $table->getName()));
                
                $otherBehavior = $otherTable->getBehavior('table_row_view');
                
                if (!$otherBehavior)
                    throw new InvalidArgumentException(sprintf(
                                    'The entity \'%s\' referenced by the parameter \'%s\' in the table_row_view behavior of table \'%s\' doesn\'t implement the table_row_behavior. Please add it before proceeding.', $entity, $captionOrIndicator, $table->getName()));
                
                var_dump($otherBehavior->parameters);
                
            } else { // simple column taken from the entity itself
               
                $column = $columnOrEntity;
                // check whether the column exists
                if (!$table->containsColumn($column))
                    throw new InvalidArgumentException(sprintf(
                                    'The column \'%s\' referenced by the parameter \'%s\' in the table_row_view behavior of table \'%s\' doesn\'t exist. ', $column, $captionOrIndicator, $table->getName()));
                
                $caption = $captionOrIndicator;
                $accessor = $table->getColumn($column)->getPhpName();
                
                $this->captions[] = $caption;
                $this->accessors[$caption] = $accessor;
                
                var_dump($this->captions);
                var_dump($this->accessors);
            }
        }//for each parameter
    }

    /**
     * Adds the $tableRowViewCaptions and $tableRowViewAccessors attributes to each object implementing the behavior.
     * Is called before objectMethods, so the results of the parseParameters call are available in the objectMethods function as well.
     */
    public function objectAttributes(){
        
        $this->resolveParameters();
        
        // captions will be available as $tableRowViewCaptions
        $captionsString = 'public $tableRowViewCaptions = array(';
        foreach ($this->captions as $caption) $captionsString .= "'$caption', ";
        $captionsString .= ");";
        
        // accessors will be available as $tableRowViewAccessors
        $accessorsString = 'public $tableRowViewAccessors = array(';
        foreach ($this->accessors as $caption => $accessor) $accessorsString .= "'$caption'=>'$accessor', ";
        $accessorsString .= ");";
        
        return $captionsString . "\r\t" . $accessorsString . "\r";
    }
    
    /**
     * Implements the functions defined by the TableRowViewInterface.
     */
    public function objectMethods() {
        return $this->renderTemplate('tableRowViewObjectMethods', array(
            'captions' => $this->captions,
            'accessors' => $this->accessors,
                ));
    }

    // add interview implementation declaration
    public function objectFilter(&$script) {
        $pattern = '/abstract class (\w+) extends (\w+) implements (\w+)/i';
        $replace = "// TableRowViewInterface automatically implemented by the TableRowViewBehavior.php\r" .
                'abstract class ${1} extends ${2}  implements ${3}, \DTA\MetadataBundle\Model\table_row_view\TableRowViewInterface';
        $script = preg_replace($pattern, $replace, $script);
    }

}

?>
