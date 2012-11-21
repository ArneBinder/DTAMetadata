<?php

namespace DTA\MetadataBundle\Model\Description\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'titleFragmentType' table.
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
class TitlefragmenttypeTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Description.map.TitlefragmenttypeTableMap';

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
        $this->setName('titleFragmentType');
        $this->setPhpName('Titlefragmenttype');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Description\\Titlefragmenttype');
        $this->setPackage('src.DTA.MetadataBundle.Model.Description');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('NAME', 'Name', 'LONGVARCHAR', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Titlefragment', 'DTA\\MetadataBundle\\Model\\Description\\Titlefragment', RelationMap::ONE_TO_MANY, array('id' => 'titleFragmentType_id', ), null, null, 'Titlefragments');
    } // buildRelations()

} // TitlefragmenttypeTableMap
