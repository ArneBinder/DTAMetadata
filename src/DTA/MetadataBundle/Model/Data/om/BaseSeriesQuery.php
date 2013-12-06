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
use DTA\MetadataBundle\Model\Data\PublicationDs;
use DTA\MetadataBundle\Model\Data\PublicationMms;
use DTA\MetadataBundle\Model\Data\PublicationMs;
use DTA\MetadataBundle\Model\Data\Series;
use DTA\MetadataBundle\Model\Data\SeriesPeer;
use DTA\MetadataBundle\Model\Data\SeriesQuery;
use DTA\MetadataBundle\Model\Data\Title;

/**
 * @method SeriesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method SeriesQuery orderByTitleId($order = Criteria::ASC) Order by the title_id column
 * @method SeriesQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method SeriesQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method SeriesQuery groupById() Group by the id column
 * @method SeriesQuery groupByTitleId() Group by the title_id column
 * @method SeriesQuery groupByCreatedAt() Group by the created_at column
 * @method SeriesQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method SeriesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method SeriesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method SeriesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method SeriesQuery leftJoinTitle($relationAlias = null) Adds a LEFT JOIN clause to the query using the Title relation
 * @method SeriesQuery rightJoinTitle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Title relation
 * @method SeriesQuery innerJoinTitle($relationAlias = null) Adds a INNER JOIN clause to the query using the Title relation
 *
 * @method SeriesQuery leftJoinPublicationDs($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationDs relation
 * @method SeriesQuery rightJoinPublicationDs($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationDs relation
 * @method SeriesQuery innerJoinPublicationDs($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationDs relation
 *
 * @method SeriesQuery leftJoinPublicationMs($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationMs relation
 * @method SeriesQuery rightJoinPublicationMs($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationMs relation
 * @method SeriesQuery innerJoinPublicationMs($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationMs relation
 *
 * @method SeriesQuery leftJoinPublicationMms($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationMms relation
 * @method SeriesQuery rightJoinPublicationMms($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationMms relation
 * @method SeriesQuery innerJoinPublicationMms($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationMms relation
 *
 * @method Series findOne(PropelPDO $con = null) Return the first Series matching the query
 * @method Series findOneOrCreate(PropelPDO $con = null) Return the first Series matching the query, or a new Series object populated from the query conditions when no match is found
 *
 * @method Series findOneByTitleId(int $title_id) Return the first Series filtered by the title_id column
 * @method Series findOneByCreatedAt(string $created_at) Return the first Series filtered by the created_at column
 * @method Series findOneByUpdatedAt(string $updated_at) Return the first Series filtered by the updated_at column
 *
 * @method array findById(int $id) Return Series objects filtered by the id column
 * @method array findByTitleId(int $title_id) Return Series objects filtered by the title_id column
 * @method array findByCreatedAt(string $created_at) Return Series objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Series objects filtered by the updated_at column
 */
