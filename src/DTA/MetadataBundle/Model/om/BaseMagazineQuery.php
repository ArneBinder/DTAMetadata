<?php

namespace DTA\MetadataBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use DTA\MetadataBundle\Model\Datespecification;
use DTA\MetadataBundle\Model\Magazine;
use DTA\MetadataBundle\Model\MagazinePeer;
use DTA\MetadataBundle\Model\MagazineQuery;
use DTA\MetadataBundle\Model\Place;
use DTA\MetadataBundle\Model\Printer;
use DTA\MetadataBundle\Model\PublicationQuery;
use DTA\MetadataBundle\Model\Publisher;
use DTA\MetadataBundle\Model\Publishingcompany;
use DTA\MetadataBundle\Model\Relatedset;
use DTA\MetadataBundle\Model\Title;
use DTA\MetadataBundle\Model\Translator;
use DTA\MetadataBundle\Model\Work;

/**
 * @method MagazineQuery orderById($order = Criteria::ASC) Order by the id column
 * @method MagazineQuery orderByPrintrun($order = Criteria::ASC) Order by the printRun column
 * @method MagazineQuery orderByPrintruncomment($order = Criteria::ASC) Order by the printRunComment column
 * @method MagazineQuery orderByEdition($order = Criteria::ASC) Order by the edition column
 * @method MagazineQuery orderByNumpages($order = Criteria::ASC) Order by the numPages column
 * @method MagazineQuery orderByNumpagesnormed($order = Criteria::ASC) Order by the numPagesNormed column
 * @method MagazineQuery orderByBibliographiccitation($order = Criteria::ASC) Order by the bibliographicCitation column
 * @method MagazineQuery orderByTitleId($order = Criteria::ASC) Order by the title_id column
 * @method MagazineQuery orderByPublishingcompanyId($order = Criteria::ASC) Order by the publishingCompany_id column
 * @method MagazineQuery orderByPlaceId($order = Criteria::ASC) Order by the place_id column
 * @method MagazineQuery orderByDatespecificationId($order = Criteria::ASC) Order by the dateSpecification_id column
 * @method MagazineQuery orderByRelatedsetId($order = Criteria::ASC) Order by the relatedSet_id column
 * @method MagazineQuery orderByWorkId($order = Criteria::ASC) Order by the work_id column
 * @method MagazineQuery orderByPublisherId($order = Criteria::ASC) Order by the publisher_id column
 * @method MagazineQuery orderByPrinterId($order = Criteria::ASC) Order by the printer_id column
 * @method MagazineQuery orderByTranslatorId($order = Criteria::ASC) Order by the translator_id column
 *
 * @method MagazineQuery groupById() Group by the id column
 * @method MagazineQuery groupByPrintrun() Group by the printRun column
 * @method MagazineQuery groupByPrintruncomment() Group by the printRunComment column
 * @method MagazineQuery groupByEdition() Group by the edition column
 * @method MagazineQuery groupByNumpages() Group by the numPages column
 * @method MagazineQuery groupByNumpagesnormed() Group by the numPagesNormed column
 * @method MagazineQuery groupByBibliographiccitation() Group by the bibliographicCitation column
 * @method MagazineQuery groupByTitleId() Group by the title_id column
 * @method MagazineQuery groupByPublishingcompanyId() Group by the publishingCompany_id column
 * @method MagazineQuery groupByPlaceId() Group by the place_id column
 * @method MagazineQuery groupByDatespecificationId() Group by the dateSpecification_id column
 * @method MagazineQuery groupByRelatedsetId() Group by the relatedSet_id column
 * @method MagazineQuery groupByWorkId() Group by the work_id column
 * @method MagazineQuery groupByPublisherId() Group by the publisher_id column
 * @method MagazineQuery groupByPrinterId() Group by the printer_id column
 * @method MagazineQuery groupByTranslatorId() Group by the translator_id column
 *
 * @method MagazineQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method MagazineQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method MagazineQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method MagazineQuery leftJoinWork($relationAlias = null) Adds a LEFT JOIN clause to the query using the Work relation
 * @method MagazineQuery rightJoinWork($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Work relation
 * @method MagazineQuery innerJoinWork($relationAlias = null) Adds a INNER JOIN clause to the query using the Work relation
 *
 * @method MagazineQuery leftJoinPublisher($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publisher relation
 * @method MagazineQuery rightJoinPublisher($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publisher relation
 * @method MagazineQuery innerJoinPublisher($relationAlias = null) Adds a INNER JOIN clause to the query using the Publisher relation
 *
 * @method MagazineQuery leftJoinPrinter($relationAlias = null) Adds a LEFT JOIN clause to the query using the Printer relation
 * @method MagazineQuery rightJoinPrinter($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Printer relation
 * @method MagazineQuery innerJoinPrinter($relationAlias = null) Adds a INNER JOIN clause to the query using the Printer relation
 *
 * @method MagazineQuery leftJoinTranslator($relationAlias = null) Adds a LEFT JOIN clause to the query using the Translator relation
 * @method MagazineQuery rightJoinTranslator($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Translator relation
 * @method MagazineQuery innerJoinTranslator($relationAlias = null) Adds a INNER JOIN clause to the query using the Translator relation
 *
 * @method MagazineQuery leftJoinRelatedset($relationAlias = null) Adds a LEFT JOIN clause to the query using the Relatedset relation
 * @method MagazineQuery rightJoinRelatedset($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Relatedset relation
 * @method MagazineQuery innerJoinRelatedset($relationAlias = null) Adds a INNER JOIN clause to the query using the Relatedset relation
 *
 * @method MagazineQuery leftJoinTitle($relationAlias = null) Adds a LEFT JOIN clause to the query using the Title relation
 * @method MagazineQuery rightJoinTitle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Title relation
 * @method MagazineQuery innerJoinTitle($relationAlias = null) Adds a INNER JOIN clause to the query using the Title relation
 *
 * @method MagazineQuery leftJoinPublishingcompany($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publishingcompany relation
 * @method MagazineQuery rightJoinPublishingcompany($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publishingcompany relation
 * @method MagazineQuery innerJoinPublishingcompany($relationAlias = null) Adds a INNER JOIN clause to the query using the Publishingcompany relation
 *
 * @method MagazineQuery leftJoinPlace($relationAlias = null) Adds a LEFT JOIN clause to the query using the Place relation
 * @method MagazineQuery rightJoinPlace($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Place relation
 * @method MagazineQuery innerJoinPlace($relationAlias = null) Adds a INNER JOIN clause to the query using the Place relation
 *
 * @method MagazineQuery leftJoinDatespecification($relationAlias = null) Adds a LEFT JOIN clause to the query using the Datespecification relation
 * @method MagazineQuery rightJoinDatespecification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Datespecification relation
 * @method MagazineQuery innerJoinDatespecification($relationAlias = null) Adds a INNER JOIN clause to the query using the Datespecification relation
 *
 * @method Magazine findOne(PropelPDO $con = null) Return the first Magazine matching the query
 * @method Magazine findOneOrCreate(PropelPDO $con = null) Return the first Magazine matching the query, or a new Magazine object populated from the query conditions when no match is found
 *
 * @method Magazine findOneByPrintrun(string $printRun) Return the first Magazine filtered by the printRun column
 * @method Magazine findOneByPrintruncomment(string $printRunComment) Return the first Magazine filtered by the printRunComment column
 * @method Magazine findOneByEdition(string $edition) Return the first Magazine filtered by the edition column
 * @method Magazine findOneByNumpages(int $numPages) Return the first Magazine filtered by the numPages column
 * @method Magazine findOneByNumpagesnormed(int $numPagesNormed) Return the first Magazine filtered by the numPagesNormed column
 * @method Magazine findOneByBibliographiccitation(string $bibliographicCitation) Return the first Magazine filtered by the bibliographicCitation column
 * @method Magazine findOneByTitleId(int $title_id) Return the first Magazine filtered by the title_id column
 * @method Magazine findOneByPublishingcompanyId(int $publishingCompany_id) Return the first Magazine filtered by the publishingCompany_id column
 * @method Magazine findOneByPlaceId(int $place_id) Return the first Magazine filtered by the place_id column
 * @method Magazine findOneByDatespecificationId(int $dateSpecification_id) Return the first Magazine filtered by the dateSpecification_id column
 * @method Magazine findOneByRelatedsetId(int $relatedSet_id) Return the first Magazine filtered by the relatedSet_id column
 * @method Magazine findOneByWorkId(int $work_id) Return the first Magazine filtered by the work_id column
 * @method Magazine findOneByPublisherId(int $publisher_id) Return the first Magazine filtered by the publisher_id column
 * @method Magazine findOneByPrinterId(int $printer_id) Return the first Magazine filtered by the printer_id column
 * @method Magazine findOneByTranslatorId(int $translator_id) Return the first Magazine filtered by the translator_id column
 *
 * @method array findById(int $id) Return Magazine objects filtered by the id column
 * @method array findByPrintrun(string $printRun) Return Magazine objects filtered by the printRun column
 * @method array findByPrintruncomment(string $printRunComment) Return Magazine objects filtered by the printRunComment column
 * @method array findByEdition(string $edition) Return Magazine objects filtered by the edition column
 * @method array findByNumpages(int $numPages) Return Magazine objects filtered by the numPages column
 * @method array findByNumpagesnormed(int $numPagesNormed) Return Magazine objects filtered by the numPagesNormed column
 * @method array findByBibliographiccitation(string $bibliographicCitation) Return Magazine objects filtered by the bibliographicCitation column
 * @method array findByTitleId(int $title_id) Return Magazine objects filtered by the title_id column
 * @method array findByPublishingcompanyId(int $publishingCompany_id) Return Magazine objects filtered by the publishingCompany_id column
 * @method array findByPlaceId(int $place_id) Return Magazine objects filtered by the place_id column
 * @method array findByDatespecificationId(int $dateSpecification_id) Return Magazine objects filtered by the dateSpecification_id column
 * @method array findByRelatedsetId(int $relatedSet_id) Return Magazine objects filtered by the relatedSet_id column
 * @method array findByWorkId(int $work_id) Return Magazine objects filtered by the work_id column
 * @method array findByPublisherId(int $publisher_id) Return Magazine objects filtered by the publisher_id column
 * @method array findByPrinterId(int $printer_id) Return Magazine objects filtered by the printer_id column
 * @method array findByTranslatorId(int $translator_id) Return Magazine objects filtered by the translator_id column
 */
