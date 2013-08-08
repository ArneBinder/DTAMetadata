<?php

namespace DTA\MetadataBundle\Model\Workflow\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use DTA\MetadataBundle\Model\Data\PublicationPeer;
use DTA\MetadataBundle\Model\Workflow\Imagesource;
use DTA\MetadataBundle\Model\Workflow\ImagesourcePeer;
use DTA\MetadataBundle\Model\Workflow\LicensePeer;
use DTA\MetadataBundle\Model\Workflow\PartnerPeer;
use DTA\MetadataBundle\Model\Workflow\map\ImagesourceTableMap;

abstract class BaseImagesourcePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'DTAMetadata';

    /** the table name for this class */
    const TABLE_NAME = 'imagesource';

    /** the related Propel class for this table */
    const OM_CLASS = 'DTA\\MetadataBundle\\Model\\Workflow\\Imagesource';

    /** the related TableMap class for this table */
    const TM_CLASS = 'ImagesourceTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 11;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 11;

    /** the column name for the id field */
    const ID = 'imagesource.id';

    /** the column name for the publication_id field */
    const PUBLICATION_ID = 'imagesource.publication_id';

    /** the column name for the partner_id field */
    const PARTNER_ID = 'imagesource.partner_id';

    /** the column name for the cataloguesignature field */
    const CATALOGUESIGNATURE = 'imagesource.cataloguesignature';

    /** the column name for the catalogueurl field */
    const CATALOGUEURL = 'imagesource.catalogueurl';

    /** the column name for the numfaksimiles field */
    const NUMFAKSIMILES = 'imagesource.numfaksimiles';

    /** the column name for the extentasofcatalogue field */
    const EXTENTASOFCATALOGUE = 'imagesource.extentasofcatalogue';

    /** the column name for the numpages field */
    const NUMPAGES = 'imagesource.numpages';

    /** the column name for the imageurl field */
    const IMAGEURL = 'imagesource.imageurl';

    /** the column name for the imageurn field */
    const IMAGEURN = 'imagesource.imageurn';

    /** the column name for the license_id field */
    const LICENSE_ID = 'imagesource.license_id';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identiy map to hold any loaded instances of Imagesource objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Imagesource[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. ImagesourcePeer::$fieldNames[ImagesourcePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'PublicationId', 'PartnerId', 'Cataloguesignature', 'Catalogueurl', 'Numfaksimiles', 'Extentasofcatalogue', 'Numpages', 'Imageurl', 'Imageurn', 'LicenseId', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'publicationId', 'partnerId', 'cataloguesignature', 'catalogueurl', 'numfaksimiles', 'extentasofcatalogue', 'numpages', 'imageurl', 'imageurn', 'licenseId', ),
        BasePeer::TYPE_COLNAME => array (ImagesourcePeer::ID, ImagesourcePeer::PUBLICATION_ID, ImagesourcePeer::PARTNER_ID, ImagesourcePeer::CATALOGUESIGNATURE, ImagesourcePeer::CATALOGUEURL, ImagesourcePeer::NUMFAKSIMILES, ImagesourcePeer::EXTENTASOFCATALOGUE, ImagesourcePeer::NUMPAGES, ImagesourcePeer::IMAGEURL, ImagesourcePeer::IMAGEURN, ImagesourcePeer::LICENSE_ID, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'PUBLICATION_ID', 'PARTNER_ID', 'CATALOGUESIGNATURE', 'CATALOGUEURL', 'NUMFAKSIMILES', 'EXTENTASOFCATALOGUE', 'NUMPAGES', 'IMAGEURL', 'IMAGEURN', 'LICENSE_ID', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'publication_id', 'partner_id', 'cataloguesignature', 'catalogueurl', 'numfaksimiles', 'extentasofcatalogue', 'numpages', 'imageurl', 'imageurn', 'license_id', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. ImagesourcePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'PublicationId' => 1, 'PartnerId' => 2, 'Cataloguesignature' => 3, 'Catalogueurl' => 4, 'Numfaksimiles' => 5, 'Extentasofcatalogue' => 6, 'Numpages' => 7, 'Imageurl' => 8, 'Imageurn' => 9, 'LicenseId' => 10, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'publicationId' => 1, 'partnerId' => 2, 'cataloguesignature' => 3, 'catalogueurl' => 4, 'numfaksimiles' => 5, 'extentasofcatalogue' => 6, 'numpages' => 7, 'imageurl' => 8, 'imageurn' => 9, 'licenseId' => 10, ),
        BasePeer::TYPE_COLNAME => array (ImagesourcePeer::ID => 0, ImagesourcePeer::PUBLICATION_ID => 1, ImagesourcePeer::PARTNER_ID => 2, ImagesourcePeer::CATALOGUESIGNATURE => 3, ImagesourcePeer::CATALOGUEURL => 4, ImagesourcePeer::NUMFAKSIMILES => 5, ImagesourcePeer::EXTENTASOFCATALOGUE => 6, ImagesourcePeer::NUMPAGES => 7, ImagesourcePeer::IMAGEURL => 8, ImagesourcePeer::IMAGEURN => 9, ImagesourcePeer::LICENSE_ID => 10, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'PUBLICATION_ID' => 1, 'PARTNER_ID' => 2, 'CATALOGUESIGNATURE' => 3, 'CATALOGUEURL' => 4, 'NUMFAKSIMILES' => 5, 'EXTENTASOFCATALOGUE' => 6, 'NUMPAGES' => 7, 'IMAGEURL' => 8, 'IMAGEURN' => 9, 'LICENSE_ID' => 10, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'publication_id' => 1, 'partner_id' => 2, 'cataloguesignature' => 3, 'catalogueurl' => 4, 'numfaksimiles' => 5, 'extentasofcatalogue' => 6, 'numpages' => 7, 'imageurl' => 8, 'imageurn' => 9, 'license_id' => 10, ),
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
        $toNames = ImagesourcePeer::getFieldNames($toType);
        $key = isset(ImagesourcePeer::$fieldKeys[$fromType][$name]) ? ImagesourcePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(ImagesourcePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, ImagesourcePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return ImagesourcePeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. ImagesourcePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(ImagesourcePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(ImagesourcePeer::ID);
            $criteria->addSelectColumn(ImagesourcePeer::PUBLICATION_ID);
            $criteria->addSelectColumn(ImagesourcePeer::PARTNER_ID);
            $criteria->addSelectColumn(ImagesourcePeer::CATALOGUESIGNATURE);
            $criteria->addSelectColumn(ImagesourcePeer::CATALOGUEURL);
            $criteria->addSelectColumn(ImagesourcePeer::NUMFAKSIMILES);
            $criteria->addSelectColumn(ImagesourcePeer::EXTENTASOFCATALOGUE);
            $criteria->addSelectColumn(ImagesourcePeer::NUMPAGES);
            $criteria->addSelectColumn(ImagesourcePeer::IMAGEURL);
            $criteria->addSelectColumn(ImagesourcePeer::IMAGEURN);
            $criteria->addSelectColumn(ImagesourcePeer::LICENSE_ID);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.publication_id');
            $criteria->addSelectColumn($alias . '.partner_id');
            $criteria->addSelectColumn($alias . '.cataloguesignature');
            $criteria->addSelectColumn($alias . '.catalogueurl');
            $criteria->addSelectColumn($alias . '.numfaksimiles');
            $criteria->addSelectColumn($alias . '.extentasofcatalogue');
            $criteria->addSelectColumn($alias . '.numpages');
            $criteria->addSelectColumn($alias . '.imageurl');
            $criteria->addSelectColumn($alias . '.imageurn');
            $criteria->addSelectColumn($alias . '.license_id');
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
        $criteria->setPrimaryTableName(ImagesourcePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ImagesourcePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(ImagesourcePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(ImagesourcePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Imagesource
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = ImagesourcePeer::doSelect($critcopy, $con);
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
        return ImagesourcePeer::populateObjects(ImagesourcePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(ImagesourcePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            ImagesourcePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);

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
     * @param      Imagesource $obj A Imagesource object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            ImagesourcePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A Imagesource object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Imagesource) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Imagesource object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(ImagesourcePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return   Imagesource Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(ImagesourcePeer::$instances[$key])) {
                return ImagesourcePeer::$instances[$key];
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
        foreach (ImagesourcePeer::$instances as $instance)
        {
          $instance->clearAllReferences(true);
        }
      }
        ImagesourcePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to imagesource
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
        $cls = ImagesourcePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = ImagesourcePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = ImagesourcePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ImagesourcePeer::addInstanceToPool($obj, $key);
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
     * @return array (Imagesource object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = ImagesourcePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = ImagesourcePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + ImagesourcePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ImagesourcePeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            ImagesourcePeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
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
        $criteria->setPrimaryTableName(ImagesourcePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ImagesourcePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ImagesourcePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ImagesourcePeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related License table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinLicense(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ImagesourcePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ImagesourcePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ImagesourcePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ImagesourcePeer::LICENSE_ID, LicensePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Partner table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPartner(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ImagesourcePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ImagesourcePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ImagesourcePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ImagesourcePeer::PARTNER_ID, PartnerPeer::ID, $join_behavior);

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
     * Selects a collection of Imagesource objects pre-filled with their Publication objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Imagesource objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPublication(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);
        }

        ImagesourcePeer::addSelectColumns($criteria);
        $startcol = ImagesourcePeer::NUM_HYDRATE_COLUMNS;
        PublicationPeer::addSelectColumns($criteria);

        $criteria->addJoin(ImagesourcePeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ImagesourcePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ImagesourcePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ImagesourcePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ImagesourcePeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Imagesource) to $obj2 (Publication)
                $obj2->addImagesource($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Imagesource objects pre-filled with their License objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Imagesource objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinLicense(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);
        }

        ImagesourcePeer::addSelectColumns($criteria);
        $startcol = ImagesourcePeer::NUM_HYDRATE_COLUMNS;
        LicensePeer::addSelectColumns($criteria);

        $criteria->addJoin(ImagesourcePeer::LICENSE_ID, LicensePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ImagesourcePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ImagesourcePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ImagesourcePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ImagesourcePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = LicensePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = LicensePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = LicensePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    LicensePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Imagesource) to $obj2 (License)
                $obj2->addImagesource($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Imagesource objects pre-filled with their Partner objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Imagesource objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPartner(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);
        }

        ImagesourcePeer::addSelectColumns($criteria);
        $startcol = ImagesourcePeer::NUM_HYDRATE_COLUMNS;
        PartnerPeer::addSelectColumns($criteria);

        $criteria->addJoin(ImagesourcePeer::PARTNER_ID, PartnerPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ImagesourcePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ImagesourcePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ImagesourcePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ImagesourcePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PartnerPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PartnerPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PartnerPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PartnerPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Imagesource) to $obj2 (Partner)
                $obj2->addImagesource($obj1);

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
        $criteria->setPrimaryTableName(ImagesourcePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ImagesourcePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ImagesourcePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ImagesourcePeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(ImagesourcePeer::LICENSE_ID, LicensePeer::ID, $join_behavior);

        $criteria->addJoin(ImagesourcePeer::PARTNER_ID, PartnerPeer::ID, $join_behavior);

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
     * Selects a collection of Imagesource objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Imagesource objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);
        }

        ImagesourcePeer::addSelectColumns($criteria);
        $startcol2 = ImagesourcePeer::NUM_HYDRATE_COLUMNS;

        PublicationPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PublicationPeer::NUM_HYDRATE_COLUMNS;

        LicensePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + LicensePeer::NUM_HYDRATE_COLUMNS;

        PartnerPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PartnerPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ImagesourcePeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(ImagesourcePeer::LICENSE_ID, LicensePeer::ID, $join_behavior);

        $criteria->addJoin(ImagesourcePeer::PARTNER_ID, PartnerPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ImagesourcePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ImagesourcePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ImagesourcePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ImagesourcePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Publication rows

            $key2 = PublicationPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = PublicationPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PublicationPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PublicationPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (Imagesource) to the collection in $obj2 (Publication)
                $obj2->addImagesource($obj1);
            } // if joined row not null

            // Add objects for joined License rows

            $key3 = LicensePeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = LicensePeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = LicensePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    LicensePeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (Imagesource) to the collection in $obj3 (License)
                $obj3->addImagesource($obj1);
            } // if joined row not null

            // Add objects for joined Partner rows

            $key4 = PartnerPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = PartnerPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = PartnerPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PartnerPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (Imagesource) to the collection in $obj4 (Partner)
                $obj4->addImagesource($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
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
        $criteria->setPrimaryTableName(ImagesourcePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ImagesourcePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ImagesourcePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ImagesourcePeer::LICENSE_ID, LicensePeer::ID, $join_behavior);

        $criteria->addJoin(ImagesourcePeer::PARTNER_ID, PartnerPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related License table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptLicense(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ImagesourcePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ImagesourcePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ImagesourcePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ImagesourcePeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(ImagesourcePeer::PARTNER_ID, PartnerPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Partner table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPartner(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ImagesourcePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ImagesourcePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ImagesourcePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ImagesourcePeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(ImagesourcePeer::LICENSE_ID, LicensePeer::ID, $join_behavior);

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
     * Selects a collection of Imagesource objects pre-filled with all related objects except Publication.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Imagesource objects.
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
            $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);
        }

        ImagesourcePeer::addSelectColumns($criteria);
        $startcol2 = ImagesourcePeer::NUM_HYDRATE_COLUMNS;

        LicensePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + LicensePeer::NUM_HYDRATE_COLUMNS;

        PartnerPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PartnerPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ImagesourcePeer::LICENSE_ID, LicensePeer::ID, $join_behavior);

        $criteria->addJoin(ImagesourcePeer::PARTNER_ID, PartnerPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ImagesourcePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ImagesourcePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ImagesourcePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ImagesourcePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined License rows

                $key2 = LicensePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = LicensePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = LicensePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    LicensePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Imagesource) to the collection in $obj2 (License)
                $obj2->addImagesource($obj1);

            } // if joined row is not null

                // Add objects for joined Partner rows

                $key3 = PartnerPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PartnerPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PartnerPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PartnerPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Imagesource) to the collection in $obj3 (Partner)
                $obj3->addImagesource($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Imagesource objects pre-filled with all related objects except License.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Imagesource objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptLicense(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);
        }

        ImagesourcePeer::addSelectColumns($criteria);
        $startcol2 = ImagesourcePeer::NUM_HYDRATE_COLUMNS;

        PublicationPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PublicationPeer::NUM_HYDRATE_COLUMNS;

        PartnerPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PartnerPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ImagesourcePeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(ImagesourcePeer::PARTNER_ID, PartnerPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ImagesourcePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ImagesourcePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ImagesourcePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ImagesourcePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Publication rows

                $key2 = PublicationPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = PublicationPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = PublicationPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PublicationPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Imagesource) to the collection in $obj2 (Publication)
                $obj2->addImagesource($obj1);

            } // if joined row is not null

                // Add objects for joined Partner rows

                $key3 = PartnerPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PartnerPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PartnerPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PartnerPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Imagesource) to the collection in $obj3 (Partner)
                $obj3->addImagesource($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Imagesource objects pre-filled with all related objects except Partner.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Imagesource objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPartner(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);
        }

        ImagesourcePeer::addSelectColumns($criteria);
        $startcol2 = ImagesourcePeer::NUM_HYDRATE_COLUMNS;

        PublicationPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PublicationPeer::NUM_HYDRATE_COLUMNS;

        LicensePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + LicensePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ImagesourcePeer::PUBLICATION_ID, PublicationPeer::ID, $join_behavior);

        $criteria->addJoin(ImagesourcePeer::LICENSE_ID, LicensePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ImagesourcePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ImagesourcePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ImagesourcePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ImagesourcePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Publication rows

                $key2 = PublicationPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = PublicationPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = PublicationPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PublicationPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Imagesource) to the collection in $obj2 (Publication)
                $obj2->addImagesource($obj1);

            } // if joined row is not null

                // Add objects for joined License rows

                $key3 = LicensePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = LicensePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = LicensePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    LicensePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Imagesource) to the collection in $obj3 (License)
                $obj3->addImagesource($obj1);

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
        return Propel::getDatabaseMap(ImagesourcePeer::DATABASE_NAME)->getTable(ImagesourcePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseImagesourcePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseImagesourcePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new ImagesourceTableMap());
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
        return ImagesourcePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Imagesource or Criteria object.
     *
     * @param      mixed $values Criteria or Imagesource object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ImagesourcePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Imagesource object
        }

        if ($criteria->containsKey(ImagesourcePeer::ID) && $criteria->keyContainsValue(ImagesourcePeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ImagesourcePeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a Imagesource or Criteria object.
     *
     * @param      mixed $values Criteria or Imagesource object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ImagesourcePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(ImagesourcePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(ImagesourcePeer::ID);
            $value = $criteria->remove(ImagesourcePeer::ID);
            if ($value) {
                $selectCriteria->add(ImagesourcePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(ImagesourcePeer::TABLE_NAME);
            }

        } else { // $values is Imagesource object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the imagesource table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ImagesourcePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(ImagesourcePeer::TABLE_NAME, $con, ImagesourcePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ImagesourcePeer::clearInstancePool();
            ImagesourcePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Imagesource or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Imagesource object or primary key or array of primary keys
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
            $con = Propel::getConnection(ImagesourcePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            ImagesourcePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Imagesource) { // it's a model object
            // invalidate the cache for this single object
            ImagesourcePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ImagesourcePeer::DATABASE_NAME);
            $criteria->add(ImagesourcePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                ImagesourcePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(ImagesourcePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            ImagesourcePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given Imagesource object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      Imagesource $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(ImagesourcePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(ImagesourcePeer::TABLE_NAME);

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

        return BasePeer::doValidate(ImagesourcePeer::DATABASE_NAME, ImagesourcePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Imagesource
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = ImagesourcePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(ImagesourcePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(ImagesourcePeer::DATABASE_NAME);
        $criteria->add(ImagesourcePeer::ID, $pk);

        $v = ImagesourcePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Imagesource[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ImagesourcePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(ImagesourcePeer::DATABASE_NAME);
            $criteria->add(ImagesourcePeer::ID, $pks, Criteria::IN);
            $objs = ImagesourcePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseImagesourcePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseImagesourcePeer::buildTableMap();