abstract class BaseSeriesQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseSeriesQuery object.
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
            $modelName = 'DTA\\MetadataBundle\\Model\\Data\\Series';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new SeriesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   SeriesQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return SeriesQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof SeriesQuery) {
            return $criteria;
        }
        $query = new SeriesQuery(null, null, $modelAlias);

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
     * @return   Series|Series[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SeriesPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Series A model object, or null if the key is not found
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
     * @return                 Series A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "id", "title_id", "created_at", "updated_at" FROM "series" WHERE "id" = :p0';
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
            $obj = new Series();
            $obj->hydrate($row);
            SeriesPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Series|Series[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Series[]|mixed the list of results, formatted by the current formatter
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
     * @return SeriesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SeriesPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return SeriesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SeriesPeer::ID, $keys, Criteria::IN);
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
     * @return SeriesQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SeriesPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SeriesPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the title_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTitleId(1234); // WHERE title_id = 1234
     * $query->filterByTitleId(array(12, 34)); // WHERE title_id IN (12, 34)
     * $query->filterByTitleId(array('min' => 12)); // WHERE title_id >= 12
     * $query->filterByTitleId(array('max' => 12)); // WHERE title_id <= 12
     * </code>
     *
     * @see       filterByTitle()
     *
     * @param     mixed $titleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SeriesQuery The current query, for fluid interface
     */
    public function filterByTitleId($titleId = null, $comparison = null)
    {
        if (is_array($titleId)) {
            $useMinMax = false;
            if (isset($titleId['min'])) {
                $this->addUsingAlias(SeriesPeer::TITLE_ID, $titleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($titleId['max'])) {
                $this->addUsingAlias(SeriesPeer::TITLE_ID, $titleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesPeer::TITLE_ID, $titleId, $comparison);
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
     * @return SeriesQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SeriesPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SeriesPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return SeriesQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SeriesPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SeriesPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriesPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Title object
     *
     * @param   Title|PropelObjectCollection $title The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SeriesQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTitle($title, $comparison = null)
    {
        if ($title instanceof Title) {
            return $this
                ->addUsingAlias(SeriesPeer::TITLE_ID, $title->getId(), $comparison);
        } elseif ($title instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SeriesPeer::TITLE_ID, $title->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTitle() only accepts arguments of type Title or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Title relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SeriesQuery The current query, for fluid interface
     */
    public function joinTitle($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Title');

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
            $this->addJoinObject($join, 'Title');
        }

        return $this;
    }

    /**
     * Use the Title relation Title object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\TitleQuery A secondary query class using the current class as primary query
     */
    public function useTitleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTitle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Title', '\DTA\MetadataBundle\Model\Data\TitleQuery');
    }

    /**
     * Filter the query by a related PublicationDs object
     *
     * @param   PublicationDs|PropelObjectCollection $publicationDs  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SeriesQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationDs($publicationDs, $comparison = null)
    {
        if ($publicationDs instanceof PublicationDs) {
            return $this
                ->addUsingAlias(SeriesPeer::ID, $publicationDs->getSeriesId(), $comparison);
        } elseif ($publicationDs instanceof PropelObjectCollection) {
            return $this
                ->usePublicationDsQuery()
                ->filterByPrimaryKeys($publicationDs->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationDs() only accepts arguments of type PublicationDs or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationDs relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SeriesQuery The current query, for fluid interface
     */
    public function joinPublicationDs($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationDs');

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
            $this->addJoinObject($join, 'PublicationDs');
        }

        return $this;
    }

    /**
     * Use the PublicationDs relation PublicationDs object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationDsQuery A secondary query class using the current class as primary query
     */
    public function usePublicationDsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationDs($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationDs', '\DTA\MetadataBundle\Model\Data\PublicationDsQuery');
    }

    /**
     * Filter the query by a related PublicationMs object
     *
     * @param   PublicationMs|PropelObjectCollection $publicationMs  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SeriesQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationMs($publicationMs, $comparison = null)
    {
        if ($publicationMs instanceof PublicationMs) {
            return $this
                ->addUsingAlias(SeriesPeer::ID, $publicationMs->getSeriesId(), $comparison);
        } elseif ($publicationMs instanceof PropelObjectCollection) {
            return $this
                ->usePublicationMsQuery()
                ->filterByPrimaryKeys($publicationMs->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationMs() only accepts arguments of type PublicationMs or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationMs relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SeriesQuery The current query, for fluid interface
     */
    public function joinPublicationMs($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationMs');

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
            $this->addJoinObject($join, 'PublicationMs');
        }

        return $this;
    }

    /**
     * Use the PublicationMs relation PublicationMs object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationMsQuery A secondary query class using the current class as primary query
     */
    public function usePublicationMsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationMs($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationMs', '\DTA\MetadataBundle\Model\Data\PublicationMsQuery');
    }

    /**
     * Filter the query by a related PublicationMms object
     *
     * @param   PublicationMms|PropelObjectCollection $publicationMms  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SeriesQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationMms($publicationMms, $comparison = null)
    {
        if ($publicationMms instanceof PublicationMms) {
            return $this
                ->addUsingAlias(SeriesPeer::ID, $publicationMms->getSeriesId(), $comparison);
        } elseif ($publicationMms instanceof PropelObjectCollection) {
            return $this
                ->usePublicationMmsQuery()
                ->filterByPrimaryKeys($publicationMms->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationMms() only accepts arguments of type PublicationMms or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationMms relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SeriesQuery The current query, for fluid interface
     */
    public function joinPublicationMms($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationMms');

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
            $this->addJoinObject($join, 'PublicationMms');
        }

        return $this;
    }

    /**
     * Use the PublicationMms relation PublicationMms object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationMmsQuery A secondary query class using the current class as primary query
     */
    public function usePublicationMmsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationMms($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationMms', '\DTA\MetadataBundle\Model\Data\PublicationMmsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Series $series Object to remove from the list of results
     *
     * @return SeriesQuery The current query, for fluid interface
     */
    public function prune($series = null)
    {
        if ($series) {
            $this->addUsingAlias(SeriesPeer::ID, $series->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     SeriesQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SeriesPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     SeriesQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SeriesPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     SeriesQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SeriesPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     SeriesQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SeriesPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     SeriesQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SeriesPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     SeriesQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SeriesPeer::CREATED_AT);
    }
}
