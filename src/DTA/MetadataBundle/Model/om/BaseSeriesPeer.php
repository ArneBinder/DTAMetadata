<?php

namespace DTA\MetadataBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use DTA\MetadataBundle\Model\DatespecificationPeer;
use DTA\MetadataBundle\Model\PlacePeer;
use DTA\MetadataBundle\Model\PrinterPeer;
use DTA\MetadataBundle\Model\PublicationPeer;
use DTA\MetadataBundle\Model\PublisherPeer;
use DTA\MetadataBundle\Model\PublishingcompanyPeer;
use DTA\MetadataBundle\Model\RelatedsetPeer;
use DTA\MetadataBundle\Model\Series;
use DTA\MetadataBundle\Model\SeriesPeer;
use DTA\MetadataBundle\Model\TitlePeer;
use DTA\MetadataBundle\Model\TranslatorPeer;
use DTA\MetadataBundle\Model\WorkPeer;
use DTA\MetadataBundle\Model\map\SeriesTableMap;

abstract class BaseSeriesPeer extends PublicationPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'DTAMetadata';

    /** the table name for this class */
    const TABLE_NAME = 'series';

    /** the related Propel class for this table */
    const OM_CLASS = 'DTA\\MetadataBundle\\Model\\Series';

    /** the related TableMap class for this table */
    const TM_CLASS = 'SeriesTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 18;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 18;

    /** the column name for the id field */
    const ID = 'series.id';

    /** the column name for the volume field */
    const VOLUME = 'series.volume';

    /** the column name for the printRun field */
    const PRINTRUN = 'series.printRun';

    /** the column name for the printRunComment field */
    const PRINTRUNCOMMENT = 'series.printRunComment';

    /** the column name for the edition field */
    const EDITION = 'series.edition';

    /** the column name for the numPages field */
    const NUMPAGES = 'series.numPages';

    /** the column name for the numPagesNormed field */
    const NUMPAGESNORMED = 'series.numPagesNormed';

    /** the column name for the bibliographicCitation field */
    const BIBLIOGRAPHICCITATION = 'series.bibliographicCitation';

    /** the column name for the title_id field */
    const TITLE_ID = 'series.title_id';

    /** the column name for the publishingCompany_id field */
    const PUBLISHINGCOMPANY_ID = 'series.publishingCompany_id';

    /** the column name for the place_id field */
    const PLACE_ID = 'series.place_id';

    /** the column name for the publicationDate_id field */
    const PUBLICATIONDATE_ID = 'series.publicationDate_id';

    /** the column name for the originDate_id field */
    const ORIGINDATE_ID = 'series.originDate_id';

    /** the column name for the relatedSet_id field */
    const RELATEDSET_ID = 'series.relatedSet_id';

    /** the column name for the work_id field */
    const WORK_ID = 'series.work_id';

    /** the column name for the publisher_id field */
    const PUBLISHER_ID = 'series.publisher_id';

    /** the column name for the printer_id field */
    const PRINTER_ID = 'series.printer_id';

    /** the column name for the translator_id field */
    const TRANSLATOR_ID = 'series.translator_id';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identiy map to hold any loaded instances of Series objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Series[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. SeriesPeer::$fieldNames[SeriesPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Volume', 'Printrun', 'Printruncomment', 'Edition', 'Numpages', 'Numpagesnormed', 'Bibliographiccitation', 'TitleId', 'PublishingcompanyId', 'PlaceId', 'PublicationdateId', 'OrigindateId', 'RelatedsetId', 'WorkId', 'PublisherId', 'PrinterId', 'TranslatorId', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'volume', 'printrun', 'printruncomment', 'edition', 'numpages', 'numpagesnormed', 'bibliographiccitation', 'titleId', 'publishingcompanyId', 'placeId', 'publicationdateId', 'origindateId', 'relatedsetId', 'workId', 'publisherId', 'printerId', 'translatorId', ),
        BasePeer::TYPE_COLNAME => array (SeriesPeer::ID, SeriesPeer::VOLUME, SeriesPeer::PRINTRUN, SeriesPeer::PRINTRUNCOMMENT, SeriesPeer::EDITION, SeriesPeer::NUMPAGES, SeriesPeer::NUMPAGESNORMED, SeriesPeer::BIBLIOGRAPHICCITATION, SeriesPeer::TITLE_ID, SeriesPeer::PUBLISHINGCOMPANY_ID, SeriesPeer::PLACE_ID, SeriesPeer::PUBLICATIONDATE_ID, SeriesPeer::ORIGINDATE_ID, SeriesPeer::RELATEDSET_ID, SeriesPeer::WORK_ID, SeriesPeer::PUBLISHER_ID, SeriesPeer::PRINTER_ID, SeriesPeer::TRANSLATOR_ID, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'VOLUME', 'PRINTRUN', 'PRINTRUNCOMMENT', 'EDITION', 'NUMPAGES', 'NUMPAGESNORMED', 'BIBLIOGRAPHICCITATION', 'TITLE_ID', 'PUBLISHINGCOMPANY_ID', 'PLACE_ID', 'PUBLICATIONDATE_ID', 'ORIGINDATE_ID', 'RELATEDSET_ID', 'WORK_ID', 'PUBLISHER_ID', 'PRINTER_ID', 'TRANSLATOR_ID', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'volume', 'printRun', 'printRunComment', 'edition', 'numPages', 'numPagesNormed', 'bibliographicCitation', 'title_id', 'publishingCompany_id', 'place_id', 'publicationDate_id', 'originDate_id', 'relatedSet_id', 'work_id', 'publisher_id', 'printer_id', 'translator_id', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. SeriesPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Volume' => 1, 'Printrun' => 2, 'Printruncomment' => 3, 'Edition' => 4, 'Numpages' => 5, 'Numpagesnormed' => 6, 'Bibliographiccitation' => 7, 'TitleId' => 8, 'PublishingcompanyId' => 9, 'PlaceId' => 10, 'PublicationdateId' => 11, 'OrigindateId' => 12, 'RelatedsetId' => 13, 'WorkId' => 14, 'PublisherId' => 15, 'PrinterId' => 16, 'TranslatorId' => 17, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'volume' => 1, 'printrun' => 2, 'printruncomment' => 3, 'edition' => 4, 'numpages' => 5, 'numpagesnormed' => 6, 'bibliographiccitation' => 7, 'titleId' => 8, 'publishingcompanyId' => 9, 'placeId' => 10, 'publicationdateId' => 11, 'origindateId' => 12, 'relatedsetId' => 13, 'workId' => 14, 'publisherId' => 15, 'printerId' => 16, 'translatorId' => 17, ),
        BasePeer::TYPE_COLNAME => array (SeriesPeer::ID => 0, SeriesPeer::VOLUME => 1, SeriesPeer::PRINTRUN => 2, SeriesPeer::PRINTRUNCOMMENT => 3, SeriesPeer::EDITION => 4, SeriesPeer::NUMPAGES => 5, SeriesPeer::NUMPAGESNORMED => 6, SeriesPeer::BIBLIOGRAPHICCITATION => 7, SeriesPeer::TITLE_ID => 8, SeriesPeer::PUBLISHINGCOMPANY_ID => 9, SeriesPeer::PLACE_ID => 10, SeriesPeer::PUBLICATIONDATE_ID => 11, SeriesPeer::ORIGINDATE_ID => 12, SeriesPeer::RELATEDSET_ID => 13, SeriesPeer::WORK_ID => 14, SeriesPeer::PUBLISHER_ID => 15, SeriesPeer::PRINTER_ID => 16, SeriesPeer::TRANSLATOR_ID => 17, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'VOLUME' => 1, 'PRINTRUN' => 2, 'PRINTRUNCOMMENT' => 3, 'EDITION' => 4, 'NUMPAGES' => 5, 'NUMPAGESNORMED' => 6, 'BIBLIOGRAPHICCITATION' => 7, 'TITLE_ID' => 8, 'PUBLISHINGCOMPANY_ID' => 9, 'PLACE_ID' => 10, 'PUBLICATIONDATE_ID' => 11, 'ORIGINDATE_ID' => 12, 'RELATEDSET_ID' => 13, 'WORK_ID' => 14, 'PUBLISHER_ID' => 15, 'PRINTER_ID' => 16, 'TRANSLATOR_ID' => 17, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'volume' => 1, 'printRun' => 2, 'printRunComment' => 3, 'edition' => 4, 'numPages' => 5, 'numPagesNormed' => 6, 'bibliographicCitation' => 7, 'title_id' => 8, 'publishingCompany_id' => 9, 'place_id' => 10, 'publicationDate_id' => 11, 'originDate_id' => 12, 'relatedSet_id' => 13, 'work_id' => 14, 'publisher_id' => 15, 'printer_id' => 16, 'translator_id' => 17, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, )
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
        $toNames = SeriesPeer::getFieldNames($toType);
        $key = isset(SeriesPeer::$fieldKeys[$fromType][$name]) ? SeriesPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(SeriesPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, SeriesPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return SeriesPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. SeriesPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(SeriesPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(SeriesPeer::ID);
            $criteria->addSelectColumn(SeriesPeer::VOLUME);
            $criteria->addSelectColumn(SeriesPeer::PRINTRUN);
            $criteria->addSelectColumn(SeriesPeer::PRINTRUNCOMMENT);
            $criteria->addSelectColumn(SeriesPeer::EDITION);
            $criteria->addSelectColumn(SeriesPeer::NUMPAGES);
            $criteria->addSelectColumn(SeriesPeer::NUMPAGESNORMED);
            $criteria->addSelectColumn(SeriesPeer::BIBLIOGRAPHICCITATION);
            $criteria->addSelectColumn(SeriesPeer::TITLE_ID);
            $criteria->addSelectColumn(SeriesPeer::PUBLISHINGCOMPANY_ID);
            $criteria->addSelectColumn(SeriesPeer::PLACE_ID);
            $criteria->addSelectColumn(SeriesPeer::PUBLICATIONDATE_ID);
            $criteria->addSelectColumn(SeriesPeer::ORIGINDATE_ID);
            $criteria->addSelectColumn(SeriesPeer::RELATEDSET_ID);
            $criteria->addSelectColumn(SeriesPeer::WORK_ID);
            $criteria->addSelectColumn(SeriesPeer::PUBLISHER_ID);
            $criteria->addSelectColumn(SeriesPeer::PRINTER_ID);
            $criteria->addSelectColumn(SeriesPeer::TRANSLATOR_ID);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.volume');
            $criteria->addSelectColumn($alias . '.printRun');
            $criteria->addSelectColumn($alias . '.printRunComment');
            $criteria->addSelectColumn($alias . '.edition');
            $criteria->addSelectColumn($alias . '.numPages');
            $criteria->addSelectColumn($alias . '.numPagesNormed');
            $criteria->addSelectColumn($alias . '.bibliographicCitation');
            $criteria->addSelectColumn($alias . '.title_id');
            $criteria->addSelectColumn($alias . '.publishingCompany_id');
            $criteria->addSelectColumn($alias . '.place_id');
            $criteria->addSelectColumn($alias . '.publicationDate_id');
            $criteria->addSelectColumn($alias . '.originDate_id');
            $criteria->addSelectColumn($alias . '.relatedSet_id');
            $criteria->addSelectColumn($alias . '.work_id');
            $criteria->addSelectColumn($alias . '.publisher_id');
            $criteria->addSelectColumn($alias . '.printer_id');
            $criteria->addSelectColumn($alias . '.translator_id');
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
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(SeriesPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Series
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = SeriesPeer::doSelect($critcopy, $con);
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
        return SeriesPeer::populateObjects(SeriesPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            SeriesPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

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
     * @param      Series $obj A Series object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            SeriesPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A Series object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Series) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Series object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(SeriesPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return   Series Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(SeriesPeer::$instances[$key])) {
                return SeriesPeer::$instances[$key];
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
      if ($and_clear_all_references)
      {
        foreach (SeriesPeer::$instances as $instance)
        {
          $instance->clearAllReferences(true);
        }
      }
        SeriesPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to series
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
        $cls = SeriesPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = SeriesPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SeriesPeer::addInstanceToPool($obj, $key);
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
     * @return array (Series object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = SeriesPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = SeriesPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + SeriesPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SeriesPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            SeriesPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Work table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinWork(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Publisher table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPublisher(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Printer table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPrinter(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Translator table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinTranslator(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Relatedset table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinRelatedset(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related DatespecificationRelatedByOrigindateId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinDatespecificationRelatedByOrigindateId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);

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
     * Selects a collection of Series objects pre-filled with their Work objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinWork(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol = SeriesPeer::NUM_HYDRATE_COLUMNS;
        WorkPeer::addSelectColumns($criteria);

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = WorkPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = WorkPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = WorkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    WorkPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Series) to $obj2 (Work)
                $obj2->addSeries($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with their Publisher objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPublisher(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol = SeriesPeer::NUM_HYDRATE_COLUMNS;
        PublisherPeer::addSelectColumns($criteria);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PublisherPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PublisherPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PublisherPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PublisherPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Series) to $obj2 (Publisher)
                $obj2->addSeries($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with their Printer objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPrinter(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol = SeriesPeer::NUM_HYDRATE_COLUMNS;
        PrinterPeer::addSelectColumns($criteria);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PrinterPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PrinterPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PrinterPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PrinterPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Series) to $obj2 (Printer)
                $obj2->addSeries($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with their Translator objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinTranslator(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol = SeriesPeer::NUM_HYDRATE_COLUMNS;
        TranslatorPeer::addSelectColumns($criteria);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = TranslatorPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = TranslatorPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = TranslatorPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    TranslatorPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Series) to $obj2 (Translator)
                $obj2->addSeries($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with their Relatedset objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinRelatedset(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol = SeriesPeer::NUM_HYDRATE_COLUMNS;
        RelatedsetPeer::addSelectColumns($criteria);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = RelatedsetPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = RelatedsetPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = RelatedsetPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    RelatedsetPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Series) to $obj2 (Relatedset)
                $obj2->addSeries($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with their Title objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinTitle(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol = SeriesPeer::NUM_HYDRATE_COLUMNS;
        TitlePeer::addSelectColumns($criteria);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Series) to $obj2 (Title)
                $obj2->addSeries($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with their Publishingcompany objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPublishingcompany(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol = SeriesPeer::NUM_HYDRATE_COLUMNS;
        PublishingcompanyPeer::addSelectColumns($criteria);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Series) to $obj2 (Publishingcompany)
                $obj2->addSeries($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with their Place objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPlace(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol = SeriesPeer::NUM_HYDRATE_COLUMNS;
        PlacePeer::addSelectColumns($criteria);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Series) to $obj2 (Place)
                $obj2->addSeries($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with their Datespecification objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinDatespecificationRelatedByPublicationdateId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol = SeriesPeer::NUM_HYDRATE_COLUMNS;
        DatespecificationPeer::addSelectColumns($criteria);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Series) to $obj2 (Datespecification)
                $obj2->addSeriesRelatedByPublicationdateId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with their Datespecification objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinDatespecificationRelatedByOrigindateId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol = SeriesPeer::NUM_HYDRATE_COLUMNS;
        DatespecificationPeer::addSelectColumns($criteria);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Series) to $obj2 (Datespecification)
                $obj2->addSeriesRelatedByOrigindateId($obj1);

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
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);

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
     * Selects a collection of Series objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol2 = SeriesPeer::NUM_HYDRATE_COLUMNS;

        WorkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + WorkPeer::NUM_HYDRATE_COLUMNS;

        PublisherPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PublisherPeer::NUM_HYDRATE_COLUMNS;

        PrinterPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PrinterPeer::NUM_HYDRATE_COLUMNS;

        TranslatorPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + TranslatorPeer::NUM_HYDRATE_COLUMNS;

        RelatedsetPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + RelatedsetPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + TitlePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol10 = $startcol9 + PlacePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol11 = $startcol10 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol12 = $startcol11 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Work rows

            $key2 = WorkPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = WorkPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = WorkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    WorkPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (Series) to the collection in $obj2 (Work)
                $obj2->addSeries($obj1);
            } // if joined row not null

            // Add objects for joined Publisher rows

            $key3 = PublisherPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = PublisherPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = PublisherPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PublisherPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (Series) to the collection in $obj3 (Publisher)
                $obj3->addSeries($obj1);
            } // if joined row not null

            // Add objects for joined Printer rows

            $key4 = PrinterPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = PrinterPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = PrinterPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PrinterPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (Series) to the collection in $obj4 (Printer)
                $obj4->addSeries($obj1);
            } // if joined row not null

            // Add objects for joined Translator rows

            $key5 = TranslatorPeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = TranslatorPeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = TranslatorPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    TranslatorPeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (Series) to the collection in $obj5 (Translator)
                $obj5->addSeries($obj1);
            } // if joined row not null

            // Add objects for joined Relatedset rows

            $key6 = RelatedsetPeer::getPrimaryKeyHashFromRow($row, $startcol6);
            if ($key6 !== null) {
                $obj6 = RelatedsetPeer::getInstanceFromPool($key6);
                if (!$obj6) {

                    $cls = RelatedsetPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    RelatedsetPeer::addInstanceToPool($obj6, $key6);
                } // if obj6 loaded

                // Add the $obj1 (Series) to the collection in $obj6 (Relatedset)
                $obj6->addSeries($obj1);
            } // if joined row not null

            // Add objects for joined Title rows

            $key7 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol7);
            if ($key7 !== null) {
                $obj7 = TitlePeer::getInstanceFromPool($key7);
                if (!$obj7) {

                    $cls = TitlePeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    TitlePeer::addInstanceToPool($obj7, $key7);
                } // if obj7 loaded

                // Add the $obj1 (Series) to the collection in $obj7 (Title)
                $obj7->addSeries($obj1);
            } // if joined row not null

            // Add objects for joined Publishingcompany rows

            $key8 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol8);
            if ($key8 !== null) {
                $obj8 = PublishingcompanyPeer::getInstanceFromPool($key8);
                if (!$obj8) {

                    $cls = PublishingcompanyPeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    PublishingcompanyPeer::addInstanceToPool($obj8, $key8);
                } // if obj8 loaded

                // Add the $obj1 (Series) to the collection in $obj8 (Publishingcompany)
                $obj8->addSeries($obj1);
            } // if joined row not null

            // Add objects for joined Place rows

            $key9 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol9);
            if ($key9 !== null) {
                $obj9 = PlacePeer::getInstanceFromPool($key9);
                if (!$obj9) {

                    $cls = PlacePeer::getOMClass();

                    $obj9 = new $cls();
                    $obj9->hydrate($row, $startcol9);
                    PlacePeer::addInstanceToPool($obj9, $key9);
                } // if obj9 loaded

                // Add the $obj1 (Series) to the collection in $obj9 (Place)
                $obj9->addSeries($obj1);
            } // if joined row not null

            // Add objects for joined Datespecification rows

            $key10 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol10);
            if ($key10 !== null) {
                $obj10 = DatespecificationPeer::getInstanceFromPool($key10);
                if (!$obj10) {

                    $cls = DatespecificationPeer::getOMClass();

                    $obj10 = new $cls();
                    $obj10->hydrate($row, $startcol10);
                    DatespecificationPeer::addInstanceToPool($obj10, $key10);
                } // if obj10 loaded

                // Add the $obj1 (Series) to the collection in $obj10 (Datespecification)
                $obj10->addSeriesRelatedByPublicationdateId($obj1);
            } // if joined row not null

            // Add objects for joined Datespecification rows

            $key11 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol11);
            if ($key11 !== null) {
                $obj11 = DatespecificationPeer::getInstanceFromPool($key11);
                if (!$obj11) {

                    $cls = DatespecificationPeer::getOMClass();

                    $obj11 = new $cls();
                    $obj11->hydrate($row, $startcol11);
                    DatespecificationPeer::addInstanceToPool($obj11, $key11);
                } // if obj11 loaded

                // Add the $obj1 (Series) to the collection in $obj11 (Datespecification)
                $obj11->addSeriesRelatedByOrigindateId($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Work table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptWork(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Publisher table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPublisher(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Printer table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPrinter(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Translator table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptTranslator(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Relatedset table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptRelatedset(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related DatespecificationRelatedByOrigindateId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptDatespecificationRelatedByOrigindateId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeriesPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

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
     * Selects a collection of Series objects pre-filled with all related objects except Work.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptWork(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol2 = SeriesPeer::NUM_HYDRATE_COLUMNS;

        PublisherPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PublisherPeer::NUM_HYDRATE_COLUMNS;

        PrinterPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PrinterPeer::NUM_HYDRATE_COLUMNS;

        TranslatorPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + TranslatorPeer::NUM_HYDRATE_COLUMNS;

        RelatedsetPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + RelatedsetPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + TitlePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + PlacePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol10 = $startcol9 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol11 = $startcol10 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Publisher rows

                $key2 = PublisherPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = PublisherPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = PublisherPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PublisherPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Series) to the collection in $obj2 (Publisher)
                $obj2->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Printer rows

                $key3 = PrinterPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PrinterPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PrinterPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PrinterPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Series) to the collection in $obj3 (Printer)
                $obj3->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Translator rows

                $key4 = TranslatorPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = TranslatorPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = TranslatorPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    TranslatorPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Series) to the collection in $obj4 (Translator)
                $obj4->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Relatedset rows

                $key5 = RelatedsetPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = RelatedsetPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = RelatedsetPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    RelatedsetPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Series) to the collection in $obj5 (Relatedset)
                $obj5->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Title rows

                $key6 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = TitlePeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = TitlePeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    TitlePeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Series) to the collection in $obj6 (Title)
                $obj6->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publishingcompany rows

                $key7 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = PublishingcompanyPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = PublishingcompanyPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    PublishingcompanyPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Series) to the collection in $obj7 (Publishingcompany)
                $obj7->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Place rows

                $key8 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = PlacePeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = PlacePeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    PlacePeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Series) to the collection in $obj8 (Place)
                $obj8->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key9 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol9);
                if ($key9 !== null) {
                    $obj9 = DatespecificationPeer::getInstanceFromPool($key9);
                    if (!$obj9) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj9 = new $cls();
                    $obj9->hydrate($row, $startcol9);
                    DatespecificationPeer::addInstanceToPool($obj9, $key9);
                } // if $obj9 already loaded

                // Add the $obj1 (Series) to the collection in $obj9 (Datespecification)
                $obj9->addSeriesRelatedByPublicationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key10 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol10);
                if ($key10 !== null) {
                    $obj10 = DatespecificationPeer::getInstanceFromPool($key10);
                    if (!$obj10) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj10 = new $cls();
                    $obj10->hydrate($row, $startcol10);
                    DatespecificationPeer::addInstanceToPool($obj10, $key10);
                } // if $obj10 already loaded

                // Add the $obj1 (Series) to the collection in $obj10 (Datespecification)
                $obj10->addSeriesRelatedByOrigindateId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with all related objects except Publisher.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPublisher(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol2 = SeriesPeer::NUM_HYDRATE_COLUMNS;

        WorkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + WorkPeer::NUM_HYDRATE_COLUMNS;

        PrinterPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PrinterPeer::NUM_HYDRATE_COLUMNS;

        TranslatorPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + TranslatorPeer::NUM_HYDRATE_COLUMNS;

        RelatedsetPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + RelatedsetPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + TitlePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + PlacePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol10 = $startcol9 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol11 = $startcol10 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Work rows

                $key2 = WorkPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = WorkPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = WorkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    WorkPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Series) to the collection in $obj2 (Work)
                $obj2->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Printer rows

                $key3 = PrinterPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PrinterPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PrinterPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PrinterPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Series) to the collection in $obj3 (Printer)
                $obj3->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Translator rows

                $key4 = TranslatorPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = TranslatorPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = TranslatorPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    TranslatorPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Series) to the collection in $obj4 (Translator)
                $obj4->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Relatedset rows

                $key5 = RelatedsetPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = RelatedsetPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = RelatedsetPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    RelatedsetPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Series) to the collection in $obj5 (Relatedset)
                $obj5->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Title rows

                $key6 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = TitlePeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = TitlePeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    TitlePeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Series) to the collection in $obj6 (Title)
                $obj6->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publishingcompany rows

                $key7 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = PublishingcompanyPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = PublishingcompanyPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    PublishingcompanyPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Series) to the collection in $obj7 (Publishingcompany)
                $obj7->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Place rows

                $key8 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = PlacePeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = PlacePeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    PlacePeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Series) to the collection in $obj8 (Place)
                $obj8->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key9 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol9);
                if ($key9 !== null) {
                    $obj9 = DatespecificationPeer::getInstanceFromPool($key9);
                    if (!$obj9) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj9 = new $cls();
                    $obj9->hydrate($row, $startcol9);
                    DatespecificationPeer::addInstanceToPool($obj9, $key9);
                } // if $obj9 already loaded

                // Add the $obj1 (Series) to the collection in $obj9 (Datespecification)
                $obj9->addSeriesRelatedByPublicationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key10 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol10);
                if ($key10 !== null) {
                    $obj10 = DatespecificationPeer::getInstanceFromPool($key10);
                    if (!$obj10) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj10 = new $cls();
                    $obj10->hydrate($row, $startcol10);
                    DatespecificationPeer::addInstanceToPool($obj10, $key10);
                } // if $obj10 already loaded

                // Add the $obj1 (Series) to the collection in $obj10 (Datespecification)
                $obj10->addSeriesRelatedByOrigindateId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with all related objects except Printer.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPrinter(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol2 = SeriesPeer::NUM_HYDRATE_COLUMNS;

        WorkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + WorkPeer::NUM_HYDRATE_COLUMNS;

        PublisherPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PublisherPeer::NUM_HYDRATE_COLUMNS;

        TranslatorPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + TranslatorPeer::NUM_HYDRATE_COLUMNS;

        RelatedsetPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + RelatedsetPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + TitlePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + PlacePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol10 = $startcol9 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol11 = $startcol10 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Work rows

                $key2 = WorkPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = WorkPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = WorkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    WorkPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Series) to the collection in $obj2 (Work)
                $obj2->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publisher rows

                $key3 = PublisherPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PublisherPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PublisherPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PublisherPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Series) to the collection in $obj3 (Publisher)
                $obj3->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Translator rows

                $key4 = TranslatorPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = TranslatorPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = TranslatorPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    TranslatorPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Series) to the collection in $obj4 (Translator)
                $obj4->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Relatedset rows

                $key5 = RelatedsetPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = RelatedsetPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = RelatedsetPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    RelatedsetPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Series) to the collection in $obj5 (Relatedset)
                $obj5->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Title rows

                $key6 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = TitlePeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = TitlePeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    TitlePeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Series) to the collection in $obj6 (Title)
                $obj6->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publishingcompany rows

                $key7 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = PublishingcompanyPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = PublishingcompanyPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    PublishingcompanyPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Series) to the collection in $obj7 (Publishingcompany)
                $obj7->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Place rows

                $key8 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = PlacePeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = PlacePeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    PlacePeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Series) to the collection in $obj8 (Place)
                $obj8->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key9 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol9);
                if ($key9 !== null) {
                    $obj9 = DatespecificationPeer::getInstanceFromPool($key9);
                    if (!$obj9) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj9 = new $cls();
                    $obj9->hydrate($row, $startcol9);
                    DatespecificationPeer::addInstanceToPool($obj9, $key9);
                } // if $obj9 already loaded

                // Add the $obj1 (Series) to the collection in $obj9 (Datespecification)
                $obj9->addSeriesRelatedByPublicationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key10 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol10);
                if ($key10 !== null) {
                    $obj10 = DatespecificationPeer::getInstanceFromPool($key10);
                    if (!$obj10) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj10 = new $cls();
                    $obj10->hydrate($row, $startcol10);
                    DatespecificationPeer::addInstanceToPool($obj10, $key10);
                } // if $obj10 already loaded

                // Add the $obj1 (Series) to the collection in $obj10 (Datespecification)
                $obj10->addSeriesRelatedByOrigindateId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with all related objects except Translator.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptTranslator(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol2 = SeriesPeer::NUM_HYDRATE_COLUMNS;

        WorkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + WorkPeer::NUM_HYDRATE_COLUMNS;

        PublisherPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PublisherPeer::NUM_HYDRATE_COLUMNS;

        PrinterPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PrinterPeer::NUM_HYDRATE_COLUMNS;

        RelatedsetPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + RelatedsetPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + TitlePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + PlacePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol10 = $startcol9 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol11 = $startcol10 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Work rows

                $key2 = WorkPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = WorkPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = WorkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    WorkPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Series) to the collection in $obj2 (Work)
                $obj2->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publisher rows

                $key3 = PublisherPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PublisherPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PublisherPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PublisherPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Series) to the collection in $obj3 (Publisher)
                $obj3->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Printer rows

                $key4 = PrinterPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PrinterPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PrinterPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PrinterPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Series) to the collection in $obj4 (Printer)
                $obj4->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Relatedset rows

                $key5 = RelatedsetPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = RelatedsetPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = RelatedsetPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    RelatedsetPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Series) to the collection in $obj5 (Relatedset)
                $obj5->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Title rows

                $key6 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = TitlePeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = TitlePeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    TitlePeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Series) to the collection in $obj6 (Title)
                $obj6->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publishingcompany rows

                $key7 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = PublishingcompanyPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = PublishingcompanyPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    PublishingcompanyPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Series) to the collection in $obj7 (Publishingcompany)
                $obj7->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Place rows

                $key8 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = PlacePeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = PlacePeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    PlacePeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Series) to the collection in $obj8 (Place)
                $obj8->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key9 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol9);
                if ($key9 !== null) {
                    $obj9 = DatespecificationPeer::getInstanceFromPool($key9);
                    if (!$obj9) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj9 = new $cls();
                    $obj9->hydrate($row, $startcol9);
                    DatespecificationPeer::addInstanceToPool($obj9, $key9);
                } // if $obj9 already loaded

                // Add the $obj1 (Series) to the collection in $obj9 (Datespecification)
                $obj9->addSeriesRelatedByPublicationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key10 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol10);
                if ($key10 !== null) {
                    $obj10 = DatespecificationPeer::getInstanceFromPool($key10);
                    if (!$obj10) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj10 = new $cls();
                    $obj10->hydrate($row, $startcol10);
                    DatespecificationPeer::addInstanceToPool($obj10, $key10);
                } // if $obj10 already loaded

                // Add the $obj1 (Series) to the collection in $obj10 (Datespecification)
                $obj10->addSeriesRelatedByOrigindateId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with all related objects except Relatedset.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptRelatedset(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol2 = SeriesPeer::NUM_HYDRATE_COLUMNS;

        WorkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + WorkPeer::NUM_HYDRATE_COLUMNS;

        PublisherPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PublisherPeer::NUM_HYDRATE_COLUMNS;

        PrinterPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PrinterPeer::NUM_HYDRATE_COLUMNS;

        TranslatorPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + TranslatorPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + TitlePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + PlacePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol10 = $startcol9 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol11 = $startcol10 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Work rows

                $key2 = WorkPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = WorkPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = WorkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    WorkPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Series) to the collection in $obj2 (Work)
                $obj2->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publisher rows

                $key3 = PublisherPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PublisherPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PublisherPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PublisherPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Series) to the collection in $obj3 (Publisher)
                $obj3->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Printer rows

                $key4 = PrinterPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PrinterPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PrinterPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PrinterPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Series) to the collection in $obj4 (Printer)
                $obj4->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Translator rows

                $key5 = TranslatorPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = TranslatorPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = TranslatorPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    TranslatorPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Series) to the collection in $obj5 (Translator)
                $obj5->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Title rows

                $key6 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = TitlePeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = TitlePeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    TitlePeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Series) to the collection in $obj6 (Title)
                $obj6->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publishingcompany rows

                $key7 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = PublishingcompanyPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = PublishingcompanyPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    PublishingcompanyPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Series) to the collection in $obj7 (Publishingcompany)
                $obj7->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Place rows

                $key8 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = PlacePeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = PlacePeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    PlacePeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Series) to the collection in $obj8 (Place)
                $obj8->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key9 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol9);
                if ($key9 !== null) {
                    $obj9 = DatespecificationPeer::getInstanceFromPool($key9);
                    if (!$obj9) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj9 = new $cls();
                    $obj9->hydrate($row, $startcol9);
                    DatespecificationPeer::addInstanceToPool($obj9, $key9);
                } // if $obj9 already loaded

                // Add the $obj1 (Series) to the collection in $obj9 (Datespecification)
                $obj9->addSeriesRelatedByPublicationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key10 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol10);
                if ($key10 !== null) {
                    $obj10 = DatespecificationPeer::getInstanceFromPool($key10);
                    if (!$obj10) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj10 = new $cls();
                    $obj10->hydrate($row, $startcol10);
                    DatespecificationPeer::addInstanceToPool($obj10, $key10);
                } // if $obj10 already loaded

                // Add the $obj1 (Series) to the collection in $obj10 (Datespecification)
                $obj10->addSeriesRelatedByOrigindateId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with all related objects except Title.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
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
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol2 = SeriesPeer::NUM_HYDRATE_COLUMNS;

        WorkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + WorkPeer::NUM_HYDRATE_COLUMNS;

        PublisherPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PublisherPeer::NUM_HYDRATE_COLUMNS;

        PrinterPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PrinterPeer::NUM_HYDRATE_COLUMNS;

        TranslatorPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + TranslatorPeer::NUM_HYDRATE_COLUMNS;

        RelatedsetPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + RelatedsetPeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + PlacePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol10 = $startcol9 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol11 = $startcol10 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Work rows

                $key2 = WorkPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = WorkPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = WorkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    WorkPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Series) to the collection in $obj2 (Work)
                $obj2->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publisher rows

                $key3 = PublisherPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PublisherPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PublisherPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PublisherPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Series) to the collection in $obj3 (Publisher)
                $obj3->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Printer rows

                $key4 = PrinterPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PrinterPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PrinterPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PrinterPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Series) to the collection in $obj4 (Printer)
                $obj4->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Translator rows

                $key5 = TranslatorPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = TranslatorPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = TranslatorPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    TranslatorPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Series) to the collection in $obj5 (Translator)
                $obj5->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Relatedset rows

                $key6 = RelatedsetPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = RelatedsetPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = RelatedsetPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    RelatedsetPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Series) to the collection in $obj6 (Relatedset)
                $obj6->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publishingcompany rows

                $key7 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = PublishingcompanyPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = PublishingcompanyPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    PublishingcompanyPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Series) to the collection in $obj7 (Publishingcompany)
                $obj7->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Place rows

                $key8 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = PlacePeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = PlacePeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    PlacePeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Series) to the collection in $obj8 (Place)
                $obj8->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key9 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol9);
                if ($key9 !== null) {
                    $obj9 = DatespecificationPeer::getInstanceFromPool($key9);
                    if (!$obj9) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj9 = new $cls();
                    $obj9->hydrate($row, $startcol9);
                    DatespecificationPeer::addInstanceToPool($obj9, $key9);
                } // if $obj9 already loaded

                // Add the $obj1 (Series) to the collection in $obj9 (Datespecification)
                $obj9->addSeriesRelatedByPublicationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key10 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol10);
                if ($key10 !== null) {
                    $obj10 = DatespecificationPeer::getInstanceFromPool($key10);
                    if (!$obj10) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj10 = new $cls();
                    $obj10->hydrate($row, $startcol10);
                    DatespecificationPeer::addInstanceToPool($obj10, $key10);
                } // if $obj10 already loaded

                // Add the $obj1 (Series) to the collection in $obj10 (Datespecification)
                $obj10->addSeriesRelatedByOrigindateId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with all related objects except Publishingcompany.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
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
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol2 = SeriesPeer::NUM_HYDRATE_COLUMNS;

        WorkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + WorkPeer::NUM_HYDRATE_COLUMNS;

        PublisherPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PublisherPeer::NUM_HYDRATE_COLUMNS;

        PrinterPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PrinterPeer::NUM_HYDRATE_COLUMNS;

        TranslatorPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + TranslatorPeer::NUM_HYDRATE_COLUMNS;

        RelatedsetPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + RelatedsetPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + TitlePeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + PlacePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol10 = $startcol9 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol11 = $startcol10 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Work rows

                $key2 = WorkPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = WorkPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = WorkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    WorkPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Series) to the collection in $obj2 (Work)
                $obj2->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publisher rows

                $key3 = PublisherPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PublisherPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PublisherPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PublisherPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Series) to the collection in $obj3 (Publisher)
                $obj3->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Printer rows

                $key4 = PrinterPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PrinterPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PrinterPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PrinterPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Series) to the collection in $obj4 (Printer)
                $obj4->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Translator rows

                $key5 = TranslatorPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = TranslatorPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = TranslatorPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    TranslatorPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Series) to the collection in $obj5 (Translator)
                $obj5->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Relatedset rows

                $key6 = RelatedsetPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = RelatedsetPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = RelatedsetPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    RelatedsetPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Series) to the collection in $obj6 (Relatedset)
                $obj6->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Title rows

                $key7 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = TitlePeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = TitlePeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    TitlePeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Series) to the collection in $obj7 (Title)
                $obj7->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Place rows

                $key8 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = PlacePeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = PlacePeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    PlacePeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Series) to the collection in $obj8 (Place)
                $obj8->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key9 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol9);
                if ($key9 !== null) {
                    $obj9 = DatespecificationPeer::getInstanceFromPool($key9);
                    if (!$obj9) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj9 = new $cls();
                    $obj9->hydrate($row, $startcol9);
                    DatespecificationPeer::addInstanceToPool($obj9, $key9);
                } // if $obj9 already loaded

                // Add the $obj1 (Series) to the collection in $obj9 (Datespecification)
                $obj9->addSeriesRelatedByPublicationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key10 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol10);
                if ($key10 !== null) {
                    $obj10 = DatespecificationPeer::getInstanceFromPool($key10);
                    if (!$obj10) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj10 = new $cls();
                    $obj10->hydrate($row, $startcol10);
                    DatespecificationPeer::addInstanceToPool($obj10, $key10);
                } // if $obj10 already loaded

                // Add the $obj1 (Series) to the collection in $obj10 (Datespecification)
                $obj10->addSeriesRelatedByOrigindateId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with all related objects except Place.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
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
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol2 = SeriesPeer::NUM_HYDRATE_COLUMNS;

        WorkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + WorkPeer::NUM_HYDRATE_COLUMNS;

        PublisherPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PublisherPeer::NUM_HYDRATE_COLUMNS;

        PrinterPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PrinterPeer::NUM_HYDRATE_COLUMNS;

        TranslatorPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + TranslatorPeer::NUM_HYDRATE_COLUMNS;

        RelatedsetPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + RelatedsetPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + TitlePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol10 = $startcol9 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol11 = $startcol10 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLICATIONDATE_ID, DatespecificationPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::ORIGINDATE_ID, DatespecificationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Work rows

                $key2 = WorkPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = WorkPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = WorkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    WorkPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Series) to the collection in $obj2 (Work)
                $obj2->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publisher rows

                $key3 = PublisherPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PublisherPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PublisherPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PublisherPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Series) to the collection in $obj3 (Publisher)
                $obj3->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Printer rows

                $key4 = PrinterPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PrinterPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PrinterPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PrinterPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Series) to the collection in $obj4 (Printer)
                $obj4->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Translator rows

                $key5 = TranslatorPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = TranslatorPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = TranslatorPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    TranslatorPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Series) to the collection in $obj5 (Translator)
                $obj5->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Relatedset rows

                $key6 = RelatedsetPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = RelatedsetPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = RelatedsetPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    RelatedsetPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Series) to the collection in $obj6 (Relatedset)
                $obj6->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Title rows

                $key7 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = TitlePeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = TitlePeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    TitlePeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Series) to the collection in $obj7 (Title)
                $obj7->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publishingcompany rows

                $key8 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = PublishingcompanyPeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = PublishingcompanyPeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    PublishingcompanyPeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Series) to the collection in $obj8 (Publishingcompany)
                $obj8->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key9 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol9);
                if ($key9 !== null) {
                    $obj9 = DatespecificationPeer::getInstanceFromPool($key9);
                    if (!$obj9) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj9 = new $cls();
                    $obj9->hydrate($row, $startcol9);
                    DatespecificationPeer::addInstanceToPool($obj9, $key9);
                } // if $obj9 already loaded

                // Add the $obj1 (Series) to the collection in $obj9 (Datespecification)
                $obj9->addSeriesRelatedByPublicationdateId($obj1);

            } // if joined row is not null

                // Add objects for joined Datespecification rows

                $key10 = DatespecificationPeer::getPrimaryKeyHashFromRow($row, $startcol10);
                if ($key10 !== null) {
                    $obj10 = DatespecificationPeer::getInstanceFromPool($key10);
                    if (!$obj10) {

                        $cls = DatespecificationPeer::getOMClass();

                    $obj10 = new $cls();
                    $obj10->hydrate($row, $startcol10);
                    DatespecificationPeer::addInstanceToPool($obj10, $key10);
                } // if $obj10 already loaded

                // Add the $obj1 (Series) to the collection in $obj10 (Datespecification)
                $obj10->addSeriesRelatedByOrigindateId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with all related objects except DatespecificationRelatedByPublicationdateId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
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
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol2 = SeriesPeer::NUM_HYDRATE_COLUMNS;

        WorkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + WorkPeer::NUM_HYDRATE_COLUMNS;

        PublisherPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PublisherPeer::NUM_HYDRATE_COLUMNS;

        PrinterPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PrinterPeer::NUM_HYDRATE_COLUMNS;

        TranslatorPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + TranslatorPeer::NUM_HYDRATE_COLUMNS;

        RelatedsetPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + RelatedsetPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + TitlePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol10 = $startcol9 + PlacePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Work rows

                $key2 = WorkPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = WorkPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = WorkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    WorkPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Series) to the collection in $obj2 (Work)
                $obj2->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publisher rows

                $key3 = PublisherPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PublisherPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PublisherPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PublisherPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Series) to the collection in $obj3 (Publisher)
                $obj3->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Printer rows

                $key4 = PrinterPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PrinterPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PrinterPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PrinterPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Series) to the collection in $obj4 (Printer)
                $obj4->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Translator rows

                $key5 = TranslatorPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = TranslatorPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = TranslatorPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    TranslatorPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Series) to the collection in $obj5 (Translator)
                $obj5->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Relatedset rows

                $key6 = RelatedsetPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = RelatedsetPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = RelatedsetPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    RelatedsetPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Series) to the collection in $obj6 (Relatedset)
                $obj6->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Title rows

                $key7 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = TitlePeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = TitlePeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    TitlePeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Series) to the collection in $obj7 (Title)
                $obj7->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publishingcompany rows

                $key8 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = PublishingcompanyPeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = PublishingcompanyPeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    PublishingcompanyPeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Series) to the collection in $obj8 (Publishingcompany)
                $obj8->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Place rows

                $key9 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol9);
                if ($key9 !== null) {
                    $obj9 = PlacePeer::getInstanceFromPool($key9);
                    if (!$obj9) {

                        $cls = PlacePeer::getOMClass();

                    $obj9 = new $cls();
                    $obj9->hydrate($row, $startcol9);
                    PlacePeer::addInstanceToPool($obj9, $key9);
                } // if $obj9 already loaded

                // Add the $obj1 (Series) to the collection in $obj9 (Place)
                $obj9->addSeries($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Series objects pre-filled with all related objects except DatespecificationRelatedByOrigindateId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Series objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptDatespecificationRelatedByOrigindateId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeriesPeer::DATABASE_NAME);
        }

        SeriesPeer::addSelectColumns($criteria);
        $startcol2 = SeriesPeer::NUM_HYDRATE_COLUMNS;

        WorkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + WorkPeer::NUM_HYDRATE_COLUMNS;

        PublisherPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PublisherPeer::NUM_HYDRATE_COLUMNS;

        PrinterPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PrinterPeer::NUM_HYDRATE_COLUMNS;

        TranslatorPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + TranslatorPeer::NUM_HYDRATE_COLUMNS;

        RelatedsetPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + RelatedsetPeer::NUM_HYDRATE_COLUMNS;

        TitlePeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + TitlePeer::NUM_HYDRATE_COLUMNS;

        PublishingcompanyPeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + PublishingcompanyPeer::NUM_HYDRATE_COLUMNS;

        PlacePeer::addSelectColumns($criteria);
        $startcol10 = $startcol9 + PlacePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SeriesPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::TITLE_ID, TitlePeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PUBLISHINGCOMPANY_ID, PublishingcompanyPeer::ID, $join_behavior);

        $criteria->addJoin(SeriesPeer::PLACE_ID, PlacePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeriesPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeriesPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SeriesPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeriesPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Work rows

                $key2 = WorkPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = WorkPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = WorkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    WorkPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Series) to the collection in $obj2 (Work)
                $obj2->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publisher rows

                $key3 = PublisherPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PublisherPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PublisherPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PublisherPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Series) to the collection in $obj3 (Publisher)
                $obj3->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Printer rows

                $key4 = PrinterPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PrinterPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PrinterPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PrinterPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Series) to the collection in $obj4 (Printer)
                $obj4->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Translator rows

                $key5 = TranslatorPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = TranslatorPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = TranslatorPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    TranslatorPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Series) to the collection in $obj5 (Translator)
                $obj5->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Relatedset rows

                $key6 = RelatedsetPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = RelatedsetPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = RelatedsetPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    RelatedsetPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Series) to the collection in $obj6 (Relatedset)
                $obj6->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Title rows

                $key7 = TitlePeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = TitlePeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = TitlePeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    TitlePeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (Series) to the collection in $obj7 (Title)
                $obj7->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Publishingcompany rows

                $key8 = PublishingcompanyPeer::getPrimaryKeyHashFromRow($row, $startcol8);
                if ($key8 !== null) {
                    $obj8 = PublishingcompanyPeer::getInstanceFromPool($key8);
                    if (!$obj8) {

                        $cls = PublishingcompanyPeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    PublishingcompanyPeer::addInstanceToPool($obj8, $key8);
                } // if $obj8 already loaded

                // Add the $obj1 (Series) to the collection in $obj8 (Publishingcompany)
                $obj8->addSeries($obj1);

            } // if joined row is not null

                // Add objects for joined Place rows

                $key9 = PlacePeer::getPrimaryKeyHashFromRow($row, $startcol9);
                if ($key9 !== null) {
                    $obj9 = PlacePeer::getInstanceFromPool($key9);
                    if (!$obj9) {

                        $cls = PlacePeer::getOMClass();

                    $obj9 = new $cls();
                    $obj9->hydrate($row, $startcol9);
                    PlacePeer::addInstanceToPool($obj9, $key9);
                } // if $obj9 already loaded

                // Add the $obj1 (Series) to the collection in $obj9 (Place)
                $obj9->addSeries($obj1);

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
        return Propel::getDatabaseMap(SeriesPeer::DATABASE_NAME)->getTable(SeriesPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseSeriesPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseSeriesPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new SeriesTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass()
    {
        return SeriesPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Series or Criteria object.
     *
     * @param      mixed $values Criteria or Series object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Series object
        }

        if ($criteria->containsKey(SeriesPeer::ID) && $criteria->keyContainsValue(SeriesPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SeriesPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a Series or Criteria object.
     *
     * @param      mixed $values Criteria or Series object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(SeriesPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(SeriesPeer::ID);
            $value = $criteria->remove(SeriesPeer::ID);
            if ($value) {
                $selectCriteria->add(SeriesPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(SeriesPeer::TABLE_NAME);
            }

        } else { // $values is Series object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the series table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(SeriesPeer::TABLE_NAME, $con, SeriesPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SeriesPeer::clearInstancePool();
            SeriesPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Series or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Series object or primary key or array of primary keys
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
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            SeriesPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Series) { // it's a model object
            // invalidate the cache for this single object
            SeriesPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SeriesPeer::DATABASE_NAME);
            $criteria->add(SeriesPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                SeriesPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(SeriesPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            SeriesPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given Series object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      Series $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(SeriesPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(SeriesPeer::TABLE_NAME);

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

        return BasePeer::doValidate(SeriesPeer::DATABASE_NAME, SeriesPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Series
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = SeriesPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(SeriesPeer::DATABASE_NAME);
        $criteria->add(SeriesPeer::ID, $pk);

        $v = SeriesPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Series[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(SeriesPeer::DATABASE_NAME);
            $criteria->add(SeriesPeer::ID, $pks, Criteria::IN);
            $objs = SeriesPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseSeriesPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseSeriesPeer::buildTableMap();

