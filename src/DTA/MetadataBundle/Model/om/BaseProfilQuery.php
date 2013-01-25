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
use DTA\MetadataBundle\Model\Konto;
use DTA\MetadataBundle\Model\Profil;
use DTA\MetadataBundle\Model\ProfilPeer;
use DTA\MetadataBundle\Model\ProfilQuery;

/**
 * @method ProfilQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method ProfilQuery orderByTelephone($order = Criteria::ASC) Order by the telephone column
 * @method ProfilQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method ProfilQuery groupByEmail() Group by the email column
 * @method ProfilQuery groupByTelephone() Group by the telephone column
 * @method ProfilQuery groupById() Group by the id column
 *
 * @method ProfilQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ProfilQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ProfilQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ProfilQuery leftJoinKonto($relationAlias = null) Adds a LEFT JOIN clause to the query using the Konto relation
 * @method ProfilQuery rightJoinKonto($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Konto relation
 * @method ProfilQuery innerJoinKonto($relationAlias = null) Adds a INNER JOIN clause to the query using the Konto relation
 *
 * @method Profil findOne(PropelPDO $con = null) Return the first Profil matching the query
 * @method Profil findOneOrCreate(PropelPDO $con = null) Return the first Profil matching the query, or a new Profil object populated from the query conditions when no match is found
 *
 * @method Profil findOneByEmail(string $email) Return the first Profil filtered by the email column
 * @method Profil findOneByTelephone(string $telephone) Return the first Profil filtered by the telephone column
 *
 * @method array findByEmail(string $email) Return Profil objects filtered by the email column
 * @method array findByTelephone(string $telephone) Return Profil objects filtered by the telephone column
 * @method array findById(int $id) Return Profil objects filtered by the id column
 */
abstract class BaseProfilQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseProfilQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Profil', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ProfilQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ProfilQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ProfilQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ProfilQuery) {
            return $criteria;
        }
        $query = new ProfilQuery();
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
     * @return   Profil|Profil[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ProfilPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ProfilPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Profil A model object, or null if the key is not found
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
     * @return                 Profil A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `email`, `telephone`, `id` FROM `profil` WHERE `id` = :p0';
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
            $obj = new Profil();
            $obj->hydrate($row);
            ProfilPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Profil|Profil[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Profil[]|mixed the list of results, formatted by the current formatter
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
     * @return ProfilQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ProfilPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ProfilQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ProfilPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProfilQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ProfilPeer::EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the telephone column
     *
     * Example usage:
     * <code>
     * $query->filterByTelephone('fooValue');   // WHERE telephone = 'fooValue'
     * $query->filterByTelephone('%fooValue%'); // WHERE telephone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $telephone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProfilQuery The current query, for fluid interface
     */
    public function filterByTelephone($telephone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($telephone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $telephone)) {
                $telephone = str_replace('*', '%', $telephone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ProfilPeer::TELEPHONE, $telephone, $comparison);
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
     * @see       filterByKonto()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ProfilQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ProfilPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ProfilPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProfilPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query by a related Konto object
     *
     * @param   Konto|PropelObjectCollection $konto The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ProfilQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByKonto($konto, $comparison = null)
    {
        if ($konto instanceof Konto) {
            return $this
                ->addUsingAlias(ProfilPeer::ID, $konto->getId(), $comparison);
        } elseif ($konto instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ProfilPeer::ID, $konto->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByKonto() only accepts arguments of type Konto or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Konto relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ProfilQuery The current query, for fluid interface
     */
    public function joinKonto($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Konto');

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
            $this->addJoinObject($join, 'Konto');
        }

        return $this;
    }

    /**
     * Use the Konto relation Konto object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\KontoQuery A secondary query class using the current class as primary query
     */
    public function useKontoQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinKonto($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Konto', '\DTA\MetadataBundle\Model\KontoQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Profil $profil Object to remove from the list of results
     *
     * @return ProfilQuery The current query, for fluid interface
     */
    public function prune($profil = null)
    {
        if ($profil) {
            $this->addUsingAlias(ProfilPeer::ID, $profil->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
