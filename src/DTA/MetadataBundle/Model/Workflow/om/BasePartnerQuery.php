<?php

namespace DTA\MetadataBundle\Model\Workflow\om;

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
use DTA\MetadataBundle\Model\Workflow\Imagesource;
use DTA\MetadataBundle\Model\Workflow\Partner;
use DTA\MetadataBundle\Model\Workflow\PartnerPeer;
use DTA\MetadataBundle\Model\Workflow\PartnerQuery;
use DTA\MetadataBundle\Model\Workflow\Task;
use DTA\MetadataBundle\Model\Workflow\Textsource;

/**
 * @method PartnerQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PartnerQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method PartnerQuery orderByPerson($order = Criteria::ASC) Order by the person column
 * @method PartnerQuery orderByContactData($order = Criteria::ASC) Order by the contact_data column
 * @method PartnerQuery orderByComments($order = Criteria::ASC) Order by the comments column
 * @method PartnerQuery orderByIsOrganization($order = Criteria::ASC) Order by the is_organization column
 *
 * @method PartnerQuery groupById() Group by the id column
 * @method PartnerQuery groupByName() Group by the name column
 * @method PartnerQuery groupByPerson() Group by the person column
 * @method PartnerQuery groupByContactData() Group by the contact_data column
 * @method PartnerQuery groupByComments() Group by the comments column
 * @method PartnerQuery groupByIsOrganization() Group by the is_organization column
 *
 * @method PartnerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PartnerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PartnerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PartnerQuery leftJoinTask($relationAlias = null) Adds a LEFT JOIN clause to the query using the Task relation
 * @method PartnerQuery rightJoinTask($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Task relation
 * @method PartnerQuery innerJoinTask($relationAlias = null) Adds a INNER JOIN clause to the query using the Task relation
 *
 * @method PartnerQuery leftJoinImagesource($relationAlias = null) Adds a LEFT JOIN clause to the query using the Imagesource relation
 * @method PartnerQuery rightJoinImagesource($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Imagesource relation
 * @method PartnerQuery innerJoinImagesource($relationAlias = null) Adds a INNER JOIN clause to the query using the Imagesource relation
 *
 * @method PartnerQuery leftJoinTextsource($relationAlias = null) Adds a LEFT JOIN clause to the query using the Textsource relation
 * @method PartnerQuery rightJoinTextsource($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Textsource relation
 * @method PartnerQuery innerJoinTextsource($relationAlias = null) Adds a INNER JOIN clause to the query using the Textsource relation
 *
 * @method Partner findOne(PropelPDO $con = null) Return the first Partner matching the query
 * @method Partner findOneOrCreate(PropelPDO $con = null) Return the first Partner matching the query, or a new Partner object populated from the query conditions when no match is found
 *
 * @method Partner findOneByName(string $name) Return the first Partner filtered by the name column
 * @method Partner findOneByPerson(string $person) Return the first Partner filtered by the person column
 * @method Partner findOneByContactData(string $contact_data) Return the first Partner filtered by the contact_data column
 * @method Partner findOneByComments(string $comments) Return the first Partner filtered by the comments column
 * @method Partner findOneByIsOrganization(boolean $is_organization) Return the first Partner filtered by the is_organization column
 *
 * @method array findById(int $id) Return Partner objects filtered by the id column
 * @method array findByName(string $name) Return Partner objects filtered by the name column
 * @method array findByPerson(string $person) Return Partner objects filtered by the person column
 * @method array findByContactData(string $contact_data) Return Partner objects filtered by the contact_data column
 * @method array findByComments(string $comments) Return Partner objects filtered by the comments column
 * @method array findByIsOrganization(boolean $is_organization) Return Partner objects filtered by the is_organization column
 */
