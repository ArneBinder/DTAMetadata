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
use DTA\MetadataBundle\Model\Classification\Source;
use DTA\MetadataBundle\Model\Classification\Tag;
use DTA\MetadataBundle\Model\Data\Article;
use DTA\MetadataBundle\Model\Data\Chapter;
use DTA\MetadataBundle\Model\Data\Datespecification;
use DTA\MetadataBundle\Model\Data\Font;
use DTA\MetadataBundle\Model\Data\Language;
use DTA\MetadataBundle\Model\Data\MultiVolume;
use DTA\MetadataBundle\Model\Data\Place;
use DTA\MetadataBundle\Model\Data\Publication;
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
use DTA\MetadataBundle\Model\Master\SequenceEntry;
use DTA\MetadataBundle\Model\Workflow\CopyLocation;
use DTA\MetadataBundle\Model\Workflow\Imagesource;
use DTA\MetadataBundle\Model\Workflow\Publicationgroup;
use DTA\MetadataBundle\Model\Workflow\Task;
use DTA\MetadataBundle\Model\Workflow\Textsource;

/**
 * @method PublicationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PublicationQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method PublicationQuery orderByLegacytype($order = Criteria::ASC) Order by the legacytype column
 * @method PublicationQuery orderByTitleId($order = Criteria::ASC) Order by the title_id column
 * @method PublicationQuery orderByFirsteditionpublicationId($order = Criteria::ASC) Order by the firsteditionpublication_id column
 * @method PublicationQuery orderByPlaceId($order = Criteria::ASC) Order by the place_id column
 * @method PublicationQuery orderByPublicationdateId($order = Criteria::ASC) Order by the publicationdate_id column
 * @method PublicationQuery orderByCreationdateId($order = Criteria::ASC) Order by the creationdate_id column
 * @method PublicationQuery orderByPublishingcompanyId($order = Criteria::ASC) Order by the publishingcompany_id column
 * @method PublicationQuery orderBySourceId($order = Criteria::ASC) Order by the source_id column
 * @method PublicationQuery orderByLegacygenre($order = Criteria::ASC) Order by the legacygenre column
 * @method PublicationQuery orderByLegacysubgenre($order = Criteria::ASC) Order by the legacysubgenre column
 * @method PublicationQuery orderByDirname($order = Criteria::ASC) Order by the dirname column
 * @method PublicationQuery orderByUsedcopylocationId($order = Criteria::ASC) Order by the usedcopylocation_id column
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
 * @method PublicationQuery orderByTreeId($order = Criteria::ASC) Order by the tree_id column
 * @method PublicationQuery orderByTreeLeft($order = Criteria::ASC) Order by the tree_left column
 * @method PublicationQuery orderByTreeRight($order = Criteria::ASC) Order by the tree_right column
 * @method PublicationQuery orderByTreeLevel($order = Criteria::ASC) Order by the tree_level column
 * @method PublicationQuery orderByPublishingcompanyIdIsReconstructed($order = Criteria::ASC) Order by the publishingcompany_id_is_reconstructed column
 * @method PublicationQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PublicationQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PublicationQuery groupById() Group by the id column
 * @method PublicationQuery groupByType() Group by the type column
 * @method PublicationQuery groupByLegacytype() Group by the legacytype column
 * @method PublicationQuery groupByTitleId() Group by the title_id column
 * @method PublicationQuery groupByFirsteditionpublicationId() Group by the firsteditionpublication_id column
 * @method PublicationQuery groupByPlaceId() Group by the place_id column
 * @method PublicationQuery groupByPublicationdateId() Group by the publicationdate_id column
 * @method PublicationQuery groupByCreationdateId() Group by the creationdate_id column
 * @method PublicationQuery groupByPublishingcompanyId() Group by the publishingcompany_id column
 * @method PublicationQuery groupBySourceId() Group by the source_id column
 * @method PublicationQuery groupByLegacygenre() Group by the legacygenre column
 * @method PublicationQuery groupByLegacysubgenre() Group by the legacysubgenre column
 * @method PublicationQuery groupByDirname() Group by the dirname column
 * @method PublicationQuery groupByUsedcopylocationId() Group by the usedcopylocation_id column
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
 * @method PublicationQuery groupByTreeId() Group by the tree_id column
 * @method PublicationQuery groupByTreeLeft() Group by the tree_left column
 * @method PublicationQuery groupByTreeRight() Group by the tree_right column
 * @method PublicationQuery groupByTreeLevel() Group by the tree_level column
 * @method PublicationQuery groupByPublishingcompanyIdIsReconstructed() Group by the publishingcompany_id_is_reconstructed column
 * @method PublicationQuery groupByCreatedAt() Group by the created_at column
 * @method PublicationQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PublicationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PublicationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PublicationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PublicationQuery leftJoinTitle($relationAlias = null) Adds a LEFT JOIN clause to the query using the Title relation
 * @method PublicationQuery rightJoinTitle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Title relation
 * @method PublicationQuery innerJoinTitle($relationAlias = null) Adds a INNER JOIN clause to the query using the Title relation
 *
 * @method PublicationQuery leftJoinSource($relationAlias = null) Adds a LEFT JOIN clause to the query using the Source relation
 * @method PublicationQuery rightJoinSource($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Source relation
 * @method PublicationQuery innerJoinSource($relationAlias = null) Adds a INNER JOIN clause to the query using the Source relation
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
 * @method PublicationQuery leftJoinMultiVolume($relationAlias = null) Adds a LEFT JOIN clause to the query using the MultiVolume relation
 * @method PublicationQuery rightJoinMultiVolume($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MultiVolume relation
 * @method PublicationQuery innerJoinMultiVolume($relationAlias = null) Adds a INNER JOIN clause to the query using the MultiVolume relation
 *
 * @method PublicationQuery leftJoinVolume($relationAlias = null) Adds a LEFT JOIN clause to the query using the Volume relation
 * @method PublicationQuery rightJoinVolume($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Volume relation
 * @method PublicationQuery innerJoinVolume($relationAlias = null) Adds a INNER JOIN clause to the query using the Volume relation
 *
 * @method PublicationQuery leftJoinChapter($relationAlias = null) Adds a LEFT JOIN clause to the query using the Chapter relation
 * @method PublicationQuery rightJoinChapter($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Chapter relation
 * @method PublicationQuery innerJoinChapter($relationAlias = null) Adds a INNER JOIN clause to the query using the Chapter relation
 *
 * @method PublicationQuery leftJoinArticle($relationAlias = null) Adds a LEFT JOIN clause to the query using the Article relation
 * @method PublicationQuery rightJoinArticle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Article relation
 * @method PublicationQuery innerJoinArticle($relationAlias = null) Adds a INNER JOIN clause to the query using the Article relation
 *
 * @method PublicationQuery leftJoinSequenceEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the SequenceEntry relation
 * @method PublicationQuery rightJoinSequenceEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SequenceEntry relation
 * @method PublicationQuery innerJoinSequenceEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the SequenceEntry relation
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
 * @method Publication findOneByType(int $type) Return the first Publication filtered by the type column
 * @method Publication findOneByLegacytype(string $legacytype) Return the first Publication filtered by the legacytype column
 * @method Publication findOneByTitleId(int $title_id) Return the first Publication filtered by the title_id column
 * @method Publication findOneByFirsteditionpublicationId(int $firsteditionpublication_id) Return the first Publication filtered by the firsteditionpublication_id column
 * @method Publication findOneByPlaceId(int $place_id) Return the first Publication filtered by the place_id column
 * @method Publication findOneByPublicationdateId(int $publicationdate_id) Return the first Publication filtered by the publicationdate_id column
 * @method Publication findOneByCreationdateId(int $creationdate_id) Return the first Publication filtered by the creationdate_id column
 * @method Publication findOneByPublishingcompanyId(int $publishingcompany_id) Return the first Publication filtered by the publishingcompany_id column
 * @method Publication findOneBySourceId(int $source_id) Return the first Publication filtered by the source_id column
 * @method Publication findOneByLegacygenre(string $legacygenre) Return the first Publication filtered by the legacygenre column
 * @method Publication findOneByLegacysubgenre(string $legacysubgenre) Return the first Publication filtered by the legacysubgenre column
 * @method Publication findOneByDirname(string $dirname) Return the first Publication filtered by the dirname column
 * @method Publication findOneByUsedcopylocationId(int $usedcopylocation_id) Return the first Publication filtered by the usedcopylocation_id column
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
 * @method Publication findOneByTreeId(int $tree_id) Return the first Publication filtered by the tree_id column
 * @method Publication findOneByTreeLeft(int $tree_left) Return the first Publication filtered by the tree_left column
 * @method Publication findOneByTreeRight(int $tree_right) Return the first Publication filtered by the tree_right column
 * @method Publication findOneByTreeLevel(int $tree_level) Return the first Publication filtered by the tree_level column
 * @method Publication findOneByPublishingcompanyIdIsReconstructed(boolean $publishingcompany_id_is_reconstructed) Return the first Publication filtered by the publishingcompany_id_is_reconstructed column
 * @method Publication findOneByCreatedAt(string $created_at) Return the first Publication filtered by the created_at column
 * @method Publication findOneByUpdatedAt(string $updated_at) Return the first Publication filtered by the updated_at column
 *
 * @method array findById(int $id) Return Publication objects filtered by the id column
 * @method array findByType(int $type) Return Publication objects filtered by the type column
 * @method array findByLegacytype(string $legacytype) Return Publication objects filtered by the legacytype column
 * @method array findByTitleId(int $title_id) Return Publication objects filtered by the title_id column
 * @method array findByFirsteditionpublicationId(int $firsteditionpublication_id) Return Publication objects filtered by the firsteditionpublication_id column
 * @method array findByPlaceId(int $place_id) Return Publication objects filtered by the place_id column
 * @method array findByPublicationdateId(int $publicationdate_id) Return Publication objects filtered by the publicationdate_id column
 * @method array findByCreationdateId(int $creationdate_id) Return Publication objects filtered by the creationdate_id column
 * @method array findByPublishingcompanyId(int $publishingcompany_id) Return Publication objects filtered by the publishingcompany_id column
 * @method array findBySourceId(int $source_id) Return Publication objects filtered by the source_id column
 * @method array findByLegacygenre(string $legacygenre) Return Publication objects filtered by the legacygenre column
 * @method array findByLegacysubgenre(string $legacysubgenre) Return Publication objects filtered by the legacysubgenre column
 * @method array findByDirname(string $dirname) Return Publication objects filtered by the dirname column
 * @method array findByUsedcopylocationId(int $usedcopylocation_id) Return Publication objects filtered by the usedcopylocation_id column
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
 * @method array findByTreeId(int $tree_id) Return Publication objects filtered by the tree_id column
 * @method array findByTreeLeft(int $tree_left) Return Publication objects filtered by the tree_left column
 * @method array findByTreeRight(int $tree_right) Return Publication objects filtered by the tree_right column
 * @method array findByTreeLevel(int $tree_level) Return Publication objects filtered by the tree_level column
 * @method array findByPublishingcompanyIdIsReconstructed(boolean $publishingcompany_id_is_reconstructed) Return Publication objects filtered by the publishingcompany_id_is_reconstructed column
 * @method array findByCreatedAt(string $created_at) Return Publication objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Publication objects filtered by the updated_at column
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
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'dtametadata';
        }
        if (null === $modelName) {
            $modelName = 'DTA\\MetadataBundle\\Model\\Data\\Publication';
        }
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
        $query = new PublicationQuery(null, null, $modelAlias);

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
            // the object is already in the instance pool
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
        $sql = 'SELECT "id", "type", "legacytype", "title_id", "firsteditionpublication_id", "place_id", "publicationdate_id", "creationdate_id", "publishingcompany_id", "source_id", "legacygenre", "legacysubgenre", "dirname", "usedcopylocation_id", "partner_id", "editiondescription", "digitaleditioneditor", "transcriptioncomment", "numpages", "numpagesnumeric", "comment", "encoding_comment", "doi", "format", "directoryname", "wwwready", "last_changed_by_user_id", "tree_id", "tree_left", "tree_right", "tree_level", "publishingcompany_id_is_reconstructed", "created_at", "updated_at" FROM "publication" WHERE "id" = :p0';
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
     * Filter the query on the type column
     *
     * @param     mixed $type The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (is_scalar($type)) {
            $type = PublicationPeer::getSqlValueForEnum(PublicationPeer::TYPE, $type);
        } elseif (is_array($type)) {
            $convertedValues = array();
            foreach ($type as $value) {
                $convertedValues[] = PublicationPeer::getSqlValueForEnum(PublicationPeer::TYPE, $value);
            }
            $type = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the legacytype column
     *
     * Example usage:
     * <code>
     * $query->filterByLegacytype('fooValue');   // WHERE legacytype = 'fooValue'
     * $query->filterByLegacytype('%fooValue%'); // WHERE legacytype LIKE '%fooValue%'
     * </code>
     *
     * @param     string $legacytype The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByLegacytype($legacytype = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($legacytype)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $legacytype)) {
                $legacytype = str_replace('*', '%', $legacytype);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationPeer::LEGACYTYPE, $legacytype, $comparison);
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
     * Filter the query on the source_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySourceId(1234); // WHERE source_id = 1234
     * $query->filterBySourceId(array(12, 34)); // WHERE source_id IN (12, 34)
     * $query->filterBySourceId(array('min' => 12)); // WHERE source_id >= 12
     * $query->filterBySourceId(array('max' => 12)); // WHERE source_id <= 12
     * </code>
     *
     * @see       filterBySource()
     *
     * @param     mixed $sourceId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterBySourceId($sourceId = null, $comparison = null)
    {
        if (is_array($sourceId)) {
            $useMinMax = false;
            if (isset($sourceId['min'])) {
                $this->addUsingAlias(PublicationPeer::SOURCE_ID, $sourceId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sourceId['max'])) {
                $this->addUsingAlias(PublicationPeer::SOURCE_ID, $sourceId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::SOURCE_ID, $sourceId, $comparison);
    }

    /**
     * Filter the query on the legacygenre column
     *
     * Example usage:
     * <code>
     * $query->filterByLegacygenre('fooValue');   // WHERE legacygenre = 'fooValue'
     * $query->filterByLegacygenre('%fooValue%'); // WHERE legacygenre LIKE '%fooValue%'
     * </code>
     *
     * @param     string $legacygenre The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByLegacygenre($legacygenre = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($legacygenre)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $legacygenre)) {
                $legacygenre = str_replace('*', '%', $legacygenre);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationPeer::LEGACYGENRE, $legacygenre, $comparison);
    }

    /**
     * Filter the query on the legacysubgenre column
     *
     * Example usage:
     * <code>
     * $query->filterByLegacysubgenre('fooValue');   // WHERE legacysubgenre = 'fooValue'
     * $query->filterByLegacysubgenre('%fooValue%'); // WHERE legacysubgenre LIKE '%fooValue%'
     * </code>
     *
     * @param     string $legacysubgenre The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByLegacysubgenre($legacysubgenre = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($legacysubgenre)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $legacysubgenre)) {
                $legacysubgenre = str_replace('*', '%', $legacysubgenre);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationPeer::LEGACYSUBGENRE, $legacysubgenre, $comparison);
    }

    /**
     * Filter the query on the dirname column
     *
     * Example usage:
     * <code>
     * $query->filterByDirname('fooValue');   // WHERE dirname = 'fooValue'
     * $query->filterByDirname('%fooValue%'); // WHERE dirname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $dirname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByDirname($dirname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($dirname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $dirname)) {
                $dirname = str_replace('*', '%', $dirname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PublicationPeer::DIRNAME, $dirname, $comparison);
    }

    /**
     * Filter the query on the usedcopylocation_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUsedcopylocationId(1234); // WHERE usedcopylocation_id = 1234
     * $query->filterByUsedcopylocationId(array(12, 34)); // WHERE usedcopylocation_id IN (12, 34)
     * $query->filterByUsedcopylocationId(array('min' => 12)); // WHERE usedcopylocation_id >= 12
     * $query->filterByUsedcopylocationId(array('max' => 12)); // WHERE usedcopylocation_id <= 12
     * </code>
     *
     * @param     mixed $usedcopylocationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByUsedcopylocationId($usedcopylocationId = null, $comparison = null)
    {
        if (is_array($usedcopylocationId)) {
            $useMinMax = false;
            if (isset($usedcopylocationId['min'])) {
                $this->addUsingAlias(PublicationPeer::USEDCOPYLOCATION_ID, $usedcopylocationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($usedcopylocationId['max'])) {
                $this->addUsingAlias(PublicationPeer::USEDCOPYLOCATION_ID, $usedcopylocationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::USEDCOPYLOCATION_ID, $usedcopylocationId, $comparison);
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
     * Filter the query on the tree_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeId(1234); // WHERE tree_id = 1234
     * $query->filterByTreeId(array(12, 34)); // WHERE tree_id IN (12, 34)
     * $query->filterByTreeId(array('min' => 12)); // WHERE tree_id >= 12
     * $query->filterByTreeId(array('max' => 12)); // WHERE tree_id <= 12
     * </code>
     *
     * @param     mixed $treeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByTreeId($treeId = null, $comparison = null)
    {
        if (is_array($treeId)) {
            $useMinMax = false;
            if (isset($treeId['min'])) {
                $this->addUsingAlias(PublicationPeer::TREE_ID, $treeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeId['max'])) {
                $this->addUsingAlias(PublicationPeer::TREE_ID, $treeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::TREE_ID, $treeId, $comparison);
    }

    /**
     * Filter the query on the tree_left column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLeft(1234); // WHERE tree_left = 1234
     * $query->filterByTreeLeft(array(12, 34)); // WHERE tree_left IN (12, 34)
     * $query->filterByTreeLeft(array('min' => 12)); // WHERE tree_left >= 12
     * $query->filterByTreeLeft(array('max' => 12)); // WHERE tree_left <= 12
     * </code>
     *
     * @param     mixed $treeLeft The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByTreeLeft($treeLeft = null, $comparison = null)
    {
        if (is_array($treeLeft)) {
            $useMinMax = false;
            if (isset($treeLeft['min'])) {
                $this->addUsingAlias(PublicationPeer::TREE_LEFT, $treeLeft['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLeft['max'])) {
                $this->addUsingAlias(PublicationPeer::TREE_LEFT, $treeLeft['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::TREE_LEFT, $treeLeft, $comparison);
    }

    /**
     * Filter the query on the tree_right column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeRight(1234); // WHERE tree_right = 1234
     * $query->filterByTreeRight(array(12, 34)); // WHERE tree_right IN (12, 34)
     * $query->filterByTreeRight(array('min' => 12)); // WHERE tree_right >= 12
     * $query->filterByTreeRight(array('max' => 12)); // WHERE tree_right <= 12
     * </code>
     *
     * @param     mixed $treeRight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByTreeRight($treeRight = null, $comparison = null)
    {
        if (is_array($treeRight)) {
            $useMinMax = false;
            if (isset($treeRight['min'])) {
                $this->addUsingAlias(PublicationPeer::TREE_RIGHT, $treeRight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeRight['max'])) {
                $this->addUsingAlias(PublicationPeer::TREE_RIGHT, $treeRight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::TREE_RIGHT, $treeRight, $comparison);
    }

    /**
     * Filter the query on the tree_level column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLevel(1234); // WHERE tree_level = 1234
     * $query->filterByTreeLevel(array(12, 34)); // WHERE tree_level IN (12, 34)
     * $query->filterByTreeLevel(array('min' => 12)); // WHERE tree_level >= 12
     * $query->filterByTreeLevel(array('max' => 12)); // WHERE tree_level <= 12
     * </code>
     *
     * @param     mixed $treeLevel The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function filterByTreeLevel($treeLevel = null, $comparison = null)
    {
        if (is_array($treeLevel)) {
            $useMinMax = false;
            if (isset($treeLevel['min'])) {
                $this->addUsingAlias(PublicationPeer::TREE_LEVEL, $treeLevel['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLevel['max'])) {
                $this->addUsingAlias(PublicationPeer::TREE_LEVEL, $treeLevel['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublicationPeer::TREE_LEVEL, $treeLevel, $comparison);
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
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at < '2011-03-13'
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
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at < '2011-03-13'
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
     * Filter the query by a related Source object
     *
     * @param   Source|PropelObjectCollection $source The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySource($source, $comparison = null)
    {
        if ($source instanceof Source) {
            return $this
                ->addUsingAlias(PublicationPeer::SOURCE_ID, $source->getId(), $comparison);
        } elseif ($source instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PublicationPeer::SOURCE_ID, $source->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
    public function joinSource($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @return   \DTA\MetadataBundle\Model\Classification\SourceQuery A secondary query class using the current class as primary query
     */
    public function useSourceQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSource($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Source', '\DTA\MetadataBundle\Model\Classification\SourceQuery');
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
     * Filter the query by a related MultiVolume object
     *
     * @param   MultiVolume|PropelObjectCollection $multiVolume  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMultiVolume($multiVolume, $comparison = null)
    {
        if ($multiVolume instanceof MultiVolume) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $multiVolume->getPublicationId(), $comparison);
        } elseif ($multiVolume instanceof PropelObjectCollection) {
            return $this
                ->useMultiVolumeQuery()
                ->filterByPrimaryKeys($multiVolume->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMultiVolume() only accepts arguments of type MultiVolume or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MultiVolume relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinMultiVolume($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MultiVolume');

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
            $this->addJoinObject($join, 'MultiVolume');
        }

        return $this;
    }

    /**
     * Use the MultiVolume relation MultiVolume object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\MultiVolumeQuery A secondary query class using the current class as primary query
     */
    public function useMultiVolumeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMultiVolume($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MultiVolume', '\DTA\MetadataBundle\Model\Data\MultiVolumeQuery');
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
    public function filterByVolume($volume, $comparison = null)
    {
        if ($volume instanceof Volume) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $volume->getPublicationId(), $comparison);
        } elseif ($volume instanceof PropelObjectCollection) {
            return $this
                ->useVolumeQuery()
                ->filterByPrimaryKeys($volume->getPrimaryKeys())
                ->endUse();
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
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinVolume($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return   \DTA\MetadataBundle\Model\Data\VolumeQuery A secondary query class using the current class as primary query
     */
    public function useVolumeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinVolume($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Volume', '\DTA\MetadataBundle\Model\Data\VolumeQuery');
    }

    /**
     * Filter the query by a related Chapter object
     *
     * @param   Chapter|PropelObjectCollection $chapter  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByChapter($chapter, $comparison = null)
    {
        if ($chapter instanceof Chapter) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $chapter->getPublicationId(), $comparison);
        } elseif ($chapter instanceof PropelObjectCollection) {
            return $this
                ->useChapterQuery()
                ->filterByPrimaryKeys($chapter->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByChapter() only accepts arguments of type Chapter or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Chapter relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinChapter($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Chapter');

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
            $this->addJoinObject($join, 'Chapter');
        }

        return $this;
    }

    /**
     * Use the Chapter relation Chapter object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\ChapterQuery A secondary query class using the current class as primary query
     */
    public function useChapterQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinChapter($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Chapter', '\DTA\MetadataBundle\Model\Data\ChapterQuery');
    }

    /**
     * Filter the query by a related Article object
     *
     * @param   Article|PropelObjectCollection $article  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByArticle($article, $comparison = null)
    {
        if ($article instanceof Article) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $article->getPublicationId(), $comparison);
        } elseif ($article instanceof PropelObjectCollection) {
            return $this
                ->useArticleQuery()
                ->filterByPrimaryKeys($article->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByArticle() only accepts arguments of type Article or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Article relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinArticle($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Article');

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
            $this->addJoinObject($join, 'Article');
        }

        return $this;
    }

    /**
     * Use the Article relation Article object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\ArticleQuery A secondary query class using the current class as primary query
     */
    public function useArticleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinArticle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Article', '\DTA\MetadataBundle\Model\Data\ArticleQuery');
    }

    /**
     * Filter the query by a related SequenceEntry object
     *
     * @param   SequenceEntry|PropelObjectCollection $sequenceEntry  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PublicationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySequenceEntry($sequenceEntry, $comparison = null)
    {
        if ($sequenceEntry instanceof SequenceEntry) {
            return $this
                ->addUsingAlias(PublicationPeer::ID, $sequenceEntry->getPublicationId(), $comparison);
        } elseif ($sequenceEntry instanceof PropelObjectCollection) {
            return $this
                ->useSequenceEntryQuery()
                ->filterByPrimaryKeys($sequenceEntry->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySequenceEntry() only accepts arguments of type SequenceEntry or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SequenceEntry relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PublicationQuery The current query, for fluid interface
     */
    public function joinSequenceEntry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SequenceEntry');

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
            $this->addJoinObject($join, 'SequenceEntry');
        }

        return $this;
    }

    /**
     * Use the SequenceEntry relation SequenceEntry object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Master\SequenceEntryQuery A secondary query class using the current class as primary query
     */
    public function useSequenceEntryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSequenceEntry($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SequenceEntry', '\DTA\MetadataBundle\Model\Master\SequenceEntryQuery');
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

    // nested_set behavior

    /**
     * Filter the query to restrict the result to root objects
     *
     * @return    PublicationQuery The current query, for fluid interface
     */
    public function treeRoots()
    {
        return $this->addUsingAlias(PublicationPeer::LEFT_COL, 1, Criteria::EQUAL);
    }

    /**
     * Returns the objects in a certain tree, from the tree scope
     *
     * @param     int $scope		Scope to determine which objects node to return
     *
     * @return    PublicationQuery The current query, for fluid interface
     */
    public function inTree($scope = null)
    {
        return $this->addUsingAlias(PublicationPeer::SCOPE_COL, $scope, Criteria::EQUAL);
    }

    /**
     * Filter the query to restrict the result to descendants of an object
     *
     * @param     Publication $publication The object to use for descendant search
     *
     * @return    PublicationQuery The current query, for fluid interface
     */
    public function descendantsOf($publication)
    {
        return $this
            ->inTree($publication->getScopeValue())
            ->addUsingAlias(PublicationPeer::LEFT_COL, $publication->getLeftValue(), Criteria::GREATER_THAN)
            ->addUsingAlias(PublicationPeer::LEFT_COL, $publication->getRightValue(), Criteria::LESS_THAN);
    }

    /**
     * Filter the query to restrict the result to the branch of an object.
     * Same as descendantsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     Publication $publication The object to use for branch search
     *
     * @return    PublicationQuery The current query, for fluid interface
     */
    public function branchOf($publication)
    {
        return $this
            ->inTree($publication->getScopeValue())
            ->addUsingAlias(PublicationPeer::LEFT_COL, $publication->getLeftValue(), Criteria::GREATER_EQUAL)
            ->addUsingAlias(PublicationPeer::LEFT_COL, $publication->getRightValue(), Criteria::LESS_EQUAL);
    }

    /**
     * Filter the query to restrict the result to children of an object
     *
     * @param     Publication $publication The object to use for child search
     *
     * @return    PublicationQuery The current query, for fluid interface
     */
    public function childrenOf($publication)
    {
        return $this
            ->descendantsOf($publication)
            ->addUsingAlias(PublicationPeer::LEVEL_COL, $publication->getLevel() + 1, Criteria::EQUAL);
    }

    /**
     * Filter the query to restrict the result to siblings of an object.
     * The result does not include the object passed as parameter.
     *
     * @param     Publication $publication The object to use for sibling search
     * @param      PropelPDO $con Connection to use.
     *
     * @return    PublicationQuery The current query, for fluid interface
     */
    public function siblingsOf($publication, PropelPDO $con = null)
    {
        if ($publication->isRoot()) {
            return $this->
                add(PublicationPeer::LEVEL_COL, '1<>1', Criteria::CUSTOM);
        } else {
            return $this
                ->childrenOf($publication->getParent($con))
                ->prune($publication);
        }
    }

    /**
     * Filter the query to restrict the result to ancestors of an object
     *
     * @param     Publication $publication The object to use for ancestors search
     *
     * @return    PublicationQuery The current query, for fluid interface
     */
    public function ancestorsOf($publication)
    {
        return $this
            ->inTree($publication->getScopeValue())
            ->addUsingAlias(PublicationPeer::LEFT_COL, $publication->getLeftValue(), Criteria::LESS_THAN)
            ->addUsingAlias(PublicationPeer::RIGHT_COL, $publication->getRightValue(), Criteria::GREATER_THAN);
    }

    /**
     * Filter the query to restrict the result to roots of an object.
     * Same as ancestorsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     Publication $publication The object to use for roots search
     *
     * @return    PublicationQuery The current query, for fluid interface
     */
    public function rootsOf($publication)
    {
        return $this
            ->inTree($publication->getScopeValue())
            ->addUsingAlias(PublicationPeer::LEFT_COL, $publication->getLeftValue(), Criteria::LESS_EQUAL)
            ->addUsingAlias(PublicationPeer::RIGHT_COL, $publication->getRightValue(), Criteria::GREATER_EQUAL);
    }

    /**
     * Order the result by branch, i.e. natural tree order
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    PublicationQuery The current query, for fluid interface
     */
    public function orderByBranch($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addDescendingOrderByColumn(PublicationPeer::LEFT_COL);
        } else {
            return $this
                ->addAscendingOrderByColumn(PublicationPeer::LEFT_COL);
        }
    }

    /**
     * Order the result by level, the closer to the root first
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    PublicationQuery The current query, for fluid interface
     */
    public function orderByLevel($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addAscendingOrderByColumn(PublicationPeer::RIGHT_COL);
        } else {
            return $this
                ->addDescendingOrderByColumn(PublicationPeer::RIGHT_COL);
        }
    }

    /**
     * Returns a root node for the tree
     *
     * @param      int $scope		Scope to determine which root node to return
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     Publication The tree root object
     */
    public function findRoot($scope = null, $con = null)
    {
        return $this
            ->addUsingAlias(PublicationPeer::LEFT_COL, 1, Criteria::EQUAL)
            ->inTree($scope)
            ->findOne($con);
    }

    /**
     * Returns the root objects for all trees.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return    mixed the list of results, formatted by the current formatter
     */
    public function findRoots($con = null)
    {
        return $this
            ->treeRoots()
            ->find($con);
    }

    /**
     * Returns a tree of objects
     *
     * @param      int $scope		Scope to determine which tree node to return
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findTree($scope = null, $con = null)
    {
        return $this
            ->inTree($scope)
            ->orderByBranch()
            ->find($con);
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
