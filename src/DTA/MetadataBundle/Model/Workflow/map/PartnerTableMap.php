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
 * @package    propel.generator.src.DTA.MetadataBundle.Model.Workflow.map
 */
class PartnerTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Workflow.map.PartnerTableMap';

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
        $this->setPackage('src.DTA.MetadataBundle.Model.Workflow');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('partner_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'LONGVARCHAR', false, null, null);
        $this->addColumn('person', 'Person', 'LONGVARCHAR', false, null, null);
        $this->addColumn('contact_data', 'ContactData', 'LONGVARCHAR', false, null, null);
        $this->addColumn('comments', 'Comments', 'LONGVARCHAR', false, null, null);
        $this->addColumn('is_organization', 'IsOrganization', 'BOOLEAN', false, null, false);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Task', 'DTA\\MetadataBundle\\Model\\Workflow\\Task', RelationMap::ONE_TO_MANY, array('id' => 'partner_id', ), null, null, 'Tasks');
        $this->addRelation('Imagesource', 'DTA\\MetadataBundle\\Model\\Workflow\\Imagesource', RelationMap::ONE_TO_MANY, array('id' => 'partner_id', ), 'SET NULL', null, 'Imagesources');
        $this->addRelation('Textsource', 'DTA\\MetadataBundle\\Model\\Workflow\\Textsource', RelationMap::ONE_TO_MANY, array('id' => 'partner_id', ), 'SET NULL', null, 'Textsources');
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
  'Name' => 'name',
  'Person' => 'person',
  'ContactData' => 'contact_data',
  'Comments' => 'comments',
  'IsOrganization' => 'is_organization',
),
        );
    } // getBehaviors()

} // PartnerTableMap
