<?php

namespace DTA\MetadataBundle\Model\Publication\om;

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
use DTA\MetadataBundle\Model\Description\Datespecification;
use DTA\MetadataBundle\Model\Description\Place;
use DTA\MetadataBundle\Model\Description\Title;
use DTA\MetadataBundle\Model\Publication\Essay;
use DTA\MetadataBundle\Model\Publication\Magazine;
use DTA\MetadataBundle\Model\Publication\Monograph;
use DTA\MetadataBundle\Model\Publication\Publication;
use DTA\MetadataBundle\Model\Publication\PublicationPeer;
use DTA\MetadataBundle\Model\Publication\PublicationQuery;
use DTA\MetadataBundle\Model\Publication\Publishingcompany;
use DTA\MetadataBundle\Model\Publication\Series;
use DTA\MetadataBundle\Model\Publication\Writ;

/**
 * @method PublicationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PublicationQuery orderByPublishingcompanyId($order = Criteria::ASC) Order by the publishingCompany_id column
 * @method PublicationQuery orderByPlaceId($order = Criteria::ASC) Order by the place_id column
 * @method PublicationQuery orderByDatespecificationId($order = Criteria::ASC) Order by the dateSpecification_id column
 * @method PublicationQuery orderByPrintrun($order = Criteria::ASC) Order by the printRun column
 * @method PublicationQuery orderByPrintruncomment($order = Criteria::ASC) Order by the printRunComment column
 * @method PublicationQuery orderByEdition($order = Criteria::ASC) Order by the edition column
 * @method PublicationQuery orderByNumpages($order = Criteria::ASC) Order by the numPages column
 * @method PublicationQuery orderByNumpagesnormed($order = Criteria::ASC) Order by the numPagesNormed column
 * @method PublicationQuery orderByBibliographiccitation($order = Criteria::ASC) Order by the bibliographicCitation column
 *
 * @method PublicationQuery groupById() Group by the id column
 * @method PublicationQuery groupByPublishingcompanyId() Group by the publishingCompany_id column
 * @method PublicationQuery groupByPlaceId() Group by the place_id column
 * @method PublicationQuery groupByDatespecificationId() Group by the dateSpecification_id column
 * @method PublicationQuery groupByPrintrun() Group by the printRun column
 * @method PublicationQuery groupByPrintruncomment() Group by the printRunComment column
 * @method PublicationQuery groupByEdition() Group by the edition column
 * @method PublicationQuery groupByNumpages() Group by the numPages column
 * @method PublicationQuery groupByNumpagesnormed() Group by the numPagesNormed column
 * @method PublicationQuery groupByBibliographiccitation() Group by the bibliographicCitation column
 *
 * @method PublicationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PublicationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PublicationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PublicationQuery leftJoinPublishingcompany($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publishingcompany relation
 * @method PublicationQuery rightJoinPublishingcompany($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publishingcompany relation
 * @method PublicationQuery innerJoinPublishingcompany($relationAlias = null) Adds a INNER JOIN clause to the query using the Publishingcompany relation
 *
 * @method PublicationQuery leftJoinPlace($relationAlias = null) Adds a LEFT JOIN clause to the query using the Place relation
 * @method PublicationQuery rightJoinPlace($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Place relation
 * @method PublicationQuery innerJoinPlace($relationAlias = null) Adds a INNER JOIN clause to the query using the Place relation
 *
 * @method PublicationQuery leftJoinDatespecification($relationAlias = null) Adds a LEFT JOIN clause to the query using the Datespecification relation
 * @method PublicationQuery rightJoinDatespecification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Datespecification relation
 * @method PublicationQuery innerJoinDatespecification($relationAlias = null) Adds a INNER JOIN clause to the query using the Datespecification relation
 *
 * @method PublicationQuery leftJoinEssay($relationAlias = null) Adds a LEFT JOIN clause to the query using the Essay relation
 * @method PublicationQuery rightJoinEssay($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Essay relation
 * @method PublicationQuery innerJoinEssay($relationAlias = null) Adds a INNER JOIN clause to the query using the Essay relation
 *
 * @method PublicationQuery leftJoinMagazine($relationAlias = null) Adds a LEFT JOIN clause to the query using the Magazine relation
 * @method PublicationQuery rightJoinMagazine($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Magazine relation
 * @method PublicationQuery innerJoinMagazine($relationAlias = null) Adds a INNER JOIN clause to the query using the Magazine relation
 *
 * @method PublicationQuery leftJoinMonograph($relationAlias = null) Adds a LEFT JOIN clause to the query using the Monograph relation
 * @method PublicationQuery rightJoinMonograph($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Monograph relation
 * @method PublicationQuery innerJoinMonograph($relationAlias = null) Adds a INNER JOIN clause to the query using the Monograph relation
 *
 * @method PublicationQuery leftJoinSeries($relationAlias = null) Adds a LEFT JOIN clause to the query using the Series relation
 * @method PublicationQuery rightJoinSeries($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Series relation
 * @method PublicationQuery innerJoinSeries($relationAlias = null) Adds a INNER JOIN clause to the query using the Series relation
 *
 * @method PublicationQuery leftJoinTitle($relationAlias = null) Adds a LEFT JOIN clause to the query using the Title relation
 * @method PublicationQuery rightJoinTitle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Title relation
 * @method PublicationQuery innerJoinTitle($relationAlias = null) Adds a INNER JOIN clause to the query using the Title relation
 *
 * @method PublicationQuery leftJoinWrit($relationAlias = null) Adds a LEFT JOIN clause to the query using the Writ relation
 * @method PublicationQuery rightJoinWrit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Writ relation
 * @method PublicationQuery innerJoinWrit($relationAlias = null) Adds a INNER JOIN clause to the query using the Writ relation
 *
 * @method Publication findOne(PropelPDO $con = null) Return the first Publication matching the query
 * @method Publication findOneOrCreate(PropelPDO $con = null) Return the first Publication matching the query, or a new Publication object populated from the query conditions when no match is found
 *
 * @method Publication findOneByPublishingcompanyId(int $publishingCompany_id) Return the first Publication filtered by the publishingCompany_id column
 * @method Publication findOneByPlaceId(int $place_id) Return the first Publication filtered by the place_id column
 * @method Publication findOneByDatespecificationId(int $dateSpecification_id) Return the first Publication filtered by the dateSpecification_id column
 * @method Publication findOneByPrintrun(string $printRun) Return the first Publication filtered by the printRun column
 * @method Publication findOneByPrintruncomment(string $printRunComment) Return the first Publication filtered by the printRunComment column
 * @method Publication findOneByEdition(string $edition) Return the first Publication filtered by the edition column
 * @method Publication findOneByNumpages(int $numPages) Return the first Publication filtered by the numPages column
 * @method Publication findOneByNumpagesnormed(int $numPagesNormed) Return the first Publication filtered by the numPagesNormed column
 * @method Publication findOneByBibliographiccitation(string $bibliographicCitation) Return the first Publication filtered by the bibliographicCitation column
 *
 * @method array findById(int $id) Return Publication objects filtered by the id column
 * @method array findByPublishingcompanyId(int $publishingCompany_id) Return Publication objects filtered by the publishingCompany_id column
 * @method array findByPlaceId(int $place_id) Return Publication objects filtered by the place_id column
 * @method array findByDatespecificationId(int $dateSpecification_id) Return Publication objects filtered by the dateSpecification_id column
 * @method array findByPrintrun(string $printRun) Return Publication objects filtered by the printRun column
 * @method array findByPrintruncomment(string $printRunComment) Return Publication objects filtered by the printRunComment column
 * @method array findByEdition(string $edition) Return Publication objects filtered by the edition column
 * @method array findByNumpages(int $numPages) Return Publication objects filtered by the numPages column
 * @method array findByNumpagesnormed(int $numPagesNormed) Return Publication objects filtered by the numPagesNormed column
 * @method array findByBibliographiccitation(string $bibliographicCitation) Return Publication objects filtered by the bibliographicCitation column
 */
