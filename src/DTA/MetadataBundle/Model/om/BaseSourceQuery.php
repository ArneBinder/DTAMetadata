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
use DTA\MetadataBundle\Model\Source;
use DTA\MetadataBundle\Model\SourcePeer;
use DTA\MetadataBundle\Model\SourceQuery;
use DTA\MetadataBundle\Model\Writ;

/**
 * @method SourceQuery orderById($order = Criteria::ASC) Order by the id column
 * @method SourceQuery orderByWritId($order = Criteria::ASC) Order by the writ_id column
 * @method SourceQuery orderByQuality($order = Criteria::ASC) Order by the quality column
 * @method SourceQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method SourceQuery orderByComments($order = Criteria::ASC) Order by the comments column
 * @method SourceQuery orderByAvailable($order = Criteria::ASC) Order by the available column
 * @method SourceQuery orderBySignatur($order = Criteria::ASC) Order by the signatur column
 * @method SourceQuery orderByLibrary($order = Criteria::ASC) Order by the library column
 * @method SourceQuery orderByLibrarygnd($order = Criteria::ASC) Order by the libraryGnd column
 *
 * @method SourceQuery groupById() Group by the id column
 * @method SourceQuery groupByWritId() Group by the writ_id column
 * @method SourceQuery groupByQuality() Group by the quality column
 * @method SourceQuery groupByName() Group by the name column
 * @method SourceQuery groupByComments() Group by the comments column
 * @method SourceQuery groupByAvailable() Group by the available column
 * @method SourceQuery groupBySignatur() Group by the signatur column
 * @method SourceQuery groupByLibrary() Group by the library column
 * @method SourceQuery groupByLibrarygnd() Group by the libraryGnd column
 *
 * @method SourceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method SourceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method SourceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method SourceQuery leftJoinWrit($relationAlias = null) Adds a LEFT JOIN clause to the query using the Writ relation
 * @method SourceQuery rightJoinWrit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Writ relation
 * @method SourceQuery innerJoinWrit($relationAlias = null) Adds a INNER JOIN clause to the query using the Writ relation
 *
 * @method Source findOne(PropelPDO $con = null) Return the first Source matching the query
 * @method Source findOneOrCreate(PropelPDO $con = null) Return the first Source matching the query, or a new Source object populated from the query conditions when no match is found
 *
 * @method Source findOneByWritId(int $writ_id) Return the first Source filtered by the writ_id column
 * @method Source findOneByQuality(string $quality) Return the first Source filtered by the quality column
 * @method Source findOneByName(string $name) Return the first Source filtered by the name column
 * @method Source findOneByComments(string $comments) Return the first Source filtered by the comments column
 * @method Source findOneByAvailable(boolean $available) Return the first Source filtered by the available column
 * @method Source findOneBySignatur(string $signatur) Return the first Source filtered by the signatur column
 * @method Source findOneByLibrary(string $library) Return the first Source filtered by the library column
 * @method Source findOneByLibrarygnd(string $libraryGnd) Return the first Source filtered by the libraryGnd column
 *
 * @method array findById(int $id) Return Source objects filtered by the id column
 * @method array findByWritId(int $writ_id) Return Source objects filtered by the writ_id column
 * @method array findByQuality(string $quality) Return Source objects filtered by the quality column
 * @method array findByName(string $name) Return Source objects filtered by the name column
 * @method array findByComments(string $comments) Return Source objects filtered by the comments column
 * @method array findByAvailable(boolean $available) Return Source objects filtered by the available column
 * @method array findBySignatur(string $signatur) Return Source objects filtered by the signatur column
 * @method array findByLibrary(string $library) Return Source objects filtered by the library column
 * @method array findByLibrarygnd(string $libraryGnd) Return Source objects filtered by the libraryGnd column
 */
