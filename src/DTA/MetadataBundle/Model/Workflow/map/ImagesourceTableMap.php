<?php

namespace DTA\MetadataBundle\Model\Workflow\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'imagesource' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DTA.MetadataBundle.Model.Workflow.map
 */
class ImagesourceTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Workflow.map.ImagesourceTableMap';

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
        $this->setName('imagesource');
        $this->setPhpName('Imagesource');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Workflow\\Imagesource');
        $this->setPackage('src.DTA.MetadataBundle.Model.Workflow');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('imagesource_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('publication_id', 'PublicationId', 'INTEGER', 'publication', 'id', true, null, null);
        $this->addColumn('faksimilerefrange', 'Faksimilerefrange', 'LONGVARCHAR', false, null, null);
        $this->addColumn('originalrefrange', 'Originalrefrange', 'LONGVARCHAR', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Data\\Publication', RelationMap::MANY_TO_ONE, array('publication_id' => 'id', ), null, null);
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
  'PublicationId' => 'publication_id',
  'Faksimilerefrange' => 'faksimilerefrange',
  'Originalrefrange' => 'originalrefrange',
  'CreatedAt' => 'created_at',
  'UpdatedAt' => 'updated_at',
),
        );
    } // getBehaviors()

} // ImagesourceTableMap
