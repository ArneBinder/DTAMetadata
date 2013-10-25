<?php

namespace DTA\MetadataBundle\Model\Data\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'publication' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DTA.MetadataBundle.Model.Data.map
 */
class PublicationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DTA.MetadataBundle.Model.Data.map.PublicationTableMap';

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
        $this->setName('publication');
        $this->setPhpName('Publication');
        $this->setClassname('DTA\\MetadataBundle\\Model\\Data\\Publication');
        $this->setPackage('src.DTA.MetadataBundle.Model.Data');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('publication_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('wwwReady', 'Wwwready', 'INTEGER', false, null, null);
        $this->addForeignKey('work_id', 'WorkId', 'INTEGER', 'work', 'id', true, null, null);
        $this->addForeignKey('place_id', 'PlaceId', 'INTEGER', 'place', 'id', false, null, null);
        $this->addForeignKey('publicationdate_id', 'PublicationdateId', 'INTEGER', 'datespecification', 'id', false, null, null);
        $this->addForeignKey('firstpublicationdate_id', 'FirstpublicationdateId', 'INTEGER', 'datespecification', 'id', false, null, null);
        $this->addForeignKey('publishingcompany_id', 'PublishingcompanyId', 'INTEGER', 'publishingcompany', 'id', false, null, null);
        $this->addColumn('partner_id', 'PartnerId', 'INTEGER', false, null, null);
        $this->addColumn('editiondescription', 'Editiondescription', 'LONGVARCHAR', false, null, null);
        $this->addColumn('digitaleditioneditor', 'Digitaleditioneditor', 'LONGVARCHAR', false, null, null);
        $this->addColumn('transcriptioncomment', 'Transcriptioncomment', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('font_id', 'FontId', 'INTEGER', 'font', 'id', false, null, null);
        $this->addColumn('volume_alphanumeric', 'VolumeAlphanumeric', 'LONGVARCHAR', false, null, null);
        $this->addColumn('volume_numeric', 'VolumeNumeric', 'LONGVARCHAR', false, null, null);
        $this->addColumn('volumes_total', 'VolumesTotal', 'LONGVARCHAR', false, null, null);
        $this->addColumn('numpages', 'Numpages', 'INTEGER', false, null, null);
        $this->addColumn('numpagesnormed', 'Numpagesnormed', 'INTEGER', false, null, null);
        $this->addColumn('comment', 'Comment', 'LONGVARCHAR', false, null, null);
        $this->addColumn('publishingcompany_id_is_reconstructed', 'PublishingcompanyIdIsReconstructed', 'BOOLEAN', false, null, false);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Work', 'DTA\\MetadataBundle\\Model\\Data\\Work', RelationMap::MANY_TO_ONE, array('work_id' => 'id', ), null, null);
        $this->addRelation('Publishingcompany', 'DTA\\MetadataBundle\\Model\\Data\\Publishingcompany', RelationMap::MANY_TO_ONE, array('publishingcompany_id' => 'id', ), null, null);
        $this->addRelation('Place', 'DTA\\MetadataBundle\\Model\\Data\\Place', RelationMap::MANY_TO_ONE, array('place_id' => 'id', ), null, null);
        $this->addRelation('DatespecificationRelatedByPublicationdateId', 'DTA\\MetadataBundle\\Model\\Data\\Datespecification', RelationMap::MANY_TO_ONE, array('publicationdate_id' => 'id', ), null, null);
        $this->addRelation('DatespecificationRelatedByFirstpublicationdateId', 'DTA\\MetadataBundle\\Model\\Data\\Datespecification', RelationMap::MANY_TO_ONE, array('firstpublicationdate_id' => 'id', ), null, null);
        $this->addRelation('Font', 'DTA\\MetadataBundle\\Model\\Data\\Font', RelationMap::MANY_TO_ONE, array('font_id' => 'id', ), null, null);
        $this->addRelation('PublicationM', 'DTA\\MetadataBundle\\Model\\Data\\PublicationM', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationMs');
        $this->addRelation('PublicationDm', 'DTA\\MetadataBundle\\Model\\Data\\PublicationDm', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationDms');
        $this->addRelation('PublicationMm', 'DTA\\MetadataBundle\\Model\\Data\\PublicationMm', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationMms');
        $this->addRelation('PublicationDs', 'DTA\\MetadataBundle\\Model\\Data\\PublicationDs', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationDss');
        $this->addRelation('PublicationMs', 'DTA\\MetadataBundle\\Model\\Data\\PublicationMs', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationMss');
        $this->addRelation('PublicationJa', 'DTA\\MetadataBundle\\Model\\Data\\PublicationJa', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationJas');
        $this->addRelation('PublicationMms', 'DTA\\MetadataBundle\\Model\\Data\\PublicationMms', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationMmss');
        $this->addRelation('PublicationJ', 'DTA\\MetadataBundle\\Model\\Data\\PublicationJ', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationJs');
        $this->addRelation('PublicationPublicationgroup', 'DTA\\MetadataBundle\\Model\\Master\\PublicationPublicationgroup', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationPublicationgroups');
        $this->addRelation('PersonPublication', 'DTA\\MetadataBundle\\Model\\Master\\PersonPublication', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PersonPublications');
        $this->addRelation('Task', 'DTA\\MetadataBundle\\Model\\Workflow\\Task', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Tasks');
        $this->addRelation('Imagesource', 'DTA\\MetadataBundle\\Model\\Workflow\\Imagesource', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Imagesources');
        $this->addRelation('Textsource', 'DTA\\MetadataBundle\\Model\\Workflow\\Textsource', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Textsources');
        $this->addRelation('Publicationgroup', 'DTA\\MetadataBundle\\Model\\Workflow\\Publicationgroup', RelationMap::MANY_TO_MANY, array(), null, null, 'Publicationgroups');
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
  'embedColumnsForWork' => 'work',
  'veröffentlicht' => 'accessor:getDatespecificationRelatedByPublicationdateId',
  'embedcolumnstitle' => 'title',
),
            'reconstructed_flaggable' =>  array (
  'column' => 'publishingcompany_id',
),
        );
    } // getBehaviors()

} // PublicationTableMap
