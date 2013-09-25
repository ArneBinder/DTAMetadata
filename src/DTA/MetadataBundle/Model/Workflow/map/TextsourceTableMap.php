<?php

namespace DTA\MetadataBundle\Model\Workflow\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'textsource' table.
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
class TextsourceTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Workflow.map.TextsourceTableMap';

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
        $this->setName('textsource');
        $this->setPhpName('Textsource');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Workflow\\Textsource');
        $this->setPackage('src.DTA.MetadataBundle.Model.Workflow');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('textsource_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('publication_id', 'PublicationId', 'INTEGER', 'publication', 'id', true, null, null);
        $this->addForeignKey('partner_id', 'PartnerId', 'INTEGER', 'partner', 'id', false, null, null);
        $this->addColumn('texturl', 'Texturl', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('license_id', 'LicenseId', 'INTEGER', 'license', 'id', false, null, null);
        $this->addColumn('attribution', 'Attribution', 'LONGVARCHAR', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Data\\Publication', RelationMap::MANY_TO_ONE, array('publication_id' => 'id', ), null, null);
        $this->addRelation('License', 'DTA\\MetadataBundle\\Model\\Workflow\\License', RelationMap::MANY_TO_ONE, array('license_id' => 'id', ), null, null);
        $this->addRelation('Partner', 'DTA\\MetadataBundle\\Model\\Workflow\\Partner', RelationMap::MANY_TO_ONE, array('partner_id' => 'id', ), null, null);
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
  'PublicationId' => 'publication_id',
  'PartnerId' => 'partner_id',
  'Texturl' => 'texturl',
  'LicenseId' => 'license_id',
  'Attribution' => 'attribution',
),
        );
    } // getBehaviors()

} // TextsourceTableMap
