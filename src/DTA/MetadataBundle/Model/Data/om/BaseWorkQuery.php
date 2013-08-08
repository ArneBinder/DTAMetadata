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
use DTA\MetadataBundle\Model\Classification\Category;
use DTA\MetadataBundle\Model\Classification\Genre;
use DTA\MetadataBundle\Model\Classification\Tag;
use DTA\MetadataBundle\Model\Data\Datespecification;
use DTA\MetadataBundle\Model\Data\Language;
use DTA\MetadataBundle\Model\Data\Publication;
use DTA\MetadataBundle\Model\Data\Title;
use DTA\MetadataBundle\Model\Data\Work;
use DTA\MetadataBundle\Model\Data\WorkPeer;
use DTA\MetadataBundle\Model\Data\WorkQuery;
use DTA\MetadataBundle\Model\Master\CategoryWork;
use DTA\MetadataBundle\Model\Master\GenreWork;
use DTA\MetadataBundle\Model\Master\LanguageWork;
use DTA\MetadataBundle\Model\Master\PersonWork;
use DTA\MetadataBundle\Model\Master\WorkTag;

/**
 * @method WorkQuery orderById($order = Criteria::ASC) Order by the id column
 * @method WorkQuery orderByTitleId($order = Criteria::ASC) Order by the title_id column
 * @method WorkQuery orderByDatespecificationId($order = Criteria::ASC) Order by the datespecification_id column
 * @method WorkQuery orderByDoi($order = Criteria::ASC) Order by the doi column
 * @method WorkQuery orderByComments($order = Criteria::ASC) Order by the comments column
 * @method WorkQuery orderByFormat($order = Criteria::ASC) Order by the format column
 * @method WorkQuery orderByDirectoryname($order = Criteria::ASC) Order by the directoryname column
 *
 * @method WorkQuery groupById() Group by the id column
 * @method WorkQuery groupByTitleId() Group by the title_id column
 * @method WorkQuery groupByDatespecificationId() Group by the datespecification_id column
 * @method WorkQuery groupByDoi() Group by the doi column
 * @method WorkQuery groupByComments() Group by the comments column
 * @method WorkQuery groupByFormat() Group by the format column
 * @method WorkQuery groupByDirectoryname() Group by the directoryname column
 *
 * @method WorkQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method WorkQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method WorkQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method WorkQuery leftJoinTitle($relationAlias = null) Adds a LEFT JOIN clause to the query using the Title relation
 * @method WorkQuery rightJoinTitle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Title relation
 * @method WorkQuery innerJoinTitle($relationAlias = null) Adds a INNER JOIN clause to the query using the Title relation
 *
 * @method WorkQuery leftJoinDatespecification($relationAlias = null) Adds a LEFT JOIN clause to the query using the Datespecification relation
 * @method WorkQuery rightJoinDatespecification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Datespecification relation
 * @method WorkQuery innerJoinDatespecification($relationAlias = null) Adds a INNER JOIN clause to the query using the Datespecification relation
 *
 * @method WorkQuery leftJoinPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publication relation
 * @method WorkQuery rightJoinPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publication relation
 * @method WorkQuery innerJoinPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the Publication relation
 *
 * @method WorkQuery leftJoinLanguageWork($relationAlias = null) Adds a LEFT JOIN clause to the query using the LanguageWork relation
 * @method WorkQuery rightJoinLanguageWork($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LanguageWork relation
 * @method WorkQuery innerJoinLanguageWork($relationAlias = null) Adds a INNER JOIN clause to the query using the LanguageWork relation
 *
 * @method WorkQuery leftJoinGenreWork($relationAlias = null) Adds a LEFT JOIN clause to the query using the GenreWork relation
 * @method WorkQuery rightJoinGenreWork($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GenreWork relation
 * @method WorkQuery innerJoinGenreWork($relationAlias = null) Adds a INNER JOIN clause to the query using the GenreWork relation
 *
 * @method WorkQuery leftJoinWorkTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the WorkTag relation
 * @method WorkQuery rightJoinWorkTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WorkTag relation
 * @method WorkQuery innerJoinWorkTag($relationAlias = null) Adds a INNER JOIN clause to the query using the WorkTag relation
 *
 * @method WorkQuery leftJoinCategoryWork($relationAlias = null) Adds a LEFT JOIN clause to the query using the CategoryWork relation
 * @method WorkQuery rightJoinCategoryWork($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CategoryWork relation
 * @method WorkQuery innerJoinCategoryWork($relationAlias = null) Adds a INNER JOIN clause to the query using the CategoryWork relation
 *
 * @method WorkQuery leftJoinPersonWork($relationAlias = null) Adds a LEFT JOIN clause to the query using the PersonWork relation
 * @method WorkQuery rightJoinPersonWork($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PersonWork relation
 * @method WorkQuery innerJoinPersonWork($relationAlias = null) Adds a INNER JOIN clause to the query using the PersonWork relation
 *
 * @method Work findOne(PropelPDO $con = null) Return the first Work matching the query
 * @method Work findOneOrCreate(PropelPDO $con = null) Return the first Work matching the query, or a new Work object populated from the query conditions when no match is found
 *
 * @method Work findOneByTitleId(int $title_id) Return the first Work filtered by the title_id column
 * @method Work findOneByDatespecificationId(int $datespecification_id) Return the first Work filtered by the datespecification_id column
 * @method Work findOneByDoi(string $doi) Return the first Work filtered by the doi column
 * @method Work findOneByComments(string $comments) Return the first Work filtered by the comments column
 * @method Work findOneByFormat(string $format) Return the first Work filtered by the format column
 * @method Work findOneByDirectoryname(string $directoryname) Return the first Work filtered by the directoryname column
 *
 * @method array findById(int $id) Return Work objects filtered by the id column
 * @method array findByTitleId(int $title_id) Return Work objects filtered by the title_id column
 * @method array findByDatespecificationId(int $datespecification_id) Return Work objects filtered by the datespecification_id column
 * @method array findByDoi(string $doi) Return Work objects filtered by the doi column
 * @method array findByComments(string $comments) Return Work objects filtered by the comments column
 * @method array findByFormat(string $format) Return Work objects filtered by the format column
 * @method array findByDirectoryname(string $directoryname) Return Work objects filtered by the directoryname column
 */
