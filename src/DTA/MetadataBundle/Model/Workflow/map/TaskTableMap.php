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
        $this->setPrimaryKeyMethodInfo('task_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('tasktype_id', 'TasktypeId', 'INTEGER', 'tasktype', 'id', true, null, null);
        $this->addColumn('done', 'Done', 'BOOLEAN', false, null, null);
        $this->addColumn('startdate', 'Startdate', 'DATE', false, null, null);
        $this->addColumn('enddate', 'Enddate', 'DATE', false, null, null);
        $this->addColumn('comments', 'Comments', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('publicationgroup_id', 'PublicationgroupId', 'INTEGER', 'publicationgroup', 'id', false, null, null);
        $this->addForeignKey('publication_id', 'PublicationId', 'INTEGER', 'publication', 'id', false, null, null);
        $this->addForeignKey('responsibleuser_id', 'ResponsibleuserId', 'INTEGER', 'dta_user', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Tasktype', 'DTA\\MetadataBundle\\Model\\Workflow\\Tasktype', RelationMap::MANY_TO_ONE, array('tasktype_id' => 'id', ), null, null);
        $this->addRelation('Publicationgroup', 'DTA\\MetadataBundle\\Model\\Workflow\\Publicationgroup', RelationMap::MANY_TO_ONE, array('publicationgroup_id' => 'id', ), null, null);
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Data\\Publication', RelationMap::MANY_TO_ONE, array('publication_id' => 'id', ), null, null);
        $this->addRelation('DtaUser', 'DTA\\MetadataBundle\\Model\\Master\\DtaUser', RelationMap::MANY_TO_ONE, array('responsibleuser_id' => 'id', ), null, null);
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
  'TasktypeId' => 'tasktype_id',
  'Done' => 'done',
  'Startdate' => 'startdate',
  'Enddate' => 'enddate',
  'Comments' => 'comments',
  'PublicationgroupId' => 'publicationgroup_id',
  'PublicationId' => 'publication_id',
  'ResponsibleuserId' => 'responsibleuser_id',
),
        );
    } // getBehaviors()

} // TaskTableMap