abstract class BasePartnerQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePartnerQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Workflow\\Partner', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PartnerQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PartnerQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PartnerQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PartnerQuery) {
            return $criteria;
        }
        $query = new PartnerQuery();
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
     * @return   Partner|Partner[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PartnerPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PartnerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Partner A model object, or null if the key is not found
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
     * @return                 Partner A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "id", "name", "person", "contact_data", "comments", "is_organization" FROM "partner" WHERE "id" = :p0';
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
            $obj = new Partner();
            $obj->hydrate($row);
            PartnerPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Partner|Partner[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Partner[]|mixed the list of results, formatted by the current formatter
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
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PartnerPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PartnerPeer::ID, $keys, Criteria::IN);
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
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PartnerPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PartnerPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PartnerPeer::ID, $id, $comparison);
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
     * @return PartnerQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PartnerPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the person column
     *
     * Example usage:
     * <code>
     * $query->filterByPerson('fooValue');   // WHERE person = 'fooValue'
     * $query->filterByPerson('%fooValue%'); // WHERE person LIKE '%fooValue%'
     * </code>
     *
     * @param     string $person The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByPerson($person = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($person)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $person)) {
                $person = str_replace('*', '%', $person);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PartnerPeer::PERSON, $person, $comparison);
    }

    /**
     * Filter the query on the contact_data column
     *
     * Example usage:
     * <code>
     * $query->filterByContactData('fooValue');   // WHERE contact_data = 'fooValue'
     * $query->filterByContactData('%fooValue%'); // WHERE contact_data LIKE '%fooValue%'
     * </code>
     *
     * @param     string $contactData The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByContactData($contactData = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($contactData)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $contactData)) {
                $contactData = str_replace('*', '%', $contactData);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PartnerPeer::CONTACT_DATA, $contactData, $comparison);
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
     * @return PartnerQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PartnerPeer::COMMENTS, $comments, $comparison);
    }

    /**
     * Filter the query on the is_organization column
     *
     * Example usage:
     * <code>
     * $query->filterByIsOrganization(true); // WHERE is_organization = true
     * $query->filterByIsOrganization('yes'); // WHERE is_organization = true
     * </code>
     *
     * @param     boolean|string $isOrganization The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByIsOrganization($isOrganization = null, $comparison = null)
    {
        if (is_string($isOrganization)) {
            $isOrganization = in_array(strtolower($isOrganization), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PartnerPeer::IS_ORGANIZATION, $isOrganization, $comparison);
    }

    /**
     * Filter the query by a related Task object
     *
     * @param   Task|PropelObjectCollection $task  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PartnerQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTask($task, $comparison = null)
    {
        if ($task instanceof Task) {
            return $this
                ->addUsingAlias(PartnerPeer::ID, $task->getPartnerId(), $comparison);
        } elseif ($task instanceof PropelObjectCollection) {
            return $this
                ->useTaskQuery()
                ->filterByPrimaryKeys($task->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTask() only accepts arguments of type Task or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Task relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function joinTask($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Task');

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
            $this->addJoinObject($join, 'Task');
        }

        return $this;
    }

    /**
     * Use the Task relation Task object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Workflow\TaskQuery A secondary query class using the current class as primary query
     */
    public function useTaskQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTask($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Task', '\DTA\MetadataBundle\Model\Workflow\TaskQuery');
    }

    /**
     * Filter the query by a related Imagesource object
     *
     * @param   Imagesource|PropelObjectCollection $imagesource  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PartnerQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByImagesource($imagesource, $comparison = null)
    {
        if ($imagesource instanceof Imagesource) {
            return $this
                ->addUsingAlias(PartnerPeer::ID, $imagesource->getPartnerId(), $comparison);
        } elseif ($imagesource instanceof PropelObjectCollection) {
            return $this
                ->useImagesourceQuery()
                ->filterByPrimaryKeys($imagesource->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByImagesource() only accepts arguments of type Imagesource or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Imagesource relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function joinImagesource($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Imagesource');

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
            $this->addJoinObject($join, 'Imagesource');
        }

        return $this;
    }

    /**
     * Use the Imagesource relation Imagesource object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Workflow\ImagesourceQuery A secondary query class using the current class as primary query
     */
    public function useImagesourceQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinImagesource($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Imagesource', '\DTA\MetadataBundle\Model\Workflow\ImagesourceQuery');
    }

    /**
     * Filter the query by a related Textsource object
     *
     * @param   Textsource|PropelObjectCollection $textsource  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PartnerQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTextsource($textsource, $comparison = null)
    {
        if ($textsource instanceof Textsource) {
            return $this
                ->addUsingAlias(PartnerPeer::ID, $textsource->getPartnerId(), $comparison);
        } elseif ($textsource instanceof PropelObjectCollection) {
            return $this
                ->useTextsourceQuery()
                ->filterByPrimaryKeys($textsource->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTextsource() only accepts arguments of type Textsource or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Textsource relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function joinTextsource($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Textsource');

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
            $this->addJoinObject($join, 'Textsource');
        }

        return $this;
    }

    /**
     * Use the Textsource relation Textsource object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Workflow\TextsourceQuery A secondary query class using the current class as primary query
     */
    public function useTextsourceQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTextsource($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Textsource', '\DTA\MetadataBundle\Model\Workflow\TextsourceQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Partner $partner Object to remove from the list of results
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function prune($partner = null)
    {
        if ($partner) {
            $this->addUsingAlias(PartnerPeer::ID, $partner->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
