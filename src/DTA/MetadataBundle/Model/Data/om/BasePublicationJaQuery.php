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
use DTA\MetadataBundle\Model\Data\PublicationJa;
use DTA\MetadataBundle\Model\Data\PublicationJaPeer;
use DTA\MetadataBundle\Model\Data\PublicationJaQuery;
use DTA\MetadataBundle\Model\Data\Volume;

/**
 * @method PublicationJaQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PublicationJaQuery orderByPublicationId($order = Criteria::ASC) Order by the publication_id column
 * @method PublicationJaQuery orderByVolumeId($order = Criteria::ASC) Order by the volume_id column
 * @method PublicationJaQuery orderByParent($order = Criteria::ASC) Order by the parent column
 *
 * @method PublicationJaQuery groupById() Group by the id column
 * @method PublicationJaQuery groupByPublicationId() Group by the publication_id column
 * @method PublicationJaQuery groupByVolumeId() Group by the volume_id column
 * @method PublicationJaQuery groupByParent() Group by the parent column
 *
 * @method PublicationJaQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PublicationJaQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PublicationJaQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PublicationJaQuery leftJoinPublicationRelatedByPublicationId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationRelatedByPublicationId relation
 * @method PublicationJaQuery rightJoinPublicationRelatedByPublicationId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationRelatedByPublicationId relation
 * @method PublicationJaQuery innerJoinPublicationRelatedByPublicationId($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationRelatedByPublicationId relation
 *
 * @method PublicationJaQuery leftJoinPublicationRelatedByParent($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationRelatedByParent relation
 * @method PublicationJaQuery rightJoinPublicationRelatedByParent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationRelatedByParent relation
 * @method PublicationJaQuery innerJoinPublicationRelatedByParent($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationRelatedByParent relation
 *
 * @method PublicationJaQuery leftJoinVolume($relationAlias = null) Adds a LEFT JOIN clause to the query using the Volume relation
 * @method PublicationJaQuery rightJoinVolume($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Volume relation
 * @method PublicationJaQuery innerJoinVolume($relationAlias = null) Adds a INNER JOIN clause to the query using the Volume relation
 *
 * @method PublicationJa findOne(PropelPDO $con = null) Return the first PublicationJa matching the query
 * @method PublicationJa findOneOrCreate(PropelPDO $con = null) Return the first PublicationJa matching the query, or a new PublicationJa object populated from the query conditions when no match is found
 *
 * @method PublicationJa findOneByPublicationId(int $publication_id) Return the first PublicationJa filtered by the publication_id column
 * @method PublicationJa findOneByVolumeId(int $volume_id) Return the first PublicationJa filtered by the volume_id column
 * @method PublicationJa findOneByParent(int $parent) Return the first PublicationJa filtered by the parent column
 *
 * @method array findById(int $id) Return PublicationJa objects filtered by the id column
 * @method array findByPublicationId(int $publication_id) Return PublicationJa objects filtered by the publication_id column
 * @method array findByVolumeId(int $volume_id) Return PublicationJa objects filtered by the volume_id column
 * @method array findByParent(int $parent) Return PublicationJa objects filtered by the parent column
 */
