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
use DTA\MetadataBundle\Model\Namefragment;
use DTA\MetadataBundle\Model\Namefragmenttype;
use DTA\MetadataBundle\Model\NamefragmenttypePeer;
use DTA\MetadataBundle\Model\NamefragmenttypeQuery;

/**
 * @method NamefragmenttypeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method NamefragmenttypeQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method NamefragmenttypeQuery groupById() Group by the id column
 * @method NamefragmenttypeQuery groupByName() Group by the name column
 *
 * @method NamefragmenttypeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method NamefragmenttypeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method NamefragmenttypeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method NamefragmenttypeQuery leftJoinNamefragment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Namefragment relation
 * @method NamefragmenttypeQuery rightJoinNamefragment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Namefragment relation
 * @method NamefragmenttypeQuery innerJoinNamefragment($relationAlias = null) Adds a INNER JOIN clause to the query using the Namefragment relation
 *
 * @method Namefragmenttype findOne(PropelPDO $con = null) Return the first Namefragmenttype matching the query
 * @method Namefragmenttype findOneOrCreate(PropelPDO $con = null) Return the first Namefragmenttype matching the query, or a new Namefragmenttype object populated from the query conditions when no match is found
 *
 * @method Namefragmenttype findOneByName(string $name) Return the first Namefragmenttype filtered by the name column
 *
 * @method array findById(int $id) Return Namefragmenttype objects filtered by the id column
 * @method array findByName(string $name) Return Namefragmenttype objects filtered by the name column
 */
abstract class BaseNamefragmenttypeQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseNamefragmenttypeQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Namefragmenttype', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new NamefragmenttypeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     NamefragmenttypeQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return NamefragmenttypeQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof NamefragmenttypeQuery) {
            return $criteria;
        }
        $query = new NamefragmenttypeQuery();
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
     * @return   Namefragmenttype|Namefragmenttype[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = NamefragmenttypePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(NamefragmenttypePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Namefragmenttype A model object, or null if the key is not found
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
     * @return   Namefragmenttype A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name` FROM `nameFragmentType` WHERE `id` = :p0';
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
            $obj = new Namefragmenttype();
            $obj->hydrate($row);
            NamefragmenttypePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Namefragmenttype|Namefragmenttype[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Namefragmenttype[]|mixed the list of results, formatted by the current formatter
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
     * @return NamefragmenttypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(NamefragmenttypePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return NamefragmenttypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(NamefragmenttypePeer::ID, $keys, Criteria::IN);
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
     * @return NamefragmenttypeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(NamefragmenttypePeer::ID, $id, $comparison);
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
     * @return NamefragmenttypeQuery The current query, for fluid interface
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

        return $this->addUsingAlias(NamefragmenttypePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related Namefragment object
     *
     * @param   Namefragment|PropelObjectCollection $namefragment  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   NamefragmenttypeQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByNamefragment($namefragment, $comparison = null)
    {
        if ($namefragment instanceof Namefragment) {
            return $this
                ->addUsingAlias(NamefragmenttypePeer::ID, $namefragment->getNamefragmenttypeid(), $comparison);
        } elseif ($namefragment instanceof PropelObjectCollection) {
            return $this
                ->useNamefragmentQuery()
                ->filterByPrimaryKeys($namefragment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByNamefragment() only accepts arguments of type Namefragment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Namefragment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return NamefragmenttypeQuery The current query, for fluid interface
     */
    public function joinNamefragment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Namefragment');

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
            $this->addJoinObject($join, 'Namefragment');
        }

        return $this;
    }

    /**
     * Use the Namefragment relation Namefragment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\NamefragmentQuery A secondary query class using the current class as primary query
     */
    public function useNamefragmentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinNamefragment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Namefragment', '\DTA\MetadataBundle\Model\NamefragmentQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Namefragmenttype $namefragmenttype Object to remove from the list of results
     *
     * @return NamefragmenttypeQuery The current query, for fluid interface
     */
    public function prune($namefragmenttype = null)
    {
        if ($namefragmenttype) {
            $this->addUsingAlias(NamefragmenttypePeer::ID, $namefragmenttype->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}