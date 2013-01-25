<?php

namespace DTA\MetadataBundle\Model\map;

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
 * @package    propel.generator.src.DTA.MetadataBundle.Model.map
 */
class PublicationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.PublicationTableMap';

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
        $this->setClassname('DTA\\MetadataBundle\\Model\\Publication');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('printRun', 'Printrun', 'LONGVARCHAR', false, null, null);
        $this->addColumn('printRunComment', 'Printruncomment', 'LONGVARCHAR', false, null, null);
        $this->addColumn('edition', 'Edition', 'LONGVARCHAR', false, null, null);
        $this->addColumn('numPages', 'Numpages', 'INTEGER', false, null, null);
        $this->addColumn('numPagesNormed', 'Numpagesnormed', 'INTEGER', false, null, null);
        $this->addColumn('bibliographicCitation', 'Bibliographiccitation', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('title_id', 'TitleId', 'INTEGER', 'title', 'id', true, null, null);
        $this->addForeignKey('publishingCompany_id', 'PublishingcompanyId', 'INTEGER', 'publishingCompany', 'id', false, null, null);
        $this->addForeignKey('place_id', 'PlaceId', 'INTEGER', 'place', 'id', false, null, null);
        $this->addForeignKey('dateSpecification_id', 'DatespecificationId', 'INTEGER', 'dateSpecification', 'id', false, null, null);
        $this->addForeignKey('relatedSet_id', 'RelatedsetId', 'INTEGER', 'relatedSet', 'id', false, null, null);
        $this->addForeignKey('work_id', 'WorkId', 'INTEGER', 'work', 'id', true, null, null);
        $this->addForeignKey('publisher_id', 'PublisherId', 'INTEGER', 'publisher', 'id', false, null, null);
        $this->addForeignKey('printer_id', 'PrinterId', 'INTEGER', 'printer', 'id', false, null, null);
        $this->addForeignKey('translator_id', 'TranslatorId', 'INTEGER', 'translator', 'id', false, null, null);
        $this->addColumn('descendant_class', 'DescendantClass', 'VARCHAR', false, 100, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Work', 'DTA\\MetadataBundle\\Model\\Work', RelationMap::MANY_TO_ONE, array('work_id' => 'id', ), null, null);
        $this->addRelation('Publisher', 'DTA\\MetadataBundle\\Model\\Publisher', RelationMap::MANY_TO_ONE, array('publisher_id' => 'id', ), null, null);
        $this->addRelation('Printer', 'DTA\\MetadataBundle\\Model\\Printer', RelationMap::MANY_TO_ONE, array('printer_id' => 'id', ), null, null);
        $this->addRelation('Translator', 'DTA\\MetadataBundle\\Model\\Translator', RelationMap::MANY_TO_ONE, array('translator_id' => 'id', ), null, null);
        $this->addRelation('Relatedset', 'DTA\\MetadataBundle\\Model\\Relatedset', RelationMap::MANY_TO_ONE, array('relatedSet_id' => 'id', ), null, null);
        $this->addRelation('Title', 'DTA\\MetadataBundle\\Model\\Title', RelationMap::MANY_TO_ONE, array('title_id' => 'id', ), null, null);
        $this->addRelation('Publishingcompany', 'DTA\\MetadataBundle\\Model\\Publishingcompany', RelationMap::MANY_TO_ONE, array('publishingCompany_id' => 'id', ), null, null);
        $this->addRelation('Place', 'DTA\\MetadataBundle\\Model\\Place', RelationMap::MANY_TO_ONE, array('place_id' => 'id', ), null, null);
        $this->addRelation('Datespecification', 'DTA\\MetadataBundle\\Model\\Datespecification', RelationMap::MANY_TO_ONE, array('dateSpecification_id' => 'id', ), null, null);
        $this->addRelation('Monograph', 'DTA\\MetadataBundle\\Model\\Monograph', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Monographs');
        $this->addRelation('PublicationPublicationgroup', 'DTA\\MetadataBundle\\Model\\PublicationPublicationgroup', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationPublicationgroups');
        $this->addRelation('Source', 'DTA\\MetadataBundle\\Model\\Source', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Sources');
        $this->addRelation('Task', 'DTA\\MetadataBundle\\Model\\Task', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Tasks');
        $this->addRelation('Publicationgroup', 'DTA\\MetadataBundle\\Model\\Publicationgroup', RelationMap::MANY_TO_MANY, array(), null, null, 'Publicationgroups');
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
            'concrete_inheritance_parent' =>  array (
  'descendant_column' => 'descendant_class',
),
        );
    } // getBehaviors()

} // PublicationTableMap
