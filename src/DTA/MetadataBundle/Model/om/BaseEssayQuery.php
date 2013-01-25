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
use DTA\MetadataBundle\Model\Datespecification;
use DTA\MetadataBundle\Model\Essay;
use DTA\MetadataBundle\Model\EssayPeer;
use DTA\MetadataBundle\Model\EssayQuery;
use DTA\MetadataBundle\Model\Place;
use DTA\MetadataBundle\Model\Publishingcompany;
use DTA\MetadataBundle\Model\Title;

/**
 * @method EssayQuery orderById($order = Criteria::ASC) Order by the id column
 * @method EssayQuery orderByTitleId($order = Criteria::ASC) Order by the title_id column
 * @method EssayQuery orderByPublishingcompanyId($order = Criteria::ASC) Order by the publishingCompany_id column
 * @method EssayQuery orderByPlaceId($order = Criteria::ASC) Order by the place_id column
 * @method EssayQuery orderByDatespecificationId($order = Criteria::ASC) Order by the dateSpecification_id column
 * @method EssayQuery orderByPrintrun($order = Criteria::ASC) Order by the printRun column
 * @method EssayQuery orderByPrintruncomment($order = Criteria::ASC) Order by the printRunComment column
 * @method EssayQuery orderByEdition($order = Criteria::ASC) Order by the edition column
 * @method EssayQuery orderByNumpages($order = Criteria::ASC) Order by the numPages column
 * @method EssayQuery orderByNumpagesnormed($order = Criteria::ASC) Order by the numPagesNormed column
 * @method EssayQuery orderByBibliographiccitation($order = Criteria::ASC) Order by the bibliographicCitation column
 *
 * @method EssayQuery groupById() Group by the id column
 * @method EssayQuery groupByTitleId() Group by the title_id column
 * @method EssayQuery groupByPublishingcompanyId() Group by the publishingCompany_id column
 * @method EssayQuery groupByPlaceId() Group by the place_id column
 * @method EssayQuery groupByDatespecificationId() Group by the dateSpecification_id column
 * @method EssayQuery groupByPrintrun() Group by the printRun column
 * @method EssayQuery groupByPrintruncomment() Group by the printRunComment column
 * @method EssayQuery groupByEdition() Group by the edition column
 * @method EssayQuery groupByNumpages() Group by the numPages column
 * @method EssayQuery groupByNumpagesnormed() Group by the numPagesNormed column
 * @method EssayQuery groupByBibliographiccitation() Group by the bibliographicCitation column
 *
 * @method EssayQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EssayQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EssayQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EssayQuery leftJoinTitle($relationAlias = null) Adds a LEFT JOIN clause to the query using the Title relation
 * @method EssayQuery rightJoinTitle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Title relation
 * @method EssayQuery innerJoinTitle($relationAlias = null) Adds a INNER JOIN clause to the query using the Title relation
 *
 * @method EssayQuery leftJoinPublishingcompany($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publishingcompany relation
 * @method EssayQuery rightJoinPublishingcompany($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publishingcompany relation
 * @method EssayQuery innerJoinPublishingcompany($relationAlias = null) Adds a INNER JOIN clause to the query using the Publishingcompany relation
 *
 * @method EssayQuery leftJoinPlace($relationAlias = null) Adds a LEFT JOIN clause to the query using the Place relation
 * @method EssayQuery rightJoinPlace($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Place relation
 * @method EssayQuery innerJoinPlace($relationAlias = null) Adds a INNER JOIN clause to the query using the Place relation
 *
 * @method EssayQuery leftJoinDatespecification($relationAlias = null) Adds a LEFT JOIN clause to the query using the Datespecification relation
 * @method EssayQuery rightJoinDatespecification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Datespecification relation
 * @method EssayQuery innerJoinDatespecification($relationAlias = null) Adds a INNER JOIN clause to the query using the Datespecification relation
 *
 * @method Essay findOne(PropelPDO $con = null) Return the first Essay matching the query
 * @method Essay findOneOrCreate(PropelPDO $con = null) Return the first Essay matching the query, or a new Essay object populated from the query conditions when no match is found
 *
 * @method Essay findOneByTitleId(int $title_id) Return the first Essay filtered by the title_id column
 * @method Essay findOneByPublishingcompanyId(int $publishingCompany_id) Return the first Essay filtered by the publishingCompany_id column
 * @method Essay findOneByPlaceId(int $place_id) Return the first Essay filtered by the place_id column
 * @method Essay findOneByDatespecificationId(int $dateSpecification_id) Return the first Essay filtered by the dateSpecification_id column
 * @method Essay findOneByPrintrun(string $printRun) Return the first Essay filtered by the printRun column
 * @method Essay findOneByPrintruncomment(string $printRunComment) Return the first Essay filtered by the printRunComment column
 * @method Essay findOneByEdition(string $edition) Return the first Essay filtered by the edition column
 * @method Essay findOneByNumpages(int $numPages) Return the first Essay filtered by the numPages column
 * @method Essay findOneByNumpagesnormed(int $numPagesNormed) Return the first Essay filtered by the numPagesNormed column
 * @method Essay findOneByBibliographiccitation(string $bibliographicCitation) Return the first Essay filtered by the bibliographicCitation column
 *
 * @method array findById(int $id) Return Essay objects filtered by the id column
 * @method array findByTitleId(int $title_id) Return Essay objects filtered by the title_id column
 * @method array findByPublishingcompanyId(int $publishingCompany_id) Return Essay objects filtered by the publishingCompany_id column
 * @method array findByPlaceId(int $place_id) Return Essay objects filtered by the place_id column
 * @method array findByDatespecificationId(int $dateSpecification_id) Return Essay objects filtered by the dateSpecification_id column
 * @method array findByPrintrun(string $printRun) Return Essay objects filtered by the printRun column
 * @method array findByPrintruncomment(string $printRunComment) Return Essay objects filtered by the printRunComment column
 * @method array findByEdition(string $edition) Return Essay objects filtered by the edition column
 * @method array findByNumpages(int $numPages) Return Essay objects filtered by the numPages column
 * @method array findByNumpagesnormed(int $numPagesNormed) Return Essay objects filtered by the numPagesNormed column
 * @method array findByBibliographiccitation(string $bibliographicCitation) Return Essay objects filtered by the bibliographicCitation column
 */