abstract class BaseWorkQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseWorkQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Data\\Work', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new WorkQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   WorkQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return WorkQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof WorkQuery) {
            return $criteria;
        }
        $query = new WorkQuery();
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
     * @return   Work|Work[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = WorkPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Work A model object, or null if the key is not found
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
     * @return                 Work A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "id", "title_id", "datespecification_id", "doi", "comments", "format", "directoryname" FROM "work" WHERE "id" = :p0';
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
            $obj = new Work();
            $obj->hydrate($row);
            WorkPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Work|Work[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Work[]|mixed the list of results, formatted by the current formatter
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
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(WorkPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(WorkPeer::ID, $keys, Criteria::IN);
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
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(WorkPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(WorkPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WorkPeer::ID, $id, $comparison);
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
     * @see       filterByTitle()
     *
     * @param     mixed $titleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByTitleId($titleId = null, $comparison = null)
    {
        if (is_array($titleId)) {
            $useMinMax = false;
            if (isset($titleId['min'])) {
                $this->addUsingAlias(WorkPeer::TITLE_ID, $titleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($titleId['max'])) {
                $this->addUsingAlias(WorkPeer::TITLE_ID, $titleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WorkPeer::TITLE_ID, $titleId, $comparison);
    }

    /**
     * Filter the query on the datespecification_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDatespecificationId(1234); // WHERE datespecification_id = 1234
     * $query->filterByDatespecificationId(array(12, 34)); // WHERE datespecification_id IN (12, 34)
     * $query->filterByDatespecificationId(array('min' => 12)); // WHERE datespecification_id >= 12
     * $query->filterByDatespecificationId(array('max' => 12)); // WHERE datespecification_id <= 12
     * </code>
     *
     * @see       filterByDatespecification()
     *
     * @param     mixed $datespecificationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByDatespecificationId($datespecificationId = null, $comparison = null)
    {
        if (is_array($datespecificationId)) {
            $useMinMax = false;
            if (isset($datespecificationId['min'])) {
                $this->addUsingAlias(WorkPeer::DATESPECIFICATION_ID, $datespecificationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($datespecificationId['max'])) {
                $this->addUsingAlias(WorkPeer::DATESPECIFICATION_ID, $datespecificationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WorkPeer::DATESPECIFICATION_ID, $datespecificationId, $comparison);
    }

    /**
     * Filter the query on the doi column
     *
     * Example usage:
     * <code>
     * $query->filterByDoi('fooValue');   // WHERE doi = 'fooValue'
     * $query->filterByDoi('%fooValue%'); // WHERE doi LIKE '%fooValue%'
     * </code>
     *
     * @param     string $doi The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByDoi($doi = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($doi)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $doi)) {
                $doi = str_replace('*', '%', $doi);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(WorkPeer::DOI, $doi, $comparison);
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
     * @return WorkQuery The current query, for fluid interface
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

        return $this->addUsingAlias(WorkPeer::COMMENTS, $comments, $comparison);
    }

    /**
     * Filter the query on the format column
     *
     * Example usage:
     * <code>
     * $query->filterByFormat('fooValue');   // WHERE format = 'fooValue'
     * $query->filterByFormat('%fooValue%'); // WHERE format LIKE '%fooValue%'
     * </code>
     *
     * @param     string $format The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByFormat($format = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($format)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $format)) {
                $format = str_replace('*', '%', $format);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(WorkPeer::FORMAT, $format, $comparison);
    }

    /**
     * Filter the query on the directoryname column
     *
     * Example usage:
     * <code>
     * $query->filterByDirectoryname('fooValue');   // WHERE directoryname = 'fooValue'
     * $query->filterByDirectoryname('%fooValue%'); // WHERE directoryname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $directoryname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function filterByDirectoryname($directoryname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($directoryname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $directoryname)) {
                $directoryname = str_replace('*', '%', $directoryname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(WorkPeer::DIRECTORYNAME, $directoryname, $comparison);
    }

    /**
     * Filter the query by a related Title object
     *
     * @param   Title|PropelObjectCollection $title The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTitle($title, $comparison = null)
    {
        if ($title instanceof Title) {
            return $this
                ->addUsingAlias(WorkPeer::TITLE_ID, $title->getId(), $comparison);
        } elseif ($title instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WorkPeer::TITLE_ID, $title->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTitle() only accepts arguments of type Title or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Title relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinTitle($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Title');

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
            $this->addJoinObject($join, 'Title');
        }

        return $this;
    }

    /**
     * Use the Title relation Title object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\TitleQuery A secondary query class using the current class as primary query
     */
    public function useTitleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTitle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Title', '\DTA\MetadataBundle\Model\Data\TitleQuery');
    }

    /**
     * Filter the query by a related Datespecification object
     *
     * @param   Datespecification|PropelObjectCollection $datespecification The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDatespecification($datespecification, $comparison = null)
    {
        if ($datespecification instanceof Datespecification) {
            return $this
                ->addUsingAlias(WorkPeer::DATESPECIFICATION_ID, $datespecification->getId(), $comparison);
        } elseif ($datespecification instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WorkPeer::DATESPECIFICATION_ID, $datespecification->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDatespecification() only accepts arguments of type Datespecification or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Datespecification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinDatespecification($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Datespecification');

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
            $this->addJoinObject($join, 'Datespecification');
        }

        return $this;
    }

    /**
     * Use the Datespecification relation Datespecification object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\DatespecificationQuery A secondary query class using the current class as primary query
     */
    public function useDatespecificationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDatespecification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Datespecification', '\DTA\MetadataBundle\Model\Data\DatespecificationQuery');
    }

    /**
     * Filter the query by a related Publication object
     *
     * @param   Publication|PropelObjectCollection $publication  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublication($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(WorkPeer::ID, $publication->getWorkId(), $comparison);
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
     * @return WorkQuery The current query, for fluid interface
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
     * Filter the query by a related LanguageWork object
     *
     * @param   LanguageWork|PropelObjectCollection $languageWork  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByLanguageWork($languageWork, $comparison = null)
    {
        if ($languageWork instanceof LanguageWork) {
            return $this
                ->addUsingAlias(WorkPeer::ID, $languageWork->getWorkId(), $comparison);
        } elseif ($languageWork instanceof PropelObjectCollection) {
            return $this
                ->useLanguageWorkQuery()
                ->filterByPrimaryKeys($languageWork->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLanguageWork() only accepts arguments of type LanguageWork or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LanguageWork relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinLanguageWork($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LanguageWork');

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
            $this->addJoinObject($join, 'LanguageWork');
        }

        return $this;
    }

    /**
     * Use the LanguageWork relation LanguageWork object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\LanguageWorkQuery A secondary query class using the current class as primary query
     */
    public function useLanguageWorkQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLanguageWork($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LanguageWork', '\DTA\MetadataBundle\Model\Master\LanguageWorkQuery');
    }

    /**
     * Filter the query by a related GenreWork object
     *
     * @param   GenreWork|PropelObjectCollection $genreWork  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByGenreWork($genreWork, $comparison = null)
    {
        if ($genreWork instanceof GenreWork) {
            return $this
                ->addUsingAlias(WorkPeer::ID, $genreWork->getWorkId(), $comparison);
        } elseif ($genreWork instanceof PropelObjectCollection) {
            return $this
                ->useGenreWorkQuery()
                ->filterByPrimaryKeys($genreWork->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByGenreWork() only accepts arguments of type GenreWork or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GenreWork relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinGenreWork($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GenreWork');

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
            $this->addJoinObject($join, 'GenreWork');
        }

        return $this;
    }

    /**
     * Use the GenreWork relation GenreWork object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\GenreWorkQuery A secondary query class using the current class as primary query
     */
    public function useGenreWorkQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGenreWork($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GenreWork', '\DTA\MetadataBundle\Model\Master\GenreWorkQuery');
    }

    /**
     * Filter the query by a related WorkTag object
     *
     * @param   WorkTag|PropelObjectCollection $workTag  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByWorkTag($workTag, $comparison = null)
    {
        if ($workTag instanceof WorkTag) {
            return $this
                ->addUsingAlias(WorkPeer::ID, $workTag->getWorkId(), $comparison);
        } elseif ($workTag instanceof PropelObjectCollection) {
            return $this
                ->useWorkTagQuery()
                ->filterByPrimaryKeys($workTag->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByWorkTag() only accepts arguments of type WorkTag or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the WorkTag relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinWorkTag($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('WorkTag');

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
            $this->addJoinObject($join, 'WorkTag');
        }

        return $this;
    }

    /**
     * Use the WorkTag relation WorkTag object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\WorkTagQuery A secondary query class using the current class as primary query
     */
    public function useWorkTagQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWorkTag($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'WorkTag', '\DTA\MetadataBundle\Model\Master\WorkTagQuery');
    }

    /**
     * Filter the query by a related CategoryWork object
     *
     * @param   CategoryWork|PropelObjectCollection $categoryWork  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCategoryWork($categoryWork, $comparison = null)
    {
        if ($categoryWork instanceof CategoryWork) {
            return $this
                ->addUsingAlias(WorkPeer::ID, $categoryWork->getWorkId(), $comparison);
        } elseif ($categoryWork instanceof PropelObjectCollection) {
            return $this
                ->useCategoryWorkQuery()
                ->filterByPrimaryKeys($categoryWork->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCategoryWork() only accepts arguments of type CategoryWork or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CategoryWork relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinCategoryWork($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CategoryWork');

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
            $this->addJoinObject($join, 'CategoryWork');
        }

        return $this;
    }

    /**
     * Use the CategoryWork relation CategoryWork object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\CategoryWorkQuery A secondary query class using the current class as primary query
     */
    public function useCategoryWorkQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCategoryWork($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CategoryWork', '\DTA\MetadataBundle\Model\Master\CategoryWorkQuery');
    }

    /**
     * Filter the query by a related PersonWork object
     *
     * @param   PersonWork|PropelObjectCollection $personWork  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WorkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPersonWork($personWork, $comparison = null)
    {
        if ($personWork instanceof PersonWork) {
            return $this
                ->addUsingAlias(WorkPeer::ID, $personWork->getWorkId(), $comparison);
        } elseif ($personWork instanceof PropelObjectCollection) {
            return $this
                ->usePersonWorkQuery()
                ->filterByPrimaryKeys($personWork->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPersonWork() only accepts arguments of type PersonWork or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PersonWork relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function joinPersonWork($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PersonWork');

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
            $this->addJoinObject($join, 'PersonWork');
        }

        return $this;
    }

    /**
     * Use the PersonWork relation PersonWork object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\PersonWorkQuery A secondary query class using the current class as primary query
     */
    public function usePersonWorkQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPersonWork($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PersonWork', '\DTA\MetadataBundle\Model\Master\PersonWorkQuery');
    }

    /**
     * Filter the query by a related Language object
     * using the language_work table as cross reference
     *
     * @param   Language $language the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   WorkQuery The current query, for fluid interface
     */
    public function filterByLanguage($language, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useLanguageWorkQuery()
            ->filterByLanguage($language, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Genre object
     * using the genre_work table as cross reference
     *
     * @param   Genre $genre the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   WorkQuery The current query, for fluid interface
     */
    public function filterByGenre($genre, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useGenreWorkQuery()
            ->filterByGenre($genre, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Tag object
     * using the work_tag table as cross reference
     *
     * @param   Tag $tag the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   WorkQuery The current query, for fluid interface
     */
    public function filterByTag($tag, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useWorkTagQuery()
            ->filterByTag($tag, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Category object
     * using the category_work table as cross reference
     *
     * @param   Category $category the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   WorkQuery The current query, for fluid interface
     */
    public function filterByCategory($category, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCategoryWorkQuery()
            ->filterByCategory($category, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   Work $work Object to remove from the list of results
     *
     * @return WorkQuery The current query, for fluid interface
     */
    public function prune($work = null)
    {
        if ($work) {
            $this->addUsingAlias(WorkPeer::ID, $work->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
