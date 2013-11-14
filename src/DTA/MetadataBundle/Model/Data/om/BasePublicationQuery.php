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
use DTA\MetadataBundle\Model\Data\Font;
use DTA\MetadataBundle\Model\Data\Language;
use DTA\MetadataBundle\Model\Data\Place;
use DTA\MetadataBundle\Model\Data\Publication;
use DTA\MetadataBundle\Model\Data\PublicationDm;
use DTA\MetadataBundle\Model\Data\PublicationDs;
use DTA\MetadataBundle\Model\Data\PublicationJ;
use DTA\MetadataBundle\Model\Data\PublicationJa;
use DTA\MetadataBundle\Model\Data\PublicationM;
use DTA\MetadataBundle\Model\Data\PublicationMms;
use DTA\MetadataBundle\Model\Data\PublicationMs;
use DTA\MetadataBundle\Model\Data\PublicationPeer;
use DTA\MetadataBundle\Model\Data\PublicationQuery;
use DTA\MetadataBundle\Model\Data\Publishingcompany;
use DTA\MetadataBundle\Model\Data\Title;
use DTA\MetadataBundle\Model\Data\Volume;
use DTA\MetadataBundle\Model\Master\CategoryPublication;
use DTA\MetadataBundle\Model\Master\DtaUser;
use DTA\MetadataBundle\Model\Master\FontPublication;
use DTA\MetadataBundle\Model\Master\GenrePublication;
use DTA\MetadataBundle\Model\Master\LanguagePublication;
use DTA\MetadataBundle\Model\Master\PersonPublication;
use DTA\MetadataBundle\Model\Master\PublicationPublicationgroup;
use DTA\MetadataBundle\Model\Master\PublicationTag;
use DTA\MetadataBundle\Model\Master\RecentUse;
use DTA\MetadataBundle\Model\Workflow\CopyLocation;
use DTA\MetadataBundle\Model\Workflow\Imagesource;
use DTA\MetadataBundle\Model\Workflow\Publicationgroup;
use DTA\MetadataBundle\Model\Workflow\Task;
use DTA\MetadataBundle\Model\Workflow\Textsource;

