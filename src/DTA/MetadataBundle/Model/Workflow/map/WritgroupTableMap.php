<?php

namespace DTA\MetadataBundle\Model\Workflow\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'writGroup' table.
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
class WritgroupTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Workflow.map.WritgroupTableMap';

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
        $this->setName('writGroup');
        $this->setPhpName('Writgroup');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Workflow\\Writgroup');
        $this->setPackage('src.Workflow');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('NAME', 'Name', 'LONGVARCHAR', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Task', 'DTA\\MetadataBundle\\Model\\Workflow\\Task', RelationMap::ONE_TO_MANY, array('id' => 'writGroup_id', ), null, null, 'Tasks');
        $this->addRelation('WritWritgroup', 'DTA\\MetadataBundle\\Model\\WritWritgroup', RelationMap::ONE_TO_MANY, array('id' => 'writGroup_id', ), null, null, 'WritWritgroups');
        $this->addRelation('Writ', 'DTA\\MetadataBundle\\Model\\Publication\\Writ', RelationMap::MANY_TO_MANY, array(), null, null, 'Writs');
    } // buildRelations()

} // WritgroupTableMap
