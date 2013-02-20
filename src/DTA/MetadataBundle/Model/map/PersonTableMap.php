<?php

namespace DTA\MetadataBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'person' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DTA.MetadataBundle.Model.map
 */
class PersonTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.PersonTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('person');
        $this->setPhpName('Person');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Person');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('gnd', 'Gnd', 'VARCHAR', false, 100, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Personalname', 'DTA\\MetadataBundle\\Model\\Personalname', RelationMap::ONE_TO_MANY, array('id' => 'person_id', ), null, null, 'Personalnames');
        $this->addRelation('Author', 'DTA\\MetadataBundle\\Model\\Author', RelationMap::ONE_TO_MANY, array('id' => 'person_id', ), null, null, 'Authors');
        $this->addRelation('Printer', 'DTA\\MetadataBundle\\Model\\Printer', RelationMap::ONE_TO_MANY, array('id' => 'person_id', ), null, null, 'Printers');
        $this->addRelation('Publisher', 'DTA\\MetadataBundle\\Model\\Publisher', RelationMap::ONE_TO_MANY, array('id' => 'person_id', ), null, null, 'Publishers');
        $this->addRelation('Translator', 'DTA\\MetadataBundle\\Model\\Translator', RelationMap::ONE_TO_MANY, array('id' => 'person_id', ), null, null, 'Translators');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'table_row_view' =>  array (
  'Normdatei' => 'gnd',
),
        );
    } // getBehaviors()

} // PersonTableMap
