<?php

namespace DTA\MetadataBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'relatedSet' table.
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
class RelatedsetTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.RelatedsetTableMap';

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
        $this->setName('relatedSet');
        $this->setPhpName('Relatedset');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Relatedset');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'LONGVARCHAR', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Publication', RelationMap::ONE_TO_MANY, array('id' => 'relatedSet_id', ), null, null, 'Publications');
        $this->addRelation('Monograph', 'DTA\\MetadataBundle\\Model\\Monograph', RelationMap::ONE_TO_MANY, array('id' => 'relatedSet_id', ), null, null, 'Monographs');
        $this->addRelation('Essay', 'DTA\\MetadataBundle\\Model\\Essay', RelationMap::ONE_TO_MANY, array('id' => 'relatedSet_id', ), null, null, 'Essays');
        $this->addRelation('Magazine', 'DTA\\MetadataBundle\\Model\\Magazine', RelationMap::ONE_TO_MANY, array('id' => 'relatedSet_id', ), null, null, 'Magazines');
        $this->addRelation('Series', 'DTA\\MetadataBundle\\Model\\Series', RelationMap::ONE_TO_MANY, array('id' => 'relatedSet_id', ), null, null, 'Series');
    } // buildRelations()

} // RelatedsetTableMap
