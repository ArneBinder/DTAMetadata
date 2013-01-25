<?php

namespace DTA\MetadataBundle\Model\om;

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
use DTA\MetadataBundle\Model\Author;
use DTA\MetadataBundle\Model\AuthorWork;
use DTA\MetadataBundle\Model\Datespecification;
use DTA\MetadataBundle\Model\Dwdsgenre;
use DTA\MetadataBundle\Model\Essay;
use DTA\MetadataBundle\Model\Genre;
use DTA\MetadataBundle\Model\Magazine;
use DTA\MetadataBundle\Model\Publication;
use DTA\MetadataBundle\Model\Series;
use DTA\MetadataBundle\Model\Status;
use DTA\MetadataBundle\Model\Work;
use DTA\MetadataBundle\Model\WorkPeer;
use DTA\MetadataBundle\Model\WorkQuery;

/**
 * @method WorkQuery orderById($order = Criteria::ASC) Order by the id column
 * @method WorkQuery orderByStatusId($order = Criteria::ASC) Order by the status_id column
 * @method WorkQuery orderByDatespecificationId($order = Criteria::ASC) Order by the dateSpecification_id column
 * @method WorkQuery orderByGenreId($order = Criteria::ASC) Order by the genre_id column
 * @method WorkQuery orderBySubgenreId($order = Criteria::ASC) Order by the subgenre_id column
 * @method WorkQuery orderByDwdsgenreId($order = Criteria::ASC) Order by the dwdsGenre_id column
 * @method WorkQuery orderByDwdssubgenreId($order = Criteria::ASC) Order by the dwdsSubgenre_id column
 * @method WorkQuery orderByDoi($order = Criteria::ASC) Order by the doi column
 * @method WorkQuery orderByComments($order = Criteria::ASC) Order by the comments column
 * @method WorkQuery orderByFormat($order = Criteria::ASC) Order by the format column
 * @method WorkQuery orderByDirectoryname($order = Criteria::ASC) Order by the directoryName column
 *
 * @method WorkQuery groupById() Group by the id column
 * @method WorkQuery groupByStatusId() Group by the status_id column
 * @method WorkQuery groupByDatespecificationId() Group by the dateSpecification_id column
 * @method WorkQuery groupByGenreId() Group by the genre_id column
 * @method WorkQuery groupBySubgenreId() Group by the subgenre_id column
 * @method WorkQuery groupByDwdsgenreId() Group by the dwdsGenre_id column
 * @method WorkQuery groupByDwdssubgenreId() Group by the dwdsSubgenre_id column
 * @method WorkQuery groupByDoi() Group by the doi column
 * @method WorkQuery groupByComments() Group by the comments column
 * @method WorkQuery groupByFormat() Group by the format column
 * @method WorkQuery groupByDirectoryname() Group by the directoryName column
 *
 * @method WorkQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method WorkQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method WorkQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method WorkQuery leftJoinStatus($relationAlias = null) Adds a LEFT JOIN clause to the query using the Status relation
 * @method WorkQuery rightJoinStatus($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Status relation
 * @method WorkQuery innerJoinStatus($relationAlias = null) Adds a INNER JOIN clause to the query using the Status relation
 *
 * @method WorkQuery leftJoinGenreRelatedByGenreId($relationAlias = null) Adds a LEFT JOIN clause to the query using the GenreRelatedByGenreId relation
 * @method WorkQuery rightJoinGenreRelatedByGenreId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GenreRelatedByGenreId relation
 * @method WorkQuery innerJoinGenreRelatedByGenreId($relationAlias = null) Adds a INNER JOIN clause to the query using the GenreRelatedByGenreId relation
 *
 * @method WorkQuery leftJoinGenreRelatedBySubgenreId($relationAlias = null) Adds a LEFT JOIN clause to the query using the GenreRelatedBySubgenreId relation
 * @method WorkQuery rightJoinGenreRelatedBySubgenreId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GenreRelatedBySubgenreId relation
 * @method WorkQuery innerJoinGenreRelatedBySubgenreId($relationAlias = null) Adds a INNER JOIN clause to the query using the GenreRelatedBySubgenreId relation
 *
 * @method WorkQuery leftJoinDwdsgenreRelatedByDwdsgenreId($relationAlias = null) Adds a LEFT JOIN clause to the query using the DwdsgenreRelatedByDwdsgenreId relation
 * @method WorkQuery rightJoinDwdsgenreRelatedByDwdsgenreId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DwdsgenreRelatedByDwdsgenreId relation
 * @method WorkQuery innerJoinDwdsgenreRelatedByDwdsgenreId($relationAlias = null) Adds a INNER JOIN clause to the query using the DwdsgenreRelatedByDwdsgenreId relation
 *
 * @method WorkQuery leftJoinDwdsgenreRelatedByDwdssubgenreId($relationAlias = null) Adds a LEFT JOIN clause to the query using the DwdsgenreRelatedByDwdssubgenreId relation
 * @method WorkQuery rightJoinDwdsgenreRelatedByDwdssubgenreId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DwdsgenreRelatedByDwdssubgenreId relation
 * @method WorkQuery innerJoinDwdsgenreRelatedByDwdssubgenreId($relationAlias = null) Adds a INNER JOIN clause to the query using the DwdsgenreRelatedByDwdssubgenreId relation
 *
 * @method WorkQuery leftJoinDatespecification($relationAlias = null) Adds a LEFT JOIN clause to the query using the Datespecification relation
 * @method WorkQuery rightJoinDatespecification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Datespecification relation
 * @method WorkQuery innerJoinDatespecification($relationAlias = null) Adds a INNER JOIN clause to the query using the Datespecification relation
 *
 * @method WorkQuery leftJoinPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publication relation
 * @method WorkQuery rightJoinPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publication relation
 * @method WorkQuery innerJoinPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the Publication relation
 *
 * @method WorkQuery leftJoinAuthorWork($relationAlias = null) Adds a LEFT JOIN clause to the query using the AuthorWork relation
 * @method WorkQuery rightJoinAuthorWork($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AuthorWork relation
 * @method WorkQuery innerJoinAuthorWork($relationAlias = null) Adds a INNER JOIN clause to the query using the AuthorWork relation
 *
 * @method WorkQuery leftJoinEssay($relationAlias = null) Adds a LEFT JOIN clause to the query using the Essay relation
 * @method WorkQuery rightJoinEssay($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Essay relation
 * @method WorkQuery innerJoinEssay($relationAlias = null) Adds a INNER JOIN clause to the query using the Essay relation
 *
 * @method WorkQuery leftJoinMagazine($relationAlias = null) Adds a LEFT JOIN clause to the query using the Magazine relation
 * @method WorkQuery rightJoinMagazine($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Magazine relation
 * @method WorkQuery innerJoinMagazine($relationAlias = null) Adds a INNER JOIN clause to the query using the Magazine relation
 *
 * @method WorkQuery leftJoinSeries($relationAlias = null) Adds a LEFT JOIN clause to the query using the Series relation
 * @method WorkQuery rightJoinSeries($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Series relation
 * @method WorkQuery innerJoinSeries($relationAlias = null) Adds a INNER JOIN clause to the query using the Series relation
 *
 * @method Work findOne(PropelPDO $con = null) Return the first Work matching the query
 * @method Work findOneOrCreate(PropelPDO $con = null) Return the first Work matching the query, or a new Work object populated from the query conditions when no match is found
 *
 * @method Work findOneByStatusId(int $status_id) Return the first Work filtered by the status_id column
 * @method Work findOneByDatespecificationId(int $dateSpecification_id) Return the first Work filtered by the dateSpecification_id column
 * @method Work findOneByGenreId(int $genre_id) Return the first Work filtered by the genre_id column
 * @method Work findOneBySubgenreId(int $subgenre_id) Return the first Work filtered by the subgenre_id column
 * @method Work findOneByDwdsgenreId(int $dwdsGenre_id) Return the first Work filtered by the dwdsGenre_id column
 * @method Work findOneByDwdssubgenreId(int $dwdsSubgenre_id) Return the first Work filtered by the dwdsSubgenre_id column
 * @method Work findOneByDoi(string $doi) Return the first Work filtered by the doi column
 * @method Work findOneByComments(string $comments) Return the first Work filtered by the comments column
 * @method Work findOneByFormat(string $format) Return the first Work filtered by the format column
 * @method Work findOneByDirectoryname(string $directoryName) Return the first Work filtered by the directoryName column
 *
 * @method array findById(int $id) Return Work objects filtered by the id column
 * @method array findByStatusId(int $status_id) Return Work objects filtered by the status_id column
 * @method array findByDatespecificationId(int $dateSpecification_id) Return Work objects filtered by the dateSpecification_id column
 * @method array findByGenreId(int $genre_id) Return Work objects filtered by the genre_id column
 * @method array findBySubgenreId(int $subgenre_id) Return Work objects filtered by the subgenre_id column
 * @method array findByDwdsgenreId(int $dwdsGenre_id) Return Work objects filtered by the dwdsGenre_id column
 * @method array findByDwdssubgenreId(int $dwdsSubgenre_id) Return Work objects filtered by the dwdsSubgenre_id column
 * @method array findByDoi(string $doi) Return Work objects filtered by the doi column
 * @method array findByComments(string $comments) Return Work objects filtered by the comments column
 * @method array findByFormat(string $format) Return Work objects filtered by the format column
 * @method array findByDirectoryname(string $directoryName) Return Work objects filtered by the directoryName column
 */
