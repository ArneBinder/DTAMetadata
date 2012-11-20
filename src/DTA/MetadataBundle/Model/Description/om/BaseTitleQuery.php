<?php

namespace DTA\MetadataBundle\Model\Description\om;

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
use DTA\MetadataBundle\Model\Description\Title;
use DTA\MetadataBundle\Model\Description\TitlePeer;
use DTA\MetadataBundle\Model\Description\TitleQuery;
use DTA\MetadataBundle\Model\Description\Titletype;
use DTA\MetadataBundle\Model\Publication\Publication;
use DTA\MetadataBundle\Model\Publication\Volume;
use DTA\MetadataBundle\Model\Publication\Work;

/**
 * @method TitleQuery orderById($order = Criteria::ASC) Order by the id column
 * @method TitleQuery orderBySequenceindex($order = Criteria::ASC) Order by the sequenceIndex column
 * @method TitleQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method TitleQuery orderByWorkId($order = Criteria::ASC) Order by the work_id column
 * @method TitleQuery orderByPublicationId($order = Criteria::ASC) Order by the publication_id column
 * @method TitleQuery orderByVolumeId($order = Criteria::ASC) Order by the volume_id column
 * @method TitleQuery orderByTitletypeId($order = Criteria::ASC) Order by the titleType_id column
 *
 * @method TitleQuery groupById() Group by the id column
 * @method TitleQuery groupBySequenceindex() Group by the sequenceIndex column
 * @method TitleQuery groupByTitle() Group by the title column
 * @method TitleQuery groupByWorkId() Group by the work_id column
 * @method TitleQuery groupByPublicationId() Group by the publication_id column
 * @method TitleQuery groupByVolumeId() Group by the volume_id column
 * @method TitleQuery groupByTitletypeId() Group by the titleType_id column
 *
 * @method TitleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method TitleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method TitleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method TitleQuery leftJoinTitletype($relationAlias = null) Adds a LEFT JOIN clause to the query using the Titletype relation
 * @method TitleQuery rightJoinTitletype($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Titletype relation
 * @method TitleQuery innerJoinTitletype($relationAlias = null) Adds a INNER JOIN clause to the query using the Titletype relation
 *
 * @method TitleQuery leftJoinPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publication relation
 * @method TitleQuery rightJoinPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publication relation
 * @method TitleQuery innerJoinPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the Publication relation
 *
 * @method TitleQuery leftJoinVolume($relationAlias = null) Adds a LEFT JOIN clause to the query using the Volume relation
 * @method TitleQuery rightJoinVolume($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Volume relation
 * @method TitleQuery innerJoinVolume($relationAlias = null) Adds a INNER JOIN clause to the query using the Volume relation
 *
 * @method TitleQuery leftJoinWork($relationAlias = null) Adds a LEFT JOIN clause to the query using the Work relation
 * @method TitleQuery rightJoinWork($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Work relation
 * @method TitleQuery innerJoinWork($relationAlias = null) Adds a INNER JOIN clause to the query using the Work relation
 *
 * @method Title findOne(PropelPDO $con = null) Return the first Title matching the query
 * @method Title findOneOrCreate(PropelPDO $con = null) Return the first Title matching the query, or a new Title object populated from the query conditions when no match is found
 *
 * @method Title findOneBySequenceindex(int $sequenceIndex) Return the first Title filtered by the sequenceIndex column
 * @method Title findOneByTitle(string $title) Return the first Title filtered by the title column
 * @method Title findOneByWorkId(int $work_id) Return the first Title filtered by the work_id column
 * @method Title findOneByPublicationId(int $publication_id) Return the first Title filtered by the publication_id column
 * @method Title findOneByVolumeId(int $volume_id) Return the first Title filtered by the volume_id column
 * @method Title findOneByTitletypeId(int $titleType_id) Return the first Title filtered by the titleType_id column
 *
 * @method array findById(int $id) Return Title objects filtered by the id column
 * @method array findBySequenceindex(int $sequenceIndex) Return Title objects filtered by the sequenceIndex column
 * @method array findByTitle(string $title) Return Title objects filtered by the title column
 * @method array findByWorkId(int $work_id) Return Title objects filtered by the work_id column
 * @method array findByPublicationId(int $publication_id) Return Title objects filtered by the publication_id column
 * @method array findByVolumeId(int $volume_id) Return Title objects filtered by the volume_id column
 * @method array findByTitletypeId(int $titleType_id) Return Title objects filtered by the titleType_id column
 */
