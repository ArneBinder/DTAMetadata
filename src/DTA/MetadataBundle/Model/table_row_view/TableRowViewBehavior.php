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
 * Replace <columnName> for any of the values of the name attribute of a column in the table, e.g.
 * 
 * <parameter name="Interne Personennummer" value="person_id"/>
 * 
 * For the column <column name="person_id" type="INTEGER" required="true"/>
 * 
 * 2. Embedding all visible columns of a one-to-one related entity
 * 
 * <parameter name="embedColumns<*>" value="<tableName>" />
 * 
 * Where <*> is an arbitrary string used to create different parameter names, because of multiple parameters 
 * with the same names, all but one are ignored.
 * Replace <tableName> for the value of the name attribute of any other table the entity is related to 
 * (this is independent of the obligation to make the relation explicit using a foreign key).
 * 
 * 3. Specifying a custom function
 * 
 * To specify a function that returns the value for the column, use the 'accessor:' prefix, 
 * followed by the name of the function to call on the object. This can be used to display derived
 * values from other columns or to call the __toString method.
 * For example, in the Personalname entity, all name fragments are summarized with an overriden __toString method and displayed using
 * 
 * <parameter name="AllFragments" value="accessor:__toString"/>
 * 
 * Causing the following code to be executed (Personalname.php) to display the value of the column "AllFragments"
 * <code>
 * public function __toString(){
        $result = "";
        foreach($this->getNamefragments() as $nameFragment){
            $result .= $nameFragment->getName() . " ";
        }
        return $result;
    }
 * </code>
 * 
 * 4. Pick a representative related entity
 * 
 * To indicate that there are several related entities or to summarize a complex related entity into a single column,
 * reference the related table without the embedColumns keyword, just by giving a column headline
 * 
 * <parameter name="<columnHeadline>" value="<tableName>"/>
 * 
 * The behavior will recognize whether this is a one-to-one relationship or a one-to-many relationship.
 * Use any value of the name attribute of another table instead of <tableName>.
 * It will try to fetch the first of the related entities and summarize by using it's __toString method.
 * 
 * 
 * @author stud
 */
class TableRowViewBehavior extends Behavior {

    // default parameters value
    protected $parameters = array();

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
     * For each parameter that requires display of a representative entity of a *-to-many relationship, 
     * a selector method is generated, providing a default for overriding in the model classes derived from the generated base classes.
     */
    public $representativeGetterFunctions = array(
        // strings, containing the php code of the single methods
    );
    
    /**
     * Contains the php code for the method that returns the value of an embedded column.
     * This will simply forward the accessor string by the embedded behavior to the related instance,
     * like for instance $this->getPublication->getAttributeByTableViewColumName('hardCodedAccessor');
     * To achieve this simply, the 'accessor:' prefix is also used to call the generated embeddedGetter
     */
    public $embeddedGetterFunctions = array(
        // strings, containing the php code of the single methods
    );
    
    private $built = false;
    
    /**
     * Creates the behavior by parsing its parameters 
     * (and possibly recursively the parameters of referenced behaviors) 
     * and gathering columns headlines and accessors.
     */
    public function build() {
        
        // each behavior needs to be built only once. this can happen either on execution of the behavior
        // or by another behavior that embeds the columns of this behavior.
        if($this->built)
            return;
        $this->built = true;
        
        // default parameters: if no parameters are defined, add all columns
        if (count($this->getParameters()) == 0) {
            foreach ($this->getTable()->getColumns() as $column) {
                // add as atomic/local column parameter (headline and column name)
                $this->addParameter(array('name' => $column->getPhpName(), 'value' => $column->getName()));
            }
        }
        TableRowViewBehavior::resolveParameters($this);
    }

    /**
     * Adds a column to the table view. 
     * @param string $caption The label of the column as displayed in <th> tags
     * @param string $accessor The accessor string as used by the view to get the value of the current entity (row) for that column
     */
    public function addViewElement($caption, $accessor) {
        $this->captions[] = $caption;
        $this->accessors[$caption] = $accessor;
    }

