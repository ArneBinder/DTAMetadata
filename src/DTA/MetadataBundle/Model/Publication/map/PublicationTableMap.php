<?php

namespace DTA\MetadataBundle\Model\Publication\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'publication' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Publication.map
 */
class PublicationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Publication.map.PublicationTableMap';

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
        $this->setName('publication');
        $this->setPhpName('Publication');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Publication\\Publication');
        $this->setPackage('src.Publication');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('PUBLISHINGCOMPANY_ID', 'PublishingcompanyId', 'INTEGER', 'publishingCompany', 'ID', false, null, null);
        $this->addForeignKey('PLACE_ID', 'PlaceId', 'INTEGER', 'place', 'ID', false, null, null);
        $this->addForeignKey('DATESPECIFICATION_ID', 'DatespecificationId', 'INTEGER', 'dateSpecification', 'ID', false, null, null);
        $this->addColumn('PRINTRUN', 'Printrun', 'LONGVARCHAR', false, null, null);
        $this->addColumn('PRINTRUNCOMMENT', 'Printruncomment', 'LONGVARCHAR', false, null, null);
        $this->addColumn('EDITION', 'Edition', 'LONGVARCHAR', false, null, null);
        $this->addColumn('NUMPAGES', 'Numpages', 'INTEGER', false, null, null);
        $this->addColumn('NUMPAGESNORMED', 'Numpagesnormed', 'INTEGER', false, null, null);
        $this->addColumn('BIBLIOGRAPHICCITATION', 'Bibliographiccitation', 'LONGVARCHAR', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Publishingcompany', 'DTA\\MetadataBundle\\Model\\Publication\\Publishingcompany', RelationMap::MANY_TO_ONE, array('publishingCompany_id' => 'id', ), null, null);
        $this->addRelation('Place', 'DTA\\MetadataBundle\\Model\\Description\\Place', RelationMap::MANY_TO_ONE, array('place_id' => 'id', ), null, null);
        $this->addRelation('Datespecification', 'DTA\\MetadataBundle\\Model\\Description\\Datespecification', RelationMap::MANY_TO_ONE, array('dateSpecification_id' => 'id', ), null, null);
        $this->addRelation('Essay', 'DTA\\MetadataBundle\\Model\\Publication\\Essay', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Essays');
        $this->addRelation('Magazine', 'DTA\\MetadataBundle\\Model\\Publication\\Magazine', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Magazines');
        $this->addRelation('Monograph', 'DTA\\MetadataBundle\\Model\\Publication\\Monograph', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Monographs');
        $this->addRelation('Series', 'DTA\\MetadataBundle\\Model\\Publication\\Series', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Series');
        $this->addRelation('Title', 'DTA\\MetadataBundle\\Model\\Description\\Title', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Titles');
        $this->addRelation('Writ', 'DTA\\MetadataBundle\\Model\\Publication\\Writ', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Writs');
    } // buildRelations()

} // PublicationTableMap
