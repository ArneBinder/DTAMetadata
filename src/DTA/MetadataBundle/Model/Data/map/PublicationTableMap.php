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
        $this->addForeignKey('title_id', 'TitleId', 'INTEGER', 'title', 'id', true, null, null);
        $this->addColumn('firsteditionpublication_id', 'FirsteditionpublicationId', 'INTEGER', false, null, null);
        $this->addForeignKey('place_id', 'PlaceId', 'INTEGER', 'place', 'id', false, null, null);
        $this->addForeignKey('publicationdate_id', 'PublicationdateId', 'INTEGER', 'datespecification', 'id', false, null, null);
        $this->addForeignKey('creationdate_id', 'CreationdateId', 'INTEGER', 'datespecification', 'id', false, null, null);
        $this->addForeignKey('publishingcompany_id', 'PublishingcompanyId', 'INTEGER', 'publishingcompany', 'id', false, null, null);
        $this->addColumn('partner_id', 'PartnerId', 'INTEGER', false, null, null);
        $this->addColumn('editiondescription', 'Editiondescription', 'LONGVARCHAR', false, null, null);
        $this->addColumn('digitaleditioneditor', 'Digitaleditioneditor', 'LONGVARCHAR', false, null, null);
        $this->addColumn('transcriptioncomment', 'Transcriptioncomment', 'LONGVARCHAR', false, null, null);
        $this->addColumn('numpages', 'Numpages', 'LONGVARCHAR', false, null, null);
        $this->addColumn('numpagesnumeric', 'Numpagesnumeric', 'INTEGER', false, null, null);
        $this->addColumn('comment', 'Comment', 'LONGVARCHAR', false, null, null);
        $this->addColumn('doi', 'Doi', 'LONGVARCHAR', false, null, null);
        $this->addColumn('format', 'Format', 'LONGVARCHAR', false, null, null);
        $this->addColumn('directoryname', 'Directoryname', 'LONGVARCHAR', false, null, null);
        $this->addColumn('wwwready', 'Wwwready', 'INTEGER', false, null, null);
        $this->addColumn('legacy_book_id', 'LegacyBookId', 'INTEGER', false, null, null);
        $this->addColumn('publishingcompany_id_is_reconstructed', 'PublishingcompanyIdIsReconstructed', 'BOOLEAN', false, null, false);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Title', 'DTA\\MetadataBundle\\Model\\Data\\Title', RelationMap::MANY_TO_ONE, array('title_id' => 'id', ), null, null);
        $this->addRelation('Publishingcompany', 'DTA\\MetadataBundle\\Model\\Data\\Publishingcompany', RelationMap::MANY_TO_ONE, array('publishingcompany_id' => 'id', ), null, null);
        $this->addRelation('Place', 'DTA\\MetadataBundle\\Model\\Data\\Place', RelationMap::MANY_TO_ONE, array('place_id' => 'id', ), null, null);
        $this->addRelation('DatespecificationRelatedByPublicationdateId', 'DTA\\MetadataBundle\\Model\\Data\\Datespecification', RelationMap::MANY_TO_ONE, array('publicationdate_id' => 'id', ), null, null);
        $this->addRelation('DatespecificationRelatedByCreationdateId', 'DTA\\MetadataBundle\\Model\\Data\\Datespecification', RelationMap::MANY_TO_ONE, array('creationdate_id' => 'id', ), null, null);
        $this->addRelation('PublicationM', 'DTA\\MetadataBundle\\Model\\Data\\PublicationM', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationMs');
        $this->addRelation('PublicationDm', 'DTA\\MetadataBundle\\Model\\Data\\PublicationDm', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationDms');
        $this->addRelation('PublicationDs', 'DTA\\MetadataBundle\\Model\\Data\\PublicationDs', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationDss');
        $this->addRelation('PublicationMs', 'DTA\\MetadataBundle\\Model\\Data\\PublicationMs', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationMss');
        $this->addRelation('PublicationJa', 'DTA\\MetadataBundle\\Model\\Data\\PublicationJa', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationJas');
        $this->addRelation('PublicationMms', 'DTA\\MetadataBundle\\Model\\Data\\PublicationMms', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationMmss');
        $this->addRelation('PublicationJ', 'DTA\\MetadataBundle\\Model\\Data\\PublicationJ', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationJs');
        $this->addRelation('VolumeRelatedByPublicationId', 'DTA\\MetadataBundle\\Model\\Data\\Volume', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'VolumesRelatedByPublicationId');
        $this->addRelation('VolumeRelatedByParentpublicationId', 'DTA\\MetadataBundle\\Model\\Data\\Volume', RelationMap::ONE_TO_MANY, array('id' => 'parentpublication_id', ), null, null, 'VolumesRelatedByParentpublicationId');
        $this->addRelation('LanguagePublication', 'DTA\\MetadataBundle\\Model\\Master\\LanguagePublication', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'LanguagePublications');
        $this->addRelation('GenrePublication', 'DTA\\MetadataBundle\\Model\\Master\\GenrePublication', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'GenrePublications');
        $this->addRelation('PublicationTag', 'DTA\\MetadataBundle\\Model\\Master\\PublicationTag', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationTags');
        $this->addRelation('CategoryPublication', 'DTA\\MetadataBundle\\Model\\Master\\CategoryPublication', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'CategoryPublications');
        $this->addRelation('FontPublication', 'DTA\\MetadataBundle\\Model\\Master\\FontPublication', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'FontPublications');
        $this->addRelation('PublicationPublicationgroup', 'DTA\\MetadataBundle\\Model\\Master\\PublicationPublicationgroup', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PublicationPublicationgroups');
        $this->addRelation('PersonPublication', 'DTA\\MetadataBundle\\Model\\Master\\PersonPublication', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'PersonPublications');
        $this->addRelation('RecentUse', 'DTA\\MetadataBundle\\Model\\Master\\RecentUse', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'RecentUses');
        $this->addRelation('Task', 'DTA\\MetadataBundle\\Model\\Workflow\\Task', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Tasks');
        $this->addRelation('CopyLocation', 'DTA\\MetadataBundle\\Model\\Workflow\\CopyLocation', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'CopyLocations');
        $this->addRelation('Imagesource', 'DTA\\MetadataBundle\\Model\\Workflow\\Imagesource', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Imagesources');
        $this->addRelation('Textsource', 'DTA\\MetadataBundle\\Model\\Workflow\\Textsource', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Textsources');
        $this->addRelation('Language', 'DTA\\MetadataBundle\\Model\\Data\\Language', RelationMap::MANY_TO_MANY, array(), null, null, 'Languages');
        $this->addRelation('Genre', 'DTA\\MetadataBundle\\Model\\Classification\\Genre', RelationMap::MANY_TO_MANY, array(), null, null, 'Genres');
        $this->addRelation('Tag', 'DTA\\MetadataBundle\\Model\\Classification\\Tag', RelationMap::MANY_TO_MANY, array(), null, null, 'Tags');
        $this->addRelation('Category', 'DTA\\MetadataBundle\\Model\\Classification\\Category', RelationMap::MANY_TO_MANY, array(), null, null, 'Categories');
        $this->addRelation('Font', 'DTA\\MetadataBundle\\Model\\Data\\Font', RelationMap::MANY_TO_MANY, array(), null, null, 'Fonts');
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
  'Titel' => 'accessor:getTitle',
  'erster Autor' => 'accessor:getFirstAuthor',
  'entstanden' => 'accessor:getDatespecification',
  'veröffentlicht' => 'accessor:getDatespecificationRelatedByPublicationdateId',
  'embedcolumnstitle' => 'title',
),
            'reconstructed_flaggable' =>  array (
  'column' => 'publishingcompany_id',
),
        );
    } // getBehaviors()

} // PublicationTableMap
