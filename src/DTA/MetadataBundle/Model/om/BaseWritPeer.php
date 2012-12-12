<?php

namespace DTA\MetadataBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use DTA\MetadataBundle\Model\PrinterPeer;
use DTA\MetadataBundle\Model\PublicationPeer;
use DTA\MetadataBundle\Model\PublisherPeer;
use DTA\MetadataBundle\Model\RelatedsetPeer;
use DTA\MetadataBundle\Model\TranslatorPeer;
use DTA\MetadataBundle\Model\WorkPeer;
use DTA\MetadataBundle\Model\Writ;
use DTA\MetadataBundle\Model\WritPeer;
use DTA\MetadataBundle\Model\map\WritTableMap;

abstract class BaseWritPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'DTAMetadata';

    /** the table name for this class */
    const TABLE_NAME = 'writ';

    /** the related Propel class for this table */
    const OM_CLASS = 'DTA\\MetadataBundle\\Model\\Writ';

    /** the related TableMap class for this table */
    const TM_CLASS = 'WritTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 8;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 8;

    /** the column name for the id field */
    const ID = 'writ.id';

    /** the column name for the work_id field */
    const WORK_ID = 'writ.work_id';

    /** the column name for the publication_id field */
    const PUBLICATION_ID = 'writ.publication_id';

    /** the column name for the publisher_id field */
    const PUBLISHER_ID = 'writ.publisher_id';

    /** the column name for the printer_id field */
    const PRINTER_ID = 'writ.printer_id';

    /** the column name for the translator_id field */
    const TRANSLATOR_ID = 'writ.translator_id';

    /** the column name for the numPages field */
    const NUMPAGES = 'writ.numPages';

    /** the column name for the relatedSet_id field */
    const RELATEDSET_ID = 'writ.relatedSet_id';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identiy map to hold any loaded instances of Writ objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Writ[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. WritPeer::$fieldNames[WritPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'WorkId', 'PublicationId', 'PublisherId', 'PrinterId', 'TranslatorId', 'Numpages', 'RelatedsetId', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'workId', 'publicationId', 'publisherId', 'printerId', 'translatorId', 'numpages', 'relatedsetId', ),
        BasePeer::TYPE_COLNAME => array (WritPeer::ID, WritPeer::WORK_ID, WritPeer::PUBLICATION_ID, WritPeer::PUBLISHER_ID, WritPeer::PRINTER_ID, WritPeer::TRANSLATOR_ID, WritPeer::NUMPAGES, WritPeer::RELATEDSET_ID, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'WORK_ID', 'PUBLICATION_ID', 'PUBLISHER_ID', 'PRINTER_ID', 'TRANSLATOR_ID', 'NUMPAGES', 'RELATEDSET_ID', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'work_id', 'publication_id', 'publisher_id', 'printer_id', 'translator_id', 'numPages', 'relatedSet_id', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. WritPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'WorkId' => 1, 'PublicationId' => 2, 'PublisherId' => 3, 'PrinterId' => 4, 'TranslatorId' => 5, 'Numpages' => 6, 'RelatedsetId' => 7, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'workId' => 1, 'publicationId' => 2, 'publisherId' => 3, 'printerId' => 4, 'translatorId' => 5, 'numpages' => 6, 'relatedsetId' => 7, ),
        BasePeer::TYPE_COLNAME => array (WritPeer::ID => 0, WritPeer::WORK_ID => 1, WritPeer::PUBLICATION_ID => 2, WritPeer::PUBLISHER_ID => 3, WritPeer::PRINTER_ID => 4, WritPeer::TRANSLATOR_ID => 5, WritPeer::NUMPAGES => 6, WritPeer::RELATEDSET_ID => 7, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'WORK_ID' => 1, 'PUBLICATION_ID' => 2, 'PUBLISHER_ID' => 3, 'PRINTER_ID' => 4, 'TRANSLATOR_ID' => 5, 'NUMPAGES' => 6, 'RELATEDSET_ID' => 7, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'work_id' => 1, 'publication_id' => 2, 'publisher_id' => 3, 'printer_id' => 4, 'translator_id' => 5, 'numPages' => 6, 'relatedSet_id' => 7, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
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
        $toNames = WritPeer::getFieldNames($toType);
        $key = isset(WritPeer::$fieldKeys[$fromType][$name]) ? WritPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(WritPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, WritPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return WritPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. WritPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(WritPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(WritPeer::ID);
            $criteria->addSelectColumn(WritPeer::WORK_ID);
            $criteria->addSelectColumn(WritPeer::PUBLICATION_ID);
            $criteria->addSelectColumn(WritPeer::PUBLISHER_ID);
            $criteria->addSelectColumn(WritPeer::PRINTER_ID);
            $criteria->addSelectColumn(WritPeer::TRANSLATOR_ID);
            $criteria->addSelectColumn(WritPeer::NUMPAGES);
            $criteria->addSelectColumn(WritPeer::RELATEDSET_ID);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.work_id');
            $criteria->addSelectColumn($alias . '.publication_id');
            $criteria->addSelectColumn($alias . '.publisher_id');
            $criteria->addSelectColumn($alias . '.printer_id');
            $criteria->addSelectColumn($alias . '.translator_id');
            $criteria->addSelectColumn($alias . '.numPages');
            $criteria->addSelectColumn($alias . '.relatedSet_id');
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
        $criteria->setPrimaryTableName(WritPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WritPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(WritPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Writ
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = WritPeer::doSelect($critcopy, $con);
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
        return WritPeer::populateObjects(WritPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement durirectly (for example
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
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            WritPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

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
     * @param      Writ $obj A Writ object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            WritPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A Writ object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Writ) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Writ object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(WritPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return   Writ Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(WritPeer::$instances[$key])) {
                return WritPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool()
    {
        WritPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to writ
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
        $cls = WritPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = WritPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = WritPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                WritPeer::addInstanceToPool($obj, $key);
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
     * @return array (Writ object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = WritPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = WritPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + WritPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = WritPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            WritPeer::addInstanceToPool($obj, $key);
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
        $criteria->setPrimaryTableName(WritPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WritPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WritPeer::WORK_ID, WorkPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(WritPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WritPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WritPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(WritPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WritPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WritPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(WritPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WritPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WritPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Publication table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPublication(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(WritPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WritPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WritPeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(WritPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WritPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WritPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

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
     * Selects a collection of Writ objects pre-filled with their Work objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Writ objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinWork(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WritPeer::DATABASE_NAME);
        }

        WritPeer::addSelectColumns($criteria);
        $startcol = WritPeer::NUM_HYDRATE_COLUMNS;
        WorkPeer::addSelectColumns($criteria);

        $criteria->addJoin(WritPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WritPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WritPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = WritPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WritPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Writ) to $obj2 (Work)
                $obj2->addWrit($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Writ objects pre-filled with their Publisher objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Writ objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPublisher(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WritPeer::DATABASE_NAME);
        }

        WritPeer::addSelectColumns($criteria);
        $startcol = WritPeer::NUM_HYDRATE_COLUMNS;
        PublisherPeer::addSelectColumns($criteria);

        $criteria->addJoin(WritPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WritPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WritPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = WritPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WritPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Writ) to $obj2 (Publisher)
                $obj2->addWrit($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Writ objects pre-filled with their Printer objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Writ objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPrinter(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WritPeer::DATABASE_NAME);
        }

        WritPeer::addSelectColumns($criteria);
        $startcol = WritPeer::NUM_HYDRATE_COLUMNS;
        PrinterPeer::addSelectColumns($criteria);

        $criteria->addJoin(WritPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WritPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WritPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = WritPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WritPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Writ) to $obj2 (Printer)
                $obj2->addWrit($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Writ objects pre-filled with their Translator objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Writ objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinTranslator(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WritPeer::DATABASE_NAME);
        }

        WritPeer::addSelectColumns($criteria);
        $startcol = WritPeer::NUM_HYDRATE_COLUMNS;
        TranslatorPeer::addSelectColumns($criteria);

        $criteria->addJoin(WritPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WritPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WritPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = WritPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WritPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Writ) to $obj2 (Translator)
                $obj2->addWrit($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Writ objects pre-filled with their Publication objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Writ objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPublication(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WritPeer::DATABASE_NAME);
        }

        WritPeer::addSelectColumns($criteria);
        $startcol = WritPeer::NUM_HYDRATE_COLUMNS;
        PublicationPeer::addSelectColumns($criteria);

        $criteria->addJoin(WritPeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WritPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WritPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = WritPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WritPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PublicationPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PublicationPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PublicationPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PublicationPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Writ) to $obj2 (Publication)
                $obj2->addWrit($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Writ objects pre-filled with their Relatedset objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Writ objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinRelatedset(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WritPeer::DATABASE_NAME);
        }

        WritPeer::addSelectColumns($criteria);
        $startcol = WritPeer::NUM_HYDRATE_COLUMNS;
        RelatedsetPeer::addSelectColumns($criteria);

        $criteria->addJoin(WritPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WritPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WritPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = WritPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WritPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Writ) to $obj2 (Relatedset)
                $obj2->addWrit($obj1);

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
        $criteria->setPrimaryTableName(WritPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WritPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WritPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

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
     * Selects a collection of Writ objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Writ objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WritPeer::DATABASE_NAME);
        }

        WritPeer::addSelectColumns($criteria);
        $startcol2 = WritPeer::NUM_HYDRATE_COLUMNS;

        WorkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + WorkPeer::NUM_HYDRATE_COLUMNS;

        PublisherPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PublisherPeer::NUM_HYDRATE_COLUMNS;

        PrinterPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PrinterPeer::NUM_HYDRATE_COLUMNS;

        TranslatorPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + TranslatorPeer::NUM_HYDRATE_COLUMNS;

        PublicationPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + PublicationPeer::NUM_HYDRATE_COLUMNS;

        RelatedsetPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + RelatedsetPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(WritPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WritPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WritPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = WritPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WritPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Writ) to the collection in $obj2 (Work)
                $obj2->addWrit($obj1);
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

                // Add the $obj1 (Writ) to the collection in $obj3 (Publisher)
                $obj3->addWrit($obj1);
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

                // Add the $obj1 (Writ) to the collection in $obj4 (Printer)
                $obj4->addWrit($obj1);
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

                // Add the $obj1 (Writ) to the collection in $obj5 (Translator)
                $obj5->addWrit($obj1);
            } // if joined row not null

            // Add objects for joined Publication rows

            $key6 = PublicationPeer::getPrimaryKeyHashFromRow($row, $startcol6);
            if ($key6 !== null) {
                $obj6 = PublicationPeer::getInstanceFromPool($key6);
                if (!$obj6) {

                    $cls = PublicationPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    PublicationPeer::addInstanceToPool($obj6, $key6);
                } // if obj6 loaded

                // Add the $obj1 (Writ) to the collection in $obj6 (Publication)
                $obj6->addWrit($obj1);
            } // if joined row not null

            // Add objects for joined Relatedset rows

            $key7 = RelatedsetPeer::getPrimaryKeyHashFromRow($row, $startcol7);
            if ($key7 !== null) {
                $obj7 = RelatedsetPeer::getInstanceFromPool($key7);
                if (!$obj7) {

                    $cls = RelatedsetPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    RelatedsetPeer::addInstanceToPool($obj7, $key7);
                } // if obj7 loaded

                // Add the $obj1 (Writ) to the collection in $obj7 (Relatedset)
                $obj7->addWrit($obj1);
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
        $criteria->setPrimaryTableName(WritPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WritPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WritPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(WritPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WritPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WritPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(WritPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WritPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WritPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(WritPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WritPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WritPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Publication table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPublication(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(WritPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WritPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WritPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(WritPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WritPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WritPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

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
     * Selects a collection of Writ objects pre-filled with all related objects except Work.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Writ objects.
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
            $criteria->setDbName(WritPeer::DATABASE_NAME);
        }

        WritPeer::addSelectColumns($criteria);
        $startcol2 = WritPeer::NUM_HYDRATE_COLUMNS;

        PublisherPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PublisherPeer::NUM_HYDRATE_COLUMNS;

        PrinterPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PrinterPeer::NUM_HYDRATE_COLUMNS;

        TranslatorPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + TranslatorPeer::NUM_HYDRATE_COLUMNS;

        PublicationPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PublicationPeer::NUM_HYDRATE_COLUMNS;

        RelatedsetPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + RelatedsetPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(WritPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WritPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WritPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = WritPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WritPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Writ) to the collection in $obj2 (Publisher)
                $obj2->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj3 (Printer)
                $obj3->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj4 (Translator)
                $obj4->addWrit($obj1);

            } // if joined row is not null

                // Add objects for joined Publication rows

                $key5 = PublicationPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = PublicationPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = PublicationPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PublicationPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Writ) to the collection in $obj5 (Publication)
                $obj5->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj6 (Relatedset)
                $obj6->addWrit($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Writ objects pre-filled with all related objects except Publisher.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Writ objects.
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
            $criteria->setDbName(WritPeer::DATABASE_NAME);
        }

        WritPeer::addSelectColumns($criteria);
        $startcol2 = WritPeer::NUM_HYDRATE_COLUMNS;

        WorkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + WorkPeer::NUM_HYDRATE_COLUMNS;

        PrinterPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PrinterPeer::NUM_HYDRATE_COLUMNS;

        TranslatorPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + TranslatorPeer::NUM_HYDRATE_COLUMNS;

        PublicationPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PublicationPeer::NUM_HYDRATE_COLUMNS;

        RelatedsetPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + RelatedsetPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(WritPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WritPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WritPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = WritPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WritPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Writ) to the collection in $obj2 (Work)
                $obj2->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj3 (Printer)
                $obj3->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj4 (Translator)
                $obj4->addWrit($obj1);

            } // if joined row is not null

                // Add objects for joined Publication rows

                $key5 = PublicationPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = PublicationPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = PublicationPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PublicationPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Writ) to the collection in $obj5 (Publication)
                $obj5->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj6 (Relatedset)
                $obj6->addWrit($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Writ objects pre-filled with all related objects except Printer.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Writ objects.
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
            $criteria->setDbName(WritPeer::DATABASE_NAME);
        }

        WritPeer::addSelectColumns($criteria);
        $startcol2 = WritPeer::NUM_HYDRATE_COLUMNS;

        WorkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + WorkPeer::NUM_HYDRATE_COLUMNS;

        PublisherPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PublisherPeer::NUM_HYDRATE_COLUMNS;

        TranslatorPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + TranslatorPeer::NUM_HYDRATE_COLUMNS;

        PublicationPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PublicationPeer::NUM_HYDRATE_COLUMNS;

        RelatedsetPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + RelatedsetPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(WritPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WritPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WritPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = WritPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WritPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Writ) to the collection in $obj2 (Work)
                $obj2->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj3 (Publisher)
                $obj3->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj4 (Translator)
                $obj4->addWrit($obj1);

            } // if joined row is not null

                // Add objects for joined Publication rows

                $key5 = PublicationPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = PublicationPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = PublicationPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PublicationPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Writ) to the collection in $obj5 (Publication)
                $obj5->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj6 (Relatedset)
                $obj6->addWrit($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Writ objects pre-filled with all related objects except Translator.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Writ objects.
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
            $criteria->setDbName(WritPeer::DATABASE_NAME);
        }

        WritPeer::addSelectColumns($criteria);
        $startcol2 = WritPeer::NUM_HYDRATE_COLUMNS;

        WorkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + WorkPeer::NUM_HYDRATE_COLUMNS;

        PublisherPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PublisherPeer::NUM_HYDRATE_COLUMNS;

        PrinterPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PrinterPeer::NUM_HYDRATE_COLUMNS;

        PublicationPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PublicationPeer::NUM_HYDRATE_COLUMNS;

        RelatedsetPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + RelatedsetPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(WritPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WritPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WritPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = WritPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WritPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Writ) to the collection in $obj2 (Work)
                $obj2->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj3 (Publisher)
                $obj3->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj4 (Printer)
                $obj4->addWrit($obj1);

            } // if joined row is not null

                // Add objects for joined Publication rows

                $key5 = PublicationPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = PublicationPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = PublicationPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PublicationPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Writ) to the collection in $obj5 (Publication)
                $obj5->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj6 (Relatedset)
                $obj6->addWrit($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Writ objects pre-filled with all related objects except Publication.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Writ objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPublication(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WritPeer::DATABASE_NAME);
        }

        WritPeer::addSelectColumns($criteria);
        $startcol2 = WritPeer::NUM_HYDRATE_COLUMNS;

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

        $criteria->addJoin(WritPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::RELATEDSET_ID, RelatedsetPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WritPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WritPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = WritPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WritPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Writ) to the collection in $obj2 (Work)
                $obj2->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj3 (Publisher)
                $obj3->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj4 (Printer)
                $obj4->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj5 (Translator)
                $obj5->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj6 (Relatedset)
                $obj6->addWrit($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Writ objects pre-filled with all related objects except Relatedset.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Writ objects.
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
            $criteria->setDbName(WritPeer::DATABASE_NAME);
        }

        WritPeer::addSelectColumns($criteria);
        $startcol2 = WritPeer::NUM_HYDRATE_COLUMNS;

        WorkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + WorkPeer::NUM_HYDRATE_COLUMNS;

        PublisherPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PublisherPeer::NUM_HYDRATE_COLUMNS;

        PrinterPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PrinterPeer::NUM_HYDRATE_COLUMNS;

        TranslatorPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + TranslatorPeer::NUM_HYDRATE_COLUMNS;

        PublicationPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + PublicationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(WritPeer::WORK_ID, WorkPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLISHER_ID, PublisherPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PRINTER_ID, PrinterPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::TRANSLATOR_ID, TranslatorPeer::ID, $join_behavior);

        $criteria->addJoin(WritPeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WritPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WritPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = WritPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WritPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Writ) to the collection in $obj2 (Work)
                $obj2->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj3 (Publisher)
                $obj3->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj4 (Printer)
                $obj4->addWrit($obj1);

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

                // Add the $obj1 (Writ) to the collection in $obj5 (Translator)
                $obj5->addWrit($obj1);

            } // if joined row is not null

                // Add objects for joined Publication rows

                $key6 = PublicationPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = PublicationPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = PublicationPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    PublicationPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Writ) to the collection in $obj6 (Publication)
                $obj6->addWrit($obj1);

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
        return Propel::getDatabaseMap(WritPeer::DATABASE_NAME)->getTable(WritPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseWritPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseWritPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new WritTableMap());
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
        return WritPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Writ or Criteria object.
     *
     * @param      mixed $values Criteria or Writ object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Writ object
        }

        if ($criteria->containsKey(WritPeer::ID) && $criteria->keyContainsValue(WritPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.WritPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a Writ or Criteria object.
     *
     * @param      mixed $values Criteria or Writ object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(WritPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(WritPeer::ID);
            $value = $criteria->remove(WritPeer::ID);
            if ($value) {
                $selectCriteria->add(WritPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(WritPeer::TABLE_NAME);
            }

        } else { // $values is Writ object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the writ table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(WritPeer::TABLE_NAME, $con, WritPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            WritPeer::clearInstancePool();
            WritPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Writ or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Writ object or primary key or array of primary keys
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
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            WritPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Writ) { // it's a model object
            // invalidate the cache for this single object
            WritPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(WritPeer::DATABASE_NAME);
            $criteria->add(WritPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                WritPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(WritPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            WritPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given Writ object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      Writ $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(WritPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(WritPeer::TABLE_NAME);

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

        return BasePeer::doValidate(WritPeer::DATABASE_NAME, WritPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Writ
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = WritPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(WritPeer::DATABASE_NAME);
        $criteria->add(WritPeer::ID, $pk);

        $v = WritPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Writ[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(WritPeer::DATABASE_NAME);
            $criteria->add(WritPeer::ID, $pks, Criteria::IN);
            $objs = WritPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseWritPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseWritPeer::buildTableMap();

