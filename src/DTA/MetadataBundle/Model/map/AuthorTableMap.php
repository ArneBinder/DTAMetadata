<?php

namespace DTA\MetadataBundle\Model\map;

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
 * @package    propel.generator.src.DTA.MetadataBundle.Model.map
 */
class AuthorTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.AuthorTableMap';

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
        $this->setClassname('DTA\\MetadataBundle\\Model\\Author');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignPrimaryKey('person_id', 'PersonId', 'INTEGER' , 'person', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Person', 'DTA\\MetadataBundle\\Model\\Person', RelationMap::MANY_TO_ONE, array('person_id' => 'id', ), null, null);
        $this->addRelation('AuthorWork', 'DTA\\MetadataBundle\\Model\\AuthorWork', RelationMap::ONE_TO_MANY, array('id' => 'author_id', 'person_id' => 'author_person_id', ), null, null, 'AuthorWorks');
        $this->addRelation('Work', 'DTA\\MetadataBundle\\Model\\Work', RelationMap::MANY_TO_MANY, array(), null, null, 'Works');
    } // buildRelations()

} // AuthorTableMap