abstract class BaseWorkQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseWorkQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Work', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new WorkQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   WorkQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return WorkQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof WorkQuery) {
            return $criteria;
        }
        $query = new WorkQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
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
     * @return   Work|Work[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = WorkPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Work A model object, or null if the key is not found
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
     * @return                 Work A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `status_id`, `dateSpecification_id`, `genre_id`, `subgenre_id`, `dwdsGenre_id`, `dwdsSubgenre_id`, `doi`, `comments`, `format`, `directoryName` FROM `work` WHERE `id` = :p0';
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
            $obj = new Work();
            $obj->hydrate($row);
            WorkPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Work|Work[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Work[]|mixed the list of results, formatted by the current formatter
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
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(WorkPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(WorkPeer::ID, $keys, Criteria::IN);
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
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(WorkPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(WorkPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WorkPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the status_id column
     *
     * Example usage:
     * <code>
     * $query->filterByStatusId(1234); // WHERE status_id = 1234
     * $query->filterByStatusId(array(12, 34)); // WHERE status_id IN (12, 34)
     * $query->filterByStatusId(array('min' => 12)); // WHERE status_id >= 12
     * $query->filterByStatusId(array('max' => 12)); // WHERE status_id <= 12
     * </code>
     *
     * @see       filterByStatus()
     *
     * @param     mixed $statusId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByStatusId($statusId = null, $comparison = null)
    {
        if (is_array($statusId)) {
            $useMinMax = false;
            if (isset($statusId['min'])) {
                $this->addUsingAlias(WorkPeer::STATUS_ID, $statusId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($statusId['max'])) {
                $this->addUsingAlias(WorkPeer::STATUS_ID, $statusId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WorkPeer::STATUS_ID, $statusId, $comparison);
    }

    /**
     * Filter the query on the dateSpecification_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDatespecificationId(1234); // WHERE dateSpecification_id = 1234
     * $query->filterByDatespecificationId(array(12, 34)); // WHERE dateSpecification_id IN (12, 34)
     * $query->filterByDatespecificationId(array('min' => 12)); // WHERE dateSpecification_id >= 12
     * $query->filterByDatespecificationId(array('max' => 12)); // WHERE dateSpecification_id <= 12
     * </code>
     *
     * @see       filterByDatespecification()
     *
     * @param     mixed $datespecificationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByDatespecificationId($datespecificationId = null, $comparison = null)
    {
        if (is_array($datespecificationId)) {
            $useMinMax = false;
            if (isset($datespecificationId['min'])) {
                $this->addUsingAlias(WorkPeer::DATESPECIFICATION_ID, $datespecificationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($datespecificationId['max'])) {
                $this->addUsingAlias(WorkPeer::DATESPECIFICATION_ID, $datespecificationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WorkPeer::DATESPECIFICATION_ID, $datespecificationId, $comparison);
    }

    /**
     * Filter the query on the genre_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGenreId(1234); // WHERE genre_id = 1234
     * $query->filterByGenreId(array(12, 34)); // WHERE genre_id IN (12, 34)
     * $query->filterByGenreId(array('min' => 12)); // WHERE genre_id >= 12
     * $query->filterByGenreId(array('max' => 12)); // WHERE genre_id <= 12
     * </code>
     *
     * @see       filterByGenreRelatedByGenreId()
     *
     * @param     mixed $genreId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByGenreId($genreId = null, $comparison = null)
    {
        if (is_array($genreId)) {
            $useMinMax = false;
            if (isset($genreId['min'])) {
                $this->addUsingAlias(WorkPeer::GENRE_ID, $genreId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($genreId['max'])) {
                $this->addUsingAlias(WorkPeer::GENRE_ID, $genreId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WorkPeer::GENRE_ID, $genreId, $comparison);
    }

    /**
     * Filter the query on the subgenre_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySubgenreId(1234); // WHERE subgenre_id = 1234
     * $query->filterBySubgenreId(array(12, 34)); // WHERE subgenre_id IN (12, 34)
     * $query->filterBySubgenreId(array('min' => 12)); // WHERE subgenre_id >= 12
     * $query->filterBySubgenreId(array('max' => 12)); // WHERE subgenre_id <= 12
     * </code>
     *
     * @see       filterByGenreRelatedBySubgenreId()
     *
     * @param     mixed $subgenreId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterBySubgenreId($subgenreId = null, $comparison = null)
    {
        if (is_array($subgenreId)) {
            $useMinMax = false;
            if (isset($subgenreId['min'])) {
                $this->addUsingAlias(WorkPeer::SUBGENRE_ID, $subgenreId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($subgenreId['max'])) {
                $this->addUsingAlias(WorkPeer::SUBGENRE_ID, $subgenreId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WorkPeer::SUBGENRE_ID, $subgenreId, $comparison);
    }

    /**
     * Filter the query on the dwdsGenre_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDwdsgenreId(1234); // WHERE dwdsGenre_id = 1234
     * $query->filterByDwdsgenreId(array(12, 34)); // WHERE dwdsGenre_id IN (12, 34)
     * $query->filterByDwdsgenreId(array('min' => 12)); // WHERE dwdsGenre_id >= 12
     * $query->filterByDwdsgenreId(array('max' => 12)); // WHERE dwdsGenre_id <= 12
     * </code>
     *
     * @see       filterByDwdsgenreRelatedByDwdsgenreId()
     *
     * @param     mixed $dwdsgenreId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByDwdsgenreId($dwdsgenreId = null, $comparison = null)
    {
        if (is_array($dwdsgenreId)) {
            $useMinMax = false;
            if (isset($dwdsgenreId['min'])) {
                $this->addUsingAlias(WorkPeer::DWDSGENRE_ID, $dwdsgenreId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dwdsgenreId['max'])) {
                $this->addUsingAlias(WorkPeer::DWDSGENRE_ID, $dwdsgenreId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WorkPeer::DWDSGENRE_ID, $dwdsgenreId, $comparison);
    }

    /**
     * Filter the query on the dwdsSubgenre_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDwdssubgenreId(1234); // WHERE dwdsSubgenre_id = 1234
     * $query->filterByDwdssubgenreId(array(12, 34)); // WHERE dwdsSubgenre_id IN (12, 34)
     * $query->filterByDwdssubgenreId(array('min' => 12)); // WHERE dwdsSubgenre_id >= 12
     * $query->filterByDwdssubgenreId(array('max' => 12)); // WHERE dwdsSubgenre_id <= 12
     * </code>
     *
     * @see       filterByDwdsgenreRelatedByDwdssubgenreId()
     *
     * @param     mixed $dwdssubgenreId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByDwdssubgenreId($dwdssubgenreId = null, $comparison = null)
    {
        if (is_array($dwdssubgenreId)) {
            $useMinMax = false;
            if (isset($dwdssubgenreId['min'])) {
                $this->addUsingAlias(WorkPeer::DWDSSUBGENRE_ID, $dwdssubgenreId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dwdssubgenreId['max'])) {
                $this->addUsingAlias(WorkPeer::DWDSSUBGENRE_ID, $dwdssubgenreId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WorkPeer::DWDSSUBGENRE_ID, $dwdssubgenreId, $comparison);
    }

    /**
     * Filter the query on the doi column
     *
     * Example usage:
     * <code>
     * $query->filterByDoi('fooValue');   // WHERE doi = 'fooValue'
     * $query->filterByDoi('%fooValue%'); // WHERE doi LIKE '%fooValue%'
     * </code>
     *
     * @param     string $doi The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByDoi($doi = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($doi)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $doi)) {
                $doi = str_replace('*', '%', $doi);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(WorkPeer::DOI, $doi, $comparison);
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
     * @return WorkQuery The current query, for fluid interface
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

        return $this->addUsingAlias(WorkPeer::COMMENTS, $comments, $comparison);
    }

    /**
     * Filter the query on the format column
     *
     * Example usage:
     * <code>
     * $query->filterByFormat('fooValue');   // WHERE format = 'fooValue'
     * $query->filterByFormat('%fooValue%'); // WHERE format LIKE '%fooValue%'
     * </code>
     *
     * @param     string $format The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByFormat($format = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($format)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $format)) {
                $format = str_replace('*', '%', $format);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(WorkPeer::FORMAT, $format, $comparison);
    }

    /**
     * Filter the query on the directoryName column
     *
     * Example usage:
     * <code>
     * $query->filterByDirectoryname('fooValue');   // WHERE directoryName = 'fooValue'
     * $query->filterByDirectoryname('%fooValue%'); // WHERE directoryName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $directoryname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByDirectoryname($directoryname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($directoryname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $directoryname)) {
                $directoryname = str_replace('*', '%', $directoryname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(WorkPeer::DIRECTORYNAME, $directoryname, $comparison);
    }

    /**
     * Filter the query by a related Status object
     *
     * @param   Status|PropelObjectCollection $status The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStatus($status, $comparison = null)
    {
        if ($status instanceof Status) {
            return $this
                ->addUsingAlias(WorkPeer::STATUS_ID, $status->getId(), $comparison);
        } elseif ($status instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WorkPeer::STATUS_ID, $status->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByStatus() only accepts arguments of type Status or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Status relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinStatus($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Status');

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
            $this->addJoinObject($join, 'Status');
        }

        return $this;
    }

    /**
     * Use the Status relation Status object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\StatusQuery A secondary query class using the current class as primary query
     */
    public function useStatusQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStatus($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Status', '\DTA\MetadataBundle\Model\StatusQuery');
    }

    /**
     * Filter the query by a related Genre object
     *
     * @param   Genre|PropelObjectCollection $genre The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByGenreRelatedByGenreId($genre, $comparison = null)
    {
        if ($genre instanceof Genre) {
            return $this
                ->addUsingAlias(WorkPeer::GENRE_ID, $genre->getId(), $comparison);
        } elseif ($genre instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WorkPeer::GENRE_ID, $genre->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByGenreRelatedByGenreId() only accepts arguments of type Genre or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GenreRelatedByGenreId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinGenreRelatedByGenreId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GenreRelatedByGenreId');

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
            $this->addJoinObject($join, 'GenreRelatedByGenreId');
        }

        return $this;
    }

    /**
     * Use the GenreRelatedByGenreId relation Genre object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\GenreQuery A secondary query class using the current class as primary query
     */
    public function useGenreRelatedByGenreIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinGenreRelatedByGenreId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GenreRelatedByGenreId', '\DTA\MetadataBundle\Model\GenreQuery');
    }

    /**
     * Filter the query by a related Genre object
     *
     * @param   Genre|PropelObjectCollection $genre The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByGenreRelatedBySubgenreId($genre, $comparison = null)
    {
        if ($genre instanceof Genre) {
            return $this
                ->addUsingAlias(WorkPeer::SUBGENRE_ID, $genre->getId(), $comparison);
        } elseif ($genre instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WorkPeer::SUBGENRE_ID, $genre->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByGenreRelatedBySubgenreId() only accepts arguments of type Genre or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GenreRelatedBySubgenreId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinGenreRelatedBySubgenreId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GenreRelatedBySubgenreId');

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
            $this->addJoinObject($join, 'GenreRelatedBySubgenreId');
        }

        return $this;
    }

    /**
     * Use the GenreRelatedBySubgenreId relation Genre object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\GenreQuery A secondary query class using the current class as primary query
     */
    public function useGenreRelatedBySubgenreIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinGenreRelatedBySubgenreId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GenreRelatedBySubgenreId', '\DTA\MetadataBundle\Model\GenreQuery');
    }

    /**
     * Filter the query by a related Dwdsgenre object
     *
     * @param   Dwdsgenre|PropelObjectCollection $dwdsgenre The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDwdsgenreRelatedByDwdsgenreId($dwdsgenre, $comparison = null)
    {
        if ($dwdsgenre instanceof Dwdsgenre) {
            return $this
                ->addUsingAlias(WorkPeer::DWDSGENRE_ID, $dwdsgenre->getId(), $comparison);
        } elseif ($dwdsgenre instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WorkPeer::DWDSGENRE_ID, $dwdsgenre->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDwdsgenreRelatedByDwdsgenreId() only accepts arguments of type Dwdsgenre or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DwdsgenreRelatedByDwdsgenreId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinDwdsgenreRelatedByDwdsgenreId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DwdsgenreRelatedByDwdsgenreId');

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
            $this->addJoinObject($join, 'DwdsgenreRelatedByDwdsgenreId');
        }

        return $this;
    }

    /**
     * Use the DwdsgenreRelatedByDwdsgenreId relation Dwdsgenre object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\DwdsgenreQuery A secondary query class using the current class as primary query
     */
    public function useDwdsgenreRelatedByDwdsgenreIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDwdsgenreRelatedByDwdsgenreId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DwdsgenreRelatedByDwdsgenreId', '\DTA\MetadataBundle\Model\DwdsgenreQuery');
    }

    /**
     * Filter the query by a related Dwdsgenre object
     *
     * @param   Dwdsgenre|PropelObjectCollection $dwdsgenre The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDwdsgenreRelatedByDwdssubgenreId($dwdsgenre, $comparison = null)
    {
        if ($dwdsgenre instanceof Dwdsgenre) {
            return $this
                ->addUsingAlias(WorkPeer::DWDSSUBGENRE_ID, $dwdsgenre->getId(), $comparison);
        } elseif ($dwdsgenre instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WorkPeer::DWDSSUBGENRE_ID, $dwdsgenre->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDwdsgenreRelatedByDwdssubgenreId() only accepts arguments of type Dwdsgenre or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DwdsgenreRelatedByDwdssubgenreId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinDwdsgenreRelatedByDwdssubgenreId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DwdsgenreRelatedByDwdssubgenreId');

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
            $this->addJoinObject($join, 'DwdsgenreRelatedByDwdssubgenreId');
        }

        return $this;
    }

    /**
     * Use the DwdsgenreRelatedByDwdssubgenreId relation Dwdsgenre object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\DwdsgenreQuery A secondary query class using the current class as primary query
     */
    public function useDwdsgenreRelatedByDwdssubgenreIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDwdsgenreRelatedByDwdssubgenreId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DwdsgenreRelatedByDwdssubgenreId', '\DTA\MetadataBundle\Model\DwdsgenreQuery');
    }

    /**
     * Filter the query by a related Datespecification object
     *
     * @param   Datespecification|PropelObjectCollection $datespecification The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDatespecification($datespecification, $comparison = null)
    {
        if ($datespecification instanceof Datespecification) {
            return $this
                ->addUsingAlias(WorkPeer::DATESPECIFICATION_ID, $datespecification->getId(), $comparison);
        } elseif ($datespecification instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WorkPeer::DATESPECIFICATION_ID, $datespecification->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDatespecification() only accepts arguments of type Datespecification or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Datespecification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinDatespecification($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Datespecification');

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
            $this->addJoinObject($join, 'Datespecification');
        }

        return $this;
    }

    /**
     * Use the Datespecification relation Datespecification object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\DatespecificationQuery A secondary query class using the current class as primary query
     */
    public function useDatespecificationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDatespecification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Datespecification', '\DTA\MetadataBundle\Model\DatespecificationQuery');
    }

    /**
     * Filter the query by a related Publication object
     *
     * @param   Publication|PropelObjectCollection $publication  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublication($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(WorkPeer::ID, $publication->getWorkId(), $comparison);
        } elseif ($publication instanceof PropelObjectCollection) {
            return $this
                ->usePublicationQuery()
                ->filterByPrimaryKeys($publication->getPrimaryKeys())
                ->endUse();
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
     * @return WorkQuery The current query, for fluid interface
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
     * @return   \DTA\MetadataBundle\Model\PublicationQuery A secondary query class using the current class as primary query
     */
    public function usePublicationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publication', '\DTA\MetadataBundle\Model\PublicationQuery');
    }

    /**
     * Filter the query by a related AuthorWork object
     *
     * @param   AuthorWork|PropelObjectCollection $authorWork  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByAuthorWork($authorWork, $comparison = null)
    {
        if ($authorWork instanceof AuthorWork) {
            return $this
                ->addUsingAlias(WorkPeer::ID, $authorWork->getWorkId(), $comparison);
        } elseif ($authorWork instanceof PropelObjectCollection) {
            return $this
                ->useAuthorWorkQuery()
                ->filterByPrimaryKeys($authorWork->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAuthorWork() only accepts arguments of type AuthorWork or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AuthorWork relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinAuthorWork($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AuthorWork');

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
            $this->addJoinObject($join, 'AuthorWork');
        }

        return $this;
    }

    /**
     * Use the AuthorWork relation AuthorWork object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\AuthorWorkQuery A secondary query class using the current class as primary query
     */
    public function useAuthorWorkQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAuthorWork($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AuthorWork', '\DTA\MetadataBundle\Model\AuthorWorkQuery');
    }

    /**
     * Filter the query by a related Essay object
     *
     * @param   Essay|PropelObjectCollection $essay  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEssay($essay, $comparison = null)
    {
        if ($essay instanceof Essay) {
            return $this
                ->addUsingAlias(WorkPeer::ID, $essay->getWorkId(), $comparison);
        } elseif ($essay instanceof PropelObjectCollection) {
            return $this
                ->useEssayQuery()
                ->filterByPrimaryKeys($essay->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEssay() only accepts arguments of type Essay or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Essay relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinEssay($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Essay');

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
            $this->addJoinObject($join, 'Essay');
        }

        return $this;
    }

    /**
     * Use the Essay relation Essay object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\EssayQuery A secondary query class using the current class as primary query
     */
    public function useEssayQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEssay($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Essay', '\DTA\MetadataBundle\Model\EssayQuery');
    }

    /**
     * Filter the query by a related Magazine object
     *
     * @param   Magazine|PropelObjectCollection $magazine  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMagazine($magazine, $comparison = null)
    {
        if ($magazine instanceof Magazine) {
            return $this
                ->addUsingAlias(WorkPeer::ID, $magazine->getWorkId(), $comparison);
        } elseif ($magazine instanceof PropelObjectCollection) {
            return $this
                ->useMagazineQuery()
                ->filterByPrimaryKeys($magazine->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMagazine() only accepts arguments of type Magazine or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Magazine relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinMagazine($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Magazine');

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
            $this->addJoinObject($join, 'Magazine');
        }

        return $this;
    }

    /**
     * Use the Magazine relation Magazine object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\MagazineQuery A secondary query class using the current class as primary query
     */
    public function useMagazineQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMagazine($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Magazine', '\DTA\MetadataBundle\Model\MagazineQuery');
    }

    /**
     * Filter the query by a related Series object
     *
     * @param   Series|PropelObjectCollection $series  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySeries($series, $comparison = null)
    {
        if ($series instanceof Series) {
            return $this
                ->addUsingAlias(WorkPeer::ID, $series->getWorkId(), $comparison);
        } elseif ($series instanceof PropelObjectCollection) {
            return $this
                ->useSeriesQuery()
                ->filterByPrimaryKeys($series->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySeries() only accepts arguments of type Series or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Series relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinSeries($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Series');

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
            $this->addJoinObject($join, 'Series');
        }

        return $this;
    }

    /**
     * Use the Series relation Series object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\SeriesQuery A secondary query class using the current class as primary query
     */
    public function useSeriesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSeries($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Series', '\DTA\MetadataBundle\Model\SeriesQuery');
    }

    /**
     * Filter the query by a related Author object
     * using the author_work table as cross reference
     *
     * @param   Author $author the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   WorkQuery The current query, for fluid interface
     */
    public function filterByAuthor($author, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useAuthorWorkQuery()
            ->filterByAuthor($author, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   Work $work Object to remove from the list of results
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function prune($work = null)
    {
        if ($work) {
            $this->addUsingAlias(WorkPeer::ID, $work->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
