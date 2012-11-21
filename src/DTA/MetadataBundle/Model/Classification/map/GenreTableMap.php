<?php

namespace DTA\MetadataBundle\Model\Classification\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'genre' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DTA.MetadataBundle.Model.Classification.map
 */
class GenreTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Classification.map.GenreTableMap';

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
        $this->setName('genre');
        $this->setPhpName('Genre');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Classification\\Genre');
        $this->setPackage('src.DTA.MetadataBundle.Model.Classification');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('NAME', 'Name', 'LONGVARCHAR', true, null, null);
        $this->addForeignKey('CHILDOF', 'Childof', 'INTEGER', 'genre', 'ID', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('GenreRelatedByChildof', 'DTA\\MetadataBundle\\Model\\Classification\\Genre', RelationMap::MANY_TO_ONE, array('childOf' => 'id', ), null, null);
        $this->addRelation('GenreRelatedById', 'DTA\\MetadataBundle\\Model\\Classification\\Genre', RelationMap::ONE_TO_MANY, array('id' => 'childOf', ), null, null, 'GenresRelatedById');
        $this->addRelation('WorkRelatedByGenreId', 'DTA\\MetadataBundle\\Model\\Publication\\Work', RelationMap::ONE_TO_MANY, array('id' => 'genre_id', ), null, null, 'WorksRelatedByGenreId');
        $this->addRelation('WorkRelatedBySubgenreId', 'DTA\\MetadataBundle\\Model\\Publication\\Work', RelationMap::ONE_TO_MANY, array('id' => 'subgenre_id', ), null, null, 'WorksRelatedBySubgenreId');
    } // buildRelations()

} // GenreTableMap
