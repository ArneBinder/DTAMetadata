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
use DTA\MetadataBundle\Model\Monograph;
use DTA\MetadataBundle\Model\Volume;
use DTA\MetadataBundle\Model\VolumePeer;
use DTA\MetadataBundle\Model\VolumeQuery;

/**
 * @method VolumeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method VolumeQuery orderByVolumeindex($order = Criteria::ASC) Order by the volumeIndex column
 * @method VolumeQuery orderByVolumeindexnumerical($order = Criteria::ASC) Order by the volumeIndexNumerical column
 * @method VolumeQuery orderByTotalvolumes($order = Criteria::ASC) Order by the totalVolumes column
 * @method VolumeQuery orderByMonographId($order = Criteria::ASC) Order by the monograph_id column
 * @method VolumeQuery orderByMonographPublicationId($order = Criteria::ASC) Order by the monograph_publication_id column
 *
 * @method VolumeQuery groupById() Group by the id column
 * @method VolumeQuery groupByVolumeindex() Group by the volumeIndex column
 * @method VolumeQuery groupByVolumeindexnumerical() Group by the volumeIndexNumerical column
 * @method VolumeQuery groupByTotalvolumes() Group by the totalVolumes column
 * @method VolumeQuery groupByMonographId() Group by the monograph_id column
 * @method VolumeQuery groupByMonographPublicationId() Group by the monograph_publication_id column
 *
 * @method VolumeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method VolumeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method VolumeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method VolumeQuery leftJoinMonograph($relationAlias = null) Adds a LEFT JOIN clause to the query using the Monograph relation
 * @method VolumeQuery rightJoinMonograph($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Monograph relation
 * @method VolumeQuery innerJoinMonograph($relationAlias = null) Adds a INNER JOIN clause to the query using the Monograph relation
 *
 * @method Volume findOne(PropelPDO $con = null) Return the first Volume matching the query
 * @method Volume findOneOrCreate(PropelPDO $con = null) Return the first Volume matching the query, or a new Volume object populated from the query conditions when no match is found
 *
 * @method Volume findOneByVolumeindex(string $volumeIndex) Return the first Volume filtered by the volumeIndex column
 * @method Volume findOneByVolumeindexnumerical(int $volumeIndexNumerical) Return the first Volume filtered by the volumeIndexNumerical column
 * @method Volume findOneByTotalvolumes(int $totalVolumes) Return the first Volume filtered by the totalVolumes column
 * @method Volume findOneByMonographId(int $monograph_id) Return the first Volume filtered by the monograph_id column
 * @method Volume findOneByMonographPublicationId(int $monograph_publication_id) Return the first Volume filtered by the monograph_publication_id column
 *
 * @method array findById(int $id) Return Volume objects filtered by the id column
 * @method array findByVolumeindex(string $volumeIndex) Return Volume objects filtered by the volumeIndex column
 * @method array findByVolumeindexnumerical(int $volumeIndexNumerical) Return Volume objects filtered by the volumeIndexNumerical column
 * @method array findByTotalvolumes(int $totalVolumes) Return Volume objects filtered by the totalVolumes column
 * @method array findByMonographId(int $monograph_id) Return Volume objects filtered by the monograph_id column
 * @method array findByMonographPublicationId(int $monograph_publication_id) Return Volume objects filtered by the monograph_publication_id column
 */
