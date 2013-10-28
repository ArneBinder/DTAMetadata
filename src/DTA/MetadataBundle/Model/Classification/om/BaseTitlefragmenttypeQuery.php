<?php

namespace DTA\MetadataBundle\Model\Classification\om;

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
use DTA\MetadataBundle\Model\Classification\Titlefragmenttype;
use DTA\MetadataBundle\Model\Classification\TitlefragmenttypePeer;
use DTA\MetadataBundle\Model\Classification\TitlefragmenttypeQuery;
use DTA\MetadataBundle\Model\Data\Titlefragment;

/**
 * @method TitlefragmenttypeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method TitlefragmenttypeQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method TitlefragmenttypeQuery groupById() Group by the id column
 * @method TitlefragmenttypeQuery groupByName() Group by the name column
 *
 * @method TitlefragmenttypeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method TitlefragmenttypeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method TitlefragmenttypeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method TitlefragmenttypeQuery leftJoinTitlefragment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Titlefragment relation
 * @method TitlefragmenttypeQuery rightJoinTitlefragment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Titlefragment relation
 * @method TitlefragmenttypeQuery innerJoinTitlefragment($relationAlias = null) Adds a INNER JOIN clause to the query using the Titlefragment relation
 *
 * @method Titlefragmenttype findOne(PropelPDO $con = null) Return the first Titlefragmenttype matching the query
 * @method Titlefragmenttype findOneOrCreate(PropelPDO $con = null) Return the first Titlefragmenttype matching the query, or a new Titlefragmenttype object populated from the query conditions when no match is found
 *
 * @method Titlefragmenttype findOneByName(string $name) Return the first Titlefragmenttype filtered by the name column
 *
 * @method array findById(int $id) Return Titlefragmenttype objects filtered by the id column
 * @method array findByName(string $name) Return Titlefragmenttype objects filtered by the name column
 */
abstract class BaseTitlefragmenttypeQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseTitlefragmenttypeQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'dtametadata', $modelName = 'DTA\\MetadataBundle\\Model\\Classification\\Titlefragmenttype', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new TitlefragmenttypeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   TitlefragmenttypeQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return TitlefragmenttypeQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof TitlefragmenttypeQuery) {
            return $criteria;
        }
        $query = new TitlefragmenttypeQuery();
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
     * @return   Titlefragmenttype|Titlefragmenttype[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TitlefragmenttypePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(TitlefragmenttypePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Titlefragmenttype A model object, or null if the key is not found
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
     * @return                 Titlefragmenttype A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "id", "name" FROM "titlefragmenttype" WHERE "id" = :p0';
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
            $obj = new Titlefragmenttype();
            $obj->hydrate($row);
            TitlefragmenttypePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Titlefragmenttype|Titlefragmenttype[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Titlefragmenttype[]|mixed the list of results, formatted by the current formatter
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
     * @return TitlefragmenttypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TitlefragmenttypePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return TitlefragmenttypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TitlefragmenttypePeer::ID, $keys, Criteria::IN);
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
     * @return TitlefragmenttypeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(TitlefragmenttypePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TitlefragmenttypePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TitlefragmenttypePeer::ID, $id, $comparison);
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
     * @return TitlefragmenttypeQuery The current query, for fluid interface
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

        return $this->addUsingAlias(TitlefragmenttypePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related Titlefragment object
     *
     * @param   Titlefragment|PropelObjectCollection $titlefragment  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 TitlefragmenttypeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTitlefragment($titlefragment, $comparison = null)
    {
        if ($titlefragment instanceof Titlefragment) {
            return $this
                ->addUsingAlias(TitlefragmenttypePeer::ID, $titlefragment->getTitlefragmenttypeId(), $comparison);
        } elseif ($titlefragment instanceof PropelObjectCollection) {
            return $this
                ->useTitlefragmentQuery()
                ->filterByPrimaryKeys($titlefragment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTitlefragment() only accepts arguments of type Titlefragment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Titlefragment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return TitlefragmenttypeQuery The current query, for fluid interface
     */
    public function joinTitlefragment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Titlefragment');

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
            $this->addJoinObject($join, 'Titlefragment');
        }

        return $this;
    }

    /**
     * Use the Titlefragment relation Titlefragment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\TitlefragmentQuery A secondary query class using the current class as primary query
     */
    public function useTitlefragmentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTitlefragment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Titlefragment', '\DTA\MetadataBundle\Model\Data\TitlefragmentQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Titlefragmenttype $titlefragmenttype Object to remove from the list of results
     *
     * @return TitlefragmenttypeQuery The current query, for fluid interface
     */
    public function prune($titlefragmenttype = null)
    {
        if ($titlefragmenttype) {
            $this->addUsingAlias(TitlefragmenttypePeer::ID, $titlefragmenttype->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
