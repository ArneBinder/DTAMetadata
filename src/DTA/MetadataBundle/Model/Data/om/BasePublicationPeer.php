<?php

namespace DTA\MetadataBundle\Model\Data\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use DTA\MetadataBundle\Model\Classification\SourcePeer;
use DTA\MetadataBundle\Model\Data\DatespecificationPeer;
use DTA\MetadataBundle\Model\Data\PlacePeer;
use DTA\MetadataBundle\Model\Data\Publication;
use DTA\MetadataBundle\Model\Data\PublicationPeer;
use DTA\MetadataBundle\Model\Data\PublishingcompanyPeer;
use DTA\MetadataBundle\Model\Data\TitlePeer;
use DTA\MetadataBundle\Model\Data\map\PublicationTableMap;
use DTA\MetadataBundle\Model\Master\DtaUserPeer;

abstract class BasePublicationPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'dtametadata';

    /** the table name for this class */
    const TABLE_NAME = 'publication';

    /** the related Propel class for this table */
    const OM_CLASS = 'DTA\\MetadataBundle\\Model\\Data\\Publication';

    /** the related TableMap class for this table */
    const TM_CLASS = 'DTA\\MetadataBundle\\Model\\Data\\map\\PublicationTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 34;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 34;

    /** the column name for the id field */
    const ID = 'publication.id';

    /** the column name for the type field */
    const TYPE = 'publication.type';

    /** the column name for the legacytype field */
    const LEGACYTYPE = 'publication.legacytype';

    /** the column name for the title_id field */
    const TITLE_ID = 'publication.title_id';

    /** the column name for the firsteditionpublication_id field */
    const FIRSTEDITIONPUBLICATION_ID = 'publication.firsteditionpublication_id';

    /** the column name for the place_id field */
    const PLACE_ID = 'publication.place_id';

    /** the column name for the publicationdate_id field */
    const PUBLICATIONDATE_ID = 'publication.publicationdate_id';

    /** the column name for the creationdate_id field */
    const CREATIONDATE_ID = 'publication.creationdate_id';

    /** the column name for the publishingcompany_id field */
    const PUBLISHINGCOMPANY_ID = 'publication.publishingcompany_id';

    /** the column name for the source_id field */
    const SOURCE_ID = 'publication.source_id';

    /** the column name for the legacygenre field */
    const LEGACYGENRE = 'publication.legacygenre';

    /** the column name for the legacysubgenre field */
    const LEGACYSUBGENRE = 'publication.legacysubgenre';

    /** the column name for the dirname field */
    const DIRNAME = 'publication.dirname';

    /** the column name for the usedcopylocation_id field */
    const USEDCOPYLOCATION_ID = 'publication.usedcopylocation_id';

    /** the column name for the partner_id field */
    const PARTNER_ID = 'publication.partner_id';

    /** the column name for the editiondescription field */
    const EDITIONDESCRIPTION = 'publication.editiondescription';

    /** the column name for the digitaleditioneditor field */
    const DIGITALEDITIONEDITOR = 'publication.digitaleditioneditor';

    /** the column name for the transcriptioncomment field */
    const TRANSCRIPTIONCOMMENT = 'publication.transcriptioncomment';

    /** the column name for the numpages field */
    const NUMPAGES = 'publication.numpages';

    /** the column name for the numpagesnumeric field */
    const NUMPAGESNUMERIC = 'publication.numpagesnumeric';

    /** the column name for the comment field */
    const COMMENT = 'publication.comment';

    /** the column name for the encoding_comment field */
    const ENCODING_COMMENT = 'publication.encoding_comment';

    /** the column name for the doi field */
    const DOI = 'publication.doi';

    /** the column name for the format field */
    const FORMAT = 'publication.format';

    /** the column name for the directoryname field */
    const DIRECTORYNAME = 'publication.directoryname';

    /** the column name for the wwwready field */
    const WWWREADY = 'publication.wwwready';

    /** the column name for the last_changed_by_user_id field */
    const LAST_CHANGED_BY_USER_ID = 'publication.last_changed_by_user_id';

    /** the column name for the tree_id field */
    const TREE_ID = 'publication.tree_id';

    /** the column name for the tree_left field */
    const TREE_LEFT = 'publication.tree_left';

    /** the column name for the tree_right field */
    const TREE_RIGHT = 'publication.tree_right';

    /** the column name for the tree_level field */
    const TREE_LEVEL = 'publication.tree_level';

    /** the column name for the publishingcompany_id_is_reconstructed field */
    const PUBLISHINGCOMPANY_ID_IS_RECONSTRUCTED = 'publication.publishingcompany_id_is_reconstructed';

    /** the column name for the created_at field */
    const CREATED_AT = 'publication.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'publication.updated_at';

    /** The enumerated values for the type field */
    const TYPE_BOOK = 'BOOK';
    const TYPE_VOLUME = 'VOLUME';
    const TYPE_MULTIVOLUME = 'MULTIVOLUME';
    const TYPE_CHAPTER = 'CHAPTER';
    const TYPE_JOURNAL = 'JOURNAL';
    const TYPE_ARTICLE = 'ARTICLE';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of Publication objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Publication[]
     */
    public static $instances = array();


    // nested_set behavior

    /**
     * Left column for the set
     */
    const LEFT_COL = 'publication.tree_left';

    /**
     * Right column for the set
     */
    const RIGHT_COL = 'publication.tree_right';

    /**
     * Level column for the set
     */
    const LEVEL_COL = 'publication.tree_level';

    /**
     * Scope column for the set
     */
    const SCOPE_COL = 'publication.tree_id';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. PublicationPeer::$fieldNames[PublicationPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Type', 'Legacytype', 'TitleId', 'FirsteditionpublicationId', 'PlaceId', 'PublicationdateId', 'CreationdateId', 'PublishingcompanyId', 'SourceId', 'Legacygenre', 'Legacysubgenre', 'Dirname', 'UsedcopylocationId', 'PartnerId', 'Editiondescription', 'Digitaleditioneditor', 'Transcriptioncomment', 'Numpages', 'Numpagesnumeric', 'Comment', 'EncodingComment', 'Doi', 'Format', 'Directoryname', 'Wwwready', 'LastChangedByUserId', 'TreeId', 'TreeLeft', 'TreeRight', 'TreeLevel', 'PublishingcompanyIdIsReconstructed', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'type', 'legacytype', 'titleId', 'firsteditionpublicationId', 'placeId', 'publicationdateId', 'creationdateId', 'publishingcompanyId', 'sourceId', 'legacygenre', 'legacysubgenre', 'dirname', 'usedcopylocationId', 'partnerId', 'editiondescription', 'digitaleditioneditor', 'transcriptioncomment', 'numpages', 'numpagesnumeric', 'comment', 'encodingComment', 'doi', 'format', 'directoryname', 'wwwready', 'lastChangedByUserId', 'treeId', 'treeLeft', 'treeRight', 'treeLevel', 'publishingcompanyIdIsReconstructed', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (PublicationPeer::ID, PublicationPeer::TYPE, PublicationPeer::LEGACYTYPE, PublicationPeer::TITLE_ID, PublicationPeer::FIRSTEDITIONPUBLICATION_ID, PublicationPeer::PLACE_ID, PublicationPeer::PUBLICATIONDATE_ID, PublicationPeer::CREATIONDATE_ID, PublicationPeer::PUBLISHINGCOMPANY_ID, PublicationPeer::SOURCE_ID, PublicationPeer::LEGACYGENRE, PublicationPeer::LEGACYSUBGENRE, PublicationPeer::DIRNAME, PublicationPeer::USEDCOPYLOCATION_ID, PublicationPeer::PARTNER_ID, PublicationPeer::EDITIONDESCRIPTION, PublicationPeer::DIGITALEDITIONEDITOR, PublicationPeer::TRANSCRIPTIONCOMMENT, PublicationPeer::NUMPAGES, PublicationPeer::NUMPAGESNUMERIC, PublicationPeer::COMMENT, PublicationPeer::ENCODING_COMMENT, PublicationPeer::DOI, PublicationPeer::FORMAT, PublicationPeer::DIRECTORYNAME, PublicationPeer::WWWREADY, PublicationPeer::LAST_CHANGED_BY_USER_ID, PublicationPeer::TREE_ID, PublicationPeer::TREE_LEFT, PublicationPeer::TREE_RIGHT, PublicationPeer::TREE_LEVEL, PublicationPeer::PUBLISHINGCOMPANY_ID_IS_RECONSTRUCTED, PublicationPeer::CREATED_AT, PublicationPeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'TYPE', 'LEGACYTYPE', 'TITLE_ID', 'FIRSTEDITIONPUBLICATION_ID', 'PLACE_ID', 'PUBLICATIONDATE_ID', 'CREATIONDATE_ID', 'PUBLISHINGCOMPANY_ID', 'SOURCE_ID', 'LEGACYGENRE', 'LEGACYSUBGENRE', 'DIRNAME', 'USEDCOPYLOCATION_ID', 'PARTNER_ID', 'EDITIONDESCRIPTION', 'DIGITALEDITIONEDITOR', 'TRANSCRIPTIONCOMMENT', 'NUMPAGES', 'NUMPAGESNUMERIC', 'COMMENT', 'ENCODING_COMMENT', 'DOI', 'FORMAT', 'DIRECTORYNAME', 'WWWREADY', 'LAST_CHANGED_BY_USER_ID', 'TREE_ID', 'TREE_LEFT', 'TREE_RIGHT', 'TREE_LEVEL', 'PUBLISHINGCOMPANY_ID_IS_RECONSTRUCTED', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'type', 'legacytype', 'title_id', 'firsteditionpublication_id', 'place_id', 'publicationdate_id', 'creationdate_id', 'publishingcompany_id', 'source_id', 'legacygenre', 'legacysubgenre', 'dirname', 'usedcopylocation_id', 'partner_id', 'editiondescription', 'digitaleditioneditor', 'transcriptioncomment', 'numpages', 'numpagesnumeric', 'comment', 'encoding_comment', 'doi', 'format', 'directoryname', 'wwwready', 'last_changed_by_user_id', 'tree_id', 'tree_left', 'tree_right', 'tree_level', 'publishingcompany_id_is_reconstructed', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. PublicationPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Type' => 1, 'Legacytype' => 2, 'TitleId' => 3, 'FirsteditionpublicationId' => 4, 'PlaceId' => 5, 'PublicationdateId' => 6, 'CreationdateId' => 7, 'PublishingcompanyId' => 8, 'SourceId' => 9, 'Legacygenre' => 10, 'Legacysubgenre' => 11, 'Dirname' => 12, 'UsedcopylocationId' => 13, 'PartnerId' => 14, 'Editiondescription' => 15, 'Digitaleditioneditor' => 16, 'Transcriptioncomment' => 17, 'Numpages' => 18, 'Numpagesnumeric' => 19, 'Comment' => 20, 'EncodingComment' => 21, 'Doi' => 22, 'Format' => 23, 'Directoryname' => 24, 'Wwwready' => 25, 'LastChangedByUserId' => 26, 'TreeId' => 27, 'TreeLeft' => 28, 'TreeRight' => 29, 'TreeLevel' => 30, 'PublishingcompanyIdIsReconstructed' => 31, 'CreatedAt' => 32, 'UpdatedAt' => 33, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'type' => 1, 'legacytype' => 2, 'titleId' => 3, 'firsteditionpublicationId' => 4, 'placeId' => 5, 'publicationdateId' => 6, 'creationdateId' => 7, 'publishingcompanyId' => 8, 'sourceId' => 9, 'legacygenre' => 10, 'legacysubgenre' => 11, 'dirname' => 12, 'usedcopylocationId' => 13, 'partnerId' => 14, 'editiondescription' => 15, 'digitaleditioneditor' => 16, 'transcriptioncomment' => 17, 'numpages' => 18, 'numpagesnumeric' => 19, 'comment' => 20, 'encodingComment' => 21, 'doi' => 22, 'format' => 23, 'directoryname' => 24, 'wwwready' => 25, 'lastChangedByUserId' => 26, 'treeId' => 27, 'treeLeft' => 28, 'treeRight' => 29, 'treeLevel' => 30, 'publishingcompanyIdIsReconstructed' => 31, 'createdAt' => 32, 'updatedAt' => 33, ),
        BasePeer::TYPE_COLNAME => array (PublicationPeer::ID => 0, PublicationPeer::TYPE => 1, PublicationPeer::LEGACYTYPE => 2, PublicationPeer::TITLE_ID => 3, PublicationPeer::FIRSTEDITIONPUBLICATION_ID => 4, PublicationPeer::PLACE_ID => 5, PublicationPeer::PUBLICATIONDATE_ID => 6, PublicationPeer::CREATIONDATE_ID => 7, PublicationPeer::PUBLISHINGCOMPANY_ID => 8, PublicationPeer::SOURCE_ID => 9, PublicationPeer::LEGACYGENRE => 10, PublicationPeer::LEGACYSUBGENRE => 11, PublicationPeer::DIRNAME => 12, PublicationPeer::USEDCOPYLOCATION_ID => 13, PublicationPeer::PARTNER_ID => 14, PublicationPeer::EDITIONDESCRIPTION => 15, PublicationPeer::DIGITALEDITIONEDITOR => 16, PublicationPeer::TRANSCRIPTIONCOMMENT => 17, PublicationPeer::NUMPAGES => 18, PublicationPeer::NUMPAGESNUMERIC => 19, PublicationPeer::COMMENT => 20, PublicationPeer::ENCODING_COMMENT => 21, PublicationPeer::DOI => 22, PublicationPeer::FORMAT => 23, PublicationPeer::DIRECTORYNAME => 24, PublicationPeer::WWWREADY => 25, PublicationPeer::LAST_CHANGED_BY_USER_ID => 26, PublicationPeer::TREE_ID => 27, PublicationPeer::TREE_LEFT => 28, PublicationPeer::TREE_RIGHT => 29, PublicationPeer::TREE_LEVEL => 30, PublicationPeer::PUBLISHINGCOMPANY_ID_IS_RECONSTRUCTED => 31, PublicationPeer::CREATED_AT => 32, PublicationPeer::UPDATED_AT => 33, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'TYPE' => 1, 'LEGACYTYPE' => 2, 'TITLE_ID' => 3, 'FIRSTEDITIONPUBLICATION_ID' => 4, 'PLACE_ID' => 5, 'PUBLICATIONDATE_ID' => 6, 'CREATIONDATE_ID' => 7, 'PUBLISHINGCOMPANY_ID' => 8, 'SOURCE_ID' => 9, 'LEGACYGENRE' => 10, 'LEGACYSUBGENRE' => 11, 'DIRNAME' => 12, 'USEDCOPYLOCATION_ID' => 13, 'PARTNER_ID' => 14, 'EDITIONDESCRIPTION' => 15, 'DIGITALEDITIONEDITOR' => 16, 'TRANSCRIPTIONCOMMENT' => 17, 'NUMPAGES' => 18, 'NUMPAGESNUMERIC' => 19, 'COMMENT' => 20, 'ENCODING_COMMENT' => 21, 'DOI' => 22, 'FORMAT' => 23, 'DIRECTORYNAME' => 24, 'WWWREADY' => 25, 'LAST_CHANGED_BY_USER_ID' => 26, 'TREE_ID' => 27, 'TREE_LEFT' => 28, 'TREE_RIGHT' => 29, 'TREE_LEVEL' => 30, 'PUBLISHINGCOMPANY_ID_IS_RECONSTRUCTED' => 31, 'CREATED_AT' => 32, 'UPDATED_AT' => 33, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'type' => 1, 'legacytype' => 2, 'title_id' => 3, 'firsteditionpublication_id' => 4, 'place_id' => 5, 'publicationdate_id' => 6, 'creationdate_id' => 7, 'publishingcompany_id' => 8, 'source_id' => 9, 'legacygenre' => 10, 'legacysubgenre' => 11, 'dirname' => 12, 'usedcopylocation_id' => 13, 'partner_id' => 14, 'editiondescription' => 15, 'digitaleditioneditor' => 16, 'transcriptioncomment' => 17, 'numpages' => 18, 'numpagesnumeric' => 19, 'comment' => 20, 'encoding_comment' => 21, 'doi' => 22, 'format' => 23, 'directoryname' => 24, 'wwwready' => 25, 'last_changed_by_user_id' => 26, 'tree_id' => 27, 'tree_left' => 28, 'tree_right' => 29, 'tree_level' => 30, 'publishingcompany_id_is_reconstructed' => 31, 'created_at' => 32, 'updated_at' => 33, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
        PublicationPeer::TYPE => array(
            PublicationPeer::TYPE_BOOK,
            PublicationPeer::TYPE_VOLUME,
            PublicationPeer::TYPE_MULTIVOLUME,
            PublicationPeer::TYPE_CHAPTER,
            PublicationPeer::TYPE_JOURNAL,
            PublicationPeer::TYPE_ARTICLE,
        ),
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = PublicationPeer::getFieldNames($toType);
        $key = isset(PublicationPeer::$fieldKeys[$fromType][$name]) ? PublicationPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(PublicationPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, PublicationPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return PublicationPeer::$fieldNames[$type];
    }

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return PublicationPeer::$enumValueSets;
    }

    /**
     * Gets the list of values for an ENUM column
     *
     * @param string $colname The ENUM column name.
     *
     * @return array list of possible values for the column
     */
    public static function getValueSet($colname)
    {
        $valueSets = PublicationPeer::getValueSets();

        if (!isset($valueSets[$colname])) {
            throw new PropelException(sprintf('Column "%s" has no ValueSet.', $colname));
        }

        return $valueSets[$colname];
    }

    /**
     * Gets the SQL value for the ENUM column value
     *
     * @param string $colname ENUM column name.
     * @param string $enumVal ENUM value.
     *
     * @return int SQL value
     */
    public static function getSqlValueForEnum($colname, $enumVal)
    {
        $values = PublicationPeer::getValueSet($colname);
        if (!in_array($enumVal, $values)) {
            throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $colname));
        }

        return array_search($enumVal, $values);
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. PublicationPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(PublicationPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(PublicationPeer::ID);
            $criteria->addSelectColumn(PublicationPeer::TYPE);
            $criteria->addSelectColumn(PublicationPeer::LEGACYTYPE);
            $criteria->addSelectColumn(PublicationPeer::TITLE_ID);
            $criteria->addSelectColumn(PublicationPeer::FIRSTEDITIONPUBLICATION_ID);
            $criteria->addSelectColumn(PublicationPeer::PLACE_ID);
            $criteria->addSelectColumn(PublicationPeer::PUBLICATIONDATE_ID);
            $criteria->addSelectColumn(PublicationPeer::CREATIONDATE_ID);
            $criteria->addSelectColumn(PublicationPeer::PUBLISHINGCOMPANY_ID);
            $criteria->addSelectColumn(PublicationPeer::SOURCE_ID);
            $criteria->addSelectColumn(PublicationPeer::LEGACYGENRE);
            $criteria->addSelectColumn(PublicationPeer::LEGACYSUBGENRE);
            $criteria->addSelectColumn(PublicationPeer::DIRNAME);
            $criteria->addSelectColumn(PublicationPeer::USEDCOPYLOCATION_ID);
            $criteria->addSelectColumn(PublicationPeer::PARTNER_ID);
            $criteria->addSelectColumn(PublicationPeer::EDITIONDESCRIPTION);
            $criteria->addSelectColumn(PublicationPeer::DIGITALEDITIONEDITOR);
            $criteria->addSelectColumn(PublicationPeer::TRANSCRIPTIONCOMMENT);
            $criteria->addSelectColumn(PublicationPeer::NUMPAGES);
            $criteria->addSelectColumn(PublicationPeer::NUMPAGESNUMERIC);
            $criteria->addSelectColumn(PublicationPeer::COMMENT);
            $criteria->addSelectColumn(PublicationPeer::ENCODING_COMMENT);
            $criteria->addSelectColumn(PublicationPeer::DOI);
            $criteria->addSelectColumn(PublicationPeer::FORMAT);
            $criteria->addSelectColumn(PublicationPeer::DIRECTORYNAME);
            $criteria->addSelectColumn(PublicationPeer::WWWREADY);
            $criteria->addSelectColumn(PublicationPeer::LAST_CHANGED_BY_USER_ID);
            $criteria->addSelectColumn(PublicationPeer::TREE_ID);
            $criteria->addSelectColumn(PublicationPeer::TREE_LEFT);
            $criteria->addSelectColumn(PublicationPeer::TREE_RIGHT);
            $criteria->addSelectColumn(PublicationPeer::TREE_LEVEL);
            $criteria->addSelectColumn(PublicationPeer::PUBLISHINGCOMPANY_ID_IS_RECONSTRUCTED);
            $criteria->addSelectColumn(PublicationPeer::CREATED_AT);
            $criteria->addSelectColumn(PublicationPeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.legacytype');
            $criteria->addSelectColumn($alias . '.title_id');
            $criteria->addSelectColumn($alias . '.firsteditionpublication_id');
            $criteria->addSelectColumn($alias . '.place_id');
            $criteria->addSelectColumn($alias . '.publicationdate_id');
            $criteria->addSelectColumn($alias . '.creationdate_id');
            $criteria->addSelectColumn($alias . '.publishingcompany_id');
            $criteria->addSelectColumn($alias . '.source_id');
            $criteria->addSelectColumn($alias . '.legacygenre');
            $criteria->addSelectColumn($alias . '.legacysubgenre');
            $criteria->addSelectColumn($alias . '.dirname');
            $criteria->addSelectColumn($alias . '.usedcopylocation_id');
            $criteria->addSelectColumn($alias . '.partner_id');
            $criteria->addSelectColumn($alias . '.editiondescription');
            $criteria->addSelectColumn($alias . '.digitaleditioneditor');
            $criteria->addSelectColumn($alias . '.transcriptioncomment');
            $criteria->addSelectColumn($alias . '.numpages');
            $criteria->addSelectColumn($alias . '.numpagesnumeric');
            $criteria->addSelectColumn($alias . '.comment');
            $criteria->addSelectColumn($alias . '.encoding_comment');
            $criteria->addSelectColumn($alias . '.doi');
            $criteria->addSelectColumn($alias . '.format');
            $criteria->addSelectColumn($alias . '.directoryname');
            $criteria->addSelectColumn($alias . '.wwwready');
            $criteria->addSelectColumn($alias . '.last_changed_by_user_id');
            $criteria->addSelectColumn($alias . '.tree_id');
            $criteria->addSelectColumn($alias . '.tree_left');
            $criteria->addSelectColumn($alias . '.tree_right');
            $criteria->addSelectColumn($alias . '.tree_level');
            $criteria->addSelectColumn($alias . '.publishingcompany_id_is_reconstructed');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(PublicationPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return Publication
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = PublicationPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return PublicationPeer::populateObjects(PublicationPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            PublicationPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param Publication $obj A Publication object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            PublicationPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A Publication object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Publication) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Publication object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(PublicationPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return Publication Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(PublicationPeer::$instances[$key])) {
                return PublicationPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (PublicationPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        PublicationPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to publication
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = PublicationPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = PublicationPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PublicationPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (Publication object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = PublicationPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = PublicationPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + PublicationPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PublicationPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            PublicationPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * Gets the SQL value for Type ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getTypeSqlValue($enumVal)
    {
        return PublicationPeer::getSqlValueForEnum(PublicationPeer::TYPE, $enumVal);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Title table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinTitle(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Source table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinSource(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Publishingcompany table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPublishingcompany(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Place table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPlace(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related DatespecificationRelatedByPublicationdateId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinDatespecificationRelatedByPublicationdateId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PublicationPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related DatespecificationRelatedByCreationdateId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinDatespecificationRelatedByCreationdateId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PublicationPeer::CREATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related LastChangedByUser table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinLastChangedByUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of Publication objects pre-filled with their Title objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Publication objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinTitle(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PublicationPeer::DATABASE_NAME);
        }

        PublicationPeer::addSelectColumns($criteria);
        $startcol = PublicationPeer::NUM_HYDRATE_COLUMNS;
        TitlePeer::addSelectColumns($criteria);

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PublicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PublicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PublicationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = TitlePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = TitlePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    TitlePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Publication) to $obj2 (Title)
                $obj2->addPublication($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Publication objects pre-filled with their Source objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Publication objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinSource(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PublicationPeer::DATABASE_NAME);
        }

        PublicationPeer::addSelectColumns($criteria);
        $startcol = PublicationPeer::NUM_HYDRATE_COLUMNS;
        SourcePeer::addSelectColumns($criteria);

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PublicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PublicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PublicationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = SourcePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = SourcePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = SourcePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    SourcePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Publication) to $obj2 (Source)
                $obj2->addPublication($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Publication objects pre-filled with their Publishingcompany objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Publication objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPublishingcompany(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PublicationPeer::DATABASE_NAME);
        }

        PublicationPeer::addSelectColumns($criteria);
        $startcol = PublicationPeer::NUM_HYDRATE_COLUMNS;
        PublishingcompanyPeer::addSelectColumns($criteria);

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PublicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PublicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PublicationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PublishingcompanyPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PublishingcompanyPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PublishingcompanyPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Publication) to $obj2 (Publishingcompany)
                $obj2->addPublication($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Publication objects pre-filled with their Place objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Publication objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPlace(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PublicationPeer::DATABASE_NAME);
        }

        PublicationPeer::addSelectColumns($criteria);
        $startcol = PublicationPeer::NUM_HYDRATE_COLUMNS;
        PlacePeer::addSelectColumns($criteria);

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PublicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PublicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PublicationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PlacePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PlacePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PlacePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Publication) to $obj2 (Place)
                $obj2->addPublication($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Publication objects pre-filled with their Datespecification objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Publication objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinDatespecificationRelatedByPublicationdateId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PublicationPeer::DATABASE_NAME);
        }

        PublicationPeer::addSelectColumns($criteria);
        $startcol = PublicationPeer::NUM_HYDRATE_COLUMNS;
        DatespecificationPeer::addSelectColumns($criteria);

        $criteria->addJoin(PublicationPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PublicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PublicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PublicationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = DatespecificationPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = DatespecificationPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    DatespecificationPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Publication) to $obj2 (Datespecification)
                $obj2->addPublicationRelatedByPublicationdateId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Publication objects pre-filled with their Datespecification objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Publication objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinDatespecificationRelatedByCreationdateId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PublicationPeer::DATABASE_NAME);
        }

        PublicationPeer::addSelectColumns($criteria);
        $startcol = PublicationPeer::NUM_HYDRATE_COLUMNS;
        DatespecificationPeer::addSelectColumns($criteria);

        $criteria->addJoin(PublicationPeer::CREATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PublicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PublicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PublicationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = DatespecificationPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = DatespecificationPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    DatespecificationPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Publication) to $obj2 (Datespecification)
                $obj2->addPublicationRelatedByCreationdateId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Publication objects pre-filled with their DtaUser objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Publication objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinLastChangedByUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PublicationPeer::DATABASE_NAME);
        }

        PublicationPeer::addSelectColumns($criteria);
        $startcol = PublicationPeer::NUM_HYDRATE_COLUMNS;
        DtaUserPeer::addSelectColumns($criteria);

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PublicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PublicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PublicationPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = DtaUserPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = DtaUserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = DtaUserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    DtaUserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Publication) to $obj2 (DtaUser)
                $obj2->addLastChangedPublication($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::CREATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of Publication objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Publication objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PublicationPeer::DATABASE_NAME);
        }

        PublicationPeer::addSelectColumns($criteria);
        $startcol2 = PublicationPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + TitlePeer::NUM_HYDRATE_COLUMNS;

        SourcePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SourcePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PlacePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DtaUserPeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + DtaUserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::CREATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PublicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PublicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PublicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Title rows

            $key2 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = TitlePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = TitlePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    TitlePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (Publication) to the collection in $obj2 (Title)
                $obj2->addPublication($obj1);
            } // if joined row not null

            // Add objects for joined Source rows

            $key3 = SourcePeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = SourcePeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = SourcePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SourcePeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (Publication) to the collection in $obj3 (Source)
                $obj3->addPublication($obj1);
            } // if joined row not null

            // Add objects for joined Publishingcompany rows

            $key4 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = PublishingcompanyPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = PublishingcompanyPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PublishingcompanyPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (Publication) to the collection in $obj4 (Publishingcompany)
                $obj4->addPublication($obj1);
            } // if joined row not null

            // Add objects for joined Place rows

            $key5 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = PlacePeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = PlacePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PlacePeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (Publication) to the collection in $obj5 (Place)
                $obj5->addPublication($obj1);
            } // if joined row not null

            // Add objects for joined Datespecification rows

            $key6 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol6);
            if ($key6 !== null) {
                $obj6 = DatespecificationPeer::getInstanceFromPool($key6);
                if (!$obj6) {

                    $cls = DatespecificationPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    DatespecificationPeer::addInstanceToPool($obj6, $key6);
                } // if obj6 loaded

                // Add the $obj1 (Publication) to the collection in $obj6 (Datespecification)
                $obj6->addPublicationRelatedByPublicationdateId($obj1);
            } // if joined row not null

            // Add objects for joined Datespecification rows

            $key7 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol7);
            if ($key7 !== null) {
                $obj7 = DatespecificationPeer::getInstanceFromPool($key7);
                if (!$obj7) {

                    $cls = DatespecificationPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    DatespecificationPeer::addInstanceToPool($obj7, $key7);
                } // if obj7 loaded

                // Add the $obj1 (Publication) to the collection in $obj7 (Datespecification)
                $obj7->addPublicationRelatedByCreationdateId($obj1);
            } // if joined row not null

            // Add objects for joined DtaUser rows

            $key8 = DtaUserPeer::getPrimaryKeyHashFromRow($row, $startcol8);
            if ($key8 !== null) {
                $obj8 = DtaUserPeer::getInstanceFromPool($key8);
                if (!$obj8) {

                    $cls = DtaUserPeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    DtaUserPeer::addInstanceToPool($obj8, $key8);
                } // if obj8 loaded

                // Add the $obj1 (Publication) to the collection in $obj8 (DtaUser)
                $obj8->addLastChangedPublication($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Title table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptTitle(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::CREATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Source table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptSource(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::CREATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Publishingcompany table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPublishingcompany(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::CREATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Place table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPlace(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::CREATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related DatespecificationRelatedByPublicationdateId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptDatespecificationRelatedByPublicationdateId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related DatespecificationRelatedByCreationdateId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptDatespecificationRelatedByCreationdateId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related LastChangedByUser table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptLastChangedByUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PublicationPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::CREATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of Publication objects pre-filled with all related objects except Title.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Publication objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptTitle(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PublicationPeer::DATABASE_NAME);
        }

        PublicationPeer::addSelectColumns($criteria);
        $startcol2 = PublicationPeer::NUM_HYDRATE_COLUMNS;

        SourcePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + SourcePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PlacePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DtaUserPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + DtaUserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::CREATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PublicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PublicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PublicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Source rows

                $key2 = SourcePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = SourcePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = SourcePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    SourcePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Publication) to the collection in $obj2 (Source)
                $obj2->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Publishingcompany rows

                $key3 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PublishingcompanyPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PublishingcompanyPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PublishingcompanyPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Publication) to the collection in $obj3 (Publishingcompany)
                $obj3->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Place rows

                $key4 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PlacePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PlacePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PlacePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Publication) to the collection in $obj4 (Place)
                $obj4->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key5 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = DatespecificationPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    DatespecificationPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Publication) to the collection in $obj5 (Datespecification)
                $obj5->addPublicationRelatedByPublicationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key6 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = DatespecificationPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    DatespecificationPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Publication) to the collection in $obj6 (Datespecification)
                $obj6->addPublicationRelatedByCreationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined DtaUser rows

                $key7 = DtaUserPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = DtaUserPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = DtaUserPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    DtaUserPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Publication) to the collection in $obj7 (DtaUser)
                $obj7->addLastChangedPublication($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Publication objects pre-filled with all related objects except Source.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Publication objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptSource(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PublicationPeer::DATABASE_NAME);
        }

        PublicationPeer::addSelectColumns($criteria);
        $startcol2 = PublicationPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + TitlePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PlacePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DtaUserPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + DtaUserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::CREATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PublicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PublicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PublicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Title rows

                $key2 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = TitlePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = TitlePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    TitlePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Publication) to the collection in $obj2 (Title)
                $obj2->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Publishingcompany rows

                $key3 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PublishingcompanyPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PublishingcompanyPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PublishingcompanyPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Publication) to the collection in $obj3 (Publishingcompany)
                $obj3->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Place rows

                $key4 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PlacePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PlacePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PlacePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Publication) to the collection in $obj4 (Place)
                $obj4->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key5 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = DatespecificationPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    DatespecificationPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Publication) to the collection in $obj5 (Datespecification)
                $obj5->addPublicationRelatedByPublicationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key6 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = DatespecificationPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    DatespecificationPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Publication) to the collection in $obj6 (Datespecification)
                $obj6->addPublicationRelatedByCreationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined DtaUser rows

                $key7 = DtaUserPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = DtaUserPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = DtaUserPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    DtaUserPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Publication) to the collection in $obj7 (DtaUser)
                $obj7->addLastChangedPublication($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Publication objects pre-filled with all related objects except Publishingcompany.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Publication objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPublishingcompany(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PublicationPeer::DATABASE_NAME);
        }

        PublicationPeer::addSelectColumns($criteria);
        $startcol2 = PublicationPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + TitlePeer::NUM_HYDRATE_COLUMNS;

        SourcePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SourcePeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PlacePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DtaUserPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + DtaUserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::CREATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PublicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PublicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PublicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Title rows

                $key2 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = TitlePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = TitlePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    TitlePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Publication) to the collection in $obj2 (Title)
                $obj2->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Source rows

                $key3 = SourcePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SourcePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SourcePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SourcePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Publication) to the collection in $obj3 (Source)
                $obj3->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Place rows

                $key4 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PlacePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PlacePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PlacePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Publication) to the collection in $obj4 (Place)
                $obj4->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key5 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = DatespecificationPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    DatespecificationPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Publication) to the collection in $obj5 (Datespecification)
                $obj5->addPublicationRelatedByPublicationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key6 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = DatespecificationPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    DatespecificationPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Publication) to the collection in $obj6 (Datespecification)
                $obj6->addPublicationRelatedByCreationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined DtaUser rows

                $key7 = DtaUserPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = DtaUserPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = DtaUserPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    DtaUserPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Publication) to the collection in $obj7 (DtaUser)
                $obj7->addLastChangedPublication($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Publication objects pre-filled with all related objects except Place.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Publication objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPlace(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PublicationPeer::DATABASE_NAME);
        }

        PublicationPeer::addSelectColumns($criteria);
        $startcol2 = PublicationPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + TitlePeer::NUM_HYDRATE_COLUMNS;

        SourcePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SourcePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DtaUserPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + DtaUserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::CREATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PublicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PublicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PublicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Title rows

                $key2 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = TitlePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = TitlePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    TitlePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Publication) to the collection in $obj2 (Title)
                $obj2->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Source rows

                $key3 = SourcePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SourcePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SourcePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SourcePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Publication) to the collection in $obj3 (Source)
                $obj3->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Publishingcompany rows

                $key4 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PublishingcompanyPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PublishingcompanyPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PublishingcompanyPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Publication) to the collection in $obj4 (Publishingcompany)
                $obj4->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key5 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = DatespecificationPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    DatespecificationPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Publication) to the collection in $obj5 (Datespecification)
                $obj5->addPublicationRelatedByPublicationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key6 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = DatespecificationPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    DatespecificationPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Publication) to the collection in $obj6 (Datespecification)
                $obj6->addPublicationRelatedByCreationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined DtaUser rows

                $key7 = DtaUserPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = DtaUserPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = DtaUserPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    DtaUserPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Publication) to the collection in $obj7 (DtaUser)
                $obj7->addLastChangedPublication($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Publication objects pre-filled with all related objects except DatespecificationRelatedByPublicationdateId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Publication objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptDatespecificationRelatedByPublicationdateId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PublicationPeer::DATABASE_NAME);
        }

        PublicationPeer::addSelectColumns($criteria);
        $startcol2 = PublicationPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + TitlePeer::NUM_HYDRATE_COLUMNS;

        SourcePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SourcePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PlacePeer::NUM_HYDRATE_COLUMNS;

        DtaUserPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + DtaUserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PublicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PublicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PublicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Title rows

                $key2 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = TitlePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = TitlePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    TitlePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Publication) to the collection in $obj2 (Title)
                $obj2->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Source rows

                $key3 = SourcePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SourcePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SourcePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SourcePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Publication) to the collection in $obj3 (Source)
                $obj3->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Publishingcompany rows

                $key4 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PublishingcompanyPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PublishingcompanyPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PublishingcompanyPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Publication) to the collection in $obj4 (Publishingcompany)
                $obj4->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Place rows

                $key5 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = PlacePeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = PlacePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PlacePeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Publication) to the collection in $obj5 (Place)
                $obj5->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined DtaUser rows

                $key6 = DtaUserPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = DtaUserPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = DtaUserPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    DtaUserPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Publication) to the collection in $obj6 (DtaUser)
                $obj6->addLastChangedPublication($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Publication objects pre-filled with all related objects except DatespecificationRelatedByCreationdateId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Publication objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptDatespecificationRelatedByCreationdateId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PublicationPeer::DATABASE_NAME);
        }

        PublicationPeer::addSelectColumns($criteria);
        $startcol2 = PublicationPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + TitlePeer::NUM_HYDRATE_COLUMNS;

        SourcePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SourcePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PlacePeer::NUM_HYDRATE_COLUMNS;

        DtaUserPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + DtaUserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::LAST_CHANGED_BY_USER_ID, DtaUserPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PublicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PublicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PublicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Title rows

                $key2 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = TitlePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = TitlePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    TitlePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Publication) to the collection in $obj2 (Title)
                $obj2->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Source rows

                $key3 = SourcePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SourcePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SourcePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SourcePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Publication) to the collection in $obj3 (Source)
                $obj3->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Publishingcompany rows

                $key4 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PublishingcompanyPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PublishingcompanyPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PublishingcompanyPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Publication) to the collection in $obj4 (Publishingcompany)
                $obj4->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Place rows

                $key5 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = PlacePeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = PlacePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PlacePeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Publication) to the collection in $obj5 (Place)
                $obj5->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined DtaUser rows

                $key6 = DtaUserPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = DtaUserPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = DtaUserPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    DtaUserPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Publication) to the collection in $obj6 (DtaUser)
                $obj6->addLastChangedPublication($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Publication objects pre-filled with all related objects except LastChangedByUser.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Publication objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptLastChangedByUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PublicationPeer::DATABASE_NAME);
        }

        PublicationPeer::addSelectColumns($criteria);
        $startcol2 = PublicationPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + TitlePeer::NUM_HYDRATE_COLUMNS;

        SourcePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SourcePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PlacePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PublicationPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::SOURCE_ID, SourcePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(PublicationPeer::CREATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PublicationPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PublicationPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PublicationPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Title rows

                $key2 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = TitlePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = TitlePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    TitlePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Publication) to the collection in $obj2 (Title)
                $obj2->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Source rows

                $key3 = SourcePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = SourcePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = SourcePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SourcePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Publication) to the collection in $obj3 (Source)
                $obj3->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Publishingcompany rows

                $key4 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PublishingcompanyPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PublishingcompanyPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PublishingcompanyPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Publication) to the collection in $obj4 (Publishingcompany)
                $obj4->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Place rows

                $key5 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = PlacePeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = PlacePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PlacePeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Publication) to the collection in $obj5 (Place)
                $obj5->addPublication($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key6 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = DatespecificationPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    DatespecificationPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Publication) to the collection in $obj6 (Datespecification)
                $obj6->addPublicationRelatedByPublicationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key7 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = DatespecificationPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    DatespecificationPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Publication) to the collection in $obj7 (Datespecification)
                $obj7->addPublicationRelatedByCreationdateId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(PublicationPeer::DATABASE_NAME)->getTable(PublicationPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BasePublicationPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BasePublicationPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \DTA\MetadataBundle\Model\Data\map\PublicationTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {
        return PublicationPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Publication or Criteria object.
     *
     * @param      mixed $values Criteria or Publication object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Publication object
        }


        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a Publication or Criteria object.
     *
     * @param      mixed $values Criteria or Publication object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(PublicationPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(PublicationPeer::ID);
            $value = $criteria->remove(PublicationPeer::ID);
            if ($value) {
                $selectCriteria->add(PublicationPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(PublicationPeer::TABLE_NAME);
            }

        } else { // $values is Publication object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the publication table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(PublicationPeer::TABLE_NAME, $con, PublicationPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PublicationPeer::clearInstancePool();
            PublicationPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Publication or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Publication object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            PublicationPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Publication) { // it's a model object
            // invalidate the cache for this single object
            PublicationPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PublicationPeer::DATABASE_NAME);
            $criteria->add(PublicationPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                PublicationPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(PublicationPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            PublicationPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given Publication object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param Publication $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(PublicationPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(PublicationPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(PublicationPeer::DATABASE_NAME, PublicationPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Publication
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = PublicationPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(PublicationPeer::DATABASE_NAME);
        $criteria->add(PublicationPeer::ID, $pk);

        $v = PublicationPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Publication[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(PublicationPeer::DATABASE_NAME);
            $criteria->add(PublicationPeer::ID, $pks, Criteria::IN);
            $objs = PublicationPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

    // nested_set behavior

    /**
     * Returns the root nodes for the tree
     *
     * @param      PropelPDO $con	Connection to use.
     * @return     Publication			Propel object for root node
     */
    public static function retrieveRoots(Criteria $criteria = null, PropelPDO $con = null)
    {
        if ($criteria === null) {
            $criteria = new Criteria(PublicationPeer::DATABASE_NAME);
        }
        $criteria->add(PublicationPeer::LEFT_COL, 1, Criteria::EQUAL);

        return PublicationPeer::doSelect($criteria, $con);
    }

    /**
     * Returns the root node for a given scope
     *
     * @param      int $scope		Scope to determine which root node to return
     * @param      PropelPDO $con	Connection to use.
     * @return     Publication			Propel object for root node
     */
    public static function retrieveRoot($scope = null, PropelPDO $con = null)
    {
        $c = new Criteria(PublicationPeer::DATABASE_NAME);
        $c->add(PublicationPeer::LEFT_COL, 1, Criteria::EQUAL);
        $c->add(PublicationPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return PublicationPeer::doSelectOne($c, $con);
    }

    /**
     * Returns the whole tree node for a given scope
     *
     * @param      int $scope		Scope to determine which root node to return
     * @param      Criteria $criteria	Optional Criteria to filter the query
     * @param      PropelPDO $con	Connection to use.
     * @return     Publication			Propel object for root node
     */
    public static function retrieveTree($scope = null, Criteria $criteria = null, PropelPDO $con = null)
    {
        if ($criteria === null) {
            $criteria = new Criteria(PublicationPeer::DATABASE_NAME);
        }
        $criteria->addAscendingOrderByColumn(PublicationPeer::LEFT_COL);
        $criteria->add(PublicationPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return PublicationPeer::doSelect($criteria, $con);
    }

    /**
     * Tests if node is valid
     *
     * @param      Publication $node	Propel object for src node
     * @return     bool
     */
    public static function isValid(Publication $node = null)
    {
        if (is_object($node) && $node->getRightValue() > $node->getLeftValue()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete an entire tree
     *
     * @param      int $scope		Scope to determine which tree to delete
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     int  The number of deleted nodes
     */
    public static function deleteTree($scope = null, PropelPDO $con = null)
    {
        $c = new Criteria(PublicationPeer::DATABASE_NAME);
        $c->add(PublicationPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return PublicationPeer::doDelete($c, $con);
    }

    /**
     * Adds $delta to all L and R values that are >= $first and <= $last.
     * '$delta' can also be negative.
     *
     * @param      int $delta		Value to be shifted by, can be negative
     * @param      int $first		First node to be shifted
     * @param      int $last			Last node to be shifted (optional)
     * @param      int $scope		Scope to use for the shift
     * @param      PropelPDO $con		Connection to use.
     */
    public static function shiftRLValues($delta, $first, $last = null, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        // Shift left column values
        $whereCriteria = new Criteria(PublicationPeer::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(PublicationPeer::LEFT_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(PublicationPeer::LEFT_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);
        $whereCriteria->add(PublicationPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(PublicationPeer::DATABASE_NAME);
        $valuesCriteria->add(PublicationPeer::LEFT_COL, array('raw' => PublicationPeer::LEFT_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);

        // Shift right column values
        $whereCriteria = new Criteria(PublicationPeer::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(PublicationPeer::RIGHT_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(PublicationPeer::RIGHT_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);
        $whereCriteria->add(PublicationPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(PublicationPeer::DATABASE_NAME);
        $valuesCriteria->add(PublicationPeer::RIGHT_COL, array('raw' => PublicationPeer::RIGHT_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
    }

    /**
     * Adds $delta to level for nodes having left value >= $first and right value <= $last.
     * '$delta' can also be negative.
     *
     * @param      int $delta		Value to be shifted by, can be negative
     * @param      int $first		First node to be shifted
     * @param      int $last			Last node to be shifted
     * @param      int $scope		Scope to use for the shift
     * @param      PropelPDO $con		Connection to use.
     */
    public static function shiftLevel($delta, $first, $last, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $whereCriteria = new Criteria(PublicationPeer::DATABASE_NAME);
        $whereCriteria->add(PublicationPeer::LEFT_COL, $first, Criteria::GREATER_EQUAL);
        $whereCriteria->add(PublicationPeer::RIGHT_COL, $last, Criteria::LESS_EQUAL);
        $whereCriteria->add(PublicationPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(PublicationPeer::DATABASE_NAME);
        $valuesCriteria->add(PublicationPeer::LEVEL_COL, array('raw' => PublicationPeer::LEVEL_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
    }

    /**
     * Reload all already loaded nodes to sync them with updated db
     *
     * @param      Publication $prune		Object to prune from the update
     * @param      PropelPDO $con		Connection to use.
     */
    public static function updateLoadedNodes($prune = null, PropelPDO $con = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            $keys = array();
            foreach (PublicationPeer::$instances as $obj) {
                if (!$prune || !$prune->equals($obj)) {
                    $keys[] = $obj->getPrimaryKey();
                }
            }

            if (!empty($keys)) {
                // We don't need to alter the object instance pool; we're just modifying these ones
                // already in the pool.
                $criteria = new Criteria(PublicationPeer::DATABASE_NAME);
                $criteria->add(PublicationPeer::ID, $keys, Criteria::IN);
                $stmt = PublicationPeer::doSelectStmt($criteria, $con);
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    $key = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
                    if (null !== ($object = PublicationPeer::getInstanceFromPool($key))) {
                        $object->setScopeValue($row[27]);
                        $object->setLeftValue($row[28]);
                        $object->setRightValue($row[29]);
                        $object->setLevel($row[30]);
                        $object->clearNestedSetChildren();
                    }
                }
                $stmt->closeCursor();
            }
        }
    }

    /**
     * Update the tree to allow insertion of a leaf at the specified position
     *
     * @param      int $left	left column value
     * @param      integer $scope	scope column value
     * @param      mixed $prune	Object to prune from the shift
     * @param      PropelPDO $con	Connection to use.
     */
    public static function makeRoomForLeaf($left, $scope, $prune = null, PropelPDO $con = null)
    {
        // Update database nodes
        PublicationPeer::shiftRLValues(2, $left, null, $scope, $con);

        // Update all loaded nodes
        PublicationPeer::updateLoadedNodes($prune, $con);
    }

    /**
     * Update the tree to allow insertion of a leaf at the specified position
     *
     * @param      integer $scope	scope column value
     * @param      PropelPDO $con	Connection to use.
     */
    public static function fixLevels($scope, PropelPDO $con = null)
    {
        $c = new Criteria();
        $c->add(PublicationPeer::SCOPE_COL, $scope, Criteria::EQUAL);
        $c->addAscendingOrderByColumn(PublicationPeer::LEFT_COL);
        $stmt = PublicationPeer::doSelectStmt($c, $con);

        // set the class once to avoid overhead in the loop
        $cls = PublicationPeer::getOMClass(false);
        $level = null;
        // iterate over the statement
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {

            // hydrate object
            $key = PublicationPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null === ($obj = PublicationPeer::getInstanceFromPool($key))) {
                $obj = new $cls();
                $obj->hydrate($row);
                PublicationPeer::addInstanceToPool($obj, $key);
            }

            // compute level
            // Algorithm shamelessly stolen from sfPropelActAsNestedSetBehaviorPlugin
            // Probably authored by Tristan Rivoallan
            if ($level === null) {
                $level = 0;
                $i = 0;
                $prev = array($obj->getRightValue());
            } else {
                while ($obj->getRightValue() > $prev[$i]) {
                    $i--;
                }
                $level = ++$i;
                $prev[$i] = $obj->getRightValue();
            }

            // update level in node if necessary
            if ($obj->getLevel() !== $level) {
                $obj->setLevel($level);
                $obj->save($con);
            }
        }
        $stmt->closeCursor();
    }

    /**
     * Updates all scope values for items that has negative left (<=0) values.
     *
     * @param      mixed     $scope
     * @param      PropelPDO $con	Connection to use.
     */
    public static function setNegativeScope($scope, PropelPDO $con = null)
    {
        //adjust scope value to $scope
        $whereCriteria = new Criteria(PublicationPeer::DATABASE_NAME);
        $whereCriteria->add(PublicationPeer::LEFT_COL, 0, Criteria::LESS_EQUAL);

        $valuesCriteria = new Criteria(PublicationPeer::DATABASE_NAME);
        $valuesCriteria->add(PublicationPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
    }

} // BasePublicationPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BasePublicationPeer::buildTableMap();

