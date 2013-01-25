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
use DTA\MetadataBundle\Model\Essay;
use DTA\MetadataBundle\Model\Magazine;
use DTA\MetadataBundle\Model\Monograph;
use DTA\MetadataBundle\Model\Place;
use DTA\MetadataBundle\Model\PlacePeer;
use DTA\MetadataBundle\Model\PlaceQuery;
use DTA\MetadataBundle\Model\Publication;
use DTA\MetadataBundle\Model\Series;

/**
 * @method PlaceQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PlaceQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method PlaceQuery orderByGnd($order = Criteria::ASC) Order by the gnd column
 *
 * @method PlaceQuery groupById() Group by the id column
 * @method PlaceQuery groupByName() Group by the name column
 * @method PlaceQuery groupByGnd() Group by the gnd column
 *
 * @method PlaceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PlaceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PlaceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PlaceQuery leftJoinPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publication relation
 * @method PlaceQuery rightJoinPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publication relation
 * @method PlaceQuery innerJoinPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the Publication relation
 *
 * @method PlaceQuery leftJoinMonograph($relationAlias = null) Adds a LEFT JOIN clause to the query using the Monograph relation
 * @method PlaceQuery rightJoinMonograph($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Monograph relation
 * @method PlaceQuery innerJoinMonograph($relationAlias = null) Adds a INNER JOIN clause to the query using the Monograph relation
 *
 * @method PlaceQuery leftJoinEssay($relationAlias = null) Adds a LEFT JOIN clause to the query using the Essay relation
 * @method PlaceQuery rightJoinEssay($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Essay relation
 * @method PlaceQuery innerJoinEssay($relationAlias = null) Adds a INNER JOIN clause to the query using the Essay relation
 *
 * @method PlaceQuery leftJoinMagazine($relationAlias = null) Adds a LEFT JOIN clause to the query using the Magazine relation
 * @method PlaceQuery rightJoinMagazine($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Magazine relation
 * @method PlaceQuery innerJoinMagazine($relationAlias = null) Adds a INNER JOIN clause to the query using the Magazine relation
 *
 * @method PlaceQuery leftJoinSeries($relationAlias = null) Adds a LEFT JOIN clause to the query using the Series relation
 * @method PlaceQuery rightJoinSeries($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Series relation
 * @method PlaceQuery innerJoinSeries($relationAlias = null) Adds a INNER JOIN clause to the query using the Series relation
 *
 * @method Place findOne(PropelPDO $con = null) Return the first Place matching the query
 * @method Place findOneOrCreate(PropelPDO $con = null) Return the first Place matching the query, or a new Place object populated from the query conditions when no match is found
 *
 * @method Place findOneByName(string $name) Return the first Place filtered by the name column
 * @method Place findOneByGnd(string $gnd) Return the first Place filtered by the gnd column
 *
 * @method array findById(int $id) Return Place objects filtered by the id column
 * @method array findByName(string $name) Return Place objects filtered by the name column
 * @method array findByGnd(string $gnd) Return Place objects filtered by the gnd column
 */
