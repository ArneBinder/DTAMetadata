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
use DTA\MetadataBundle\Model\Corpus;
use DTA\MetadataBundle\Model\CorpusPeer;
use DTA\MetadataBundle\Model\CorpusQuery;
use DTA\MetadataBundle\Model\Writ;

/**
 * @method CorpusQuery orderById($order = Criteria::ASC) Order by the id column
 * @method CorpusQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method CorpusQuery orderByWritId($order = Criteria::ASC) Order by the writ_id column
 *
 * @method CorpusQuery groupById() Group by the id column
 * @method CorpusQuery groupByName() Group by the name column
 * @method CorpusQuery groupByWritId() Group by the writ_id column
 *
 * @method CorpusQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CorpusQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CorpusQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CorpusQuery leftJoinWrit($relationAlias = null) Adds a LEFT JOIN clause to the query using the Writ relation
 * @method CorpusQuery rightJoinWrit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Writ relation
 * @method CorpusQuery innerJoinWrit($relationAlias = null) Adds a INNER JOIN clause to the query using the Writ relation
 *
 * @method Corpus findOne(PropelPDO $con = null) Return the first Corpus matching the query
 * @method Corpus findOneOrCreate(PropelPDO $con = null) Return the first Corpus matching the query, or a new Corpus object populated from the query conditions when no match is found
 *
 * @method Corpus findOneByName(string $name) Return the first Corpus filtered by the name column
 * @method Corpus findOneByWritId(int $writ_id) Return the first Corpus filtered by the writ_id column
 *
 * @method array findById(int $id) Return Corpus objects filtered by the id column
 * @method array findByName(string $name) Return Corpus objects filtered by the name column
 * @method array findByWritId(int $writ_id) Return Corpus objects filtered by the writ_id column
 */
abstract class BaseCorpusQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCorpusQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Corpus', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CorpusQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     CorpusQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CorpusQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CorpusQuery) {
            return $criteria;
        }
        $query = new CorpusQuery();
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
     * @return   Corpus|Corpus[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CorpusPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CorpusPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Corpus A model object, or null if the key is not found
     * @throws   PropelException
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
     * @return   Corpus A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `writ_id` FROM `corpus` WHERE `id` = :p0';
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
            $obj = new Corpus();
            $obj->hydrate($row);
            CorpusPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Corpus|Corpus[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Corpus[]|mixed the list of results, formatted by the current formatter
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
     * @return CorpusQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CorpusPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CorpusQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CorpusPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CorpusQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(CorpusPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CorpusQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CorpusPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the writ_id column
     *
     * Example usage:
     * <code>
     * $query->filterByWritId(1234); // WHERE writ_id = 1234
     * $query->filterByWritId(array(12, 34)); // WHERE writ_id IN (12, 34)
     * $query->filterByWritId(array('min' => 12)); // WHERE writ_id > 12
     * </code>
     *
     * @see       filterByWrit()
     *
     * @param     mixed $writId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CorpusQuery The current query, for fluid interface
     */
    public function filterByWritId($writId = null, $comparison = null)
    {
        if (is_array($writId)) {
            $useMinMax = false;
            if (isset($writId['min'])) {
                $this->addUsingAlias(CorpusPeer::WRIT_ID, $writId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($writId['max'])) {
                $this->addUsingAlias(CorpusPeer::WRIT_ID, $writId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CorpusPeer::WRIT_ID, $writId, $comparison);
    }

    /**
     * Filter the query by a related Writ object
     *
     * @param   Writ|PropelObjectCollection $writ The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   CorpusQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByWrit($writ, $comparison = null)
    {
        if ($writ instanceof Writ) {
            return $this
                ->addUsingAlias(CorpusPeer::WRIT_ID, $writ->getId(), $comparison);
        } elseif ($writ instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CorpusPeer::WRIT_ID, $writ->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByWrit() only accepts arguments of type Writ or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Writ relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CorpusQuery The current query, for fluid interface
     */
    public function joinWrit($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Writ');

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
            $this->addJoinObject($join, 'Writ');
        }

        return $this;
    }

    /**
     * Use the Writ relation Writ object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\WritQuery A secondary query class using the current class as primary query
     */
    public function useWritQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinWrit($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Writ', '\DTA\MetadataBundle\Model\WritQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Corpus $corpus Object to remove from the list of results
     *
     * @return CorpusQuery The current query, for fluid interface
     */
    public function prune($corpus = null)
    {
        if ($corpus) {
            $this->addUsingAlias(CorpusPeer::ID, $corpus->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
