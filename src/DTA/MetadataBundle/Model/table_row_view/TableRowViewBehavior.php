<?php

/**
 * This behavior automatically implements the TableRowViewInterface for the generated base classes
 * like BaseAuthor, BasePublication, etc. in Model/om.
 * It helps the view component to display only those attributes that are relevant to the user.
 * What's relevant is manually specified in the dta_master_schema.xml using the table_row_view behavior tag.
 *
 * The behavior supports the following functionality
 * 
 * 1. Simple projection on / selection of local columns
 * 
 *  <parameter name="<columnHeadline>" value="<columnName>"/>
 * 
 * Select a column of the table for display under caption columnHeadline.
 * Replace <columnName> for any of the name attributes of the columns defined in the table, as for instance
 * 
 *  <column name="person_id" type="INTEGER" required="true"/>
 * 
 * Can be selected using <parameter name="Interne Personennummer" value="person_id"/>
 * 
 * 2. Embedding the columns of one-to-one related entity
 * 
 * <parameter name="embedColumns<*>" value="<tableName>" />
 * 
 * Where <*> is an arbitrary string used to create different parameter names, because parameters 
 * with the same names are listed only once. 
 * Replace <tableName> for the value of the name attribute of any other table the entity is related to 
 * (this is independent of the obligation to make the relation explicit using a foreign key).
 * 
 * 
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
     * Creates the behavior by (possibly recursively) parsing the parameters 
     * and generating columns and accessors.
     */
    public function build(){
        // default behavior: if no parameters are defined add all columns
        if( count($this->getParameters()) == 0 ){
            $c = new Column();
            foreach( $this->getTable()->getColumns() as $column){
                $this->addParameter(array('name'=>$column->getPhpName(), 'value'=>$column->getName()));
            }
        }
        TableRowViewBehavior::resolveParameters($this);
    }
    
    /**
     * Adds a column to the table view. 
     * @param string $caption The label of the column as displayed in <th> tags
     * @param string $accessor The accessor string as used by the view to get the value of the current entity (row) for that column
     */
    public function addViewElement($caption, $accessor){
        $this->captions[] = $caption;
        $this->accessors[$caption] = $accessor;
    }
    
    /**
     * Looks up and adds the columns designated for view in another table and adds
     * all of them to the local colum list 
     * @param string $tableName Another table (also implementing the table_row_view behavior) to embed the columns of.
     * @throws InvalidArgumentException if referenced table doesn't exist, doesn't implement table_row_view behavior or is the referencing table
     */
    private function resolveEmbeddedColumns($tableName) {

        // table on which the behavior is currently processed
        $table = $this->getTable();

        // check other entity
        $relatedEntity = $table->getDatabase()->getTable($tableName);
        if (!$relatedEntity)
            throw new InvalidArgumentException(sprintf(
                            'The entity \'%s\' referenced by the parameter \'%s\' in the table_row_view behavior of table \'%s\' doesn\'t exist. Please use the table name as specified by the schema <table> tag.', $tableName, $captionOrIndicator, $table->getName()));

        // prevent recursion
        if ($table->getName() === $relatedEntity->getName())
            throw new InvalidArgumentException(sprintf(
                            'No recursion allowed in the table_row_view behavior (table \'%s\').', $table->getName()));

        // related entity must implement table_row_view behavior
        $otherBehavior = $relatedEntity->getBehavior('table_row_view');
        if (!$otherBehavior)
            throw new InvalidArgumentException(sprintf(
                            'The entity \'%s\' referenced by the parameter \'%s\' in the table_row_view behavior of table \'%s\' doesn\'t implement the table_row_behavior. Please add it before proceeding.', $tableName, $captionOrIndicator, $table->getName()));

        // build the behavior (e.g. parse embed columns parameters recursively) 
        $otherBehavior->build();
        
        // add all columns to the local view
        foreach($otherBehavior->accessors as $caption => $accessor){
            $this->addViewElement($caption, $accessor);
        }
        
    }

    /**
     * @param string $name The value of the name attribute of the column tag that shall be visible in the table view.
     * @throws InvalidArgumentException 
     */
    private function resolveAtomicColumn($caption, $column) {
        
        // table on which the behavior is currently processed
        $table = $this->getTable();
        
        // check whether the column exists
        if (!$table->containsColumn($column))
            throw new InvalidArgumentException(sprintf(
                            'The column \'%s\' referenced by the parameter \'%s\' in the table_row_view behavior of table \'%s\' doesn\'t exist. ', $column, $caption, $table->getName()));

        $caption = $caption;
        $accessor = $table->getColumn($column)->getPhpName();

        $this->addViewElement($caption, $accessor);
    }

    /**
     * Extract local columns and embedded columns from the parameters.
     * Check their existance and whether target entities also implement the behavior.
     * @param TableRowViewBehavior $behavior
     * @return TableRowViewBehavior the modified behavior (could also be passed by reference, but this might be clearer)
     */
    private static function resolveParameters(TableRowViewBehavior &$behavior) {

        if( $behavior->getTable()->getAttribute("isCrossRef") )
            return;
        
        foreach ($behavior->getParameters() as $captionOrIndicator => $columnOrEntity) {

            // check whether parameter name is an indicator to embed columns
            if (!strncmp($captionOrIndicator, "embedColumns", strlen("embedColumns"))) {

                $entity = $columnOrEntity;
                $behavior->resolveEmbeddedColumns($entity);
                
            } else { // simple column taken from the entity itself
                
                $column = $columnOrEntity;
                $caption = $captionOrIndicator;
                $behavior->resolveAtomicColumn($caption, $column);
                
            }
        }
        
//        passed by reference
//        return $behavior;
    }

    /**
     * Adds the $tableRowViewCaptions and $tableRowViewAccessors attributes to each object implementing the behavior.
     * Is called before objectMethods, so the results of the parseParameters call are available in the objectMethods function as well.
     */
    public function objectAttributes() {

        $this->build();

        // captions will be available as $tableRowViewCaptions
        $captionsString = 'public static $tableRowViewCaptions = array(';
        foreach ($this->captions as $caption)
            $captionsString .= "'$caption', ";
        $captionsString .= ");";

        // accessors will be available as $tableRowViewAccessors
        $accessorsString = 'public $tableRowViewAccessors = array(';
        foreach ($this->accessors as $caption => $accessor)
            $accessorsString .= "'$caption'=>'$accessor', ";
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