abstract class BaseTitleQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseTitleQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Description\\Title', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new TitleQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     TitleQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return TitleQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof TitleQuery) {
            return $criteria;
        }
        $query = new TitleQuery();
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
     * @return   Title|Title[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TitlePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(TitlePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Title A model object, or null if the key is not found
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
     * @return   Title A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `SEQUENCEINDEX`, `TITLE`, `WORK_ID`, `PUBLICATION_ID`, `VOLUME_ID`, `TITLETYPE_ID` FROM `title` WHERE `ID` = :p0';
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
            $obj = new Title();
            $obj->hydrate($row);
            TitlePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Title|Title[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Title[]|mixed the list of results, formatted by the current formatter
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
     * @return TitleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TitlePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return TitleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TitlePeer::ID, $keys, Criteria::IN);
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
     * @return TitleQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(TitlePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the sequenceIndex column
     *
     * Example usage:
     * <code>
     * $query->filterBySequenceindex(1234); // WHERE sequenceIndex = 1234
     * $query->filterBySequenceindex(array(12, 34)); // WHERE sequenceIndex IN (12, 34)
     * $query->filterBySequenceindex(array('min' => 12)); // WHERE sequenceIndex > 12
     * </code>
     *
     * @param     mixed $sequenceindex The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TitleQuery The current query, for fluid interface
     */
    public function filterBySequenceindex($sequenceindex = null, $comparison = null)
    {
        if (is_array($sequenceindex)) {
            $useMinMax = false;
            if (isset($sequenceindex['min'])) {
                $this->addUsingAlias(TitlePeer::SEQUENCEINDEX, $sequenceindex['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sequenceindex['max'])) {
                $this->addUsingAlias(TitlePeer::SEQUENCEINDEX, $sequenceindex['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TitlePeer::SEQUENCEINDEX, $sequenceindex, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TitleQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TitlePeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the work_id column
     *
     * Example usage:
     * <code>
     * $query->filterByWorkId(1234); // WHERE work_id = 1234
     * $query->filterByWorkId(array(12, 34)); // WHERE work_id IN (12, 34)
     * $query->filterByWorkId(array('min' => 12)); // WHERE work_id > 12
     * </code>
     *
     * @see       filterByWork()
     *
     * @param     mixed $workId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TitleQuery The current query, for fluid interface
     */
    public function filterByWorkId($workId = null, $comparison = null)
    {
        if (is_array($workId)) {
            $useMinMax = false;
            if (isset($workId['min'])) {
                $this->addUsingAlias(TitlePeer::WORK_ID, $workId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($workId['max'])) {
                $this->addUsingAlias(TitlePeer::WORK_ID, $workId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TitlePeer::WORK_ID, $workId, $comparison);
    }

    /**
     * Filter the query on the publication_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublicationId(1234); // WHERE publication_id = 1234
     * $query->filterByPublicationId(array(12, 34)); // WHERE publication_id IN (12, 34)
     * $query->filterByPublicationId(array('min' => 12)); // WHERE publication_id > 12
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
     * @return TitleQuery The current query, for fluid interface
     */
    public function filterByPublicationId($publicationId = null, $comparison = null)
    {
        if (is_array($publicationId)) {
            $useMinMax = false;
            if (isset($publicationId['min'])) {
                $this->addUsingAlias(TitlePeer::PUBLICATION_ID, $publicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publicationId['max'])) {
                $this->addUsingAlias(TitlePeer::PUBLICATION_ID, $publicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TitlePeer::PUBLICATION_ID, $publicationId, $comparison);
    }

    /**
     * Filter the query on the volume_id column
     *
     * Example usage:
     * <code>
     * $query->filterByVolumeId(1234); // WHERE volume_id = 1234
     * $query->filterByVolumeId(array(12, 34)); // WHERE volume_id IN (12, 34)
     * $query->filterByVolumeId(array('min' => 12)); // WHERE volume_id > 12
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
     * @return TitleQuery The current query, for fluid interface
     */
    public function filterByVolumeId($volumeId = null, $comparison = null)
    {
        if (is_array($volumeId)) {
            $useMinMax = false;
            if (isset($volumeId['min'])) {
                $this->addUsingAlias(TitlePeer::VOLUME_ID, $volumeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($volumeId['max'])) {
                $this->addUsingAlias(TitlePeer::VOLUME_ID, $volumeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TitlePeer::VOLUME_ID, $volumeId, $comparison);
    }

    /**
     * Filter the query on the titleType_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTitletypeId(1234); // WHERE titleType_id = 1234
     * $query->filterByTitletypeId(array(12, 34)); // WHERE titleType_id IN (12, 34)
     * $query->filterByTitletypeId(array('min' => 12)); // WHERE titleType_id > 12
     * </code>
     *
     * @see       filterByTitletype()
     *
     * @param     mixed $titletypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TitleQuery The current query, for fluid interface
     */
    public function filterByTitletypeId($titletypeId = null, $comparison = null)
    {
        if (is_array($titletypeId)) {
            $useMinMax = false;
            if (isset($titletypeId['min'])) {
                $this->addUsingAlias(TitlePeer::TITLETYPE_ID, $titletypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($titletypeId['max'])) {
                $this->addUsingAlias(TitlePeer::TITLETYPE_ID, $titletypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TitlePeer::TITLETYPE_ID, $titletypeId, $comparison);
    }

    /**
     * Filter the query by a related Titletype object
     *
     * @param   Titletype|PropelObjectCollection $titletype The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   TitleQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByTitletype($titletype, $comparison = null)
    {
        if ($titletype instanceof Titletype) {
            return $this
                ->addUsingAlias(TitlePeer::TITLETYPE_ID, $titletype->getId(), $comparison);
        } elseif ($titletype instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TitlePeer::TITLETYPE_ID, $titletype->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTitletype() only accepts arguments of type Titletype or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Titletype relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return TitleQuery The current query, for fluid interface
     */
    public function joinTitletype($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Titletype');

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
            $this->addJoinObject($join, 'Titletype');
        }

        return $this;
    }

    /**
     * Use the Titletype relation Titletype object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Description\TitletypeQuery A secondary query class using the current class as primary query
     */
    public function useTitletypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTitletype($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Titletype', '\DTA\MetadataBundle\Model\Description\TitletypeQuery');
    }

    /**
     * Filter the query by a related Publication object
     *
     * @param   Publication|PropelObjectCollection $publication The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   TitleQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByPublication($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(TitlePeer::PUBLICATION_ID, $publication->getId(), $comparison);
        } elseif ($publication instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TitlePeer::PUBLICATION_ID, $publication->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return TitleQuery The current query, for fluid interface
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
     * @return   \DTA\MetadataBundle\Model\Publication\PublicationQuery A secondary query class using the current class as primary query
     */
    public function usePublicationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPublication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publication', '\DTA\MetadataBundle\Model\Publication\PublicationQuery');
    }

    /**
     * Filter the query by a related Volume object
     *
     * @param   Volume|PropelObjectCollection $volume The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   TitleQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByVolume($volume, $comparison = null)
    {
        if ($volume instanceof Volume) {
            return $this
                ->addUsingAlias(TitlePeer::VOLUME_ID, $volume->getId(), $comparison);
        } elseif ($volume instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TitlePeer::VOLUME_ID, $volume->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return TitleQuery The current query, for fluid interface
     */
    public function joinVolume($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @return   \DTA\MetadataBundle\Model\Publication\VolumeQuery A secondary query class using the current class as primary query
     */
    public function useVolumeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinVolume($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Volume', '\DTA\MetadataBundle\Model\Publication\VolumeQuery');
    }

    /**
     * Filter the query by a related Work object
     *
     * @param   Work|PropelObjectCollection $work The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   TitleQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByWork($work, $comparison = null)
    {
        if ($work instanceof Work) {
            return $this
                ->addUsingAlias(TitlePeer::WORK_ID, $work->getId(), $comparison);
        } elseif ($work instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TitlePeer::WORK_ID, $work->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByWork() only accepts arguments of type Work or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Work relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return TitleQuery The current query, for fluid interface
     */
    public function joinWork($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Work');

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
            $this->addJoinObject($join, 'Work');
        }

        return $this;
    }

    /**
     * Use the Work relation Work object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Publication\WorkQuery A secondary query class using the current class as primary query
     */
    public function useWorkQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinWork($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Work', '\DTA\MetadataBundle\Model\Publication\WorkQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Title $title Object to remove from the list of results
     *
     * @return TitleQuery The current query, for fluid interface
     */
    public function prune($title = null)
    {
        if ($title) {
            $this->addUsingAlias(TitlePeer::ID, $title->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
