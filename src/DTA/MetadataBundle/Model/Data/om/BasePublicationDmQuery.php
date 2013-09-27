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
use DTA\MetadataBundle\Model\Data\PublicationDm;
use DTA\MetadataBundle\Model\Data\PublicationDmPeer;
use DTA\MetadataBundle\Model\Data\PublicationDmQuery;

/**
 * @method PublicationDmQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PublicationDmQuery orderByPublicationId($order = Criteria::ASC) Order by the publication_id column
 * @method PublicationDmQuery orderByTitleId($order = Criteria::ASC) Order by the title_id column
 * @method PublicationDmQuery orderByPages($order = Criteria::ASC) Order by the pages column
 *
 * @method PublicationDmQuery groupById() Group by the id column
 * @method PublicationDmQuery groupByPublicationId() Group by the publication_id column
 * @method PublicationDmQuery groupByTitleId() Group by the title_id column
 * @method PublicationDmQuery groupByPages() Group by the pages column
 *
 * @method PublicationDmQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PublicationDmQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PublicationDmQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PublicationDmQuery leftJoinPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publication relation
 * @method PublicationDmQuery rightJoinPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publication relation
 * @method PublicationDmQuery innerJoinPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the Publication relation
 *
 * @method PublicationDm findOne(PropelPDO $con = null) Return the first PublicationDm matching the query
 * @method PublicationDm findOneOrCreate(PropelPDO $con = null) Return the first PublicationDm matching the query, or a new PublicationDm object populated from the query conditions when no match is found
 *
 * @method PublicationDm findOneByPublicationId(int $publication_id) Return the first PublicationDm filtered by the publication_id column
 * @method PublicationDm findOneByTitleId(int $title_id) Return the first PublicationDm filtered by the title_id column
 * @method PublicationDm findOneByPages(string $pages) Return the first PublicationDm filtered by the pages column
 *
 * @method array findById(int $id) Return PublicationDm objects filtered by the id column
 * @method array findByPublicationId(int $publication_id) Return PublicationDm objects filtered by the publication_id column
 * @method array findByTitleId(int $title_id) Return PublicationDm objects filtered by the title_id column
 * @method array findByPages(string $pages) Return PublicationDm objects filtered by the pages column
 */
abstract class BasePublicationDmQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePublicationDmQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Data\\PublicationDm', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PublicationDmQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PublicationDmQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PublicationDmQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PublicationDmQuery) {
            return $criteria;
        }
        $query = new PublicationDmQuery();
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
     * @return   PublicationDm|PublicationDm[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PublicationDmPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PublicationDmPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PublicationDm A model object, or null if the key is not found
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
     * @return                 PublicationDm A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "id", "publication_id", "title_id", "pages" FROM "publication_dm" WHERE "id" = :p0';
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
            $obj = new PublicationDm();
            $obj->hydrate($row);
            PublicationDmPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PublicationDm|PublicationDm[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PublicationDm[]|mixed the list of results, formatted by the current formatter
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
     * @return PublicationDmQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PublicationDmPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PublicationDmQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PublicationDmPeer::ID, $keys, Criteria::IN);
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
     * @return PublicationDmQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PublicationDmPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PublicationDmPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationDmPeer::ID, $id, $comparison);
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
     * @return PublicationDmQuery The current query, for fluid interface
     */
    public function filterByPublicationId($publicationId = null, $comparison = null)
    {
        if (is_array($publicationId)) {
            $useMinMax = false;
            if (isset($publicationId['min'])) {
                $this->addUsingAlias(PublicationDmPeer::PUBLICATION_ID, $publicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publicationId['max'])) {
                $this->addUsingAlias(PublicationDmPeer::PUBLICATION_ID, $publicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationDmPeer::PUBLICATION_ID, $publicationId, $comparison);
    }

    /**
     * Filter the query on the title_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTitleId(1234); // WHERE title_id = 1234
     * $query->filterByTitleId(array(12, 34)); // WHERE title_id IN (12, 34)
     * $query->filterByTitleId(array('min' => 12)); // WHERE title_id >= 12
     * $query->filterByTitleId(array('max' => 12)); // WHERE title_id <= 12
     * </code>
     *
     * @param     mixed $titleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationDmQuery The current query, for fluid interface
     */
    public function filterByTitleId($titleId = null, $comparison = null)
    {
        if (is_array($titleId)) {
            $useMinMax = false;
            if (isset($titleId['min'])) {
                $this->addUsingAlias(PublicationDmPeer::TITLE_ID, $titleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($titleId['max'])) {
                $this->addUsingAlias(PublicationDmPeer::TITLE_ID, $titleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationDmPeer::TITLE_ID, $titleId, $comparison);
    }

    /**
     * Filter the query on the pages column
     *
     * Example usage:
     * <code>
     * $query->filterByPages('fooValue');   // WHERE pages = 'fooValue'
     * $query->filterByPages('%fooValue%'); // WHERE pages LIKE '%fooValue%'
     * </code>
     *
     * @param     string $pages The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationDmQuery The current query, for fluid interface
     */
    public function filterByPages($pages = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($pages)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $pages)) {
                $pages = str_replace('*', '%', $pages);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationDmPeer::PAGES, $pages, $comparison);
    }

    /**
     * Filter the query by a related Publication object
     *
     * @param   Publication|PropelObjectCollection $publication The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationDmQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublication($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(PublicationDmPeer::PUBLICATION_ID, $publication->getId(), $comparison);
        } elseif ($publication instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationDmPeer::PUBLICATION_ID, $publication->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PublicationDmQuery The current query, for fluid interface
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
     * @param   PublicationDm $publicationDm Object to remove from the list of results
     *
     * @return PublicationDmQuery The current query, for fluid interface
     */
    public function prune($publicationDm = null)
    {
        if ($publicationDm) {
            $this->addUsingAlias(PublicationDmPeer::ID, $publicationDm->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
