<?php

namespace DTA\MetadataBundle\Model\Publication\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'writ_writGroup' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DTA.MetadataBundle.Model.Publication.map
 */
class WritWritgroupTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Publication.map.WritWritgroupTableMap';

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
        $this->setName('writ_writGroup');
        $this->setPhpName('WritWritgroup');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Publication\\WritWritgroup');
        $this->setPackage('src.DTA.MetadataBundle.Model.Publication');
        $this->setUseIdGenerator(false);
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignPrimaryKey('WRITGROUP_ID', 'WritgroupId', 'INTEGER' , 'writGroup', 'ID', true, null, null);
        $this->addForeignPrimaryKey('WRIT_ID', 'WritId', 'INTEGER' , 'writ', 'ID', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Writgroup', 'DTA\\MetadataBundle\\Model\\Workflow\\Writgroup', RelationMap::MANY_TO_ONE, array('writGroup_id' => 'id', ), null, null);
        $this->addRelation('Writ', 'DTA\\MetadataBundle\\Model\\Publication\\Writ', RelationMap::MANY_TO_ONE, array('writ_id' => 'id', ), null, null);
    } // buildRelations()

} // WritWritgroupTableMap
