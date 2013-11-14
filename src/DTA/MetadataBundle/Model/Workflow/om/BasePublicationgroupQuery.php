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
use DTA\MetadataBundle\Model\Master\PublicationPublicationgroup;
use DTA\MetadataBundle\Model\Workflow\Publicationgroup;
use DTA\MetadataBundle\Model\Workflow\PublicationgroupPeer;
use DTA\MetadataBundle\Model\Workflow\PublicationgroupQuery;
use DTA\MetadataBundle\Model\Workflow\Task;

/**
 * @method PublicationgroupQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PublicationgroupQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method PublicationgroupQuery orderByLegacyGroupId($order = Criteria::ASC) Order by the legacy_group_id column
 *
 * @method PublicationgroupQuery groupById() Group by the id column
 * @method PublicationgroupQuery groupByName() Group by the name column
 * @method PublicationgroupQuery groupByLegacyGroupId() Group by the legacy_group_id column
 *
 * @method PublicationgroupQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PublicationgroupQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PublicationgroupQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PublicationgroupQuery leftJoinPublicationPublicationgroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationPublicationgroup relation
 * @method PublicationgroupQuery rightJoinPublicationPublicationgroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationPublicationgroup relation
 * @method PublicationgroupQuery innerJoinPublicationPublicationgroup($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationPublicationgroup relation
 *
 * @method PublicationgroupQuery leftJoinTask($relationAlias = null) Adds a LEFT JOIN clause to the query using the Task relation
 * @method PublicationgroupQuery rightJoinTask($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Task relation
 * @method PublicationgroupQuery innerJoinTask($relationAlias = null) Adds a INNER JOIN clause to the query using the Task relation
 *
 * @method Publicationgroup findOne(PropelPDO $con = null) Return the first Publicationgroup matching the query
 * @method Publicationgroup findOneOrCreate(PropelPDO $con = null) Return the first Publicationgroup matching the query, or a new Publicationgroup object populated from the query conditions when no match is found
 *
 * @method Publicationgroup findOneByName(string $name) Return the first Publicationgroup filtered by the name column
 * @method Publicationgroup findOneByLegacyGroupId(int $legacy_group_id) Return the first Publicationgroup filtered by the legacy_group_id column
 *
 * @method array findById(int $id) Return Publicationgroup objects filtered by the id column
 * @method array findByName(string $name) Return Publicationgroup objects filtered by the name column
 * @method array findByLegacyGroupId(int $legacy_group_id) Return Publicationgroup objects filtered by the legacy_group_id column
 */
abstract class BasePublicationgroupQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePublicationgroupQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'dtametadata', $modelName = 'DTA\\MetadataBundle\\Model\\Workflow\\Publicationgroup', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PublicationgroupQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PublicationgroupQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PublicationgroupQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PublicationgroupQuery) {
            return $criteria;
        }
        $query = new PublicationgroupQuery();
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
     * @return   Publicationgroup|Publicationgroup[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PublicationgroupPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PublicationgroupPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Publicationgroup A model object, or null if the key is not found
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
     * @return                 Publicationgroup A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "id", "name", "legacy_group_id" FROM "publicationgroup" WHERE "id" = :p0';
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
            $obj = new Publicationgroup();
            $obj->hydrate($row);
            PublicationgroupPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Publicationgroup|Publicationgroup[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Publicationgroup[]|mixed the list of results, formatted by the current formatter
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
     * @return PublicationgroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PublicationgroupPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PublicationgroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PublicationgroupPeer::ID, $keys, Criteria::IN);
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
     * @return PublicationgroupQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PublicationgroupPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PublicationgroupPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationgroupPeer::ID, $id, $comparison);
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
     * @return PublicationgroupQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PublicationgroupPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the legacy_group_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLegacyGroupId(1234); // WHERE legacy_group_id = 1234
     * $query->filterByLegacyGroupId(array(12, 34)); // WHERE legacy_group_id IN (12, 34)
     * $query->filterByLegacyGroupId(array('min' => 12)); // WHERE legacy_group_id >= 12
     * $query->filterByLegacyGroupId(array('max' => 12)); // WHERE legacy_group_id <= 12
     * </code>
     *
     * @param     mixed $legacyGroupId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationgroupQuery The current query, for fluid interface
     */
    public function filterByLegacyGroupId($legacyGroupId = null, $comparison = null)
    {
        if (is_array($legacyGroupId)) {
            $useMinMax = false;
            if (isset($legacyGroupId['min'])) {
                $this->addUsingAlias(PublicationgroupPeer::LEGACY_GROUP_ID, $legacyGroupId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($legacyGroupId['max'])) {
                $this->addUsingAlias(PublicationgroupPeer::LEGACY_GROUP_ID, $legacyGroupId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationgroupPeer::LEGACY_GROUP_ID, $legacyGroupId, $comparison);
    }

    /**
     * Filter the query by a related PublicationPublicationgroup object
     *
     * @param   PublicationPublicationgroup|PropelObjectCollection $publicationPublicationgroup  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationgroupQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationPublicationgroup($publicationPublicationgroup, $comparison = null)
    {
        if ($publicationPublicationgroup instanceof PublicationPublicationgroup) {
            return $this
                ->addUsingAlias(PublicationgroupPeer::ID, $publicationPublicationgroup->getPublicationgroupId(), $comparison);
        } elseif ($publicationPublicationgroup instanceof PropelObjectCollection) {
            return $this
                ->usePublicationPublicationgroupQuery()
                ->filterByPrimaryKeys($publicationPublicationgroup->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationPublicationgroup() only accepts arguments of type PublicationPublicationgroup or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationPublicationgroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationgroupQuery The current query, for fluid interface
     */
    public function joinPublicationPublicationgroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationPublicationgroup');

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
            $this->addJoinObject($join, 'PublicationPublicationgroup');
        }

        return $this;
    }

    /**
     * Use the PublicationPublicationgroup relation PublicationPublicationgroup object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\PublicationPublicationgroupQuery A secondary query class using the current class as primary query
     */
    public function usePublicationPublicationgroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationPublicationgroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationPublicationgroup', '\DTA\MetadataBundle\Model\Master\PublicationPublicationgroupQuery');
    }

    /**
     * Filter the query by a related Task object
     *
     * @param   Task|PropelObjectCollection $task  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationgroupQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTask($task, $comparison = null)
    {
        if ($task instanceof Task) {
            return $this
                ->addUsingAlias(PublicationgroupPeer::ID, $task->getPublicationgroupId(), $comparison);
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
     * @return PublicationgroupQuery The current query, for fluid interface
     */
    public function joinTask($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useTaskQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTask($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Task', '\DTA\MetadataBundle\Model\Workflow\TaskQuery');
    }

    /**
     * Filter the query by a related Publication object
     * using the publication_publicationgroup table as cross reference
     *
     * @param   Publication $publication the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationgroupQuery The current query, for fluid interface
     */
    public function filterByPublication($publication, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePublicationPublicationgroupQuery()
            ->filterByPublication($publication, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   Publicationgroup $publicationgroup Object to remove from the list of results
     *
     * @return PublicationgroupQuery The current query, for fluid interface
     */
    public function prune($publicationgroup = null)
    {
        if ($publicationgroup) {
            $this->addUsingAlias(PublicationgroupPeer::ID, $publicationgroup->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
