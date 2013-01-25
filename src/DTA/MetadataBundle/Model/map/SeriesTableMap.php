<?php

namespace DTA\MetadataBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'series' table.
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
class SeriesTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.SeriesTableMap';

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
        $this->setName('series');
        $this->setPhpName('Series');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Series');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('volume', 'Volume', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('title_id', 'TitleId', 'INTEGER', 'title', 'id', true, null, null);
        $this->addForeignKey('publishingCompany_id', 'PublishingcompanyId', 'INTEGER', 'publishingCompany', 'id', false, null, null);
        $this->addForeignKey('place_id', 'PlaceId', 'INTEGER', 'place', 'id', false, null, null);
        $this->addForeignKey('dateSpecification_id', 'DatespecificationId', 'INTEGER', 'dateSpecification', 'id', false, null, null);
        $this->addColumn('printRun', 'Printrun', 'LONGVARCHAR', false, null, null);
        $this->addColumn('printRunComment', 'Printruncomment', 'LONGVARCHAR', false, null, null);
        $this->addColumn('edition', 'Edition', 'LONGVARCHAR', false, null, null);
        $this->addColumn('numPages', 'Numpages', 'INTEGER', false, null, null);
        $this->addColumn('numPagesNormed', 'Numpagesnormed', 'INTEGER', false, null, null);
        $this->addColumn('bibliographicCitation', 'Bibliographiccitation', 'LONGVARCHAR', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Title', 'DTA\\MetadataBundle\\Model\\Title', RelationMap::MANY_TO_ONE, array('title_id' => 'id', ), null, null);
        $this->addRelation('Publishingcompany', 'DTA\\MetadataBundle\\Model\\Publishingcompany', RelationMap::MANY_TO_ONE, array('publishingCompany_id' => 'id', ), null, null);
        $this->addRelation('Place', 'DTA\\MetadataBundle\\Model\\Place', RelationMap::MANY_TO_ONE, array('place_id' => 'id', ), null, null);
        $this->addRelation('Datespecification', 'DTA\\MetadataBundle\\Model\\Datespecification', RelationMap::MANY_TO_ONE, array('dateSpecification_id' => 'id', ), null, null);
    } // buildRelations()

} // SeriesTableMap
