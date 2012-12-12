<?php

namespace DTA\MetadataBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'author_work' table.
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
class AuthorWorkTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.AuthorWorkTableMap';

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
        $this->setName('author_work');
        $this->setPhpName('AuthorWork');
        $this->setClassname('DTA\\MetadataBundle\\Model\\AuthorWork');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(false);
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignPrimaryKey('work_id', 'WorkId', 'INTEGER' , 'work', 'id', true, null, null);
        $this->addForeignPrimaryKey('author_id', 'AuthorId', 'INTEGER' , 'author', 'id', true, null, null);
        $this->addForeignPrimaryKey('author_person_id', 'AuthorPersonId', 'INTEGER' , 'author', 'person_id', true, null, null);
        $this->addColumn('name_id', 'NameId', 'INTEGER', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Work', 'DTA\\MetadataBundle\\Model\\Work', RelationMap::MANY_TO_ONE, array('work_id' => 'id', ), null, null);
        $this->addRelation('Author', 'DTA\\MetadataBundle\\Model\\Author', RelationMap::MANY_TO_ONE, array('author_id' => 'id', 'author_person_id' => 'person_id', ), null, null);
    } // buildRelations()

} // AuthorWorkTableMap