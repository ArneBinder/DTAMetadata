<?php

namespace DTA\MetadataBundle\Model\Data\om;

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
use DTA\MetadataBundle\Model\Data\PublicationMs;
use DTA\MetadataBundle\Model\Data\PublicationMsPeer;
use DTA\MetadataBundle\Model\Data\PublicationMsQuery;
use DTA\MetadataBundle\Model\Data\Series;

/**
 * @method PublicationMsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PublicationMsQuery orderByPublicationId($order = Criteria::ASC) Order by the publication_id column
 * @method PublicationMsQuery orderBySeriesId($order = Criteria::ASC) Order by the series_id column
 * @method PublicationMsQuery orderByVolumenumberinseries($order = Criteria::ASC) Order by the volumenumberinseries column
 * @method PublicationMsQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PublicationMsQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PublicationMsQuery groupById() Group by the id column
 * @method PublicationMsQuery groupByPublicationId() Group by the publication_id column
 * @method PublicationMsQuery groupBySeriesId() Group by the series_id column
 * @method PublicationMsQuery groupByVolumenumberinseries() Group by the volumenumberinseries column
 * @method PublicationMsQuery groupByCreatedAt() Group by the created_at column
 * @method PublicationMsQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PublicationMsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PublicationMsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PublicationMsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PublicationMsQuery leftJoinPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publication relation
 * @method PublicationMsQuery rightJoinPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publication relation
 * @method PublicationMsQuery innerJoinPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the Publication relation
 *
 * @method PublicationMsQuery leftJoinSeries($relationAlias = null) Adds a LEFT JOIN clause to the query using the Series relation
 * @method PublicationMsQuery rightJoinSeries($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Series relation
 * @method PublicationMsQuery innerJoinSeries($relationAlias = null) Adds a INNER JOIN clause to the query using the Series relation
 *
 * @method PublicationMs findOne(PropelPDO $con = null) Return the first PublicationMs matching the query
 * @method PublicationMs findOneOrCreate(PropelPDO $con = null) Return the first PublicationMs matching the query, or a new PublicationMs object populated from the query conditions when no match is found
 *
 * @method PublicationMs findOneByPublicationId(int $publication_id) Return the first PublicationMs filtered by the publication_id column
 * @method PublicationMs findOneBySeriesId(int $series_id) Return the first PublicationMs filtered by the series_id column
 * @method PublicationMs findOneByVolumenumberinseries(string $volumenumberinseries) Return the first PublicationMs filtered by the volumenumberinseries column
 * @method PublicationMs findOneByCreatedAt(string $created_at) Return the first PublicationMs filtered by the created_at column
 * @method PublicationMs findOneByUpdatedAt(string $updated_at) Return the first PublicationMs filtered by the updated_at column
 *
 * @method array findById(int $id) Return PublicationMs objects filtered by the id column
 * @method array findByPublicationId(int $publication_id) Return PublicationMs objects filtered by the publication_id column
 * @method array findBySeriesId(int $series_id) Return PublicationMs objects filtered by the series_id column
 * @method array findByVolumenumberinseries(string $volumenumberinseries) Return PublicationMs objects filtered by the volumenumberinseries column
 * @method array findByCreatedAt(string $created_at) Return PublicationMs objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PublicationMs objects filtered by the updated_at column
 */
