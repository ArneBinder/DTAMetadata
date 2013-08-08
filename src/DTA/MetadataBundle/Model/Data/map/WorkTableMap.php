<?php

namespace DTA\MetadataBundle\Model\Data\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'work' table.
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
class WorkTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Data.map.WorkTableMap';

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
        $this->setName('work');
        $this->setPhpName('Work');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Data\\Work');
        $this->setPackage('src.DTA.MetadataBundle.Model.Data');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('work_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('title_id', 'TitleId', 'INTEGER', 'title', 'id', true, null, null);
        $this->addForeignKey('datespecification_id', 'DatespecificationId', 'INTEGER', 'datespecification', 'id', false, null, null);
        $this->addColumn('doi', 'Doi', 'LONGVARCHAR', false, null, null);
        $this->addColumn('comments', 'Comments', 'LONGVARCHAR', false, null, null);
        $this->addColumn('format', 'Format', 'LONGVARCHAR', false, null, null);
        $this->addColumn('directoryname', 'Directoryname', 'LONGVARCHAR', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Title', 'DTA\\MetadataBundle\\Model\\Data\\Title', RelationMap::MANY_TO_ONE, array('title_id' => 'id', ), null, null);
        $this->addRelation('Datespecification', 'DTA\\MetadataBundle\\Model\\Data\\Datespecification', RelationMap::MANY_TO_ONE, array('datespecification_id' => 'id', ), null, null);
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Data\\Publication', RelationMap::ONE_TO_MANY, array('id' => 'work_id', ), null, null, 'Publications');
        $this->addRelation('LanguageWork', 'DTA\\MetadataBundle\\Model\\Master\\LanguageWork', RelationMap::ONE_TO_MANY, array('id' => 'work_id', ), null, null, 'LanguageWorks');
        $this->addRelation('GenreWork', 'DTA\\MetadataBundle\\Model\\Master\\GenreWork', RelationMap::ONE_TO_MANY, array('id' => 'work_id', ), null, null, 'GenreWorks');
        $this->addRelation('WorkTag', 'DTA\\MetadataBundle\\Model\\Master\\WorkTag', RelationMap::ONE_TO_MANY, array('id' => 'work_id', ), null, null, 'WorkTags');
        $this->addRelation('CategoryWork', 'DTA\\MetadataBundle\\Model\\Master\\CategoryWork', RelationMap::ONE_TO_MANY, array('id' => 'work_id', ), null, null, 'CategoryWorks');
        $this->addRelation('PersonWork', 'DTA\\MetadataBundle\\Model\\Master\\PersonWork', RelationMap::ONE_TO_MANY, array('id' => 'work_id', ), null, null, 'PersonWorks');
        $this->addRelation('Language', 'DTA\\MetadataBundle\\Model\\Data\\Language', RelationMap::MANY_TO_MANY, array(), null, null, 'Languages');
        $this->addRelation('Genre', 'DTA\\MetadataBundle\\Model\\Classification\\Genre', RelationMap::MANY_TO_MANY, array(), null, null, 'Genres');
        $this->addRelation('Tag', 'DTA\\MetadataBundle\\Model\\Classification\\Tag', RelationMap::MANY_TO_MANY, array(), null, null, 'Tags');
        $this->addRelation('Category', 'DTA\\MetadataBundle\\Model\\Classification\\Category', RelationMap::MANY_TO_MANY, array(), null, null, 'Categories');
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
  'Titel' => 'accessor:getTitle',
  'erster Autor' => 'accessor:getFirstAuthor',
  'entstanden' => 'accessor:getDatespecification',
),
        );
    } // getBehaviors()

} // WorkTableMap
