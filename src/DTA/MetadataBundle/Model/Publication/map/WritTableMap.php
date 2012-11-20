<?php

namespace DTA\MetadataBundle\Model\Publication\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'writ' table.
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
class WritTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Publication.map.WritTableMap';

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
        $this->setName('writ');
        $this->setPhpName('Writ');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Publication\\Writ');
        $this->setPackage('src.Publication');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('WORK_ID', 'WorkId', 'INTEGER', 'work', 'ID', true, null, null);
        $this->addForeignKey('PUBLICATION_ID', 'PublicationId', 'INTEGER', 'publication', 'ID', true, null, null);
        $this->addForeignKey('PUBLISHER_ID', 'PublisherId', 'INTEGER', 'publisher', 'ID', false, null, null);
        $this->addForeignKey('PRINTER_ID', 'PrinterId', 'INTEGER', 'printer', 'ID', false, null, null);
        $this->addForeignKey('TRANSLATOR_ID', 'TranslatorId', 'INTEGER', 'translator', 'ID', false, null, null);
        $this->addColumn('NUMPAGES', 'Numpages', 'INTEGER', false, null, null);
        $this->addForeignKey('RELATEDSET_ID', 'RelatedsetId', 'INTEGER', 'relatedSet', 'ID', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Work', 'DTA\\MetadataBundle\\Model\\Publication\\Work', RelationMap::MANY_TO_ONE, array('work_id' => 'id', ), null, null);
        $this->addRelation('Publisher', 'DTA\\MetadataBundle\\Model\\HistoricalPerson\\Publisher', RelationMap::MANY_TO_ONE, array('publisher_id' => 'id', ), null, null);
        $this->addRelation('Printer', 'DTA\\MetadataBundle\\Model\\HistoricalPerson\\Printer', RelationMap::MANY_TO_ONE, array('printer_id' => 'id', ), null, null);
        $this->addRelation('Translator', 'DTA\\MetadataBundle\\Model\\HistoricalPerson\\Translator', RelationMap::MANY_TO_ONE, array('translator_id' => 'id', ), null, null);
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Publication\\Publication', RelationMap::MANY_TO_ONE, array('publication_id' => 'id', ), null, null);
        $this->addRelation('Relatedset', 'DTA\\MetadataBundle\\Model\\Workflow\\Relatedset', RelationMap::MANY_TO_ONE, array('relatedSet_id' => 'id', ), null, null);
        $this->addRelation('Corpus', 'DTA\\MetadataBundle\\Model\\Classification\\Corpus', RelationMap::ONE_TO_MANY, array('id' => 'writ_id', ), null, null, 'Corpuses');
        $this->addRelation('Source', 'DTA\\MetadataBundle\\Model\\Workflow\\Source', RelationMap::ONE_TO_MANY, array('id' => 'writ_id', ), null, null, 'Sources');
        $this->addRelation('Task', 'DTA\\MetadataBundle\\Model\\Workflow\\Task', RelationMap::ONE_TO_MANY, array('id' => 'writ_id', ), null, null, 'Tasks');
        $this->addRelation('WritWritgroup', 'DTA\\MetadataBundle\\Model\\WritWritgroup', RelationMap::ONE_TO_MANY, array('id' => 'writ_id', ), null, null, 'WritWritgroups');
        $this->addRelation('Writgroup', 'DTA\\MetadataBundle\\Model\\Workflow\\Writgroup', RelationMap::MANY_TO_MANY, array(), null, null, 'Writgroups');
    } // buildRelations()

} // WritTableMap
