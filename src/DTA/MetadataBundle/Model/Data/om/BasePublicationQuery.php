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
use DTA\MetadataBundle\Model\Data\Datespecification;
use DTA\MetadataBundle\Model\Data\Font;
use DTA\MetadataBundle\Model\Data\Place;
use DTA\MetadataBundle\Model\Data\Publication;
use DTA\MetadataBundle\Model\Data\PublicationDm;
use DTA\MetadataBundle\Model\Data\PublicationDs;
use DTA\MetadataBundle\Model\Data\PublicationJ;
use DTA\MetadataBundle\Model\Data\PublicationJa;
use DTA\MetadataBundle\Model\Data\PublicationM;
use DTA\MetadataBundle\Model\Data\PublicationMm;
use DTA\MetadataBundle\Model\Data\PublicationMms;
use DTA\MetadataBundle\Model\Data\PublicationMs;
use DTA\MetadataBundle\Model\Data\PublicationPeer;
use DTA\MetadataBundle\Model\Data\PublicationQuery;
use DTA\MetadataBundle\Model\Data\Publishingcompany;
use DTA\MetadataBundle\Model\Data\Work;
use DTA\MetadataBundle\Model\Master\PersonPublication;
use DTA\MetadataBundle\Model\Master\PublicationPublicationgroup;
use DTA\MetadataBundle\Model\Workflow\Imagesource;
use DTA\MetadataBundle\Model\Workflow\Publicationgroup;
use DTA\MetadataBundle\Model\Workflow\Task;
use DTA\MetadataBundle\Model\Workflow\Textsource;

