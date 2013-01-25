<?php

namespace DTA\MetadataBundle\Model\map;

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
 * @package    propel.generator.src.DTA.MetadataBundle.Model.map
 */
class WorkTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.WorkTableMap';

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
        $this->setClassname('DTA\\MetadataBundle\\Model\\Work');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('status_id', 'StatusId', 'INTEGER', 'status', 'id', true, null, null);
        $this->addForeignKey('dateSpecification_id', 'DatespecificationId', 'INTEGER', 'dateSpecification', 'id', false, null, null);
        $this->addForeignKey('genre_id', 'GenreId', 'INTEGER', 'genre', 'id', false, null, null);
        $this->addForeignKey('subgenre_id', 'SubgenreId', 'INTEGER', 'genre', 'id', false, null, null);
        $this->addForeignKey('dwdsGenre_id', 'DwdsgenreId', 'INTEGER', 'dwdsGenre', 'id', false, null, null);
        $this->addForeignKey('dwdsSubgenre_id', 'DwdssubgenreId', 'INTEGER', 'dwdsGenre', 'id', false, null, null);
        $this->addColumn('doi', 'Doi', 'LONGVARCHAR', false, null, null);
        $this->addColumn('comments', 'Comments', 'LONGVARCHAR', false, null, null);
        $this->addColumn('format', 'Format', 'LONGVARCHAR', false, null, null);
        $this->addColumn('directoryName', 'Directoryname', 'LONGVARCHAR', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Status', 'DTA\\MetadataBundle\\Model\\Status', RelationMap::MANY_TO_ONE, array('status_id' => 'id', ), null, null);
        $this->addRelation('GenreRelatedByGenreId', 'DTA\\MetadataBundle\\Model\\Genre', RelationMap::MANY_TO_ONE, array('genre_id' => 'id', ), null, null);
        $this->addRelation('GenreRelatedBySubgenreId', 'DTA\\MetadataBundle\\Model\\Genre', RelationMap::MANY_TO_ONE, array('subgenre_id' => 'id', ), null, null);
        $this->addRelation('DwdsgenreRelatedByDwdsgenreId', 'DTA\\MetadataBundle\\Model\\Dwdsgenre', RelationMap::MANY_TO_ONE, array('dwdsGenre_id' => 'id', ), null, null);
        $this->addRelation('DwdsgenreRelatedByDwdssubgenreId', 'DTA\\MetadataBundle\\Model\\Dwdsgenre', RelationMap::MANY_TO_ONE, array('dwdsSubgenre_id' => 'id', ), null, null);
        $this->addRelation('Datespecification', 'DTA\\MetadataBundle\\Model\\Datespecification', RelationMap::MANY_TO_ONE, array('dateSpecification_id' => 'id', ), null, null);
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Publication', RelationMap::ONE_TO_MANY, array('id' => 'work_id', ), null, null, 'Publications');
        $this->addRelation('AuthorWork', 'DTA\\MetadataBundle\\Model\\AuthorWork', RelationMap::ONE_TO_MANY, array('id' => 'work_id', ), null, null, 'AuthorWorks');
        $this->addRelation('Monograph', 'DTA\\MetadataBundle\\Model\\Monograph', RelationMap::ONE_TO_MANY, array('id' => 'work_id', ), null, null, 'Monographs');
        $this->addRelation('Essay', 'DTA\\MetadataBundle\\Model\\Essay', RelationMap::ONE_TO_MANY, array('id' => 'work_id', ), null, null, 'Essays');
        $this->addRelation('Magazine', 'DTA\\MetadataBundle\\Model\\Magazine', RelationMap::ONE_TO_MANY, array('id' => 'work_id', ), null, null, 'Magazines');
        $this->addRelation('Series', 'DTA\\MetadataBundle\\Model\\Series', RelationMap::ONE_TO_MANY, array('id' => 'work_id', ), null, null, 'Series');
        $this->addRelation('Author', 'DTA\\MetadataBundle\\Model\\Author', RelationMap::MANY_TO_MANY, array(), null, null, 'Authors');
    } // buildRelations()

} // WorkTableMap
