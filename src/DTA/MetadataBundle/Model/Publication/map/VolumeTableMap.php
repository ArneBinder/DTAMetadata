<?php

namespace DTA\MetadataBundle\Model\Publication\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'volume' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Publication.map
 */
class VolumeTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Publication.map.VolumeTableMap';

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
        $this->setName('volume');
        $this->setPhpName('Volume');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Publication\\Volume');
        $this->setPackage('src.Publication');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('VOLUMEINDEX', 'Volumeindex', 'LONGVARCHAR', false, null, null);
        $this->addColumn('VOLUMEINDEXNUMERICAL', 'Volumeindexnumerical', 'INTEGER', false, null, null);
        $this->addColumn('TOTALVOLUMES', 'Totalvolumes', 'INTEGER', false, null, null);
        $this->addForeignKey('MONOGRAPH_ID', 'MonographId', 'INTEGER', 'monograph', 'ID', true, null, null);
        $this->addForeignKey('MONOGRAPH_PUBLICATION_ID', 'MonographPublicationId', 'INTEGER', 'monograph', 'PUBLICATION_ID', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Monograph', 'DTA\\MetadataBundle\\Model\\Publication\\Monograph', RelationMap::MANY_TO_ONE, array('monograph_id' => 'id', 'monograph_publication_id' => 'publication_id', ), null, null);
        $this->addRelation('Title', 'DTA\\MetadataBundle\\Model\\Description\\Title', RelationMap::ONE_TO_MANY, array('id' => 'volume_id', ), null, null, 'Titles');
    } // buildRelations()

} // VolumeTableMap
