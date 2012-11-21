<?php

namespace DTA\MetadataBundle\Model\Workflow\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'user' table.
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
class UserTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Workflow.map.UserTableMap';

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
        $this->setName('user');
        $this->setPhpName('User');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Workflow\\User');
        $this->setPackage('src.DTA.MetadataBundle.Model.Workflow');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('USERNAME', 'Username', 'VARCHAR', false, 255, null);
        $this->addColumn('PASSWORDHASH', 'Passwordhash', 'LONGVARCHAR', false, null, null);
        $this->addColumn('NAME_ID', 'NameId', 'INTEGER', true, null, null);
        $this->addColumn('MAIL', 'Mail', 'LONGVARCHAR', false, null, null);
        $this->addColumn('PHONE', 'Phone', 'LONGVARCHAR', false, null, null);
        $this->addColumn('CREATED', 'Created', 'TIMESTAMP', false, null, null);
        $this->addColumn('MODIFIED', 'Modified', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Task', 'DTA\\MetadataBundle\\Model\\Workflow\\Task', RelationMap::ONE_TO_MANY, array('id' => 'responsibleUser_id', ), null, null, 'Tasks');
    } // buildRelations()

} // UserTableMap
