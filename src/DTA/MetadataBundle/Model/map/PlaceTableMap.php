<?php

namespace DTA\MetadataBundle\Model\map;

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
 * @package    propel.generator.src.DTA.MetadataBundle.Model.map
 */
class PlaceTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.PlaceTableMap';

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
        $this->setClassname('DTA\\MetadataBundle\\Model\\Place');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'LONGVARCHAR', true, null, null);
        $this->addColumn('gnd', 'Gnd', 'VARCHAR', false, 100, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Publication', RelationMap::ONE_TO_MANY, array('id' => 'place_id', ), null, null, 'Publications');
        $this->addRelation('Essay', 'DTA\\MetadataBundle\\Model\\Essay', RelationMap::ONE_TO_MANY, array('id' => 'place_id', ), null, null, 'Essays');
        $this->addRelation('Magazine', 'DTA\\MetadataBundle\\Model\\Magazine', RelationMap::ONE_TO_MANY, array('id' => 'place_id', ), null, null, 'Magazines');
        $this->addRelation('Series', 'DTA\\MetadataBundle\\Model\\Series', RelationMap::ONE_TO_MANY, array('id' => 'place_id', ), null, null, 'Series');
    } // buildRelations()

} // PlaceTableMap
