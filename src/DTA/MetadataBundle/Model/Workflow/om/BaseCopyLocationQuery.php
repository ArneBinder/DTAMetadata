<?php

namespace DTA\MetadataBundle\Model\Workflow\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use DTA\MetadataBundle\Model\Data\Publication;
use DTA\MetadataBundle\Model\Workflow\CopyLocation;
use DTA\MetadataBundle\Model\Workflow\CopyLocationPeer;
use DTA\MetadataBundle\Model\Workflow\CopyLocationQuery;
use DTA\MetadataBundle\Model\Workflow\License;
use DTA\MetadataBundle\Model\Workflow\Partner;
use DTA\MetadataBundle\Model\Workflow\Task;

/**
 * @method CopyLocationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method CopyLocationQuery orderByPublicationId($order = Criteria::ASC) Order by the publication_id column
 * @method CopyLocationQuery orderByPartnerId($order = Criteria::ASC) Order by the partner_id column
 * @method CopyLocationQuery orderByCatalogueSignature($order = Criteria::ASC) Order by the catalogue_signature column
 * @method CopyLocationQuery orderByCatalogueInternal($order = Criteria::ASC) Order by the catalogue_internal column
 * @method CopyLocationQuery orderByCatalogueUrl($order = Criteria::ASC) Order by the catalogue_url column
 * @method CopyLocationQuery orderByNumfaksimiles($order = Criteria::ASC) Order by the numfaksimiles column
 * @method CopyLocationQuery orderByCatalogueExtent($order = Criteria::ASC) Order by the catalogue_extent column
 * @method CopyLocationQuery orderByAvailability($order = Criteria::ASC) Order by the availability column
 * @method CopyLocationQuery orderByComments($order = Criteria::ASC) Order by the comments column
 * @method CopyLocationQuery orderByImageurl($order = Criteria::ASC) Order by the imageurl column
 * @method CopyLocationQuery orderByImageurn($order = Criteria::ASC) Order by the imageurn column
 * @method CopyLocationQuery orderByLicenseId($order = Criteria::ASC) Order by the license_id column
 * @method CopyLocationQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method CopyLocationQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method CopyLocationQuery groupById() Group by the id column
 * @method CopyLocationQuery groupByPublicationId() Group by the publication_id column
 * @method CopyLocationQuery groupByPartnerId() Group by the partner_id column
 * @method CopyLocationQuery groupByCatalogueSignature() Group by the catalogue_signature column
 * @method CopyLocationQuery groupByCatalogueInternal() Group by the catalogue_internal column
 * @method CopyLocationQuery groupByCatalogueUrl() Group by the catalogue_url column
 * @method CopyLocationQuery groupByNumfaksimiles() Group by the numfaksimiles column
 * @method CopyLocationQuery groupByCatalogueExtent() Group by the catalogue_extent column
 * @method CopyLocationQuery groupByAvailability() Group by the availability column
 * @method CopyLocationQuery groupByComments() Group by the comments column
 * @method CopyLocationQuery groupByImageurl() Group by the imageurl column
 * @method CopyLocationQuery groupByImageurn() Group by the imageurn column
 * @method CopyLocationQuery groupByLicenseId() Group by the license_id column
 * @method CopyLocationQuery groupByCreatedAt() Group by the created_at column
 * @method CopyLocationQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method CopyLocationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CopyLocationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CopyLocationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CopyLocationQuery leftJoinPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publication relation
 * @method CopyLocationQuery rightJoinPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publication relation
 * @method CopyLocationQuery innerJoinPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the Publication relation
 *
 * @method CopyLocationQuery leftJoinPartner($relationAlias = null) Adds a LEFT JOIN clause to the query using the Partner relation
 * @method CopyLocationQuery rightJoinPartner($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Partner relation
 * @method CopyLocationQuery innerJoinPartner($relationAlias = null) Adds a INNER JOIN clause to the query using the Partner relation
 *
 * @method CopyLocationQuery leftJoinLicense($relationAlias = null) Adds a LEFT JOIN clause to the query using the License relation
 * @method CopyLocationQuery rightJoinLicense($relationAlias = null) Adds a RIGHT JOIN clause to the query using the License relation
 * @method CopyLocationQuery innerJoinLicense($relationAlias = null) Adds a INNER JOIN clause to the query using the License relation
 *
 * @method CopyLocationQuery leftJoinTask($relationAlias = null) Adds a LEFT JOIN clause to the query using the Task relation
 * @method CopyLocationQuery rightJoinTask($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Task relation
 * @method CopyLocationQuery innerJoinTask($relationAlias = null) Adds a INNER JOIN clause to the query using the Task relation
 *
 * @method CopyLocation findOne(PropelPDO $con = null) Return the first CopyLocation matching the query
 * @method CopyLocation findOneOrCreate(PropelPDO $con = null) Return the first CopyLocation matching the query, or a new CopyLocation object populated from the query conditions when no match is found
 *
 * @method CopyLocation findOneByPublicationId(int $publication_id) Return the first CopyLocation filtered by the publication_id column
 * @method CopyLocation findOneByPartnerId(int $partner_id) Return the first CopyLocation filtered by the partner_id column
 * @method CopyLocation findOneByCatalogueSignature(string $catalogue_signature) Return the first CopyLocation filtered by the catalogue_signature column
 * @method CopyLocation findOneByCatalogueInternal(string $catalogue_internal) Return the first CopyLocation filtered by the catalogue_internal column
 * @method CopyLocation findOneByCatalogueUrl(string $catalogue_url) Return the first CopyLocation filtered by the catalogue_url column
 * @method CopyLocation findOneByNumfaksimiles(int $numfaksimiles) Return the first CopyLocation filtered by the numfaksimiles column
 * @method CopyLocation findOneByCatalogueExtent(string $catalogue_extent) Return the first CopyLocation filtered by the catalogue_extent column
 * @method CopyLocation findOneByAvailability(boolean $availability) Return the first CopyLocation filtered by the availability column
 * @method CopyLocation findOneByComments(string $comments) Return the first CopyLocation filtered by the comments column
 * @method CopyLocation findOneByImageurl(string $imageurl) Return the first CopyLocation filtered by the imageurl column
 * @method CopyLocation findOneByImageurn(string $imageurn) Return the first CopyLocation filtered by the imageurn column
 * @method CopyLocation findOneByLicenseId(int $license_id) Return the first CopyLocation filtered by the license_id column
 * @method CopyLocation findOneByCreatedAt(string $created_at) Return the first CopyLocation filtered by the created_at column
 * @method CopyLocation findOneByUpdatedAt(string $updated_at) Return the first CopyLocation filtered by the updated_at column
 *
 * @method array findById(int $id) Return CopyLocation objects filtered by the id column
 * @method array findByPublicationId(int $publication_id) Return CopyLocation objects filtered by the publication_id column
 * @method array findByPartnerId(int $partner_id) Return CopyLocation objects filtered by the partner_id column
 * @method array findByCatalogueSignature(string $catalogue_signature) Return CopyLocation objects filtered by the catalogue_signature column
 * @method array findByCatalogueInternal(string $catalogue_internal) Return CopyLocation objects filtered by the catalogue_internal column
 * @method array findByCatalogueUrl(string $catalogue_url) Return CopyLocation objects filtered by the catalogue_url column
 * @method array findByNumfaksimiles(int $numfaksimiles) Return CopyLocation objects filtered by the numfaksimiles column
 * @method array findByCatalogueExtent(string $catalogue_extent) Return CopyLocation objects filtered by the catalogue_extent column
 * @method array findByAvailability(boolean $availability) Return CopyLocation objects filtered by the availability column
 * @method array findByComments(string $comments) Return CopyLocation objects filtered by the comments column
 * @method array findByImageurl(string $imageurl) Return CopyLocation objects filtered by the imageurl column
 * @method array findByImageurn(string $imageurn) Return CopyLocation objects filtered by the imageurn column
 * @method array findByLicenseId(int $license_id) Return CopyLocation objects filtered by the license_id column
 * @method array findByCreatedAt(string $created_at) Return CopyLocation objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return CopyLocation objects filtered by the updated_at column
 */
