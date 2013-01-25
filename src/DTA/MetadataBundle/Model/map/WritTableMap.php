<?php

namespace DTA\MetadataBundle\Model\map;

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
 * @package    propel.generator.src.DTA.MetadataBundle.Model.map
 */
class WritTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.WritTableMap';

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
        $this->setClassname('DTA\\MetadataBundle\\Model\\Writ');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('work_id', 'WorkId', 'INTEGER', 'work', 'id', true, null, null);
        $this->addForeignKey('publication_id', 'PublicationId', 'INTEGER', 'publication', 'id', true, null, null);
        $this->addForeignKey('publisher_id', 'PublisherId', 'INTEGER', 'publisher', 'id', false, null, null);
        $this->addForeignKey('printer_id', 'PrinterId', 'INTEGER', 'printer', 'id', false, null, null);
        $this->addForeignKey('translator_id', 'TranslatorId', 'INTEGER', 'translator', 'id', false, null, null);
        $this->addColumn('numPages', 'Numpages', 'INTEGER', false, null, null);
        $this->addForeignKey('relatedSet_id', 'RelatedsetId', 'INTEGER', 'relatedSet', 'id', false, null, null);
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
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Publication', RelationMap::MANY_TO_ONE, array('publication_id' => 'id', ), null, null);
        $this->addRelation('Relatedset', 'DTA\\MetadataBundle\\Model\\Relatedset', RelationMap::MANY_TO_ONE, array('relatedSet_id' => 'id', ), null, null);
        $this->addRelation('WritWritgroup', 'DTA\\MetadataBundle\\Model\\WritWritgroup', RelationMap::ONE_TO_MANY, array('id' => 'writ_id', ), null, null, 'WritWritgroups');
        $this->addRelation('Corpus', 'DTA\\MetadataBundle\\Model\\Corpus', RelationMap::ONE_TO_MANY, array('id' => 'writ_id', ), null, null, 'Corpuses');
        $this->addRelation('Source', 'DTA\\MetadataBundle\\Model\\Source', RelationMap::ONE_TO_MANY, array('id' => 'writ_id', ), null, null, 'Sources');
        $this->addRelation('Task', 'DTA\\MetadataBundle\\Model\\Task', RelationMap::ONE_TO_MANY, array('id' => 'writ_id', ), null, null, 'Tasks');
        $this->addRelation('Writgroup', 'DTA\\MetadataBundle\\Model\\Writgroup', RelationMap::MANY_TO_MANY, array(), null, null, 'Writgroups');
    } // buildRelations()

} // WritTableMap
