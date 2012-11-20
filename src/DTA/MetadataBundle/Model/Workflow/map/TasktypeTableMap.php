<?php

namespace DTA\MetadataBundle\Model\Workflow\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'taskType' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Workflow.map
 */
class TasktypeTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Workflow.map.TasktypeTableMap';

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
        $this->setName('taskType');
        $this->setPhpName('Tasktype');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Workflow\\Tasktype');
        $this->setPackage('src.Workflow');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('NAME', 'Name', 'LONGVARCHAR', true, null, null);
        $this->addColumn('TREE_LEFT', 'TreeLeft', 'INTEGER', false, null, null);
        $this->addColumn('TREE_RIGHT', 'TreeRight', 'INTEGER', false, null, null);
        $this->addColumn('TREE_LEVEL', 'TreeLevel', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Task', 'DTA\\MetadataBundle\\Model\\Workflow\\Task', RelationMap::ONE_TO_MANY, array('id' => 'taskType_id', ), null, null, 'Tasks');
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
            'nested_set' => array('left_column' => 'tree_left', 'right_column' => 'tree_right', 'level_column' => 'tree_level', 'use_scope' => 'false', 'scope_column' => 'tree_scope', 'method_proxies' => 'false', ),
        );
    } // getBehaviors()

} // TasktypeTableMap
