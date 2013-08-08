<?php

namespace DTA\MetadataBundle\Model\Data\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'datespecification' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DTA.MetadataBundle.Model.Data.map
 */
class DatespecificationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Data.map.DatespecificationTableMap';

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
        $this->setName('datespecification');
        $this->setPhpName('Datespecification');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Data\\Datespecification');
        $this->setPackage('src.DTA.MetadataBundle.Model.Data');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('datespecification_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('year', 'Year', 'INTEGER', false, null, null);
        $this->addColumn('comments', 'Comments', 'LONGVARCHAR', false, null, null);
        $this->addColumn('year_is_reconstructed', 'YearIsReconstructed', 'BOOLEAN', false, null, false);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PublicationRelatedByPublicationdateId', 'DTA\\MetadataBundle\\Model\\Data\\Publication', RelationMap::ONE_TO_MANY, array('id' => 'publicationdate_id', ), null, null, 'PublicationsRelatedByPublicationdateId');
        $this->addRelation('PublicationRelatedByFirstpublicationdateId', 'DTA\\MetadataBundle\\Model\\Data\\Publication', RelationMap::ONE_TO_MANY, array('id' => 'firstpublicationdate_id', ), null, null, 'PublicationsRelatedByFirstpublicationdateId');
        $this->addRelation('Work', 'DTA\\MetadataBundle\\Model\\Data\\Work', RelationMap::ONE_TO_MANY, array('id' => 'datespecification_id', ), null, null, 'Works');
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
            'reconstructed_flaggable' =>  array (
  'column' => 'year',
),
            'table_row_view' =>  array (
  'Id' => 'id',
  'Year' => 'year',
  'Comments' => 'comments',
  'YearIsReconstructed' => 'year_is_reconstructed',
),
        );
    } // getBehaviors()

} // DatespecificationTableMap
