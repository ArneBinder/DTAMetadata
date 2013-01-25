<?php

namespace DTA\MetadataBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'publication_publicationGroup' table.
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
class PublicationPublicationgroupTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.map.PublicationPublicationgroupTableMap';

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
        $this->setName('publication_publicationGroup');
        $this->setPhpName('PublicationPublicationgroup');
        $this->setClassname('DTA\\MetadataBundle\\Model\\PublicationPublicationgroup');
        $this->setPackage('src.DTA.MetadataBundle.Model');
        $this->setUseIdGenerator(false);
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignPrimaryKey('publicationGroup_id', 'PublicationgroupId', 'INTEGER' , 'publicationGroup', 'id', true, null, null);
        $this->addForeignPrimaryKey('publication_id', 'PublicationId', 'INTEGER' , 'publication', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Publicationgroup', 'DTA\\MetadataBundle\\Model\\Publicationgroup', RelationMap::MANY_TO_ONE, array('publicationGroup_id' => 'id', ), null, null);
        $this->addRelation('Publication', 'DTA\\MetadataBundle\\Model\\Publication', RelationMap::MANY_TO_ONE, array('publication_id' => 'id', ), null, null);
    } // buildRelations()

} // PublicationPublicationgroupTableMap
