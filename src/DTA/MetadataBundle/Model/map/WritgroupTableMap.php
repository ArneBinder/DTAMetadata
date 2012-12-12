<?php

namespace DTA\MetadataBundle\Model\map;

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
 * @package    propel.generator.src.DTA.MetadataBundle.Model.map
 */
class WritgroupTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.WritgroupTableMap';

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
        $this->setClassname('DTA\\MetadataBundle\\Model\\Writgroup');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(true);
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
        $this->addRelation('Task', 'DTA\\MetadataBundle\\Model\\Task', RelationMap::ONE_TO_MANY, array('id' => 'writGroup_id', ), null, null, 'Tasks');
        $this->addRelation('WritWritgroup', 'DTA\\MetadataBundle\\Model\\WritWritgroup', RelationMap::ONE_TO_MANY, array('id' => 'writGroup_id', ), null, null, 'WritWritgroups');
        $this->addRelation('Writ', 'DTA\\MetadataBundle\\Model\\Writ', RelationMap::MANY_TO_MANY, array(), null, null, 'Writs');
    } // buildRelations()

} // WritgroupTableMap