/**
 * @method PublicationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PublicationQuery orderByWwwready($order = Criteria::ASC) Order by the wwwReady column
 * @method PublicationQuery orderByWorkId($order = Criteria::ASC) Order by the work_id column
 * @method PublicationQuery orderByPlaceId($order = Criteria::ASC) Order by the place_id column
 * @method PublicationQuery orderByPublicationdateId($order = Criteria::ASC) Order by the publicationdate_id column
 * @method PublicationQuery orderByFirstpublicationdateId($order = Criteria::ASC) Order by the firstpublicationdate_id column
 * @method PublicationQuery orderByPublishingcompanyId($order = Criteria::ASC) Order by the publishingcompany_id column
 * @method PublicationQuery orderByPartnerId($order = Criteria::ASC) Order by the partner_id column
 * @method PublicationQuery orderByEditiondescription($order = Criteria::ASC) Order by the editiondescription column
 * @method PublicationQuery orderByDigitaleditioneditor($order = Criteria::ASC) Order by the digitaleditioneditor column
 * @method PublicationQuery orderByTranscriptioncomment($order = Criteria::ASC) Order by the transcriptioncomment column
 * @method PublicationQuery orderByFontId($order = Criteria::ASC) Order by the font_id column
 * @method PublicationQuery orderByVolumeAlphanumeric($order = Criteria::ASC) Order by the volume_alphanumeric column
 * @method PublicationQuery orderByVolumeNumeric($order = Criteria::ASC) Order by the volume_numeric column
 * @method PublicationQuery orderByVolumesTotal($order = Criteria::ASC) Order by the volumes_total column
 * @method PublicationQuery orderByNumpages($order = Criteria::ASC) Order by the numpages column
 * @method PublicationQuery orderByNumpagesnormed($order = Criteria::ASC) Order by the numpagesnormed column
 * @method PublicationQuery orderByComment($order = Criteria::ASC) Order by the comment column
 * @method PublicationQuery orderByPublishingcompanyIdIsReconstructed($order = Criteria::ASC) Order by the publishingcompany_id_is_reconstructed column
 *
 * @method PublicationQuery groupById() Group by the id column
 * @method PublicationQuery groupByWwwready() Group by the wwwReady column
 * @method PublicationQuery groupByWorkId() Group by the work_id column
 * @method PublicationQuery groupByPlaceId() Group by the place_id column
 * @method PublicationQuery groupByPublicationdateId() Group by the publicationdate_id column
 * @method PublicationQuery groupByFirstpublicationdateId() Group by the firstpublicationdate_id column
 * @method PublicationQuery groupByPublishingcompanyId() Group by the publishingcompany_id column
 * @method PublicationQuery groupByPartnerId() Group by the partner_id column
 * @method PublicationQuery groupByEditiondescription() Group by the editiondescription column
 * @method PublicationQuery groupByDigitaleditioneditor() Group by the digitaleditioneditor column
 * @method PublicationQuery groupByTranscriptioncomment() Group by the transcriptioncomment column
 * @method PublicationQuery groupByFontId() Group by the font_id column
 * @method PublicationQuery groupByVolumeAlphanumeric() Group by the volume_alphanumeric column
 * @method PublicationQuery groupByVolumeNumeric() Group by the volume_numeric column
 * @method PublicationQuery groupByVolumesTotal() Group by the volumes_total column
 * @method PublicationQuery groupByNumpages() Group by the numpages column
 * @method PublicationQuery groupByNumpagesnormed() Group by the numpagesnormed column
 * @method PublicationQuery groupByComment() Group by the comment column
 * @method PublicationQuery groupByPublishingcompanyIdIsReconstructed() Group by the publishingcompany_id_is_reconstructed column
 *
 * @method PublicationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PublicationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PublicationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PublicationQuery leftJoinWork($relationAlias = null) Adds a LEFT JOIN clause to the query using the Work relation
 * @method PublicationQuery rightJoinWork($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Work relation
 * @method PublicationQuery innerJoinWork($relationAlias = null) Adds a INNER JOIN clause to the query using the Work relation
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
 * @method PublicationQuery leftJoinDatespecificationRelatedByFirstpublicationdateId($relationAlias = null) Adds a LEFT JOIN clause to the query using the DatespecificationRelatedByFirstpublicationdateId relation
 * @method PublicationQuery rightJoinDatespecificationRelatedByFirstpublicationdateId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DatespecificationRelatedByFirstpublicationdateId relation
 * @method PublicationQuery innerJoinDatespecificationRelatedByFirstpublicationdateId($relationAlias = null) Adds a INNER JOIN clause to the query using the DatespecificationRelatedByFirstpublicationdateId relation
 *
 * @method PublicationQuery leftJoinFont($relationAlias = null) Adds a LEFT JOIN clause to the query using the Font relation
 * @method PublicationQuery rightJoinFont($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Font relation
 * @method PublicationQuery innerJoinFont($relationAlias = null) Adds a INNER JOIN clause to the query using the Font relation
 *
 * @method PublicationQuery leftJoinPublicationM($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationM relation
 * @method PublicationQuery rightJoinPublicationM($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationM relation
 * @method PublicationQuery innerJoinPublicationM($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationM relation
 *
 * @method PublicationQuery leftJoinPublicationDm($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationDm relation
 * @method PublicationQuery rightJoinPublicationDm($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationDm relation
 * @method PublicationQuery innerJoinPublicationDm($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationDm relation
 *
 * @method PublicationQuery leftJoinPublicationMm($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationMm relation
 * @method PublicationQuery rightJoinPublicationMm($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationMm relation
 * @method PublicationQuery innerJoinPublicationMm($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationMm relation
 *
 * @method PublicationQuery leftJoinPublicationDs($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationDs relation
 * @method PublicationQuery rightJoinPublicationDs($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationDs relation
 * @method PublicationQuery innerJoinPublicationDs($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationDs relation
 *
 * @method PublicationQuery leftJoinPublicationMs($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationMs relation
 * @method PublicationQuery rightJoinPublicationMs($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationMs relation
 * @method PublicationQuery innerJoinPublicationMs($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationMs relation
 *
 * @method PublicationQuery leftJoinPublicationJa($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationJa relation
 * @method PublicationQuery rightJoinPublicationJa($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationJa relation
 * @method PublicationQuery innerJoinPublicationJa($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationJa relation
 *
 * @method PublicationQuery leftJoinPublicationMms($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationMms relation
 * @method PublicationQuery rightJoinPublicationMms($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationMms relation
 * @method PublicationQuery innerJoinPublicationMms($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationMms relation
 *
 * @method PublicationQuery leftJoinPublicationJ($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationJ relation
 * @method PublicationQuery rightJoinPublicationJ($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationJ relation
 * @method PublicationQuery innerJoinPublicationJ($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationJ relation
 *
 * @method PublicationQuery leftJoinPublicationPublicationgroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationPublicationgroup relation
 * @method PublicationQuery rightJoinPublicationPublicationgroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationPublicationgroup relation
 * @method PublicationQuery innerJoinPublicationPublicationgroup($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationPublicationgroup relation
 *
 * @method PublicationQuery leftJoinPersonPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the PersonPublication relation
 * @method PublicationQuery rightJoinPersonPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PersonPublication relation
 * @method PublicationQuery innerJoinPersonPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the PersonPublication relation
 *
 * @method PublicationQuery leftJoinTask($relationAlias = null) Adds a LEFT JOIN clause to the query using the Task relation
 * @method PublicationQuery rightJoinTask($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Task relation
 * @method PublicationQuery innerJoinTask($relationAlias = null) Adds a INNER JOIN clause to the query using the Task relation
 *
 * @method PublicationQuery leftJoinImagesource($relationAlias = null) Adds a LEFT JOIN clause to the query using the Imagesource relation
 * @method PublicationQuery rightJoinImagesource($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Imagesource relation
 * @method PublicationQuery innerJoinImagesource($relationAlias = null) Adds a INNER JOIN clause to the query using the Imagesource relation
 *
 * @method PublicationQuery leftJoinTextsource($relationAlias = null) Adds a LEFT JOIN clause to the query using the Textsource relation
 * @method PublicationQuery rightJoinTextsource($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Textsource relation
 * @method PublicationQuery innerJoinTextsource($relationAlias = null) Adds a INNER JOIN clause to the query using the Textsource relation
 *
 * @method Publication findOne(PropelPDO $con = null) Return the first Publication matching the query
 * @method Publication findOneOrCreate(PropelPDO $con = null) Return the first Publication matching the query, or a new Publication object populated from the query conditions when no match is found
 *
 * @method Publication findOneByWwwready(int $wwwReady) Return the first Publication filtered by the wwwReady column
 * @method Publication findOneByWorkId(int $work_id) Return the first Publication filtered by the work_id column
 * @method Publication findOneByPlaceId(int $place_id) Return the first Publication filtered by the place_id column
 * @method Publication findOneByPublicationdateId(int $publicationdate_id) Return the first Publication filtered by the publicationdate_id column
 * @method Publication findOneByFirstpublicationdateId(int $firstpublicationdate_id) Return the first Publication filtered by the firstpublicationdate_id column
 * @method Publication findOneByPublishingcompanyId(int $publishingcompany_id) Return the first Publication filtered by the publishingcompany_id column
 * @method Publication findOneByPartnerId(int $partner_id) Return the first Publication filtered by the partner_id column
 * @method Publication findOneByEditiondescription(string $editiondescription) Return the first Publication filtered by the editiondescription column
 * @method Publication findOneByDigitaleditioneditor(string $digitaleditioneditor) Return the first Publication filtered by the digitaleditioneditor column
 * @method Publication findOneByTranscriptioncomment(string $transcriptioncomment) Return the first Publication filtered by the transcriptioncomment column
 * @method Publication findOneByFontId(int $font_id) Return the first Publication filtered by the font_id column
 * @method Publication findOneByVolumeAlphanumeric(string $volume_alphanumeric) Return the first Publication filtered by the volume_alphanumeric column
 * @method Publication findOneByVolumeNumeric(string $volume_numeric) Return the first Publication filtered by the volume_numeric column
 * @method Publication findOneByVolumesTotal(string $volumes_total) Return the first Publication filtered by the volumes_total column
 * @method Publication findOneByNumpages(int $numpages) Return the first Publication filtered by the numpages column
 * @method Publication findOneByNumpagesnormed(int $numpagesnormed) Return the first Publication filtered by the numpagesnormed column
 * @method Publication findOneByComment(string $comment) Return the first Publication filtered by the comment column
 * @method Publication findOneByPublishingcompanyIdIsReconstructed(boolean $publishingcompany_id_is_reconstructed) Return the first Publication filtered by the publishingcompany_id_is_reconstructed column
 *
 * @method array findById(int $id) Return Publication objects filtered by the id column
 * @method array findByWwwready(int $wwwReady) Return Publication objects filtered by the wwwReady column
 * @method array findByWorkId(int $work_id) Return Publication objects filtered by the work_id column
 * @method array findByPlaceId(int $place_id) Return Publication objects filtered by the place_id column
 * @method array findByPublicationdateId(int $publicationdate_id) Return Publication objects filtered by the publicationdate_id column
 * @method array findByFirstpublicationdateId(int $firstpublicationdate_id) Return Publication objects filtered by the firstpublicationdate_id column
 * @method array findByPublishingcompanyId(int $publishingcompany_id) Return Publication objects filtered by the publishingcompany_id column
 * @method array findByPartnerId(int $partner_id) Return Publication objects filtered by the partner_id column
 * @method array findByEditiondescription(string $editiondescription) Return Publication objects filtered by the editiondescription column
 * @method array findByDigitaleditioneditor(string $digitaleditioneditor) Return Publication objects filtered by the digitaleditioneditor column
 * @method array findByTranscriptioncomment(string $transcriptioncomment) Return Publication objects filtered by the transcriptioncomment column
 * @method array findByFontId(int $font_id) Return Publication objects filtered by the font_id column
 * @method array findByVolumeAlphanumeric(string $volume_alphanumeric) Return Publication objects filtered by the volume_alphanumeric column
 * @method array findByVolumeNumeric(string $volume_numeric) Return Publication objects filtered by the volume_numeric column
 * @method array findByVolumesTotal(string $volumes_total) Return Publication objects filtered by the volumes_total column
 * @method array findByNumpages(int $numpages) Return Publication objects filtered by the numpages column
 * @method array findByNumpagesnormed(int $numpagesnormed) Return Publication objects filtered by the numpagesnormed column
 * @method array findByComment(string $comment) Return Publication objects filtered by the comment column
 * @method array findByPublishingcompanyIdIsReconstructed(boolean $publishingcompany_id_is_reconstructed) Return Publication objects filtered by the publishingcompany_id_is_reconstructed column
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
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Data\\Publication', $modelAlias = null)
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
        $sql = 'SELECT "id", "wwwReady", "work_id", "place_id", "publicationdate_id", "firstpublicationdate_id", "publishingcompany_id", "partner_id", "editiondescription", "digitaleditioneditor", "transcriptioncomment", "font_id", "volume_alphanumeric", "volume_numeric", "volumes_total", "numpages", "numpagesnormed", "comment", "publishingcompany_id_is_reconstructed" FROM "publication" WHERE "id" = :p0';
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
     * Filter the query on the wwwReady column
     *
     * Example usage:
     * <code>
     * $query->filterByWwwready(1234); // WHERE wwwReady = 1234
     * $query->filterByWwwready(array(12, 34)); // WHERE wwwReady IN (12, 34)
     * $query->filterByWwwready(array('min' => 12)); // WHERE wwwReady >= 12
     * $query->filterByWwwready(array('max' => 12)); // WHERE wwwReady <= 12
     * </code>
     *
     * @param     mixed $wwwready The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByWwwready($wwwready = null, $comparison = null)
    {
        if (is_array($wwwready)) {
            $useMinMax = false;
            if (isset($wwwready['min'])) {
                $this->addUsingAlias(PublicationPeer::WWWREADY, $wwwready['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($wwwready['max'])) {
                $this->addUsingAlias(PublicationPeer::WWWREADY, $wwwready['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::WWWREADY, $wwwready, $comparison);
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
     * Filter the query on the publicationdate_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublicationdateId(1234); // WHERE publicationdate_id = 1234
     * $query->filterByPublicationdateId(array(12, 34)); // WHERE publicationdate_id IN (12, 34)
     * $query->filterByPublicationdateId(array('min' => 12)); // WHERE publicationdate_id >= 12
     * $query->filterByPublicationdateId(array('max' => 12)); // WHERE publicationdate_id <= 12
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
     * Filter the query on the firstpublicationdate_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstpublicationdateId(1234); // WHERE firstpublicationdate_id = 1234
     * $query->filterByFirstpublicationdateId(array(12, 34)); // WHERE firstpublicationdate_id IN (12, 34)
     * $query->filterByFirstpublicationdateId(array('min' => 12)); // WHERE firstpublicationdate_id >= 12
     * $query->filterByFirstpublicationdateId(array('max' => 12)); // WHERE firstpublicationdate_id <= 12
     * </code>
     *
     * @see       filterByDatespecificationRelatedByFirstpublicationdateId()
     *
     * @param     mixed $firstpublicationdateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByFirstpublicationdateId($firstpublicationdateId = null, $comparison = null)
    {
        if (is_array($firstpublicationdateId)) {
            $useMinMax = false;
            if (isset($firstpublicationdateId['min'])) {
                $this->addUsingAlias(PublicationPeer::FIRSTPUBLICATIONDATE_ID, $firstpublicationdateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($firstpublicationdateId['max'])) {
                $this->addUsingAlias(PublicationPeer::FIRSTPUBLICATIONDATE_ID, $firstpublicationdateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::FIRSTPUBLICATIONDATE_ID, $firstpublicationdateId, $comparison);
    }

    /**
     * Filter the query on the publishingcompany_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublishingcompanyId(1234); // WHERE publishingcompany_id = 1234
     * $query->filterByPublishingcompanyId(array(12, 34)); // WHERE publishingcompany_id IN (12, 34)
     * $query->filterByPublishingcompanyId(array('min' => 12)); // WHERE publishingcompany_id >= 12
     * $query->filterByPublishingcompanyId(array('max' => 12)); // WHERE publishingcompany_id <= 12
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
     * Filter the query on the partner_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPartnerId(1234); // WHERE partner_id = 1234
     * $query->filterByPartnerId(array(12, 34)); // WHERE partner_id IN (12, 34)
     * $query->filterByPartnerId(array('min' => 12)); // WHERE partner_id >= 12
     * $query->filterByPartnerId(array('max' => 12)); // WHERE partner_id <= 12
     * </code>
     *
     * @param     mixed $partnerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByPartnerId($partnerId = null, $comparison = null)
    {
        if (is_array($partnerId)) {
            $useMinMax = false;
            if (isset($partnerId['min'])) {
                $this->addUsingAlias(PublicationPeer::PARTNER_ID, $partnerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($partnerId['max'])) {
                $this->addUsingAlias(PublicationPeer::PARTNER_ID, $partnerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::PARTNER_ID, $partnerId, $comparison);
    }

    /**
     * Filter the query on the editiondescription column
     *
     * Example usage:
     * <code>
     * $query->filterByEditiondescription('fooValue');   // WHERE editiondescription = 'fooValue'
     * $query->filterByEditiondescription('%fooValue%'); // WHERE editiondescription LIKE '%fooValue%'
     * </code>
     *
     * @param     string $editiondescription The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByEditiondescription($editiondescription = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($editiondescription)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $editiondescription)) {
                $editiondescription = str_replace('*', '%', $editiondescription);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationPeer::EDITIONDESCRIPTION, $editiondescription, $comparison);
    }

    /**
     * Filter the query on the digitaleditioneditor column
     *
     * Example usage:
     * <code>
     * $query->filterByDigitaleditioneditor('fooValue');   // WHERE digitaleditioneditor = 'fooValue'
     * $query->filterByDigitaleditioneditor('%fooValue%'); // WHERE digitaleditioneditor LIKE '%fooValue%'
     * </code>
     *
     * @param     string $digitaleditioneditor The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByDigitaleditioneditor($digitaleditioneditor = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($digitaleditioneditor)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $digitaleditioneditor)) {
                $digitaleditioneditor = str_replace('*', '%', $digitaleditioneditor);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationPeer::DIGITALEDITIONEDITOR, $digitaleditioneditor, $comparison);
    }

    /**
     * Filter the query on the transcriptioncomment column
     *
     * Example usage:
     * <code>
     * $query->filterByTranscriptioncomment('fooValue');   // WHERE transcriptioncomment = 'fooValue'
     * $query->filterByTranscriptioncomment('%fooValue%'); // WHERE transcriptioncomment LIKE '%fooValue%'
     * </code>
     *
     * @param     string $transcriptioncomment The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByTranscriptioncomment($transcriptioncomment = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($transcriptioncomment)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $transcriptioncomment)) {
                $transcriptioncomment = str_replace('*', '%', $transcriptioncomment);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationPeer::TRANSCRIPTIONCOMMENT, $transcriptioncomment, $comparison);
    }

    /**
     * Filter the query on the font_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFontId(1234); // WHERE font_id = 1234
     * $query->filterByFontId(array(12, 34)); // WHERE font_id IN (12, 34)
     * $query->filterByFontId(array('min' => 12)); // WHERE font_id >= 12
     * $query->filterByFontId(array('max' => 12)); // WHERE font_id <= 12
     * </code>
     *
     * @see       filterByFont()
     *
     * @param     mixed $fontId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByFontId($fontId = null, $comparison = null)
    {
        if (is_array($fontId)) {
            $useMinMax = false;
            if (isset($fontId['min'])) {
                $this->addUsingAlias(PublicationPeer::FONT_ID, $fontId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fontId['max'])) {
                $this->addUsingAlias(PublicationPeer::FONT_ID, $fontId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::FONT_ID, $fontId, $comparison);
    }

    /**
     * Filter the query on the volume_alphanumeric column
     *
     * Example usage:
     * <code>
     * $query->filterByVolumeAlphanumeric('fooValue');   // WHERE volume_alphanumeric = 'fooValue'
     * $query->filterByVolumeAlphanumeric('%fooValue%'); // WHERE volume_alphanumeric LIKE '%fooValue%'
     * </code>
     *
     * @param     string $volumeAlphanumeric The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByVolumeAlphanumeric($volumeAlphanumeric = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($volumeAlphanumeric)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $volumeAlphanumeric)) {
                $volumeAlphanumeric = str_replace('*', '%', $volumeAlphanumeric);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationPeer::VOLUME_ALPHANUMERIC, $volumeAlphanumeric, $comparison);
    }

    /**
     * Filter the query on the volume_numeric column
     *
     * Example usage:
     * <code>
     * $query->filterByVolumeNumeric('fooValue');   // WHERE volume_numeric = 'fooValue'
     * $query->filterByVolumeNumeric('%fooValue%'); // WHERE volume_numeric LIKE '%fooValue%'
     * </code>
     *
     * @param     string $volumeNumeric The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByVolumeNumeric($volumeNumeric = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($volumeNumeric)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $volumeNumeric)) {
                $volumeNumeric = str_replace('*', '%', $volumeNumeric);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationPeer::VOLUME_NUMERIC, $volumeNumeric, $comparison);
    }

    /**
     * Filter the query on the volumes_total column
     *
     * Example usage:
     * <code>
     * $query->filterByVolumesTotal('fooValue');   // WHERE volumes_total = 'fooValue'
     * $query->filterByVolumesTotal('%fooValue%'); // WHERE volumes_total LIKE '%fooValue%'
     * </code>
     *
     * @param     string $volumesTotal The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByVolumesTotal($volumesTotal = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($volumesTotal)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $volumesTotal)) {
                $volumesTotal = str_replace('*', '%', $volumesTotal);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationPeer::VOLUMES_TOTAL, $volumesTotal, $comparison);
    }

    /**
     * Filter the query on the numpages column
     *
     * Example usage:
     * <code>
     * $query->filterByNumpages(1234); // WHERE numpages = 1234
     * $query->filterByNumpages(array(12, 34)); // WHERE numpages IN (12, 34)
     * $query->filterByNumpages(array('min' => 12)); // WHERE numpages >= 12
     * $query->filterByNumpages(array('max' => 12)); // WHERE numpages <= 12
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
     * Filter the query on the numpagesnormed column
     *
     * Example usage:
     * <code>
     * $query->filterByNumpagesnormed(1234); // WHERE numpagesnormed = 1234
     * $query->filterByNumpagesnormed(array(12, 34)); // WHERE numpagesnormed IN (12, 34)
     * $query->filterByNumpagesnormed(array('min' => 12)); // WHERE numpagesnormed >= 12
     * $query->filterByNumpagesnormed(array('max' => 12)); // WHERE numpagesnormed <= 12
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
     * Filter the query on the comment column
     *
     * Example usage:
     * <code>
     * $query->filterByComment('fooValue');   // WHERE comment = 'fooValue'
     * $query->filterByComment('%fooValue%'); // WHERE comment LIKE '%fooValue%'
     * </code>
     *
     * @param     string $comment The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByComment($comment = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($comment)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $comment)) {
                $comment = str_replace('*', '%', $comment);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationPeer::COMMENT, $comment, $comparison);
    }

    /**
     * Filter the query on the publishingcompany_id_is_reconstructed column
     *
     * Example usage:
     * <code>
     * $query->filterByPublishingcompanyIdIsReconstructed(true); // WHERE publishingcompany_id_is_reconstructed = true
     * $query->filterByPublishingcompanyIdIsReconstructed('yes'); // WHERE publishingcompany_id_is_reconstructed = true
     * </code>
     *
     * @param     boolean|string $publishingcompanyIdIsReconstructed The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByPublishingcompanyIdIsReconstructed($publishingcompanyIdIsReconstructed = null, $comparison = null)
    {
        if (is_string($publishingcompanyIdIsReconstructed)) {
            $publishingcompanyIdIsReconstructed = in_array(strtolower($publishingcompanyIdIsReconstructed), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PublicationPeer::PUBLISHINGCOMPANY_ID_IS_RECONSTRUCTED, $publishingcompanyIdIsReconstructed, $comparison);
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
     * @return   \DTA\MetadataBundle\Model\Data\WorkQuery A secondary query class using the current class as primary query
     */
    public function useWorkQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWork($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Work', '\DTA\MetadataBundle\Model\Data\WorkQuery');
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
     * @return   \DTA\MetadataBundle\Model\Data\PublishingcompanyQuery A secondary query class using the current class as primary query
     */
    public function usePublishingcompanyQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPublishingcompany($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publishingcompany', '\DTA\MetadataBundle\Model\Data\PublishingcompanyQuery');
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
     * @return   \DTA\MetadataBundle\Model\Data\PlaceQuery A secondary query class using the current class as primary query
     */
    public function usePlaceQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPlace($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Place', '\DTA\MetadataBundle\Model\Data\PlaceQuery');
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
     * @return   \DTA\MetadataBundle\Model\Data\DatespecificationQuery A secondary query class using the current class as primary query
     */
    public function useDatespecificationRelatedByPublicationdateIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDatespecificationRelatedByPublicationdateId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DatespecificationRelatedByPublicationdateId', '\DTA\MetadataBundle\Model\Data\DatespecificationQuery');
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
    public function filterByDatespecificationRelatedByFirstpublicationdateId($datespecification, $comparison = null)
    {
        if ($datespecification instanceof Datespecification) {
            return $this
                ->addUsingAlias(PublicationPeer::FIRSTPUBLICATIONDATE_ID, $datespecification->getId(), $comparison);
        } elseif ($datespecification instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::FIRSTPUBLICATIONDATE_ID, $datespecification->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDatespecificationRelatedByFirstpublicationdateId() only accepts arguments of type Datespecification or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DatespecificationRelatedByFirstpublicationdateId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinDatespecificationRelatedByFirstpublicationdateId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DatespecificationRelatedByFirstpublicationdateId');

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
            $this->addJoinObject($join, 'DatespecificationRelatedByFirstpublicationdateId');
        }

        return $this;
    }

    /**
     * Use the DatespecificationRelatedByFirstpublicationdateId relation Datespecification object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\DatespecificationQuery A secondary query class using the current class as primary query
     */
    public function useDatespecificationRelatedByFirstpublicationdateIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDatespecificationRelatedByFirstpublicationdateId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DatespecificationRelatedByFirstpublicationdateId', '\DTA\MetadataBundle\Model\Data\DatespecificationQuery');
    }

    /**
     * Filter the query by a related Font object
     *
     * @param   Font|PropelObjectCollection $font The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFont($font, $comparison = null)
    {
        if ($font instanceof Font) {
            return $this
                ->addUsingAlias(PublicationPeer::FONT_ID, $font->getId(), $comparison);
        } elseif ($font instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::FONT_ID, $font->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFont() only accepts arguments of type Font or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Font relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinFont($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Font');

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
            $this->addJoinObject($join, 'Font');
        }

        return $this;
    }

    /**
     * Use the Font relation Font object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\FontQuery A secondary query class using the current class as primary query
     */
    public function useFontQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFont($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Font', '\DTA\MetadataBundle\Model\Data\FontQuery');
    }

    /**
     * Filter the query by a related PublicationM object
     *
     * @param   PublicationM|PropelObjectCollection $publicationM  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationM($publicationM, $comparison = null)
    {
        if ($publicationM instanceof PublicationM) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $publicationM->getPublicationId(), $comparison);
        } elseif ($publicationM instanceof PropelObjectCollection) {
            return $this
                ->usePublicationMQuery()
                ->filterByPrimaryKeys($publicationM->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationM() only accepts arguments of type PublicationM or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationM relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinPublicationM($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationM');

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
            $this->addJoinObject($join, 'PublicationM');
        }

        return $this;
    }

    /**
     * Use the PublicationM relation PublicationM object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationMQuery A secondary query class using the current class as primary query
     */
    public function usePublicationMQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationM($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationM', '\DTA\MetadataBundle\Model\Data\PublicationMQuery');
    }

    /**
     * Filter the query by a related PublicationDm object
     *
     * @param   PublicationDm|PropelObjectCollection $publicationDm  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationDm($publicationDm, $comparison = null)
    {
        if ($publicationDm instanceof PublicationDm) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $publicationDm->getPublicationId(), $comparison);
        } elseif ($publicationDm instanceof PropelObjectCollection) {
            return $this
                ->usePublicationDmQuery()
                ->filterByPrimaryKeys($publicationDm->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationDm() only accepts arguments of type PublicationDm or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationDm relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinPublicationDm($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationDm');

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
            $this->addJoinObject($join, 'PublicationDm');
        }

        return $this;
    }

    /**
     * Use the PublicationDm relation PublicationDm object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationDmQuery A secondary query class using the current class as primary query
     */
    public function usePublicationDmQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationDm($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationDm', '\DTA\MetadataBundle\Model\Data\PublicationDmQuery');
    }

    /**
     * Filter the query by a related PublicationMm object
     *
     * @param   PublicationMm|PropelObjectCollection $publicationMm  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationMm($publicationMm, $comparison = null)
    {
        if ($publicationMm instanceof PublicationMm) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $publicationMm->getPublicationId(), $comparison);
        } elseif ($publicationMm instanceof PropelObjectCollection) {
            return $this
                ->usePublicationMmQuery()
                ->filterByPrimaryKeys($publicationMm->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationMm() only accepts arguments of type PublicationMm or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationMm relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinPublicationMm($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationMm');

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
            $this->addJoinObject($join, 'PublicationMm');
        }

        return $this;
    }

    /**
     * Use the PublicationMm relation PublicationMm object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationMmQuery A secondary query class using the current class as primary query
     */
    public function usePublicationMmQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationMm($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationMm', '\DTA\MetadataBundle\Model\Data\PublicationMmQuery');
    }

    /**
     * Filter the query by a related PublicationDs object
     *
     * @param   PublicationDs|PropelObjectCollection $publicationDs  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationDs($publicationDs, $comparison = null)
    {
        if ($publicationDs instanceof PublicationDs) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $publicationDs->getPublicationId(), $comparison);
        } elseif ($publicationDs instanceof PropelObjectCollection) {
            return $this
                ->usePublicationDsQuery()
                ->filterByPrimaryKeys($publicationDs->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationDs() only accepts arguments of type PublicationDs or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationDs relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinPublicationDs($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationDs');

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
            $this->addJoinObject($join, 'PublicationDs');
        }

        return $this;
    }

    /**
     * Use the PublicationDs relation PublicationDs object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationDsQuery A secondary query class using the current class as primary query
     */
    public function usePublicationDsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationDs($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationDs', '\DTA\MetadataBundle\Model\Data\PublicationDsQuery');
    }

    /**
     * Filter the query by a related PublicationMs object
     *
     * @param   PublicationMs|PropelObjectCollection $publicationMs  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationMs($publicationMs, $comparison = null)
    {
        if ($publicationMs instanceof PublicationMs) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $publicationMs->getPublicationId(), $comparison);
        } elseif ($publicationMs instanceof PropelObjectCollection) {
            return $this
                ->usePublicationMsQuery()
                ->filterByPrimaryKeys($publicationMs->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationMs() only accepts arguments of type PublicationMs or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationMs relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinPublicationMs($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationMs');

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
            $this->addJoinObject($join, 'PublicationMs');
        }

        return $this;
    }

    /**
     * Use the PublicationMs relation PublicationMs object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationMsQuery A secondary query class using the current class as primary query
     */
    public function usePublicationMsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationMs($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationMs', '\DTA\MetadataBundle\Model\Data\PublicationMsQuery');
    }

    /**
     * Filter the query by a related PublicationJa object
     *
     * @param   PublicationJa|PropelObjectCollection $publicationJa  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationJa($publicationJa, $comparison = null)
    {
        if ($publicationJa instanceof PublicationJa) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $publicationJa->getPublicationId(), $comparison);
        } elseif ($publicationJa instanceof PropelObjectCollection) {
            return $this
                ->usePublicationJaQuery()
                ->filterByPrimaryKeys($publicationJa->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationJa() only accepts arguments of type PublicationJa or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationJa relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinPublicationJa($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationJa');

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
            $this->addJoinObject($join, 'PublicationJa');
        }

        return $this;
    }

    /**
     * Use the PublicationJa relation PublicationJa object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationJaQuery A secondary query class using the current class as primary query
     */
    public function usePublicationJaQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationJa($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationJa', '\DTA\MetadataBundle\Model\Data\PublicationJaQuery');
    }

    /**
     * Filter the query by a related PublicationMms object
     *
     * @param   PublicationMms|PropelObjectCollection $publicationMms  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationMms($publicationMms, $comparison = null)
    {
        if ($publicationMms instanceof PublicationMms) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $publicationMms->getPublicationId(), $comparison);
        } elseif ($publicationMms instanceof PropelObjectCollection) {
            return $this
                ->usePublicationMmsQuery()
                ->filterByPrimaryKeys($publicationMms->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationMms() only accepts arguments of type PublicationMms or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationMms relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinPublicationMms($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationMms');

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
            $this->addJoinObject($join, 'PublicationMms');
        }

        return $this;
    }

    /**
     * Use the PublicationMms relation PublicationMms object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationMmsQuery A secondary query class using the current class as primary query
     */
    public function usePublicationMmsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationMms($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationMms', '\DTA\MetadataBundle\Model\Data\PublicationMmsQuery');
    }

    /**
     * Filter the query by a related PublicationJ object
     *
     * @param   PublicationJ|PropelObjectCollection $publicationJ  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationJ($publicationJ, $comparison = null)
    {
        if ($publicationJ instanceof PublicationJ) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $publicationJ->getPublicationId(), $comparison);
        } elseif ($publicationJ instanceof PropelObjectCollection) {
            return $this
                ->usePublicationJQuery()
                ->filterByPrimaryKeys($publicationJ->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationJ() only accepts arguments of type PublicationJ or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationJ relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinPublicationJ($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationJ');

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
            $this->addJoinObject($join, 'PublicationJ');
        }

        return $this;
    }

    /**
     * Use the PublicationJ relation PublicationJ object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationJQuery A secondary query class using the current class as primary query
     */
    public function usePublicationJQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationJ($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationJ', '\DTA\MetadataBundle\Model\Data\PublicationJQuery');
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
     * @return   \DTA\MetadataBundle\Model\Master\PublicationPublicationgroupQuery A secondary query class using the current class as primary query
     */
    public function usePublicationPublicationgroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationPublicationgroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationPublicationgroup', '\DTA\MetadataBundle\Model\Master\PublicationPublicationgroupQuery');
    }

    /**
     * Filter the query by a related PersonPublication object
     *
     * @param   PersonPublication|PropelObjectCollection $personPublication  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPersonPublication($personPublication, $comparison = null)
    {
        if ($personPublication instanceof PersonPublication) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $personPublication->getPublicationId(), $comparison);
        } elseif ($personPublication instanceof PropelObjectCollection) {
            return $this
                ->usePersonPublicationQuery()
                ->filterByPrimaryKeys($personPublication->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPersonPublication() only accepts arguments of type PersonPublication or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PersonPublication relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinPersonPublication($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PersonPublication');

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
            $this->addJoinObject($join, 'PersonPublication');
        }

        return $this;
    }

    /**
     * Use the PersonPublication relation PersonPublication object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\PersonPublicationQuery A secondary query class using the current class as primary query
     */
    public function usePersonPublicationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPersonPublication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PersonPublication', '\DTA\MetadataBundle\Model\Master\PersonPublicationQuery');
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
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByImagesource($imagesource, $comparison = null)
    {
        if ($imagesource instanceof Imagesource) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $imagesource->getPublicationId(), $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinImagesource($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useImagesourceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTextsource($textsource, $comparison = null)
    {
        if ($textsource instanceof Textsource) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $textsource->getPublicationId(), $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinTextsource($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useTextsourceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTextsource($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Textsource', '\DTA\MetadataBundle\Model\Workflow\TextsourceQuery');
    }

    /**
     * Filter the query by a related Publicationgroup object
     * using the publication_publicationgroup table as cross reference
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