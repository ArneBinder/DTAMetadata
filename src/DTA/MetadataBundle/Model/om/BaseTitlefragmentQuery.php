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
use DTA\MetadataBundle\Model\Title;
use DTA\MetadataBundle\Model\Titlefragment;
use DTA\MetadataBundle\Model\TitlefragmentPeer;
use DTA\MetadataBundle\Model\TitlefragmentQuery;
use DTA\MetadataBundle\Model\Titlefragmenttype;

/**
 * @method TitlefragmentQuery orderById($order = Criteria::ASC) Order by the id column
 * @method TitlefragmentQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method TitlefragmentQuery orderByTitleId($order = Criteria::ASC) Order by the title_id column
 * @method TitlefragmentQuery orderByTitlefragmenttypeId($order = Criteria::ASC) Order by the titleFragmentType_id column
 * @method TitlefragmentQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method TitlefragmentQuery groupById() Group by the id column
 * @method TitlefragmentQuery groupByName() Group by the name column
 * @method TitlefragmentQuery groupByTitleId() Group by the title_id column
 * @method TitlefragmentQuery groupByTitlefragmenttypeId() Group by the titleFragmentType_id column
 * @method TitlefragmentQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method TitlefragmentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method TitlefragmentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method TitlefragmentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method TitlefragmentQuery leftJoinTitle($relationAlias = null) Adds a LEFT JOIN clause to the query using the Title relation
 * @method TitlefragmentQuery rightJoinTitle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Title relation
 * @method TitlefragmentQuery innerJoinTitle($relationAlias = null) Adds a INNER JOIN clause to the query using the Title relation
 *
 * @method TitlefragmentQuery leftJoinTitlefragmenttype($relationAlias = null) Adds a LEFT JOIN clause to the query using the Titlefragmenttype relation
 * @method TitlefragmentQuery rightJoinTitlefragmenttype($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Titlefragmenttype relation
 * @method TitlefragmentQuery innerJoinTitlefragmenttype($relationAlias = null) Adds a INNER JOIN clause to the query using the Titlefragmenttype relation
 *
 * @method Titlefragment findOne(PropelPDO $con = null) Return the first Titlefragment matching the query
 * @method Titlefragment findOneOrCreate(PropelPDO $con = null) Return the first Titlefragment matching the query, or a new Titlefragment object populated from the query conditions when no match is found
 *
 * @method Titlefragment findOneByName(string $name) Return the first Titlefragment filtered by the name column
 * @method Titlefragment findOneByTitleId(int $title_id) Return the first Titlefragment filtered by the title_id column
 * @method Titlefragment findOneByTitlefragmenttypeId(int $titleFragmentType_id) Return the first Titlefragment filtered by the titleFragmentType_id column
 * @method Titlefragment findOneBySortableRank(int $sortable_rank) Return the first Titlefragment filtered by the sortable_rank column
 *
 * @method array findById(int $id) Return Titlefragment objects filtered by the id column
 * @method array findByName(string $name) Return Titlefragment objects filtered by the name column
 * @method array findByTitleId(int $title_id) Return Titlefragment objects filtered by the title_id column
 * @method array findByTitlefragmenttypeId(int $titleFragmentType_id) Return Titlefragment objects filtered by the titleFragmentType_id column
 * @method array findBySortableRank(int $sortable_rank) Return Titlefragment objects filtered by the sortable_rank column
 */
