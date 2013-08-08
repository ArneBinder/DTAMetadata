<?php

namespace DTA\MetadataBundle\Model\Data\om;

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
use DTA\MetadataBundle\Model\Data\PublicationDs;
use DTA\MetadataBundle\Model\Data\PublicationJa;
use DTA\MetadataBundle\Model\Data\PublicationMm;
use DTA\MetadataBundle\Model\Data\PublicationMms;
use DTA\MetadataBundle\Model\Data\Volume;
use DTA\MetadataBundle\Model\Data\VolumePeer;
use DTA\MetadataBundle\Model\Data\VolumeQuery;

/**
 * @method VolumeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method VolumeQuery orderByVolumedescription($order = Criteria::ASC) Order by the volumedescription column
 * @method VolumeQuery orderByVolumenumeric($order = Criteria::ASC) Order by the volumenumeric column
 *
 * @method VolumeQuery groupById() Group by the id column
 * @method VolumeQuery groupByVolumedescription() Group by the volumedescription column
 * @method VolumeQuery groupByVolumenumeric() Group by the volumenumeric column
 *
 * @method VolumeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method VolumeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method VolumeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method VolumeQuery leftJoinPublicationMm($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationMm relation
 * @method VolumeQuery rightJoinPublicationMm($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationMm relation
 * @method VolumeQuery innerJoinPublicationMm($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationMm relation
 *
 * @method VolumeQuery leftJoinPublicationDs($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationDs relation
 * @method VolumeQuery rightJoinPublicationDs($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationDs relation
 * @method VolumeQuery innerJoinPublicationDs($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationDs relation
 *
 * @method VolumeQuery leftJoinPublicationJa($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationJa relation
 * @method VolumeQuery rightJoinPublicationJa($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationJa relation
 * @method VolumeQuery innerJoinPublicationJa($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationJa relation
 *
 * @method VolumeQuery leftJoinPublicationMms($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationMms relation
 * @method VolumeQuery rightJoinPublicationMms($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationMms relation
 * @method VolumeQuery innerJoinPublicationMms($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationMms relation
 *
 * @method Volume findOne(PropelPDO $con = null) Return the first Volume matching the query
 * @method Volume findOneOrCreate(PropelPDO $con = null) Return the first Volume matching the query, or a new Volume object populated from the query conditions when no match is found
 *
 * @method Volume findOneByVolumedescription(int $volumedescription) Return the first Volume filtered by the volumedescription column
 * @method Volume findOneByVolumenumeric(string $volumenumeric) Return the first Volume filtered by the volumenumeric column
 *
 * @method array findById(int $id) Return Volume objects filtered by the id column
 * @method array findByVolumedescription(int $volumedescription) Return Volume objects filtered by the volumedescription column
 * @method array findByVolumenumeric(string $volumenumeric) Return Volume objects filtered by the volumenumeric column
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
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Data\\Volume', $modelAlias = null)
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
        $sql = 'SELECT "id", "volumedescription", "volumenumeric" FROM "volume" WHERE "id" = :p0';
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
     * Filter the query on the volumedescription column
     *
     * Example usage:
     * <code>
     * $query->filterByVolumedescription(1234); // WHERE volumedescription = 1234
     * $query->filterByVolumedescription(array(12, 34)); // WHERE volumedescription IN (12, 34)
     * $query->filterByVolumedescription(array('min' => 12)); // WHERE volumedescription >= 12
     * $query->filterByVolumedescription(array('max' => 12)); // WHERE volumedescription <= 12
     * </code>
     *
     * @param     mixed $volumedescription The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function filterByVolumedescription($volumedescription = null, $comparison = null)
    {
        if (is_array($volumedescription)) {
            $useMinMax = false;
            if (isset($volumedescription['min'])) {
                $this->addUsingAlias(VolumePeer::VOLUMEDESCRIPTION, $volumedescription['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($volumedescription['max'])) {
                $this->addUsingAlias(VolumePeer::VOLUMEDESCRIPTION, $volumedescription['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VolumePeer::VOLUMEDESCRIPTION, $volumedescription, $comparison);
    }

    /**
     * Filter the query on the volumenumeric column
     *
     * Example usage:
     * <code>
     * $query->filterByVolumenumeric('fooValue');   // WHERE volumenumeric = 'fooValue'
     * $query->filterByVolumenumeric('%fooValue%'); // WHERE volumenumeric LIKE '%fooValue%'
     * </code>
     *
     * @param     string $volumenumeric The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function filterByVolumenumeric($volumenumeric = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($volumenumeric)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $volumenumeric)) {
                $volumenumeric = str_replace('*', '%', $volumenumeric);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(VolumePeer::VOLUMENUMERIC, $volumenumeric, $comparison);
    }

    /**
     * Filter the query by a related PublicationMm object
     *
     * @param   PublicationMm|PropelObjectCollection $publicationMm  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 VolumeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationMm($publicationMm, $comparison = null)
    {
        if ($publicationMm instanceof PublicationMm) {
            return $this
                ->addUsingAlias(VolumePeer::ID, $publicationMm->getVolumeId(), $comparison);
        } elseif ($publicationMm instanceof PropelObjectCollection) {
            return $this
                ->usePublicationMmQuery()
                ->filterByPrimaryKeys($publicationMm->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationMm() only accepts arguments of type PublicationMm or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationMm relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function joinPublicationMm($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationMm');

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
            $this->addJoinObject($join, 'PublicationMm');
        }

        return $this;
    }

    /**
     * Use the PublicationMm relation PublicationMm object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationMmQuery A secondary query class using the current class as primary query
     */
    public function usePublicationMmQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationMm($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationMm', '\DTA\MetadataBundle\Model\Data\PublicationMmQuery');
    }

    /**
     * Filter the query by a related PublicationDs object
     *
     * @param   PublicationDs|PropelObjectCollection $publicationDs  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 VolumeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationDs($publicationDs, $comparison = null)
    {
        if ($publicationDs instanceof PublicationDs) {
            return $this
                ->addUsingAlias(VolumePeer::ID, $publicationDs->getVolumeId(), $comparison);
        } elseif ($publicationDs instanceof PropelObjectCollection) {
            return $this
                ->usePublicationDsQuery()
                ->filterByPrimaryKeys($publicationDs->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationDs() only accepts arguments of type PublicationDs or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationDs relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function joinPublicationDs($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationDs');

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
            $this->addJoinObject($join, 'PublicationDs');
        }

        return $this;
    }

    /**
     * Use the PublicationDs relation PublicationDs object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationDsQuery A secondary query class using the current class as primary query
     */
    public function usePublicationDsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationDs($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationDs', '\DTA\MetadataBundle\Model\Data\PublicationDsQuery');
    }

    /**
     * Filter the query by a related PublicationJa object
     *
     * @param   PublicationJa|PropelObjectCollection $publicationJa  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 VolumeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationJa($publicationJa, $comparison = null)
    {
        if ($publicationJa instanceof PublicationJa) {
            return $this
                ->addUsingAlias(VolumePeer::ID, $publicationJa->getVolumeId(), $comparison);
        } elseif ($publicationJa instanceof PropelObjectCollection) {
            return $this
                ->usePublicationJaQuery()
                ->filterByPrimaryKeys($publicationJa->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationJa() only accepts arguments of type PublicationJa or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationJa relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function joinPublicationJa($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationJa');

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
            $this->addJoinObject($join, 'PublicationJa');
        }

        return $this;
    }

    /**
     * Use the PublicationJa relation PublicationJa object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationJaQuery A secondary query class using the current class as primary query
     */
    public function usePublicationJaQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationJa($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationJa', '\DTA\MetadataBundle\Model\Data\PublicationJaQuery');
    }

    /**
     * Filter the query by a related PublicationMms object
     *
     * @param   PublicationMms|PropelObjectCollection $publicationMms  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 VolumeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationMms($publicationMms, $comparison = null)
    {
        if ($publicationMms instanceof PublicationMms) {
            return $this
                ->addUsingAlias(VolumePeer::ID, $publicationMms->getVolumeId(), $comparison);
        } elseif ($publicationMms instanceof PropelObjectCollection) {
            return $this
                ->usePublicationMmsQuery()
                ->filterByPrimaryKeys($publicationMms->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationMms() only accepts arguments of type PublicationMms or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationMms relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function joinPublicationMms($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationMms');

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
            $this->addJoinObject($join, 'PublicationMms');
        }

        return $this;
    }

    /**
     * Use the PublicationMms relation PublicationMms object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationMmsQuery A secondary query class using the current class as primary query
     */
    public function usePublicationMmsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationMms($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationMms', '\DTA\MetadataBundle\Model\Data\PublicationMmsQuery');
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
