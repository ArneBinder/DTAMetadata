<?php

namespace DTA\MetadataBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'profil' table.
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
class ProfilTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.ProfilTableMap';

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
        $this->setName('profil');
        $this->setPhpName('Profil');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Profil');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addColumn('email', 'Email', 'VARCHAR', false, 255, null);
        $this->addColumn('telephone', 'Telephone', 'VARCHAR', false, 255, null);
        $this->addForeignPrimaryKey('id', 'Id', 'INTEGER' , 'konto', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Konto', 'DTA\\MetadataBundle\\Model\\Konto', RelationMap::MANY_TO_ONE, array('id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

} // ProfilTableMap
