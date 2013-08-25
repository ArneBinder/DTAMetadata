<?php

namespace DTA\MetadataBundle\Model\Master\om;

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
use DTA\MetadataBundle\Model\Master\PublicationPublicationgroup;
use DTA\MetadataBundle\Model\Master\PublicationPublicationgroupPeer;
use DTA\MetadataBundle\Model\Master\PublicationPublicationgroupQuery;
use DTA\MetadataBundle\Model\Workflow\Publicationgroup;

/**
 * @method PublicationPublicationgroupQuery orderByPublicationgroupId($order = Criteria::ASC) Order by the publicationgroup_id column
 * @method PublicationPublicationgroupQuery orderByPublicationId($order = Criteria::ASC) Order by the publication_id column
 * @method PublicationPublicationgroupQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method PublicationPublicationgroupQuery groupByPublicationgroupId() Group by the publicationgroup_id column
 * @method PublicationPublicationgroupQuery groupByPublicationId() Group by the publication_id column
 * @method PublicationPublicationgroupQuery groupById() Group by the id column
 *
 * @method PublicationPublicationgroupQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PublicationPublicationgroupQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PublicationPublicationgroupQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PublicationPublicationgroupQuery leftJoinPublicationgroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publicationgroup relation
 * @method PublicationPublicationgroupQuery rightJoinPublicationgroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publicationgroup relation
 * @method PublicationPublicationgroupQuery innerJoinPublicationgroup($relationAlias = null) Adds a INNER JOIN clause to the query using the Publicationgroup relation
 *
 * @method PublicationPublicationgroupQuery leftJoinPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publication relation
 * @method PublicationPublicationgroupQuery rightJoinPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publication relation
 * @method PublicationPublicationgroupQuery innerJoinPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the Publication relation
 *
 * @method PublicationPublicationgroup findOne(PropelPDO $con = null) Return the first PublicationPublicationgroup matching the query
 * @method PublicationPublicationgroup findOneOrCreate(PropelPDO $con = null) Return the first PublicationPublicationgroup matching the query, or a new PublicationPublicationgroup object populated from the query conditions when no match is found
 *
 * @method PublicationPublicationgroup findOneByPublicationgroupId(int $publicationgroup_id) Return the first PublicationPublicationgroup filtered by the publicationgroup_id column
 * @method PublicationPublicationgroup findOneByPublicationId(int $publication_id) Return the first PublicationPublicationgroup filtered by the publication_id column
 *
 * @method array findByPublicationgroupId(int $publicationgroup_id) Return PublicationPublicationgroup objects filtered by the publicationgroup_id column
 * @method array findByPublicationId(int $publication_id) Return PublicationPublicationgroup objects filtered by the publication_id column
 * @method array findById(int $id) Return PublicationPublicationgroup objects filtered by the id column
 */
abstract class BasePublicationPublicationgroupQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePublicationPublicationgroupQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Master\\PublicationPublicationgroup', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PublicationPublicationgroupQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PublicationPublicationgroupQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PublicationPublicationgroupQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PublicationPublicationgroupQuery) {
            return $criteria;
        }
        $query = new PublicationPublicationgroupQuery();
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
     * @return   PublicationPublicationgroup|PublicationPublicationgroup[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PublicationPublicationgroupPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PublicationPublicationgroupPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PublicationPublicationgroup A model object, or null if the key is not found
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
     * @return                 PublicationPublicationgroup A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "publicationgroup_id", "publication_id", "id" FROM "publication_publicationgroup" WHERE "id" = :p0';
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
            $obj = new PublicationPublicationgroup();
            $obj->hydrate($row);
            PublicationPublicationgroupPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PublicationPublicationgroup|PublicationPublicationgroup[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PublicationPublicationgroup[]|mixed the list of results, formatted by the current formatter
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
     * @return PublicationPublicationgroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PublicationPublicationgroupPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PublicationPublicationgroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PublicationPublicationgroupPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the publicationgroup_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublicationgroupId(1234); // WHERE publicationgroup_id = 1234
     * $query->filterByPublicationgroupId(array(12, 34)); // WHERE publicationgroup_id IN (12, 34)
     * $query->filterByPublicationgroupId(array('min' => 12)); // WHERE publicationgroup_id >= 12
     * $query->filterByPublicationgroupId(array('max' => 12)); // WHERE publicationgroup_id <= 12
     * </code>
     *
     * @see       filterByPublicationgroup()
     *
     * @param     mixed $publicationgroupId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationPublicationgroupQuery The current query, for fluid interface
     */
    public function filterByPublicationgroupId($publicationgroupId = null, $comparison = null)
    {
        if (is_array($publicationgroupId)) {
            $useMinMax = false;
            if (isset($publicationgroupId['min'])) {
                $this->addUsingAlias(PublicationPublicationgroupPeer::PUBLICATIONGROUP_ID, $publicationgroupId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publicationgroupId['max'])) {
                $this->addUsingAlias(PublicationPublicationgroupPeer::PUBLICATIONGROUP_ID, $publicationgroupId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPublicationgroupPeer::PUBLICATIONGROUP_ID, $publicationgroupId, $comparison);
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
     * @see       filterByPublication()
     *
     * @param     mixed $publicationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationPublicationgroupQuery The current query, for fluid interface
     */
    public function filterByPublicationId($publicationId = null, $comparison = null)
    {
        if (is_array($publicationId)) {
            $useMinMax = false;
            if (isset($publicationId['min'])) {
                $this->addUsingAlias(PublicationPublicationgroupPeer::PUBLICATION_ID, $publicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publicationId['max'])) {
                $this->addUsingAlias(PublicationPublicationgroupPeer::PUBLICATION_ID, $publicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPublicationgroupPeer::PUBLICATION_ID, $publicationId, $comparison);
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
     * @return PublicationPublicationgroupQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PublicationPublicationgroupPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PublicationPublicationgroupPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPublicationgroupPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query by a related Publicationgroup object
     *
     * @param   Publicationgroup|PropelObjectCollection $publicationgroup The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationPublicationgroupQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationgroup($publicationgroup, $comparison = null)
    {
        if ($publicationgroup instanceof Publicationgroup) {
            return $this
                ->addUsingAlias(PublicationPublicationgroupPeer::PUBLICATIONGROUP_ID, $publicationgroup->getId(), $comparison);
        } elseif ($publicationgroup instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPublicationgroupPeer::PUBLICATIONGROUP_ID, $publicationgroup->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPublicationgroup() only accepts arguments of type Publicationgroup or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Publicationgroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationPublicationgroupQuery The current query, for fluid interface
     */
    public function joinPublicationgroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Publicationgroup');

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
            $this->addJoinObject($join, 'Publicationgroup');
        }

        return $this;
    }

    /**
     * Use the Publicationgroup relation Publicationgroup object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Workflow\PublicationgroupQuery A secondary query class using the current class as primary query
     */
    public function usePublicationgroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationgroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publicationgroup', '\DTA\MetadataBundle\Model\Workflow\PublicationgroupQuery');
    }

    /**
     * Filter the query by a related Publication object
     *
     * @param   Publication|PropelObjectCollection $publication The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationPublicationgroupQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublication($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(PublicationPublicationgroupPeer::PUBLICATION_ID, $publication->getId(), $comparison);
        } elseif ($publication instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPublicationgroupPeer::PUBLICATION_ID, $publication->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPublication() only accepts arguments of type Publication or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Publication relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationPublicationgroupQuery The current query, for fluid interface
     */
    public function joinPublication($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Publication');

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
            $this->addJoinObject($join, 'Publication');
        }

        return $this;
    }

    /**
     * Use the Publication relation Publication object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationQuery A secondary query class using the current class as primary query
     */
    public function usePublicationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publication', '\DTA\MetadataBundle\Model\Data\PublicationQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PublicationPublicationgroup $publicationPublicationgroup Object to remove from the list of results
     *
     * @return PublicationPublicationgroupQuery The current query, for fluid interface
     */
    public function prune($publicationPublicationgroup = null)
    {
        if ($publicationPublicationgroup) {
            $this->addUsingAlias(PublicationPublicationgroupPeer::ID, $publicationPublicationgroup->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
