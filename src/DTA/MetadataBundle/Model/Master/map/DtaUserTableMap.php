<?php

namespace DTA\MetadataBundle\Model\Master\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'dta_user' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DTA.MetadataBundle.Model.Master.map
 */
class DtaUserTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Master.map.DtaUserTableMap';

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
        $this->setName('dta_user');
        $this->setPhpName('DtaUser');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Master\\DtaUser');
        $this->setPackage('src.DTA.MetadataBundle.Model.Master');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('username', 'Username', 'LONGVARCHAR', false, null, null);
        $this->getColumn('username', false)->setPrimaryString(true);
        $this->addColumn('password', 'Password', 'VARCHAR', false, 512, null);
        $this->addColumn('salt', 'Salt', 'VARCHAR', false, 512, null);
        $this->addColumn('mail', 'Mail', 'LONGVARCHAR', false, null, null);
        $this->addColumn('admin', 'Admin', 'BOOLEAN', false, null, false);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('LastChangedPublication', 'DTA\\MetadataBundle\\Model\\Data\\Publication', RelationMap::ONE_TO_MANY, array('id' => 'last_changed_by_user_id', ), null, null, 'LastChangedPublications');
        $this->addRelation('RecentUse', 'DTA\\MetadataBundle\\Model\\Master\\RecentUse', RelationMap::ONE_TO_MANY, array('id' => 'dta_user_id', ), null, null, 'RecentUses');
        $this->addRelation('Task', 'DTA\\MetadataBundle\\Model\\Workflow\\Task', RelationMap::ONE_TO_MANY, array('id' => 'responsibleuser_id', ), null, null, 'Tasks');
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
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
            'table_row_view' =>  array (
  'id' => 'id',
  'benutzername' => 'username',
  'mail' => 'mail',
  'administratorrechte' => 'accessor:adminToString',
),
        );
    } // getBehaviors()

} // DtaUserTableMap