abstract class BaseMagazineQuery extends PublicationQuery
{
    /**
     * Initializes internal state of BaseMagazineQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Magazine', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new MagazineQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   MagazineQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return MagazineQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof MagazineQuery) {
            return $criteria;
        }
        $query = new MagazineQuery();
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
     * @return   Magazine|Magazine[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MagazinePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(MagazinePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Magazine A model object, or null if the key is not found
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
     * @return                 Magazine A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `printRun`, `printRunComment`, `edition`, `numPages`, `numPagesNormed`, `bibliographicCitation`, `title_id`, `publishingCompany_id`, `place_id`, `dateSpecification_id`, `relatedSet_id`, `work_id`, `publisher_id`, `printer_id`, `translator_id` FROM `magazine` WHERE `id` = :p0';
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
            $obj = new Magazine();
            $obj->hydrate($row);
            MagazinePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Magazine|Magazine[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Magazine[]|mixed the list of results, formatted by the current formatter
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
     * @return MagazineQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MagazinePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return MagazineQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MagazinePeer::ID, $keys, Criteria::IN);
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
     * @return MagazineQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MagazinePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MagazinePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MagazinePeer::ID, $id, $comparison);
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
     * @return MagazineQuery The current query, for fluid interface
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

        return $this->addUsingAlias(MagazinePeer::PRINTRUN, $printrun, $comparison);
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
     * @return MagazineQuery The current query, for fluid interface
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

        return $this->addUsingAlias(MagazinePeer::PRINTRUNCOMMENT, $printruncomment, $comparison);
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
     * @return MagazineQuery The current query, for fluid interface
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

        return $this->addUsingAlias(MagazinePeer::EDITION, $edition, $comparison);
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
     * @return MagazineQuery The current query, for fluid interface
     */
    public function filterByNumpages($numpages = null, $comparison = null)
    {
        if (is_array($numpages)) {
            $useMinMax = false;
            if (isset($numpages['min'])) {
                $this->addUsingAlias(MagazinePeer::NUMPAGES, $numpages['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numpages['max'])) {
                $this->addUsingAlias(MagazinePeer::NUMPAGES, $numpages['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MagazinePeer::NUMPAGES, $numpages, $comparison);
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
     * @return MagazineQuery The current query, for fluid interface
     */
    public function filterByNumpagesnormed($numpagesnormed = null, $comparison = null)
    {
        if (is_array($numpagesnormed)) {
            $useMinMax = false;
            if (isset($numpagesnormed['min'])) {
                $this->addUsingAlias(MagazinePeer::NUMPAGESNORMED, $numpagesnormed['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numpagesnormed['max'])) {
                $this->addUsingAlias(MagazinePeer::NUMPAGESNORMED, $numpagesnormed['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MagazinePeer::NUMPAGESNORMED, $numpagesnormed, $comparison);
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
     * @return MagazineQuery The current query, for fluid interface
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

        return $this->addUsingAlias(MagazinePeer::BIBLIOGRAPHICCITATION, $bibliographiccitation, $comparison);
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
     * @return MagazineQuery The current query, for fluid interface
     */
    public function filterByTitleId($titleId = null, $comparison = null)
    {
        if (is_array($titleId)) {
            $useMinMax = false;
            if (isset($titleId['min'])) {
                $this->addUsingAlias(MagazinePeer::TITLE_ID, $titleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($titleId['max'])) {
                $this->addUsingAlias(MagazinePeer::TITLE_ID, $titleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MagazinePeer::TITLE_ID, $titleId, $comparison);
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
     * @return MagazineQuery The current query, for fluid interface
     */
    public function filterByPublishingcompanyId($publishingcompanyId = null, $comparison = null)
    {
        if (is_array($publishingcompanyId)) {
            $useMinMax = false;
            if (isset($publishingcompanyId['min'])) {
                $this->addUsingAlias(MagazinePeer::PUBLISHINGCOMPANY_ID, $publishingcompanyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publishingcompanyId['max'])) {
                $this->addUsingAlias(MagazinePeer::PUBLISHINGCOMPANY_ID, $publishingcompanyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MagazinePeer::PUBLISHINGCOMPANY_ID, $publishingcompanyId, $comparison);
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
     * @return MagazineQuery The current query, for fluid interface
     */
    public function filterByPlaceId($placeId = null, $comparison = null)
    {
        if (is_array($placeId)) {
            $useMinMax = false;
            if (isset($placeId['min'])) {
                $this->addUsingAlias(MagazinePeer::PLACE_ID, $placeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($placeId['max'])) {
                $this->addUsingAlias(MagazinePeer::PLACE_ID, $placeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MagazinePeer::PLACE_ID, $placeId, $comparison);
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
     * @return MagazineQuery The current query, for fluid interface
     */
    public function filterByDatespecificationId($datespecificationId = null, $comparison = null)
    {
        if (is_array($datespecificationId)) {
            $useMinMax = false;
            if (isset($datespecificationId['min'])) {
                $this->addUsingAlias(MagazinePeer::DATESPECIFICATION_ID, $datespecificationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($datespecificationId['max'])) {
                $this->addUsingAlias(MagazinePeer::DATESPECIFICATION_ID, $datespecificationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MagazinePeer::DATESPECIFICATION_ID, $datespecificationId, $comparison);
    }

    /**
     * Filter the query on the relatedSet_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRelatedsetId(1234); // WHERE relatedSet_id = 1234
     * $query->filterByRelatedsetId(array(12, 34)); // WHERE relatedSet_id IN (12, 34)
     * $query->filterByRelatedsetId(array('min' => 12)); // WHERE relatedSet_id >= 12
     * $query->filterByRelatedsetId(array('max' => 12)); // WHERE relatedSet_id <= 12
     * </code>
     *
     * @see       filterByRelatedset()
     *
     * @param     mixed $relatedsetId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MagazineQuery The current query, for fluid interface
     */
    public function filterByRelatedsetId($relatedsetId = null, $comparison = null)
    {
        if (is_array($relatedsetId)) {
            $useMinMax = false;
            if (isset($relatedsetId['min'])) {
                $this->addUsingAlias(MagazinePeer::RELATEDSET_ID, $relatedsetId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($relatedsetId['max'])) {
                $this->addUsingAlias(MagazinePeer::RELATEDSET_ID, $relatedsetId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MagazinePeer::RELATEDSET_ID, $relatedsetId, $comparison);
    }

    /**
     * Filter the query on the work_id column
     *
     * Example usage:
     * <code>
     * $query->filterByWorkId(1234); // WHERE work_id = 1234
     * $query->filterByWorkId(array(12, 34)); // WHERE work_id IN (12, 34)
     * $query->filterByWorkId(array('min' => 12)); // WHERE work_id >= 12
     * $query->filterByWorkId(array('max' => 12)); // WHERE work_id <= 12
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
     * @return MagazineQuery The current query, for fluid interface
     */
    public function filterByWorkId($workId = null, $comparison = null)
    {
        if (is_array($workId)) {
            $useMinMax = false;
            if (isset($workId['min'])) {
                $this->addUsingAlias(MagazinePeer::WORK_ID, $workId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($workId['max'])) {
                $this->addUsingAlias(MagazinePeer::WORK_ID, $workId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MagazinePeer::WORK_ID, $workId, $comparison);
    }

    /**
     * Filter the query on the publisher_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublisherId(1234); // WHERE publisher_id = 1234
     * $query->filterByPublisherId(array(12, 34)); // WHERE publisher_id IN (12, 34)
     * $query->filterByPublisherId(array('min' => 12)); // WHERE publisher_id >= 12
     * $query->filterByPublisherId(array('max' => 12)); // WHERE publisher_id <= 12
     * </code>
     *
     * @see       filterByPublisher()
     *
     * @param     mixed $publisherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MagazineQuery The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, $comparison = null)
    {
        if (is_array($publisherId)) {
            $useMinMax = false;
            if (isset($publisherId['min'])) {
                $this->addUsingAlias(MagazinePeer::PUBLISHER_ID, $publisherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publisherId['max'])) {
                $this->addUsingAlias(MagazinePeer::PUBLISHER_ID, $publisherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MagazinePeer::PUBLISHER_ID, $publisherId, $comparison);
    }

    /**
     * Filter the query on the printer_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPrinterId(1234); // WHERE printer_id = 1234
     * $query->filterByPrinterId(array(12, 34)); // WHERE printer_id IN (12, 34)
     * $query->filterByPrinterId(array('min' => 12)); // WHERE printer_id >= 12
     * $query->filterByPrinterId(array('max' => 12)); // WHERE printer_id <= 12
     * </code>
     *
     * @see       filterByPrinter()
     *
     * @param     mixed $printerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MagazineQuery The current query, for fluid interface
     */
    public function filterByPrinterId($printerId = null, $comparison = null)
    {
        if (is_array($printerId)) {
            $useMinMax = false;
            if (isset($printerId['min'])) {
                $this->addUsingAlias(MagazinePeer::PRINTER_ID, $printerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($printerId['max'])) {
                $this->addUsingAlias(MagazinePeer::PRINTER_ID, $printerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MagazinePeer::PRINTER_ID, $printerId, $comparison);
    }

    /**
     * Filter the query on the translator_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTranslatorId(1234); // WHERE translator_id = 1234
     * $query->filterByTranslatorId(array(12, 34)); // WHERE translator_id IN (12, 34)
     * $query->filterByTranslatorId(array('min' => 12)); // WHERE translator_id >= 12
     * $query->filterByTranslatorId(array('max' => 12)); // WHERE translator_id <= 12
     * </code>
     *
     * @see       filterByTranslator()
     *
     * @param     mixed $translatorId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MagazineQuery The current query, for fluid interface
     */
    public function filterByTranslatorId($translatorId = null, $comparison = null)
    {
        if (is_array($translatorId)) {
            $useMinMax = false;
            if (isset($translatorId['min'])) {
                $this->addUsingAlias(MagazinePeer::TRANSLATOR_ID, $translatorId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($translatorId['max'])) {
                $this->addUsingAlias(MagazinePeer::TRANSLATOR_ID, $translatorId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MagazinePeer::TRANSLATOR_ID, $translatorId, $comparison);
    }

    /**
     * Filter the query by a related Work object
     *
     * @param   Work|PropelObjectCollection $work The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 MagazineQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByWork($work, $comparison = null)
    {
        if ($work instanceof Work) {
            return $this
                ->addUsingAlias(MagazinePeer::WORK_ID, $work->getId(), $comparison);
        } elseif ($work instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MagazinePeer::WORK_ID, $work->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return MagazineQuery The current query, for fluid interface
     */
    public function joinWork($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return   \DTA\MetadataBundle\Model\WorkQuery A secondary query class using the current class as primary query
     */
    public function useWorkQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWork($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Work', '\DTA\MetadataBundle\Model\WorkQuery');
    }

    /**
     * Filter the query by a related Publisher object
     *
     * @param   Publisher|PropelObjectCollection $publisher The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 MagazineQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublisher($publisher, $comparison = null)
    {
        if ($publisher instanceof Publisher) {
            return $this
                ->addUsingAlias(MagazinePeer::PUBLISHER_ID, $publisher->getId(), $comparison);
        } elseif ($publisher instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MagazinePeer::PUBLISHER_ID, $publisher->toKeyValue('Id', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPublisher() only accepts arguments of type Publisher or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Publisher relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return MagazineQuery The current query, for fluid interface
     */
    public function joinPublisher($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Publisher');

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
            $this->addJoinObject($join, 'Publisher');
        }

        return $this;
    }

    /**
     * Use the Publisher relation Publisher object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\PublisherQuery A secondary query class using the current class as primary query
     */
    public function usePublisherQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPublisher($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publisher', '\DTA\MetadataBundle\Model\PublisherQuery');
    }

    /**
     * Filter the query by a related Printer object
     *
     * @param   Printer|PropelObjectCollection $printer The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 MagazineQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPrinter($printer, $comparison = null)
    {
        if ($printer instanceof Printer) {
            return $this
                ->addUsingAlias(MagazinePeer::PRINTER_ID, $printer->getId(), $comparison);
        } elseif ($printer instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MagazinePeer::PRINTER_ID, $printer->toKeyValue('Id', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPrinter() only accepts arguments of type Printer or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Printer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return MagazineQuery The current query, for fluid interface
     */
    public function joinPrinter($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Printer');

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
            $this->addJoinObject($join, 'Printer');
        }

        return $this;
    }

    /**
     * Use the Printer relation Printer object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\PrinterQuery A secondary query class using the current class as primary query
     */
    public function usePrinterQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPrinter($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Printer', '\DTA\MetadataBundle\Model\PrinterQuery');
    }

    /**
     * Filter the query by a related Translator object
     *
     * @param   Translator|PropelObjectCollection $translator The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 MagazineQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTranslator($translator, $comparison = null)
    {
        if ($translator instanceof Translator) {
            return $this
                ->addUsingAlias(MagazinePeer::TRANSLATOR_ID, $translator->getId(), $comparison);
        } elseif ($translator instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MagazinePeer::TRANSLATOR_ID, $translator->toKeyValue('Id', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTranslator() only accepts arguments of type Translator or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Translator relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return MagazineQuery The current query, for fluid interface
     */
    public function joinTranslator($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Translator');

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
            $this->addJoinObject($join, 'Translator');
        }

        return $this;
    }

    /**
     * Use the Translator relation Translator object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\TranslatorQuery A secondary query class using the current class as primary query
     */
    public function useTranslatorQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTranslator($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Translator', '\DTA\MetadataBundle\Model\TranslatorQuery');
    }

    /**
     * Filter the query by a related Relatedset object
     *
     * @param   Relatedset|PropelObjectCollection $relatedset The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 MagazineQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByRelatedset($relatedset, $comparison = null)
    {
        if ($relatedset instanceof Relatedset) {
            return $this
                ->addUsingAlias(MagazinePeer::RELATEDSET_ID, $relatedset->getId(), $comparison);
        } elseif ($relatedset instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MagazinePeer::RELATEDSET_ID, $relatedset->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByRelatedset() only accepts arguments of type Relatedset or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Relatedset relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return MagazineQuery The current query, for fluid interface
     */
    public function joinRelatedset($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Relatedset');

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
            $this->addJoinObject($join, 'Relatedset');
        }

        return $this;
    }

    /**
     * Use the Relatedset relation Relatedset object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\RelatedsetQuery A secondary query class using the current class as primary query
     */
    public function useRelatedsetQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinRelatedset($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Relatedset', '\DTA\MetadataBundle\Model\RelatedsetQuery');
    }

    /**
     * Filter the query by a related Title object
     *
     * @param   Title|PropelObjectCollection $title The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 MagazineQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTitle($title, $comparison = null)
    {
        if ($title instanceof Title) {
            return $this
                ->addUsingAlias(MagazinePeer::TITLE_ID, $title->getId(), $comparison);
        } elseif ($title instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MagazinePeer::TITLE_ID, $title->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return MagazineQuery The current query, for fluid interface
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
     * @return                 MagazineQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublishingcompany($publishingcompany, $comparison = null)
    {
        if ($publishingcompany instanceof Publishingcompany) {
            return $this
                ->addUsingAlias(MagazinePeer::PUBLISHINGCOMPANY_ID, $publishingcompany->getId(), $comparison);
        } elseif ($publishingcompany instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MagazinePeer::PUBLISHINGCOMPANY_ID, $publishingcompany->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return MagazineQuery The current query, for fluid interface
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
     * @return                 MagazineQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPlace($place, $comparison = null)
    {
        if ($place instanceof Place) {
            return $this
                ->addUsingAlias(MagazinePeer::PLACE_ID, $place->getId(), $comparison);
        } elseif ($place instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MagazinePeer::PLACE_ID, $place->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return MagazineQuery The current query, for fluid interface
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
     * @return                 MagazineQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDatespecification($datespecification, $comparison = null)
    {
        if ($datespecification instanceof Datespecification) {
            return $this
                ->addUsingAlias(MagazinePeer::DATESPECIFICATION_ID, $datespecification->getId(), $comparison);
        } elseif ($datespecification instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MagazinePeer::DATESPECIFICATION_ID, $datespecification->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return MagazineQuery The current query, for fluid interface
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
     * @param   Magazine $magazine Object to remove from the list of results
     *
     * @return MagazineQuery The current query, for fluid interface
     */
    public function prune($magazine = null)
    {
        if ($magazine) {
            $this->addUsingAlias(MagazinePeer::ID, $magazine->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
