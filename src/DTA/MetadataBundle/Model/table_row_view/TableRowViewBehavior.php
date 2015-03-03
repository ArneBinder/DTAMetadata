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
 * ----------------------------------------------------------------
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
 * ----------------------------------------------------------------
 * 
 * <parameter name="embedColumns<*>" value="<tableName>" />
 * 
 * Where <*> is an arbitrary string used to create different parameter names, because of multiple parameters 
 * with the same names, all but one are ignored.
 * Replace <tableName> for the value of the name attribute of any other table the entity is related to 
 * (this is independent of the obligation to make the relation explicit using a foreign key).
 * 
 * 3. Specifying a custom function
 * ----------------------------------------------------------------
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
 * ----------------------------------------------------------------
 * 
 * To indicate that there are several related entities or to summarize a complex related entity into a single column,
 * reference the related table without the embedColumns keyword, just by giving a column headline with either the suffix '@representative' 
 * or '@count' to get the first of the related entities or the count, respectively.
 * 
 * <parameter name="<columnHeadline>@representative" value="<tableName>"/>
 * <parameter name="<columnHeadline>@count" value="<tableName>"/>
 * 
 * The behavior will recognize whether this is a one-to-one relationship or a one-to-many relationship.
 * Use any value of the name attribute of another table instead of <tableName>.
 * It will try to fetch the first of the related entities and summarize by using it's __toString method.
 * 
 * 
 * @author stud
 */

