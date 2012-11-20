<?php

namespace DTA\MetadataBundle\Model\Description\om;

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
use DTA\MetadataBundle\Model\Description\Namefragment;
use DTA\MetadataBundle\Model\Description\NamefragmentPeer;
use DTA\MetadataBundle\Model\Description\NamefragmentQuery;
use DTA\MetadataBundle\Model\Description\Namefragmenttype;
use DTA\MetadataBundle\Model\Description\Personalname;

/**
 * @method NamefragmentQuery orderById($order = Criteria::ASC) Order by the id column
 * @method NamefragmentQuery orderByPersonalnameId($order = Criteria::ASC) Order by the personalName_id column
 * @method NamefragmentQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method NamefragmentQuery orderByNamefragmenttypeid($order = Criteria::ASC) Order by the nameFragmentTypeId column
 * @method NamefragmentQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method NamefragmentQuery groupById() Group by the id column
 * @method NamefragmentQuery groupByPersonalnameId() Group by the personalName_id column
 * @method NamefragmentQuery groupByName() Group by the name column
 * @method NamefragmentQuery groupByNamefragmenttypeid() Group by the nameFragmentTypeId column
 * @method NamefragmentQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method NamefragmentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method NamefragmentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method NamefragmentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method NamefragmentQuery leftJoinNamefragmenttype($relationAlias = null) Adds a LEFT JOIN clause to the query using the Namefragmenttype relation
 * @method NamefragmentQuery rightJoinNamefragmenttype($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Namefragmenttype relation
 * @method NamefragmentQuery innerJoinNamefragmenttype($relationAlias = null) Adds a INNER JOIN clause to the query using the Namefragmenttype relation
 *
 * @method NamefragmentQuery leftJoinPersonalname($relationAlias = null) Adds a LEFT JOIN clause to the query using the Personalname relation
 * @method NamefragmentQuery rightJoinPersonalname($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Personalname relation
 * @method NamefragmentQuery innerJoinPersonalname($relationAlias = null) Adds a INNER JOIN clause to the query using the Personalname relation
 *
 * @method Namefragment findOne(PropelPDO $con = null) Return the first Namefragment matching the query
 * @method Namefragment findOneOrCreate(PropelPDO $con = null) Return the first Namefragment matching the query, or a new Namefragment object populated from the query conditions when no match is found
 *
 * @method Namefragment findOneByPersonalnameId(int $personalName_id) Return the first Namefragment filtered by the personalName_id column
 * @method Namefragment findOneByName(string $name) Return the first Namefragment filtered by the name column
 * @method Namefragment findOneByNamefragmenttypeid(int $nameFragmentTypeId) Return the first Namefragment filtered by the nameFragmentTypeId column
 * @method Namefragment findOneBySortableRank(int $sortable_rank) Return the first Namefragment filtered by the sortable_rank column
 *
 * @method array findById(int $id) Return Namefragment objects filtered by the id column
 * @method array findByPersonalnameId(int $personalName_id) Return Namefragment objects filtered by the personalName_id column
 * @method array findByName(string $name) Return Namefragment objects filtered by the name column
 * @method array findByNamefragmenttypeid(int $nameFragmentTypeId) Return Namefragment objects filtered by the nameFragmentTypeId column
 * @method array findBySortableRank(int $sortable_rank) Return Namefragment objects filtered by the sortable_rank column
 */
