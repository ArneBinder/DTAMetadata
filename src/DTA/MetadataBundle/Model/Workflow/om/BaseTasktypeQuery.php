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
use DTA\MetadataBundle\Model\Workflow\Task;
use DTA\MetadataBundle\Model\Workflow\Tasktype;
use DTA\MetadataBundle\Model\Workflow\TasktypePeer;
use DTA\MetadataBundle\Model\Workflow\TasktypeQuery;

/**
 * @method TasktypeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method TasktypeQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method TasktypeQuery orderByLegacyTasktypeId($order = Criteria::ASC) Order by the legacy_tasktype_id column
 * @method TasktypeQuery orderByTreeLeft($order = Criteria::ASC) Order by the tree_left column
 * @method TasktypeQuery orderByTreeRight($order = Criteria::ASC) Order by the tree_right column
 * @method TasktypeQuery orderByTreeLevel($order = Criteria::ASC) Order by the tree_level column
 *
 * @method TasktypeQuery groupById() Group by the id column
 * @method TasktypeQuery groupByName() Group by the name column
 * @method TasktypeQuery groupByLegacyTasktypeId() Group by the legacy_tasktype_id column
 * @method TasktypeQuery groupByTreeLeft() Group by the tree_left column
 * @method TasktypeQuery groupByTreeRight() Group by the tree_right column
 * @method TasktypeQuery groupByTreeLevel() Group by the tree_level column
 *
 * @method TasktypeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method TasktypeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method TasktypeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method TasktypeQuery leftJoinTask($relationAlias = null) Adds a LEFT JOIN clause to the query using the Task relation
 * @method TasktypeQuery rightJoinTask($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Task relation
 * @method TasktypeQuery innerJoinTask($relationAlias = null) Adds a INNER JOIN clause to the query using the Task relation
 *
 * @method Tasktype findOne(PropelPDO $con = null) Return the first Tasktype matching the query
 * @method Tasktype findOneOrCreate(PropelPDO $con = null) Return the first Tasktype matching the query, or a new Tasktype object populated from the query conditions when no match is found
 *
 * @method Tasktype findOneByName(string $name) Return the first Tasktype filtered by the name column
 * @method Tasktype findOneByLegacyTasktypeId(int $legacy_tasktype_id) Return the first Tasktype filtered by the legacy_tasktype_id column
 * @method Tasktype findOneByTreeLeft(int $tree_left) Return the first Tasktype filtered by the tree_left column
 * @method Tasktype findOneByTreeRight(int $tree_right) Return the first Tasktype filtered by the tree_right column
 * @method Tasktype findOneByTreeLevel(int $tree_level) Return the first Tasktype filtered by the tree_level column
 *
 * @method array findById(int $id) Return Tasktype objects filtered by the id column
 * @method array findByName(string $name) Return Tasktype objects filtered by the name column
 * @method array findByLegacyTasktypeId(int $legacy_tasktype_id) Return Tasktype objects filtered by the legacy_tasktype_id column
 * @method array findByTreeLeft(int $tree_left) Return Tasktype objects filtered by the tree_left column
 * @method array findByTreeRight(int $tree_right) Return Tasktype objects filtered by the tree_right column
 * @method array findByTreeLevel(int $tree_level) Return Tasktype objects filtered by the tree_level column
 */