abstract class BaseVolumeQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseVolumeQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Volume', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new VolumeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   VolumeQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return VolumeQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof VolumeQuery) {
            return $criteria;
        }
        $query = new VolumeQuery();
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
     * @return   Volume|Volume[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = VolumePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(VolumePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Volume A model object, or null if the key is not found
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
     * @return                 Volume A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `volumeIndex`, `volumeIndexNumerical`, `totalVolumes`, `monograph_id`, `monograph_publication_id` FROM `volume` WHERE `id` = :p0';
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
            $obj = new Volume();
            $obj->hydrate($row);
            VolumePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Volume|Volume[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Volume[]|mixed the list of results, formatted by the current formatter
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
     * @return VolumeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(VolumePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(VolumePeer::ID, $keys, Criteria::IN);
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
     * @return VolumeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(VolumePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(VolumePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VolumePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the volumeIndex column
     *
     * Example usage:
     * <code>
     * $query->filterByVolumeindex('fooValue');   // WHERE volumeIndex = 'fooValue'
     * $query->filterByVolumeindex('%fooValue%'); // WHERE volumeIndex LIKE '%fooValue%'
     * </code>
     *
     * @param     string $volumeindex The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function filterByVolumeindex($volumeindex = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($volumeindex)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $volumeindex)) {
                $volumeindex = str_replace('*', '%', $volumeindex);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(VolumePeer::VOLUMEINDEX, $volumeindex, $comparison);
    }

    /**
     * Filter the query on the volumeIndexNumerical column
     *
     * Example usage:
     * <code>
     * $query->filterByVolumeindexnumerical(1234); // WHERE volumeIndexNumerical = 1234
     * $query->filterByVolumeindexnumerical(array(12, 34)); // WHERE volumeIndexNumerical IN (12, 34)
     * $query->filterByVolumeindexnumerical(array('min' => 12)); // WHERE volumeIndexNumerical >= 12
     * $query->filterByVolumeindexnumerical(array('max' => 12)); // WHERE volumeIndexNumerical <= 12
     * </code>
     *
     * @param     mixed $volumeindexnumerical The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function filterByVolumeindexnumerical($volumeindexnumerical = null, $comparison = null)
    {
        if (is_array($volumeindexnumerical)) {
            $useMinMax = false;
            if (isset($volumeindexnumerical['min'])) {
                $this->addUsingAlias(VolumePeer::VOLUMEINDEXNUMERICAL, $volumeindexnumerical['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($volumeindexnumerical['max'])) {
                $this->addUsingAlias(VolumePeer::VOLUMEINDEXNUMERICAL, $volumeindexnumerical['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VolumePeer::VOLUMEINDEXNUMERICAL, $volumeindexnumerical, $comparison);
    }

    /**
     * Filter the query on the totalVolumes column
     *
     * Example usage:
     * <code>
     * $query->filterByTotalvolumes(1234); // WHERE totalVolumes = 1234
     * $query->filterByTotalvolumes(array(12, 34)); // WHERE totalVolumes IN (12, 34)
     * $query->filterByTotalvolumes(array('min' => 12)); // WHERE totalVolumes >= 12
     * $query->filterByTotalvolumes(array('max' => 12)); // WHERE totalVolumes <= 12
     * </code>
     *
     * @param     mixed $totalvolumes The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function filterByTotalvolumes($totalvolumes = null, $comparison = null)
    {
        if (is_array($totalvolumes)) {
            $useMinMax = false;
            if (isset($totalvolumes['min'])) {
                $this->addUsingAlias(VolumePeer::TOTALVOLUMES, $totalvolumes['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($totalvolumes['max'])) {
                $this->addUsingAlias(VolumePeer::TOTALVOLUMES, $totalvolumes['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VolumePeer::TOTALVOLUMES, $totalvolumes, $comparison);
    }

    /**
     * Filter the query on the monograph_id column
     *
     * Example usage:
     * <code>
     * $query->filterByMonographId(1234); // WHERE monograph_id = 1234
     * $query->filterByMonographId(array(12, 34)); // WHERE monograph_id IN (12, 34)
     * $query->filterByMonographId(array('min' => 12)); // WHERE monograph_id >= 12
     * $query->filterByMonographId(array('max' => 12)); // WHERE monograph_id <= 12
     * </code>
     *
     * @see       filterByMonograph()
     *
     * @param     mixed $monographId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function filterByMonographId($monographId = null, $comparison = null)
    {
        if (is_array($monographId)) {
            $useMinMax = false;
            if (isset($monographId['min'])) {
                $this->addUsingAlias(VolumePeer::MONOGRAPH_ID, $monographId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($monographId['max'])) {
                $this->addUsingAlias(VolumePeer::MONOGRAPH_ID, $monographId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VolumePeer::MONOGRAPH_ID, $monographId, $comparison);
    }

    /**
     * Filter the query on the monograph_publication_id column
     *
     * Example usage:
     * <code>
     * $query->filterByMonographPublicationId(1234); // WHERE monograph_publication_id = 1234
     * $query->filterByMonographPublicationId(array(12, 34)); // WHERE monograph_publication_id IN (12, 34)
     * $query->filterByMonographPublicationId(array('min' => 12)); // WHERE monograph_publication_id >= 12
     * $query->filterByMonographPublicationId(array('max' => 12)); // WHERE monograph_publication_id <= 12
     * </code>
     *
     * @param     mixed $monographPublicationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function filterByMonographPublicationId($monographPublicationId = null, $comparison = null)
    {
        if (is_array($monographPublicationId)) {
            $useMinMax = false;
            if (isset($monographPublicationId['min'])) {
                $this->addUsingAlias(VolumePeer::MONOGRAPH_PUBLICATION_ID, $monographPublicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($monographPublicationId['max'])) {
                $this->addUsingAlias(VolumePeer::MONOGRAPH_PUBLICATION_ID, $monographPublicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VolumePeer::MONOGRAPH_PUBLICATION_ID, $monographPublicationId, $comparison);
    }

    /**
     * Filter the query by a related Monograph object
     *
     * @param   Monograph|PropelObjectCollection $monograph The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 VolumeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMonograph($monograph, $comparison = null)
    {
        if ($monograph instanceof Monograph) {
            return $this
                ->addUsingAlias(VolumePeer::MONOGRAPH_ID, $monograph->getId(), $comparison);
        } elseif ($monograph instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(VolumePeer::MONOGRAPH_ID, $monograph->toKeyValue('Id', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByMonograph() only accepts arguments of type Monograph or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Monograph relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function joinMonograph($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Monograph');

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
            $this->addJoinObject($join, 'Monograph');
        }

        return $this;
    }

    /**
     * Use the Monograph relation Monograph object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\MonographQuery A secondary query class using the current class as primary query
     */
    public function useMonographQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMonograph($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Monograph', '\DTA\MetadataBundle\Model\MonographQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Volume $volume Object to remove from the list of results
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function prune($volume = null)
    {
        if ($volume) {
            $this->addUsingAlias(VolumePeer::ID, $volume->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
