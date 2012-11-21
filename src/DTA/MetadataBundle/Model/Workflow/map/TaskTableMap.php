<?php

namespace DTA\MetadataBundle\Model\Workflow\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'task' table.
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
class TaskTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Workflow.map.TaskTableMap';

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
        $this->setName('task');
        $this->setPhpName('Task');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Workflow\\Task');
        $this->setPackage('src.DTA.MetadataBundle.Model.Workflow');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('TASKTYPE_ID', 'TasktypeId', 'INTEGER', 'taskType', 'ID', true, null, null);
        $this->addColumn('DONE', 'Done', 'BOOLEAN', false, 1, null);
        $this->addColumn('START', 'Start', 'TIMESTAMP', false, null, null);
        $this->addColumn('END', 'End', 'TIMESTAMP', false, null, null);
        $this->addColumn('COMMENTS', 'Comments', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('WRITGROUP_ID', 'WritgroupId', 'INTEGER', 'writGroup', 'ID', false, null, null);
        $this->addForeignKey('WRIT_ID', 'WritId', 'INTEGER', 'writ', 'ID', false, null, null);
        $this->addForeignKey('RESPONSIBLEUSER_ID', 'ResponsibleuserId', 'INTEGER', 'user', 'ID', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Tasktype', 'DTA\\MetadataBundle\\Model\\Workflow\\Tasktype', RelationMap::MANY_TO_ONE, array('taskType_id' => 'id', ), null, null);
        $this->addRelation('Writgroup', 'DTA\\MetadataBundle\\Model\\Workflow\\Writgroup', RelationMap::MANY_TO_ONE, array('writGroup_id' => 'id', ), null, null);
        $this->addRelation('Writ', 'DTA\\MetadataBundle\\Model\\Publication\\Writ', RelationMap::MANY_TO_ONE, array('writ_id' => 'id', ), null, null);
        $this->addRelation('User', 'DTA\\MetadataBundle\\Model\\Workflow\\User', RelationMap::MANY_TO_ONE, array('responsibleUser_id' => 'id', ), null, null);
    } // buildRelations()

} // TaskTableMap
