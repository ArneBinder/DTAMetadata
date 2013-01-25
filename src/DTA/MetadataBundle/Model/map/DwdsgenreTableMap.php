<?php

namespace DTA\MetadataBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'dwdsGenre' table.
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
class DwdsgenreTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.DwdsgenreTableMap';

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
        $this->setName('dwdsGenre');
        $this->setPhpName('Dwdsgenre');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Dwdsgenre');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'LONGVARCHAR', true, null, null);
        $this->addForeignKey('childOf', 'Childof', 'INTEGER', 'dwdsGenre', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('DwdsgenreRelatedByChildof', 'DTA\\MetadataBundle\\Model\\Dwdsgenre', RelationMap::MANY_TO_ONE, array('childOf' => 'id', ), null, null);
        $this->addRelation('WorkRelatedByDwdsgenreId', 'DTA\\MetadataBundle\\Model\\Work', RelationMap::ONE_TO_MANY, array('id' => 'dwdsGenre_id', ), null, null, 'WorksRelatedByDwdsgenreId');
        $this->addRelation('WorkRelatedByDwdssubgenreId', 'DTA\\MetadataBundle\\Model\\Work', RelationMap::ONE_TO_MANY, array('id' => 'dwdsSubgenre_id', ), null, null, 'WorksRelatedByDwdssubgenreId');
        $this->addRelation('DwdsgenreRelatedById', 'DTA\\MetadataBundle\\Model\\Dwdsgenre', RelationMap::ONE_TO_MANY, array('id' => 'childOf', ), null, null, 'DwdsgenresRelatedById');
    } // buildRelations()

} // DwdsgenreTableMap
