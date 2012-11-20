<?php

namespace DTA\MetadataBundle\Model\Workflow\map;

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
 * @package    propel.generator.src.Workflow.map
 */
class PartnerTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Workflow.map.PartnerTableMap';

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
        $this->setClassname('DTA\\MetadataBundle\\Model\\Workflow\\Partner');
        $this->setPackage('src.Workflow');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('NAME', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('ADRESS', 'Adress', 'VARCHAR', false, 255, null);
        $this->addColumn('PERSON', 'Person', 'VARCHAR', false, 255, null);
        $this->addColumn('MAIL', 'Mail', 'VARCHAR', false, 100, null);
        $this->addColumn('WEB', 'Web', 'VARCHAR', false, 255, null);
        $this->addColumn('COMMENTS', 'Comments', 'LONGVARCHAR', false, null, null);
        $this->addColumn('PHONE1', 'Phone1', 'VARCHAR', false, 50, null);
        $this->addColumn('PHONE2', 'Phone2', 'VARCHAR', false, 50, null);
        $this->addColumn('PHONE3', 'Phone3', 'VARCHAR', false, 50, null);
        $this->addColumn('FAX', 'Fax', 'VARCHAR', false, 50, null);
        $this->addColumn('LOG_LAST_CHANGE', 'LogLastChange', 'TIMESTAMP', true, null, null);
        $this->addColumn('LOG_LAST_USER', 'LogLastUser', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

} // PartnerTableMap
