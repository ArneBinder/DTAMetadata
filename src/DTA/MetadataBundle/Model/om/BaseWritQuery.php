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
use DTA\MetadataBundle\Model\Corpus;
use DTA\MetadataBundle\Model\Printer;
use DTA\MetadataBundle\Model\Publication;
use DTA\MetadataBundle\Model\Publisher;
use DTA\MetadataBundle\Model\Relatedset;
use DTA\MetadataBundle\Model\Source;
use DTA\MetadataBundle\Model\Task;
use DTA\MetadataBundle\Model\Translator;
use DTA\MetadataBundle\Model\Work;
use DTA\MetadataBundle\Model\Writ;
use DTA\MetadataBundle\Model\WritPeer;
use DTA\MetadataBundle\Model\WritQuery;
use DTA\MetadataBundle\Model\WritWritgroup;
use DTA\MetadataBundle\Model\Writgroup;

/**
 * @method WritQuery orderById($order = Criteria::ASC) Order by the id column
 * @method WritQuery orderByWorkId($order = Criteria::ASC) Order by the work_id column
 * @method WritQuery orderByPublicationId($order = Criteria::ASC) Order by the publication_id column
 * @method WritQuery orderByPublisherId($order = Criteria::ASC) Order by the publisher_id column
 * @method WritQuery orderByPrinterId($order = Criteria::ASC) Order by the printer_id column
 * @method WritQuery orderByTranslatorId($order = Criteria::ASC) Order by the translator_id column
 * @method WritQuery orderByNumpages($order = Criteria::ASC) Order by the numPages column
 * @method WritQuery orderByRelatedsetId($order = Criteria::ASC) Order by the relatedSet_id column
 *
 * @method WritQuery groupById() Group by the id column
 * @method WritQuery groupByWorkId() Group by the work_id column
 * @method WritQuery groupByPublicationId() Group by the publication_id column
 * @method WritQuery groupByPublisherId() Group by the publisher_id column
 * @method WritQuery groupByPrinterId() Group by the printer_id column
 * @method WritQuery groupByTranslatorId() Group by the translator_id column
 * @method WritQuery groupByNumpages() Group by the numPages column
 * @method WritQuery groupByRelatedsetId() Group by the relatedSet_id column
 *
 * @method WritQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method WritQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method WritQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method WritQuery leftJoinWork($relationAlias = null) Adds a LEFT JOIN clause to the query using the Work relation
 * @method WritQuery rightJoinWork($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Work relation
 * @method WritQuery innerJoinWork($relationAlias = null) Adds a INNER JOIN clause to the query using the Work relation
 *
 * @method WritQuery leftJoinPublisher($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publisher relation
 * @method WritQuery rightJoinPublisher($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publisher relation
 * @method WritQuery innerJoinPublisher($relationAlias = null) Adds a INNER JOIN clause to the query using the Publisher relation
 *
 * @method WritQuery leftJoinPrinter($relationAlias = null) Adds a LEFT JOIN clause to the query using the Printer relation
 * @method WritQuery rightJoinPrinter($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Printer relation
 * @method WritQuery innerJoinPrinter($relationAlias = null) Adds a INNER JOIN clause to the query using the Printer relation
 *
 * @method WritQuery leftJoinTranslator($relationAlias = null) Adds a LEFT JOIN clause to the query using the Translator relation
 * @method WritQuery rightJoinTranslator($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Translator relation
 * @method WritQuery innerJoinTranslator($relationAlias = null) Adds a INNER JOIN clause to the query using the Translator relation
 *
 * @method WritQuery leftJoinPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publication relation
 * @method WritQuery rightJoinPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publication relation
 * @method WritQuery innerJoinPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the Publication relation
 *
 * @method WritQuery leftJoinRelatedset($relationAlias = null) Adds a LEFT JOIN clause to the query using the Relatedset relation
 * @method WritQuery rightJoinRelatedset($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Relatedset relation
 * @method WritQuery innerJoinRelatedset($relationAlias = null) Adds a INNER JOIN clause to the query using the Relatedset relation
 *
 * @method WritQuery leftJoinWritWritgroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the WritWritgroup relation
 * @method WritQuery rightJoinWritWritgroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WritWritgroup relation
 * @method WritQuery innerJoinWritWritgroup($relationAlias = null) Adds a INNER JOIN clause to the query using the WritWritgroup relation
 *
 * @method WritQuery leftJoinCorpus($relationAlias = null) Adds a LEFT JOIN clause to the query using the Corpus relation
 * @method WritQuery rightJoinCorpus($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Corpus relation
 * @method WritQuery innerJoinCorpus($relationAlias = null) Adds a INNER JOIN clause to the query using the Corpus relation
 *
 * @method WritQuery leftJoinSource($relationAlias = null) Adds a LEFT JOIN clause to the query using the Source relation
 * @method WritQuery rightJoinSource($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Source relation
 * @method WritQuery innerJoinSource($relationAlias = null) Adds a INNER JOIN clause to the query using the Source relation
 *
 * @method WritQuery leftJoinTask($relationAlias = null) Adds a LEFT JOIN clause to the query using the Task relation
 * @method WritQuery rightJoinTask($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Task relation
 * @method WritQuery innerJoinTask($relationAlias = null) Adds a INNER JOIN clause to the query using the Task relation
 *
 * @method Writ findOne(PropelPDO $con = null) Return the first Writ matching the query
 * @method Writ findOneOrCreate(PropelPDO $con = null) Return the first Writ matching the query, or a new Writ object populated from the query conditions when no match is found
 *
 * @method Writ findOneByWorkId(int $work_id) Return the first Writ filtered by the work_id column
 * @method Writ findOneByPublicationId(int $publication_id) Return the first Writ filtered by the publication_id column
 * @method Writ findOneByPublisherId(int $publisher_id) Return the first Writ filtered by the publisher_id column
 * @method Writ findOneByPrinterId(int $printer_id) Return the first Writ filtered by the printer_id column
 * @method Writ findOneByTranslatorId(int $translator_id) Return the first Writ filtered by the translator_id column
 * @method Writ findOneByNumpages(int $numPages) Return the first Writ filtered by the numPages column
 * @method Writ findOneByRelatedsetId(int $relatedSet_id) Return the first Writ filtered by the relatedSet_id column
 *
 * @method array findById(int $id) Return Writ objects filtered by the id column
 * @method array findByWorkId(int $work_id) Return Writ objects filtered by the work_id column
 * @method array findByPublicationId(int $publication_id) Return Writ objects filtered by the publication_id column
 * @method array findByPublisherId(int $publisher_id) Return Writ objects filtered by the publisher_id column
 * @method array findByPrinterId(int $printer_id) Return Writ objects filtered by the printer_id column
 * @method array findByTranslatorId(int $translator_id) Return Writ objects filtered by the translator_id column
 * @method array findByNumpages(int $numPages) Return Writ objects filtered by the numPages column
 * @method array findByRelatedsetId(int $relatedSet_id) Return Writ objects filtered by the relatedSet_id column
 */
