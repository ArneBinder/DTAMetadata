<?php

namespace DTA\MetadataBundle\Model\Classification\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'namefragmenttype' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DTA.MetadataBundle.Model.Classification.map
 */
class NamefragmenttypeTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Classification.map.NamefragmenttypeTableMap';

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
        $this->setName('namefragmenttype');
        $this->setPhpName('Namefragmenttype');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Classification\\Namefragmenttype');
        $this->setPackage('src.DTA.MetadataBundle.Model.Classification');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('namefragmenttype_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'LONGVARCHAR', true, null, null);
        $this->getColumn('name', false)->setPrimaryString(true);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Namefragment', 'DTA\\MetadataBundle\\Model\\Data\\Namefragment', RelationMap::ONE_TO_MANY, array('id' => 'namefragmenttypeid', ), null, null, 'Namefragments');
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
  'Name' => 'name',
  'CreatedAt' => 'created_at',
  'UpdatedAt' => 'updated_at',
),
        );
    } // getBehaviors()

} // NamefragmenttypeTableMap
