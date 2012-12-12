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
use DTA\MetadataBundle\Model\Dwdsgenre;
use DTA\MetadataBundle\Model\DwdsgenrePeer;
use DTA\MetadataBundle\Model\DwdsgenreQuery;
use DTA\MetadataBundle\Model\Work;

/**
 * @method DwdsgenreQuery orderById($order = Criteria::ASC) Order by the id column
 * @method DwdsgenreQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method DwdsgenreQuery orderByChildof($order = Criteria::ASC) Order by the childOf column
 *
 * @method DwdsgenreQuery groupById() Group by the id column
 * @method DwdsgenreQuery groupByName() Group by the name column
 * @method DwdsgenreQuery groupByChildof() Group by the childOf column
 *
 * @method DwdsgenreQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method DwdsgenreQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method DwdsgenreQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method DwdsgenreQuery leftJoinDwdsgenreRelatedByChildof($relationAlias = null) Adds a LEFT JOIN clause to the query using the DwdsgenreRelatedByChildof relation
 * @method DwdsgenreQuery rightJoinDwdsgenreRelatedByChildof($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DwdsgenreRelatedByChildof relation
 * @method DwdsgenreQuery innerJoinDwdsgenreRelatedByChildof($relationAlias = null) Adds a INNER JOIN clause to the query using the DwdsgenreRelatedByChildof relation
 *
 * @method DwdsgenreQuery leftJoinDwdsgenreRelatedById($relationAlias = null) Adds a LEFT JOIN clause to the query using the DwdsgenreRelatedById relation
 * @method DwdsgenreQuery rightJoinDwdsgenreRelatedById($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DwdsgenreRelatedById relation
 * @method DwdsgenreQuery innerJoinDwdsgenreRelatedById($relationAlias = null) Adds a INNER JOIN clause to the query using the DwdsgenreRelatedById relation
 *
 * @method DwdsgenreQuery leftJoinWorkRelatedByDwdsgenreId($relationAlias = null) Adds a LEFT JOIN clause to the query using the WorkRelatedByDwdsgenreId relation
 * @method DwdsgenreQuery rightJoinWorkRelatedByDwdsgenreId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WorkRelatedByDwdsgenreId relation
 * @method DwdsgenreQuery innerJoinWorkRelatedByDwdsgenreId($relationAlias = null) Adds a INNER JOIN clause to the query using the WorkRelatedByDwdsgenreId relation
 *
 * @method DwdsgenreQuery leftJoinWorkRelatedByDwdssubgenreId($relationAlias = null) Adds a LEFT JOIN clause to the query using the WorkRelatedByDwdssubgenreId relation
 * @method DwdsgenreQuery rightJoinWorkRelatedByDwdssubgenreId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WorkRelatedByDwdssubgenreId relation
 * @method DwdsgenreQuery innerJoinWorkRelatedByDwdssubgenreId($relationAlias = null) Adds a INNER JOIN clause to the query using the WorkRelatedByDwdssubgenreId relation
 *
 * @method Dwdsgenre findOne(PropelPDO $con = null) Return the first Dwdsgenre matching the query
 * @method Dwdsgenre findOneOrCreate(PropelPDO $con = null) Return the first Dwdsgenre matching the query, or a new Dwdsgenre object populated from the query conditions when no match is found
 *
 * @method Dwdsgenre findOneByName(string $name) Return the first Dwdsgenre filtered by the name column
 * @method Dwdsgenre findOneByChildof(int $childOf) Return the first Dwdsgenre filtered by the childOf column
 *
 * @method array findById(int $id) Return Dwdsgenre objects filtered by the id column
 * @method array findByName(string $name) Return Dwdsgenre objects filtered by the name column
 * @method array findByChildof(int $childOf) Return Dwdsgenre objects filtered by the childOf column
 */
