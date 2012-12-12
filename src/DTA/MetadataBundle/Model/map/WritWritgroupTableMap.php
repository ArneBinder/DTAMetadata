<?php

namespace DTA\MetadataBundle\Model\map;

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
 * @package    propel.generator.src.DTA.MetadataBundle.Model.map
 */
class WritWritgroupTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.WritWritgroupTableMap';

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
        $this->setClassname('DTA\\MetadataBundle\\Model\\WritWritgroup');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(false);
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignPrimaryKey('writGroup_id', 'WritgroupId', 'INTEGER' , 'writGroup', 'id', true, null, null);
        $this->addForeignPrimaryKey('writ_id', 'WritId', 'INTEGER' , 'writ', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Writgroup', 'DTA\\MetadataBundle\\Model\\Writgroup', RelationMap::MANY_TO_ONE, array('writGroup_id' => 'id', ), null, null);
        $this->addRelation('Writ', 'DTA\\MetadataBundle\\Model\\Writ', RelationMap::MANY_TO_ONE, array('writ_id' => 'id', ), null, null);
    } // buildRelations()

} // WritWritgroupTableMap
