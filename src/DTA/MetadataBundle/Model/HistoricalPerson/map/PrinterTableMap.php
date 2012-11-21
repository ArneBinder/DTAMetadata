<?php

namespace DTA\MetadataBundle\Model\HistoricalPerson\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'printer' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DTA.MetadataBundle.Model.HistoricalPerson.map
 */
class PrinterTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.HistoricalPerson.map.PrinterTableMap';

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
        $this->setName('printer');
        $this->setPhpName('Printer');
        $this->setClassname('DTA\\MetadataBundle\\Model\\HistoricalPerson\\Printer');
        $this->setPackage('src.DTA.MetadataBundle.Model.HistoricalPerson');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignPrimaryKey('PERSON_ID', 'PersonId', 'INTEGER' , 'person', 'ID', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Person', 'DTA\\MetadataBundle\\Model\\HistoricalPerson\\Person', RelationMap::MANY_TO_ONE, array('person_id' => 'id', ), null, null);
        $this->addRelation('Writ', 'DTA\\MetadataBundle\\Model\\Publication\\Writ', RelationMap::ONE_TO_MANY, array('id' => 'printer_id', ), null, null, 'Writs');
    } // buildRelations()

} // PrinterTableMap
