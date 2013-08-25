<?php

namespace DTA\MetadataBundle\Model\Master\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'person_publication' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DTA.MetadataBundle.Model.Master.map
 */
class PersonPublicationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Master.map.PersonPublicationTableMap';

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
        $this->setName('person_publication');
        $this->setPhpName('PersonPublication');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Master\\PersonPublication');
        $this->setPackage('src.DTA.MetadataBundle.Model.Master');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('person_publication_id_seq');
        // columns
        $this->addForeignKey('personrole_id', 'PersonroleId', 'INTEGER', 'personrole', 'id', true, null, null);
        $this->addForeignKey('person_id', 'PersonId', 'INTEGER', 'person', 'id', true, null, null);
        $this->addForeignKey('publication_id', 'PublicationId', 'INTEGER', 'publication', 'id', true, null, null);
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Personrole', 'DTA\\MetadataBundle\\Model\\Classification\\Personrole', RelationMap::MANY_TO_ONE, array('personrole_id' => 'id', ), null, null);
        $this->addRelation('Person', 'DTA\\MetadataBundle\\Model\\Data\\Person', RelationMap::MANY_TO_ONE, array('person_id' => 'id', ), null, null);
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Data\\Publication', RelationMap::MANY_TO_ONE, array('publication_id' => 'id', ), null, null);
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
  'PersonroleId' => 'personrole_id',
  'PersonId' => 'person_id',
  'PublicationId' => 'publication_id',
  'Id' => 'id',
),
            'auto_add_pk' =>  array (
  'name' => 'id',
  'autoIncrement' => 'true',
  'type' => 'INTEGER',
),
        );
    } // getBehaviors()

} // PersonPublicationTableMap
