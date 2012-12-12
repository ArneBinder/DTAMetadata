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
use DTA\MetadataBundle\Model\AuthorWorkPeer;
use DTA\MetadataBundle\Model\AuthorWorkQuery;
use DTA\MetadataBundle\Model\Work;

/**
 * @method AuthorWorkQuery orderByWorkId($order = Criteria::ASC) Order by the work_id column
 * @method AuthorWorkQuery orderByAuthorId($order = Criteria::ASC) Order by the author_id column
 * @method AuthorWorkQuery orderByAuthorPersonId($order = Criteria::ASC) Order by the author_person_id column
 * @method AuthorWorkQuery orderByNameId($order = Criteria::ASC) Order by the name_id column
 *
 * @method AuthorWorkQuery groupByWorkId() Group by the work_id column
 * @method AuthorWorkQuery groupByAuthorId() Group by the author_id column
 * @method AuthorWorkQuery groupByAuthorPersonId() Group by the author_person_id column
 * @method AuthorWorkQuery groupByNameId() Group by the name_id column
 *
 * @method AuthorWorkQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method AuthorWorkQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method AuthorWorkQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method AuthorWorkQuery leftJoinWork($relationAlias = null) Adds a LEFT JOIN clause to the query using the Work relation
 * @method AuthorWorkQuery rightJoinWork($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Work relation
 * @method AuthorWorkQuery innerJoinWork($relationAlias = null) Adds a INNER JOIN clause to the query using the Work relation
 *
 * @method AuthorWorkQuery leftJoinAuthor($relationAlias = null) Adds a LEFT JOIN clause to the query using the Author relation
 * @method AuthorWorkQuery rightJoinAuthor($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Author relation
 * @method AuthorWorkQuery innerJoinAuthor($relationAlias = null) Adds a INNER JOIN clause to the query using the Author relation
 *
 * @method AuthorWork findOne(PropelPDO $con = null) Return the first AuthorWork matching the query
 * @method AuthorWork findOneOrCreate(PropelPDO $con = null) Return the first AuthorWork matching the query, or a new AuthorWork object populated from the query conditions when no match is found
 *
 * @method AuthorWork findOneByWorkId(int $work_id) Return the first AuthorWork filtered by the work_id column
 * @method AuthorWork findOneByAuthorId(int $author_id) Return the first AuthorWork filtered by the author_id column
 * @method AuthorWork findOneByAuthorPersonId(int $author_person_id) Return the first AuthorWork filtered by the author_person_id column
 * @method AuthorWork findOneByNameId(int $name_id) Return the first AuthorWork filtered by the name_id column
 *
 * @method array findByWorkId(int $work_id) Return AuthorWork objects filtered by the work_id column
 * @method array findByAuthorId(int $author_id) Return AuthorWork objects filtered by the author_id column
 * @method array findByAuthorPersonId(int $author_person_id) Return AuthorWork objects filtered by the author_person_id column
 * @method array findByNameId(int $name_id) Return AuthorWork objects filtered by the name_id column
 */
