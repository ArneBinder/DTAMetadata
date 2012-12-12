<?php

namespace DTA\MetadataBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'partner' table.
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
class PartnerTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.PartnerTableMap';

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
        $this->setName('partner');
        $this->setPhpName('Partner');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Partner');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('adress', 'Adress', 'VARCHAR', false, 255, null);
        $this->addColumn('person', 'Person', 'VARCHAR', false, 255, null);
        $this->addColumn('mail', 'Mail', 'VARCHAR', false, 100, null);
        $this->addColumn('web', 'Web', 'VARCHAR', false, 255, null);
        $this->addColumn('comments', 'Comments', 'LONGVARCHAR', false, null, null);
        $this->addColumn('phone1', 'Phone1', 'VARCHAR', false, 50, null);
        $this->addColumn('phone2', 'Phone2', 'VARCHAR', false, 50, null);
        $this->addColumn('phone3', 'Phone3', 'VARCHAR', false, 50, null);
        $this->addColumn('fax', 'Fax', 'VARCHAR', false, 50, null);
        $this->addColumn('log_last_change', 'LogLastChange', 'TIMESTAMP', true, null, null);
        $this->addColumn('log_last_user', 'LogLastUser', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

} // PartnerTableMap
