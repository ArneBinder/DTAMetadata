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
use DTA\MetadataBundle\Model\Task;
use DTA\MetadataBundle\Model\TaskPeer;
use DTA\MetadataBundle\Model\TaskQuery;
use DTA\MetadataBundle\Model\Tasktype;
use DTA\MetadataBundle\Model\User;
use DTA\MetadataBundle\Model\Writ;
use DTA\MetadataBundle\Model\Writgroup;

/**
 * @method TaskQuery orderById($order = Criteria::ASC) Order by the id column
 * @method TaskQuery orderByTasktypeId($order = Criteria::ASC) Order by the taskType_id column
 * @method TaskQuery orderByDone($order = Criteria::ASC) Order by the done column
 * @method TaskQuery orderByStart($order = Criteria::ASC) Order by the start column
 * @method TaskQuery orderByEnd($order = Criteria::ASC) Order by the end column
 * @method TaskQuery orderByComments($order = Criteria::ASC) Order by the comments column
 * @method TaskQuery orderByWritgroupId($order = Criteria::ASC) Order by the writGroup_id column
 * @method TaskQuery orderByWritId($order = Criteria::ASC) Order by the writ_id column
 * @method TaskQuery orderByResponsibleuserId($order = Criteria::ASC) Order by the responsibleUser_id column
 *
 * @method TaskQuery groupById() Group by the id column
 * @method TaskQuery groupByTasktypeId() Group by the taskType_id column
 * @method TaskQuery groupByDone() Group by the done column
 * @method TaskQuery groupByStart() Group by the start column
 * @method TaskQuery groupByEnd() Group by the end column
 * @method TaskQuery groupByComments() Group by the comments column
 * @method TaskQuery groupByWritgroupId() Group by the writGroup_id column
 * @method TaskQuery groupByWritId() Group by the writ_id column
 * @method TaskQuery groupByResponsibleuserId() Group by the responsibleUser_id column
 *
 * @method TaskQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method TaskQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method TaskQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method TaskQuery leftJoinTasktype($relationAlias = null) Adds a LEFT JOIN clause to the query using the Tasktype relation
 * @method TaskQuery rightJoinTasktype($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Tasktype relation
 * @method TaskQuery innerJoinTasktype($relationAlias = null) Adds a INNER JOIN clause to the query using the Tasktype relation
 *
 * @method TaskQuery leftJoinWritgroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the Writgroup relation
 * @method TaskQuery rightJoinWritgroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Writgroup relation
 * @method TaskQuery innerJoinWritgroup($relationAlias = null) Adds a INNER JOIN clause to the query using the Writgroup relation
 *
 * @method TaskQuery leftJoinWrit($relationAlias = null) Adds a LEFT JOIN clause to the query using the Writ relation
 * @method TaskQuery rightJoinWrit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Writ relation
 * @method TaskQuery innerJoinWrit($relationAlias = null) Adds a INNER JOIN clause to the query using the Writ relation
 *
 * @method TaskQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method TaskQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method TaskQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method Task findOne(PropelPDO $con = null) Return the first Task matching the query
 * @method Task findOneOrCreate(PropelPDO $con = null) Return the first Task matching the query, or a new Task object populated from the query conditions when no match is found
 *
 * @method Task findOneByTasktypeId(int $taskType_id) Return the first Task filtered by the taskType_id column
 * @method Task findOneByDone(boolean $done) Return the first Task filtered by the done column
 * @method Task findOneByStart(string $start) Return the first Task filtered by the start column
 * @method Task findOneByEnd(string $end) Return the first Task filtered by the end column
 * @method Task findOneByComments(string $comments) Return the first Task filtered by the comments column
 * @method Task findOneByWritgroupId(int $writGroup_id) Return the first Task filtered by the writGroup_id column
 * @method Task findOneByWritId(int $writ_id) Return the first Task filtered by the writ_id column
 * @method Task findOneByResponsibleuserId(int $responsibleUser_id) Return the first Task filtered by the responsibleUser_id column
 *
 * @method array findById(int $id) Return Task objects filtered by the id column
 * @method array findByTasktypeId(int $taskType_id) Return Task objects filtered by the taskType_id column
 * @method array findByDone(boolean $done) Return Task objects filtered by the done column
 * @method array findByStart(string $start) Return Task objects filtered by the start column
 * @method array findByEnd(string $end) Return Task objects filtered by the end column
 * @method array findByComments(string $comments) Return Task objects filtered by the comments column
 * @method array findByWritgroupId(int $writGroup_id) Return Task objects filtered by the writGroup_id column
 * @method array findByWritId(int $writ_id) Return Task objects filtered by the writ_id column
 * @method array findByResponsibleuserId(int $responsibleUser_id) Return Task objects filtered by the responsibleUser_id column
 */