abstract class BasePublicationMsQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePublicationMsQuery object.
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
            $modelName = 'DTA\\MetadataBundle\\Model\\Data\\PublicationMs';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PublicationMsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PublicationMsQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PublicationMsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PublicationMsQuery) {
            return $criteria;
        }
        $query = new PublicationMsQuery(null, null, $modelAlias);

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
     * @return   PublicationMs|PublicationMs[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PublicationMsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PublicationMsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PublicationMs A model object, or null if the key is not found
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
     * @return                 PublicationMs A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "id", "publication_id", "series_id", "volumenumberinseries", "created_at", "updated_at" FROM "publication_ms" WHERE "id" = :p0';
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
            $obj = new PublicationMs();
            $obj->hydrate($row);
            PublicationMsPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PublicationMs|PublicationMs[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PublicationMs[]|mixed the list of results, formatted by the current formatter
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
     * @return PublicationMsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PublicationMsPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PublicationMsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PublicationMsPeer::ID, $keys, Criteria::IN);
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
     * @return PublicationMsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PublicationMsPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PublicationMsPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationMsPeer::ID, $id, $comparison);
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
     * @return PublicationMsQuery The current query, for fluid interface
     */
    public function filterByPublicationId($publicationId = null, $comparison = null)
    {
        if (is_array($publicationId)) {
            $useMinMax = false;
            if (isset($publicationId['min'])) {
                $this->addUsingAlias(PublicationMsPeer::PUBLICATION_ID, $publicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publicationId['max'])) {
                $this->addUsingAlias(PublicationMsPeer::PUBLICATION_ID, $publicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationMsPeer::PUBLICATION_ID, $publicationId, $comparison);
    }

    /**
     * Filter the query on the series_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySeriesId(1234); // WHERE series_id = 1234
     * $query->filterBySeriesId(array(12, 34)); // WHERE series_id IN (12, 34)
     * $query->filterBySeriesId(array('min' => 12)); // WHERE series_id >= 12
     * $query->filterBySeriesId(array('max' => 12)); // WHERE series_id <= 12
     * </code>
     *
     * @see       filterBySeries()
     *
     * @param     mixed $seriesId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationMsQuery The current query, for fluid interface
     */
    public function filterBySeriesId($seriesId = null, $comparison = null)
    {
        if (is_array($seriesId)) {
            $useMinMax = false;
            if (isset($seriesId['min'])) {
                $this->addUsingAlias(PublicationMsPeer::SERIES_ID, $seriesId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($seriesId['max'])) {
                $this->addUsingAlias(PublicationMsPeer::SERIES_ID, $seriesId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationMsPeer::SERIES_ID, $seriesId, $comparison);
    }

    /**
     * Filter the query on the volumenumberinseries column
     *
     * Example usage:
     * <code>
     * $query->filterByVolumenumberinseries('fooValue');   // WHERE volumenumberinseries = 'fooValue'
     * $query->filterByVolumenumberinseries('%fooValue%'); // WHERE volumenumberinseries LIKE '%fooValue%'
     * </code>
     *
     * @param     string $volumenumberinseries The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationMsQuery The current query, for fluid interface
     */
    public function filterByVolumenumberinseries($volumenumberinseries = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($volumenumberinseries)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $volumenumberinseries)) {
                $volumenumberinseries = str_replace('*', '%', $volumenumberinseries);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationMsPeer::VOLUMENUMBERINSERIES, $volumenumberinseries, $comparison);
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
     * @return PublicationMsQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PublicationMsPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PublicationMsPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationMsPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PublicationMsQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PublicationMsPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PublicationMsPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationMsPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Publication object
     *
     * @param   Publication|PropelObjectCollection $publication The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationMsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublication($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(PublicationMsPeer::PUBLICATION_ID, $publication->getId(), $comparison);
        } elseif ($publication instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationMsPeer::PUBLICATION_ID, $publication->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PublicationMsQuery The current query, for fluid interface
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
     * Filter the query by a related Series object
     *
     * @param   Series|PropelObjectCollection $series The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationMsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySeries($series, $comparison = null)
    {
        if ($series instanceof Series) {
            return $this
                ->addUsingAlias(PublicationMsPeer::SERIES_ID, $series->getId(), $comparison);
        } elseif ($series instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationMsPeer::SERIES_ID, $series->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PublicationMsQuery The current query, for fluid interface
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
     * @return   \DTA\MetadataBundle\Model\Data\SeriesQuery A secondary query class using the current class as primary query
     */
    public function useSeriesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSeries($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Series', '\DTA\MetadataBundle\Model\Data\SeriesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PublicationMs $publicationMs Object to remove from the list of results
     *
     * @return PublicationMsQuery The current query, for fluid interface
     */
    public function prune($publicationMs = null)
    {
        if ($publicationMs) {
            $this->addUsingAlias(PublicationMsPeer::ID, $publicationMs->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PublicationMsQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PublicationMsPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PublicationMsQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PublicationMsPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PublicationMsQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PublicationMsPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PublicationMsQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PublicationMsPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PublicationMsQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PublicationMsPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PublicationMsQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PublicationMsPeer::CREATED_AT);
    }
}
