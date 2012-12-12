<?php

namespace DTA\MetadataBundle\Model\map;

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
 * @package    propel.generator.src.DTA.MetadataBundle.Model.map
 */
class TaskTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.TaskTableMap';

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
        $this->setClassname('DTA\\MetadataBundle\\Model\\Task');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('taskType_id', 'TasktypeId', 'INTEGER', 'taskType', 'id', true, null, null);
        $this->addColumn('done', 'Done', 'BOOLEAN', false, 1, null);
        $this->addColumn('start', 'Start', 'TIMESTAMP', false, null, null);
        $this->addColumn('end', 'End', 'TIMESTAMP', false, null, null);
        $this->addColumn('comments', 'Comments', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('writGroup_id', 'WritgroupId', 'INTEGER', 'writGroup', 'id', false, null, null);
        $this->addForeignKey('writ_id', 'WritId', 'INTEGER', 'writ', 'id', false, null, null);
        $this->addForeignKey('responsibleUser_id', 'ResponsibleuserId', 'INTEGER', 'user', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Tasktype', 'DTA\\MetadataBundle\\Model\\Tasktype', RelationMap::MANY_TO_ONE, array('taskType_id' => 'id', ), null, null);
        $this->addRelation('Writgroup', 'DTA\\MetadataBundle\\Model\\Writgroup', RelationMap::MANY_TO_ONE, array('writGroup_id' => 'id', ), null, null);
        $this->addRelation('Writ', 'DTA\\MetadataBundle\\Model\\Writ', RelationMap::MANY_TO_ONE, array('writ_id' => 'id', ), null, null);
        $this->addRelation('User', 'DTA\\MetadataBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('responsibleUser_id' => 'id', ), null, null);
    } // buildRelations()

} // TaskTableMap