abstract class BaseTaskQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseTaskQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Task', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new TaskQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     TaskQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return TaskQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof TaskQuery) {
            return $criteria;
        }
        $query = new TaskQuery();
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
     * @return   Task|Task[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TaskPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(TaskPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Task A model object, or null if the key is not found
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
     * @return   Task A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `taskType_id`, `done`, `start`, `end`, `comments`, `writGroup_id`, `writ_id`, `responsibleUser_id` FROM `task` WHERE `id` = :p0';
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
            $obj = new Task();
            $obj->hydrate($row);
            TaskPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Task|Task[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Task[]|mixed the list of results, formatted by the current formatter
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
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TaskPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TaskPeer::ID, $keys, Criteria::IN);
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
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(TaskPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the taskType_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTasktypeId(1234); // WHERE taskType_id = 1234
     * $query->filterByTasktypeId(array(12, 34)); // WHERE taskType_id IN (12, 34)
     * $query->filterByTasktypeId(array('min' => 12)); // WHERE taskType_id > 12
     * </code>
     *
     * @see       filterByTasktype()
     *
     * @param     mixed $tasktypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByTasktypeId($tasktypeId = null, $comparison = null)
    {
        if (is_array($tasktypeId)) {
            $useMinMax = false;
            if (isset($tasktypeId['min'])) {
                $this->addUsingAlias(TaskPeer::TASKTYPE_ID, $tasktypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($tasktypeId['max'])) {
                $this->addUsingAlias(TaskPeer::TASKTYPE_ID, $tasktypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::TASKTYPE_ID, $tasktypeId, $comparison);
    }

    /**
     * Filter the query on the done column
     *
     * Example usage:
     * <code>
     * $query->filterByDone(true); // WHERE done = true
     * $query->filterByDone('yes'); // WHERE done = true
     * </code>
     *
     * @param     boolean|string $done The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByDone($done = null, $comparison = null)
    {
        if (is_string($done)) {
            $done = in_array(strtolower($done), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(TaskPeer::DONE, $done, $comparison);
    }

    /**
     * Filter the query on the start column
     *
     * Example usage:
     * <code>
     * $query->filterByStart('2011-03-14'); // WHERE start = '2011-03-14'
     * $query->filterByStart('now'); // WHERE start = '2011-03-14'
     * $query->filterByStart(array('max' => 'yesterday')); // WHERE start > '2011-03-13'
     * </code>
     *
     * @param     mixed $start The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByStart($start = null, $comparison = null)
    {
        if (is_array($start)) {
            $useMinMax = false;
            if (isset($start['min'])) {
                $this->addUsingAlias(TaskPeer::START, $start['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($start['max'])) {
                $this->addUsingAlias(TaskPeer::START, $start['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::START, $start, $comparison);
    }

    /**
     * Filter the query on the end column
     *
     * Example usage:
     * <code>
     * $query->filterByEnd('2011-03-14'); // WHERE end = '2011-03-14'
     * $query->filterByEnd('now'); // WHERE end = '2011-03-14'
     * $query->filterByEnd(array('max' => 'yesterday')); // WHERE end > '2011-03-13'
     * </code>
     *
     * @param     mixed $end The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByEnd($end = null, $comparison = null)
    {
        if (is_array($end)) {
            $useMinMax = false;
            if (isset($end['min'])) {
                $this->addUsingAlias(TaskPeer::END, $end['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($end['max'])) {
                $this->addUsingAlias(TaskPeer::END, $end['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::END, $end, $comparison);
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
     * @return TaskQuery The current query, for fluid interface
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

        return $this->addUsingAlias(TaskPeer::COMMENTS, $comments, $comparison);
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
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByWritgroupId($writgroupId = null, $comparison = null)
    {
        if (is_array($writgroupId)) {
            $useMinMax = false;
            if (isset($writgroupId['min'])) {
                $this->addUsingAlias(TaskPeer::WRITGROUP_ID, $writgroupId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($writgroupId['max'])) {
                $this->addUsingAlias(TaskPeer::WRITGROUP_ID, $writgroupId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::WRITGROUP_ID, $writgroupId, $comparison);
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
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByWritId($writId = null, $comparison = null)
    {
        if (is_array($writId)) {
            $useMinMax = false;
            if (isset($writId['min'])) {
                $this->addUsingAlias(TaskPeer::WRIT_ID, $writId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($writId['max'])) {
                $this->addUsingAlias(TaskPeer::WRIT_ID, $writId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::WRIT_ID, $writId, $comparison);
    }

    /**
     * Filter the query on the responsibleUser_id column
     *
     * Example usage:
     * <code>
     * $query->filterByResponsibleuserId(1234); // WHERE responsibleUser_id = 1234
     * $query->filterByResponsibleuserId(array(12, 34)); // WHERE responsibleUser_id IN (12, 34)
     * $query->filterByResponsibleuserId(array('min' => 12)); // WHERE responsibleUser_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $responsibleuserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByResponsibleuserId($responsibleuserId = null, $comparison = null)
    {
        if (is_array($responsibleuserId)) {
            $useMinMax = false;
            if (isset($responsibleuserId['min'])) {
                $this->addUsingAlias(TaskPeer::RESPONSIBLEUSER_ID, $responsibleuserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($responsibleuserId['max'])) {
                $this->addUsingAlias(TaskPeer::RESPONSIBLEUSER_ID, $responsibleuserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::RESPONSIBLEUSER_ID, $responsibleuserId, $comparison);
    }

    /**
     * Filter the query by a related Tasktype object
     *
     * @param   Tasktype|PropelObjectCollection $tasktype The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   TaskQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByTasktype($tasktype, $comparison = null)
    {
        if ($tasktype instanceof Tasktype) {
            return $this
                ->addUsingAlias(TaskPeer::TASKTYPE_ID, $tasktype->getId(), $comparison);
        } elseif ($tasktype instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TaskPeer::TASKTYPE_ID, $tasktype->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTasktype() only accepts arguments of type Tasktype or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Tasktype relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function joinTasktype($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Tasktype');

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
            $this->addJoinObject($join, 'Tasktype');
        }

        return $this;
    }

    /**
     * Use the Tasktype relation Tasktype object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\TasktypeQuery A secondary query class using the current class as primary query
     */
    public function useTasktypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTasktype($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Tasktype', '\DTA\MetadataBundle\Model\TasktypeQuery');
    }

    /**
     * Filter the query by a related Writgroup object
     *
     * @param   Writgroup|PropelObjectCollection $writgroup The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   TaskQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByWritgroup($writgroup, $comparison = null)
    {
        if ($writgroup instanceof Writgroup) {
            return $this
                ->addUsingAlias(TaskPeer::WRITGROUP_ID, $writgroup->getId(), $comparison);
        } elseif ($writgroup instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TaskPeer::WRITGROUP_ID, $writgroup->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return TaskQuery The current query, for fluid interface
     */
    public function joinWritgroup($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useWritgroupQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @return   TaskQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByWrit($writ, $comparison = null)
    {
        if ($writ instanceof Writ) {
            return $this
                ->addUsingAlias(TaskPeer::WRIT_ID, $writ->getId(), $comparison);
        } elseif ($writ instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TaskPeer::WRIT_ID, $writ->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return TaskQuery The current query, for fluid interface
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
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   TaskQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(TaskPeer::RESPONSIBLEUSER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TaskPeer::RESPONSIBLEUSER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\DTA\MetadataBundle\Model\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Task $task Object to remove from the list of results
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function prune($task = null)
    {
        if ($task) {
            $this->addUsingAlias(TaskPeer::ID, $task->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