abstract class BasePublicationQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePublicationQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Publication\\Publication', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PublicationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     PublicationQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PublicationQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PublicationQuery) {
            return $criteria;
        }
        $query = new PublicationQuery();
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
     * @return   Publication|Publication[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PublicationPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Publication A model object, or null if the key is not found
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
     * @return   Publication A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `PUBLISHINGCOMPANY_ID`, `PLACE_ID`, `DATESPECIFICATION_ID`, `PRINTRUN`, `PRINTRUNCOMMENT`, `EDITION`, `NUMPAGES`, `NUMPAGESNORMED`, `BIBLIOGRAPHICCITATION` FROM `publication` WHERE `ID` = :p0';
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
            $obj = new Publication();
            $obj->hydrate($row);
            PublicationPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Publication|Publication[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Publication[]|mixed the list of results, formatted by the current formatter
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PublicationPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PublicationPeer::ID, $keys, Criteria::IN);
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(PublicationPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the publishingCompany_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublishingcompanyId(1234); // WHERE publishingCompany_id = 1234
     * $query->filterByPublishingcompanyId(array(12, 34)); // WHERE publishingCompany_id IN (12, 34)
     * $query->filterByPublishingcompanyId(array('min' => 12)); // WHERE publishingCompany_id > 12
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByPublishingcompanyId($publishingcompanyId = null, $comparison = null)
    {
        if (is_array($publishingcompanyId)) {
            $useMinMax = false;
            if (isset($publishingcompanyId['min'])) {
                $this->addUsingAlias(PublicationPeer::PUBLISHINGCOMPANY_ID, $publishingcompanyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publishingcompanyId['max'])) {
                $this->addUsingAlias(PublicationPeer::PUBLISHINGCOMPANY_ID, $publishingcompanyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::PUBLISHINGCOMPANY_ID, $publishingcompanyId, $comparison);
    }

    /**
     * Filter the query on the place_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPlaceId(1234); // WHERE place_id = 1234
     * $query->filterByPlaceId(array(12, 34)); // WHERE place_id IN (12, 34)
     * $query->filterByPlaceId(array('min' => 12)); // WHERE place_id > 12
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByPlaceId($placeId = null, $comparison = null)
    {
        if (is_array($placeId)) {
            $useMinMax = false;
            if (isset($placeId['min'])) {
                $this->addUsingAlias(PublicationPeer::PLACE_ID, $placeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($placeId['max'])) {
                $this->addUsingAlias(PublicationPeer::PLACE_ID, $placeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::PLACE_ID, $placeId, $comparison);
    }

    /**
     * Filter the query on the dateSpecification_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDatespecificationId(1234); // WHERE dateSpecification_id = 1234
     * $query->filterByDatespecificationId(array(12, 34)); // WHERE dateSpecification_id IN (12, 34)
     * $query->filterByDatespecificationId(array('min' => 12)); // WHERE dateSpecification_id > 12
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByDatespecificationId($datespecificationId = null, $comparison = null)
    {
        if (is_array($datespecificationId)) {
            $useMinMax = false;
            if (isset($datespecificationId['min'])) {
                $this->addUsingAlias(PublicationPeer::DATESPECIFICATION_ID, $datespecificationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($datespecificationId['max'])) {
                $this->addUsingAlias(PublicationPeer::DATESPECIFICATION_ID, $datespecificationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::DATESPECIFICATION_ID, $datespecificationId, $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PublicationPeer::PRINTRUN, $printrun, $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PublicationPeer::PRINTRUNCOMMENT, $printruncomment, $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PublicationPeer::EDITION, $edition, $comparison);
    }

    /**
     * Filter the query on the numPages column
     *
     * Example usage:
     * <code>
     * $query->filterByNumpages(1234); // WHERE numPages = 1234
     * $query->filterByNumpages(array(12, 34)); // WHERE numPages IN (12, 34)
     * $query->filterByNumpages(array('min' => 12)); // WHERE numPages > 12
     * </code>
     *
     * @param     mixed $numpages The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByNumpages($numpages = null, $comparison = null)
    {
        if (is_array($numpages)) {
            $useMinMax = false;
            if (isset($numpages['min'])) {
                $this->addUsingAlias(PublicationPeer::NUMPAGES, $numpages['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numpages['max'])) {
                $this->addUsingAlias(PublicationPeer::NUMPAGES, $numpages['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::NUMPAGES, $numpages, $comparison);
    }

    /**
     * Filter the query on the numPagesNormed column
     *
     * Example usage:
     * <code>
     * $query->filterByNumpagesnormed(1234); // WHERE numPagesNormed = 1234
     * $query->filterByNumpagesnormed(array(12, 34)); // WHERE numPagesNormed IN (12, 34)
     * $query->filterByNumpagesnormed(array('min' => 12)); // WHERE numPagesNormed > 12
     * </code>
     *
     * @param     mixed $numpagesnormed The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByNumpagesnormed($numpagesnormed = null, $comparison = null)
    {
        if (is_array($numpagesnormed)) {
            $useMinMax = false;
            if (isset($numpagesnormed['min'])) {
                $this->addUsingAlias(PublicationPeer::NUMPAGESNORMED, $numpagesnormed['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numpagesnormed['max'])) {
                $this->addUsingAlias(PublicationPeer::NUMPAGESNORMED, $numpagesnormed['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::NUMPAGESNORMED, $numpagesnormed, $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PublicationPeer::BIBLIOGRAPHICCITATION, $bibliographiccitation, $comparison);
    }

    /**
     * Filter the query by a related Publishingcompany object
     *
     * @param   Publishingcompany|PropelObjectCollection $publishingcompany The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByPublishingcompany($publishingcompany, $comparison = null)
    {
        if ($publishingcompany instanceof Publishingcompany) {
            return $this
                ->addUsingAlias(PublicationPeer::PUBLISHINGCOMPANY_ID, $publishingcompany->getId(), $comparison);
        } elseif ($publishingcompany instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::PUBLISHINGCOMPANY_ID, $publishingcompany->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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
     * @return   \DTA\MetadataBundle\Model\Publication\PublishingcompanyQuery A secondary query class using the current class as primary query
     */
    public function usePublishingcompanyQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPublishingcompany($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publishingcompany', '\DTA\MetadataBundle\Model\Publication\PublishingcompanyQuery');
    }

    /**
     * Filter the query by a related Place object
     *
     * @param   Place|PropelObjectCollection $place The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByPlace($place, $comparison = null)
    {
        if ($place instanceof Place) {
            return $this
                ->addUsingAlias(PublicationPeer::PLACE_ID, $place->getId(), $comparison);
        } elseif ($place instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::PLACE_ID, $place->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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
     * @return   \DTA\MetadataBundle\Model\Description\PlaceQuery A secondary query class using the current class as primary query
     */
    public function usePlaceQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPlace($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Place', '\DTA\MetadataBundle\Model\Description\PlaceQuery');
    }

    /**
     * Filter the query by a related Datespecification object
     *
     * @param   Datespecification|PropelObjectCollection $datespecification The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByDatespecification($datespecification, $comparison = null)
    {
        if ($datespecification instanceof Datespecification) {
            return $this
                ->addUsingAlias(PublicationPeer::DATESPECIFICATION_ID, $datespecification->getId(), $comparison);
        } elseif ($datespecification instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::DATESPECIFICATION_ID, $datespecification->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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
     * @return   \DTA\MetadataBundle\Model\Description\DatespecificationQuery A secondary query class using the current class as primary query
     */
    public function useDatespecificationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDatespecification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Datespecification', '\DTA\MetadataBundle\Model\Description\DatespecificationQuery');
    }

    /**
     * Filter the query by a related Essay object
     *
     * @param   Essay|PropelObjectCollection $essay  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByEssay($essay, $comparison = null)
    {
        if ($essay instanceof Essay) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $essay->getPublicationId(), $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinEssay($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return   \DTA\MetadataBundle\Model\Publication\EssayQuery A secondary query class using the current class as primary query
     */
    public function useEssayQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEssay($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Essay', '\DTA\MetadataBundle\Model\Publication\EssayQuery');
    }

    /**
     * Filter the query by a related Magazine object
     *
     * @param   Magazine|PropelObjectCollection $magazine  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByMagazine($magazine, $comparison = null)
    {
        if ($magazine instanceof Magazine) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $magazine->getPublicationId(), $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinMagazine($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return   \DTA\MetadataBundle\Model\Publication\MagazineQuery A secondary query class using the current class as primary query
     */
    public function useMagazineQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMagazine($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Magazine', '\DTA\MetadataBundle\Model\Publication\MagazineQuery');
    }

    /**
     * Filter the query by a related Monograph object
     *
     * @param   Monograph|PropelObjectCollection $monograph  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByMonograph($monograph, $comparison = null)
    {
        if ($monograph instanceof Monograph) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $monograph->getPublicationId(), $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinMonograph($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return   \DTA\MetadataBundle\Model\Publication\MonographQuery A secondary query class using the current class as primary query
     */
    public function useMonographQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMonograph($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Monograph', '\DTA\MetadataBundle\Model\Publication\MonographQuery');
    }

    /**
     * Filter the query by a related Series object
     *
     * @param   Series|PropelObjectCollection $series  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterBySeries($series, $comparison = null)
    {
        if ($series instanceof Series) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $series->getPublicationId(), $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinSeries($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return   \DTA\MetadataBundle\Model\Publication\SeriesQuery A secondary query class using the current class as primary query
     */
    public function useSeriesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSeries($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Series', '\DTA\MetadataBundle\Model\Publication\SeriesQuery');
    }

    /**
     * Filter the query by a related Title object
     *
     * @param   Title|PropelObjectCollection $title  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByTitle($title, $comparison = null)
    {
        if ($title instanceof Title) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $title->getPublicationId(), $comparison);
        } elseif ($title instanceof PropelObjectCollection) {
            return $this
                ->useTitleQuery()
                ->filterByPrimaryKeys($title->getPrimaryKeys())
                ->endUse();
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinTitle($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @return   \DTA\MetadataBundle\Model\Description\TitleQuery A secondary query class using the current class as primary query
     */
    public function useTitleQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTitle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Title', '\DTA\MetadataBundle\Model\Description\TitleQuery');
    }

    /**
     * Filter the query by a related Writ object
     *
     * @param   Writ|PropelObjectCollection $writ  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByWrit($writ, $comparison = null)
    {
        if ($writ instanceof Writ) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $writ->getPublicationId(), $comparison);
        } elseif ($writ instanceof PropelObjectCollection) {
            return $this
                ->useWritQuery()
                ->filterByPrimaryKeys($writ->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByWrit() only accepts arguments of type Writ or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Writ relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinWrit($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Writ');

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
            $this->addJoinObject($join, 'Writ');
        }

        return $this;
    }

    /**
     * Use the Writ relation Writ object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Publication\WritQuery A secondary query class using the current class as primary query
     */
    public function useWritQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWrit($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Writ', '\DTA\MetadataBundle\Model\Publication\WritQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Publication $publication Object to remove from the list of results
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function prune($publication = null)
    {
        if ($publication) {
            $this->addUsingAlias(PublicationPeer::ID, $publication->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
