<?php

namespace DTA\MetadataBundle\Model\Description\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'place' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Description.map
 */
class PlaceTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Description.map.PlaceTableMap';

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
        $this->setName('place');
        $this->setPhpName('Place');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Description\\Place');
        $this->setPackage('src.Description');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('NAME', 'Name', 'LONGVARCHAR', true, null, null);
        $this->addColumn('GND', 'Gnd', 'VARCHAR', false, 100, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Publication\\Publication', RelationMap::ONE_TO_MANY, array('id' => 'place_id', ), null, null, 'Publications');
    } // buildRelations()

} // PlaceTableMap