    /**
     * Extract local columns and embedded columns from the parameters.
     * Check their existance and whether target entities also implement the behavior.
     * @param TableRowViewBehavior $behavior
     * @return TableRowViewBehavior the modified behavior (could also be passed by reference, but this might be clearer)
     */
    private static function resolveParameters(TableRowViewBehavior &$behavior) {

        // cross ref tables are only for maintaining many-to-many relationships and don't represent entities.
        if ($behavior->getTable()->getIsCrossRef())
            return;

        foreach ($behavior->getParameters() as $captionOrIndicator => $columnOrEntityOrAccessor) {

            // check whether parameter name is an indicator to embed columns ... 
            if (!strncmp($captionOrIndicator, "embedColumns", strlen("embedColumns"))) {
                $entity = $columnOrEntityOrAccessor;
                $behavior->resolveEmbeddedColumns($entity);
                // ... or to insert a complex entity as single column ... 
            } elseif ($behavior->getTable()->getDatabase()->hasTable($columnOrEntityOrAccessor)) {
                $entity = $columnOrEntityOrAccessor;
                $caption = $captionOrIndicator;
                $behavior->resolveRepresentativeColumn($caption, $entity);
                // ... or an explicitly defined accessor to the object ...
            } elseif(!strncmp($columnOrEntityOrAccessor, "accessor:", strlen("accessor:"))) {
                // the prefix 'accessor:' is left as an indicator for the generated getAttributeByTableViewColumName method
                // to prevent using a standard propel getter
                $accessor = $columnOrEntityOrAccessor;
                $caption = $captionOrIndicator;
                $behavior->addViewElement($caption, $accessor);
                // ... or simply a column taken from the entity itself
            } else {
                $column = $columnOrEntityOrAccessor;
                $caption = $captionOrIndicator;
                $behavior->resolveAtomicColumn($caption, $column);
            }
        }// each parameter
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
        // the generated getAttributeByTableViewColumName will use the propel standard getter 
        // $this->getByName($accessor, \BasePeer::TYPE_PHPNAME) to use the accessor
        $accessor = $table->getColumn($column)->getPhpName();

        $this->addViewElement($caption, $accessor);
    }

    /**
     * Looks up and adds the columns designated for view in another table and adds all of them to the local colum list.
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
        $i = 0;
        foreach ($otherBehavior->accessors as $remoteCaption => $remoteAccessor) {
            $i++;
            // generate a getter function that redirected the remote accessor to the related entity
            $relatedEntityPhpName = $relatedEntity->getPhpName();
            $embeddedGetterFunctionName = 'getEmbeddedColumn' . $i . 'Of' . $relatedEntityPhpName;
            $this->embeddedGetterFunctions[] = $this->renderTemplate('tableRowViewEmbeddedGetter',array(
                'functionName' => $embeddedGetterFunctionName,
                'relatedEntity' => $relatedEntityPhpName,
                'caption' => $remoteCaption,
            ));
            
            $subAccessor = 'accessor:' . $embeddedGetterFunctionName;
//            visualizing the structure is useful but results in long table headlines, that are impractical
//            $this->addViewElement($relatedEntityPhpName."_".$remoteCaption, $subAccessor);
            $this->addViewElement($remoteCaption, $subAccessor);
            
        }
    }

    /**
     * Handles parameters that refer to *-to-many relations. Generates default methods 
     * to override in the custom model classes. They follow the naming schema getRepresentative<modelClassName>()
     * where <modelClassName> is the php name of the generated entity
     * @param type $tableName
     * @throws InvalidArgumentException
     */
    private function resolveRepresentativeColumn($caption, $tableName) {

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
        
        $relatedPhpName = $relatedEntity->getPhpName();
        
        $accessor = "accessor:" . "getRepresentative$relatedPhpName";
        
        $getterFunc = $this->renderTemplate('tableRowViewRepresentativeGetter', array(
            'className' => $relatedPhpName,
        ));
        $this->representativeGetterFunctions[] = $getterFunc;
        
        $this->addViewElement($caption, $accessor);
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
        $accessorsString = 'public   $tableRowViewAccessors = array(';
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
                    'representativeGetterFunctions' => $this->representativeGetterFunctions,
                    'embeddedGetterFunctions' => $this->embeddedGetterFunctions,
                ));
    }

    // add interface implementation declaration
    public function objectFilter(&$script) {
        $pattern = '/abstract class (\w+) extends (\w+) implements (\w+)/i';
        $replace = "// TableRowViewInterface automatically implemented by the TableRowViewBehavior.php\r" .
                'abstract class ${1} extends ${2}  implements ${3}, \DTA\MetadataBundle\Model\table_row_view\TableRowViewInterface';
        $script = preg_replace($pattern, $replace, $script);
    }

}

?>