abstract class BasePlaceQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePlaceQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Place', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PlaceQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PlaceQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PlaceQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PlaceQuery) {
            return $criteria;
        }
        $query = new PlaceQuery();
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
     * @return   Place|Place[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PlacePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PlacePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Place A model object, or null if the key is not found
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
     * @return                 Place A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `gnd` FROM `place` WHERE `id` = :p0';
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
            $obj = new Place();
            $obj->hydrate($row);
            PlacePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Place|Place[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Place[]|mixed the list of results, formatted by the current formatter
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
     * @return PlaceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PlacePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PlaceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PlacePeer::ID, $keys, Criteria::IN);
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
     * @return PlaceQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PlacePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PlacePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlacePeer::ID, $id, $comparison);
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
     * @return PlaceQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PlacePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the gnd column
     *
     * Example usage:
     * <code>
     * $query->filterByGnd('fooValue');   // WHERE gnd = 'fooValue'
     * $query->filterByGnd('%fooValue%'); // WHERE gnd LIKE '%fooValue%'
     * </code>
     *
     * @param     string $gnd The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PlaceQuery The current query, for fluid interface
     */
    public function filterByGnd($gnd = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($gnd)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $gnd)) {
                $gnd = str_replace('*', '%', $gnd);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PlacePeer::GND, $gnd, $comparison);
    }

    /**
     * Filter the query by a related Publication object
     *
     * @param   Publication|PropelObjectCollection $publication  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PlaceQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublication($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(PlacePeer::ID, $publication->getPlaceId(), $comparison);
        } elseif ($publication instanceof PropelObjectCollection) {
            return $this
                ->usePublicationQuery()
                ->filterByPrimaryKeys($publication->getPrimaryKeys())
                ->endUse();
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
     * @return PlaceQuery The current query, for fluid interface
     */
    public function joinPublication($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @return   \DTA\MetadataBundle\Model\PublicationQuery A secondary query class using the current class as primary query
     */
    public function usePublicationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPublication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publication', '\DTA\MetadataBundle\Model\PublicationQuery');
    }

    /**
     * Filter the query by a related Monograph object
     *
     * @param   Monograph|PropelObjectCollection $monograph  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PlaceQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMonograph($monograph, $comparison = null)
    {
        if ($monograph instanceof Monograph) {
            return $this
                ->addUsingAlias(PlacePeer::ID, $monograph->getPlaceId(), $comparison);
        } elseif ($monograph instanceof PropelObjectCollection) {
            return $this
                ->useMonographQuery()
                ->filterByPrimaryKeys($monograph->getPrimaryKeys())
                ->endUse();
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
     * @return PlaceQuery The current query, for fluid interface
     */
    public function joinMonograph($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useMonographQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMonograph($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Monograph', '\DTA\MetadataBundle\Model\MonographQuery');
    }

    /**
     * Filter the query by a related Essay object
     *
     * @param   Essay|PropelObjectCollection $essay  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PlaceQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEssay($essay, $comparison = null)
    {
        if ($essay instanceof Essay) {
            return $this
                ->addUsingAlias(PlacePeer::ID, $essay->getPlaceId(), $comparison);
        } elseif ($essay instanceof PropelObjectCollection) {
            return $this
                ->useEssayQuery()
                ->filterByPrimaryKeys($essay->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEssay() only accepts arguments of type Essay or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Essay relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PlaceQuery The current query, for fluid interface
     */
    public function joinEssay($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Essay');

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
            $this->addJoinObject($join, 'Essay');
        }

        return $this;
    }

    /**
     * Use the Essay relation Essay object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\EssayQuery A secondary query class using the current class as primary query
     */
    public function useEssayQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinEssay($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Essay', '\DTA\MetadataBundle\Model\EssayQuery');
    }

    /**
     * Filter the query by a related Magazine object
     *
     * @param   Magazine|PropelObjectCollection $magazine  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PlaceQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMagazine($magazine, $comparison = null)
    {
        if ($magazine instanceof Magazine) {
            return $this
                ->addUsingAlias(PlacePeer::ID, $magazine->getPlaceId(), $comparison);
        } elseif ($magazine instanceof PropelObjectCollection) {
            return $this
                ->useMagazineQuery()
                ->filterByPrimaryKeys($magazine->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMagazine() only accepts arguments of type Magazine or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Magazine relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PlaceQuery The current query, for fluid interface
     */
    public function joinMagazine($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Magazine');

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
            $this->addJoinObject($join, 'Magazine');
        }

        return $this;
    }

    /**
     * Use the Magazine relation Magazine object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\MagazineQuery A secondary query class using the current class as primary query
     */
    public function useMagazineQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMagazine($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Magazine', '\DTA\MetadataBundle\Model\MagazineQuery');
    }

    /**
     * Filter the query by a related Series object
     *
     * @param   Series|PropelObjectCollection $series  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PlaceQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySeries($series, $comparison = null)
    {
        if ($series instanceof Series) {
            return $this
                ->addUsingAlias(PlacePeer::ID, $series->getPlaceId(), $comparison);
        } elseif ($series instanceof PropelObjectCollection) {
            return $this
                ->useSeriesQuery()
                ->filterByPrimaryKeys($series->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySeries() only accepts arguments of type Series or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Series relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PlaceQuery The current query, for fluid interface
     */
    public function joinSeries($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Series');

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
            $this->addJoinObject($join, 'Series');
        }

        return $this;
    }

    /**
     * Use the Series relation Series object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\SeriesQuery A secondary query class using the current class as primary query
     */
    public function useSeriesQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSeries($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Series', '\DTA\MetadataBundle\Model\SeriesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Place $place Object to remove from the list of results
     *
     * @return PlaceQuery The current query, for fluid interface
     */
    public function prune($place = null)
    {
        if ($place) {
            $this->addUsingAlias(PlacePeer::ID, $place->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
