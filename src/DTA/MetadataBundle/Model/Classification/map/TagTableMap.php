<?php

namespace DTA\MetadataBundle\Model\Classification\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'tag' table.
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
class TagTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Classification.map.TagTableMap';

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
        $this->setName('tag');
        $this->setPhpName('Tag');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Classification\\Tag');
        $this->setPackage('src.DTA.MetadataBundle.Model.Classification');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('tag_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'LONGVARCHAR', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('WorkTag', 'DTA\\MetadataBundle\\Model\\Master\\WorkTag', RelationMap::ONE_TO_MANY, array('id' => 'tag_id', ), null, null, 'WorkTags');
        $this->addRelation('Work', 'DTA\\MetadataBundle\\Model\\Data\\Work', RelationMap::MANY_TO_MANY, array(), null, null, 'Works');
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
  'Name' => 'name',
),
        );
    } // getBehaviors()

} // TagTableMap