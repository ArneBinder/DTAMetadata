<?php

namespace DTA\MetadataBundle\Model\HistoricalPerson\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'author' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.HistoricalPerson.map
 */
class AuthorTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.HistoricalPerson.map.AuthorTableMap';

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
        $this->setName('author');
        $this->setPhpName('Author');
        $this->setClassname('DTA\\MetadataBundle\\Model\\HistoricalPerson\\Author');
        $this->setPackage('src.HistoricalPerson');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignPrimaryKey('PERSON_ID', 'PersonId', 'INTEGER' , 'person', 'ID', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Person', 'DTA\\MetadataBundle\\Model\\HistoricalPerson\\Person', RelationMap::MANY_TO_ONE, array('person_id' => 'id', ), null, null);
        $this->addRelation('AuthorWork', 'DTA\\MetadataBundle\\Model\\AuthorWork', RelationMap::ONE_TO_MANY, array('id' => 'author_id', 'person_id' => 'author_person_id', ), null, null, 'AuthorWorks');
        $this->addRelation('Work', 'DTA\\MetadataBundle\\Model\\Publication\\Work', RelationMap::MANY_TO_MANY, array(), null, null, 'Works');
    } // buildRelations()

} // AuthorTableMap