abstract class BaseCopyLocationQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCopyLocationQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'dtametadata';
        }
        if (null === $modelName) {
            $modelName = 'DTA\\MetadataBundle\\Model\\Workflow\\CopyLocation';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CopyLocationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CopyLocationQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CopyLocationQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CopyLocationQuery) {
            return $criteria;
        }
        $query = new CopyLocationQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   CopyLocation|CopyLocation[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CopyLocationPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CopyLocationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 CopyLocation A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 CopyLocation A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "id", "publication_id", "partner_id", "catalogue_signature", "catalogue_internal", "catalogue_url", "numfaksimiles", "catalogue_extent", "availability", "comments", "imageurl", "imageurn", "license_id", "created_at", "updated_at" FROM "copy_location" WHERE "id" = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new CopyLocation();
            $obj->hydrate($row);
            CopyLocationPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return CopyLocation|CopyLocation[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|CopyLocation[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CopyLocationPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CopyLocationPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CopyLocationPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CopyLocationPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CopyLocationPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the publication_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublicationId(1234); // WHERE publication_id = 1234
     * $query->filterByPublicationId(array(12, 34)); // WHERE publication_id IN (12, 34)
     * $query->filterByPublicationId(array('min' => 12)); // WHERE publication_id >= 12
     * $query->filterByPublicationId(array('max' => 12)); // WHERE publication_id <= 12
     * </code>
     *
     * @see       filterByPublication()
     *
     * @param     mixed $publicationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByPublicationId($publicationId = null, $comparison = null)
    {
        if (is_array($publicationId)) {
            $useMinMax = false;
            if (isset($publicationId['min'])) {
                $this->addUsingAlias(CopyLocationPeer::PUBLICATION_ID, $publicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publicationId['max'])) {
                $this->addUsingAlias(CopyLocationPeer::PUBLICATION_ID, $publicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CopyLocationPeer::PUBLICATION_ID, $publicationId, $comparison);
    }

    /**
     * Filter the query on the partner_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPartnerId(1234); // WHERE partner_id = 1234
     * $query->filterByPartnerId(array(12, 34)); // WHERE partner_id IN (12, 34)
     * $query->filterByPartnerId(array('min' => 12)); // WHERE partner_id >= 12
     * $query->filterByPartnerId(array('max' => 12)); // WHERE partner_id <= 12
     * </code>
     *
     * @see       filterByPartner()
     *
     * @param     mixed $partnerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByPartnerId($partnerId = null, $comparison = null)
    {
        if (is_array($partnerId)) {
            $useMinMax = false;
            if (isset($partnerId['min'])) {
                $this->addUsingAlias(CopyLocationPeer::PARTNER_ID, $partnerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($partnerId['max'])) {
                $this->addUsingAlias(CopyLocationPeer::PARTNER_ID, $partnerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CopyLocationPeer::PARTNER_ID, $partnerId, $comparison);
    }

    /**
     * Filter the query on the catalogue_signature column
     *
     * Example usage:
     * <code>
     * $query->filterByCatalogueSignature('fooValue');   // WHERE catalogue_signature = 'fooValue'
     * $query->filterByCatalogueSignature('%fooValue%'); // WHERE catalogue_signature LIKE '%fooValue%'
     * </code>
     *
     * @param     string $catalogueSignature The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByCatalogueSignature($catalogueSignature = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($catalogueSignature)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $catalogueSignature)) {
                $catalogueSignature = str_replace('*', '%', $catalogueSignature);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CopyLocationPeer::CATALOGUE_SIGNATURE, $catalogueSignature, $comparison);
    }

    /**
     * Filter the query on the catalogue_internal column
     *
     * Example usage:
     * <code>
     * $query->filterByCatalogueInternal('fooValue');   // WHERE catalogue_internal = 'fooValue'
     * $query->filterByCatalogueInternal('%fooValue%'); // WHERE catalogue_internal LIKE '%fooValue%'
     * </code>
     *
     * @param     string $catalogueInternal The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByCatalogueInternal($catalogueInternal = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($catalogueInternal)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $catalogueInternal)) {
                $catalogueInternal = str_replace('*', '%', $catalogueInternal);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CopyLocationPeer::CATALOGUE_INTERNAL, $catalogueInternal, $comparison);
    }

    /**
     * Filter the query on the catalogue_url column
     *
     * Example usage:
     * <code>
     * $query->filterByCatalogueUrl('fooValue');   // WHERE catalogue_url = 'fooValue'
     * $query->filterByCatalogueUrl('%fooValue%'); // WHERE catalogue_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $catalogueUrl The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByCatalogueUrl($catalogueUrl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($catalogueUrl)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $catalogueUrl)) {
                $catalogueUrl = str_replace('*', '%', $catalogueUrl);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CopyLocationPeer::CATALOGUE_URL, $catalogueUrl, $comparison);
    }

    /**
     * Filter the query on the numfaksimiles column
     *
     * Example usage:
     * <code>
     * $query->filterByNumfaksimiles(1234); // WHERE numfaksimiles = 1234
     * $query->filterByNumfaksimiles(array(12, 34)); // WHERE numfaksimiles IN (12, 34)
     * $query->filterByNumfaksimiles(array('min' => 12)); // WHERE numfaksimiles >= 12
     * $query->filterByNumfaksimiles(array('max' => 12)); // WHERE numfaksimiles <= 12
     * </code>
     *
     * @param     mixed $numfaksimiles The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByNumfaksimiles($numfaksimiles = null, $comparison = null)
    {
        if (is_array($numfaksimiles)) {
            $useMinMax = false;
            if (isset($numfaksimiles['min'])) {
                $this->addUsingAlias(CopyLocationPeer::NUMFAKSIMILES, $numfaksimiles['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numfaksimiles['max'])) {
                $this->addUsingAlias(CopyLocationPeer::NUMFAKSIMILES, $numfaksimiles['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CopyLocationPeer::NUMFAKSIMILES, $numfaksimiles, $comparison);
    }

    /**
     * Filter the query on the catalogue_extent column
     *
     * Example usage:
     * <code>
     * $query->filterByCatalogueExtent('fooValue');   // WHERE catalogue_extent = 'fooValue'
     * $query->filterByCatalogueExtent('%fooValue%'); // WHERE catalogue_extent LIKE '%fooValue%'
     * </code>
     *
     * @param     string $catalogueExtent The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByCatalogueExtent($catalogueExtent = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($catalogueExtent)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $catalogueExtent)) {
                $catalogueExtent = str_replace('*', '%', $catalogueExtent);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CopyLocationPeer::CATALOGUE_EXTENT, $catalogueExtent, $comparison);
    }

    /**
     * Filter the query on the availability column
     *
     * Example usage:
     * <code>
     * $query->filterByAvailability(true); // WHERE availability = true
     * $query->filterByAvailability('yes'); // WHERE availability = true
     * </code>
     *
     * @param     boolean|string $availability The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByAvailability($availability = null, $comparison = null)
    {
        if (is_string($availability)) {
            $availability = in_array(strtolower($availability), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CopyLocationPeer::AVAILABILITY, $availability, $comparison);
    }

    /**
     * Filter the query on the comments column
     *
     * Example usage:
     * <code>
     * $query->filterByComments('fooValue');   // WHERE comments = 'fooValue'
     * $query->filterByComments('%fooValue%'); // WHERE comments LIKE '%fooValue%'
     * </code>
     *
     * @param     string $comments The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByComments($comments = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($comments)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $comments)) {
                $comments = str_replace('*', '%', $comments);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CopyLocationPeer::COMMENTS, $comments, $comparison);
    }

    /**
     * Filter the query on the imageurl column
     *
     * Example usage:
     * <code>
     * $query->filterByImageurl('fooValue');   // WHERE imageurl = 'fooValue'
     * $query->filterByImageurl('%fooValue%'); // WHERE imageurl LIKE '%fooValue%'
     * </code>
     *
     * @param     string $imageurl The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByImageurl($imageurl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($imageurl)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $imageurl)) {
                $imageurl = str_replace('*', '%', $imageurl);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CopyLocationPeer::IMAGEURL, $imageurl, $comparison);
    }

    /**
     * Filter the query on the imageurn column
     *
     * Example usage:
     * <code>
     * $query->filterByImageurn('fooValue');   // WHERE imageurn = 'fooValue'
     * $query->filterByImageurn('%fooValue%'); // WHERE imageurn LIKE '%fooValue%'
     * </code>
     *
     * @param     string $imageurn The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByImageurn($imageurn = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($imageurn)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $imageurn)) {
                $imageurn = str_replace('*', '%', $imageurn);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CopyLocationPeer::IMAGEURN, $imageurn, $comparison);
    }

    /**
     * Filter the query on the license_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLicenseId(1234); // WHERE license_id = 1234
     * $query->filterByLicenseId(array(12, 34)); // WHERE license_id IN (12, 34)
     * $query->filterByLicenseId(array('min' => 12)); // WHERE license_id >= 12
     * $query->filterByLicenseId(array('max' => 12)); // WHERE license_id <= 12
     * </code>
     *
     * @see       filterByLicense()
     *
     * @param     mixed $licenseId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByLicenseId($licenseId = null, $comparison = null)
    {
        if (is_array($licenseId)) {
            $useMinMax = false;
            if (isset($licenseId['min'])) {
                $this->addUsingAlias(CopyLocationPeer::LICENSE_ID, $licenseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($licenseId['max'])) {
                $this->addUsingAlias(CopyLocationPeer::LICENSE_ID, $licenseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CopyLocationPeer::LICENSE_ID, $licenseId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CopyLocationPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CopyLocationPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CopyLocationPeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CopyLocationPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CopyLocationPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CopyLocationPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Publication object
     *
     * @param   Publication|PropelObjectCollection $publication The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CopyLocationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublication($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(CopyLocationPeer::PUBLICATION_ID, $publication->getId(), $comparison);
        } elseif ($publication instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CopyLocationPeer::PUBLICATION_ID, $publication->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPublication() only accepts arguments of type Publication or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Publication relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function joinPublication($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Publication');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Publication');
        }

        return $this;
    }

    /**
     * Use the Publication relation Publication object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationQuery A secondary query class using the current class as primary query
     */
    public function usePublicationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publication', '\DTA\MetadataBundle\Model\Data\PublicationQuery');
    }

    /**
     * Filter the query by a related Partner object
     *
     * @param   Partner|PropelObjectCollection $partner The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CopyLocationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPartner($partner, $comparison = null)
    {
        if ($partner instanceof Partner) {
            return $this
                ->addUsingAlias(CopyLocationPeer::PARTNER_ID, $partner->getId(), $comparison);
        } elseif ($partner instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CopyLocationPeer::PARTNER_ID, $partner->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPartner() only accepts arguments of type Partner or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Partner relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function joinPartner($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Partner');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Partner');
        }

        return $this;
    }

    /**
     * Use the Partner relation Partner object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Workflow\PartnerQuery A secondary query class using the current class as primary query
     */
    public function usePartnerQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPartner($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Partner', '\DTA\MetadataBundle\Model\Workflow\PartnerQuery');
    }

    /**
     * Filter the query by a related License object
     *
     * @param   License|PropelObjectCollection $license The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CopyLocationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByLicense($license, $comparison = null)
    {
        if ($license instanceof License) {
            return $this
                ->addUsingAlias(CopyLocationPeer::LICENSE_ID, $license->getId(), $comparison);
        } elseif ($license instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CopyLocationPeer::LICENSE_ID, $license->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLicense() only accepts arguments of type License or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the License relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function joinLicense($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('License');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'License');
        }

        return $this;
    }

    /**
     * Use the License relation License object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Workflow\LicenseQuery A secondary query class using the current class as primary query
     */
    public function useLicenseQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLicense($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'License', '\DTA\MetadataBundle\Model\Workflow\LicenseQuery');
    }

    /**
     * Filter the query by a related Task object
     *
     * @param   Task|PropelObjectCollection $task  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CopyLocationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTask($task, $comparison = null)
    {
        if ($task instanceof Task) {
            return $this
                ->addUsingAlias(CopyLocationPeer::ID, $task->getCopylocationId(), $comparison);
        } elseif ($task instanceof PropelObjectCollection) {
            return $this
                ->useTaskQuery()
                ->filterByPrimaryKeys($task->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTask() only accepts arguments of type Task or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Task relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function joinTask($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Task');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Task');
        }

        return $this;
    }

    /**
     * Use the Task relation Task object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Workflow\TaskQuery A secondary query class using the current class as primary query
     */
    public function useTaskQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTask($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Task', '\DTA\MetadataBundle\Model\Workflow\TaskQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   CopyLocation $copyLocation Object to remove from the list of results
     *
     * @return CopyLocationQuery The current query, for fluid interface
     */
    public function prune($copyLocation = null)
    {
        if ($copyLocation) {
            $this->addUsingAlias(CopyLocationPeer::ID, $copyLocation->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     CopyLocationQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CopyLocationPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     CopyLocationQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CopyLocationPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     CopyLocationQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CopyLocationPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     CopyLocationQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CopyLocationPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     CopyLocationQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CopyLocationPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     CopyLocationQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CopyLocationPeer::CREATED_AT);
    }
}
