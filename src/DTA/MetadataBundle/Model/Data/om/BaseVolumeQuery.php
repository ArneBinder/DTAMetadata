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
use DTA\MetadataBundle\Model\Data\Publication;
use DTA\MetadataBundle\Model\Data\Volume;
use DTA\MetadataBundle\Model\Data\VolumePeer;
use DTA\MetadataBundle\Model\Data\VolumeQuery;

/**
 * @method VolumeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method VolumeQuery orderByPublicationId($order = Criteria::ASC) Order by the publication_id column
 * @method VolumeQuery orderByParentpublicationId($order = Criteria::ASC) Order by the parentpublication_id column
 * @method VolumeQuery orderByVolumedescription($order = Criteria::ASC) Order by the volumedescription column
 * @method VolumeQuery orderByVolumenumeric($order = Criteria::ASC) Order by the volumenumeric column
 * @method VolumeQuery orderByVolumestotal($order = Criteria::ASC) Order by the volumestotal column
 *
 * @method VolumeQuery groupById() Group by the id column
 * @method VolumeQuery groupByPublicationId() Group by the publication_id column
 * @method VolumeQuery groupByParentpublicationId() Group by the parentpublication_id column
 * @method VolumeQuery groupByVolumedescription() Group by the volumedescription column
 * @method VolumeQuery groupByVolumenumeric() Group by the volumenumeric column
 * @method VolumeQuery groupByVolumestotal() Group by the volumestotal column
 *
 * @method VolumeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method VolumeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method VolumeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method VolumeQuery leftJoinPublicationRelatedByPublicationId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationRelatedByPublicationId relation
 * @method VolumeQuery rightJoinPublicationRelatedByPublicationId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationRelatedByPublicationId relation
 * @method VolumeQuery innerJoinPublicationRelatedByPublicationId($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationRelatedByPublicationId relation
 *
 * @method VolumeQuery leftJoinPublicationRelatedByParentpublicationId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationRelatedByParentpublicationId relation
 * @method VolumeQuery rightJoinPublicationRelatedByParentpublicationId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationRelatedByParentpublicationId relation
 * @method VolumeQuery innerJoinPublicationRelatedByParentpublicationId($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationRelatedByParentpublicationId relation
 *
 * @method Volume findOne(PropelPDO $con = null) Return the first Volume matching the query
 * @method Volume findOneOrCreate(PropelPDO $con = null) Return the first Volume matching the query, or a new Volume object populated from the query conditions when no match is found
 *
 * @method Volume findOneByPublicationId(int $publication_id) Return the first Volume filtered by the publication_id column
 * @method Volume findOneByParentpublicationId(int $parentpublication_id) Return the first Volume filtered by the parentpublication_id column
 * @method Volume findOneByVolumedescription(int $volumedescription) Return the first Volume filtered by the volumedescription column
 * @method Volume findOneByVolumenumeric(int $volumenumeric) Return the first Volume filtered by the volumenumeric column
 * @method Volume findOneByVolumestotal(int $volumestotal) Return the first Volume filtered by the volumestotal column
 *
 * @method array findById(int $id) Return Volume objects filtered by the id column
 * @method array findByPublicationId(int $publication_id) Return Volume objects filtered by the publication_id column
 * @method array findByParentpublicationId(int $parentpublication_id) Return Volume objects filtered by the parentpublication_id column
 * @method array findByVolumedescription(int $volumedescription) Return Volume objects filtered by the volumedescription column
 * @method array findByVolumenumeric(int $volumenumeric) Return Volume objects filtered by the volumenumeric column
 * @method array findByVolumestotal(int $volumestotal) Return Volume objects filtered by the volumestotal column
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
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'dtametadata';
        }
        if (null === $modelName) {
            $modelName = 'DTA\\MetadataBundle\\Model\\Data\\Volume';
        }
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
        $query = new VolumeQuery(null, null, $modelAlias);

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
            // the object is already in the instance pool
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
        $sql = 'SELECT "id", "publication_id", "parentpublication_id", "volumedescription", "volumenumeric", "volumestotal" FROM "volume" WHERE "id" = :p0';
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
     * Filter the query on the publication_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublicationId(1234); // WHERE publication_id = 1234
     * $query->filterByPublicationId(array(12, 34)); // WHERE publication_id IN (12, 34)
     * $query->filterByPublicationId(array('min' => 12)); // WHERE publication_id >= 12
     * $query->filterByPublicationId(array('max' => 12)); // WHERE publication_id <= 12
     * </code>
     *
     * @see       filterByPublicationRelatedByPublicationId()
     *
     * @param     mixed $publicationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function filterByPublicationId($publicationId = null, $comparison = null)
    {
        if (is_array($publicationId)) {
            $useMinMax = false;
            if (isset($publicationId['min'])) {
                $this->addUsingAlias(VolumePeer::PUBLICATION_ID, $publicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publicationId['max'])) {
                $this->addUsingAlias(VolumePeer::PUBLICATION_ID, $publicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VolumePeer::PUBLICATION_ID, $publicationId, $comparison);
    }

    /**
     * Filter the query on the parentpublication_id column
     *
     * Example usage:
     * <code>
     * $query->filterByParentpublicationId(1234); // WHERE parentpublication_id = 1234
     * $query->filterByParentpublicationId(array(12, 34)); // WHERE parentpublication_id IN (12, 34)
     * $query->filterByParentpublicationId(array('min' => 12)); // WHERE parentpublication_id >= 12
     * $query->filterByParentpublicationId(array('max' => 12)); // WHERE parentpublication_id <= 12
     * </code>
     *
     * @see       filterByPublicationRelatedByParentpublicationId()
     *
     * @param     mixed $parentpublicationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function filterByParentpublicationId($parentpublicationId = null, $comparison = null)
    {
        if (is_array($parentpublicationId)) {
            $useMinMax = false;
            if (isset($parentpublicationId['min'])) {
                $this->addUsingAlias(VolumePeer::PARENTPUBLICATION_ID, $parentpublicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($parentpublicationId['max'])) {
                $this->addUsingAlias(VolumePeer::PARENTPUBLICATION_ID, $parentpublicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VolumePeer::PARENTPUBLICATION_ID, $parentpublicationId, $comparison);
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
     * $query->filterByVolumenumeric(1234); // WHERE volumenumeric = 1234
     * $query->filterByVolumenumeric(array(12, 34)); // WHERE volumenumeric IN (12, 34)
     * $query->filterByVolumenumeric(array('min' => 12)); // WHERE volumenumeric >= 12
     * $query->filterByVolumenumeric(array('max' => 12)); // WHERE volumenumeric <= 12
     * </code>
     *
     * @param     mixed $volumenumeric The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function filterByVolumenumeric($volumenumeric = null, $comparison = null)
    {
        if (is_array($volumenumeric)) {
            $useMinMax = false;
            if (isset($volumenumeric['min'])) {
                $this->addUsingAlias(VolumePeer::VOLUMENUMERIC, $volumenumeric['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($volumenumeric['max'])) {
                $this->addUsingAlias(VolumePeer::VOLUMENUMERIC, $volumenumeric['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VolumePeer::VOLUMENUMERIC, $volumenumeric, $comparison);
    }

    /**
     * Filter the query on the volumestotal column
     *
     * Example usage:
     * <code>
     * $query->filterByVolumestotal(1234); // WHERE volumestotal = 1234
     * $query->filterByVolumestotal(array(12, 34)); // WHERE volumestotal IN (12, 34)
     * $query->filterByVolumestotal(array('min' => 12)); // WHERE volumestotal >= 12
     * $query->filterByVolumestotal(array('max' => 12)); // WHERE volumestotal <= 12
     * </code>
     *
     * @param     mixed $volumestotal The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function filterByVolumestotal($volumestotal = null, $comparison = null)
    {
        if (is_array($volumestotal)) {
            $useMinMax = false;
            if (isset($volumestotal['min'])) {
                $this->addUsingAlias(VolumePeer::VOLUMESTOTAL, $volumestotal['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($volumestotal['max'])) {
                $this->addUsingAlias(VolumePeer::VOLUMESTOTAL, $volumestotal['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VolumePeer::VOLUMESTOTAL, $volumestotal, $comparison);
    }

    /**
     * Filter the query by a related Publication object
     *
     * @param   Publication|PropelObjectCollection $publication The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 VolumeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationRelatedByPublicationId($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(VolumePeer::PUBLICATION_ID, $publication->getId(), $comparison);
        } elseif ($publication instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(VolumePeer::PUBLICATION_ID, $publication->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPublicationRelatedByPublicationId() only accepts arguments of type Publication or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationRelatedByPublicationId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function joinPublicationRelatedByPublicationId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationRelatedByPublicationId');

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
            $this->addJoinObject($join, 'PublicationRelatedByPublicationId');
        }

        return $this;
    }

    /**
     * Use the PublicationRelatedByPublicationId relation Publication object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationQuery A secondary query class using the current class as primary query
     */
    public function usePublicationRelatedByPublicationIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationRelatedByPublicationId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationRelatedByPublicationId', '\DTA\MetadataBundle\Model\Data\PublicationQuery');
    }

    /**
     * Filter the query by a related Publication object
     *
     * @param   Publication|PropelObjectCollection $publication The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 VolumeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationRelatedByParentpublicationId($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(VolumePeer::PARENTPUBLICATION_ID, $publication->getId(), $comparison);
        } elseif ($publication instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(VolumePeer::PARENTPUBLICATION_ID, $publication->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPublicationRelatedByParentpublicationId() only accepts arguments of type Publication or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationRelatedByParentpublicationId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return VolumeQuery The current query, for fluid interface
     */
    public function joinPublicationRelatedByParentpublicationId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationRelatedByParentpublicationId');

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
            $this->addJoinObject($join, 'PublicationRelatedByParentpublicationId');
        }

        return $this;
    }

    /**
     * Use the PublicationRelatedByParentpublicationId relation Publication object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationQuery A secondary query class using the current class as primary query
     */
    public function usePublicationRelatedByParentpublicationIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationRelatedByParentpublicationId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationRelatedByParentpublicationId', '\DTA\MetadataBundle\Model\Data\PublicationQuery');
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