abstract class BaseNamefragmentQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseNamefragmentQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Description\\Namefragment', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new NamefragmentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     NamefragmentQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return NamefragmentQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof NamefragmentQuery) {
            return $criteria;
        }
        $query = new NamefragmentQuery();
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
     * @return   Namefragment|Namefragment[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = NamefragmentPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(NamefragmentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Namefragment A model object, or null if the key is not found
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
     * @return   Namefragment A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `PERSONALNAME_ID`, `NAME`, `NAMEFRAGMENTTYPEID`, `SORTABLE_RANK` FROM `nameFragment` WHERE `ID` = :p0';
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
            $obj = new Namefragment();
            $obj->hydrate($row);
            NamefragmentPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Namefragment|Namefragment[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Namefragment[]|mixed the list of results, formatted by the current formatter
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
     * @return NamefragmentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(NamefragmentPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return NamefragmentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(NamefragmentPeer::ID, $keys, Criteria::IN);
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
     * @return NamefragmentQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(NamefragmentPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the personalName_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPersonalnameId(1234); // WHERE personalName_id = 1234
     * $query->filterByPersonalnameId(array(12, 34)); // WHERE personalName_id IN (12, 34)
     * $query->filterByPersonalnameId(array('min' => 12)); // WHERE personalName_id > 12
     * </code>
     *
     * @see       filterByPersonalname()
     *
     * @param     mixed $personalnameId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return NamefragmentQuery The current query, for fluid interface
     */
    public function filterByPersonalnameId($personalnameId = null, $comparison = null)
    {
        if (is_array($personalnameId)) {
            $useMinMax = false;
            if (isset($personalnameId['min'])) {
                $this->addUsingAlias(NamefragmentPeer::PERSONALNAME_ID, $personalnameId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($personalnameId['max'])) {
                $this->addUsingAlias(NamefragmentPeer::PERSONALNAME_ID, $personalnameId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NamefragmentPeer::PERSONALNAME_ID, $personalnameId, $comparison);
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
     * @return NamefragmentQuery The current query, for fluid interface
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

        return $this->addUsingAlias(NamefragmentPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the nameFragmentTypeId column
     *
     * Example usage:
     * <code>
     * $query->filterByNamefragmenttypeid(1234); // WHERE nameFragmentTypeId = 1234
     * $query->filterByNamefragmenttypeid(array(12, 34)); // WHERE nameFragmentTypeId IN (12, 34)
     * $query->filterByNamefragmenttypeid(array('min' => 12)); // WHERE nameFragmentTypeId > 12
     * </code>
     *
     * @see       filterByNamefragmenttype()
     *
     * @param     mixed $namefragmenttypeid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return NamefragmentQuery The current query, for fluid interface
     */
    public function filterByNamefragmenttypeid($namefragmenttypeid = null, $comparison = null)
    {
        if (is_array($namefragmenttypeid)) {
            $useMinMax = false;
            if (isset($namefragmenttypeid['min'])) {
                $this->addUsingAlias(NamefragmentPeer::NAMEFRAGMENTTYPEID, $namefragmenttypeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($namefragmenttypeid['max'])) {
                $this->addUsingAlias(NamefragmentPeer::NAMEFRAGMENTTYPEID, $namefragmenttypeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NamefragmentPeer::NAMEFRAGMENTTYPEID, $namefragmenttypeid, $comparison);
    }

    /**
     * Filter the query on the sortable_rank column
     *
     * Example usage:
     * <code>
     * $query->filterBySortableRank(1234); // WHERE sortable_rank = 1234
     * $query->filterBySortableRank(array(12, 34)); // WHERE sortable_rank IN (12, 34)
     * $query->filterBySortableRank(array('min' => 12)); // WHERE sortable_rank > 12
     * </code>
     *
     * @param     mixed $sortableRank The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return NamefragmentQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(NamefragmentPeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(NamefragmentPeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NamefragmentPeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related Namefragmenttype object
     *
     * @param   Namefragmenttype|PropelObjectCollection $namefragmenttype The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   NamefragmentQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByNamefragmenttype($namefragmenttype, $comparison = null)
    {
        if ($namefragmenttype instanceof Namefragmenttype) {
            return $this
                ->addUsingAlias(NamefragmentPeer::NAMEFRAGMENTTYPEID, $namefragmenttype->getId(), $comparison);
        } elseif ($namefragmenttype instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(NamefragmentPeer::NAMEFRAGMENTTYPEID, $namefragmenttype->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByNamefragmenttype() only accepts arguments of type Namefragmenttype or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Namefragmenttype relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return NamefragmentQuery The current query, for fluid interface
     */
    public function joinNamefragmenttype($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Namefragmenttype');

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
            $this->addJoinObject($join, 'Namefragmenttype');
        }

        return $this;
    }

    /**
     * Use the Namefragmenttype relation Namefragmenttype object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Description\NamefragmenttypeQuery A secondary query class using the current class as primary query
     */
    public function useNamefragmenttypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinNamefragmenttype($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Namefragmenttype', '\DTA\MetadataBundle\Model\Description\NamefragmenttypeQuery');
    }

    /**
     * Filter the query by a related Personalname object
     *
     * @param   Personalname|PropelObjectCollection $personalname The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   NamefragmentQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByPersonalname($personalname, $comparison = null)
    {
        if ($personalname instanceof Personalname) {
            return $this
                ->addUsingAlias(NamefragmentPeer::PERSONALNAME_ID, $personalname->getId(), $comparison);
        } elseif ($personalname instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(NamefragmentPeer::PERSONALNAME_ID, $personalname->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPersonalname() only accepts arguments of type Personalname or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Personalname relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return NamefragmentQuery The current query, for fluid interface
     */
    public function joinPersonalname($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Personalname');

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
            $this->addJoinObject($join, 'Personalname');
        }

        return $this;
    }

    /**
     * Use the Personalname relation Personalname object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Description\PersonalnameQuery A secondary query class using the current class as primary query
     */
    public function usePersonalnameQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPersonalname($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Personalname', '\DTA\MetadataBundle\Model\Description\PersonalnameQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Namefragment $namefragment Object to remove from the list of results
     *
     * @return NamefragmentQuery The current query, for fluid interface
     */
    public function prune($namefragment = null)
    {
        if ($namefragment) {
            $this->addUsingAlias(NamefragmentPeer::ID, $namefragment->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // sortable behavior

    /**
     * Returns the objects in a certain list, from the list scope
     *
     * @param     int $scope		Scope to determine which objects node to return
     *
     * @return    NamefragmentQuery The current query, for fluid interface
     */
    public function inList($scope = null)
    {
        return $this->addUsingAlias(NamefragmentPeer::SCOPE_COL, $scope, Criteria::EQUAL);
    }

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     * @param     int $scope		Scope to determine which suite to consider
     *
     * @return    NamefragmentQuery The current query, for fluid interface
     */
    public function filterByRank($rank, $scope = null)
    {
        return $this
            ->inList($scope)
            ->addUsingAlias(NamefragmentPeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    NamefragmentQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(NamefragmentPeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(NamefragmentPeer::RANK_COL));
                break;
            default:
                throw new PropelException('NamefragmentQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     int $scope		Scope to determine which suite to consider
     * @param     PropelPDO $con optional connection
     *
     * @return    Namefragment
     */
    public function findOneByRank($rank, $scope = null, PropelPDO $con = null)
    {
        return $this
            ->filterByRank($rank, $scope)
            ->findOne($con);
    }

    /**
     * Returns a list of objects
     *
     * @param      int $scope		Scope to determine which list to return
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findList($scope = null, $con = null)
    {
        return $this
            ->inList($scope)
            ->orderByRank()
            ->find($con);
    }

    /**
     * Get the highest rank
     *
     * @param      int $scope		Scope to determine which suite to consider
     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRank($scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(NamefragmentPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . NamefragmentPeer::RANK_COL . ')');
        $this->add(NamefragmentPeer::SCOPE_COL, $scope, Criteria::EQUAL);
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Reorder a set of sortable objects based on a list of id/position
     * Beware that there is no check made on the positions passed
     * So incoherent positions will result in an incoherent list
     *
     * @param     array     $order id => rank pairs
     * @param     PropelPDO $con   optional connection
     *
     * @return    boolean true if the reordering took place, false if a database problem prevented it
     */
    public function reorder(array $order, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(NamefragmentPeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = $this->findPks($ids, $con);
            foreach ($objects as $object) {
                $pk = $object->getPrimaryKey();
                if ($object->getSortableRank() != $order[$pk]) {
                    $object->setSortableRank($order[$pk]);
                    $object->save($con);
                }
            }
            $con->commit();

            return true;
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

}
