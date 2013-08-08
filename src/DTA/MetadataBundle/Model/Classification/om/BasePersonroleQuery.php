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
use DTA\MetadataBundle\Model\Classification\Personrole;
use DTA\MetadataBundle\Model\Classification\PersonrolePeer;
use DTA\MetadataBundle\Model\Classification\PersonroleQuery;
use DTA\MetadataBundle\Model\Master\PersonPublication;
use DTA\MetadataBundle\Model\Master\PersonWork;

/**
 * @method PersonroleQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PersonroleQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method PersonroleQuery orderByApplicableToPublication($order = Criteria::ASC) Order by the applicable_to_publication column
 * @method PersonroleQuery orderByApplicableToWork($order = Criteria::ASC) Order by the applicable_to_work column
 *
 * @method PersonroleQuery groupById() Group by the id column
 * @method PersonroleQuery groupByName() Group by the name column
 * @method PersonroleQuery groupByApplicableToPublication() Group by the applicable_to_publication column
 * @method PersonroleQuery groupByApplicableToWork() Group by the applicable_to_work column
 *
 * @method PersonroleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PersonroleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PersonroleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PersonroleQuery leftJoinPersonPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the PersonPublication relation
 * @method PersonroleQuery rightJoinPersonPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PersonPublication relation
 * @method PersonroleQuery innerJoinPersonPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the PersonPublication relation
 *
 * @method PersonroleQuery leftJoinPersonWork($relationAlias = null) Adds a LEFT JOIN clause to the query using the PersonWork relation
 * @method PersonroleQuery rightJoinPersonWork($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PersonWork relation
 * @method PersonroleQuery innerJoinPersonWork($relationAlias = null) Adds a INNER JOIN clause to the query using the PersonWork relation
 *
 * @method Personrole findOne(PropelPDO $con = null) Return the first Personrole matching the query
 * @method Personrole findOneOrCreate(PropelPDO $con = null) Return the first Personrole matching the query, or a new Personrole object populated from the query conditions when no match is found
 *
 * @method Personrole findOneByName(string $name) Return the first Personrole filtered by the name column
 * @method Personrole findOneByApplicableToPublication(boolean $applicable_to_publication) Return the first Personrole filtered by the applicable_to_publication column
 * @method Personrole findOneByApplicableToWork(boolean $applicable_to_work) Return the first Personrole filtered by the applicable_to_work column
 *
 * @method array findById(int $id) Return Personrole objects filtered by the id column
 * @method array findByName(string $name) Return Personrole objects filtered by the name column
 * @method array findByApplicableToPublication(boolean $applicable_to_publication) Return Personrole objects filtered by the applicable_to_publication column
 * @method array findByApplicableToWork(boolean $applicable_to_work) Return Personrole objects filtered by the applicable_to_work column
 */
abstract class BasePersonroleQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePersonroleQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Classification\\Personrole', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PersonroleQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PersonroleQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PersonroleQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PersonroleQuery) {
            return $criteria;
        }
        $query = new PersonroleQuery();
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
     * @return   Personrole|Personrole[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PersonrolePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PersonrolePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Personrole A model object, or null if the key is not found
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
     * @return                 Personrole A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "id", "name", "applicable_to_publication", "applicable_to_work" FROM "personrole" WHERE "id" = :p0';
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
            $obj = new Personrole();
            $obj->hydrate($row);
            PersonrolePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Personrole|Personrole[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Personrole[]|mixed the list of results, formatted by the current formatter
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
     * @return PersonroleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PersonrolePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PersonroleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PersonrolePeer::ID, $keys, Criteria::IN);
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
     * @return PersonroleQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PersonrolePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PersonrolePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonrolePeer::ID, $id, $comparison);
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
     * @return PersonroleQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PersonrolePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the applicable_to_publication column
     *
     * Example usage:
     * <code>
     * $query->filterByApplicableToPublication(true); // WHERE applicable_to_publication = true
     * $query->filterByApplicableToPublication('yes'); // WHERE applicable_to_publication = true
     * </code>
     *
     * @param     boolean|string $applicableToPublication The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PersonroleQuery The current query, for fluid interface
     */
    public function filterByApplicableToPublication($applicableToPublication = null, $comparison = null)
    {
        if (is_string($applicableToPublication)) {
            $applicableToPublication = in_array(strtolower($applicableToPublication), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PersonrolePeer::APPLICABLE_TO_PUBLICATION, $applicableToPublication, $comparison);
    }

    /**
     * Filter the query on the applicable_to_work column
     *
     * Example usage:
     * <code>
     * $query->filterByApplicableToWork(true); // WHERE applicable_to_work = true
     * $query->filterByApplicableToWork('yes'); // WHERE applicable_to_work = true
     * </code>
     *
     * @param     boolean|string $applicableToWork The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PersonroleQuery The current query, for fluid interface
     */
    public function filterByApplicableToWork($applicableToWork = null, $comparison = null)
    {
        if (is_string($applicableToWork)) {
            $applicableToWork = in_array(strtolower($applicableToWork), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PersonrolePeer::APPLICABLE_TO_WORK, $applicableToWork, $comparison);
    }

    /**
     * Filter the query by a related PersonPublication object
     *
     * @param   PersonPublication|PropelObjectCollection $personPublication  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PersonroleQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPersonPublication($personPublication, $comparison = null)
    {
        if ($personPublication instanceof PersonPublication) {
            return $this
                ->addUsingAlias(PersonrolePeer::ID, $personPublication->getPersonroleId(), $comparison);
        } elseif ($personPublication instanceof PropelObjectCollection) {
            return $this
                ->usePersonPublicationQuery()
                ->filterByPrimaryKeys($personPublication->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPersonPublication() only accepts arguments of type PersonPublication or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PersonPublication relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PersonroleQuery The current query, for fluid interface
     */
    public function joinPersonPublication($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PersonPublication');

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
            $this->addJoinObject($join, 'PersonPublication');
        }

        return $this;
    }

    /**
     * Use the PersonPublication relation PersonPublication object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\PersonPublicationQuery A secondary query class using the current class as primary query
     */
    public function usePersonPublicationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPersonPublication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PersonPublication', '\DTA\MetadataBundle\Model\Master\PersonPublicationQuery');
    }

    /**
     * Filter the query by a related PersonWork object
     *
     * @param   PersonWork|PropelObjectCollection $personWork  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PersonroleQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPersonWork($personWork, $comparison = null)
    {
        if ($personWork instanceof PersonWork) {
            return $this
                ->addUsingAlias(PersonrolePeer::ID, $personWork->getPersonroleId(), $comparison);
        } elseif ($personWork instanceof PropelObjectCollection) {
            return $this
                ->usePersonWorkQuery()
                ->filterByPrimaryKeys($personWork->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPersonWork() only accepts arguments of type PersonWork or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PersonWork relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PersonroleQuery The current query, for fluid interface
     */
    public function joinPersonWork($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PersonWork');

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
            $this->addJoinObject($join, 'PersonWork');
        }

        return $this;
    }

    /**
     * Use the PersonWork relation PersonWork object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\PersonWorkQuery A secondary query class using the current class as primary query
     */
    public function usePersonWorkQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPersonWork($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PersonWork', '\DTA\MetadataBundle\Model\Master\PersonWorkQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Personrole $personrole Object to remove from the list of results
     *
     * @return PersonroleQuery The current query, for fluid interface
     */
    public function prune($personrole = null)
    {
        if ($personrole) {
            $this->addUsingAlias(PersonrolePeer::ID, $personrole->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