abstract class BaseTitlefragmentQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseTitlefragmentQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Titlefragment', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new TitlefragmentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     TitlefragmentQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return TitlefragmentQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof TitlefragmentQuery) {
            return $criteria;
        }
        $query = new TitlefragmentQuery();
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
     * @return   Titlefragment|Titlefragment[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TitlefragmentPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(TitlefragmentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Titlefragment A model object, or null if the key is not found
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
     * @return   Titlefragment A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `title_id`, `titleFragmentType_id`, `sortable_rank` FROM `titleFragment` WHERE `id` = :p0';
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
            $obj = new Titlefragment();
            $obj->hydrate($row);
            TitlefragmentPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Titlefragment|Titlefragment[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Titlefragment[]|mixed the list of results, formatted by the current formatter
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
     * @return TitlefragmentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TitlefragmentPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return TitlefragmentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TitlefragmentPeer::ID, $keys, Criteria::IN);
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
     * @return TitlefragmentQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(TitlefragmentPeer::ID, $id, $comparison);
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
     * @return TitlefragmentQuery The current query, for fluid interface
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

        return $this->addUsingAlias(TitlefragmentPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the title_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTitleId(1234); // WHERE title_id = 1234
     * $query->filterByTitleId(array(12, 34)); // WHERE title_id IN (12, 34)
     * $query->filterByTitleId(array('min' => 12)); // WHERE title_id > 12
     * </code>
     *
     * @see       filterByTitle()
     *
     * @param     mixed $titleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TitlefragmentQuery The current query, for fluid interface
     */
    public function filterByTitleId($titleId = null, $comparison = null)
    {
        if (is_array($titleId)) {
            $useMinMax = false;
            if (isset($titleId['min'])) {
                $this->addUsingAlias(TitlefragmentPeer::TITLE_ID, $titleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($titleId['max'])) {
                $this->addUsingAlias(TitlefragmentPeer::TITLE_ID, $titleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TitlefragmentPeer::TITLE_ID, $titleId, $comparison);
    }

    /**
     * Filter the query on the titleFragmentType_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTitlefragmenttypeId(1234); // WHERE titleFragmentType_id = 1234
     * $query->filterByTitlefragmenttypeId(array(12, 34)); // WHERE titleFragmentType_id IN (12, 34)
     * $query->filterByTitlefragmenttypeId(array('min' => 12)); // WHERE titleFragmentType_id > 12
     * </code>
     *
     * @see       filterByTitlefragmenttype()
     *
     * @param     mixed $titlefragmenttypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TitlefragmentQuery The current query, for fluid interface
     */
    public function filterByTitlefragmenttypeId($titlefragmenttypeId = null, $comparison = null)
    {
        if (is_array($titlefragmenttypeId)) {
            $useMinMax = false;
            if (isset($titlefragmenttypeId['min'])) {
                $this->addUsingAlias(TitlefragmentPeer::TITLEFRAGMENTTYPE_ID, $titlefragmenttypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($titlefragmenttypeId['max'])) {
                $this->addUsingAlias(TitlefragmentPeer::TITLEFRAGMENTTYPE_ID, $titlefragmenttypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TitlefragmentPeer::TITLEFRAGMENTTYPE_ID, $titlefragmenttypeId, $comparison);
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
     * @return TitlefragmentQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(TitlefragmentPeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(TitlefragmentPeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TitlefragmentPeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related Title object
     *
     * @param   Title|PropelObjectCollection $title The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   TitlefragmentQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByTitle($title, $comparison = null)
    {
        if ($title instanceof Title) {
            return $this
                ->addUsingAlias(TitlefragmentPeer::TITLE_ID, $title->getId(), $comparison);
        } elseif ($title instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TitlefragmentPeer::TITLE_ID, $title->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTitle() only accepts arguments of type Title or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Title relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return TitlefragmentQuery The current query, for fluid interface
     */
    public function joinTitle($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Title');

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
            $this->addJoinObject($join, 'Title');
        }

        return $this;
    }

    /**
     * Use the Title relation Title object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\TitleQuery A secondary query class using the current class as primary query
     */
    public function useTitleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTitle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Title', '\DTA\MetadataBundle\Model\TitleQuery');
    }

    /**
     * Filter the query by a related Titlefragmenttype object
     *
     * @param   Titlefragmenttype|PropelObjectCollection $titlefragmenttype The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   TitlefragmentQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByTitlefragmenttype($titlefragmenttype, $comparison = null)
    {
        if ($titlefragmenttype instanceof Titlefragmenttype) {
            return $this
                ->addUsingAlias(TitlefragmentPeer::TITLEFRAGMENTTYPE_ID, $titlefragmenttype->getId(), $comparison);
        } elseif ($titlefragmenttype instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TitlefragmentPeer::TITLEFRAGMENTTYPE_ID, $titlefragmenttype->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTitlefragmenttype() only accepts arguments of type Titlefragmenttype or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Titlefragmenttype relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return TitlefragmentQuery The current query, for fluid interface
     */
    public function joinTitlefragmenttype($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Titlefragmenttype');

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
            $this->addJoinObject($join, 'Titlefragmenttype');
        }

        return $this;
    }

    /**
     * Use the Titlefragmenttype relation Titlefragmenttype object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\TitlefragmenttypeQuery A secondary query class using the current class as primary query
     */
    public function useTitlefragmenttypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTitlefragmenttype($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Titlefragmenttype', '\DTA\MetadataBundle\Model\TitlefragmenttypeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Titlefragment $titlefragment Object to remove from the list of results
     *
     * @return TitlefragmentQuery The current query, for fluid interface
     */
    public function prune($titlefragment = null)
    {
        if ($titlefragment) {
            $this->addUsingAlias(TitlefragmentPeer::ID, $titlefragment->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // sortable behavior

    /**
     * Returns the objects in a certain list, from the list scope
     *
     * @param     int $scope		Scope to determine which objects node to return
     *
     * @return    TitlefragmentQuery The current query, for fluid interface
     */
    public function inList($scope = null)
    {
        return $this->addUsingAlias(TitlefragmentPeer::SCOPE_COL, $scope, Criteria::EQUAL);
    }

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     * @param     int $scope		Scope to determine which suite to consider
     *
     * @return    TitlefragmentQuery The current query, for fluid interface
     */
    public function filterByRank($rank, $scope = null)
    {
        return $this
            ->inList($scope)
            ->addUsingAlias(TitlefragmentPeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    TitlefragmentQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(TitlefragmentPeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(TitlefragmentPeer::RANK_COL));
                break;
            default:
                throw new PropelException('TitlefragmentQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     int $scope		Scope to determine which suite to consider
     * @param     PropelPDO $con optional connection
     *
     * @return    Titlefragment
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
            $con = Propel::getConnection(TitlefragmentPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . TitlefragmentPeer::RANK_COL . ')');
        $this->add(TitlefragmentPeer::SCOPE_COL, $scope, Criteria::EQUAL);
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
            $con = Propel::getConnection(TitlefragmentPeer::DATABASE_NAME);
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