/**
 * @method PublicationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PublicationQuery orderByTitleId($order = Criteria::ASC) Order by the title_id column
 * @method PublicationQuery orderByFirsteditionpublicationId($order = Criteria::ASC) Order by the firsteditionpublication_id column
 * @method PublicationQuery orderByPlaceId($order = Criteria::ASC) Order by the place_id column
 * @method PublicationQuery orderByPublicationdateId($order = Criteria::ASC) Order by the publicationdate_id column
 * @method PublicationQuery orderByCreationdateId($order = Criteria::ASC) Order by the creationdate_id column
 * @method PublicationQuery orderByPublishingcompanyId($order = Criteria::ASC) Order by the publishingcompany_id column
 * @method PublicationQuery orderByPartnerId($order = Criteria::ASC) Order by the partner_id column
 * @method PublicationQuery orderByEditiondescription($order = Criteria::ASC) Order by the editiondescription column
 * @method PublicationQuery orderByDigitaleditioneditor($order = Criteria::ASC) Order by the digitaleditioneditor column
 * @method PublicationQuery orderByTranscriptioncomment($order = Criteria::ASC) Order by the transcriptioncomment column
 * @method PublicationQuery orderByNumpages($order = Criteria::ASC) Order by the numpages column
 * @method PublicationQuery orderByNumpagesnumeric($order = Criteria::ASC) Order by the numpagesnumeric column
 * @method PublicationQuery orderByComment($order = Criteria::ASC) Order by the comment column
 * @method PublicationQuery orderByEncodingComment($order = Criteria::ASC) Order by the encoding_comment column
 * @method PublicationQuery orderByDoi($order = Criteria::ASC) Order by the doi column
 * @method PublicationQuery orderByFormat($order = Criteria::ASC) Order by the format column
 * @method PublicationQuery orderByDirectoryname($order = Criteria::ASC) Order by the directoryname column
 * @method PublicationQuery orderByWwwready($order = Criteria::ASC) Order by the wwwready column
 * @method PublicationQuery orderByLastChangedByUserId($order = Criteria::ASC) Order by the last_changed_by_user_id column
 * @method PublicationQuery orderByLegacyBookId($order = Criteria::ASC) Order by the legacy_book_id column
 * @method PublicationQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PublicationQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PublicationQuery orderByPublishingcompanyIdIsReconstructed($order = Criteria::ASC) Order by the publishingcompany_id_is_reconstructed column
 *
 * @method PublicationQuery groupById() Group by the id column
 * @method PublicationQuery groupByTitleId() Group by the title_id column
 * @method PublicationQuery groupByFirsteditionpublicationId() Group by the firsteditionpublication_id column
 * @method PublicationQuery groupByPlaceId() Group by the place_id column
 * @method PublicationQuery groupByPublicationdateId() Group by the publicationdate_id column
 * @method PublicationQuery groupByCreationdateId() Group by the creationdate_id column
 * @method PublicationQuery groupByPublishingcompanyId() Group by the publishingcompany_id column
 * @method PublicationQuery groupByPartnerId() Group by the partner_id column
 * @method PublicationQuery groupByEditiondescription() Group by the editiondescription column
 * @method PublicationQuery groupByDigitaleditioneditor() Group by the digitaleditioneditor column
 * @method PublicationQuery groupByTranscriptioncomment() Group by the transcriptioncomment column
 * @method PublicationQuery groupByNumpages() Group by the numpages column
 * @method PublicationQuery groupByNumpagesnumeric() Group by the numpagesnumeric column
 * @method PublicationQuery groupByComment() Group by the comment column
 * @method PublicationQuery groupByEncodingComment() Group by the encoding_comment column
 * @method PublicationQuery groupByDoi() Group by the doi column
 * @method PublicationQuery groupByFormat() Group by the format column
 * @method PublicationQuery groupByDirectoryname() Group by the directoryname column
 * @method PublicationQuery groupByWwwready() Group by the wwwready column
 * @method PublicationQuery groupByLastChangedByUserId() Group by the last_changed_by_user_id column
 * @method PublicationQuery groupByLegacyBookId() Group by the legacy_book_id column
 * @method PublicationQuery groupByCreatedAt() Group by the created_at column
 * @method PublicationQuery groupByUpdatedAt() Group by the updated_at column
 * @method PublicationQuery groupByPublishingcompanyIdIsReconstructed() Group by the publishingcompany_id_is_reconstructed column
 *
 * @method PublicationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PublicationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PublicationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
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
 * @method PublicationQuery leftJoinDatespecificationRelatedByCreationdateId($relationAlias = null) Adds a LEFT JOIN clause to the query using the DatespecificationRelatedByCreationdateId relation
 * @method PublicationQuery rightJoinDatespecificationRelatedByCreationdateId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DatespecificationRelatedByCreationdateId relation
 * @method PublicationQuery innerJoinDatespecificationRelatedByCreationdateId($relationAlias = null) Adds a INNER JOIN clause to the query using the DatespecificationRelatedByCreationdateId relation
 *
 * @method PublicationQuery leftJoinLastChangedByUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the LastChangedByUser relation
 * @method PublicationQuery rightJoinLastChangedByUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LastChangedByUser relation
 * @method PublicationQuery innerJoinLastChangedByUser($relationAlias = null) Adds a INNER JOIN clause to the query using the LastChangedByUser relation
 *
 * @method PublicationQuery leftJoinPublicationM($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationM relation
 * @method PublicationQuery rightJoinPublicationM($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationM relation
 * @method PublicationQuery innerJoinPublicationM($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationM relation
 *
 * @method PublicationQuery leftJoinPublicationDm($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationDm relation
 * @method PublicationQuery rightJoinPublicationDm($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationDm relation
 * @method PublicationQuery innerJoinPublicationDm($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationDm relation
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
 * @method PublicationQuery leftJoinVolumeRelatedByPublicationId($relationAlias = null) Adds a LEFT JOIN clause to the query using the VolumeRelatedByPublicationId relation
 * @method PublicationQuery rightJoinVolumeRelatedByPublicationId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the VolumeRelatedByPublicationId relation
 * @method PublicationQuery innerJoinVolumeRelatedByPublicationId($relationAlias = null) Adds a INNER JOIN clause to the query using the VolumeRelatedByPublicationId relation
 *
 * @method PublicationQuery leftJoinVolumeRelatedByParentpublicationId($relationAlias = null) Adds a LEFT JOIN clause to the query using the VolumeRelatedByParentpublicationId relation
 * @method PublicationQuery rightJoinVolumeRelatedByParentpublicationId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the VolumeRelatedByParentpublicationId relation
 * @method PublicationQuery innerJoinVolumeRelatedByParentpublicationId($relationAlias = null) Adds a INNER JOIN clause to the query using the VolumeRelatedByParentpublicationId relation
 *
 * @method PublicationQuery leftJoinLanguagePublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the LanguagePublication relation
 * @method PublicationQuery rightJoinLanguagePublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LanguagePublication relation
 * @method PublicationQuery innerJoinLanguagePublication($relationAlias = null) Adds a INNER JOIN clause to the query using the LanguagePublication relation
 *
 * @method PublicationQuery leftJoinGenrePublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the GenrePublication relation
 * @method PublicationQuery rightJoinGenrePublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GenrePublication relation
 * @method PublicationQuery innerJoinGenrePublication($relationAlias = null) Adds a INNER JOIN clause to the query using the GenrePublication relation
 *
 * @method PublicationQuery leftJoinPublicationTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationTag relation
 * @method PublicationQuery rightJoinPublicationTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationTag relation
 * @method PublicationQuery innerJoinPublicationTag($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationTag relation
 *
 * @method PublicationQuery leftJoinCategoryPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the CategoryPublication relation
 * @method PublicationQuery rightJoinCategoryPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CategoryPublication relation
 * @method PublicationQuery innerJoinCategoryPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the CategoryPublication relation
 *
 * @method PublicationQuery leftJoinFontPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the FontPublication relation
 * @method PublicationQuery rightJoinFontPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FontPublication relation
 * @method PublicationQuery innerJoinFontPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the FontPublication relation
 *
 * @method PublicationQuery leftJoinPublicationPublicationgroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the PublicationPublicationgroup relation
 * @method PublicationQuery rightJoinPublicationPublicationgroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PublicationPublicationgroup relation
 * @method PublicationQuery innerJoinPublicationPublicationgroup($relationAlias = null) Adds a INNER JOIN clause to the query using the PublicationPublicationgroup relation
 *
 * @method PublicationQuery leftJoinPersonPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the PersonPublication relation
 * @method PublicationQuery rightJoinPersonPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PersonPublication relation
 * @method PublicationQuery innerJoinPersonPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the PersonPublication relation
 *
 * @method PublicationQuery leftJoinRecentUse($relationAlias = null) Adds a LEFT JOIN clause to the query using the RecentUse relation
 * @method PublicationQuery rightJoinRecentUse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RecentUse relation
 * @method PublicationQuery innerJoinRecentUse($relationAlias = null) Adds a INNER JOIN clause to the query using the RecentUse relation
 *
 * @method PublicationQuery leftJoinTask($relationAlias = null) Adds a LEFT JOIN clause to the query using the Task relation
 * @method PublicationQuery rightJoinTask($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Task relation
 * @method PublicationQuery innerJoinTask($relationAlias = null) Adds a INNER JOIN clause to the query using the Task relation
 *
 * @method PublicationQuery leftJoinCopyLocation($relationAlias = null) Adds a LEFT JOIN clause to the query using the CopyLocation relation
 * @method PublicationQuery rightJoinCopyLocation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CopyLocation relation
 * @method PublicationQuery innerJoinCopyLocation($relationAlias = null) Adds a INNER JOIN clause to the query using the CopyLocation relation
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
 * @method Publication findOneByTitleId(int $title_id) Return the first Publication filtered by the title_id column
 * @method Publication findOneByFirsteditionpublicationId(int $firsteditionpublication_id) Return the first Publication filtered by the firsteditionpublication_id column
 * @method Publication findOneByPlaceId(int $place_id) Return the first Publication filtered by the place_id column
 * @method Publication findOneByPublicationdateId(int $publicationdate_id) Return the first Publication filtered by the publicationdate_id column
 * @method Publication findOneByCreationdateId(int $creationdate_id) Return the first Publication filtered by the creationdate_id column
 * @method Publication findOneByPublishingcompanyId(int $publishingcompany_id) Return the first Publication filtered by the publishingcompany_id column
 * @method Publication findOneByPartnerId(int $partner_id) Return the first Publication filtered by the partner_id column
 * @method Publication findOneByEditiondescription(string $editiondescription) Return the first Publication filtered by the editiondescription column
 * @method Publication findOneByDigitaleditioneditor(string $digitaleditioneditor) Return the first Publication filtered by the digitaleditioneditor column
 * @method Publication findOneByTranscriptioncomment(string $transcriptioncomment) Return the first Publication filtered by the transcriptioncomment column
 * @method Publication findOneByNumpages(string $numpages) Return the first Publication filtered by the numpages column
 * @method Publication findOneByNumpagesnumeric(int $numpagesnumeric) Return the first Publication filtered by the numpagesnumeric column
 * @method Publication findOneByComment(string $comment) Return the first Publication filtered by the comment column
 * @method Publication findOneByEncodingComment(string $encoding_comment) Return the first Publication filtered by the encoding_comment column
 * @method Publication findOneByDoi(string $doi) Return the first Publication filtered by the doi column
 * @method Publication findOneByFormat(string $format) Return the first Publication filtered by the format column
 * @method Publication findOneByDirectoryname(string $directoryname) Return the first Publication filtered by the directoryname column
 * @method Publication findOneByWwwready(int $wwwready) Return the first Publication filtered by the wwwready column
 * @method Publication findOneByLastChangedByUserId(int $last_changed_by_user_id) Return the first Publication filtered by the last_changed_by_user_id column
 * @method Publication findOneByLegacyBookId(int $legacy_book_id) Return the first Publication filtered by the legacy_book_id column
 * @method Publication findOneByCreatedAt(string $created_at) Return the first Publication filtered by the created_at column
 * @method Publication findOneByUpdatedAt(string $updated_at) Return the first Publication filtered by the updated_at column
 * @method Publication findOneByPublishingcompanyIdIsReconstructed(boolean $publishingcompany_id_is_reconstructed) Return the first Publication filtered by the publishingcompany_id_is_reconstructed column
 *
 * @method array findById(int $id) Return Publication objects filtered by the id column
 * @method array findByTitleId(int $title_id) Return Publication objects filtered by the title_id column
 * @method array findByFirsteditionpublicationId(int $firsteditionpublication_id) Return Publication objects filtered by the firsteditionpublication_id column
 * @method array findByPlaceId(int $place_id) Return Publication objects filtered by the place_id column
 * @method array findByPublicationdateId(int $publicationdate_id) Return Publication objects filtered by the publicationdate_id column
 * @method array findByCreationdateId(int $creationdate_id) Return Publication objects filtered by the creationdate_id column
 * @method array findByPublishingcompanyId(int $publishingcompany_id) Return Publication objects filtered by the publishingcompany_id column
 * @method array findByPartnerId(int $partner_id) Return Publication objects filtered by the partner_id column
 * @method array findByEditiondescription(string $editiondescription) Return Publication objects filtered by the editiondescription column
 * @method array findByDigitaleditioneditor(string $digitaleditioneditor) Return Publication objects filtered by the digitaleditioneditor column
 * @method array findByTranscriptioncomment(string $transcriptioncomment) Return Publication objects filtered by the transcriptioncomment column
 * @method array findByNumpages(string $numpages) Return Publication objects filtered by the numpages column
 * @method array findByNumpagesnumeric(int $numpagesnumeric) Return Publication objects filtered by the numpagesnumeric column
 * @method array findByComment(string $comment) Return Publication objects filtered by the comment column
 * @method array findByEncodingComment(string $encoding_comment) Return Publication objects filtered by the encoding_comment column
 * @method array findByDoi(string $doi) Return Publication objects filtered by the doi column
 * @method array findByFormat(string $format) Return Publication objects filtered by the format column
 * @method array findByDirectoryname(string $directoryname) Return Publication objects filtered by the directoryname column
 * @method array findByWwwready(int $wwwready) Return Publication objects filtered by the wwwready column
 * @method array findByLastChangedByUserId(int $last_changed_by_user_id) Return Publication objects filtered by the last_changed_by_user_id column
 * @method array findByLegacyBookId(int $legacy_book_id) Return Publication objects filtered by the legacy_book_id column
 * @method array findByCreatedAt(string $created_at) Return Publication objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Publication objects filtered by the updated_at column
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
    public function __construct($dbName = 'dtametadata', $modelName = 'DTA\\MetadataBundle\\Model\\Data\\Publication', $modelAlias = null)
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
        $sql = 'SELECT "id", "title_id", "firsteditionpublication_id", "place_id", "publicationdate_id", "creationdate_id", "publishingcompany_id", "partner_id", "editiondescription", "digitaleditioneditor", "transcriptioncomment", "numpages", "numpagesnumeric", "comment", "encoding_comment", "doi", "format", "directoryname", "wwwready", "last_changed_by_user_id", "legacy_book_id", "created_at", "updated_at", "publishingcompany_id_is_reconstructed" FROM "publication" WHERE "id" = :p0';
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
     * Filter the query on the firsteditionpublication_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFirsteditionpublicationId(1234); // WHERE firsteditionpublication_id = 1234
     * $query->filterByFirsteditionpublicationId(array(12, 34)); // WHERE firsteditionpublication_id IN (12, 34)
     * $query->filterByFirsteditionpublicationId(array('min' => 12)); // WHERE firsteditionpublication_id >= 12
     * $query->filterByFirsteditionpublicationId(array('max' => 12)); // WHERE firsteditionpublication_id <= 12
     * </code>
     *
     * @param     mixed $firsteditionpublicationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByFirsteditionpublicationId($firsteditionpublicationId = null, $comparison = null)
    {
        if (is_array($firsteditionpublicationId)) {
            $useMinMax = false;
            if (isset($firsteditionpublicationId['min'])) {
                $this->addUsingAlias(PublicationPeer::FIRSTEDITIONPUBLICATION_ID, $firsteditionpublicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($firsteditionpublicationId['max'])) {
                $this->addUsingAlias(PublicationPeer::FIRSTEDITIONPUBLICATION_ID, $firsteditionpublicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::FIRSTEDITIONPUBLICATION_ID, $firsteditionpublicationId, $comparison);
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
     * Filter the query on the creationdate_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCreationdateId(1234); // WHERE creationdate_id = 1234
     * $query->filterByCreationdateId(array(12, 34)); // WHERE creationdate_id IN (12, 34)
     * $query->filterByCreationdateId(array('min' => 12)); // WHERE creationdate_id >= 12
     * $query->filterByCreationdateId(array('max' => 12)); // WHERE creationdate_id <= 12
     * </code>
     *
     * @see       filterByDatespecificationRelatedByCreationdateId()
     *
     * @param     mixed $creationdateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByCreationdateId($creationdateId = null, $comparison = null)
    {
        if (is_array($creationdateId)) {
            $useMinMax = false;
            if (isset($creationdateId['min'])) {
                $this->addUsingAlias(PublicationPeer::CREATIONDATE_ID, $creationdateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($creationdateId['max'])) {
                $this->addUsingAlias(PublicationPeer::CREATIONDATE_ID, $creationdateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::CREATIONDATE_ID, $creationdateId, $comparison);
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
     * Filter the query on the numpages column
     *
     * Example usage:
     * <code>
     * $query->filterByNumpages('fooValue');   // WHERE numpages = 'fooValue'
     * $query->filterByNumpages('%fooValue%'); // WHERE numpages LIKE '%fooValue%'
     * </code>
     *
     * @param     string $numpages The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByNumpages($numpages = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($numpages)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $numpages)) {
                $numpages = str_replace('*', '%', $numpages);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationPeer::NUMPAGES, $numpages, $comparison);
    }

    /**
     * Filter the query on the numpagesnumeric column
     *
     * Example usage:
     * <code>
     * $query->filterByNumpagesnumeric(1234); // WHERE numpagesnumeric = 1234
     * $query->filterByNumpagesnumeric(array(12, 34)); // WHERE numpagesnumeric IN (12, 34)
     * $query->filterByNumpagesnumeric(array('min' => 12)); // WHERE numpagesnumeric >= 12
     * $query->filterByNumpagesnumeric(array('max' => 12)); // WHERE numpagesnumeric <= 12
     * </code>
     *
     * @param     mixed $numpagesnumeric The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByNumpagesnumeric($numpagesnumeric = null, $comparison = null)
    {
        if (is_array($numpagesnumeric)) {
            $useMinMax = false;
            if (isset($numpagesnumeric['min'])) {
                $this->addUsingAlias(PublicationPeer::NUMPAGESNUMERIC, $numpagesnumeric['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numpagesnumeric['max'])) {
                $this->addUsingAlias(PublicationPeer::NUMPAGESNUMERIC, $numpagesnumeric['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::NUMPAGESNUMERIC, $numpagesnumeric, $comparison);
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
     * Filter the query on the encoding_comment column
     *
     * Example usage:
     * <code>
     * $query->filterByEncodingComment('fooValue');   // WHERE encoding_comment = 'fooValue'
     * $query->filterByEncodingComment('%fooValue%'); // WHERE encoding_comment LIKE '%fooValue%'
     * </code>
     *
     * @param     string $encodingComment The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByEncodingComment($encodingComment = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($encodingComment)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $encodingComment)) {
                $encodingComment = str_replace('*', '%', $encodingComment);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationPeer::ENCODING_COMMENT, $encodingComment, $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PublicationPeer::DOI, $doi, $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PublicationPeer::FORMAT, $format, $comparison);
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
     * @return PublicationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PublicationPeer::DIRECTORYNAME, $directoryname, $comparison);
    }

    /**
     * Filter the query on the wwwready column
     *
     * Example usage:
     * <code>
     * $query->filterByWwwready(1234); // WHERE wwwready = 1234
     * $query->filterByWwwready(array(12, 34)); // WHERE wwwready IN (12, 34)
     * $query->filterByWwwready(array('min' => 12)); // WHERE wwwready >= 12
     * $query->filterByWwwready(array('max' => 12)); // WHERE wwwready <= 12
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
     * Filter the query on the last_changed_by_user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLastChangedByUserId(1234); // WHERE last_changed_by_user_id = 1234
     * $query->filterByLastChangedByUserId(array(12, 34)); // WHERE last_changed_by_user_id IN (12, 34)
     * $query->filterByLastChangedByUserId(array('min' => 12)); // WHERE last_changed_by_user_id >= 12
     * $query->filterByLastChangedByUserId(array('max' => 12)); // WHERE last_changed_by_user_id <= 12
     * </code>
     *
     * @see       filterByLastChangedByUser()
     *
     * @param     mixed $lastChangedByUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByLastChangedByUserId($lastChangedByUserId = null, $comparison = null)
    {
        if (is_array($lastChangedByUserId)) {
            $useMinMax = false;
            if (isset($lastChangedByUserId['min'])) {
                $this->addUsingAlias(PublicationPeer::LAST_CHANGED_BY_USER_ID, $lastChangedByUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastChangedByUserId['max'])) {
                $this->addUsingAlias(PublicationPeer::LAST_CHANGED_BY_USER_ID, $lastChangedByUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::LAST_CHANGED_BY_USER_ID, $lastChangedByUserId, $comparison);
    }

    /**
     * Filter the query on the legacy_book_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLegacyBookId(1234); // WHERE legacy_book_id = 1234
     * $query->filterByLegacyBookId(array(12, 34)); // WHERE legacy_book_id IN (12, 34)
     * $query->filterByLegacyBookId(array('min' => 12)); // WHERE legacy_book_id >= 12
     * $query->filterByLegacyBookId(array('max' => 12)); // WHERE legacy_book_id <= 12
     * </code>
     *
     * @param     mixed $legacyBookId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByLegacyBookId($legacyBookId = null, $comparison = null)
    {
        if (is_array($legacyBookId)) {
            $useMinMax = false;
            if (isset($legacyBookId['min'])) {
                $this->addUsingAlias(PublicationPeer::LEGACY_BOOK_ID, $legacyBookId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($legacyBookId['max'])) {
                $this->addUsingAlias(PublicationPeer::LEGACY_BOOK_ID, $legacyBookId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::LEGACY_BOOK_ID, $legacyBookId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PublicationPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PublicationPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PublicationPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PublicationPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return   \DTA\MetadataBundle\Model\Data\TitleQuery A secondary query class using the current class as primary query
     */
    public function useTitleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTitle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Title', '\DTA\MetadataBundle\Model\Data\TitleQuery');
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
    public function filterByDatespecificationRelatedByCreationdateId($datespecification, $comparison = null)
    {
        if ($datespecification instanceof Datespecification) {
            return $this
                ->addUsingAlias(PublicationPeer::CREATIONDATE_ID, $datespecification->getId(), $comparison);
        } elseif ($datespecification instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::CREATIONDATE_ID, $datespecification->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDatespecificationRelatedByCreationdateId() only accepts arguments of type Datespecification or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DatespecificationRelatedByCreationdateId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinDatespecificationRelatedByCreationdateId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DatespecificationRelatedByCreationdateId');

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
            $this->addJoinObject($join, 'DatespecificationRelatedByCreationdateId');
        }

        return $this;
    }

    /**
     * Use the DatespecificationRelatedByCreationdateId relation Datespecification object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\DatespecificationQuery A secondary query class using the current class as primary query
     */
    public function useDatespecificationRelatedByCreationdateIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDatespecificationRelatedByCreationdateId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DatespecificationRelatedByCreationdateId', '\DTA\MetadataBundle\Model\Data\DatespecificationQuery');
    }

    /**
     * Filter the query by a related DtaUser object
     *
     * @param   DtaUser|PropelObjectCollection $dtaUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByLastChangedByUser($dtaUser, $comparison = null)
    {
        if ($dtaUser instanceof DtaUser) {
            return $this
                ->addUsingAlias(PublicationPeer::LAST_CHANGED_BY_USER_ID, $dtaUser->getId(), $comparison);
        } elseif ($dtaUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::LAST_CHANGED_BY_USER_ID, $dtaUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLastChangedByUser() only accepts arguments of type DtaUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LastChangedByUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinLastChangedByUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LastChangedByUser');

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
            $this->addJoinObject($join, 'LastChangedByUser');
        }

        return $this;
    }

    /**
     * Use the LastChangedByUser relation DtaUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\DtaUserQuery A secondary query class using the current class as primary query
     */
    public function useLastChangedByUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLastChangedByUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LastChangedByUser', '\DTA\MetadataBundle\Model\Master\DtaUserQuery');
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
     * Filter the query by a related Volume object
     *
     * @param   Volume|PropelObjectCollection $volume  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByVolumeRelatedByPublicationId($volume, $comparison = null)
    {
        if ($volume instanceof Volume) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $volume->getPublicationId(), $comparison);
        } elseif ($volume instanceof PropelObjectCollection) {
            return $this
                ->useVolumeRelatedByPublicationIdQuery()
                ->filterByPrimaryKeys($volume->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByVolumeRelatedByPublicationId() only accepts arguments of type Volume or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the VolumeRelatedByPublicationId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinVolumeRelatedByPublicationId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('VolumeRelatedByPublicationId');

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
            $this->addJoinObject($join, 'VolumeRelatedByPublicationId');
        }

        return $this;
    }

    /**
     * Use the VolumeRelatedByPublicationId relation Volume object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\VolumeQuery A secondary query class using the current class as primary query
     */
    public function useVolumeRelatedByPublicationIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinVolumeRelatedByPublicationId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'VolumeRelatedByPublicationId', '\DTA\MetadataBundle\Model\Data\VolumeQuery');
    }

    /**
     * Filter the query by a related Volume object
     *
     * @param   Volume|PropelObjectCollection $volume  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByVolumeRelatedByParentpublicationId($volume, $comparison = null)
    {
        if ($volume instanceof Volume) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $volume->getParentpublicationId(), $comparison);
        } elseif ($volume instanceof PropelObjectCollection) {
            return $this
                ->useVolumeRelatedByParentpublicationIdQuery()
                ->filterByPrimaryKeys($volume->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByVolumeRelatedByParentpublicationId() only accepts arguments of type Volume or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the VolumeRelatedByParentpublicationId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinVolumeRelatedByParentpublicationId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('VolumeRelatedByParentpublicationId');

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
            $this->addJoinObject($join, 'VolumeRelatedByParentpublicationId');
        }

        return $this;
    }

    /**
     * Use the VolumeRelatedByParentpublicationId relation Volume object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\VolumeQuery A secondary query class using the current class as primary query
     */
    public function useVolumeRelatedByParentpublicationIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinVolumeRelatedByParentpublicationId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'VolumeRelatedByParentpublicationId', '\DTA\MetadataBundle\Model\Data\VolumeQuery');
    }

    /**
     * Filter the query by a related LanguagePublication object
     *
     * @param   LanguagePublication|PropelObjectCollection $languagePublication  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByLanguagePublication($languagePublication, $comparison = null)
    {
        if ($languagePublication instanceof LanguagePublication) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $languagePublication->getPublicationId(), $comparison);
        } elseif ($languagePublication instanceof PropelObjectCollection) {
            return $this
                ->useLanguagePublicationQuery()
                ->filterByPrimaryKeys($languagePublication->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLanguagePublication() only accepts arguments of type LanguagePublication or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LanguagePublication relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinLanguagePublication($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LanguagePublication');

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
            $this->addJoinObject($join, 'LanguagePublication');
        }

        return $this;
    }

    /**
     * Use the LanguagePublication relation LanguagePublication object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\LanguagePublicationQuery A secondary query class using the current class as primary query
     */
    public function useLanguagePublicationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLanguagePublication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LanguagePublication', '\DTA\MetadataBundle\Model\Master\LanguagePublicationQuery');
    }

    /**
     * Filter the query by a related GenrePublication object
     *
     * @param   GenrePublication|PropelObjectCollection $genrePublication  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByGenrePublication($genrePublication, $comparison = null)
    {
        if ($genrePublication instanceof GenrePublication) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $genrePublication->getPublicationId(), $comparison);
        } elseif ($genrePublication instanceof PropelObjectCollection) {
            return $this
                ->useGenrePublicationQuery()
                ->filterByPrimaryKeys($genrePublication->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByGenrePublication() only accepts arguments of type GenrePublication or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GenrePublication relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinGenrePublication($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GenrePublication');

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
            $this->addJoinObject($join, 'GenrePublication');
        }

        return $this;
    }

    /**
     * Use the GenrePublication relation GenrePublication object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\GenrePublicationQuery A secondary query class using the current class as primary query
     */
    public function useGenrePublicationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGenrePublication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GenrePublication', '\DTA\MetadataBundle\Model\Master\GenrePublicationQuery');
    }

    /**
     * Filter the query by a related PublicationTag object
     *
     * @param   PublicationTag|PropelObjectCollection $publicationTag  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublicationTag($publicationTag, $comparison = null)
    {
        if ($publicationTag instanceof PublicationTag) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $publicationTag->getPublicationId(), $comparison);
        } elseif ($publicationTag instanceof PropelObjectCollection) {
            return $this
                ->usePublicationTagQuery()
                ->filterByPrimaryKeys($publicationTag->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPublicationTag() only accepts arguments of type PublicationTag or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PublicationTag relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinPublicationTag($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PublicationTag');

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
            $this->addJoinObject($join, 'PublicationTag');
        }

        return $this;
    }

    /**
     * Use the PublicationTag relation PublicationTag object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\PublicationTagQuery A secondary query class using the current class as primary query
     */
    public function usePublicationTagQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublicationTag($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PublicationTag', '\DTA\MetadataBundle\Model\Master\PublicationTagQuery');
    }

    /**
     * Filter the query by a related CategoryPublication object
     *
     * @param   CategoryPublication|PropelObjectCollection $categoryPublication  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCategoryPublication($categoryPublication, $comparison = null)
    {
        if ($categoryPublication instanceof CategoryPublication) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $categoryPublication->getPublicationId(), $comparison);
        } elseif ($categoryPublication instanceof PropelObjectCollection) {
            return $this
                ->useCategoryPublicationQuery()
                ->filterByPrimaryKeys($categoryPublication->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCategoryPublication() only accepts arguments of type CategoryPublication or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CategoryPublication relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinCategoryPublication($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CategoryPublication');

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
            $this->addJoinObject($join, 'CategoryPublication');
        }

        return $this;
    }

    /**
     * Use the CategoryPublication relation CategoryPublication object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\CategoryPublicationQuery A secondary query class using the current class as primary query
     */
    public function useCategoryPublicationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCategoryPublication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CategoryPublication', '\DTA\MetadataBundle\Model\Master\CategoryPublicationQuery');
    }

    /**
     * Filter the query by a related FontPublication object
     *
     * @param   FontPublication|PropelObjectCollection $fontPublication  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFontPublication($fontPublication, $comparison = null)
    {
        if ($fontPublication instanceof FontPublication) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $fontPublication->getPublicationId(), $comparison);
        } elseif ($fontPublication instanceof PropelObjectCollection) {
            return $this
                ->useFontPublicationQuery()
                ->filterByPrimaryKeys($fontPublication->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFontPublication() only accepts arguments of type FontPublication or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FontPublication relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinFontPublication($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FontPublication');

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
            $this->addJoinObject($join, 'FontPublication');
        }

        return $this;
    }

    /**
     * Use the FontPublication relation FontPublication object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\FontPublicationQuery A secondary query class using the current class as primary query
     */
    public function useFontPublicationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFontPublication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FontPublication', '\DTA\MetadataBundle\Model\Master\FontPublicationQuery');
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
     * Filter the query by a related RecentUse object
     *
     * @param   RecentUse|PropelObjectCollection $recentUse  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByRecentUse($recentUse, $comparison = null)
    {
        if ($recentUse instanceof RecentUse) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $recentUse->getPublicationId(), $comparison);
        } elseif ($recentUse instanceof PropelObjectCollection) {
            return $this
                ->useRecentUseQuery()
                ->filterByPrimaryKeys($recentUse->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByRecentUse() only accepts arguments of type RecentUse or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the RecentUse relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinRecentUse($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('RecentUse');

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
            $this->addJoinObject($join, 'RecentUse');
        }

        return $this;
    }

    /**
     * Use the RecentUse relation RecentUse object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\RecentUseQuery A secondary query class using the current class as primary query
     */
    public function useRecentUseQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinRecentUse($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'RecentUse', '\DTA\MetadataBundle\Model\Master\RecentUseQuery');
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
     * Filter the query by a related CopyLocation object
     *
     * @param   CopyLocation|PropelObjectCollection $copyLocation  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCopyLocation($copyLocation, $comparison = null)
    {
        if ($copyLocation instanceof CopyLocation) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $copyLocation->getPublicationId(), $comparison);
        } elseif ($copyLocation instanceof PropelObjectCollection) {
            return $this
                ->useCopyLocationQuery()
                ->filterByPrimaryKeys($copyLocation->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCopyLocation() only accepts arguments of type CopyLocation or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CopyLocation relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinCopyLocation($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CopyLocation');

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
            $this->addJoinObject($join, 'CopyLocation');
        }

        return $this;
    }

    /**
     * Use the CopyLocation relation CopyLocation object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Workflow\CopyLocationQuery A secondary query class using the current class as primary query
     */
    public function useCopyLocationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCopyLocation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CopyLocation', '\DTA\MetadataBundle\Model\Workflow\CopyLocationQuery');
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
     * Filter the query by a related Language object
     * using the language_publication table as cross reference
     *
     * @param   Language $language the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationQuery The current query, for fluid interface
     */
    public function filterByLanguage($language, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useLanguagePublicationQuery()
            ->filterByLanguage($language, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Genre object
     * using the genre_publication table as cross reference
     *
     * @param   Genre $genre the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationQuery The current query, for fluid interface
     */
    public function filterByGenre($genre, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useGenrePublicationQuery()
            ->filterByGenre($genre, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Tag object
     * using the publication_tag table as cross reference
     *
     * @param   Tag $tag the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationQuery The current query, for fluid interface
     */
    public function filterByTag($tag, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePublicationTagQuery()
            ->filterByTag($tag, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Category object
     * using the category_publication table as cross reference
     *
     * @param   Category $category the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationQuery The current query, for fluid interface
     */
    public function filterByCategory($category, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCategoryPublicationQuery()
            ->filterByCategory($category, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Font object
     * using the font_publication table as cross reference
     *
     * @param   Font $font the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PublicationQuery The current query, for fluid interface
     */
    public function filterByFont($font, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useFontPublicationQuery()
            ->filterByFont($font, $comparison)
            ->endUse();
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

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PublicationQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PublicationPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PublicationQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PublicationPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PublicationQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PublicationPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PublicationQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PublicationPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PublicationQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PublicationPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PublicationQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PublicationPeer::CREATED_AT);
    }
}
