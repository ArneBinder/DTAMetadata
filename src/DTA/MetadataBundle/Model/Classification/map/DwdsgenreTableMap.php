<?php

namespace DTA\MetadataBundle\Model\Classification\map;

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
 * @package    propel.generator.src.Classification.map
 */
class DwdsgenreTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Classification.map.DwdsgenreTableMap';

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
        $this->setClassname('DTA\\MetadataBundle\\Model\\Classification\\Dwdsgenre');
        $this->setPackage('src.Classification');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('NAME', 'Name', 'LONGVARCHAR', true, null, null);
        $this->addForeignKey('CHILDOF', 'Childof', 'INTEGER', 'dwdsGenre', 'ID', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('DwdsgenreRelatedByChildof', 'DTA\\MetadataBundle\\Model\\Classification\\Dwdsgenre', RelationMap::MANY_TO_ONE, array('childOf' => 'id', ), null, null);
        $this->addRelation('DwdsgenreRelatedById', 'DTA\\MetadataBundle\\Model\\Classification\\Dwdsgenre', RelationMap::ONE_TO_MANY, array('id' => 'childOf', ), null, null, 'DwdsgenresRelatedById');
        $this->addRelation('WorkRelatedByDwdsgenreId', 'DTA\\MetadataBundle\\Model\\Publication\\Work', RelationMap::ONE_TO_MANY, array('id' => 'dwdsGenre_id', ), null, null, 'WorksRelatedByDwdsgenreId');
        $this->addRelation('WorkRelatedByDwdssubgenreId', 'DTA\\MetadataBundle\\Model\\Publication\\Work', RelationMap::ONE_TO_MANY, array('id' => 'dwdsSubgenre_id', ), null, null, 'WorksRelatedByDwdssubgenreId');
    } // buildRelations()

} // DwdsgenreTableMap
