<?php

namespace DTA\MetadataBundle\Model\Description\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'title' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Description.map
 */
class TitleTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Description.map.TitleTableMap';

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
        $this->setName('title');
        $this->setPhpName('Title');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Description\\Title');
        $this->setPackage('src.Description');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('SEQUENCEINDEX', 'Sequenceindex', 'INTEGER', true, null, null);
        $this->addColumn('TITLE', 'Title', 'LONGVARCHAR', true, null, null);
        $this->addForeignKey('WORK_ID', 'WorkId', 'INTEGER', 'work', 'ID', false, null, null);
        $this->addForeignKey('PUBLICATION_ID', 'PublicationId', 'INTEGER', 'publication', 'ID', false, null, null);
        $this->addForeignKey('VOLUME_ID', 'VolumeId', 'INTEGER', 'volume', 'ID', false, null, null);
        $this->addForeignKey('TITLETYPE_ID', 'TitletypeId', 'INTEGER', 'titleType', 'ID', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Titletype', 'DTA\\MetadataBundle\\Model\\Description\\Titletype', RelationMap::MANY_TO_ONE, array('titleType_id' => 'id', ), null, null);
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Publication\\Publication', RelationMap::MANY_TO_ONE, array('publication_id' => 'id', ), null, null);
        $this->addRelation('Volume', 'DTA\\MetadataBundle\\Model\\Publication\\Volume', RelationMap::MANY_TO_ONE, array('volume_id' => 'id', ), null, null);
        $this->addRelation('Work', 'DTA\\MetadataBundle\\Model\\Publication\\Work', RelationMap::MANY_TO_ONE, array('work_id' => 'id', ), null, null);
    } // buildRelations()

} // TitleTableMap
