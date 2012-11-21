<?php

namespace DTA\MetadataBundle\Model\Description\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'nameFragment' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DTA.MetadataBundle.Model.Description.map
 */
class NamefragmentTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Description.map.NamefragmentTableMap';

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
        $this->setName('nameFragment');
        $this->setPhpName('Namefragment');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Description\\Namefragment');
        $this->setPackage('src.DTA.MetadataBundle.Model.Description');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('PERSONALNAME_ID', 'PersonalnameId', 'INTEGER', 'personalName', 'ID', true, null, null);
        $this->addColumn('NAME', 'Name', 'LONGVARCHAR', true, null, null);
        $this->addForeignKey('NAMEFRAGMENTTYPEID', 'Namefragmenttypeid', 'INTEGER', 'nameFragmentType', 'ID', true, null, null);
        $this->addColumn('SORTABLE_RANK', 'SortableRank', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Namefragmenttype', 'DTA\\MetadataBundle\\Model\\Description\\Namefragmenttype', RelationMap::MANY_TO_ONE, array('nameFragmentTypeId' => 'id', ), null, null);
        $this->addRelation('Personalname', 'DTA\\MetadataBundle\\Model\\Description\\Personalname', RelationMap::MANY_TO_ONE, array('personalName_id' => 'id', ), null, null);
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
            'sortable' => array('rank_column' => 'sortable_rank', 'use_scope' => 'true', 'scope_column' => 'personalName_id', ),
        );
    } // getBehaviors()

} // NamefragmentTableMap
