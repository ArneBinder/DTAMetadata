<?php

namespace DTA\MetadataBundle\Model\Data\map;

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
 * @package    propel.generator.src.DTA.MetadataBundle.Model.Data.map
 */
class VolumeTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Data.map.VolumeTableMap';

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
        $this->setClassname('DTA\\MetadataBundle\\Model\\Data\\Volume');
        $this->setPackage('src.DTA.MetadataBundle.Model.Data');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('volume_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('volumedescription', 'Volumedescription', 'INTEGER', false, null, null);
        $this->addColumn('volumenumeric', 'Volumenumeric', 'LONGVARCHAR', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PublicationMm', 'DTA\\MetadataBundle\\Model\\Data\\PublicationMm', RelationMap::ONE_TO_MANY, array('id' => 'volume_id', ), null, null, 'PublicationMms');
        $this->addRelation('PublicationDs', 'DTA\\MetadataBundle\\Model\\Data\\PublicationDs', RelationMap::ONE_TO_MANY, array('id' => 'volume_id', ), null, null, 'PublicationDss');
        $this->addRelation('PublicationJa', 'DTA\\MetadataBundle\\Model\\Data\\PublicationJa', RelationMap::ONE_TO_MANY, array('id' => 'volume_id', ), null, null, 'PublicationJas');
        $this->addRelation('PublicationMms', 'DTA\\MetadataBundle\\Model\\Data\\PublicationMms', RelationMap::ONE_TO_MANY, array('id' => 'volume_id', ), null, null, 'PublicationMmss');
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
  'Id' => 'id',
  'Volumedescription' => 'volumedescription',
  'Volumenumeric' => 'volumenumeric',
),
        );
    } // getBehaviors()

} // VolumeTableMap
