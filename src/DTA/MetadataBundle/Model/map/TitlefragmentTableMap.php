<?php

namespace DTA\MetadataBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'titleFragment' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DTA.MetadataBundle.Model.map
 */
class TitlefragmentTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.TitlefragmentTableMap';

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
        $this->setName('titleFragment');
        $this->setPhpName('Titlefragment');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Titlefragment');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'LONGVARCHAR', true, null, null);
        $this->addForeignKey('title_id', 'TitleId', 'INTEGER', 'title', 'id', true, null, null);
        $this->addForeignKey('titleFragmentType_id', 'TitlefragmenttypeId', 'INTEGER', 'titleFragmentType', 'id', true, null, null);
        $this->addColumn('sortable_rank', 'SortableRank', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Title', 'DTA\\MetadataBundle\\Model\\Title', RelationMap::MANY_TO_ONE, array('title_id' => 'id', ), null, null);
        $this->addRelation('Titlefragmenttype', 'DTA\\MetadataBundle\\Model\\Titlefragmenttype', RelationMap::MANY_TO_ONE, array('titleFragmentType_id' => 'id', ), null, null);
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
            'sortable' => array('rank_column' => 'sortable_rank', 'use_scope' => 'true', 'scope_column' => 'title_id', ),
        );
    } // getBehaviors()

} // TitlefragmentTableMap