abstract class BaseEssayQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEssayQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Essay', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EssayQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   EssayQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EssayQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EssayQuery) {
            return $criteria;
        }
        $query = new EssayQuery();
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
     * @return   Essay|Essay[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EssayPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EssayPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Essay A model object, or null if the key is not found
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
     * @return                 Essay A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `title_id`, `publishingCompany_id`, `place_id`, `dateSpecification_id`, `printRun`, `printRunComment`, `edition`, `numPages`, `numPagesNormed`, `bibliographicCitation` FROM `essay` WHERE `id` = :p0';
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
            $obj = new Essay();
            $obj->hydrate($row);
            EssayPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Essay|Essay[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Essay[]|mixed the list of results, formatted by the current formatter
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
     * @return EssayQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EssayPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EssayQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EssayPeer::ID, $keys, Criteria::IN);
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
     * @return EssayQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EssayPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EssayPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EssayPeer::ID, $id, $comparison);
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
     * @return EssayQuery The current query, for fluid interface
     */
    public function filterByTitleId($titleId = null, $comparison = null)
    {
        if (is_array($titleId)) {
            $useMinMax = false;
            if (isset($titleId['min'])) {
                $this->addUsingAlias(EssayPeer::TITLE_ID, $titleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($titleId['max'])) {
                $this->addUsingAlias(EssayPeer::TITLE_ID, $titleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EssayPeer::TITLE_ID, $titleId, $comparison);
    }

    /**
     * Filter the query on the publishingCompany_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublishingcompanyId(1234); // WHERE publishingCompany_id = 1234
     * $query->filterByPublishingcompanyId(array(12, 34)); // WHERE publishingCompany_id IN (12, 34)
     * $query->filterByPublishingcompanyId(array('min' => 12)); // WHERE publishingCompany_id >= 12
     * $query->filterByPublishingcompanyId(array('max' => 12)); // WHERE publishingCompany_id <= 12
     * </code>
     *
     * @see       filterByPublishingcompany()
     *
     * @param     mixed $publishingcompanyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EssayQuery The current query, for fluid interface
     */
    public function filterByPublishingcompanyId($publishingcompanyId = null, $comparison = null)
    {
        if (is_array($publishingcompanyId)) {
            $useMinMax = false;
            if (isset($publishingcompanyId['min'])) {
                $this->addUsingAlias(EssayPeer::PUBLISHINGCOMPANY_ID, $publishingcompanyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publishingcompanyId['max'])) {
                $this->addUsingAlias(EssayPeer::PUBLISHINGCOMPANY_ID, $publishingcompanyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EssayPeer::PUBLISHINGCOMPANY_ID, $publishingcompanyId, $comparison);
    }

    /**
     * Filter the query on the place_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPlaceId(1234); // WHERE place_id = 1234
     * $query->filterByPlaceId(array(12, 34)); // WHERE place_id IN (12, 34)
     * $query->filterByPlaceId(array('min' => 12)); // WHERE place_id >= 12
     * $query->filterByPlaceId(array('max' => 12)); // WHERE place_id <= 12
     * </code>
     *
     * @see       filterByPlace()
     *
     * @param     mixed $placeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EssayQuery The current query, for fluid interface
     */
    public function filterByPlaceId($placeId = null, $comparison = null)
    {
        if (is_array($placeId)) {
            $useMinMax = false;
            if (isset($placeId['min'])) {
                $this->addUsingAlias(EssayPeer::PLACE_ID, $placeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($placeId['max'])) {
                $this->addUsingAlias(EssayPeer::PLACE_ID, $placeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EssayPeer::PLACE_ID, $placeId, $comparison);
    }

    /**
     * Filter the query on the dateSpecification_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDatespecificationId(1234); // WHERE dateSpecification_id = 1234
     * $query->filterByDatespecificationId(array(12, 34)); // WHERE dateSpecification_id IN (12, 34)
     * $query->filterByDatespecificationId(array('min' => 12)); // WHERE dateSpecification_id >= 12
     * $query->filterByDatespecificationId(array('max' => 12)); // WHERE dateSpecification_id <= 12
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
     * @return EssayQuery The current query, for fluid interface
     */
    public function filterByDatespecificationId($datespecificationId = null, $comparison = null)
    {
        if (is_array($datespecificationId)) {
            $useMinMax = false;
            if (isset($datespecificationId['min'])) {
                $this->addUsingAlias(EssayPeer::DATESPECIFICATION_ID, $datespecificationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($datespecificationId['max'])) {
                $this->addUsingAlias(EssayPeer::DATESPECIFICATION_ID, $datespecificationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EssayPeer::DATESPECIFICATION_ID, $datespecificationId, $comparison);
    }

    /**
     * Filter the query on the printRun column
     *
     * Example usage:
     * <code>
     * $query->filterByPrintrun('fooValue');   // WHERE printRun = 'fooValue'
     * $query->filterByPrintrun('%fooValue%'); // WHERE printRun LIKE '%fooValue%'
     * </code>
     *
     * @param     string $printrun The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EssayQuery The current query, for fluid interface
     */
    public function filterByPrintrun($printrun = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($printrun)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $printrun)) {
                $printrun = str_replace('*', '%', $printrun);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EssayPeer::PRINTRUN, $printrun, $comparison);
    }

    /**
     * Filter the query on the printRunComment column
     *
     * Example usage:
     * <code>
     * $query->filterByPrintruncomment('fooValue');   // WHERE printRunComment = 'fooValue'
     * $query->filterByPrintruncomment('%fooValue%'); // WHERE printRunComment LIKE '%fooValue%'
     * </code>
     *
     * @param     string $printruncomment The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EssayQuery The current query, for fluid interface
     */
    public function filterByPrintruncomment($printruncomment = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($printruncomment)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $printruncomment)) {
                $printruncomment = str_replace('*', '%', $printruncomment);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EssayPeer::PRINTRUNCOMMENT, $printruncomment, $comparison);
    }

    /**
     * Filter the query on the edition column
     *
     * Example usage:
     * <code>
     * $query->filterByEdition('fooValue');   // WHERE edition = 'fooValue'
     * $query->filterByEdition('%fooValue%'); // WHERE edition LIKE '%fooValue%'
     * </code>
     *
     * @param     string $edition The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EssayQuery The current query, for fluid interface
     */
    public function filterByEdition($edition = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($edition)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $edition)) {
                $edition = str_replace('*', '%', $edition);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EssayPeer::EDITION, $edition, $comparison);
    }

    /**
     * Filter the query on the numPages column
     *
     * Example usage:
     * <code>
     * $query->filterByNumpages(1234); // WHERE numPages = 1234
     * $query->filterByNumpages(array(12, 34)); // WHERE numPages IN (12, 34)
     * $query->filterByNumpages(array('min' => 12)); // WHERE numPages >= 12
     * $query->filterByNumpages(array('max' => 12)); // WHERE numPages <= 12
     * </code>
     *
     * @param     mixed $numpages The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EssayQuery The current query, for fluid interface
     */
    public function filterByNumpages($numpages = null, $comparison = null)
    {
        if (is_array($numpages)) {
            $useMinMax = false;
            if (isset($numpages['min'])) {
                $this->addUsingAlias(EssayPeer::NUMPAGES, $numpages['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numpages['max'])) {
                $this->addUsingAlias(EssayPeer::NUMPAGES, $numpages['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EssayPeer::NUMPAGES, $numpages, $comparison);
    }

    /**
     * Filter the query on the numPagesNormed column
     *
     * Example usage:
     * <code>
     * $query->filterByNumpagesnormed(1234); // WHERE numPagesNormed = 1234
     * $query->filterByNumpagesnormed(array(12, 34)); // WHERE numPagesNormed IN (12, 34)
     * $query->filterByNumpagesnormed(array('min' => 12)); // WHERE numPagesNormed >= 12
     * $query->filterByNumpagesnormed(array('max' => 12)); // WHERE numPagesNormed <= 12
     * </code>
     *
     * @param     mixed $numpagesnormed The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EssayQuery The current query, for fluid interface
     */
    public function filterByNumpagesnormed($numpagesnormed = null, $comparison = null)
    {
        if (is_array($numpagesnormed)) {
            $useMinMax = false;
            if (isset($numpagesnormed['min'])) {
                $this->addUsingAlias(EssayPeer::NUMPAGESNORMED, $numpagesnormed['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numpagesnormed['max'])) {
                $this->addUsingAlias(EssayPeer::NUMPAGESNORMED, $numpagesnormed['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EssayPeer::NUMPAGESNORMED, $numpagesnormed, $comparison);
    }

    /**
     * Filter the query on the bibliographicCitation column
     *
     * Example usage:
     * <code>
     * $query->filterByBibliographiccitation('fooValue');   // WHERE bibliographicCitation = 'fooValue'
     * $query->filterByBibliographiccitation('%fooValue%'); // WHERE bibliographicCitation LIKE '%fooValue%'
     * </code>
     *
     * @param     string $bibliographiccitation The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EssayQuery The current query, for fluid interface
     */
    public function filterByBibliographiccitation($bibliographiccitation = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bibliographiccitation)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $bibliographiccitation)) {
                $bibliographiccitation = str_replace('*', '%', $bibliographiccitation);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EssayPeer::BIBLIOGRAPHICCITATION, $bibliographiccitation, $comparison);
    }

    /**
     * Filter the query by a related Title object
     *
     * @param   Title|PropelObjectCollection $title The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EssayQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTitle($title, $comparison = null)
    {
        if ($title instanceof Title) {
            return $this
                ->addUsingAlias(EssayPeer::TITLE_ID, $title->getId(), $comparison);
        } elseif ($title instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EssayPeer::TITLE_ID, $title->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return EssayQuery The current query, for fluid interface
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
     * @return   \DTA\MetadataBundle\Model\TitleQuery A secondary query class using the current class as primary query
     */
    public function useTitleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTitle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Title', '\DTA\MetadataBundle\Model\TitleQuery');
    }

    /**
     * Filter the query by a related Publishingcompany object
     *
     * @param   Publishingcompany|PropelObjectCollection $publishingcompany The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EssayQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublishingcompany($publishingcompany, $comparison = null)
    {
        if ($publishingcompany instanceof Publishingcompany) {
            return $this
                ->addUsingAlias(EssayPeer::PUBLISHINGCOMPANY_ID, $publishingcompany->getId(), $comparison);
        } elseif ($publishingcompany instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EssayPeer::PUBLISHINGCOMPANY_ID, $publishingcompany->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPublishingcompany() only accepts arguments of type Publishingcompany or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Publishingcompany relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EssayQuery The current query, for fluid interface
     */
    public function joinPublishingcompany($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Publishingcompany');

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
            $this->addJoinObject($join, 'Publishingcompany');
        }

        return $this;
    }

    /**
     * Use the Publishingcompany relation Publishingcompany object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\PublishingcompanyQuery A secondary query class using the current class as primary query
     */
    public function usePublishingcompanyQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPublishingcompany($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publishingcompany', '\DTA\MetadataBundle\Model\PublishingcompanyQuery');
    }

    /**
     * Filter the query by a related Place object
     *
     * @param   Place|PropelObjectCollection $place The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EssayQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPlace($place, $comparison = null)
    {
        if ($place instanceof Place) {
            return $this
                ->addUsingAlias(EssayPeer::PLACE_ID, $place->getId(), $comparison);
        } elseif ($place instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EssayPeer::PLACE_ID, $place->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlace() only accepts arguments of type Place or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Place relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EssayQuery The current query, for fluid interface
     */
    public function joinPlace($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Place');

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
            $this->addJoinObject($join, 'Place');
        }

        return $this;
    }

    /**
     * Use the Place relation Place object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\PlaceQuery A secondary query class using the current class as primary query
     */
    public function usePlaceQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPlace($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Place', '\DTA\MetadataBundle\Model\PlaceQuery');
    }

    /**
     * Filter the query by a related Datespecification object
     *
     * @param   Datespecification|PropelObjectCollection $datespecification The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EssayQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDatespecification($datespecification, $comparison = null)
    {
        if ($datespecification instanceof Datespecification) {
            return $this
                ->addUsingAlias(EssayPeer::DATESPECIFICATION_ID, $datespecification->getId(), $comparison);
        } elseif ($datespecification instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EssayPeer::DATESPECIFICATION_ID, $datespecification->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return EssayQuery The current query, for fluid interface
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
     * @return   \DTA\MetadataBundle\Model\DatespecificationQuery A secondary query class using the current class as primary query
     */
    public function useDatespecificationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDatespecification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Datespecification', '\DTA\MetadataBundle\Model\DatespecificationQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Essay $essay Object to remove from the list of results
     *
     * @return EssayQuery The current query, for fluid interface
     */
    public function prune($essay = null)
    {
        if ($essay) {
            $this->addUsingAlias(EssayPeer::ID, $essay->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
