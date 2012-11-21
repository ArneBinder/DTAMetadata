<?php

namespace DTA\MetadataBundle\Model\Description\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'personalName' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DTA.MetadataBundle.Model.Description.map
 */
class PersonalnameTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Description.map.PersonalnameTableMap';

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
        $this->setName('personalName');
        $this->setPhpName('Personalname');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Description\\Personalname');
        $this->setPackage('src.DTA.MetadataBundle.Model.Description');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('PERSON_ID', 'PersonId', 'INTEGER', 'person', 'ID', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Person', 'DTA\\MetadataBundle\\Model\\HistoricalPerson\\Person', RelationMap::MANY_TO_ONE, array('person_id' => 'id', ), null, null);
        $this->addRelation('Namefragment', 'DTA\\MetadataBundle\\Model\\Description\\Namefragment', RelationMap::ONE_TO_MANY, array('id' => 'personalName_id', ), null, null, 'Namefragments');
    } // buildRelations()

} // PersonalnameTableMap
