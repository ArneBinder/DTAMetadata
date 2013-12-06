<?php

namespace DTA\MetadataBundle\Model\Data\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'series' table.
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
class SeriesTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Data.map.SeriesTableMap';

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
        $this->setName('series');
        $this->setPhpName('Series');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Data\\Series');
        $this->setPackage('src.DTA.MetadataBundle.Model.Data');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('series_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('title_id', 'TitleId', 'INTEGER', 'title', 'id', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Title', 'DTA\\MetadataBundle\\Model\\Data\\Title', RelationMap::MANY_TO_ONE, array('title_id' => 'id', ), null, null);
        $this->addRelation('PublicationDs', 'DTA\\MetadataBundle\\Model\\Data\\PublicationDs', RelationMap::ONE_TO_MANY, array('id' => 'series_id', ), null, null, 'PublicationDss');
        $this->addRelation('PublicationMs', 'DTA\\MetadataBundle\\Model\\Data\\PublicationMs', RelationMap::ONE_TO_MANY, array('id' => 'series_id', ), null, null, 'PublicationMss');
        $this->addRelation('PublicationMms', 'DTA\\MetadataBundle\\Model\\Data\\PublicationMms', RelationMap::ONE_TO_MANY, array('id' => 'series_id', ), null, null, 'PublicationMmss');
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
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
            'table_row_view' =>  array (
  'Id' => 'id',
  'TitleId' => 'title_id',
  'CreatedAt' => 'created_at',
  'UpdatedAt' => 'updated_at',
),
        );
    } // getBehaviors()

} // SeriesTableMap
