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
use DTA\MetadataBundle\Model\Monograph;
use DTA\MetadataBundle\Model\Place;
use DTA\MetadataBundle\Model\Printer;
use DTA\MetadataBundle\Model\Publication;
use DTA\MetadataBundle\Model\PublicationPeer;
use DTA\MetadataBundle\Model\PublicationPublicationgroup;
use DTA\MetadataBundle\Model\PublicationQuery;
use DTA\MetadataBundle\Model\Publicationgroup;
use DTA\MetadataBundle\Model\Publisher;
use DTA\MetadataBundle\Model\Publishingcompany;
use DTA\MetadataBundle\Model\Relatedset;
use DTA\MetadataBundle\Model\Source;
use DTA\MetadataBundle\Model\Task;
use DTA\MetadataBundle\Model\Title;
use DTA\MetadataBundle\Model\Translator;
use DTA\MetadataBundle\Model\Work;

/**
 * @method PublicationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PublicationQuery orderByPrintrun($order = Criteria::ASC) Order by the printRun column
 * @method PublicationQuery orderByPrintruncomment($order = Criteria::ASC) Order by the printRunComment column
 * @method PublicationQuery orderByEdition($order = Criteria::ASC) Order by the edition column
 * @method PublicationQuery orderByNumpages($order = Criteria::ASC) Order by the numPages column
 * @method PublicationQuery orderByNumpagesnormed($order = Criteria::ASC) Order by the numPagesNormed column
 * @method PublicationQuery orderByBibliographiccitation($order = Criteria::ASC) Order by the bibliographicCitation column
 * @method PublicationQuery orderByTitleId($order = Criteria::ASC) Order by the title_id column
 * @method PublicationQuery orderByPublishingcompanyId($order = Criteria::ASC) Order by the publishingCompany_id column
 * @method PublicationQuery orderByPlaceId($order = Criteria::ASC) Order by the place_id column
 * @method PublicationQuery orderByPublicationdateId($order = Criteria::ASC) Order by the publicationDate_id column
 * @method PublicationQuery orderByOrigindateId($order = Criteria::ASC) Order by the originDate_id column
 * @method PublicationQuery orderByRelatedsetId($order = Criteria::ASC) Order by the relatedSet_id column
 * @method PublicationQuery orderByWorkId($order = Criteria::ASC) Order by the work_id column
 * @method PublicationQuery orderByPublisherId($order = Criteria::ASC) Order by the publisher_id column
 * @method PublicationQuery orderByPrinterId($order = Criteria::ASC) Order by the printer_id column
 * @method PublicationQuery orderByTranslatorId($order = Criteria::ASC) Order by the translator_id column
 * @method PublicationQuery orderByDescendantClass($order = Criteria::ASC) Order by the descendant_class column
 *
 * @method PublicationQuery groupById() Group by the id column
 * @method PublicationQuery groupByPrintrun() Group by the printRun column
 * @method PublicationQuery groupByPrintruncomment() Group by the printRunComment column
 * @method PublicationQuery groupByEdition() Group by the edition column
 * @method PublicationQuery groupByNumpages() Group by the numPages column
 * @method PublicationQuery groupByNumpagesnormed() Group by the numPagesNormed column
 * @method PublicationQuery groupByBibliographiccitation() Group by the bibliographicCitation column
 * @method PublicationQuery groupByTitleId() Group by the title_id column
 * @method PublicationQuery groupByPublishingcompanyId() Group by the publishingCompany_id column
 * @method PublicationQuery groupByPlaceId() Group by the place_id column
 * @method PublicationQuery groupByPublicationdateId() Group by the publicationDate_id column
 * @method PublicationQuery groupByOrigindateId() Group by the originDate_id column
 * @method PublicationQuery groupByRelatedsetId() Group by the relatedSet_id column
 * @method PublicationQuery groupByWorkId() Group by the work_id column
 * @method PublicationQuery groupByPublisherId() Group by the publisher_id column
 * @method PublicationQuery groupByPrinterId() Group by the printer_id column
 * @method PublicationQuery groupByTranslatorId() Group by the translator_id column
 * @method PublicationQuery groupByDescendantClass() Group by the descendant_class column
 *
 * @method PublicationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PublicationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PublicationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PublicationQuery leftJoinWork($relationAlias = null) Adds a LEFT JOIN clause to the query using the Work relation
 * @method PublicationQuery rightJoinWork($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Work relation
 * @method PublicationQuery innerJoinWork($relationAlias = null) Adds a INNER JOIN clause to the query using the Work relation
 *
 * @method PublicationQuery leftJoinPublisher($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publisher relation
 * @method PublicationQuery rightJoinPublisher($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publisher relation
 * @method PublicationQuery innerJoinPublisher($relationAlias = null) Adds a INNER JOIN clause to the query using the Publisher relation
 *
 * @method PublicationQuery leftJoinPrinter($relationAlias = null) Adds a LEFT JOIN clause to the query using the Printer relation
 * @method PublicationQuery rightJoinPrinter($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Printer relation
 * @method PublicationQuery innerJoinPrinter($relationAlias = null) Adds a INNER JOIN clause to the query using the Printer relation
 *
 * @method PublicationQuery leftJoinTranslator($relationAlias = null) Adds a LEFT JOIN clause to the query using the Translator relation
 * @method PublicationQuery rightJoinTranslator($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Translator relation
 * @method PublicationQuery innerJoinTranslator($relationAlias = null) Adds a INNER JOIN clause to the query using the Translator relation
 *
 * @method PublicationQuery leftJoinRelatedset($relationAlias = null) Adds a LEFT JOIN clause to the query using the Relatedset relation
 * @method PublicationQuery rightJoinRelatedset($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Relatedset relation
 * @method PublicationQuery innerJoinRelatedset($relationAlias = null) Adds a INNER JOIN clause to the query using the Relatedset relation
 *
 * @method PublicationQuery leftJoinTitle($relationAlias = null) Adds a LEFT JOIN clause to the query using the Title relation
 * @method PublicationQuery rightJoinTitle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Title relation
 * @method PublicationQuery innerJoinTitle($relationAlias = null) Adds a INNER JOIN clause to the query using the Title relation
 *
 * @method PublicationQuery leftJoinPublishingcompany($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publishingcompany relation
 * @method PublicationQuery rightJoinPublishingcompany($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publishingcompany relation
 * @method PublicationQuery innerJoinPublishingcompany($relationAlias = null) Adds a INNER JOIN clause to the query using the Publishingcompany relation
 *
 * @method PublicationQuery leftJoinPlace($relationAlias = null) Adds a LEFT JOIN clause to the query using the Place relation
 * @method PublicationQuery rightJoinPlace($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Place relation
 * @method PublicationQuery innerJoinPlace($relationAlias = null) Adds a INNER JOIN clause to the query using the Place relation
 *
 * @method PublicationQuery leftJoinDatespecificationRelatedByPublicationdateId($relationAlias = null) Adds a LEFT JOIN clause to the query using the DatespecificationRelatedByPublicationdateId relation
 * @method PublicationQuery rightJoinDatespecificationRelatedByPublicationdateId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DatespecificationRelatedByPublicationdateId relation
 * @method PublicationQuery innerJoinDatespecificationRelatedByPublicationdateId($relationAlias = null) Adds a INNER JOIN clause to the query using the DatespecificationRelatedByPublicationdateId relation
 *
 * @method PublicationQuery leftJoinDatespecificationRelatedByOrigindateId($relationAlias = null) Adds a LEFT JOIN clause to the query using the DatespecificationRelatedByOrigindateId relation
 * @method PublicationQuery rightJoinDatespecificationRelatedByOrigindateId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DatespecificationRelatedByOrigindateId relation
 * @method PublicationQuery innerJoinDatespecificationRelatedByOrigindateId($relationAlias = null) Adds a INNER JOIN clause to the query using the DatespecificationRelatedByOrigindateId relation
 *
 * @method PublicationQuery leftJoinMonograph($relationAlias = null) Adds a LEFT JOIN clause to the query using the Monograph relation
 * @method PublicationQuery rightJoinMonograph($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Monograph relation
 * @method PublicationQuery innerJoinMonograph($relationAlias = null) Adds a INNER JOIN clause to the query using the Monograph relation
 *
 * @method PublicationQuery leftJoinPublicationPublicationgroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationPublicationgroup relation
 * @method PublicationQuery rightJoinPublicationPublicationgroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationPublicationgroup relation
 * @method PublicationQuery innerJoinPublicationPublicationgroup($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationPublicationgroup relation
 *
 * @method PublicationQuery leftJoinSource($relationAlias = null) Adds a LEFT JOIN clause to the query using the Source relation
 * @method PublicationQuery rightJoinSource($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Source relation
 * @method PublicationQuery innerJoinSource($relationAlias = null) Adds a INNER JOIN clause to the query using the Source relation
 *
 * @method PublicationQuery leftJoinTask($relationAlias = null) Adds a LEFT JOIN clause to the query using the Task relation
 * @method PublicationQuery rightJoinTask($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Task relation
 * @method PublicationQuery innerJoinTask($relationAlias = null) Adds a INNER JOIN clause to the query using the Task relation
 *
 * @method Publication findOne(PropelPDO $con = null) Return the first Publication matching the query
 * @method Publication findOneOrCreate(PropelPDO $con = null) Return the first Publication matching the query, or a new Publication object populated from the query conditions when no match is found
 *
 * @method Publication findOneByPrintrun(string $printRun) Return the first Publication filtered by the printRun column
 * @method Publication findOneByPrintruncomment(string $printRunComment) Return the first Publication filtered by the printRunComment column
 * @method Publication findOneByEdition(string $edition) Return the first Publication filtered by the edition column
 * @method Publication findOneByNumpages(int $numPages) Return the first Publication filtered by the numPages column
 * @method Publication findOneByNumpagesnormed(int $numPagesNormed) Return the first Publication filtered by the numPagesNormed column
 * @method Publication findOneByBibliographiccitation(string $bibliographicCitation) Return the first Publication filtered by the bibliographicCitation column
 * @method Publication findOneByTitleId(int $title_id) Return the first Publication filtered by the title_id column
 * @method Publication findOneByPublishingcompanyId(int $publishingCompany_id) Return the first Publication filtered by the publishingCompany_id column
 * @method Publication findOneByPlaceId(int $place_id) Return the first Publication filtered by the place_id column
 * @method Publication findOneByPublicationdateId(int $publicationDate_id) Return the first Publication filtered by the publicationDate_id column
 * @method Publication findOneByOrigindateId(int $originDate_id) Return the first Publication filtered by the originDate_id column
 * @method Publication findOneByRelatedsetId(int $relatedSet_id) Return the first Publication filtered by the relatedSet_id column
 * @method Publication findOneByWorkId(int $work_id) Return the first Publication filtered by the work_id column
 * @method Publication findOneByPublisherId(int $publisher_id) Return the first Publication filtered by the publisher_id column
 * @method Publication findOneByPrinterId(int $printer_id) Return the first Publication filtered by the printer_id column
 * @method Publication findOneByTranslatorId(int $translator_id) Return the first Publication filtered by the translator_id column
 * @method Publication findOneByDescendantClass(string $descendant_class) Return the first Publication filtered by the descendant_class column
 *
 * @method array findById(int $id) Return Publication objects filtered by the id column
 * @method array findByPrintrun(string $printRun) Return Publication objects filtered by the printRun column
 * @method array findByPrintruncomment(string $printRunComment) Return Publication objects filtered by the printRunComment column
 * @method array findByEdition(string $edition) Return Publication objects filtered by the edition column
 * @method array findByNumpages(int $numPages) Return Publication objects filtered by the numPages column
 * @method array findByNumpagesnormed(int $numPagesNormed) Return Publication objects filtered by the numPagesNormed column
 * @method array findByBibliographiccitation(string $bibliographicCitation) Return Publication objects filtered by the bibliographicCitation column
 * @method array findByTitleId(int $title_id) Return Publication objects filtered by the title_id column
 * @method array findByPublishingcompanyId(int $publishingCompany_id) Return Publication objects filtered by the publishingCompany_id column
 * @method array findByPlaceId(int $place_id) Return Publication objects filtered by the place_id column
 * @method array findByPublicationdateId(int $publicationDate_id) Return Publication objects filtered by the publicationDate_id column
 * @method array findByOrigindateId(int $originDate_id) Return Publication objects filtered by the originDate_id column
 * @method array findByRelatedsetId(int $relatedSet_id) Return Publication objects filtered by the relatedSet_id column
 * @method array findByWorkId(int $work_id) Return Publication objects filtered by the work_id column
 * @method array findByPublisherId(int $publisher_id) Return Publication objects filtered by the publisher_id column
 * @method array findByPrinterId(int $printer_id) Return Publication objects filtered by the printer_id column
 * @method array findByTranslatorId(int $translator_id) Return Publication objects filtered by the translator_id column
 * @method array findByDescendantClass(string $descendant_class) Return Publication objects filtered by the descendant_class column
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
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Publication', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PublicationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PublicationQuery|Criteria $criteria Optional Criteria to build the query from
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
     * @return                 Publication A model object, or null if the key is not found
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
     * @return                 Publication A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `printRun`, `printRunComment`, `edition`, `numPages`, `numPagesNormed`, `bibliographicCitation`, `title_id`, `publishingCompany_id`, `place_id`, `publicationDate_id`, `originDate_id`, `relatedSet_id`, `work_id`, `publisher_id`, `printer_id`, `translator_id`, `descendant_class` FROM `publication` WHERE `id` = :p0';
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PublicationPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PublicationPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::ID, $id, $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByTitleId($titleId = null, $comparison = null)
    {
        if (is_array($titleId)) {
            $useMinMax = false;
            if (isset($titleId['min'])) {
                $this->addUsingAlias(PublicationPeer::TITLE_ID, $titleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($titleId['max'])) {
                $this->addUsingAlias(PublicationPeer::TITLE_ID, $titleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::TITLE_ID, $titleId, $comparison);
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
     * Filter the query on the publicationDate_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublicationdateId(1234); // WHERE publicationDate_id = 1234
     * $query->filterByPublicationdateId(array(12, 34)); // WHERE publicationDate_id IN (12, 34)
     * $query->filterByPublicationdateId(array('min' => 12)); // WHERE publicationDate_id >= 12
     * $query->filterByPublicationdateId(array('max' => 12)); // WHERE publicationDate_id <= 12
     * </code>
     *
     * @see       filterByDatespecificationRelatedByPublicationdateId()
     *
     * @param     mixed $publicationdateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByPublicationdateId($publicationdateId = null, $comparison = null)
    {
        if (is_array($publicationdateId)) {
            $useMinMax = false;
            if (isset($publicationdateId['min'])) {
                $this->addUsingAlias(PublicationPeer::PUBLICATIONDATE_ID, $publicationdateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publicationdateId['max'])) {
                $this->addUsingAlias(PublicationPeer::PUBLICATIONDATE_ID, $publicationdateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::PUBLICATIONDATE_ID, $publicationdateId, $comparison);
    }

    /**
     * Filter the query on the originDate_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOrigindateId(1234); // WHERE originDate_id = 1234
     * $query->filterByOrigindateId(array(12, 34)); // WHERE originDate_id IN (12, 34)
     * $query->filterByOrigindateId(array('min' => 12)); // WHERE originDate_id >= 12
     * $query->filterByOrigindateId(array('max' => 12)); // WHERE originDate_id <= 12
     * </code>
     *
     * @see       filterByDatespecificationRelatedByOrigindateId()
     *
     * @param     mixed $origindateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByOrigindateId($origindateId = null, $comparison = null)
    {
        if (is_array($origindateId)) {
            $useMinMax = false;
            if (isset($origindateId['min'])) {
                $this->addUsingAlias(PublicationPeer::ORIGINDATE_ID, $origindateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($origindateId['max'])) {
                $this->addUsingAlias(PublicationPeer::ORIGINDATE_ID, $origindateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::ORIGINDATE_ID, $origindateId, $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByRelatedsetId($relatedsetId = null, $comparison = null)
    {
        if (is_array($relatedsetId)) {
            $useMinMax = false;
            if (isset($relatedsetId['min'])) {
                $this->addUsingAlias(PublicationPeer::RELATEDSET_ID, $relatedsetId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($relatedsetId['max'])) {
                $this->addUsingAlias(PublicationPeer::RELATEDSET_ID, $relatedsetId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::RELATEDSET_ID, $relatedsetId, $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByWorkId($workId = null, $comparison = null)
    {
        if (is_array($workId)) {
            $useMinMax = false;
            if (isset($workId['min'])) {
                $this->addUsingAlias(PublicationPeer::WORK_ID, $workId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($workId['max'])) {
                $this->addUsingAlias(PublicationPeer::WORK_ID, $workId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::WORK_ID, $workId, $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, $comparison = null)
    {
        if (is_array($publisherId)) {
            $useMinMax = false;
            if (isset($publisherId['min'])) {
                $this->addUsingAlias(PublicationPeer::PUBLISHER_ID, $publisherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publisherId['max'])) {
                $this->addUsingAlias(PublicationPeer::PUBLISHER_ID, $publisherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::PUBLISHER_ID, $publisherId, $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByPrinterId($printerId = null, $comparison = null)
    {
        if (is_array($printerId)) {
            $useMinMax = false;
            if (isset($printerId['min'])) {
                $this->addUsingAlias(PublicationPeer::PRINTER_ID, $printerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($printerId['max'])) {
                $this->addUsingAlias(PublicationPeer::PRINTER_ID, $printerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::PRINTER_ID, $printerId, $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByTranslatorId($translatorId = null, $comparison = null)
    {
        if (is_array($translatorId)) {
            $useMinMax = false;
            if (isset($translatorId['min'])) {
                $this->addUsingAlias(PublicationPeer::TRANSLATOR_ID, $translatorId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($translatorId['max'])) {
                $this->addUsingAlias(PublicationPeer::TRANSLATOR_ID, $translatorId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::TRANSLATOR_ID, $translatorId, $comparison);
    }

    /**
     * Filter the query on the descendant_class column
     *
     * Example usage:
     * <code>
     * $query->filterByDescendantClass('fooValue');   // WHERE descendant_class = 'fooValue'
     * $query->filterByDescendantClass('%fooValue%'); // WHERE descendant_class LIKE '%fooValue%'
     * </code>
     *
     * @param     string $descendantClass The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByDescendantClass($descendantClass = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($descendantClass)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $descendantClass)) {
                $descendantClass = str_replace('*', '%', $descendantClass);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationPeer::DESCENDANT_CLASS, $descendantClass, $comparison);
    }

    /**
     * Filter the query by a related Work object
     *
     * @param   Work|PropelObjectCollection $work The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByWork($work, $comparison = null)
    {
        if ($work instanceof Work) {
            return $this
                ->addUsingAlias(PublicationPeer::WORK_ID, $work->getId(), $comparison);
        } elseif ($work instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::WORK_ID, $work->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublisher($publisher, $comparison = null)
    {
        if ($publisher instanceof Publisher) {
            return $this
                ->addUsingAlias(PublicationPeer::PUBLISHER_ID, $publisher->getId(), $comparison);
        } elseif ($publisher instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::PUBLISHER_ID, $publisher->toKeyValue('Id', 'Id'), $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPrinter($printer, $comparison = null)
    {
        if ($printer instanceof Printer) {
            return $this
                ->addUsingAlias(PublicationPeer::PRINTER_ID, $printer->getId(), $comparison);
        } elseif ($printer instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::PRINTER_ID, $printer->toKeyValue('Id', 'Id'), $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTranslator($translator, $comparison = null)
    {
        if ($translator instanceof Translator) {
            return $this
                ->addUsingAlias(PublicationPeer::TRANSLATOR_ID, $translator->getId(), $comparison);
        } elseif ($translator instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::TRANSLATOR_ID, $translator->toKeyValue('Id', 'Id'), $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByRelatedset($relatedset, $comparison = null)
    {
        if ($relatedset instanceof Relatedset) {
            return $this
                ->addUsingAlias(PublicationPeer::RELATEDSET_ID, $relatedset->getId(), $comparison);
        } elseif ($relatedset instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::RELATEDSET_ID, $relatedset->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTitle($title, $comparison = null)
    {
        if ($title instanceof Title) {
            return $this
                ->addUsingAlias(PublicationPeer::TITLE_ID, $title->getId(), $comparison);
        } elseif ($title instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::TITLE_ID, $title->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
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
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
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
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDatespecificationRelatedByPublicationdateId($datespecification, $comparison = null)
    {
        if ($datespecification instanceof Datespecification) {
            return $this
                ->addUsingAlias(PublicationPeer::PUBLICATIONDATE_ID, $datespecification->getId(), $comparison);
        } elseif ($datespecification instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::PUBLICATIONDATE_ID, $datespecification->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDatespecificationRelatedByPublicationdateId() only accepts arguments of type Datespecification or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DatespecificationRelatedByPublicationdateId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinDatespecificationRelatedByPublicationdateId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DatespecificationRelatedByPublicationdateId');

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
            $this->addJoinObject($join, 'DatespecificationRelatedByPublicationdateId');
        }

        return $this;
    }

    /**
     * Use the DatespecificationRelatedByPublicationdateId relation Datespecification object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\DatespecificationQuery A secondary query class using the current class as primary query
     */
    public function useDatespecificationRelatedByPublicationdateIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDatespecificationRelatedByPublicationdateId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DatespecificationRelatedByPublicationdateId', '\DTA\MetadataBundle\Model\DatespecificationQuery');
    }

    /**
     * Filter the query by a related Datespecification object
     *
     * @param   Datespecification|PropelObjectCollection $datespecification The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDatespecificationRelatedByOrigindateId($datespecification, $comparison = null)
    {
        if ($datespecification instanceof Datespecification) {
            return $this
                ->addUsingAlias(PublicationPeer::ORIGINDATE_ID, $datespecification->getId(), $comparison);
        } elseif ($datespecification instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::ORIGINDATE_ID, $datespecification->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDatespecificationRelatedByOrigindateId() only accepts arguments of type Datespecification or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DatespecificationRelatedByOrigindateId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinDatespecificationRelatedByOrigindateId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DatespecificationRelatedByOrigindateId');

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
            $this->addJoinObject($join, 'DatespecificationRelatedByOrigindateId');
        }

        return $this;
    }

    /**
     * Use the DatespecificationRelatedByOrigindateId relation Datespecification object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\DatespecificationQuery A secondary query class using the current class as primary query
     */
    public function useDatespecificationRelatedByOrigindateIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDatespecificationRelatedByOrigindateId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DatespecificationRelatedByOrigindateId', '\DTA\MetadataBundle\Model\DatespecificationQuery');
    }

    /**
     * Filter the query by a related Monograph object
     *
     * @param   Monograph|PropelObjectCollection $monograph  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
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
     * @return   \DTA\MetadataBundle\Model\MonographQuery A secondary query class using the current class as primary query
     */
    public function useMonographQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMonograph($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Monograph', '\DTA\MetadataBundle\Model\MonographQuery');
    }

    /**
     * Filter the query by a related PublicationPublicationgroup object
     *
     * @param   PublicationPublicationgroup|PropelObjectCollection $publicationPublicationgroup  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationPublicationgroup($publicationPublicationgroup, $comparison = null)
    {
        if ($publicationPublicationgroup instanceof PublicationPublicationgroup) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $publicationPublicationgroup->getPublicationId(), $comparison);
        } elseif ($publicationPublicationgroup instanceof PropelObjectCollection) {
            return $this
                ->usePublicationPublicationgroupQuery()
                ->filterByPrimaryKeys($publicationPublicationgroup->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationPublicationgroup() only accepts arguments of type PublicationPublicationgroup or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationPublicationgroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinPublicationPublicationgroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationPublicationgroup');

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
            $this->addJoinObject($join, 'PublicationPublicationgroup');
        }

        return $this;
    }

    /**
     * Use the PublicationPublicationgroup relation PublicationPublicationgroup object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\PublicationPublicationgroupQuery A secondary query class using the current class as primary query
     */
    public function usePublicationPublicationgroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationPublicationgroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationPublicationgroup', '\DTA\MetadataBundle\Model\PublicationPublicationgroupQuery');
    }

    /**
     * Filter the query by a related Source object
     *
     * @param   Source|PropelObjectCollection $source  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySource($source, $comparison = null)
    {
        if ($source instanceof Source) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $source->getPublicationId(), $comparison);
        } elseif ($source instanceof PropelObjectCollection) {
            return $this
                ->useSourceQuery()
                ->filterByPrimaryKeys($source->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySource() only accepts arguments of type Source or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Source relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinSource($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Source');

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
            $this->addJoinObject($join, 'Source');
        }

        return $this;
    }

    /**
     * Use the Source relation Source object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\SourceQuery A secondary query class using the current class as primary query
     */
    public function useSourceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSource($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Source', '\DTA\MetadataBundle\Model\SourceQuery');
    }

    /**
     * Filter the query by a related Task object
     *
     * @param   Task|PropelObjectCollection $task  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTask($task, $comparison = null)
    {
        if ($task instanceof Task) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $task->getPublicationId(), $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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
     * @return   \DTA\MetadataBundle\Model\TaskQuery A secondary query class using the current class as primary query
     */
    public function useTaskQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTask($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Task', '\DTA\MetadataBundle\Model\TaskQuery');
    }

    /**
     * Filter the query by a related Publicationgroup object
     * using the publication_publicationGroup table as cross reference
     *
     * @param   Publicationgroup $publicationgroup the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationQuery The current query, for fluid interface
     */
    public function filterByPublicationgroup($publicationgroup, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePublicationPublicationgroupQuery()
            ->filterByPublicationgroup($publicationgroup, $comparison)
            ->endUse();
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