use DTA\MetadataBundle\Controller\ORMController;

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

    public $filterColumns = array(

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


    /**
     *
     */
    public $orderColumnFunctions = array(
        // strings, containing the php code of the single methods
    );

    /**
     *
     */
    public $filterFunctions = array();
    
    /** [optional] Executable code that generates the propel query object for the class to return records in a certain manner (e.g. more efficient). 
     * A valid string would be 
     * "DTA\MetadataBundle\Model\Data\PersonQuery::create()
        ->joinWith('Personalname')
        ->joinWith('Personalname.Namefragment')
        ->joinWith('Namefragment.Namefragmenttype')
        ->orderBy('Namefragmenttype.id', \Criteria::DESC)   // to sort by last name
        ->orderBy('Namefragment.name');"
     * 
     */
    public $queryConstructionString = NULL;
    
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

    /*private static function extractPureAccessor($accessor){
        if(strncmp($accessor, "accessor:", strlen("accessor:"))) {
            return $accessor;
        }elseif(!strncmp($accessor, "accessor:get", strlen("accessor:get"))) {
            $modifiedAccessor = substr($accessor,strlen("accessor:get"));
            return $modifiedAccessor;
        }else{
            throw new InvalidArgumentException(sprintf(
                'Could not extract pure accessor of \'%s\'.', $accessor));
        }
    }*/

    /**
     * Extract local columns and embedded columns from the parameters.
     * Check their existance and whether target entities also implement the behavior.
     * @param TableRowViewBehavior $behavior
     * @return TableRowViewBehavior the modified behavior (could also be passed by reference, but this might be clearer)
     */
    private static function resolveParameters(TableRowViewBehavior &$behavior) {

        // cross ref tables are only for maintaining many-to-many relationships and don't represent entities.
        // Still they're added since the behavior is usually applied to an entire set of tables (schema).
        if ($behavior->getTable()->getIsCrossRef())
            return;

        foreach ($behavior->getParameters() as $captionOrIndicator => $columnOrEntityOrAccessor) {

            // split parameter into map
            $parameterArray = preg_split('/(accessor|query|embedColumns|filterColumn):/',$columnOrEntityOrAccessor, null, PREG_SPLIT_DELIM_CAPTURE);
            //echo implode(implode(', ',$parameterArray))."\n";
            $parameters = array();
            if(count($parameterArray) % 2 == 1){
                //the anonymous parameter part is the propel accessor
                $parameters['propelAccessor'] = trim(array_shift($parameterArray));
            }
            $key = null;
            foreach ($parameterArray as $param) {
                if ($key != null) {
                    $parameters[$key] = trim($param);
                    $key = null;
                } else {
                    $key = $param;
                }
            }

            if(array_key_exists('filterColumn',$parameters)){
                $splitted = explode('@',$captionOrIndicator);
                if(count($splitted)>1){
                    $behavior->filterColumns[]=$splitted[0];
                }else {
                    $behavior->filterColumns[] = $captionOrIndicator;
                }
            }

            // check whether the string starts with 'embedColumns'
            if(array_key_exists('embedColumns',$parameters)){
                $entity = $parameters['embedColumns'];
                $behavior->resolveEmbeddedColumns($entity);
                // ... or to insert a complex entity as single column ...
            } elseif(array_key_exists('query',$parameters)) {
                $behavior->queryConstructionString = $parameters['query'];
            } elseif (array_key_exists('propelAccessor',$parameters) && $behavior->getTable()->getDatabase()->hasTable($parameters['propelAccessor'])) {
                $entity = $parameters['propelAccessor'];
                $caption = $captionOrIndicator;
                $behavior->resolveRepresentativeColumn($caption, $entity);
                // ... or an explicitly defined accessor to the object ...
            } elseif(array_key_exists('accessor',$parameters)) {
                // the prefix 'accessor:' is left as an indicator for the generated getAttributeByTableViewColumName method
                // to prevent using a standard propel getter
                $accessor = 'accessor:'.$parameters['accessor'];
                $caption = $captionOrIndicator;
                $behavior->addViewElement($caption, $accessor);
                /*if(!strncmp($parameters['accessor'], "get", strlen("get"))) {
                    $modifiedAccessor = substr($parameters['accessor'], strlen("get"));
                    $behavior->orderColumnFunctions[] = $behavior->renderTemplate('tableRowViewOrderColumnFunction', array(
                        'functionName'=> $modifiedAccessor,
                        'orderEntity' => $modifiedAccessor
                    ));
                }*/
                // or if it defines how to construct the query object
            } else {
                $column = $parameters['propelAccessor'];
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
     * Looks up and adds the columns designated for view in another table and adds all of them to the local column list.
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
                            'The entity \'%s\' in the table_row_view behavior of table \'%s\' doesn\'t exist. Please use the table name as specified by the schema <table> tag.', $tableName, $table->getName()));

        // prevent recursion
        if ($table->getName() === $relatedEntity->getName())
            throw new InvalidArgumentException(sprintf(
                            'No recursion allowed in the table_row_view behavior (table \'%s\').', $table->getName()));

        // related entity must implement table_row_view behavior
        $otherBehavior = $relatedEntity->getBehavior('table_row_view');
        if (!$otherBehavior)
            throw new InvalidArgumentException(sprintf(
                            'The entity \'%s\' in the table_row_view behavior of table \'%s\' doesn\'t implement the table_row_behavior. Please add it before proceeding.', $tableName,$table->getName()));

        // build the behavior (e.g. parse embed columns parameters recursively) 
        $otherBehavior->build();

        /*$relatedPhpName = $relatedEntity->getPhpName();
        $relatedPackage = end(explode('.',$relatedEntity->getPackage()));
        if(method_exists("DTA\\MetadataBundle\\Model\\$relatedPackage\\$relatedPhpName".'Query', 'sqlFilter')){
            $this->filterFunctions[] = $this->renderTemplate('tableRowViewFilterMethodEmbedded', array(
                'filterElement' => $relatedPhpName,
                'package' => $relatedPackage
            ));
        }*/

        // add all columns to the local view
        $i = 0;
        foreach ($otherBehavior->accessors as $remoteCaption => $remoteAccessor) {
            $i++;
            // generate a getter function that redirected the remote accessor to the related entity
            $relatedEntityPhpName = $relatedEntity->getPhpName();
            //$embeddedGetterFunctionName = 'getEmbeddedColumn' . $i . 'Of' . $relatedEntityPhpName;
            $embeddedGetterFunctionName = str_replace("accessor:","",$remoteAccessor) . 'Of' . $relatedEntityPhpName;
            $this->embeddedGetterFunctions[] = $this->renderTemplate('tableRowViewEmbeddedGetter',array(
                'functionName' => $embeddedGetterFunctionName,
                'relatedEntity' => $relatedEntityPhpName,
                'caption' => $remoteCaption,
            ));

            if(in_array($remoteCaption, $otherBehavior->filterColumns)){
                $this->filterColumns[] = $remoteCaption;
            }

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
                            'The entity \'%s\' in the table_row_view behavior of table \'%s\' doesn\'t exist. Please use the table name as specified by the schema <table> tag.', $tableName, $table->getName()));
        
        // prevent recursion
        if ($table->getName() === $relatedEntity->getName())
            throw new InvalidArgumentException(sprintf(
                            'No recursion allowed in the table_row_view behavior (table \'%s\').', $table->getName()));
        
        $relatedPhpName = $relatedEntity->getPhpName();
        
        // check suffizes to determine special columns
        $representativeCandidate = substr($caption, -strlen("@representative"));
        $countCandidate = substr($caption, -strlen("@count"));
        
        if(!strncmp($representativeCandidate, "@representative", strlen("@representative"))){
            
            $captionCharacters = strlen($caption) - strlen('@representative');
            $representativeCaption = substr($caption, 0, $captionCharacters);
            $representativeAccessor = 'accessor:getRepresentative' . $relatedPhpName;
            $this->addViewElement($representativeCaption, $representativeAccessor);

            /*$this->orderColumnFunctions[] = $this->renderTemplate('tableRowViewOrderColumnFunction', array(
                'functionName'=> "Representative$relatedPhpName",
                'orderEntity' => $relatedPhpName
            ));*/
            
        } elseif (!strncmp($countCandidate, "@count", strlen("@count"))) {
            
            $captionCharacters = strlen($caption) - strlen('@count');
            $countCaption = substr($caption, 0, $captionCharacters);
            // the accessor already exists as a propel convenience function
            $pluralizer = new \StandardEnglishPluralizer();
            $pluralizedClassname = $pluralizer->getPluralForm($relatedPhpName);
            $countAccessor = "accessor:" . "count".$pluralizedClassname;
            $this->addViewElement($countCaption, $countAccessor);
            
        }
        
        $getterFunc = $this->renderTemplate('tableRowViewRepresentativeGetter', array(
            'className' => $relatedPhpName,
            'modelClassName' => $table->getPhpName(),
        ));
        
        if( false === array_search($getterFunc, $this->representativeGetterFunctions))
            $this->representativeGetterFunctions[] = $getterFunc;
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
        $accessorsString = 'public static $tableRowViewAccessors = array(';
        foreach ($this->accessors as $caption => $accessor) {
            $accessorsString .= "'$caption'=>'$accessor', ";

        }
        $accessorsString .= ");";

        $queryConstructionStringValue = $this->queryConstructionString === NULL ? 'NULL' : '"' . $this->queryConstructionString . '"';
        $queryConstructionString = 'public static $queryConstructionString = ' . $queryConstructionStringValue . ';';

        $filterColumnsString = 'public static $filterColumns = array('. implode(",",array_map(function($value) { return "'$value'"; },$this->filterColumns)). ");";

        return $captionsString . "\r\t" . $accessorsString . "\r\t" . $queryConstructionString . "\r\t" . $filterColumnsString . "\r";
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

    public function queryMethods(){

    }

    // add interface implementation declaration
    public function objectFilter(&$script) {
        //$pattern = '/abstract class (\w+) extends (\w+) implements (\w+)/i';
        $pattern = '/abstract class (\w+) extends (\w+) implements (\w+)/i';
        // the comment causes problems if other behaviors want to add interfaces, too. "// TableRowViewInterface automatically implemented by the TableRowViewBehavior.php\r" .
        $replace = 'abstract class ${1} extends ${2} implements ${3}, \DTA\MetadataBundle\Model\table_row_view\TableRowViewInterface';
        $script = preg_replace($pattern, $replace, $script);
    }

    /*public function queryMethods(){
        return $this->renderTemplate('tableRowViewQueryMethods', array(
            'orderColumnFunctions' => $this->orderColumnFunctions
        ));
    }
*/
}

?>