abstract class BasePublicationJaQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePublicationJaQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Data\\PublicationJa', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PublicationJaQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PublicationJaQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PublicationJaQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PublicationJaQuery) {
            return $criteria;
        }
        $query = new PublicationJaQuery();
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
     * @return   PublicationJa|PublicationJa[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PublicationJaPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PublicationJaPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PublicationJa A model object, or null if the key is not found
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
     * @return                 PublicationJa A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "id", "publication_id", "volume_id", "parent" FROM "publication_ja" WHERE "id" = :p0';
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
            $obj = new PublicationJa();
            $obj->hydrate($row);
            PublicationJaPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PublicationJa|PublicationJa[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PublicationJa[]|mixed the list of results, formatted by the current formatter
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
     * @return PublicationJaQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PublicationJaPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PublicationJaQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PublicationJaPeer::ID, $keys, Criteria::IN);
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
     * @return PublicationJaQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PublicationJaPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PublicationJaPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationJaPeer::ID, $id, $comparison);
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
     * @return PublicationJaQuery The current query, for fluid interface
     */
    public function filterByPublicationId($publicationId = null, $comparison = null)
    {
        if (is_array($publicationId)) {
            $useMinMax = false;
            if (isset($publicationId['min'])) {
                $this->addUsingAlias(PublicationJaPeer::PUBLICATION_ID, $publicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publicationId['max'])) {
                $this->addUsingAlias(PublicationJaPeer::PUBLICATION_ID, $publicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationJaPeer::PUBLICATION_ID, $publicationId, $comparison);
    }

    /**
     * Filter the query on the volume_id column
     *
     * Example usage:
     * <code>
     * $query->filterByVolumeId(1234); // WHERE volume_id = 1234
     * $query->filterByVolumeId(array(12, 34)); // WHERE volume_id IN (12, 34)
     * $query->filterByVolumeId(array('min' => 12)); // WHERE volume_id >= 12
     * $query->filterByVolumeId(array('max' => 12)); // WHERE volume_id <= 12
     * </code>
     *
     * @see       filterByVolume()
     *
     * @param     mixed $volumeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationJaQuery The current query, for fluid interface
     */
    public function filterByVolumeId($volumeId = null, $comparison = null)
    {
        if (is_array($volumeId)) {
            $useMinMax = false;
            if (isset($volumeId['min'])) {
                $this->addUsingAlias(PublicationJaPeer::VOLUME_ID, $volumeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($volumeId['max'])) {
                $this->addUsingAlias(PublicationJaPeer::VOLUME_ID, $volumeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationJaPeer::VOLUME_ID, $volumeId, $comparison);
    }

    /**
     * Filter the query on the parent column
     *
     * Example usage:
     * <code>
     * $query->filterByParent(1234); // WHERE parent = 1234
     * $query->filterByParent(array(12, 34)); // WHERE parent IN (12, 34)
     * $query->filterByParent(array('min' => 12)); // WHERE parent >= 12
     * $query->filterByParent(array('max' => 12)); // WHERE parent <= 12
     * </code>
     *
     * @see       filterByPublicationRelatedByParent()
     *
     * @param     mixed $parent The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationJaQuery The current query, for fluid interface
     */
    public function filterByParent($parent = null, $comparison = null)
    {
        if (is_array($parent)) {
            $useMinMax = false;
            if (isset($parent['min'])) {
                $this->addUsingAlias(PublicationJaPeer::PARENT, $parent['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($parent['max'])) {
                $this->addUsingAlias(PublicationJaPeer::PARENT, $parent['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationJaPeer::PARENT, $parent, $comparison);
    }

    /**
     * Filter the query by a related Publication object
     *
     * @param   Publication|PropelObjectCollection $publication The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationJaQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationRelatedByPublicationId($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(PublicationJaPeer::PUBLICATION_ID, $publication->getId(), $comparison);
        } elseif ($publication instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationJaPeer::PUBLICATION_ID, $publication->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PublicationJaQuery The current query, for fluid interface
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
     * @return                 PublicationJaQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationRelatedByParent($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(PublicationJaPeer::PARENT, $publication->getId(), $comparison);
        } elseif ($publication instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationJaPeer::PARENT, $publication->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPublicationRelatedByParent() only accepts arguments of type Publication or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationRelatedByParent relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationJaQuery The current query, for fluid interface
     */
    public function joinPublicationRelatedByParent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationRelatedByParent');

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
            $this->addJoinObject($join, 'PublicationRelatedByParent');
        }

        return $this;
    }

    /**
     * Use the PublicationRelatedByParent relation Publication object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationQuery A secondary query class using the current class as primary query
     */
    public function usePublicationRelatedByParentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationRelatedByParent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationRelatedByParent', '\DTA\MetadataBundle\Model\Data\PublicationQuery');
    }

    /**
     * Filter the query by a related Volume object
     *
     * @param   Volume|PropelObjectCollection $volume The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationJaQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByVolume($volume, $comparison = null)
    {
        if ($volume instanceof Volume) {
            return $this
                ->addUsingAlias(PublicationJaPeer::VOLUME_ID, $volume->getId(), $comparison);
        } elseif ($volume instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationJaPeer::VOLUME_ID, $volume->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByVolume() only accepts arguments of type Volume or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Volume relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationJaQuery The current query, for fluid interface
     */
    public function joinVolume($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Volume');

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
            $this->addJoinObject($join, 'Volume');
        }

        return $this;
    }

    /**
     * Use the Volume relation Volume object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\VolumeQuery A secondary query class using the current class as primary query
     */
    public function useVolumeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinVolume($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Volume', '\DTA\MetadataBundle\Model\Data\VolumeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PublicationJa $publicationJa Object to remove from the list of results
     *
     * @return PublicationJaQuery The current query, for fluid interface
     */
    public function prune($publicationJa = null)
    {
        if ($publicationJa) {
            $this->addUsingAlias(PublicationJaPeer::ID, $publicationJa->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
