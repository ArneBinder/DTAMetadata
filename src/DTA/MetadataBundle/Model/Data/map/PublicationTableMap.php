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
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('type', 'Type', 'ENUM', false, null, null);
        $this->getColumn('type', false)->setValueSet(array (
  0 => 'BOOK',
  1 => 'VOLUME',
  2 => 'MULTIVOLUME',
  3 => 'CHAPTER',
  4 => 'JOURNAL',
  5 => 'ARTICLE',
));
        $this->addColumn('legacytype', 'Legacytype', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('title_id', 'TitleId', 'INTEGER', 'title', 'id', true, null, null);
        $this->addColumn('firsteditionpublication_id', 'FirsteditionpublicationId', 'INTEGER', false, null, null);
        $this->addForeignKey('place_id', 'PlaceId', 'INTEGER', 'place', 'id', false, null, null);
        $this->addForeignKey('publicationdate_id', 'PublicationdateId', 'INTEGER', 'datespecification', 'id', false, null, null);
        $this->addForeignKey('creationdate_id', 'CreationdateId', 'INTEGER', 'datespecification', 'id', false, null, null);
        $this->addForeignKey('publishingcompany_id', 'PublishingcompanyId', 'INTEGER', 'publishingcompany', 'id', false, null, null);
        $this->addForeignKey('source_id', 'SourceId', 'INTEGER', 'source', 'id', false, null, null);
        $this->addColumn('legacygenre', 'Legacygenre', 'LONGVARCHAR', false, null, null);
        $this->addColumn('legacysubgenre', 'Legacysubgenre', 'LONGVARCHAR', false, null, null);
        $this->addColumn('dirname', 'Dirname', 'LONGVARCHAR', false, null, null);
        $this->addColumn('usedcopylocation_id', 'UsedcopylocationId', 'INTEGER', false, null, null);
        $this->addColumn('partner_id', 'PartnerId', 'INTEGER', false, null, null);
        $this->addColumn('editiondescription', 'Editiondescription', 'LONGVARCHAR', false, null, null);
        $this->addColumn('digitaleditioneditor', 'Digitaleditioneditor', 'LONGVARCHAR', false, null, null);
        $this->addColumn('transcriptioncomment', 'Transcriptioncomment', 'LONGVARCHAR', false, null, null);
        $this->addColumn('numpages', 'Numpages', 'LONGVARCHAR', false, null, null);
        $this->addColumn('numpagesnumeric', 'Numpagesnumeric', 'INTEGER', false, null, null);
        $this->addColumn('comment', 'Comment', 'LONGVARCHAR', false, null, null);
        $this->addColumn('encoding_comment', 'EncodingComment', 'LONGVARCHAR', false, null, null);
        $this->addColumn('doi', 'Doi', 'LONGVARCHAR', false, null, null);
        $this->addColumn('format', 'Format', 'LONGVARCHAR', false, null, null);
        $this->addColumn('directoryname', 'Directoryname', 'LONGVARCHAR', false, null, null);
        $this->addColumn('wwwready', 'Wwwready', 'INTEGER', false, null, null);
        $this->addForeignKey('last_changed_by_user_id', 'LastChangedByUserId', 'INTEGER', 'dta_user', 'id', false, null, null);
        $this->addColumn('tree_id', 'TreeId', 'INTEGER', false, null, null);
        $this->addColumn('tree_left', 'TreeLeft', 'INTEGER', false, null, null);
        $this->addColumn('tree_right', 'TreeRight', 'INTEGER', false, null, null);
        $this->addColumn('tree_level', 'TreeLevel', 'INTEGER', false, null, null);
        $this->addColumn('publishingcompany_id_is_reconstructed', 'PublishingcompanyIdIsReconstructed', 'BOOLEAN', false, null, false);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Title', 'DTA\\MetadataBundle\\Model\\Data\\Title', RelationMap::MANY_TO_ONE, array('title_id' => 'id', ), null, null);
        $this->addRelation('Source', 'DTA\\MetadataBundle\\Model\\Classification\\Source', RelationMap::MANY_TO_ONE, array('source_id' => 'id', ), null, null);
        $this->addRelation('Publishingcompany', 'DTA\\MetadataBundle\\Model\\Data\\Publishingcompany', RelationMap::MANY_TO_ONE, array('publishingcompany_id' => 'id', ), null, null);
        $this->addRelation('Place', 'DTA\\MetadataBundle\\Model\\Data\\Place', RelationMap::MANY_TO_ONE, array('place_id' => 'id', ), null, null);
        $this->addRelation('DatespecificationRelatedByPublicationdateId', 'DTA\\MetadataBundle\\Model\\Data\\Datespecification', RelationMap::MANY_TO_ONE, array('publicationdate_id' => 'id', ), null, null);
        $this->addRelation('DatespecificationRelatedByCreationdateId', 'DTA\\MetadataBundle\\Model\\Data\\Datespecification', RelationMap::MANY_TO_ONE, array('creationdate_id' => 'id', ), null, null);
        $this->addRelation('LastChangedByUser', 'DTA\\MetadataBundle\\Model\\Master\\DtaUser', RelationMap::MANY_TO_ONE, array('last_changed_by_user_id' => 'id', ), null, null);
        $this->addRelation('MultiVolume', 'DTA\\MetadataBundle\\Model\\Data\\MultiVolume', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'MultiVolumes');
        $this->addRelation('Volume', 'DTA\\MetadataBundle\\Model\\Data\\Volume', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Volumes');
        $this->addRelation('Chapter', 'DTA\\MetadataBundle\\Model\\Data\\Chapter', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Chapters');
        $this->addRelation('Article', 'DTA\\MetadataBundle\\Model\\Data\\Article', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'Articles');
        $this->addRelation('SequenceEntry', 'DTA\\MetadataBundle\\Model\\Master\\SequenceEntry', RelationMap::ONE_TO_MANY, array('id' => 'publication_id', ), null, null, 'SequenceEntries');
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
            'nested_set' =>  array (
  'left_column' => 'tree_left',
  'right_column' => 'tree_right',
  'level_column' => 'tree_level',
  'use_scope' => 'tree_id',
  'scope_column' => 'tree_id',
  'method_proxies' => 'false',
),
            'table_row_view' =>  array (
  'Titel' => 'accessor:getTitle',
  'erster Autor' => 'accessor:getFirstAuthor',
  'Typ' => 'type',
  'Verlag' => 'accessor:getPublishingCompany',
  'veröffentlicht' => 'accessor:getDatespecificationRelatedByPublicationdateId',
  'embedcolumnstitle' => 'title',
  'query' => '\\DTA\\MetadataBundle\\Model\\Data\\PublicationQuery::create()                     ->leftJoinWith(\'Title\')                     ->leftJoinWith(\'Title.Titlefragment\')                     ->leftJoinWith(\'DatespecificationRelatedByPublicationdateId\')                     ->leftJoinWith(\'PersonPublication\')                     ->leftJoinWith(\'PersonPublication.Person\')                     ->leftJoinWith(\'Person.Personalname\')                     ->leftJoinWith(\'Personalname.Namefragment\');',
),
            'reconstructed_flaggable' =>  array (
  'column' => 'publishingcompany_id',
),
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
        );
    } // getBehaviors()

} // PublicationTableMap
