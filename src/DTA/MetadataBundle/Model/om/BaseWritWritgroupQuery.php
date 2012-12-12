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
use DTA\MetadataBundle\Model\Writ;
use DTA\MetadataBundle\Model\WritWritgroup;
use DTA\MetadataBundle\Model\WritWritgroupPeer;
use DTA\MetadataBundle\Model\WritWritgroupQuery;
use DTA\MetadataBundle\Model\Writgroup;

/**
 * @method WritWritgroupQuery orderByWritgroupId($order = Criteria::ASC) Order by the writGroup_id column
 * @method WritWritgroupQuery orderByWritId($order = Criteria::ASC) Order by the writ_id column
 *
 * @method WritWritgroupQuery groupByWritgroupId() Group by the writGroup_id column
 * @method WritWritgroupQuery groupByWritId() Group by the writ_id column
 *
 * @method WritWritgroupQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method WritWritgroupQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method WritWritgroupQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method WritWritgroupQuery leftJoinWritgroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the Writgroup relation
 * @method WritWritgroupQuery rightJoinWritgroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Writgroup relation
 * @method WritWritgroupQuery innerJoinWritgroup($relationAlias = null) Adds a INNER JOIN clause to the query using the Writgroup relation
 *
 * @method WritWritgroupQuery leftJoinWrit($relationAlias = null) Adds a LEFT JOIN clause to the query using the Writ relation
 * @method WritWritgroupQuery rightJoinWrit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Writ relation
 * @method WritWritgroupQuery innerJoinWrit($relationAlias = null) Adds a INNER JOIN clause to the query using the Writ relation
 *
 * @method WritWritgroup findOne(PropelPDO $con = null) Return the first WritWritgroup matching the query
 * @method WritWritgroup findOneOrCreate(PropelPDO $con = null) Return the first WritWritgroup matching the query, or a new WritWritgroup object populated from the query conditions when no match is found
 *
 * @method WritWritgroup findOneByWritgroupId(int $writGroup_id) Return the first WritWritgroup filtered by the writGroup_id column
 * @method WritWritgroup findOneByWritId(int $writ_id) Return the first WritWritgroup filtered by the writ_id column
 *
 * @method array findByWritgroupId(int $writGroup_id) Return WritWritgroup objects filtered by the writGroup_id column
 * @method array findByWritId(int $writ_id) Return WritWritgroup objects filtered by the writ_id column
 */
abstract class BaseWritWritgroupQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseWritWritgroupQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\WritWritgroup', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new WritWritgroupQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     WritWritgroupQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return WritWritgroupQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof WritWritgroupQuery) {
            return $criteria;
        }
        $query = new WritWritgroupQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array $key Primary key to use for the query
                         A Primary key composition: [$writGroup_id, $writ_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   WritWritgroup|WritWritgroup[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = WritWritgroupPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(WritWritgroupPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return   WritWritgroup A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `writGroup_id`, `writ_id` FROM `writ_writGroup` WHERE `writGroup_id` = :p0 AND `writ_id` = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new WritWritgroup();
            $obj->hydrate($row);
            WritWritgroupPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return WritWritgroup|WritWritgroup[]|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|WritWritgroup[]|mixed the list of results, formatted by the current formatter
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
     * @return WritWritgroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(WritWritgroupPeer::WRITGROUP_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(WritWritgroupPeer::WRIT_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return WritWritgroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(WritWritgroupPeer::WRITGROUP_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(WritWritgroupPeer::WRIT_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the writGroup_id column
     *
     * Example usage:
     * <code>
     * $query->filterByWritgroupId(1234); // WHERE writGroup_id = 1234
     * $query->filterByWritgroupId(array(12, 34)); // WHERE writGroup_id IN (12, 34)
     * $query->filterByWritgroupId(array('min' => 12)); // WHERE writGroup_id > 12
     * </code>
     *
     * @see       filterByWritgroup()
     *
     * @param     mixed $writgroupId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return WritWritgroupQuery The current query, for fluid interface
     */
    public function filterByWritgroupId($writgroupId = null, $comparison = null)
    {
        if (is_array($writgroupId) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(WritWritgroupPeer::WRITGROUP_ID, $writgroupId, $comparison);
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
     * @return WritWritgroupQuery The current query, for fluid interface
     */
    public function filterByWritId($writId = null, $comparison = null)
    {
        if (is_array($writId) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(WritWritgroupPeer::WRIT_ID, $writId, $comparison);
    }

    /**
     * Filter the query by a related Writgroup object
     *
     * @param   Writgroup|PropelObjectCollection $writgroup The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   WritWritgroupQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByWritgroup($writgroup, $comparison = null)
    {
        if ($writgroup instanceof Writgroup) {
            return $this
                ->addUsingAlias(WritWritgroupPeer::WRITGROUP_ID, $writgroup->getId(), $comparison);
        } elseif ($writgroup instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WritWritgroupPeer::WRITGROUP_ID, $writgroup->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByWritgroup() only accepts arguments of type Writgroup or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Writgroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WritWritgroupQuery The current query, for fluid interface
     */
    public function joinWritgroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Writgroup');

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
            $this->addJoinObject($join, 'Writgroup');
        }

        return $this;
    }

    /**
     * Use the Writgroup relation Writgroup object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\WritgroupQuery A secondary query class using the current class as primary query
     */
    public function useWritgroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWritgroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Writgroup', '\DTA\MetadataBundle\Model\WritgroupQuery');
    }

    /**
     * Filter the query by a related Writ object
     *
     * @param   Writ|PropelObjectCollection $writ The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   WritWritgroupQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByWrit($writ, $comparison = null)
    {
        if ($writ instanceof Writ) {
            return $this
                ->addUsingAlias(WritWritgroupPeer::WRIT_ID, $writ->getId(), $comparison);
        } elseif ($writ instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WritWritgroupPeer::WRIT_ID, $writ->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return WritWritgroupQuery The current query, for fluid interface
     */
    public function joinWrit($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useWritQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWrit($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Writ', '\DTA\MetadataBundle\Model\WritQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   WritWritgroup $writWritgroup Object to remove from the list of results
     *
     * @return WritWritgroupQuery The current query, for fluid interface
     */
    public function prune($writWritgroup = null)
    {
        if ($writWritgroup) {
            $this->addCond('pruneCond0', $this->getAliasedColName(WritWritgroupPeer::WRITGROUP_ID), $writWritgroup->getWritgroupId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(WritWritgroupPeer::WRIT_ID), $writWritgroup->getWritId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
