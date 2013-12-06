<?php

namespace DTA\MetadataBundle\Model\Classification\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'category' table.
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
class CategoryTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Classification.map.CategoryTableMap';

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
        $this->setName('category');
        $this->setPhpName('Category');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Classification\\Category');
        $this->setPackage('src.DTA.MetadataBundle.Model.Classification');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('category_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'LONGVARCHAR', true, null, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', true, null, null);
        $this->addForeignKey('categorytype_id', 'CategorytypeId', 'INTEGER', 'categorytype', 'id', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Categorytype', 'DTA\\MetadataBundle\\Model\\Classification\\Categorytype', RelationMap::MANY_TO_ONE, array('categorytype_id' => 'id', ), null, null);
        $this->addRelation('CategoryPublication', 'DTA\\MetadataBundle\\Model\\Master\\CategoryPublication', RelationMap::ONE_TO_MANY, array('id' => 'category_id', ), null, null, 'CategoryPublications');
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Data\\Publication', RelationMap::MANY_TO_MANY, array(), null, null, 'Publications');
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
  'Description' => 'description',
  'CategorytypeId' => 'categorytype_id',
  'CreatedAt' => 'created_at',
  'UpdatedAt' => 'updated_at',
),
        );
    } // getBehaviors()

} // CategoryTableMap
