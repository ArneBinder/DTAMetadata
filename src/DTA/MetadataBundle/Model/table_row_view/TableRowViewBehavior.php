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

    // add implements declaration
    public function objectFilter(&$script)
    {
        $pattern = '/abstract class (\w+) extends (\w+) implements (\w+)/i';
        $replace = "// TableRowViewInterface automatically implemented by the TableRowViewBehavior.php\r".
                'abstract class ${1} extends ${2}  implements ${3}, \DTA\MetadataBundle\Model\table_row_view\TableRowViewInterface';
        $script = preg_replace($pattern, $replace, $script);
    }
    
    public function objectMethods($builder) {
        return $this->renderTemplate('tableRowViewObjectMethods', array(
            'flaggableColumns' => $this->flaggableColumns,
            'flagColumnSuffix' => ReconstructedFlaggableBehavior::$flagColumnSuffix,
            'phpFlagColumnSuffix' => ReconstructedFlaggableBehavior::$phpFlagColumnSuffix,
        ));
    }

}

?>
