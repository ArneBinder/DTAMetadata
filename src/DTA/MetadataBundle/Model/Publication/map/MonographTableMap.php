<?php

namespace DTA\MetadataBundle\Model\Publication\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'monograph' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DTA.MetadataBundle.Model.Publication.map
 */
class MonographTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Publication.map.MonographTableMap';

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
        $this->setName('monograph');
        $this->setPhpName('Monograph');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Publication\\Monograph');
        $this->setPackage('src.DTA.MetadataBundle.Model.Publication');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignPrimaryKey('PUBLICATION_ID', 'PublicationId', 'INTEGER' , 'publication', 'ID', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Publication\\Publication', RelationMap::MANY_TO_ONE, array('publication_id' => 'id', ), null, null);
        $this->addRelation('Volume', 'DTA\\MetadataBundle\\Model\\Publication\\Volume', RelationMap::ONE_TO_MANY, array('id' => 'monograph_id', 'publication_id' => 'monograph_publication_id', ), null, null, 'Volumes');
    } // buildRelations()

} // MonographTableMap
