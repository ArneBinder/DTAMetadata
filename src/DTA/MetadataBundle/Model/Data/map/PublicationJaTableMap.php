<?php

namespace DTA\MetadataBundle\Model\Data\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'publication_ja' table.
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
class PublicationJaTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Data.map.PublicationJaTableMap';

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
        $this->setName('publication_ja');
        $this->setPhpName('PublicationJa');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Data\\PublicationJa');
        $this->setPackage('src.DTA.MetadataBundle.Model.Data');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('publication_ja_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('publication_id', 'PublicationId', 'INTEGER', 'publication', 'id', true, null, null);
        $this->addForeignKey('volume_id', 'VolumeId', 'INTEGER', 'volume', 'id', true, null, null);
        $this->addColumn('parent', 'Parent', 'INTEGER', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Data\\Publication', RelationMap::MANY_TO_ONE, array('publication_id' => 'id', ), null, null);
        $this->addRelation('Volume', 'DTA\\MetadataBundle\\Model\\Data\\Volume', RelationMap::MANY_TO_ONE, array('volume_id' => 'id', ), null, null);
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
  'embedcolumnspublication' => 'publication',
),
        );
    } // getBehaviors()

} // PublicationJaTableMap
