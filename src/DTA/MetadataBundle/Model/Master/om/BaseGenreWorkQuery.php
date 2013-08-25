<?php

namespace DTA\MetadataBundle\Model\Master\om;

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
use DTA\MetadataBundle\Model\Classification\Genre;
use DTA\MetadataBundle\Model\Data\Work;
use DTA\MetadataBundle\Model\Master\GenreWork;
use DTA\MetadataBundle\Model\Master\GenreWorkPeer;
use DTA\MetadataBundle\Model\Master\GenreWorkQuery;

/**
 * @method GenreWorkQuery orderByGenreId($order = Criteria::ASC) Order by the genre_id column
 * @method GenreWorkQuery orderByWorkId($order = Criteria::ASC) Order by the work_id column
 * @method GenreWorkQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method GenreWorkQuery groupByGenreId() Group by the genre_id column
 * @method GenreWorkQuery groupByWorkId() Group by the work_id column
 * @method GenreWorkQuery groupById() Group by the id column
 *
 * @method GenreWorkQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method GenreWorkQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method GenreWorkQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method GenreWorkQuery leftJoinGenre($relationAlias = null) Adds a LEFT JOIN clause to the query using the Genre relation
 * @method GenreWorkQuery rightJoinGenre($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Genre relation
 * @method GenreWorkQuery innerJoinGenre($relationAlias = null) Adds a INNER JOIN clause to the query using the Genre relation
 *
 * @method GenreWorkQuery leftJoinWork($relationAlias = null) Adds a LEFT JOIN clause to the query using the Work relation
 * @method GenreWorkQuery rightJoinWork($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Work relation
 * @method GenreWorkQuery innerJoinWork($relationAlias = null) Adds a INNER JOIN clause to the query using the Work relation
 *
 * @method GenreWork findOne(PropelPDO $con = null) Return the first GenreWork matching the query
 * @method GenreWork findOneOrCreate(PropelPDO $con = null) Return the first GenreWork matching the query, or a new GenreWork object populated from the query conditions when no match is found
 *
 * @method GenreWork findOneByGenreId(int $genre_id) Return the first GenreWork filtered by the genre_id column
 * @method GenreWork findOneByWorkId(int $work_id) Return the first GenreWork filtered by the work_id column
 *
 * @method array findByGenreId(int $genre_id) Return GenreWork objects filtered by the genre_id column
 * @method array findByWorkId(int $work_id) Return GenreWork objects filtered by the work_id column
 * @method array findById(int $id) Return GenreWork objects filtered by the id column
 */
abstract class BaseGenreWorkQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseGenreWorkQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Master\\GenreWork', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new GenreWorkQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   GenreWorkQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return GenreWorkQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof GenreWorkQuery) {
            return $criteria;
        }
        $query = new GenreWorkQuery();
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
     * @return   GenreWork|GenreWork[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = GenreWorkPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(GenreWorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 GenreWork A model object, or null if the key is not found
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
     * @return                 GenreWork A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "genre_id", "work_id", "id" FROM "genre_work" WHERE "id" = :p0';
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
            $obj = new GenreWork();
            $obj->hydrate($row);
            GenreWorkPeer::addInstanceToPool($obj, (string) $key);
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
     * @return GenreWork|GenreWork[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|GenreWork[]|mixed the list of results, formatted by the current formatter
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
     * @return GenreWorkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(GenreWorkPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return GenreWorkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(GenreWorkPeer::ID, $keys, Criteria::IN);
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
     * @see       filterByGenre()
     *
     * @param     mixed $genreId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return GenreWorkQuery The current query, for fluid interface
     */
    public function filterByGenreId($genreId = null, $comparison = null)
    {
        if (is_array($genreId)) {
            $useMinMax = false;
            if (isset($genreId['min'])) {
                $this->addUsingAlias(GenreWorkPeer::GENRE_ID, $genreId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($genreId['max'])) {
                $this->addUsingAlias(GenreWorkPeer::GENRE_ID, $genreId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GenreWorkPeer::GENRE_ID, $genreId, $comparison);
    }

    /**
     * Filter the query on the work_id column
     *
     * Example usage:
     * <code>
     * $query->filterByWorkId(1234); // WHERE work_id = 1234
     * $query->filterByWorkId(array(12, 34)); // WHERE work_id IN (12, 34)
     * $query->filterByWorkId(array('min' => 12)); // WHERE work_id >= 12
     * $query->filterByWorkId(array('max' => 12)); // WHERE work_id <= 12
     * </code>
     *
     * @see       filterByWork()
     *
     * @param     mixed $workId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return GenreWorkQuery The current query, for fluid interface
     */
    public function filterByWorkId($workId = null, $comparison = null)
    {
        if (is_array($workId)) {
            $useMinMax = false;
            if (isset($workId['min'])) {
                $this->addUsingAlias(GenreWorkPeer::WORK_ID, $workId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($workId['max'])) {
                $this->addUsingAlias(GenreWorkPeer::WORK_ID, $workId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GenreWorkPeer::WORK_ID, $workId, $comparison);
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
     * @return GenreWorkQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(GenreWorkPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(GenreWorkPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GenreWorkPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query by a related Genre object
     *
     * @param   Genre|PropelObjectCollection $genre The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 GenreWorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByGenre($genre, $comparison = null)
    {
        if ($genre instanceof Genre) {
            return $this
                ->addUsingAlias(GenreWorkPeer::GENRE_ID, $genre->getId(), $comparison);
        } elseif ($genre instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GenreWorkPeer::GENRE_ID, $genre->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByGenre() only accepts arguments of type Genre or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Genre relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return GenreWorkQuery The current query, for fluid interface
     */
    public function joinGenre($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Genre');

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
            $this->addJoinObject($join, 'Genre');
        }

        return $this;
    }

    /**
     * Use the Genre relation Genre object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Classification\GenreQuery A secondary query class using the current class as primary query
     */
    public function useGenreQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGenre($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Genre', '\DTA\MetadataBundle\Model\Classification\GenreQuery');
    }

    /**
     * Filter the query by a related Work object
     *
     * @param   Work|PropelObjectCollection $work The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 GenreWorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByWork($work, $comparison = null)
    {
        if ($work instanceof Work) {
            return $this
                ->addUsingAlias(GenreWorkPeer::WORK_ID, $work->getId(), $comparison);
        } elseif ($work instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GenreWorkPeer::WORK_ID, $work->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByWork() only accepts arguments of type Work or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Work relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return GenreWorkQuery The current query, for fluid interface
     */
    public function joinWork($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Work');

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
            $this->addJoinObject($join, 'Work');
        }

        return $this;
    }

    /**
     * Use the Work relation Work object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\WorkQuery A secondary query class using the current class as primary query
     */
    public function useWorkQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWork($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Work', '\DTA\MetadataBundle\Model\Data\WorkQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   GenreWork $genreWork Object to remove from the list of results
     *
     * @return GenreWorkQuery The current query, for fluid interface
     */
    public function prune($genreWork = null)
    {
        if ($genreWork) {
            $this->addUsingAlias(GenreWorkPeer::ID, $genreWork->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