abstract class BaseAuthorWorkQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseAuthorWorkQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\AuthorWork', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new AuthorWorkQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     AuthorWorkQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return AuthorWorkQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof AuthorWorkQuery) {
            return $criteria;
        }
        $query = new AuthorWorkQuery();
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
     * $obj = $c->findPk(array(12, 34, 56), $con);
     * </code>
     *
     * @param array $key Primary key to use for the query
                         A Primary key composition: [$work_id, $author_id, $author_person_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   AuthorWork|AuthorWork[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AuthorWorkPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1], (string) $key[2]))))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(AuthorWorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   AuthorWork A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `work_id`, `author_id`, `author_person_id`, `name_id` FROM `author_work` WHERE `work_id` = :p0 AND `author_id` = :p1 AND `author_person_id` = :p2';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new AuthorWork();
            $obj->hydrate($row);
            AuthorWorkPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1], (string) $key[2])));
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
     * @return AuthorWork|AuthorWork[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|AuthorWork[]|mixed the list of results, formatted by the current formatter
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
     * @return AuthorWorkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(AuthorWorkPeer::WORK_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(AuthorWorkPeer::AUTHOR_ID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(AuthorWorkPeer::AUTHOR_PERSON_ID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return AuthorWorkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(AuthorWorkPeer::WORK_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(AuthorWorkPeer::AUTHOR_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(AuthorWorkPeer::AUTHOR_PERSON_ID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the work_id column
     *
     * Example usage:
     * <code>
     * $query->filterByWorkId(1234); // WHERE work_id = 1234
     * $query->filterByWorkId(array(12, 34)); // WHERE work_id IN (12, 34)
     * $query->filterByWorkId(array('min' => 12)); // WHERE work_id > 12
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
     * @return AuthorWorkQuery The current query, for fluid interface
     */
    public function filterByWorkId($workId = null, $comparison = null)
    {
        if (is_array($workId) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(AuthorWorkPeer::WORK_ID, $workId, $comparison);
    }

    /**
     * Filter the query on the author_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAuthorId(1234); // WHERE author_id = 1234
     * $query->filterByAuthorId(array(12, 34)); // WHERE author_id IN (12, 34)
     * $query->filterByAuthorId(array('min' => 12)); // WHERE author_id > 12
     * </code>
     *
     * @see       filterByAuthor()
     *
     * @param     mixed $authorId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AuthorWorkQuery The current query, for fluid interface
     */
    public function filterByAuthorId($authorId = null, $comparison = null)
    {
        if (is_array($authorId) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(AuthorWorkPeer::AUTHOR_ID, $authorId, $comparison);
    }

    /**
     * Filter the query on the author_person_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAuthorPersonId(1234); // WHERE author_person_id = 1234
     * $query->filterByAuthorPersonId(array(12, 34)); // WHERE author_person_id IN (12, 34)
     * $query->filterByAuthorPersonId(array('min' => 12)); // WHERE author_person_id > 12
     * </code>
     *
     * @see       filterByAuthor()
     *
     * @param     mixed $authorPersonId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AuthorWorkQuery The current query, for fluid interface
     */
    public function filterByAuthorPersonId($authorPersonId = null, $comparison = null)
    {
        if (is_array($authorPersonId) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(AuthorWorkPeer::AUTHOR_PERSON_ID, $authorPersonId, $comparison);
    }

    /**
     * Filter the query on the name_id column
     *
     * Example usage:
     * <code>
     * $query->filterByNameId(1234); // WHERE name_id = 1234
     * $query->filterByNameId(array(12, 34)); // WHERE name_id IN (12, 34)
     * $query->filterByNameId(array('min' => 12)); // WHERE name_id > 12
     * </code>
     *
     * @param     mixed $nameId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AuthorWorkQuery The current query, for fluid interface
     */
    public function filterByNameId($nameId = null, $comparison = null)
    {
        if (is_array($nameId)) {
            $useMinMax = false;
            if (isset($nameId['min'])) {
                $this->addUsingAlias(AuthorWorkPeer::NAME_ID, $nameId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nameId['max'])) {
                $this->addUsingAlias(AuthorWorkPeer::NAME_ID, $nameId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AuthorWorkPeer::NAME_ID, $nameId, $comparison);
    }

    /**
     * Filter the query by a related Work object
     *
     * @param   Work|PropelObjectCollection $work The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AuthorWorkQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByWork($work, $comparison = null)
    {
        if ($work instanceof Work) {
            return $this
                ->addUsingAlias(AuthorWorkPeer::WORK_ID, $work->getId(), $comparison);
        } elseif ($work instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AuthorWorkPeer::WORK_ID, $work->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return AuthorWorkQuery The current query, for fluid interface
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
     * @return   \DTA\MetadataBundle\Model\WorkQuery A secondary query class using the current class as primary query
     */
    public function useWorkQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWork($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Work', '\DTA\MetadataBundle\Model\WorkQuery');
    }

    /**
     * Filter the query by a related Author object
     *
     * @param   Author $author The related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AuthorWorkQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByAuthor($author, $comparison = null)
    {
        if ($author instanceof Author) {
            return $this
                ->addUsingAlias(AuthorWorkPeer::AUTHOR_ID, $author->getId(), $comparison)
                ->addUsingAlias(AuthorWorkPeer::AUTHOR_PERSON_ID, $author->getPersonId(), $comparison);
        } else {
            throw new PropelException('filterByAuthor() only accepts arguments of type Author');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Author relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AuthorWorkQuery The current query, for fluid interface
     */
    public function joinAuthor($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Author');

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
            $this->addJoinObject($join, 'Author');
        }

        return $this;
    }

    /**
     * Use the Author relation Author object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\AuthorQuery A secondary query class using the current class as primary query
     */
    public function useAuthorQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAuthor($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Author', '\DTA\MetadataBundle\Model\AuthorQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   AuthorWork $authorWork Object to remove from the list of results
     *
     * @return AuthorWorkQuery The current query, for fluid interface
     */
    public function prune($authorWork = null)
    {
        if ($authorWork) {
            $this->addCond('pruneCond0', $this->getAliasedColName(AuthorWorkPeer::WORK_ID), $authorWork->getWorkId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(AuthorWorkPeer::AUTHOR_ID), $authorWork->getAuthorId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(AuthorWorkPeer::AUTHOR_PERSON_ID), $authorWork->getAuthorPersonId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
