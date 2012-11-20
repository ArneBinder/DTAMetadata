<?php

namespace DTA\MetadataBundle\Model\HistoricalPerson\map;

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
 * @package    propel.generator.src.HistoricalPerson.map
 */
class PersonTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.HistoricalPerson.map.PersonTableMap';

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
        $this->setClassname('DTA\\MetadataBundle\\Model\\HistoricalPerson\\Person');
        $this->setPackage('src.HistoricalPerson');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('GND', 'Gnd', 'VARCHAR', false, 100, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Author', 'DTA\\MetadataBundle\\Model\\HistoricalPerson\\Author', RelationMap::ONE_TO_MANY, array('id' => 'person_id', ), null, null, 'Authors');
        $this->addRelation('Personalname', 'DTA\\MetadataBundle\\Model\\Description\\Personalname', RelationMap::ONE_TO_MANY, array('id' => 'person_id', ), null, null, 'Personalnames');
        $this->addRelation('Printer', 'DTA\\MetadataBundle\\Model\\HistoricalPerson\\Printer', RelationMap::ONE_TO_MANY, array('id' => 'person_id', ), null, null, 'Printers');
        $this->addRelation('Publisher', 'DTA\\MetadataBundle\\Model\\HistoricalPerson\\Publisher', RelationMap::ONE_TO_MANY, array('id' => 'person_id', ), null, null, 'Publishers');
        $this->addRelation('Translator', 'DTA\\MetadataBundle\\Model\\HistoricalPerson\\Translator', RelationMap::ONE_TO_MANY, array('id' => 'person_id', ), null, null, 'Translators');
    } // buildRelations()

} // PersonTableMap
