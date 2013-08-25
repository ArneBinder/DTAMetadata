<?php

namespace DTA\MetadataBundle\Model\Data\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'personalname' table.
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
class PersonalnameTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Data.map.PersonalnameTableMap';

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
        $this->setName('personalname');
        $this->setPhpName('Personalname');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Data\\Personalname');
        $this->setPackage('src.DTA.MetadataBundle.Model.Data');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('personalname_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('person_id', 'PersonId', 'INTEGER', 'person', 'id', true, null, null);
        $this->addColumn('sortable_rank', 'SortableRank', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Person', 'DTA\\MetadataBundle\\Model\\Data\\Person', RelationMap::MANY_TO_ONE, array('person_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Namefragment', 'DTA\\MetadataBundle\\Model\\Data\\Namefragment', RelationMap::ONE_TO_MANY, array('id' => 'personalname_id', ), 'CASCADE', null, 'Namefragments');
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
            'sortable' =>  array (
  'rank_column' => 'sortable_rank',
  'use_scope' => 'false',
  'scope_column' => 'id',
),
            'table_row_view' =>  array (
  'id' => 'id',
  'name' => 'accessor:__toString',
  'zugeordnet (personen-id)' => 'person_id',
  'reihenfolge' => 'accessor:getSortableRank',
),
        );
    } // getBehaviors()

} // PersonalnameTableMap
