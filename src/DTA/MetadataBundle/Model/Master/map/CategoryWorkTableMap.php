<?php

namespace DTA\MetadataBundle\Model\Master\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'category_work' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DTA.MetadataBundle.Model.Master.map
 */
class CategoryWorkTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Master.map.CategoryWorkTableMap';

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
        $this->setName('category_work');
        $this->setPhpName('CategoryWork');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Master\\CategoryWork');
        $this->setPackage('src.DTA.MetadataBundle.Model.Master');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('category_work_id_seq');
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignKey('category_id', 'CategoryId', 'INTEGER', 'category', 'id', true, null, null);
        $this->addForeignKey('work_id', 'WorkId', 'INTEGER', 'work', 'id', true, null, null);
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Category', 'DTA\\MetadataBundle\\Model\\Classification\\Category', RelationMap::MANY_TO_ONE, array('category_id' => 'id', ), null, null);
        $this->addRelation('Work', 'DTA\\MetadataBundle\\Model\\Data\\Work', RelationMap::MANY_TO_ONE, array('work_id' => 'id', ), null, null);
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
  'CategoryId' => 'category_id',
  'WorkId' => 'work_id',
  'Id' => 'id',
),
            'auto_add_pk' =>  array (
  'name' => 'id',
  'autoIncrement' => 'true',
  'type' => 'INTEGER',
),
        );
    } // getBehaviors()

} // CategoryWorkTableMap
