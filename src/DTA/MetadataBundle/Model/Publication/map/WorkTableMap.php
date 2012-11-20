<?php

namespace DTA\MetadataBundle\Model\Publication\map;

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
 * @package    propel.generator.src.Publication.map
 */
class WorkTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Publication.map.WorkTableMap';

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
        $this->setClassname('DTA\\MetadataBundle\\Model\\Publication\\Work');
        $this->setPackage('src.Publication');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('STATUS_ID', 'StatusId', 'INTEGER', 'status', 'ID', true, null, null);
        $this->addForeignKey('DATESPECIFICATION_ID', 'DatespecificationId', 'INTEGER', 'dateSpecification', 'ID', false, null, null);
        $this->addForeignKey('GENRE_ID', 'GenreId', 'INTEGER', 'genre', 'ID', false, null, null);
        $this->addForeignKey('SUBGENRE_ID', 'SubgenreId', 'INTEGER', 'genre', 'ID', false, null, null);
        $this->addForeignKey('DWDSGENRE_ID', 'DwdsgenreId', 'INTEGER', 'dwdsGenre', 'ID', false, null, null);
        $this->addForeignKey('DWDSSUBGENRE_ID', 'DwdssubgenreId', 'INTEGER', 'dwdsGenre', 'ID', false, null, null);
        $this->addColumn('DOI', 'Doi', 'LONGVARCHAR', false, null, null);
        $this->addColumn('COMMENTS', 'Comments', 'LONGVARCHAR', false, null, null);
        $this->addColumn('FORMAT', 'Format', 'LONGVARCHAR', false, null, null);
        $this->addColumn('DIRECTORYNAME', 'Directoryname', 'LONGVARCHAR', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Status', 'DTA\\MetadataBundle\\Model\\Workflow\\Status', RelationMap::MANY_TO_ONE, array('status_id' => 'id', ), null, null);
        $this->addRelation('GenreRelatedByGenreId', 'DTA\\MetadataBundle\\Model\\Classification\\Genre', RelationMap::MANY_TO_ONE, array('genre_id' => 'id', ), null, null);
        $this->addRelation('GenreRelatedBySubgenreId', 'DTA\\MetadataBundle\\Model\\Classification\\Genre', RelationMap::MANY_TO_ONE, array('subgenre_id' => 'id', ), null, null);
        $this->addRelation('DwdsgenreRelatedByDwdsgenreId', 'DTA\\MetadataBundle\\Model\\Classification\\Dwdsgenre', RelationMap::MANY_TO_ONE, array('dwdsGenre_id' => 'id', ), null, null);
        $this->addRelation('DwdsgenreRelatedByDwdssubgenreId', 'DTA\\MetadataBundle\\Model\\Classification\\Dwdsgenre', RelationMap::MANY_TO_ONE, array('dwdsSubgenre_id' => 'id', ), null, null);
        $this->addRelation('Datespecification', 'DTA\\MetadataBundle\\Model\\Description\\Datespecification', RelationMap::MANY_TO_ONE, array('dateSpecification_id' => 'id', ), null, null);
        $this->addRelation('AuthorWork', 'DTA\\MetadataBundle\\Model\\AuthorWork', RelationMap::ONE_TO_MANY, array('id' => 'work_id', ), null, null, 'AuthorWorks');
        $this->addRelation('Title', 'DTA\\MetadataBundle\\Model\\Description\\Title', RelationMap::ONE_TO_MANY, array('id' => 'work_id', ), null, null, 'Titles');
        $this->addRelation('Writ', 'DTA\\MetadataBundle\\Model\\Publication\\Writ', RelationMap::ONE_TO_MANY, array('id' => 'work_id', ), null, null, 'Writs');
        $this->addRelation('Author', 'DTA\\MetadataBundle\\Model\\HistoricalPerson\\Author', RelationMap::MANY_TO_MANY, array(), null, null, 'Authors');
    } // buildRelations()

} // WorkTableMap
