<?php

namespace DTA\MetadataBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'dateSpecification' table.
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
class DatespecificationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.DatespecificationTableMap';

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
        $this->setName('dateSpecification');
        $this->setPhpName('Datespecification');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Datespecification');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('year', 'Year', 'INTEGER', false, null, null);
        $this->addColumn('comments', 'Comments', 'LONGVARCHAR', false, null, null);
        $this->addColumn('year_is_reconstructed', 'YearIsReconstructed', 'BOOLEAN', false, 1, false);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Publication', RelationMap::ONE_TO_MANY, array('id' => 'dateSpecification_id', ), null, null, 'Publications');
        $this->addRelation('Work', 'DTA\\MetadataBundle\\Model\\Work', RelationMap::ONE_TO_MANY, array('id' => 'dateSpecification_id', ), null, null, 'Works');
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
        );
    } // getBehaviors()

} // DatespecificationTableMap