abstract class BaseTasktypeQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseTasktypeQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'dtametadata', $modelName = 'DTA\\MetadataBundle\\Model\\Workflow\\Tasktype', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new TasktypeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   TasktypeQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return TasktypeQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof TasktypeQuery) {
            return $criteria;
        }
        $query = new TasktypeQuery();
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
     * @return   Tasktype|Tasktype[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TasktypePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(TasktypePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Tasktype A model object, or null if the key is not found
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
     * @return                 Tasktype A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "id", "name", "legacy_tasktype_id", "tree_left", "tree_right", "tree_level" FROM "tasktype" WHERE "id" = :p0';
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
            $obj = new Tasktype();
            $obj->hydrate($row);
            TasktypePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Tasktype|Tasktype[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Tasktype[]|mixed the list of results, formatted by the current formatter
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
     * @return TasktypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TasktypePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return TasktypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TasktypePeer::ID, $keys, Criteria::IN);
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
     * @return TasktypeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(TasktypePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TasktypePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TasktypePeer::ID, $id, $comparison);
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
     * @return TasktypeQuery The current query, for fluid interface
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

        return $this->addUsingAlias(TasktypePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the legacy_tasktype_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLegacyTasktypeId(1234); // WHERE legacy_tasktype_id = 1234
     * $query->filterByLegacyTasktypeId(array(12, 34)); // WHERE legacy_tasktype_id IN (12, 34)
     * $query->filterByLegacyTasktypeId(array('min' => 12)); // WHERE legacy_tasktype_id >= 12
     * $query->filterByLegacyTasktypeId(array('max' => 12)); // WHERE legacy_tasktype_id <= 12
     * </code>
     *
     * @param     mixed $legacyTasktypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TasktypeQuery The current query, for fluid interface
     */
    public function filterByLegacyTasktypeId($legacyTasktypeId = null, $comparison = null)
    {
        if (is_array($legacyTasktypeId)) {
            $useMinMax = false;
            if (isset($legacyTasktypeId['min'])) {
                $this->addUsingAlias(TasktypePeer::LEGACY_TASKTYPE_ID, $legacyTasktypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($legacyTasktypeId['max'])) {
                $this->addUsingAlias(TasktypePeer::LEGACY_TASKTYPE_ID, $legacyTasktypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TasktypePeer::LEGACY_TASKTYPE_ID, $legacyTasktypeId, $comparison);
    }

    /**
     * Filter the query on the tree_left column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLeft(1234); // WHERE tree_left = 1234
     * $query->filterByTreeLeft(array(12, 34)); // WHERE tree_left IN (12, 34)
     * $query->filterByTreeLeft(array('min' => 12)); // WHERE tree_left >= 12
     * $query->filterByTreeLeft(array('max' => 12)); // WHERE tree_left <= 12
     * </code>
     *
     * @param     mixed $treeLeft The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TasktypeQuery The current query, for fluid interface
     */
    public function filterByTreeLeft($treeLeft = null, $comparison = null)
    {
        if (is_array($treeLeft)) {
            $useMinMax = false;
            if (isset($treeLeft['min'])) {
                $this->addUsingAlias(TasktypePeer::TREE_LEFT, $treeLeft['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLeft['max'])) {
                $this->addUsingAlias(TasktypePeer::TREE_LEFT, $treeLeft['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TasktypePeer::TREE_LEFT, $treeLeft, $comparison);
    }

    /**
     * Filter the query on the tree_right column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeRight(1234); // WHERE tree_right = 1234
     * $query->filterByTreeRight(array(12, 34)); // WHERE tree_right IN (12, 34)
     * $query->filterByTreeRight(array('min' => 12)); // WHERE tree_right >= 12
     * $query->filterByTreeRight(array('max' => 12)); // WHERE tree_right <= 12
     * </code>
     *
     * @param     mixed $treeRight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TasktypeQuery The current query, for fluid interface
     */
    public function filterByTreeRight($treeRight = null, $comparison = null)
    {
        if (is_array($treeRight)) {
            $useMinMax = false;
            if (isset($treeRight['min'])) {
                $this->addUsingAlias(TasktypePeer::TREE_RIGHT, $treeRight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeRight['max'])) {
                $this->addUsingAlias(TasktypePeer::TREE_RIGHT, $treeRight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TasktypePeer::TREE_RIGHT, $treeRight, $comparison);
    }

    /**
     * Filter the query on the tree_level column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLevel(1234); // WHERE tree_level = 1234
     * $query->filterByTreeLevel(array(12, 34)); // WHERE tree_level IN (12, 34)
     * $query->filterByTreeLevel(array('min' => 12)); // WHERE tree_level >= 12
     * $query->filterByTreeLevel(array('max' => 12)); // WHERE tree_level <= 12
     * </code>
     *
     * @param     mixed $treeLevel The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TasktypeQuery The current query, for fluid interface
     */
    public function filterByTreeLevel($treeLevel = null, $comparison = null)
    {
        if (is_array($treeLevel)) {
            $useMinMax = false;
            if (isset($treeLevel['min'])) {
                $this->addUsingAlias(TasktypePeer::TREE_LEVEL, $treeLevel['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLevel['max'])) {
                $this->addUsingAlias(TasktypePeer::TREE_LEVEL, $treeLevel['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TasktypePeer::TREE_LEVEL, $treeLevel, $comparison);
    }

    /**
     * Filter the query by a related Task object
     *
     * @param   Task|PropelObjectCollection $task  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 TasktypeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTask($task, $comparison = null)
    {
        if ($task instanceof Task) {
            return $this
                ->addUsingAlias(TasktypePeer::ID, $task->getTasktypeId(), $comparison);
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
     * @return TasktypeQuery The current query, for fluid interface
     */
    public function joinTask($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useTaskQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTask($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Task', '\DTA\MetadataBundle\Model\Workflow\TaskQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Tasktype $tasktype Object to remove from the list of results
     *
     * @return TasktypeQuery The current query, for fluid interface
     */
    public function prune($tasktype = null)
    {
        if ($tasktype) {
            $this->addUsingAlias(TasktypePeer::ID, $tasktype->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // nested_set behavior

    /**
     * Filter the query to restrict the result to descendants of an object
     *
     * @param     Tasktype $tasktype The object to use for descendant search
     *
     * @return    TasktypeQuery The current query, for fluid interface
     */
    public function descendantsOf($tasktype)
    {
        return $this
            ->addUsingAlias(TasktypePeer::LEFT_COL, $tasktype->getLeftValue(), Criteria::GREATER_THAN)
            ->addUsingAlias(TasktypePeer::LEFT_COL, $tasktype->getRightValue(), Criteria::LESS_THAN);
    }

    /**
     * Filter the query to restrict the result to the branch of an object.
     * Same as descendantsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     Tasktype $tasktype The object to use for branch search
     *
     * @return    TasktypeQuery The current query, for fluid interface
     */
    public function branchOf($tasktype)
    {
        return $this
            ->addUsingAlias(TasktypePeer::LEFT_COL, $tasktype->getLeftValue(), Criteria::GREATER_EQUAL)
            ->addUsingAlias(TasktypePeer::LEFT_COL, $tasktype->getRightValue(), Criteria::LESS_EQUAL);
    }

    /**
     * Filter the query to restrict the result to children of an object
     *
     * @param     Tasktype $tasktype The object to use for child search
     *
     * @return    TasktypeQuery The current query, for fluid interface
     */
    public function childrenOf($tasktype)
    {
        return $this
            ->descendantsOf($tasktype)
            ->addUsingAlias(TasktypePeer::LEVEL_COL, $tasktype->getLevel() + 1, Criteria::EQUAL);
    }

    /**
     * Filter the query to restrict the result to siblings of an object.
     * The result does not include the object passed as parameter.
     *
     * @param     Tasktype $tasktype The object to use for sibling search
     * @param      PropelPDO $con Connection to use.
     *
     * @return    TasktypeQuery The current query, for fluid interface
     */
    public function siblingsOf($tasktype, PropelPDO $con = null)
    {
        if ($tasktype->isRoot()) {
            return $this->
                add(TasktypePeer::LEVEL_COL, '1<>1', Criteria::CUSTOM);
        } else {
            return $this
                ->childrenOf($tasktype->getParent($con))
                ->prune($tasktype);
        }
    }

    /**
     * Filter the query to restrict the result to ancestors of an object
     *
     * @param     Tasktype $tasktype The object to use for ancestors search
     *
     * @return    TasktypeQuery The current query, for fluid interface
     */
    public function ancestorsOf($tasktype)
    {
        return $this
            ->addUsingAlias(TasktypePeer::LEFT_COL, $tasktype->getLeftValue(), Criteria::LESS_THAN)
            ->addUsingAlias(TasktypePeer::RIGHT_COL, $tasktype->getRightValue(), Criteria::GREATER_THAN);
    }

    /**
     * Filter the query to restrict the result to roots of an object.
     * Same as ancestorsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     Tasktype $tasktype The object to use for roots search
     *
     * @return    TasktypeQuery The current query, for fluid interface
     */
    public function rootsOf($tasktype)
    {
        return $this
            ->addUsingAlias(TasktypePeer::LEFT_COL, $tasktype->getLeftValue(), Criteria::LESS_EQUAL)
            ->addUsingAlias(TasktypePeer::RIGHT_COL, $tasktype->getRightValue(), Criteria::GREATER_EQUAL);
    }

    /**
     * Order the result by branch, i.e. natural tree order
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    TasktypeQuery The current query, for fluid interface
     */
    public function orderByBranch($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addDescendingOrderByColumn(TasktypePeer::LEFT_COL);
        } else {
            return $this
                ->addAscendingOrderByColumn(TasktypePeer::LEFT_COL);
        }
    }

    /**
     * Order the result by level, the closer to the root first
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    TasktypeQuery The current query, for fluid interface
     */
    public function orderByLevel($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addAscendingOrderByColumn(TasktypePeer::RIGHT_COL);
        } else {
            return $this
                ->addDescendingOrderByColumn(TasktypePeer::RIGHT_COL);
        }
    }

    /**
     * Returns the root node for the tree
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     Tasktype The tree root object
     */
    public function findRoot($con = null)
    {
        return $this
            ->addUsingAlias(TasktypePeer::LEFT_COL, 1, Criteria::EQUAL)
            ->findOne($con);
    }

    /**
     * Returns the tree of objects
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findTree($con = null)
    {
        return $this
            ->orderByBranch()
            ->find($con);
    }

}