abstract class BaseWritQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseWritQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Writ', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new WritQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   WritQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return WritQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof WritQuery) {
            return $criteria;
        }
        $query = new WritQuery();
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
     * @return   Writ|Writ[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = WritPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Writ A model object, or null if the key is not found
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
     * @return                 Writ A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `work_id`, `publication_id`, `publisher_id`, `printer_id`, `translator_id`, `numPages`, `relatedSet_id` FROM `writ` WHERE `id` = :p0';
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
            $obj = new Writ();
            $obj->hydrate($row);
            WritPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Writ|Writ[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Writ[]|mixed the list of results, formatted by the current formatter
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
     * @return WritQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(WritPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return WritQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(WritPeer::ID, $keys, Criteria::IN);
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
     * @return WritQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(WritPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(WritPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WritPeer::ID, $id, $comparison);
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
     * @return WritQuery The current query, for fluid interface
     */
    public function filterByWorkId($workId = null, $comparison = null)
    {
        if (is_array($workId)) {
            $useMinMax = false;
            if (isset($workId['min'])) {
                $this->addUsingAlias(WritPeer::WORK_ID, $workId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($workId['max'])) {
                $this->addUsingAlias(WritPeer::WORK_ID, $workId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WritPeer::WORK_ID, $workId, $comparison);
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
     * @return WritQuery The current query, for fluid interface
     */
    public function filterByPublicationId($publicationId = null, $comparison = null)
    {
        if (is_array($publicationId)) {
            $useMinMax = false;
            if (isset($publicationId['min'])) {
                $this->addUsingAlias(WritPeer::PUBLICATION_ID, $publicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publicationId['max'])) {
                $this->addUsingAlias(WritPeer::PUBLICATION_ID, $publicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WritPeer::PUBLICATION_ID, $publicationId, $comparison);
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
     * @return WritQuery The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, $comparison = null)
    {
        if (is_array($publisherId)) {
            $useMinMax = false;
            if (isset($publisherId['min'])) {
                $this->addUsingAlias(WritPeer::PUBLISHER_ID, $publisherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publisherId['max'])) {
                $this->addUsingAlias(WritPeer::PUBLISHER_ID, $publisherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WritPeer::PUBLISHER_ID, $publisherId, $comparison);
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
     * @return WritQuery The current query, for fluid interface
     */
    public function filterByPrinterId($printerId = null, $comparison = null)
    {
        if (is_array($printerId)) {
            $useMinMax = false;
            if (isset($printerId['min'])) {
                $this->addUsingAlias(WritPeer::PRINTER_ID, $printerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($printerId['max'])) {
                $this->addUsingAlias(WritPeer::PRINTER_ID, $printerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WritPeer::PRINTER_ID, $printerId, $comparison);
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
     * @return WritQuery The current query, for fluid interface
     */
    public function filterByTranslatorId($translatorId = null, $comparison = null)
    {
        if (is_array($translatorId)) {
            $useMinMax = false;
            if (isset($translatorId['min'])) {
                $this->addUsingAlias(WritPeer::TRANSLATOR_ID, $translatorId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($translatorId['max'])) {
                $this->addUsingAlias(WritPeer::TRANSLATOR_ID, $translatorId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WritPeer::TRANSLATOR_ID, $translatorId, $comparison);
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
     * @return WritQuery The current query, for fluid interface
     */
    public function filterByNumpages($numpages = null, $comparison = null)
    {
        if (is_array($numpages)) {
            $useMinMax = false;
            if (isset($numpages['min'])) {
                $this->addUsingAlias(WritPeer::NUMPAGES, $numpages['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numpages['max'])) {
                $this->addUsingAlias(WritPeer::NUMPAGES, $numpages['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WritPeer::NUMPAGES, $numpages, $comparison);
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
     * @return WritQuery The current query, for fluid interface
     */
    public function filterByRelatedsetId($relatedsetId = null, $comparison = null)
    {
        if (is_array($relatedsetId)) {
            $useMinMax = false;
            if (isset($relatedsetId['min'])) {
                $this->addUsingAlias(WritPeer::RELATEDSET_ID, $relatedsetId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($relatedsetId['max'])) {
                $this->addUsingAlias(WritPeer::RELATEDSET_ID, $relatedsetId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WritPeer::RELATEDSET_ID, $relatedsetId, $comparison);
    }

    /**
     * Filter the query by a related Work object
     *
     * @param   Work|PropelObjectCollection $work The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WritQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByWork($work, $comparison = null)
    {
        if ($work instanceof Work) {
            return $this
                ->addUsingAlias(WritPeer::WORK_ID, $work->getId(), $comparison);
        } elseif ($work instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WritPeer::WORK_ID, $work->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return WritQuery The current query, for fluid interface
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
     * @return                 WritQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublisher($publisher, $comparison = null)
    {
        if ($publisher instanceof Publisher) {
            return $this
                ->addUsingAlias(WritPeer::PUBLISHER_ID, $publisher->getId(), $comparison);
        } elseif ($publisher instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WritPeer::PUBLISHER_ID, $publisher->toKeyValue('Id', 'Id'), $comparison);
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
     * @return WritQuery The current query, for fluid interface
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
     * @return                 WritQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPrinter($printer, $comparison = null)
    {
        if ($printer instanceof Printer) {
            return $this
                ->addUsingAlias(WritPeer::PRINTER_ID, $printer->getId(), $comparison);
        } elseif ($printer instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WritPeer::PRINTER_ID, $printer->toKeyValue('Id', 'Id'), $comparison);
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
     * @return WritQuery The current query, for fluid interface
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
     * @return                 WritQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTranslator($translator, $comparison = null)
    {
        if ($translator instanceof Translator) {
            return $this
                ->addUsingAlias(WritPeer::TRANSLATOR_ID, $translator->getId(), $comparison);
        } elseif ($translator instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WritPeer::TRANSLATOR_ID, $translator->toKeyValue('Id', 'Id'), $comparison);
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
     * @return WritQuery The current query, for fluid interface
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
     * Filter the query by a related Publication object
     *
     * @param   Publication|PropelObjectCollection $publication The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WritQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublication($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(WritPeer::PUBLICATION_ID, $publication->getId(), $comparison);
        } elseif ($publication instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WritPeer::PUBLICATION_ID, $publication->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return WritQuery The current query, for fluid interface
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
     * @return   \DTA\MetadataBundle\Model\PublicationQuery A secondary query class using the current class as primary query
     */
    public function usePublicationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publication', '\DTA\MetadataBundle\Model\PublicationQuery');
    }

    /**
     * Filter the query by a related Relatedset object
     *
     * @param   Relatedset|PropelObjectCollection $relatedset The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WritQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByRelatedset($relatedset, $comparison = null)
    {
        if ($relatedset instanceof Relatedset) {
            return $this
                ->addUsingAlias(WritPeer::RELATEDSET_ID, $relatedset->getId(), $comparison);
        } elseif ($relatedset instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WritPeer::RELATEDSET_ID, $relatedset->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return WritQuery The current query, for fluid interface
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
     * Filter the query by a related WritWritgroup object
     *
     * @param   WritWritgroup|PropelObjectCollection $writWritgroup  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WritQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByWritWritgroup($writWritgroup, $comparison = null)
    {
        if ($writWritgroup instanceof WritWritgroup) {
            return $this
                ->addUsingAlias(WritPeer::ID, $writWritgroup->getWritId(), $comparison);
        } elseif ($writWritgroup instanceof PropelObjectCollection) {
            return $this
                ->useWritWritgroupQuery()
                ->filterByPrimaryKeys($writWritgroup->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByWritWritgroup() only accepts arguments of type WritWritgroup or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the WritWritgroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WritQuery The current query, for fluid interface
     */
    public function joinWritWritgroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('WritWritgroup');

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
            $this->addJoinObject($join, 'WritWritgroup');
        }

        return $this;
    }

    /**
     * Use the WritWritgroup relation WritWritgroup object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\WritWritgroupQuery A secondary query class using the current class as primary query
     */
    public function useWritWritgroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWritWritgroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'WritWritgroup', '\DTA\MetadataBundle\Model\WritWritgroupQuery');
    }

    /**
     * Filter the query by a related Corpus object
     *
     * @param   Corpus|PropelObjectCollection $corpus  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WritQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCorpus($corpus, $comparison = null)
    {
        if ($corpus instanceof Corpus) {
            return $this
                ->addUsingAlias(WritPeer::ID, $corpus->getWritId(), $comparison);
        } elseif ($corpus instanceof PropelObjectCollection) {
            return $this
                ->useCorpusQuery()
                ->filterByPrimaryKeys($corpus->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCorpus() only accepts arguments of type Corpus or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Corpus relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return WritQuery The current query, for fluid interface
     */
    public function joinCorpus($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Corpus');

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
            $this->addJoinObject($join, 'Corpus');
        }

        return $this;
    }

    /**
     * Use the Corpus relation Corpus object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\CorpusQuery A secondary query class using the current class as primary query
     */
    public function useCorpusQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCorpus($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Corpus', '\DTA\MetadataBundle\Model\CorpusQuery');
    }

    /**
     * Filter the query by a related Source object
     *
     * @param   Source|PropelObjectCollection $source  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 WritQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySource($source, $comparison = null)
    {
        if ($source instanceof Source) {
            return $this
                ->addUsingAlias(WritPeer::ID, $source->getWritId(), $comparison);
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
     * @return WritQuery The current query, for fluid interface
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
     * @return                 WritQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTask($task, $comparison = null)
    {
        if ($task instanceof Task) {
            return $this
                ->addUsingAlias(WritPeer::ID, $task->getWritId(), $comparison);
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
     * @return WritQuery The current query, for fluid interface
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
     * Filter the query by a related Writgroup object
     * using the writ_writGroup table as cross reference
     *
     * @param   Writgroup $writgroup the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   WritQuery The current query, for fluid interface
     */
    public function filterByWritgroup($writgroup, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useWritWritgroupQuery()
            ->filterByWritgroup($writgroup, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   Writ $writ Object to remove from the list of results
     *
     * @return WritQuery The current query, for fluid interface
     */
    public function prune($writ = null)
    {
        if ($writ) {
            $this->addUsingAlias(WritPeer::ID, $writ->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
