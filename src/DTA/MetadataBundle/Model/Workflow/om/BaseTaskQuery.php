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
use DTA\MetadataBundle\Model\Master\DtaUser;
use DTA\MetadataBundle\Model\Workflow\CopyLocation;
use DTA\MetadataBundle\Model\Workflow\Partner;
use DTA\MetadataBundle\Model\Workflow\Publicationgroup;
use DTA\MetadataBundle\Model\Workflow\Task;
use DTA\MetadataBundle\Model\Workflow\TaskPeer;
use DTA\MetadataBundle\Model\Workflow\TaskQuery;
use DTA\MetadataBundle\Model\Workflow\Tasktype;

/**
 * @method TaskQuery orderById($order = Criteria::ASC) Order by the id column
 * @method TaskQuery orderByTasktypeId($order = Criteria::ASC) Order by the tasktype_id column
 * @method TaskQuery orderByClosed($order = Criteria::ASC) Order by the closed column
 * @method TaskQuery orderByStartDate($order = Criteria::ASC) Order by the start_date column
 * @method TaskQuery orderByEndDate($order = Criteria::ASC) Order by the end_date column
 * @method TaskQuery orderByComments($order = Criteria::ASC) Order by the comments column
 * @method TaskQuery orderByPublicationgroupId($order = Criteria::ASC) Order by the publicationgroup_id column
 * @method TaskQuery orderByPublicationId($order = Criteria::ASC) Order by the publication_id column
 * @method TaskQuery orderByPartnerId($order = Criteria::ASC) Order by the partner_id column
 * @method TaskQuery orderByResponsibleuserId($order = Criteria::ASC) Order by the responsibleuser_id column
 * @method TaskQuery orderByCopylocationId($order = Criteria::ASC) Order by the copylocation_id column
 * @method TaskQuery orderByPriority($order = Criteria::ASC) Order by the priority column
 * @method TaskQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method TaskQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method TaskQuery groupById() Group by the id column
 * @method TaskQuery groupByTasktypeId() Group by the tasktype_id column
 * @method TaskQuery groupByClosed() Group by the closed column
 * @method TaskQuery groupByStartDate() Group by the start_date column
 * @method TaskQuery groupByEndDate() Group by the end_date column
 * @method TaskQuery groupByComments() Group by the comments column
 * @method TaskQuery groupByPublicationgroupId() Group by the publicationgroup_id column
 * @method TaskQuery groupByPublicationId() Group by the publication_id column
 * @method TaskQuery groupByPartnerId() Group by the partner_id column
 * @method TaskQuery groupByResponsibleuserId() Group by the responsibleuser_id column
 * @method TaskQuery groupByCopylocationId() Group by the copylocation_id column
 * @method TaskQuery groupByPriority() Group by the priority column
 * @method TaskQuery groupByCreatedAt() Group by the created_at column
 * @method TaskQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method TaskQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method TaskQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method TaskQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method TaskQuery leftJoinTasktype($relationAlias = null) Adds a LEFT JOIN clause to the query using the Tasktype relation
 * @method TaskQuery rightJoinTasktype($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Tasktype relation
 * @method TaskQuery innerJoinTasktype($relationAlias = null) Adds a INNER JOIN clause to the query using the Tasktype relation
 *
 * @method TaskQuery leftJoinPublicationgroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publicationgroup relation
 * @method TaskQuery rightJoinPublicationgroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publicationgroup relation
 * @method TaskQuery innerJoinPublicationgroup($relationAlias = null) Adds a INNER JOIN clause to the query using the Publicationgroup relation
 *
 * @method TaskQuery leftJoinPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publication relation
 * @method TaskQuery rightJoinPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publication relation
 * @method TaskQuery innerJoinPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the Publication relation
 *
 * @method TaskQuery leftJoinPartner($relationAlias = null) Adds a LEFT JOIN clause to the query using the Partner relation
 * @method TaskQuery rightJoinPartner($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Partner relation
 * @method TaskQuery innerJoinPartner($relationAlias = null) Adds a INNER JOIN clause to the query using the Partner relation
 *
 * @method TaskQuery leftJoinDtaUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the DtaUser relation
 * @method TaskQuery rightJoinDtaUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DtaUser relation
 * @method TaskQuery innerJoinDtaUser($relationAlias = null) Adds a INNER JOIN clause to the query using the DtaUser relation
 *
 * @method TaskQuery leftJoinCopyLocation($relationAlias = null) Adds a LEFT JOIN clause to the query using the CopyLocation relation
 * @method TaskQuery rightJoinCopyLocation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CopyLocation relation
 * @method TaskQuery innerJoinCopyLocation($relationAlias = null) Adds a INNER JOIN clause to the query using the CopyLocation relation
 *
 * @method Task findOne(PropelPDO $con = null) Return the first Task matching the query
 * @method Task findOneOrCreate(PropelPDO $con = null) Return the first Task matching the query, or a new Task object populated from the query conditions when no match is found
 *
 * @method Task findOneByTasktypeId(int $tasktype_id) Return the first Task filtered by the tasktype_id column
 * @method Task findOneByClosed(boolean $closed) Return the first Task filtered by the closed column
 * @method Task findOneByStartDate(string $start_date) Return the first Task filtered by the start_date column
 * @method Task findOneByEndDate(string $end_date) Return the first Task filtered by the end_date column
 * @method Task findOneByComments(string $comments) Return the first Task filtered by the comments column
 * @method Task findOneByPublicationgroupId(int $publicationgroup_id) Return the first Task filtered by the publicationgroup_id column
 * @method Task findOneByPublicationId(int $publication_id) Return the first Task filtered by the publication_id column
 * @method Task findOneByPartnerId(int $partner_id) Return the first Task filtered by the partner_id column
 * @method Task findOneByResponsibleuserId(int $responsibleuser_id) Return the first Task filtered by the responsibleuser_id column
 * @method Task findOneByCopylocationId(int $copylocation_id) Return the first Task filtered by the copylocation_id column
 * @method Task findOneByPriority(int $priority) Return the first Task filtered by the priority column
 * @method Task findOneByCreatedAt(string $created_at) Return the first Task filtered by the created_at column
 * @method Task findOneByUpdatedAt(string $updated_at) Return the first Task filtered by the updated_at column
 *
 * @method array findById(int $id) Return Task objects filtered by the id column
 * @method array findByTasktypeId(int $tasktype_id) Return Task objects filtered by the tasktype_id column
 * @method array findByClosed(boolean $closed) Return Task objects filtered by the closed column
 * @method array findByStartDate(string $start_date) Return Task objects filtered by the start_date column
 * @method array findByEndDate(string $end_date) Return Task objects filtered by the end_date column
 * @method array findByComments(string $comments) Return Task objects filtered by the comments column
 * @method array findByPublicationgroupId(int $publicationgroup_id) Return Task objects filtered by the publicationgroup_id column
 * @method array findByPublicationId(int $publication_id) Return Task objects filtered by the publication_id column
 * @method array findByPartnerId(int $partner_id) Return Task objects filtered by the partner_id column
 * @method array findByResponsibleuserId(int $responsibleuser_id) Return Task objects filtered by the responsibleuser_id column
 * @method array findByCopylocationId(int $copylocation_id) Return Task objects filtered by the copylocation_id column
 * @method array findByPriority(int $priority) Return Task objects filtered by the priority column
 * @method array findByCreatedAt(string $created_at) Return Task objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Task objects filtered by the updated_at column
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
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'dtametadata';
        }
        if (null === $modelName) {
            $modelName = 'DTA\\MetadataBundle\\Model\\Workflow\\Task';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new TaskQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   TaskQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return TaskQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof TaskQuery) {
            return $criteria;
        }
        $query = new TaskQuery(null, null, $modelAlias);

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
            // the object is already in the instance pool
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
     * @return                 Task A model object, or null if the key is not found
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
     * @return                 Task A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "id", "tasktype_id", "closed", "start_date", "end_date", "comments", "publicationgroup_id", "publication_id", "partner_id", "responsibleuser_id", "copylocation_id", "priority", "created_at", "updated_at" FROM "task" WHERE "id" = :p0';
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
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(TaskPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TaskPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the tasktype_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTasktypeId(1234); // WHERE tasktype_id = 1234
     * $query->filterByTasktypeId(array(12, 34)); // WHERE tasktype_id IN (12, 34)
     * $query->filterByTasktypeId(array('min' => 12)); // WHERE tasktype_id >= 12
     * $query->filterByTasktypeId(array('max' => 12)); // WHERE tasktype_id <= 12
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
     * Filter the query on the closed column
     *
     * Example usage:
     * <code>
     * $query->filterByClosed(true); // WHERE closed = true
     * $query->filterByClosed('yes'); // WHERE closed = true
     * </code>
     *
     * @param     boolean|string $closed The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByClosed($closed = null, $comparison = null)
    {
        if (is_string($closed)) {
            $closed = in_array(strtolower($closed), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(TaskPeer::CLOSED, $closed, $comparison);
    }

    /**
     * Filter the query on the start_date column
     *
     * Example usage:
     * <code>
     * $query->filterByStartDate('2011-03-14'); // WHERE start_date = '2011-03-14'
     * $query->filterByStartDate('now'); // WHERE start_date = '2011-03-14'
     * $query->filterByStartDate(array('max' => 'yesterday')); // WHERE start_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $startDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByStartDate($startDate = null, $comparison = null)
    {
        if (is_array($startDate)) {
            $useMinMax = false;
            if (isset($startDate['min'])) {
                $this->addUsingAlias(TaskPeer::START_DATE, $startDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($startDate['max'])) {
                $this->addUsingAlias(TaskPeer::START_DATE, $startDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::START_DATE, $startDate, $comparison);
    }

    /**
     * Filter the query on the end_date column
     *
     * Example usage:
     * <code>
     * $query->filterByEndDate('2011-03-14'); // WHERE end_date = '2011-03-14'
     * $query->filterByEndDate('now'); // WHERE end_date = '2011-03-14'
     * $query->filterByEndDate(array('max' => 'yesterday')); // WHERE end_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $endDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByEndDate($endDate = null, $comparison = null)
    {
        if (is_array($endDate)) {
            $useMinMax = false;
            if (isset($endDate['min'])) {
                $this->addUsingAlias(TaskPeer::END_DATE, $endDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($endDate['max'])) {
                $this->addUsingAlias(TaskPeer::END_DATE, $endDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::END_DATE, $endDate, $comparison);
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
     * Filter the query on the publicationgroup_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublicationgroupId(1234); // WHERE publicationgroup_id = 1234
     * $query->filterByPublicationgroupId(array(12, 34)); // WHERE publicationgroup_id IN (12, 34)
     * $query->filterByPublicationgroupId(array('min' => 12)); // WHERE publicationgroup_id >= 12
     * $query->filterByPublicationgroupId(array('max' => 12)); // WHERE publicationgroup_id <= 12
     * </code>
     *
     * @see       filterByPublicationgroup()
     *
     * @param     mixed $publicationgroupId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByPublicationgroupId($publicationgroupId = null, $comparison = null)
    {
        if (is_array($publicationgroupId)) {
            $useMinMax = false;
            if (isset($publicationgroupId['min'])) {
                $this->addUsingAlias(TaskPeer::PUBLICATIONGROUP_ID, $publicationgroupId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publicationgroupId['max'])) {
                $this->addUsingAlias(TaskPeer::PUBLICATIONGROUP_ID, $publicationgroupId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::PUBLICATIONGROUP_ID, $publicationgroupId, $comparison);
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
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByPublicationId($publicationId = null, $comparison = null)
    {
        if (is_array($publicationId)) {
            $useMinMax = false;
            if (isset($publicationId['min'])) {
                $this->addUsingAlias(TaskPeer::PUBLICATION_ID, $publicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publicationId['max'])) {
                $this->addUsingAlias(TaskPeer::PUBLICATION_ID, $publicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::PUBLICATION_ID, $publicationId, $comparison);
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
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByPartnerId($partnerId = null, $comparison = null)
    {
        if (is_array($partnerId)) {
            $useMinMax = false;
            if (isset($partnerId['min'])) {
                $this->addUsingAlias(TaskPeer::PARTNER_ID, $partnerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($partnerId['max'])) {
                $this->addUsingAlias(TaskPeer::PARTNER_ID, $partnerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::PARTNER_ID, $partnerId, $comparison);
    }

    /**
     * Filter the query on the responsibleuser_id column
     *
     * Example usage:
     * <code>
     * $query->filterByResponsibleuserId(1234); // WHERE responsibleuser_id = 1234
     * $query->filterByResponsibleuserId(array(12, 34)); // WHERE responsibleuser_id IN (12, 34)
     * $query->filterByResponsibleuserId(array('min' => 12)); // WHERE responsibleuser_id >= 12
     * $query->filterByResponsibleuserId(array('max' => 12)); // WHERE responsibleuser_id <= 12
     * </code>
     *
     * @see       filterByDtaUser()
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
     * Filter the query on the copylocation_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCopylocationId(1234); // WHERE copylocation_id = 1234
     * $query->filterByCopylocationId(array(12, 34)); // WHERE copylocation_id IN (12, 34)
     * $query->filterByCopylocationId(array('min' => 12)); // WHERE copylocation_id >= 12
     * $query->filterByCopylocationId(array('max' => 12)); // WHERE copylocation_id <= 12
     * </code>
     *
     * @see       filterByCopyLocation()
     *
     * @param     mixed $copylocationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByCopylocationId($copylocationId = null, $comparison = null)
    {
        if (is_array($copylocationId)) {
            $useMinMax = false;
            if (isset($copylocationId['min'])) {
                $this->addUsingAlias(TaskPeer::COPYLOCATION_ID, $copylocationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($copylocationId['max'])) {
                $this->addUsingAlias(TaskPeer::COPYLOCATION_ID, $copylocationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::COPYLOCATION_ID, $copylocationId, $comparison);
    }

    /**
     * Filter the query on the priority column
     *
     * Example usage:
     * <code>
     * $query->filterByPriority(1234); // WHERE priority = 1234
     * $query->filterByPriority(array(12, 34)); // WHERE priority IN (12, 34)
     * $query->filterByPriority(array('min' => 12)); // WHERE priority >= 12
     * $query->filterByPriority(array('max' => 12)); // WHERE priority <= 12
     * </code>
     *
     * @param     mixed $priority The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByPriority($priority = null, $comparison = null)
    {
        if (is_array($priority)) {
            $useMinMax = false;
            if (isset($priority['min'])) {
                $this->addUsingAlias(TaskPeer::PRIORITY, $priority['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($priority['max'])) {
                $this->addUsingAlias(TaskPeer::PRIORITY, $priority['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::PRIORITY, $priority, $comparison);
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
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(TaskPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(TaskPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return TaskQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(TaskPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(TaskPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TaskPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Tasktype object
     *
     * @param   Tasktype|PropelObjectCollection $tasktype The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 TaskQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
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
     * @return   \DTA\MetadataBundle\Model\Workflow\TasktypeQuery A secondary query class using the current class as primary query
     */
    public function useTasktypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTasktype($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Tasktype', '\DTA\MetadataBundle\Model\Workflow\TasktypeQuery');
    }

    /**
     * Filter the query by a related Publicationgroup object
     *
     * @param   Publicationgroup|PropelObjectCollection $publicationgroup The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 TaskQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationgroup($publicationgroup, $comparison = null)
    {
        if ($publicationgroup instanceof Publicationgroup) {
            return $this
                ->addUsingAlias(TaskPeer::PUBLICATIONGROUP_ID, $publicationgroup->getId(), $comparison);
        } elseif ($publicationgroup instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TaskPeer::PUBLICATIONGROUP_ID, $publicationgroup->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPublicationgroup() only accepts arguments of type Publicationgroup or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Publicationgroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function joinPublicationgroup($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Publicationgroup');

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
            $this->addJoinObject($join, 'Publicationgroup');
        }

        return $this;
    }

    /**
     * Use the Publicationgroup relation Publicationgroup object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Workflow\PublicationgroupQuery A secondary query class using the current class as primary query
     */
    public function usePublicationgroupQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPublicationgroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publicationgroup', '\DTA\MetadataBundle\Model\Workflow\PublicationgroupQuery');
    }

    /**
     * Filter the query by a related Publication object
     *
     * @param   Publication|PropelObjectCollection $publication The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 TaskQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublication($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(TaskPeer::PUBLICATION_ID, $publication->getId(), $comparison);
        } elseif ($publication instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TaskPeer::PUBLICATION_ID, $publication->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return TaskQuery The current query, for fluid interface
     */
    public function joinPublication($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function usePublicationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @return                 TaskQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPartner($partner, $comparison = null)
    {
        if ($partner instanceof Partner) {
            return $this
                ->addUsingAlias(TaskPeer::PARTNER_ID, $partner->getId(), $comparison);
        } elseif ($partner instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TaskPeer::PARTNER_ID, $partner->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return TaskQuery The current query, for fluid interface
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
     * Filter the query by a related DtaUser object
     *
     * @param   DtaUser|PropelObjectCollection $dtaUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 TaskQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDtaUser($dtaUser, $comparison = null)
    {
        if ($dtaUser instanceof DtaUser) {
            return $this
                ->addUsingAlias(TaskPeer::RESPONSIBLEUSER_ID, $dtaUser->getId(), $comparison);
        } elseif ($dtaUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TaskPeer::RESPONSIBLEUSER_ID, $dtaUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDtaUser() only accepts arguments of type DtaUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DtaUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function joinDtaUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DtaUser');

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
            $this->addJoinObject($join, 'DtaUser');
        }

        return $this;
    }

    /**
     * Use the DtaUser relation DtaUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\DtaUserQuery A secondary query class using the current class as primary query
     */
    public function useDtaUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDtaUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DtaUser', '\DTA\MetadataBundle\Model\Master\DtaUserQuery');
    }

    /**
     * Filter the query by a related CopyLocation object
     *
     * @param   CopyLocation|PropelObjectCollection $copyLocation The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 TaskQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCopyLocation($copyLocation, $comparison = null)
    {
        if ($copyLocation instanceof CopyLocation) {
            return $this
                ->addUsingAlias(TaskPeer::COPYLOCATION_ID, $copyLocation->getId(), $comparison);
        } elseif ($copyLocation instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TaskPeer::COPYLOCATION_ID, $copyLocation->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCopyLocation() only accepts arguments of type CopyLocation or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CopyLocation relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return TaskQuery The current query, for fluid interface
     */
    public function joinCopyLocation($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CopyLocation');

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
            $this->addJoinObject($join, 'CopyLocation');
        }

        return $this;
    }

    /**
     * Use the CopyLocation relation CopyLocation object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Workflow\CopyLocationQuery A secondary query class using the current class as primary query
     */
    public function useCopyLocationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCopyLocation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CopyLocation', '\DTA\MetadataBundle\Model\Workflow\CopyLocationQuery');
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

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     TaskQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(TaskPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     TaskQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(TaskPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     TaskQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(TaskPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     TaskQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(TaskPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     TaskQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(TaskPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     TaskQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(TaskPeer::CREATED_AT);
    }
}
