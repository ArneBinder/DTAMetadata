<?php

namespace DTA\MetadataBundle\Model\map;

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
 * @package    propel.generator.src.DTA.MetadataBundle.Model.map
 */
class VolumeTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.VolumeTableMap';

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
        $this->setClassname('DTA\\MetadataBundle\\Model\\Volume');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('volumeIndex', 'Volumeindex', 'LONGVARCHAR', false, null, null);
        $this->addColumn('volumeIndexNumerical', 'Volumeindexnumerical', 'INTEGER', false, null, null);
        $this->addColumn('totalVolumes', 'Totalvolumes', 'INTEGER', false, null, null);
        $this->addForeignKey('monograph_id', 'MonographId', 'INTEGER', 'monograph', 'id', true, null, null);
        $this->addForeignKey('monograph_publication_id', 'MonographPublicationId', 'INTEGER', 'monograph', 'publication_id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Monograph', 'DTA\\MetadataBundle\\Model\\Monograph', RelationMap::MANY_TO_ONE, array('monograph_id' => 'id', 'monograph_publication_id' => 'publication_id', ), null, null);
    } // buildRelations()

} // VolumeTableMap
