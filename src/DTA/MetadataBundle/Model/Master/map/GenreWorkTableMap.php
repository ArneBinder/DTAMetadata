<?php

namespace DTA\MetadataBundle\Model\Master\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'genre_work' table.
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
class GenreWorkTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Master.map.GenreWorkTableMap';

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
        $this->setName('genre_work');
        $this->setPhpName('GenreWork');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Master\\GenreWork');
        $this->setPackage('src.DTA.MetadataBundle.Model.Master');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('genre_work_id_seq');
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignKey('genre_id', 'GenreId', 'INTEGER', 'genre', 'id', true, null, null);
        $this->addForeignKey('work_id', 'WorkId', 'INTEGER', 'work', 'id', true, null, null);
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Genre', 'DTA\\MetadataBundle\\Model\\Classification\\Genre', RelationMap::MANY_TO_ONE, array('genre_id' => 'id', ), null, null);
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
  'GenreId' => 'genre_id',
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

} // GenreWorkTableMap
