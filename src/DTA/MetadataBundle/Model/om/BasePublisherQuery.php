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
use DTA\MetadataBundle\Model\Person;
use DTA\MetadataBundle\Model\Publication;
use DTA\MetadataBundle\Model\Publisher;
use DTA\MetadataBundle\Model\PublisherPeer;
use DTA\MetadataBundle\Model\PublisherQuery;
use DTA\MetadataBundle\Model\Series;

/**
 * @method PublisherQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PublisherQuery orderByPersonId($order = Criteria::ASC) Order by the person_id column
 *
 * @method PublisherQuery groupById() Group by the id column
 * @method PublisherQuery groupByPersonId() Group by the person_id column
 *
 * @method PublisherQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PublisherQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PublisherQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PublisherQuery leftJoinPerson($relationAlias = null) Adds a LEFT JOIN clause to the query using the Person relation
 * @method PublisherQuery rightJoinPerson($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Person relation
 * @method PublisherQuery innerJoinPerson($relationAlias = null) Adds a INNER JOIN clause to the query using the Person relation
 *
 * @method PublisherQuery leftJoinPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publication relation
 * @method PublisherQuery rightJoinPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publication relation
 * @method PublisherQuery innerJoinPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the Publication relation
 *
 * @method PublisherQuery leftJoinMonograph($relationAlias = null) Adds a LEFT JOIN clause to the query using the Monograph relation
 * @method PublisherQuery rightJoinMonograph($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Monograph relation
 * @method PublisherQuery innerJoinMonograph($relationAlias = null) Adds a INNER JOIN clause to the query using the Monograph relation
 *
 * @method PublisherQuery leftJoinEssay($relationAlias = null) Adds a LEFT JOIN clause to the query using the Essay relation
 * @method PublisherQuery rightJoinEssay($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Essay relation
 * @method PublisherQuery innerJoinEssay($relationAlias = null) Adds a INNER JOIN clause to the query using the Essay relation
 *
 * @method PublisherQuery leftJoinMagazine($relationAlias = null) Adds a LEFT JOIN clause to the query using the Magazine relation
 * @method PublisherQuery rightJoinMagazine($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Magazine relation
 * @method PublisherQuery innerJoinMagazine($relationAlias = null) Adds a INNER JOIN clause to the query using the Magazine relation
 *
 * @method PublisherQuery leftJoinSeries($relationAlias = null) Adds a LEFT JOIN clause to the query using the Series relation
 * @method PublisherQuery rightJoinSeries($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Series relation
 * @method PublisherQuery innerJoinSeries($relationAlias = null) Adds a INNER JOIN clause to the query using the Series relation
 *
 * @method Publisher findOne(PropelPDO $con = null) Return the first Publisher matching the query
 * @method Publisher findOneOrCreate(PropelPDO $con = null) Return the first Publisher matching the query, or a new Publisher object populated from the query conditions when no match is found
 *
 * @method Publisher findOneById(int $id) Return the first Publisher filtered by the id column
 * @method Publisher findOneByPersonId(int $person_id) Return the first Publisher filtered by the person_id column
 *
 * @method array findById(int $id) Return Publisher objects filtered by the id column
 * @method array findByPersonId(int $person_id) Return Publisher objects filtered by the person_id column
 */
abstract class BasePublisherQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePublisherQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Publisher', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PublisherQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PublisherQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PublisherQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PublisherQuery) {
            return $criteria;
        }
        $query = new PublisherQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array $key Primary key to use for the query
                         A Primary key composition: [$id, $person_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Publisher|Publisher[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PublisherPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PublisherPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Publisher A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `person_id` FROM `publisher` WHERE `id` = :p0 AND `person_id` = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Publisher();
            $obj->hydrate($row);
            PublisherPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return Publisher|Publisher[]|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Publisher[]|mixed the list of results, formatted by the current formatter
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
     * @return PublisherQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PublisherPeer::ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PublisherPeer::PERSON_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PublisherQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PublisherPeer::ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PublisherPeer::PERSON_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return PublisherQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PublisherPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PublisherPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the person_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPersonId(1234); // WHERE person_id = 1234
     * $query->filterByPersonId(array(12, 34)); // WHERE person_id IN (12, 34)
     * $query->filterByPersonId(array('min' => 12)); // WHERE person_id >= 12
     * $query->filterByPersonId(array('max' => 12)); // WHERE person_id <= 12
     * </code>
     *
     * @see       filterByPerson()
     *
     * @param     mixed $personId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublisherQuery The current query, for fluid interface
     */
    public function filterByPersonId($personId = null, $comparison = null)
    {
        if (is_array($personId)) {
            $useMinMax = false;
            if (isset($personId['min'])) {
                $this->addUsingAlias(PublisherPeer::PERSON_ID, $personId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($personId['max'])) {
                $this->addUsingAlias(PublisherPeer::PERSON_ID, $personId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherPeer::PERSON_ID, $personId, $comparison);
    }

    /**
     * Filter the query by a related Person object
     *
     * @param   Person|PropelObjectCollection $person The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublisherQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPerson($person, $comparison = null)
    {
        if ($person instanceof Person) {
            return $this
                ->addUsingAlias(PublisherPeer::PERSON_ID, $person->getId(), $comparison);
        } elseif ($person instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublisherPeer::PERSON_ID, $person->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPerson() only accepts arguments of type Person or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Person relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublisherQuery The current query, for fluid interface
     */
    public function joinPerson($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Person');

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
            $this->addJoinObject($join, 'Person');
        }

        return $this;
    }

    /**
     * Use the Person relation Person object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\PersonQuery A secondary query class using the current class as primary query
     */
    public function usePersonQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPerson($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Person', '\DTA\MetadataBundle\Model\PersonQuery');
    }

    /**
     * Filter the query by a related Publication object
     *
     * @param   Publication|PropelObjectCollection $publication  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublisherQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublication($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(PublisherPeer::ID, $publication->getPublisherId(), $comparison);
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
     * @return PublisherQuery The current query, for fluid interface
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
     * @return                 PublisherQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMonograph($monograph, $comparison = null)
    {
        if ($monograph instanceof Monograph) {
            return $this
                ->addUsingAlias(PublisherPeer::ID, $monograph->getPublisherId(), $comparison);
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
     * @return PublisherQuery The current query, for fluid interface
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
     * @return                 PublisherQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEssay($essay, $comparison = null)
    {
        if ($essay instanceof Essay) {
            return $this
                ->addUsingAlias(PublisherPeer::ID, $essay->getPublisherId(), $comparison);
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
     * @return PublisherQuery The current query, for fluid interface
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
     * @return                 PublisherQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMagazine($magazine, $comparison = null)
    {
        if ($magazine instanceof Magazine) {
            return $this
                ->addUsingAlias(PublisherPeer::ID, $magazine->getPublisherId(), $comparison);
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
     * @return PublisherQuery The current query, for fluid interface
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
     * @return                 PublisherQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySeries($series, $comparison = null)
    {
        if ($series instanceof Series) {
            return $this
                ->addUsingAlias(PublisherPeer::ID, $series->getPublisherId(), $comparison);
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
     * @return PublisherQuery The current query, for fluid interface
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
     * @param   Publisher $publisher Object to remove from the list of results
     *
     * @return PublisherQuery The current query, for fluid interface
     */
    public function prune($publisher = null)
    {
        if ($publisher) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PublisherPeer::ID), $publisher->getId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PublisherPeer::PERSON_ID), $publisher->getPersonId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
