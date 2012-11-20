<?php

namespace DTA\MetadataBundle\Model\Publication\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use DTA\MetadataBundle\Model\Classification\DwdsgenrePeer;
use DTA\MetadataBundle\Model\Classification\GenrePeer;
use DTA\MetadataBundle\Model\Description\DatespecificationPeer;
use DTA\MetadataBundle\Model\Publication\Work;
use DTA\MetadataBundle\Model\Publication\WorkPeer;
use DTA\MetadataBundle\Model\Publication\map\WorkTableMap;
use DTA\MetadataBundle\Model\Workflow\StatusPeer;

abstract class BaseWorkPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'DTAMetadata';

    /** the table name for this class */
    const TABLE_NAME = 'work';

    /** the related Propel class for this table */
    const OM_CLASS = 'DTA\\MetadataBundle\\Model\\Publication\\Work';

    /** the related TableMap class for this table */
    const TM_CLASS = 'WorkTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 11;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 11;

    /** the column name for the ID field */
    const ID = 'work.ID';

    /** the column name for the STATUS_ID field */
    const STATUS_ID = 'work.STATUS_ID';

    /** the column name for the DATESPECIFICATION_ID field */
    const DATESPECIFICATION_ID = 'work.DATESPECIFICATION_ID';

    /** the column name for the GENRE_ID field */
    const GENRE_ID = 'work.GENRE_ID';

    /** the column name for the SUBGENRE_ID field */
    const SUBGENRE_ID = 'work.SUBGENRE_ID';

    /** the column name for the DWDSGENRE_ID field */
    const DWDSGENRE_ID = 'work.DWDSGENRE_ID';

    /** the column name for the DWDSSUBGENRE_ID field */
    const DWDSSUBGENRE_ID = 'work.DWDSSUBGENRE_ID';

    /** the column name for the DOI field */
    const DOI = 'work.DOI';

    /** the column name for the COMMENTS field */
    const COMMENTS = 'work.COMMENTS';

    /** the column name for the FORMAT field */
    const FORMAT = 'work.FORMAT';

    /** the column name for the DIRECTORYNAME field */
    const DIRECTORYNAME = 'work.DIRECTORYNAME';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identiy map to hold any loaded instances of Work objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Work[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. WorkPeer::$fieldNames[WorkPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'StatusId', 'DatespecificationId', 'GenreId', 'SubgenreId', 'DwdsgenreId', 'DwdssubgenreId', 'Doi', 'Comments', 'Format', 'Directoryname', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'statusId', 'datespecificationId', 'genreId', 'subgenreId', 'dwdsgenreId', 'dwdssubgenreId', 'doi', 'comments', 'format', 'directoryname', ),
        BasePeer::TYPE_COLNAME => array (WorkPeer::ID, WorkPeer::STATUS_ID, WorkPeer::DATESPECIFICATION_ID, WorkPeer::GENRE_ID, WorkPeer::SUBGENRE_ID, WorkPeer::DWDSGENRE_ID, WorkPeer::DWDSSUBGENRE_ID, WorkPeer::DOI, WorkPeer::COMMENTS, WorkPeer::FORMAT, WorkPeer::DIRECTORYNAME, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'STATUS_ID', 'DATESPECIFICATION_ID', 'GENRE_ID', 'SUBGENRE_ID', 'DWDSGENRE_ID', 'DWDSSUBGENRE_ID', 'DOI', 'COMMENTS', 'FORMAT', 'DIRECTORYNAME', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'status_id', 'dateSpecification_id', 'genre_id', 'subgenre_id', 'dwdsGenre_id', 'dwdsSubgenre_id', 'doi', 'comments', 'format', 'directoryName', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. WorkPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'StatusId' => 1, 'DatespecificationId' => 2, 'GenreId' => 3, 'SubgenreId' => 4, 'DwdsgenreId' => 5, 'DwdssubgenreId' => 6, 'Doi' => 7, 'Comments' => 8, 'Format' => 9, 'Directoryname' => 10, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'statusId' => 1, 'datespecificationId' => 2, 'genreId' => 3, 'subgenreId' => 4, 'dwdsgenreId' => 5, 'dwdssubgenreId' => 6, 'doi' => 7, 'comments' => 8, 'format' => 9, 'directoryname' => 10, ),
        BasePeer::TYPE_COLNAME => array (WorkPeer::ID => 0, WorkPeer::STATUS_ID => 1, WorkPeer::DATESPECIFICATION_ID => 2, WorkPeer::GENRE_ID => 3, WorkPeer::SUBGENRE_ID => 4, WorkPeer::DWDSGENRE_ID => 5, WorkPeer::DWDSSUBGENRE_ID => 6, WorkPeer::DOI => 7, WorkPeer::COMMENTS => 8, WorkPeer::FORMAT => 9, WorkPeer::DIRECTORYNAME => 10, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'STATUS_ID' => 1, 'DATESPECIFICATION_ID' => 2, 'GENRE_ID' => 3, 'SUBGENRE_ID' => 4, 'DWDSGENRE_ID' => 5, 'DWDSSUBGENRE_ID' => 6, 'DOI' => 7, 'COMMENTS' => 8, 'FORMAT' => 9, 'DIRECTORYNAME' => 10, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'status_id' => 1, 'dateSpecification_id' => 2, 'genre_id' => 3, 'subgenre_id' => 4, 'dwdsGenre_id' => 5, 'dwdsSubgenre_id' => 6, 'doi' => 7, 'comments' => 8, 'format' => 9, 'directoryName' => 10, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
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
        $toNames = WorkPeer::getFieldNames($toType);
        $key = isset(WorkPeer::$fieldKeys[$fromType][$name]) ? WorkPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(WorkPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, WorkPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return WorkPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. WorkPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(WorkPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(WorkPeer::ID);
            $criteria->addSelectColumn(WorkPeer::STATUS_ID);
            $criteria->addSelectColumn(WorkPeer::DATESPECIFICATION_ID);
            $criteria->addSelectColumn(WorkPeer::GENRE_ID);
            $criteria->addSelectColumn(WorkPeer::SUBGENRE_ID);
            $criteria->addSelectColumn(WorkPeer::DWDSGENRE_ID);
            $criteria->addSelectColumn(WorkPeer::DWDSSUBGENRE_ID);
            $criteria->addSelectColumn(WorkPeer::DOI);
            $criteria->addSelectColumn(WorkPeer::COMMENTS);
            $criteria->addSelectColumn(WorkPeer::FORMAT);
            $criteria->addSelectColumn(WorkPeer::DIRECTORYNAME);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.STATUS_ID');
            $criteria->addSelectColumn($alias . '.DATESPECIFICATION_ID');
            $criteria->addSelectColumn($alias . '.GENRE_ID');
            $criteria->addSelectColumn($alias . '.SUBGENRE_ID');
            $criteria->addSelectColumn($alias . '.DWDSGENRE_ID');
            $criteria->addSelectColumn($alias . '.DWDSSUBGENRE_ID');
            $criteria->addSelectColumn($alias . '.DOI');
            $criteria->addSelectColumn($alias . '.COMMENTS');
            $criteria->addSelectColumn($alias . '.FORMAT');
            $criteria->addSelectColumn($alias . '.DIRECTORYNAME');
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
        $criteria->setPrimaryTableName(WorkPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WorkPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(WorkPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Work
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = WorkPeer::doSelect($critcopy, $con);
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
        return WorkPeer::populateObjects(WorkPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            WorkPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

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
     * @param      Work $obj A Work object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            WorkPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A Work object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Work) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Work object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(WorkPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return   Work Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(WorkPeer::$instances[$key])) {
                return WorkPeer::$instances[$key];
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
        WorkPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to work
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
        $cls = WorkPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = WorkPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = WorkPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                WorkPeer::addInstanceToPool($obj, $key);
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
     * @return array (Work object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = WorkPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = WorkPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + WorkPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = WorkPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            WorkPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Status table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinStatus(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(WorkPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WorkPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WorkPeer::STATUS_ID, StatusPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related GenreRelatedByGenreId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinGenreRelatedByGenreId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(WorkPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WorkPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WorkPeer::GENRE_ID, GenrePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related GenreRelatedBySubgenreId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinGenreRelatedBySubgenreId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(WorkPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WorkPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WorkPeer::SUBGENRE_ID, GenrePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related DwdsgenreRelatedByDwdsgenreId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinDwdsgenreRelatedByDwdsgenreId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(WorkPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WorkPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WorkPeer::DWDSGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related DwdsgenreRelatedByDwdssubgenreId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinDwdsgenreRelatedByDwdssubgenreId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(WorkPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WorkPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WorkPeer::DWDSSUBGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Datespecification table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinDatespecification(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(WorkPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WorkPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WorkPeer::DATESPECIFICATION_ID, DatespecificationPeer::ID, $join_behavior);

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
     * Selects a collection of Work objects pre-filled with their Status objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Work objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinStatus(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WorkPeer::DATABASE_NAME);
        }

        WorkPeer::addSelectColumns($criteria);
        $startcol = WorkPeer::NUM_HYDRATE_COLUMNS;
        StatusPeer::addSelectColumns($criteria);

        $criteria->addJoin(WorkPeer::STATUS_ID, StatusPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WorkPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WorkPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = WorkPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WorkPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = StatusPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = StatusPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = StatusPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    StatusPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Work) to $obj2 (Status)
                $obj2->addWork($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Work objects pre-filled with their Genre objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Work objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinGenreRelatedByGenreId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WorkPeer::DATABASE_NAME);
        }

        WorkPeer::addSelectColumns($criteria);
        $startcol = WorkPeer::NUM_HYDRATE_COLUMNS;
        GenrePeer::addSelectColumns($criteria);

        $criteria->addJoin(WorkPeer::GENRE_ID, GenrePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WorkPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WorkPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = WorkPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WorkPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = GenrePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = GenrePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = GenrePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    GenrePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Work) to $obj2 (Genre)
                $obj2->addWorkRelatedByGenreId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Work objects pre-filled with their Genre objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Work objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinGenreRelatedBySubgenreId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WorkPeer::DATABASE_NAME);
        }

        WorkPeer::addSelectColumns($criteria);
        $startcol = WorkPeer::NUM_HYDRATE_COLUMNS;
        GenrePeer::addSelectColumns($criteria);

        $criteria->addJoin(WorkPeer::SUBGENRE_ID, GenrePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WorkPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WorkPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = WorkPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WorkPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = GenrePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = GenrePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = GenrePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    GenrePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Work) to $obj2 (Genre)
                $obj2->addWorkRelatedBySubgenreId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Work objects pre-filled with their Dwdsgenre objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Work objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinDwdsgenreRelatedByDwdsgenreId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WorkPeer::DATABASE_NAME);
        }

        WorkPeer::addSelectColumns($criteria);
        $startcol = WorkPeer::NUM_HYDRATE_COLUMNS;
        DwdsgenrePeer::addSelectColumns($criteria);

        $criteria->addJoin(WorkPeer::DWDSGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WorkPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WorkPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = WorkPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WorkPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = DwdsgenrePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = DwdsgenrePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = DwdsgenrePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    DwdsgenrePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Work) to $obj2 (Dwdsgenre)
                $obj2->addWorkRelatedByDwdsgenreId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Work objects pre-filled with their Dwdsgenre objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Work objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinDwdsgenreRelatedByDwdssubgenreId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WorkPeer::DATABASE_NAME);
        }

        WorkPeer::addSelectColumns($criteria);
        $startcol = WorkPeer::NUM_HYDRATE_COLUMNS;
        DwdsgenrePeer::addSelectColumns($criteria);

        $criteria->addJoin(WorkPeer::DWDSSUBGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WorkPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WorkPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = WorkPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WorkPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = DwdsgenrePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = DwdsgenrePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = DwdsgenrePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    DwdsgenrePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Work) to $obj2 (Dwdsgenre)
                $obj2->addWorkRelatedByDwdssubgenreId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Work objects pre-filled with their Datespecification objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Work objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinDatespecification(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WorkPeer::DATABASE_NAME);
        }

        WorkPeer::addSelectColumns($criteria);
        $startcol = WorkPeer::NUM_HYDRATE_COLUMNS;
        DatespecificationPeer::addSelectColumns($criteria);

        $criteria->addJoin(WorkPeer::DATESPECIFICATION_ID, DatespecificationPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WorkPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WorkPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = WorkPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WorkPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Work) to $obj2 (Datespecification)
                $obj2->addWork($obj1);

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
        $criteria->setPrimaryTableName(WorkPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WorkPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WorkPeer::STATUS_ID, StatusPeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::GENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::SUBGENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSSUBGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DATESPECIFICATION_ID, DatespecificationPeer::ID, $join_behavior);

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
     * Selects a collection of Work objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Work objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WorkPeer::DATABASE_NAME);
        }

        WorkPeer::addSelectColumns($criteria);
        $startcol2 = WorkPeer::NUM_HYDRATE_COLUMNS;

        StatusPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StatusPeer::NUM_HYDRATE_COLUMNS;

        GenrePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + GenrePeer::NUM_HYDRATE_COLUMNS;

        GenrePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + GenrePeer::NUM_HYDRATE_COLUMNS;

        DwdsgenrePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + DwdsgenrePeer::NUM_HYDRATE_COLUMNS;

        DwdsgenrePeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + DwdsgenrePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(WorkPeer::STATUS_ID, StatusPeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::GENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::SUBGENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSSUBGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DATESPECIFICATION_ID, DatespecificationPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WorkPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WorkPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = WorkPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WorkPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Status rows

            $key2 = StatusPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = StatusPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = StatusPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    StatusPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (Work) to the collection in $obj2 (Status)
                $obj2->addWork($obj1);
            } // if joined row not null

            // Add objects for joined Genre rows

            $key3 = GenrePeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = GenrePeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = GenrePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    GenrePeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (Work) to the collection in $obj3 (Genre)
                $obj3->addWorkRelatedByGenreId($obj1);
            } // if joined row not null

            // Add objects for joined Genre rows

            $key4 = GenrePeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = GenrePeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = GenrePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    GenrePeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (Work) to the collection in $obj4 (Genre)
                $obj4->addWorkRelatedBySubgenreId($obj1);
            } // if joined row not null

            // Add objects for joined Dwdsgenre rows

            $key5 = DwdsgenrePeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = DwdsgenrePeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = DwdsgenrePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    DwdsgenrePeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (Work) to the collection in $obj5 (Dwdsgenre)
                $obj5->addWorkRelatedByDwdsgenreId($obj1);
            } // if joined row not null

            // Add objects for joined Dwdsgenre rows

            $key6 = DwdsgenrePeer::getPrimaryKeyHashFromRow($row, $startcol6);
            if ($key6 !== null) {
                $obj6 = DwdsgenrePeer::getInstanceFromPool($key6);
                if (!$obj6) {

                    $cls = DwdsgenrePeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    DwdsgenrePeer::addInstanceToPool($obj6, $key6);
                } // if obj6 loaded

                // Add the $obj1 (Work) to the collection in $obj6 (Dwdsgenre)
                $obj6->addWorkRelatedByDwdssubgenreId($obj1);
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

                // Add the $obj1 (Work) to the collection in $obj7 (Datespecification)
                $obj7->addWork($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Status table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptStatus(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(WorkPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WorkPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WorkPeer::GENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::SUBGENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSSUBGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DATESPECIFICATION_ID, DatespecificationPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related GenreRelatedByGenreId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptGenreRelatedByGenreId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(WorkPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WorkPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WorkPeer::STATUS_ID, StatusPeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSSUBGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DATESPECIFICATION_ID, DatespecificationPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related GenreRelatedBySubgenreId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptGenreRelatedBySubgenreId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(WorkPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WorkPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WorkPeer::STATUS_ID, StatusPeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSSUBGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DATESPECIFICATION_ID, DatespecificationPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related DwdsgenreRelatedByDwdsgenreId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptDwdsgenreRelatedByDwdsgenreId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(WorkPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WorkPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WorkPeer::STATUS_ID, StatusPeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::GENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::SUBGENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DATESPECIFICATION_ID, DatespecificationPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related DwdsgenreRelatedByDwdssubgenreId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptDwdsgenreRelatedByDwdssubgenreId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(WorkPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WorkPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WorkPeer::STATUS_ID, StatusPeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::GENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::SUBGENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DATESPECIFICATION_ID, DatespecificationPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Datespecification table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptDatespecification(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(WorkPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            WorkPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(WorkPeer::STATUS_ID, StatusPeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::GENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::SUBGENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSSUBGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

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
     * Selects a collection of Work objects pre-filled with all related objects except Status.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Work objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptStatus(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WorkPeer::DATABASE_NAME);
        }

        WorkPeer::addSelectColumns($criteria);
        $startcol2 = WorkPeer::NUM_HYDRATE_COLUMNS;

        GenrePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + GenrePeer::NUM_HYDRATE_COLUMNS;

        GenrePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + GenrePeer::NUM_HYDRATE_COLUMNS;

        DwdsgenrePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + DwdsgenrePeer::NUM_HYDRATE_COLUMNS;

        DwdsgenrePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + DwdsgenrePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(WorkPeer::GENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::SUBGENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSSUBGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DATESPECIFICATION_ID, DatespecificationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WorkPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WorkPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = WorkPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WorkPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Genre rows

                $key2 = GenrePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = GenrePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = GenrePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    GenrePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Work) to the collection in $obj2 (Genre)
                $obj2->addWorkRelatedByGenreId($obj1);

            } // if joined row is not null

                // Add objects for joined Genre rows

                $key3 = GenrePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = GenrePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = GenrePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    GenrePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Work) to the collection in $obj3 (Genre)
                $obj3->addWorkRelatedBySubgenreId($obj1);

            } // if joined row is not null

                // Add objects for joined Dwdsgenre rows

                $key4 = DwdsgenrePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = DwdsgenrePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = DwdsgenrePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    DwdsgenrePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Work) to the collection in $obj4 (Dwdsgenre)
                $obj4->addWorkRelatedByDwdsgenreId($obj1);

            } // if joined row is not null

                // Add objects for joined Dwdsgenre rows

                $key5 = DwdsgenrePeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = DwdsgenrePeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = DwdsgenrePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    DwdsgenrePeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Work) to the collection in $obj5 (Dwdsgenre)
                $obj5->addWorkRelatedByDwdssubgenreId($obj1);

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

                // Add the $obj1 (Work) to the collection in $obj6 (Datespecification)
                $obj6->addWork($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Work objects pre-filled with all related objects except GenreRelatedByGenreId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Work objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptGenreRelatedByGenreId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WorkPeer::DATABASE_NAME);
        }

        WorkPeer::addSelectColumns($criteria);
        $startcol2 = WorkPeer::NUM_HYDRATE_COLUMNS;

        StatusPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StatusPeer::NUM_HYDRATE_COLUMNS;

        DwdsgenrePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + DwdsgenrePeer::NUM_HYDRATE_COLUMNS;

        DwdsgenrePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + DwdsgenrePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(WorkPeer::STATUS_ID, StatusPeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSSUBGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DATESPECIFICATION_ID, DatespecificationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WorkPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WorkPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = WorkPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WorkPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Status rows

                $key2 = StatusPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = StatusPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = StatusPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    StatusPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Work) to the collection in $obj2 (Status)
                $obj2->addWork($obj1);

            } // if joined row is not null

                // Add objects for joined Dwdsgenre rows

                $key3 = DwdsgenrePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = DwdsgenrePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = DwdsgenrePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    DwdsgenrePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Work) to the collection in $obj3 (Dwdsgenre)
                $obj3->addWorkRelatedByDwdsgenreId($obj1);

            } // if joined row is not null

                // Add objects for joined Dwdsgenre rows

                $key4 = DwdsgenrePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = DwdsgenrePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = DwdsgenrePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    DwdsgenrePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Work) to the collection in $obj4 (Dwdsgenre)
                $obj4->addWorkRelatedByDwdssubgenreId($obj1);

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

                // Add the $obj1 (Work) to the collection in $obj5 (Datespecification)
                $obj5->addWork($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Work objects pre-filled with all related objects except GenreRelatedBySubgenreId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Work objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptGenreRelatedBySubgenreId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WorkPeer::DATABASE_NAME);
        }

        WorkPeer::addSelectColumns($criteria);
        $startcol2 = WorkPeer::NUM_HYDRATE_COLUMNS;

        StatusPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StatusPeer::NUM_HYDRATE_COLUMNS;

        DwdsgenrePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + DwdsgenrePeer::NUM_HYDRATE_COLUMNS;

        DwdsgenrePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + DwdsgenrePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(WorkPeer::STATUS_ID, StatusPeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSSUBGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DATESPECIFICATION_ID, DatespecificationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WorkPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WorkPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = WorkPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WorkPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Status rows

                $key2 = StatusPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = StatusPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = StatusPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    StatusPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Work) to the collection in $obj2 (Status)
                $obj2->addWork($obj1);

            } // if joined row is not null

                // Add objects for joined Dwdsgenre rows

                $key3 = DwdsgenrePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = DwdsgenrePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = DwdsgenrePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    DwdsgenrePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Work) to the collection in $obj3 (Dwdsgenre)
                $obj3->addWorkRelatedByDwdsgenreId($obj1);

            } // if joined row is not null

                // Add objects for joined Dwdsgenre rows

                $key4 = DwdsgenrePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = DwdsgenrePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = DwdsgenrePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    DwdsgenrePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Work) to the collection in $obj4 (Dwdsgenre)
                $obj4->addWorkRelatedByDwdssubgenreId($obj1);

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

                // Add the $obj1 (Work) to the collection in $obj5 (Datespecification)
                $obj5->addWork($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Work objects pre-filled with all related objects except DwdsgenreRelatedByDwdsgenreId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Work objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptDwdsgenreRelatedByDwdsgenreId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WorkPeer::DATABASE_NAME);
        }

        WorkPeer::addSelectColumns($criteria);
        $startcol2 = WorkPeer::NUM_HYDRATE_COLUMNS;

        StatusPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StatusPeer::NUM_HYDRATE_COLUMNS;

        GenrePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + GenrePeer::NUM_HYDRATE_COLUMNS;

        GenrePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + GenrePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(WorkPeer::STATUS_ID, StatusPeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::GENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::SUBGENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DATESPECIFICATION_ID, DatespecificationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WorkPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WorkPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = WorkPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WorkPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Status rows

                $key2 = StatusPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = StatusPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = StatusPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    StatusPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Work) to the collection in $obj2 (Status)
                $obj2->addWork($obj1);

            } // if joined row is not null

                // Add objects for joined Genre rows

                $key3 = GenrePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = GenrePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = GenrePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    GenrePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Work) to the collection in $obj3 (Genre)
                $obj3->addWorkRelatedByGenreId($obj1);

            } // if joined row is not null

                // Add objects for joined Genre rows

                $key4 = GenrePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = GenrePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = GenrePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    GenrePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Work) to the collection in $obj4 (Genre)
                $obj4->addWorkRelatedBySubgenreId($obj1);

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

                // Add the $obj1 (Work) to the collection in $obj5 (Datespecification)
                $obj5->addWork($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Work objects pre-filled with all related objects except DwdsgenreRelatedByDwdssubgenreId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Work objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptDwdsgenreRelatedByDwdssubgenreId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WorkPeer::DATABASE_NAME);
        }

        WorkPeer::addSelectColumns($criteria);
        $startcol2 = WorkPeer::NUM_HYDRATE_COLUMNS;

        StatusPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StatusPeer::NUM_HYDRATE_COLUMNS;

        GenrePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + GenrePeer::NUM_HYDRATE_COLUMNS;

        GenrePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + GenrePeer::NUM_HYDRATE_COLUMNS;

        DatespecificationPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + DatespecificationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(WorkPeer::STATUS_ID, StatusPeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::GENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::SUBGENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DATESPECIFICATION_ID, DatespecificationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WorkPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WorkPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = WorkPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WorkPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Status rows

                $key2 = StatusPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = StatusPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = StatusPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    StatusPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Work) to the collection in $obj2 (Status)
                $obj2->addWork($obj1);

            } // if joined row is not null

                // Add objects for joined Genre rows

                $key3 = GenrePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = GenrePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = GenrePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    GenrePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Work) to the collection in $obj3 (Genre)
                $obj3->addWorkRelatedByGenreId($obj1);

            } // if joined row is not null

                // Add objects for joined Genre rows

                $key4 = GenrePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = GenrePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = GenrePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    GenrePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Work) to the collection in $obj4 (Genre)
                $obj4->addWorkRelatedBySubgenreId($obj1);

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

                // Add the $obj1 (Work) to the collection in $obj5 (Datespecification)
                $obj5->addWork($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Work objects pre-filled with all related objects except Datespecification.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Work objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptDatespecification(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(WorkPeer::DATABASE_NAME);
        }

        WorkPeer::addSelectColumns($criteria);
        $startcol2 = WorkPeer::NUM_HYDRATE_COLUMNS;

        StatusPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StatusPeer::NUM_HYDRATE_COLUMNS;

        GenrePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + GenrePeer::NUM_HYDRATE_COLUMNS;

        GenrePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + GenrePeer::NUM_HYDRATE_COLUMNS;

        DwdsgenrePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + DwdsgenrePeer::NUM_HYDRATE_COLUMNS;

        DwdsgenrePeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + DwdsgenrePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(WorkPeer::STATUS_ID, StatusPeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::GENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::SUBGENRE_ID, GenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSGENRE_ID, DwdsgenrePeer::ID, $join_behavior);

        $criteria->addJoin(WorkPeer::DWDSSUBGENRE_ID, DwdsgenrePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = WorkPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = WorkPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = WorkPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                WorkPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Status rows

                $key2 = StatusPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = StatusPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = StatusPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    StatusPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Work) to the collection in $obj2 (Status)
                $obj2->addWork($obj1);

            } // if joined row is not null

                // Add objects for joined Genre rows

                $key3 = GenrePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = GenrePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = GenrePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    GenrePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Work) to the collection in $obj3 (Genre)
                $obj3->addWorkRelatedByGenreId($obj1);

            } // if joined row is not null

                // Add objects for joined Genre rows

                $key4 = GenrePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = GenrePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = GenrePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    GenrePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Work) to the collection in $obj4 (Genre)
                $obj4->addWorkRelatedBySubgenreId($obj1);

            } // if joined row is not null

                // Add objects for joined Dwdsgenre rows

                $key5 = DwdsgenrePeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = DwdsgenrePeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = DwdsgenrePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    DwdsgenrePeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Work) to the collection in $obj5 (Dwdsgenre)
                $obj5->addWorkRelatedByDwdsgenreId($obj1);

            } // if joined row is not null

                // Add objects for joined Dwdsgenre rows

                $key6 = DwdsgenrePeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = DwdsgenrePeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = DwdsgenrePeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    DwdsgenrePeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Work) to the collection in $obj6 (Dwdsgenre)
                $obj6->addWorkRelatedByDwdssubgenreId($obj1);

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
        return Propel::getDatabaseMap(WorkPeer::DATABASE_NAME)->getTable(WorkPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseWorkPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseWorkPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new WorkTableMap());
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
        return WorkPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Work or Criteria object.
     *
     * @param      mixed $values Criteria or Work object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Work object
        }

        if ($criteria->containsKey(WorkPeer::ID) && $criteria->keyContainsValue(WorkPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.WorkPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a Work or Criteria object.
     *
     * @param      mixed $values Criteria or Work object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(WorkPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(WorkPeer::ID);
            $value = $criteria->remove(WorkPeer::ID);
            if ($value) {
                $selectCriteria->add(WorkPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(WorkPeer::TABLE_NAME);
            }

        } else { // $values is Work object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the work table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(WorkPeer::TABLE_NAME, $con, WorkPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            WorkPeer::clearInstancePool();
            WorkPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Work or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Work object or primary key or array of primary keys
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
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            WorkPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Work) { // it's a model object
            // invalidate the cache for this single object
            WorkPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(WorkPeer::DATABASE_NAME);
            $criteria->add(WorkPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                WorkPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(WorkPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            WorkPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given Work object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      Work $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(WorkPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(WorkPeer::TABLE_NAME);

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

        return BasePeer::doValidate(WorkPeer::DATABASE_NAME, WorkPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Work
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = WorkPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(WorkPeer::DATABASE_NAME);
        $criteria->add(WorkPeer::ID, $pk);

        $v = WorkPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Work[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(WorkPeer::DATABASE_NAME);
            $criteria->add(WorkPeer::ID, $pks, Criteria::IN);
            $objs = WorkPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseWorkPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseWorkPeer::buildTableMap();

