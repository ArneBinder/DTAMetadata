<?php

/**
 * Parses a propel XML schema and dumps the symfony validation YAML output that corresponds to the required="true" attributes on the columns.
 * Columns named "id" are skipped because it is assumed that these columns are auto incrementing.
 */

require_once '../../../../../vendor/symfony/symfony/src/Symfony/Component/Yaml/Dumper.php';
require_once '../../../../../vendor/symfony/symfony/src/Symfony/Component/Yaml/Yaml.php';
require_once '../../../../../vendor/symfony/symfony/src/Symfony/Component/Yaml/Inline.php';
require_once '../../../../../vendor/symfony/symfony/src/Symfony/Component/Yaml/Escaper.php';
require_once '../../../../../vendor/symfony/symfony/src/Symfony/Component/Yaml/Parser.php';
require_once '../../../../../vendor/symfony/symfony/src/Symfony/Component/Yaml/Unescaper.php';

/**
 * Configuration
 */
$inputFileName = './dta_workflow_schema.xml';
$outputFileName = 'output.yml';

/**
 * Main Program
 */

$xml = new SimpleXMLElement(file_get_contents($inputFileName));
createSymfonyValidationRules($xml);

/**
 * Functions
 */

function createSymfonyValidationRules($dom){
    
    $result = array();

    // iterate over each table and create an entity
    foreach($dom->table as $table){
        $qualifiedEntityName = $dom->xpath("@namespace")[0] . '\\' . phpClassName($table->xpath("@name")[0]);
        $constraints = array();
        foreach($table->column as $column){
            
            $columnName = "" . $column->xpath('@name')[0];
            
            foreach($column->attributes() as $attribute=>$value){
                if( $attribute == 'required' && $value == 'true' && $columnName !== 'id'){
                    $constraints[$columnName] = array(array('NotBlank'=>'~'));
                }
            }
        }
        $result[$qualifiedEntityName] = array('properties'=>$constraints);
    }

    $yamlFileContent = Symfony\Component\Yaml\Yaml::dump($result, 3);
    $yamlFileContent = str_replace("'~'", "~", $yamlFileContent);
    
    file_put_contents($outputFileName, $yamlFileContent);
}

function phpClassName($tableName){
    // converts a data base name like multi_volume to a camel case version like MultiVolume
    return implode('',explode(' ',ucwords(str_replace('_', ' ', $tableName))));
}