abstract class BaseSourceQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseSourceQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Source', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new SourceQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     SourceQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return SourceQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof SourceQuery) {
            return $criteria;
        }
        $query = new SourceQuery();
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
     * @return   Source|Source[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SourcePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(SourcePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Source A model object, or null if the key is not found
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
     * @return   Source A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `writ_id`, `quality`, `name`, `comments`, `available`, `signatur`, `library`, `libraryGnd` FROM `source` WHERE `id` = :p0';
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
            $obj = new Source();
            $obj->hydrate($row);
            SourcePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Source|Source[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Source[]|mixed the list of results, formatted by the current formatter
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
     * @return SourceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SourcePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return SourceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SourcePeer::ID, $keys, Criteria::IN);
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
     * @return SourceQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(SourcePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the writ_id column
     *
     * Example usage:
     * <code>
     * $query->filterByWritId(1234); // WHERE writ_id = 1234
     * $query->filterByWritId(array(12, 34)); // WHERE writ_id IN (12, 34)
     * $query->filterByWritId(array('min' => 12)); // WHERE writ_id > 12
     * </code>
     *
     * @see       filterByWrit()
     *
     * @param     mixed $writId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SourceQuery The current query, for fluid interface
     */
    public function filterByWritId($writId = null, $comparison = null)
    {
        if (is_array($writId)) {
            $useMinMax = false;
            if (isset($writId['min'])) {
                $this->addUsingAlias(SourcePeer::WRIT_ID, $writId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($writId['max'])) {
                $this->addUsingAlias(SourcePeer::WRIT_ID, $writId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SourcePeer::WRIT_ID, $writId, $comparison);
    }

    /**
     * Filter the query on the quality column
     *
     * Example usage:
     * <code>
     * $query->filterByQuality('fooValue');   // WHERE quality = 'fooValue'
     * $query->filterByQuality('%fooValue%'); // WHERE quality LIKE '%fooValue%'
     * </code>
     *
     * @param     string $quality The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SourceQuery The current query, for fluid interface
     */
    public function filterByQuality($quality = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($quality)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $quality)) {
                $quality = str_replace('*', '%', $quality);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SourcePeer::QUALITY, $quality, $comparison);
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
     * @return SourceQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SourcePeer::NAME, $name, $comparison);
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
     * @return SourceQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SourcePeer::COMMENTS, $comments, $comparison);
    }

    /**
     * Filter the query on the available column
     *
     * Example usage:
     * <code>
     * $query->filterByAvailable(true); // WHERE available = true
     * $query->filterByAvailable('yes'); // WHERE available = true
     * </code>
     *
     * @param     boolean|string $available The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SourceQuery The current query, for fluid interface
     */
    public function filterByAvailable($available = null, $comparison = null)
    {
        if (is_string($available)) {
            $available = in_array(strtolower($available), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SourcePeer::AVAILABLE, $available, $comparison);
    }

    /**
     * Filter the query on the signatur column
     *
     * Example usage:
     * <code>
     * $query->filterBySignatur('fooValue');   // WHERE signatur = 'fooValue'
     * $query->filterBySignatur('%fooValue%'); // WHERE signatur LIKE '%fooValue%'
     * </code>
     *
     * @param     string $signatur The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SourceQuery The current query, for fluid interface
     */
    public function filterBySignatur($signatur = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($signatur)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $signatur)) {
                $signatur = str_replace('*', '%', $signatur);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SourcePeer::SIGNATUR, $signatur, $comparison);
    }

    /**
     * Filter the query on the library column
     *
     * Example usage:
     * <code>
     * $query->filterByLibrary('fooValue');   // WHERE library = 'fooValue'
     * $query->filterByLibrary('%fooValue%'); // WHERE library LIKE '%fooValue%'
     * </code>
     *
     * @param     string $library The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SourceQuery The current query, for fluid interface
     */
    public function filterByLibrary($library = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($library)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $library)) {
                $library = str_replace('*', '%', $library);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SourcePeer::LIBRARY, $library, $comparison);
    }

    /**
     * Filter the query on the libraryGnd column
     *
     * Example usage:
     * <code>
     * $query->filterByLibrarygnd('fooValue');   // WHERE libraryGnd = 'fooValue'
     * $query->filterByLibrarygnd('%fooValue%'); // WHERE libraryGnd LIKE '%fooValue%'
     * </code>
     *
     * @param     string $librarygnd The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SourceQuery The current query, for fluid interface
     */
    public function filterByLibrarygnd($librarygnd = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($librarygnd)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $librarygnd)) {
                $librarygnd = str_replace('*', '%', $librarygnd);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SourcePeer::LIBRARYGND, $librarygnd, $comparison);
    }

    /**
     * Filter the query by a related Writ object
     *
     * @param   Writ|PropelObjectCollection $writ The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   SourceQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByWrit($writ, $comparison = null)
    {
        if ($writ instanceof Writ) {
            return $this
                ->addUsingAlias(SourcePeer::WRIT_ID, $writ->getId(), $comparison);
        } elseif ($writ instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SourcePeer::WRIT_ID, $writ->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByWrit() only accepts arguments of type Writ or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Writ relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SourceQuery The current query, for fluid interface
     */
    public function joinWrit($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Writ');

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
            $this->addJoinObject($join, 'Writ');
        }

        return $this;
    }

    /**
     * Use the Writ relation Writ object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\WritQuery A secondary query class using the current class as primary query
     */
    public function useWritQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWrit($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Writ', '\DTA\MetadataBundle\Model\WritQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Source $source Object to remove from the list of results
     *
     * @return SourceQuery The current query, for fluid interface
     */
    public function prune($source = null)
    {
        if ($source) {
            $this->addUsingAlias(SourcePeer::ID, $source->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