abstract class BaseDwdsgenreQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseDwdsgenreQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Dwdsgenre', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new DwdsgenreQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     DwdsgenreQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return DwdsgenreQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof DwdsgenreQuery) {
            return $criteria;
        }
        $query = new DwdsgenreQuery();
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
     * @return   Dwdsgenre|Dwdsgenre[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DwdsgenrePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(DwdsgenrePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Dwdsgenre A model object, or null if the key is not found
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
     * @return   Dwdsgenre A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `childOf` FROM `dwdsGenre` WHERE `id` = :p0';
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
            $obj = new Dwdsgenre();
            $obj->hydrate($row);
            DwdsgenrePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Dwdsgenre|Dwdsgenre[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Dwdsgenre[]|mixed the list of results, formatted by the current formatter
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
     * @return DwdsgenreQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DwdsgenrePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return DwdsgenreQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DwdsgenrePeer::ID, $keys, Criteria::IN);
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
     * @return DwdsgenreQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(DwdsgenrePeer::ID, $id, $comparison);
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
     * @return DwdsgenreQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DwdsgenrePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the childOf column
     *
     * Example usage:
     * <code>
     * $query->filterByChildof(1234); // WHERE childOf = 1234
     * $query->filterByChildof(array(12, 34)); // WHERE childOf IN (12, 34)
     * $query->filterByChildof(array('min' => 12)); // WHERE childOf > 12
     * </code>
     *
     * @see       filterByDwdsgenreRelatedByChildof()
     *
     * @param     mixed $childof The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DwdsgenreQuery The current query, for fluid interface
     */
    public function filterByChildof($childof = null, $comparison = null)
    {
        if (is_array($childof)) {
            $useMinMax = false;
            if (isset($childof['min'])) {
                $this->addUsingAlias(DwdsgenrePeer::CHILDOF, $childof['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($childof['max'])) {
                $this->addUsingAlias(DwdsgenrePeer::CHILDOF, $childof['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DwdsgenrePeer::CHILDOF, $childof, $comparison);
    }

    /**
     * Filter the query by a related Dwdsgenre object
     *
     * @param   Dwdsgenre|PropelObjectCollection $dwdsgenre The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   DwdsgenreQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByDwdsgenreRelatedByChildof($dwdsgenre, $comparison = null)
    {
        if ($dwdsgenre instanceof Dwdsgenre) {
            return $this
                ->addUsingAlias(DwdsgenrePeer::CHILDOF, $dwdsgenre->getId(), $comparison);
        } elseif ($dwdsgenre instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DwdsgenrePeer::CHILDOF, $dwdsgenre->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDwdsgenreRelatedByChildof() only accepts arguments of type Dwdsgenre or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DwdsgenreRelatedByChildof relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DwdsgenreQuery The current query, for fluid interface
     */
    public function joinDwdsgenreRelatedByChildof($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DwdsgenreRelatedByChildof');

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
            $this->addJoinObject($join, 'DwdsgenreRelatedByChildof');
        }

        return $this;
    }

    /**
     * Use the DwdsgenreRelatedByChildof relation Dwdsgenre object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\DwdsgenreQuery A secondary query class using the current class as primary query
     */
    public function useDwdsgenreRelatedByChildofQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDwdsgenreRelatedByChildof($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DwdsgenreRelatedByChildof', '\DTA\MetadataBundle\Model\DwdsgenreQuery');
    }

    /**
     * Filter the query by a related Dwdsgenre object
     *
     * @param   Dwdsgenre|PropelObjectCollection $dwdsgenre  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   DwdsgenreQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByDwdsgenreRelatedById($dwdsgenre, $comparison = null)
    {
        if ($dwdsgenre instanceof Dwdsgenre) {
            return $this
                ->addUsingAlias(DwdsgenrePeer::ID, $dwdsgenre->getChildof(), $comparison);
        } elseif ($dwdsgenre instanceof PropelObjectCollection) {
            return $this
                ->useDwdsgenreRelatedByIdQuery()
                ->filterByPrimaryKeys($dwdsgenre->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDwdsgenreRelatedById() only accepts arguments of type Dwdsgenre or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DwdsgenreRelatedById relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DwdsgenreQuery The current query, for fluid interface
     */
    public function joinDwdsgenreRelatedById($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DwdsgenreRelatedById');

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
            $this->addJoinObject($join, 'DwdsgenreRelatedById');
        }

        return $this;
    }

    /**
     * Use the DwdsgenreRelatedById relation Dwdsgenre object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\DwdsgenreQuery A secondary query class using the current class as primary query
     */
    public function useDwdsgenreRelatedByIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDwdsgenreRelatedById($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DwdsgenreRelatedById', '\DTA\MetadataBundle\Model\DwdsgenreQuery');
    }

    /**
     * Filter the query by a related Work object
     *
     * @param   Work|PropelObjectCollection $work  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   DwdsgenreQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByWorkRelatedByDwdsgenreId($work, $comparison = null)
    {
        if ($work instanceof Work) {
            return $this
                ->addUsingAlias(DwdsgenrePeer::ID, $work->getDwdsgenreId(), $comparison);
        } elseif ($work instanceof PropelObjectCollection) {
            return $this
                ->useWorkRelatedByDwdsgenreIdQuery()
                ->filterByPrimaryKeys($work->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByWorkRelatedByDwdsgenreId() only accepts arguments of type Work or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the WorkRelatedByDwdsgenreId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DwdsgenreQuery The current query, for fluid interface
     */
    public function joinWorkRelatedByDwdsgenreId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('WorkRelatedByDwdsgenreId');

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
            $this->addJoinObject($join, 'WorkRelatedByDwdsgenreId');
        }

        return $this;
    }

    /**
     * Use the WorkRelatedByDwdsgenreId relation Work object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\WorkQuery A secondary query class using the current class as primary query
     */
    public function useWorkRelatedByDwdsgenreIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinWorkRelatedByDwdsgenreId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'WorkRelatedByDwdsgenreId', '\DTA\MetadataBundle\Model\WorkQuery');
    }

    /**
     * Filter the query by a related Work object
     *
     * @param   Work|PropelObjectCollection $work  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   DwdsgenreQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByWorkRelatedByDwdssubgenreId($work, $comparison = null)
    {
        if ($work instanceof Work) {
            return $this
                ->addUsingAlias(DwdsgenrePeer::ID, $work->getDwdssubgenreId(), $comparison);
        } elseif ($work instanceof PropelObjectCollection) {
            return $this
                ->useWorkRelatedByDwdssubgenreIdQuery()
                ->filterByPrimaryKeys($work->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByWorkRelatedByDwdssubgenreId() only accepts arguments of type Work or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the WorkRelatedByDwdssubgenreId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DwdsgenreQuery The current query, for fluid interface
     */
    public function joinWorkRelatedByDwdssubgenreId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('WorkRelatedByDwdssubgenreId');

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
            $this->addJoinObject($join, 'WorkRelatedByDwdssubgenreId');
        }

        return $this;
    }

    /**
     * Use the WorkRelatedByDwdssubgenreId relation Work object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\WorkQuery A secondary query class using the current class as primary query
     */
    public function useWorkRelatedByDwdssubgenreIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinWorkRelatedByDwdssubgenreId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'WorkRelatedByDwdssubgenreId', '\DTA\MetadataBundle\Model\WorkQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Dwdsgenre $dwdsgenre Object to remove from the list of results
     *
     * @return DwdsgenreQuery The current query, for fluid interface
     */
    public function prune($dwdsgenre = null)
    {
        if ($dwdsgenre) {
            $this->addUsingAlias(DwdsgenrePeer::ID, $dwdsgenre->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
