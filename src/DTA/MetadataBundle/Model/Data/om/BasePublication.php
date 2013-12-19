<?php

namespace DTA\MetadataBundle\Model\Data\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \NestedSetRecursiveIterator;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use DTA\MetadataBundle\Model\Classification\Category;
use DTA\MetadataBundle\Model\Classification\CategoryQuery;
use DTA\MetadataBundle\Model\Classification\Genre;
use DTA\MetadataBundle\Model\Classification\GenreQuery;
use DTA\MetadataBundle\Model\Classification\Source;
use DTA\MetadataBundle\Model\Classification\SourceQuery;
use DTA\MetadataBundle\Model\Classification\Tag;
use DTA\MetadataBundle\Model\Classification\TagQuery;
use DTA\MetadataBundle\Model\Data\Article;
use DTA\MetadataBundle\Model\Data\ArticleQuery;
use DTA\MetadataBundle\Model\Data\Chapter;
use DTA\MetadataBundle\Model\Data\ChapterQuery;
use DTA\MetadataBundle\Model\Data\Datespecification;
use DTA\MetadataBundle\Model\Data\DatespecificationQuery;
use DTA\MetadataBundle\Model\Data\Font;
use DTA\MetadataBundle\Model\Data\FontQuery;
use DTA\MetadataBundle\Model\Data\Language;
use DTA\MetadataBundle\Model\Data\LanguageQuery;
use DTA\MetadataBundle\Model\Data\MultiVolume;
use DTA\MetadataBundle\Model\Data\MultiVolumeQuery;
use DTA\MetadataBundle\Model\Data\Place;
use DTA\MetadataBundle\Model\Data\PlaceQuery;
use DTA\MetadataBundle\Model\Data\Publication;
use DTA\MetadataBundle\Model\Data\PublicationPeer;
use DTA\MetadataBundle\Model\Data\PublicationQuery;
use DTA\MetadataBundle\Model\Data\Publishingcompany;
use DTA\MetadataBundle\Model\Data\PublishingcompanyQuery;
use DTA\MetadataBundle\Model\Data\Title;
use DTA\MetadataBundle\Model\Data\TitleQuery;
use DTA\MetadataBundle\Model\Data\Volume;
use DTA\MetadataBundle\Model\Data\VolumeQuery;
use DTA\MetadataBundle\Model\Master\CategoryPublication;
use DTA\MetadataBundle\Model\Master\CategoryPublicationQuery;
use DTA\MetadataBundle\Model\Master\DtaUser;
use DTA\MetadataBundle\Model\Master\DtaUserQuery;
use DTA\MetadataBundle\Model\Master\FontPublication;
use DTA\MetadataBundle\Model\Master\FontPublicationQuery;
use DTA\MetadataBundle\Model\Master\GenrePublication;
use DTA\MetadataBundle\Model\Master\GenrePublicationQuery;
use DTA\MetadataBundle\Model\Master\LanguagePublication;
use DTA\MetadataBundle\Model\Master\LanguagePublicationQuery;
use DTA\MetadataBundle\Model\Master\PersonPublication;
use DTA\MetadataBundle\Model\Master\PersonPublicationQuery;
use DTA\MetadataBundle\Model\Master\PublicationPublicationgroup;
use DTA\MetadataBundle\Model\Master\PublicationPublicationgroupQuery;
use DTA\MetadataBundle\Model\Master\PublicationTag;
use DTA\MetadataBundle\Model\Master\PublicationTagQuery;
use DTA\MetadataBundle\Model\Master\RecentUse;
use DTA\MetadataBundle\Model\Master\RecentUseQuery;
use DTA\MetadataBundle\Model\Master\SequenceEntry;
use DTA\MetadataBundle\Model\Master\SequenceEntryQuery;
use DTA\MetadataBundle\Model\Workflow\CopyLocation;
use DTA\MetadataBundle\Model\Workflow\CopyLocationQuery;
use DTA\MetadataBundle\Model\Workflow\Imagesource;
use DTA\MetadataBundle\Model\Workflow\ImagesourceQuery;
use DTA\MetadataBundle\Model\Workflow\Publicationgroup;
use DTA\MetadataBundle\Model\Workflow\PublicationgroupQuery;
use DTA\MetadataBundle\Model\Workflow\Task;
use DTA\MetadataBundle\Model\Workflow\TaskQuery;
use DTA\MetadataBundle\Model\Workflow\Textsource;
use DTA\MetadataBundle\Model\Workflow\TextsourceQuery;

abstract class BasePublication extends BaseObject implements Persistent, \DTA\MetadataBundle\Model\reconstructed_flaggable\ReconstructedFlaggableInterface, \DTA\MetadataBundle\Model\table_row_view\TableRowViewInterface
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\Data\\PublicationPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PublicationPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the type field.
     * @var        int
     */
    protected $type;

    /**
     * The value for the legacytype field.
     * @var        string
     */
    protected $legacytype;

    /**
     * The value for the title_id field.
     * @var        int
     */
    protected $title_id;

    /**
     * The value for the firsteditionpublication_id field.
     * @var        int
     */
    protected $firsteditionpublication_id;

    /**
     * The value for the place_id field.
     * @var        int
     */
    protected $place_id;

    /**
     * The value for the publicationdate_id field.
     * @var        int
     */
    protected $publicationdate_id;

    /**
     * The value for the creationdate_id field.
     * @var        int
     */
    protected $creationdate_id;

    /**
     * The value for the publishingcompany_id field.
     * @var        int
     */
    protected $publishingcompany_id;

    /**
     * The value for the source_id field.
     * @var        int
     */
    protected $source_id;

    /**
     * The value for the legacygenre field.
     * @var        string
     */
    protected $legacygenre;

    /**
     * The value for the legacysubgenre field.
     * @var        string
     */
    protected $legacysubgenre;

    /**
     * The value for the dirname field.
     * @var        string
     */
    protected $dirname;

    /**
     * The value for the usedcopylocation_id field.
     * @var        int
     */
    protected $usedcopylocation_id;

    /**
     * The value for the partner_id field.
     * @var        int
     */
    protected $partner_id;

    /**
     * The value for the editiondescription field.
     * @var        string
     */
    protected $editiondescription;

    /**
     * The value for the digitaleditioneditor field.
     * @var        string
     */
    protected $digitaleditioneditor;

    /**
     * The value for the transcriptioncomment field.
     * @var        string
     */
    protected $transcriptioncomment;

    /**
     * The value for the numpages field.
     * @var        string
     */
    protected $numpages;

    /**
     * The value for the numpagesnumeric field.
     * @var        int
     */
    protected $numpagesnumeric;

    /**
     * The value for the comment field.
     * @var        string
     */
    protected $comment;

    /**
     * The value for the encoding_comment field.
     * @var        string
     */
    protected $encoding_comment;

    /**
     * The value for the doi field.
     * @var        string
     */
    protected $doi;

    /**
     * The value for the format field.
     * @var        string
     */
    protected $format;

    /**
     * The value for the directoryname field.
     * @var        string
     */
    protected $directoryname;

    /**
     * The value for the wwwready field.
     * @var        int
     */
    protected $wwwready;

    /**
     * The value for the last_changed_by_user_id field.
     * @var        int
     */
    protected $last_changed_by_user_id;

    /**
     * The value for the tree_id field.
     * @var        int
     */
    protected $tree_id;

    /**
     * The value for the tree_left field.
     * @var        int
     */
    protected $tree_left;

    /**
     * The value for the tree_right field.
     * @var        int
     */
    protected $tree_right;

    /**
     * The value for the tree_level field.
     * @var        int
     */
    protected $tree_level;

    /**
     * The value for the publishingcompany_id_is_reconstructed field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $publishingcompany_id_is_reconstructed;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        Title
     */
    protected $aTitle;

    /**
     * @var        Source
     */
    protected $aSource;

    /**
     * @var        Publishingcompany
     */
    protected $aPublishingcompany;

    /**
     * @var        Place
     */
    protected $aPlace;

    /**
     * @var        Datespecification
     */
    protected $aDatespecificationRelatedByPublicationdateId;

    /**
     * @var        Datespecification
     */
    protected $aDatespecificationRelatedByCreationdateId;

    /**
     * @var        DtaUser
     */
    protected $aLastChangedByUser;

    /**
     * @var        PropelObjectCollection|MultiVolume[] Collection to store aggregation of MultiVolume objects.
     */
    protected $collMultiVolumes;
    protected $collMultiVolumesPartial;

    /**
     * @var        PropelObjectCollection|Volume[] Collection to store aggregation of Volume objects.
     */
    protected $collVolumes;
    protected $collVolumesPartial;

    /**
     * @var        PropelObjectCollection|Chapter[] Collection to store aggregation of Chapter objects.
     */
    protected $collChapters;
    protected $collChaptersPartial;

    /**
     * @var        PropelObjectCollection|Article[] Collection to store aggregation of Article objects.
     */
    protected $collArticles;
    protected $collArticlesPartial;

    /**
     * @var        PropelObjectCollection|SequenceEntry[] Collection to store aggregation of SequenceEntry objects.
     */
    protected $collSequenceEntries;
    protected $collSequenceEntriesPartial;

    /**
     * @var        PropelObjectCollection|LanguagePublication[] Collection to store aggregation of LanguagePublication objects.
     */
    protected $collLanguagePublications;
    protected $collLanguagePublicationsPartial;

    /**
     * @var        PropelObjectCollection|GenrePublication[] Collection to store aggregation of GenrePublication objects.
     */
    protected $collGenrePublications;
    protected $collGenrePublicationsPartial;

    /**
     * @var        PropelObjectCollection|PublicationTag[] Collection to store aggregation of PublicationTag objects.
     */
    protected $collPublicationTags;
    protected $collPublicationTagsPartial;

    /**
     * @var        PropelObjectCollection|CategoryPublication[] Collection to store aggregation of CategoryPublication objects.
     */
    protected $collCategoryPublications;
    protected $collCategoryPublicationsPartial;

    /**
     * @var        PropelObjectCollection|FontPublication[] Collection to store aggregation of FontPublication objects.
     */
    protected $collFontPublications;
    protected $collFontPublicationsPartial;

    /**
     * @var        PropelObjectCollection|PublicationPublicationgroup[] Collection to store aggregation of PublicationPublicationgroup objects.
     */
    protected $collPublicationPublicationgroups;
    protected $collPublicationPublicationgroupsPartial;

    /**
     * @var        PropelObjectCollection|PersonPublication[] Collection to store aggregation of PersonPublication objects.
     */
    protected $collPersonPublications;
    protected $collPersonPublicationsPartial;

    /**
     * @var        PropelObjectCollection|RecentUse[] Collection to store aggregation of RecentUse objects.
     */
    protected $collRecentUses;
    protected $collRecentUsesPartial;

    /**
     * @var        PropelObjectCollection|Task[] Collection to store aggregation of Task objects.
     */
    protected $collTasks;
    protected $collTasksPartial;

    /**
     * @var        PropelObjectCollection|CopyLocation[] Collection to store aggregation of CopyLocation objects.
     */
    protected $collCopyLocations;
    protected $collCopyLocationsPartial;

    /**
     * @var        PropelObjectCollection|Imagesource[] Collection to store aggregation of Imagesource objects.
     */
    protected $collImagesources;
    protected $collImagesourcesPartial;

    /**
     * @var        PropelObjectCollection|Textsource[] Collection to store aggregation of Textsource objects.
     */
    protected $collTextsources;
    protected $collTextsourcesPartial;

    /**
     * @var        PropelObjectCollection|Language[] Collection to store aggregation of Language objects.
     */
    protected $collLanguages;

    /**
     * @var        PropelObjectCollection|Genre[] Collection to store aggregation of Genre objects.
     */
    protected $collGenres;

    /**
     * @var        PropelObjectCollection|Tag[] Collection to store aggregation of Tag objects.
     */
    protected $collTags;

    /**
     * @var        PropelObjectCollection|Category[] Collection to store aggregation of Category objects.
     */
    protected $collCategories;

    /**
     * @var        PropelObjectCollection|Font[] Collection to store aggregation of Font objects.
     */
    protected $collFonts;

    /**
     * @var        PropelObjectCollection|Publicationgroup[] Collection to store aggregation of Publicationgroup objects.
     */
    protected $collPublicationgroups;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    // nested_set behavior

    /**
     * Queries to be executed in the save transaction
     * @var        array
     */
    protected $nestedSetQueries = array();

    /**
     * Internal cache for children nodes
     * @var        null|PropelObjectCollection
     */
    protected $collNestedSetChildren = null;

    /**
     * Internal cache for parent node
     * @var        null|Publication
     */
    protected $aNestedSetParent = null;


    // table_row_view behavior
    public static $tableRowViewCaptions = array('Titel', 'erster Autor', 'veröffentlicht', 'Verlag', 'Typ', );	public   $tableRowViewAccessors = array('Titel'=>'accessor:getTitleString', 'erster Autor'=>'accessor:getFirstAuthor', 'veröffentlicht'=>'accessor:getDatespecificationRelatedByPublicationdateId', 'Verlag'=>'accessor:getPublishingCompany', 'Typ'=>'Type', );	public static $queryConstructionString = "\DTA\MetadataBundle\Model\Data\PublicationQuery::create()                     ->leftJoinWith('Title')                     ->leftJoinWith('Title.Titlefragment')                     ->leftJoinWith('DatespecificationRelatedByPublicationdateId')                     ->leftJoinWith('PersonPublication')                     ->leftJoinWith('PersonPublication.Person')                     ->leftJoinWith('Person.Personalname')                     ->leftJoinWith('Personalname.Namefragment');";
    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $languagesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $genresScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $tagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $categoriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $fontsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationgroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $multiVolumesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $volumesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $chaptersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $articlesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $sequenceEntriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $languagePublicationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $genrePublicationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationTagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $categoryPublicationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $fontPublicationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationPublicationgroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $personPublicationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $recentUsesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $tasksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $copyLocationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $imagesourcesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $textsourcesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->publishingcompany_id_is_reconstructed = false;
    }

    /**
     * Initializes internal state of BasePublication object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [type] column value.
     * Publikationstyp. Zur Auflösung des dynamischen Typs (ein Volume bettet ein Publication objekt ein, mit nichts als dem Publikationsobjekt in der Hand, lässt sich das zugehörige speziellere objekt aber nur durch ausprobieren aller objektarten herausfinden.)
     * @return int
     * @throws PropelException - if the stored enum key is unknown.
     */
    public function getType()
    {
        if (null === $this->type) {
            return null;
        }
        $valueSet = PublicationPeer::getValueSet(PublicationPeer::TYPE);
        if (!isset($valueSet[$this->type])) {
            throw new PropelException('Unknown stored enum key: ' . $this->type);
        }

        return $valueSet[$this->type];
    }

    /**
     * Get the [legacytype] column value.
     * Altes Publikationstypen-Kürzel (J, JA, M, MM, MMS, etc.)
     * @return string
     */
    public function getLegacytype()
    {

        return $this->legacytype;
    }

    /**
     * Get the [title_id] column value.
     *
     * @return int
     */
    public function getTitleId()
    {

        return $this->title_id;
    }

    /**
     * Get the [firsteditionpublication_id] column value.
     * Publikation, die die Informationen zur Erstauflage enthält
     * @return int
     */
    public function getFirsteditionpublicationId()
    {

        return $this->firsteditionpublication_id;
    }

    /**
     * Get the [place_id] column value.
     * Druckort
     * @return int
     */
    public function getPlaceId()
    {

        return $this->place_id;
    }

    /**
     * Get the [publicationdate_id] column value.
     * Erscheinungsjahr
     * @return int
     */
    public function getPublicationdateId()
    {

        return $this->publicationdate_id;
    }

    /**
     * Get the [creationdate_id] column value.
     * Erscheinungsjahr der Erstausgabe
     * @return int
     */
    public function getCreationdateId()
    {

        return $this->creationdate_id;
    }

    /**
     * Get the [publishingcompany_id] column value.
     * Verlag
     * @return int
     */
    public function getPublishingcompanyId()
    {

        return $this->publishingcompany_id;
    }

    /**
     * Get the [source_id] column value.
     * Zur Sicherheit aus der alten DB übernommen
     * @return int
     */
    public function getSourceId()
    {

        return $this->source_id;
    }

    /**
     * Get the [legacygenre] column value.
     * Alt-Angabe zum Genre, zur Weiterverarbeitung bei Umstellung auf das neue Genre-System.
     * @return string
     */
    public function getLegacygenre()
    {

        return $this->legacygenre;
    }

    /**
     * Get the [legacysubgenre] column value.
     * Alt-Angabe zum Untergenre.
     * @return string
     */
    public function getLegacysubgenre()
    {

        return $this->legacysubgenre;
    }

    /**
     * Get the [dirname] column value.
     * Textuelle ID (Kombination aus Autor, Titel, Jahr)
     * @return string
     */
    public function getDirname()
    {

        return $this->dirname;
    }

    /**
     * Get the [usedcopylocation_id] column value.
     * Vermutlich der eingesetzte Nachweis. Entspricht dem alten metadaten.id_nachweis.
     * @return int
     */
    public function getUsedcopylocationId()
    {

        return $this->usedcopylocation_id;
    }

    /**
     * Get the [partner_id] column value.
     * akquiriert über
     * @return int
     */
    public function getPartnerId()
    {

        return $this->partner_id;
    }

    /**
     * Get the [editiondescription] column value.
     * Art der Ausgabe
     * @return string
     */
    public function getEditiondescription()
    {

        return $this->editiondescription;
    }

    /**
     * Get the [digitaleditioneditor] column value.
     * Bearbeiter der digitalen Edition
     * @return string
     */
    public function getDigitaleditioneditor()
    {

        return $this->digitaleditioneditor;
    }

    /**
     * Get the [transcriptioncomment] column value.
     * Bemerkungen zu den Transkriptionsrichtlinien
     * @return string
     */
    public function getTranscriptioncomment()
    {

        return $this->transcriptioncomment;
    }

    /**
     * Get the [numpages] column value.
     * Anzahl Seiten (Umfang)
     * @return string
     */
    public function getNumpages()
    {

        return $this->numpages;
    }

    /**
     * Get the [numpagesnumeric] column value.
     * Umfang (normiert)
     * @return int
     */
    public function getNumpagesnumeric()
    {

        return $this->numpagesnumeric;
    }

    /**
     * Get the [comment] column value.
     * Anmerkungen
     * @return string
     */
    public function getComment()
    {

        return $this->comment;
    }

    /**
     * Get the [encoding_comment] column value.
     * Kommentar Encoding
     * @return string
     */
    public function getEncodingComment()
    {

        return $this->encoding_comment;
    }

    /**
     * Get the [doi] column value.
     *
     * @return string
     */
    public function getDoi()
    {

        return $this->doi;
    }

    /**
     * Get the [format] column value.
     *
     * @return string
     */
    public function getFormat()
    {

        return $this->format;
    }

    /**
     * Get the [directoryname] column value.
     *
     * @return string
     */
    public function getDirectoryname()
    {

        return $this->directoryname;
    }

    /**
     * Get the [wwwready] column value.
     *
     * @return int
     */
    public function getWwwready()
    {

        return $this->wwwready;
    }

    /**
     * Get the [last_changed_by_user_id] column value.
     *
     * @return int
     */
    public function getLastChangedByUserId()
    {

        return $this->last_changed_by_user_id;
    }

    /**
     * Get the [tree_id] column value.
     * Publikationen können vertikal organisiert werden (Teil/Ganzes). Die id dient zur Unterscheidung der einzelnen Bäume.
     * @return int
     */
    public function getTreeId()
    {

        return $this->tree_id;
    }

    /**
     * Get the [tree_left] column value.
     *
     * @return int
     */
    public function getTreeLeft()
    {

        return $this->tree_left;
    }

    /**
     * Get the [tree_right] column value.
     *
     * @return int
     */
    public function getTreeRight()
    {

        return $this->tree_right;
    }

    /**
     * Get the [tree_level] column value.
     *
     * @return int
     */
    public function getTreeLevel()
    {

        return $this->tree_level;
    }

    /**
     * Get the [publishingcompany_id_is_reconstructed] column value.
     *
     * @return boolean
     */
    public function getPublishingcompanyIdIsReconstructed()
    {

        return $this->publishingcompany_id_is_reconstructed;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }


        try {
            $dt = new DateTime($this->created_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($this->updated_at === null) {
            return null;
        }


        try {
            $dt = new DateTime($this->updated_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PublicationPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [type] column.
     * Publikationstyp. Zur Auflösung des dynamischen Typs (ein Volume bettet ein Publication objekt ein, mit nichts als dem Publikationsobjekt in der Hand, lässt sich das zugehörige speziellere objekt aber nur durch ausprobieren aller objektarten herausfinden.)
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setType($v)
    {
        if ($v !== null) {
            $valueSet = PublicationPeer::getValueSet(PublicationPeer::TYPE);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[] = PublicationPeer::TYPE;
        }


        return $this;
    } // setType()

    /**
     * Set the value of [legacytype] column.
     * Altes Publikationstypen-Kürzel (J, JA, M, MM, MMS, etc.)
     * @param  string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setLegacytype($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->legacytype !== $v) {
            $this->legacytype = $v;
            $this->modifiedColumns[] = PublicationPeer::LEGACYTYPE;
        }


        return $this;
    } // setLegacytype()

    /**
     * Set the value of [title_id] column.
     *
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setTitleId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->title_id !== $v) {
            $this->title_id = $v;
            $this->modifiedColumns[] = PublicationPeer::TITLE_ID;
        }

        if ($this->aTitle !== null && $this->aTitle->getId() !== $v) {
            $this->aTitle = null;
        }


        return $this;
    } // setTitleId()

    /**
     * Set the value of [firsteditionpublication_id] column.
     * Publikation, die die Informationen zur Erstauflage enthält
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setFirsteditionpublicationId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->firsteditionpublication_id !== $v) {
            $this->firsteditionpublication_id = $v;
            $this->modifiedColumns[] = PublicationPeer::FIRSTEDITIONPUBLICATION_ID;
        }


        return $this;
    } // setFirsteditionpublicationId()

    /**
     * Set the value of [place_id] column.
     * Druckort
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setPlaceId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->place_id !== $v) {
            $this->place_id = $v;
            $this->modifiedColumns[] = PublicationPeer::PLACE_ID;
        }

        if ($this->aPlace !== null && $this->aPlace->getId() !== $v) {
            $this->aPlace = null;
        }


        return $this;
    } // setPlaceId()

    /**
     * Set the value of [publicationdate_id] column.
     * Erscheinungsjahr
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationdateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->publicationdate_id !== $v) {
            $this->publicationdate_id = $v;
            $this->modifiedColumns[] = PublicationPeer::PUBLICATIONDATE_ID;
        }

        if ($this->aDatespecificationRelatedByPublicationdateId !== null && $this->aDatespecificationRelatedByPublicationdateId->getId() !== $v) {
            $this->aDatespecificationRelatedByPublicationdateId = null;
        }


        return $this;
    } // setPublicationdateId()

    /**
     * Set the value of [creationdate_id] column.
     * Erscheinungsjahr der Erstausgabe
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setCreationdateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->creationdate_id !== $v) {
            $this->creationdate_id = $v;
            $this->modifiedColumns[] = PublicationPeer::CREATIONDATE_ID;
        }

        if ($this->aDatespecificationRelatedByCreationdateId !== null && $this->aDatespecificationRelatedByCreationdateId->getId() !== $v) {
            $this->aDatespecificationRelatedByCreationdateId = null;
        }


        return $this;
    } // setCreationdateId()

    /**
     * Set the value of [publishingcompany_id] column.
     * Verlag
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setPublishingcompanyId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->publishingcompany_id !== $v) {
            $this->publishingcompany_id = $v;
            $this->modifiedColumns[] = PublicationPeer::PUBLISHINGCOMPANY_ID;
        }

        if ($this->aPublishingcompany !== null && $this->aPublishingcompany->getId() !== $v) {
            $this->aPublishingcompany = null;
        }


        return $this;
    } // setPublishingcompanyId()

    /**
     * Set the value of [source_id] column.
     * Zur Sicherheit aus der alten DB übernommen
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setSourceId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->source_id !== $v) {
            $this->source_id = $v;
            $this->modifiedColumns[] = PublicationPeer::SOURCE_ID;
        }

        if ($this->aSource !== null && $this->aSource->getId() !== $v) {
            $this->aSource = null;
        }


        return $this;
    } // setSourceId()

    /**
     * Set the value of [legacygenre] column.
     * Alt-Angabe zum Genre, zur Weiterverarbeitung bei Umstellung auf das neue Genre-System.
     * @param  string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setLegacygenre($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->legacygenre !== $v) {
            $this->legacygenre = $v;
            $this->modifiedColumns[] = PublicationPeer::LEGACYGENRE;
        }


        return $this;
    } // setLegacygenre()

    /**
     * Set the value of [legacysubgenre] column.
     * Alt-Angabe zum Untergenre.
     * @param  string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setLegacysubgenre($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->legacysubgenre !== $v) {
            $this->legacysubgenre = $v;
            $this->modifiedColumns[] = PublicationPeer::LEGACYSUBGENRE;
        }


        return $this;
    } // setLegacysubgenre()

    /**
     * Set the value of [dirname] column.
     * Textuelle ID (Kombination aus Autor, Titel, Jahr)
     * @param  string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setDirname($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->dirname !== $v) {
            $this->dirname = $v;
            $this->modifiedColumns[] = PublicationPeer::DIRNAME;
        }


        return $this;
    } // setDirname()

    /**
     * Set the value of [usedcopylocation_id] column.
     * Vermutlich der eingesetzte Nachweis. Entspricht dem alten metadaten.id_nachweis.
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setUsedcopylocationId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->usedcopylocation_id !== $v) {
            $this->usedcopylocation_id = $v;
            $this->modifiedColumns[] = PublicationPeer::USEDCOPYLOCATION_ID;
        }


        return $this;
    } // setUsedcopylocationId()

    /**
     * Set the value of [partner_id] column.
     * akquiriert über
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setPartnerId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->partner_id !== $v) {
            $this->partner_id = $v;
            $this->modifiedColumns[] = PublicationPeer::PARTNER_ID;
        }


        return $this;
    } // setPartnerId()

    /**
     * Set the value of [editiondescription] column.
     * Art der Ausgabe
     * @param  string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setEditiondescription($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->editiondescription !== $v) {
            $this->editiondescription = $v;
            $this->modifiedColumns[] = PublicationPeer::EDITIONDESCRIPTION;
        }


        return $this;
    } // setEditiondescription()

    /**
     * Set the value of [digitaleditioneditor] column.
     * Bearbeiter der digitalen Edition
     * @param  string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setDigitaleditioneditor($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->digitaleditioneditor !== $v) {
            $this->digitaleditioneditor = $v;
            $this->modifiedColumns[] = PublicationPeer::DIGITALEDITIONEDITOR;
        }


        return $this;
    } // setDigitaleditioneditor()

    /**
     * Set the value of [transcriptioncomment] column.
     * Bemerkungen zu den Transkriptionsrichtlinien
     * @param  string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setTranscriptioncomment($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->transcriptioncomment !== $v) {
            $this->transcriptioncomment = $v;
            $this->modifiedColumns[] = PublicationPeer::TRANSCRIPTIONCOMMENT;
        }


        return $this;
    } // setTranscriptioncomment()

    /**
     * Set the value of [numpages] column.
     * Anzahl Seiten (Umfang)
     * @param  string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setNumpages($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->numpages !== $v) {
            $this->numpages = $v;
            $this->modifiedColumns[] = PublicationPeer::NUMPAGES;
        }


        return $this;
    } // setNumpages()

    /**
     * Set the value of [numpagesnumeric] column.
     * Umfang (normiert)
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setNumpagesnumeric($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->numpagesnumeric !== $v) {
            $this->numpagesnumeric = $v;
            $this->modifiedColumns[] = PublicationPeer::NUMPAGESNUMERIC;
        }


        return $this;
    } // setNumpagesnumeric()

    /**
     * Set the value of [comment] column.
     * Anmerkungen
     * @param  string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setComment($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->comment !== $v) {
            $this->comment = $v;
            $this->modifiedColumns[] = PublicationPeer::COMMENT;
        }


        return $this;
    } // setComment()

    /**
     * Set the value of [encoding_comment] column.
     * Kommentar Encoding
     * @param  string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setEncodingComment($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->encoding_comment !== $v) {
            $this->encoding_comment = $v;
            $this->modifiedColumns[] = PublicationPeer::ENCODING_COMMENT;
        }


        return $this;
    } // setEncodingComment()

    /**
     * Set the value of [doi] column.
     *
     * @param  string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setDoi($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->doi !== $v) {
            $this->doi = $v;
            $this->modifiedColumns[] = PublicationPeer::DOI;
        }


        return $this;
    } // setDoi()

    /**
     * Set the value of [format] column.
     *
     * @param  string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setFormat($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->format !== $v) {
            $this->format = $v;
            $this->modifiedColumns[] = PublicationPeer::FORMAT;
        }


        return $this;
    } // setFormat()

    /**
     * Set the value of [directoryname] column.
     *
     * @param  string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setDirectoryname($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->directoryname !== $v) {
            $this->directoryname = $v;
            $this->modifiedColumns[] = PublicationPeer::DIRECTORYNAME;
        }


        return $this;
    } // setDirectoryname()

    /**
     * Set the value of [wwwready] column.
     *
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setWwwready($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->wwwready !== $v) {
            $this->wwwready = $v;
            $this->modifiedColumns[] = PublicationPeer::WWWREADY;
        }


        return $this;
    } // setWwwready()

    /**
     * Set the value of [last_changed_by_user_id] column.
     *
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setLastChangedByUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->last_changed_by_user_id !== $v) {
            $this->last_changed_by_user_id = $v;
            $this->modifiedColumns[] = PublicationPeer::LAST_CHANGED_BY_USER_ID;
        }

        if ($this->aLastChangedByUser !== null && $this->aLastChangedByUser->getId() !== $v) {
            $this->aLastChangedByUser = null;
        }


        return $this;
    } // setLastChangedByUserId()

    /**
     * Set the value of [tree_id] column.
     * Publikationen können vertikal organisiert werden (Teil/Ganzes). Die id dient zur Unterscheidung der einzelnen Bäume.
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setTreeId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_id !== $v) {
            $this->tree_id = $v;
            $this->modifiedColumns[] = PublicationPeer::TREE_ID;
        }


        return $this;
    } // setTreeId()

    /**
     * Set the value of [tree_left] column.
     *
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setTreeLeft($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_left !== $v) {
            $this->tree_left = $v;
            $this->modifiedColumns[] = PublicationPeer::TREE_LEFT;
        }


        return $this;
    } // setTreeLeft()

    /**
     * Set the value of [tree_right] column.
     *
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setTreeRight($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_right !== $v) {
            $this->tree_right = $v;
            $this->modifiedColumns[] = PublicationPeer::TREE_RIGHT;
        }


        return $this;
    } // setTreeRight()

    /**
     * Set the value of [tree_level] column.
     *
     * @param  int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setTreeLevel($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_level !== $v) {
            $this->tree_level = $v;
            $this->modifiedColumns[] = PublicationPeer::TREE_LEVEL;
        }


        return $this;
    } // setTreeLevel()

    /**
     * Sets the value of the [publishingcompany_id_is_reconstructed] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Publication The current object (for fluent API support)
     */
    public function setPublishingcompanyIdIsReconstructed($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->publishingcompany_id_is_reconstructed !== $v) {
            $this->publishingcompany_id_is_reconstructed = $v;
            $this->modifiedColumns[] = PublicationPeer::PUBLISHINGCOMPANY_ID_IS_RECONSTRUCTED;
        }


        return $this;
    } // setPublishingcompanyIdIsReconstructed()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Publication The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PublicationPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Publication The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PublicationPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->publishingcompany_id_is_reconstructed !== false) {
                return false;
            }

        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->type = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->legacytype = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->title_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->firsteditionpublication_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->place_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->publicationdate_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->creationdate_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->publishingcompany_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->source_id = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->legacygenre = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->legacysubgenre = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->dirname = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->usedcopylocation_id = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
            $this->partner_id = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
            $this->editiondescription = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->digitaleditioneditor = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
            $this->transcriptioncomment = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->numpages = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
            $this->numpagesnumeric = ($row[$startcol + 19] !== null) ? (int) $row[$startcol + 19] : null;
            $this->comment = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
            $this->encoding_comment = ($row[$startcol + 21] !== null) ? (string) $row[$startcol + 21] : null;
            $this->doi = ($row[$startcol + 22] !== null) ? (string) $row[$startcol + 22] : null;
            $this->format = ($row[$startcol + 23] !== null) ? (string) $row[$startcol + 23] : null;
            $this->directoryname = ($row[$startcol + 24] !== null) ? (string) $row[$startcol + 24] : null;
            $this->wwwready = ($row[$startcol + 25] !== null) ? (int) $row[$startcol + 25] : null;
            $this->last_changed_by_user_id = ($row[$startcol + 26] !== null) ? (int) $row[$startcol + 26] : null;
            $this->tree_id = ($row[$startcol + 27] !== null) ? (int) $row[$startcol + 27] : null;
            $this->tree_left = ($row[$startcol + 28] !== null) ? (int) $row[$startcol + 28] : null;
            $this->tree_right = ($row[$startcol + 29] !== null) ? (int) $row[$startcol + 29] : null;
            $this->tree_level = ($row[$startcol + 30] !== null) ? (int) $row[$startcol + 30] : null;
            $this->publishingcompany_id_is_reconstructed = ($row[$startcol + 31] !== null) ? (boolean) $row[$startcol + 31] : null;
            $this->created_at = ($row[$startcol + 32] !== null) ? (string) $row[$startcol + 32] : null;
            $this->updated_at = ($row[$startcol + 33] !== null) ? (string) $row[$startcol + 33] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 34; // 34 = PublicationPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Publication object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aTitle !== null && $this->title_id !== $this->aTitle->getId()) {
            $this->aTitle = null;
        }
        if ($this->aPlace !== null && $this->place_id !== $this->aPlace->getId()) {
            $this->aPlace = null;
        }
        if ($this->aDatespecificationRelatedByPublicationdateId !== null && $this->publicationdate_id !== $this->aDatespecificationRelatedByPublicationdateId->getId()) {
            $this->aDatespecificationRelatedByPublicationdateId = null;
        }
        if ($this->aDatespecificationRelatedByCreationdateId !== null && $this->creationdate_id !== $this->aDatespecificationRelatedByCreationdateId->getId()) {
            $this->aDatespecificationRelatedByCreationdateId = null;
        }
        if ($this->aPublishingcompany !== null && $this->publishingcompany_id !== $this->aPublishingcompany->getId()) {
            $this->aPublishingcompany = null;
        }
        if ($this->aSource !== null && $this->source_id !== $this->aSource->getId()) {
            $this->aSource = null;
        }
        if ($this->aLastChangedByUser !== null && $this->last_changed_by_user_id !== $this->aLastChangedByUser->getId()) {
            $this->aLastChangedByUser = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PublicationPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aTitle = null;
            $this->aSource = null;
            $this->aPublishingcompany = null;
            $this->aPlace = null;
            $this->aDatespecificationRelatedByPublicationdateId = null;
            $this->aDatespecificationRelatedByCreationdateId = null;
            $this->aLastChangedByUser = null;
            $this->collMultiVolumes = null;

            $this->collVolumes = null;

            $this->collChapters = null;

            $this->collArticles = null;

            $this->collSequenceEntries = null;

            $this->collLanguagePublications = null;

            $this->collGenrePublications = null;

            $this->collPublicationTags = null;

            $this->collCategoryPublications = null;

            $this->collFontPublications = null;

            $this->collPublicationPublicationgroups = null;

            $this->collPersonPublications = null;

            $this->collRecentUses = null;

            $this->collTasks = null;

            $this->collCopyLocations = null;

            $this->collImagesources = null;

            $this->collTextsources = null;

            $this->collLanguages = null;
            $this->collGenres = null;
            $this->collTags = null;
            $this->collCategories = null;
            $this->collFonts = null;
            $this->collPublicationgroups = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PublicationQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // nested_set behavior
            if ($this->isRoot()) {
                throw new PropelException('Deletion of a root node is disabled for nested sets. Use PublicationPeer::deleteTree($scope) instead to delete an entire tree');
            }

            if ($this->isInTree()) {
                $this->deleteDescendants($con);
            }

            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                // nested_set behavior
                if ($this->isInTree()) {
                    // fill up the room that was used by the node
                    PublicationPeer::shiftRLValues(-2, $this->getRightValue() + 1, null, $this->getScopeValue(), $con);
                }

                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // nested_set behavior
            if ($this->isNew() && $this->isRoot()) {
                // check if no other root exist in, the tree
                $nbRoots = PublicationQuery::create()
                    ->addUsingAlias(PublicationPeer::LEFT_COL, 1, Criteria::EQUAL)
                    ->addUsingAlias(PublicationPeer::SCOPE_COL, $this->getScopeValue(), Criteria::EQUAL)
                    ->count($con);
                if ($nbRoots > 0) {
                        throw new PropelException(sprintf('A root node already exists in this tree with scope "%s".', $this->getScopeValue()));
                }
            }
            $this->processNestedSetQueries($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PublicationPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PublicationPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PublicationPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                PublicationPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aTitle !== null) {
                if ($this->aTitle->isModified() || $this->aTitle->isNew()) {
                    $affectedRows += $this->aTitle->save($con);
                }
                $this->setTitle($this->aTitle);
            }

            if ($this->aSource !== null) {
                if ($this->aSource->isModified() || $this->aSource->isNew()) {
                    $affectedRows += $this->aSource->save($con);
                }
                $this->setSource($this->aSource);
            }

            if ($this->aPublishingcompany !== null) {
                if ($this->aPublishingcompany->isModified() || $this->aPublishingcompany->isNew()) {
                    $affectedRows += $this->aPublishingcompany->save($con);
                }
                $this->setPublishingcompany($this->aPublishingcompany);
            }

            if ($this->aPlace !== null) {
                if ($this->aPlace->isModified() || $this->aPlace->isNew()) {
                    $affectedRows += $this->aPlace->save($con);
                }
                $this->setPlace($this->aPlace);
            }

            if ($this->aDatespecificationRelatedByPublicationdateId !== null) {
                if ($this->aDatespecificationRelatedByPublicationdateId->isModified() || $this->aDatespecificationRelatedByPublicationdateId->isNew()) {
                    $affectedRows += $this->aDatespecificationRelatedByPublicationdateId->save($con);
                }
                $this->setDatespecificationRelatedByPublicationdateId($this->aDatespecificationRelatedByPublicationdateId);
            }

            if ($this->aDatespecificationRelatedByCreationdateId !== null) {
                if ($this->aDatespecificationRelatedByCreationdateId->isModified() || $this->aDatespecificationRelatedByCreationdateId->isNew()) {
                    $affectedRows += $this->aDatespecificationRelatedByCreationdateId->save($con);
                }
                $this->setDatespecificationRelatedByCreationdateId($this->aDatespecificationRelatedByCreationdateId);
            }

            if ($this->aLastChangedByUser !== null) {
                if ($this->aLastChangedByUser->isModified() || $this->aLastChangedByUser->isNew()) {
                    $affectedRows += $this->aLastChangedByUser->save($con);
                }
                $this->setLastChangedByUser($this->aLastChangedByUser);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->languagesScheduledForDeletion !== null) {
                if (!$this->languagesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->languagesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    LanguagePublicationQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->languagesScheduledForDeletion = null;
                }

                foreach ($this->getLanguages() as $language) {
                    if ($language->isModified()) {
                        $language->save($con);
                    }
                }
            } elseif ($this->collLanguages) {
                foreach ($this->collLanguages as $language) {
                    if ($language->isModified()) {
                        $language->save($con);
                    }
                }
            }

            if ($this->genresScheduledForDeletion !== null) {
                if (!$this->genresScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->genresScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    GenrePublicationQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->genresScheduledForDeletion = null;
                }

                foreach ($this->getGenres() as $genre) {
                    if ($genre->isModified()) {
                        $genre->save($con);
                    }
                }
            } elseif ($this->collGenres) {
                foreach ($this->collGenres as $genre) {
                    if ($genre->isModified()) {
                        $genre->save($con);
                    }
                }
            }

            if ($this->tagsScheduledForDeletion !== null) {
                if (!$this->tagsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->tagsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PublicationTagQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->tagsScheduledForDeletion = null;
                }

                foreach ($this->getTags() as $tag) {
                    if ($tag->isModified()) {
                        $tag->save($con);
                    }
                }
            } elseif ($this->collTags) {
                foreach ($this->collTags as $tag) {
                    if ($tag->isModified()) {
                        $tag->save($con);
                    }
                }
            }

            if ($this->categoriesScheduledForDeletion !== null) {
                if (!$this->categoriesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->categoriesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    CategoryPublicationQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->categoriesScheduledForDeletion = null;
                }

                foreach ($this->getCategories() as $category) {
                    if ($category->isModified()) {
                        $category->save($con);
                    }
                }
            } elseif ($this->collCategories) {
                foreach ($this->collCategories as $category) {
                    if ($category->isModified()) {
                        $category->save($con);
                    }
                }
            }

            if ($this->fontsScheduledForDeletion !== null) {
                if (!$this->fontsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->fontsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    FontPublicationQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->fontsScheduledForDeletion = null;
                }

                foreach ($this->getFonts() as $font) {
                    if ($font->isModified()) {
                        $font->save($con);
                    }
                }
            } elseif ($this->collFonts) {
                foreach ($this->collFonts as $font) {
                    if ($font->isModified()) {
                        $font->save($con);
                    }
                }
            }

            if ($this->publicationgroupsScheduledForDeletion !== null) {
                if (!$this->publicationgroupsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->publicationgroupsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PublicationPublicationgroupQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->publicationgroupsScheduledForDeletion = null;
                }

                foreach ($this->getPublicationgroups() as $publicationgroup) {
                    if ($publicationgroup->isModified()) {
                        $publicationgroup->save($con);
                    }
                }
            } elseif ($this->collPublicationgroups) {
                foreach ($this->collPublicationgroups as $publicationgroup) {
                    if ($publicationgroup->isModified()) {
                        $publicationgroup->save($con);
                    }
                }
            }

            if ($this->multiVolumesScheduledForDeletion !== null) {
                if (!$this->multiVolumesScheduledForDeletion->isEmpty()) {
                    MultiVolumeQuery::create()
                        ->filterByPrimaryKeys($this->multiVolumesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->multiVolumesScheduledForDeletion = null;
                }
            }

            if ($this->collMultiVolumes !== null) {
                foreach ($this->collMultiVolumes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->volumesScheduledForDeletion !== null) {
                if (!$this->volumesScheduledForDeletion->isEmpty()) {
                    VolumeQuery::create()
                        ->filterByPrimaryKeys($this->volumesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->volumesScheduledForDeletion = null;
                }
            }

            if ($this->collVolumes !== null) {
                foreach ($this->collVolumes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->chaptersScheduledForDeletion !== null) {
                if (!$this->chaptersScheduledForDeletion->isEmpty()) {
                    ChapterQuery::create()
                        ->filterByPrimaryKeys($this->chaptersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->chaptersScheduledForDeletion = null;
                }
            }

            if ($this->collChapters !== null) {
                foreach ($this->collChapters as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->articlesScheduledForDeletion !== null) {
                if (!$this->articlesScheduledForDeletion->isEmpty()) {
                    ArticleQuery::create()
                        ->filterByPrimaryKeys($this->articlesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->articlesScheduledForDeletion = null;
                }
            }

            if ($this->collArticles !== null) {
                foreach ($this->collArticles as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->sequenceEntriesScheduledForDeletion !== null) {
                if (!$this->sequenceEntriesScheduledForDeletion->isEmpty()) {
                    SequenceEntryQuery::create()
                        ->filterByPrimaryKeys($this->sequenceEntriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->sequenceEntriesScheduledForDeletion = null;
                }
            }

            if ($this->collSequenceEntries !== null) {
                foreach ($this->collSequenceEntries as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->languagePublicationsScheduledForDeletion !== null) {
                if (!$this->languagePublicationsScheduledForDeletion->isEmpty()) {
                    LanguagePublicationQuery::create()
                        ->filterByPrimaryKeys($this->languagePublicationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->languagePublicationsScheduledForDeletion = null;
                }
            }

            if ($this->collLanguagePublications !== null) {
                foreach ($this->collLanguagePublications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->genrePublicationsScheduledForDeletion !== null) {
                if (!$this->genrePublicationsScheduledForDeletion->isEmpty()) {
                    GenrePublicationQuery::create()
                        ->filterByPrimaryKeys($this->genrePublicationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->genrePublicationsScheduledForDeletion = null;
                }
            }

            if ($this->collGenrePublications !== null) {
                foreach ($this->collGenrePublications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->publicationTagsScheduledForDeletion !== null) {
                if (!$this->publicationTagsScheduledForDeletion->isEmpty()) {
                    PublicationTagQuery::create()
                        ->filterByPrimaryKeys($this->publicationTagsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationTagsScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationTags !== null) {
                foreach ($this->collPublicationTags as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->categoryPublicationsScheduledForDeletion !== null) {
                if (!$this->categoryPublicationsScheduledForDeletion->isEmpty()) {
                    CategoryPublicationQuery::create()
                        ->filterByPrimaryKeys($this->categoryPublicationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->categoryPublicationsScheduledForDeletion = null;
                }
            }

            if ($this->collCategoryPublications !== null) {
                foreach ($this->collCategoryPublications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->fontPublicationsScheduledForDeletion !== null) {
                if (!$this->fontPublicationsScheduledForDeletion->isEmpty()) {
                    FontPublicationQuery::create()
                        ->filterByPrimaryKeys($this->fontPublicationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->fontPublicationsScheduledForDeletion = null;
                }
            }

            if ($this->collFontPublications !== null) {
                foreach ($this->collFontPublications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->publicationPublicationgroupsScheduledForDeletion !== null) {
                if (!$this->publicationPublicationgroupsScheduledForDeletion->isEmpty()) {
                    PublicationPublicationgroupQuery::create()
                        ->filterByPrimaryKeys($this->publicationPublicationgroupsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationPublicationgroupsScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationPublicationgroups !== null) {
                foreach ($this->collPublicationPublicationgroups as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->personPublicationsScheduledForDeletion !== null) {
                if (!$this->personPublicationsScheduledForDeletion->isEmpty()) {
                    PersonPublicationQuery::create()
                        ->filterByPrimaryKeys($this->personPublicationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->personPublicationsScheduledForDeletion = null;
                }
            }

            if ($this->collPersonPublications !== null) {
                foreach ($this->collPersonPublications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->recentUsesScheduledForDeletion !== null) {
                if (!$this->recentUsesScheduledForDeletion->isEmpty()) {
                    RecentUseQuery::create()
                        ->filterByPrimaryKeys($this->recentUsesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->recentUsesScheduledForDeletion = null;
                }
            }

            if ($this->collRecentUses !== null) {
                foreach ($this->collRecentUses as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->tasksScheduledForDeletion !== null) {
                if (!$this->tasksScheduledForDeletion->isEmpty()) {
                    foreach ($this->tasksScheduledForDeletion as $task) {
                        // need to save related object because we set the relation to null
                        $task->save($con);
                    }
                    $this->tasksScheduledForDeletion = null;
                }
            }

            if ($this->collTasks !== null) {
                foreach ($this->collTasks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->copyLocationsScheduledForDeletion !== null) {
                if (!$this->copyLocationsScheduledForDeletion->isEmpty()) {
                    CopyLocationQuery::create()
                        ->filterByPrimaryKeys($this->copyLocationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->copyLocationsScheduledForDeletion = null;
                }
            }

            if ($this->collCopyLocations !== null) {
                foreach ($this->collCopyLocations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->imagesourcesScheduledForDeletion !== null) {
                if (!$this->imagesourcesScheduledForDeletion->isEmpty()) {
                    ImagesourceQuery::create()
                        ->filterByPrimaryKeys($this->imagesourcesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->imagesourcesScheduledForDeletion = null;
                }
            }

            if ($this->collImagesources !== null) {
                foreach ($this->collImagesources as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->textsourcesScheduledForDeletion !== null) {
                if (!$this->textsourcesScheduledForDeletion->isEmpty()) {
                    TextsourceQuery::create()
                        ->filterByPrimaryKeys($this->textsourcesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->textsourcesScheduledForDeletion = null;
                }
            }

            if ($this->collTextsources !== null) {
                foreach ($this->collTextsources as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PublicationPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '"id"';
        }
        if ($this->isColumnModified(PublicationPeer::TYPE)) {
            $modifiedColumns[':p' . $index++]  = '"type"';
        }
        if ($this->isColumnModified(PublicationPeer::LEGACYTYPE)) {
            $modifiedColumns[':p' . $index++]  = '"legacytype"';
        }
        if ($this->isColumnModified(PublicationPeer::TITLE_ID)) {
            $modifiedColumns[':p' . $index++]  = '"title_id"';
        }
        if ($this->isColumnModified(PublicationPeer::FIRSTEDITIONPUBLICATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '"firsteditionpublication_id"';
        }
        if ($this->isColumnModified(PublicationPeer::PLACE_ID)) {
            $modifiedColumns[':p' . $index++]  = '"place_id"';
        }
        if ($this->isColumnModified(PublicationPeer::PUBLICATIONDATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '"publicationdate_id"';
        }
        if ($this->isColumnModified(PublicationPeer::CREATIONDATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '"creationdate_id"';
        }
        if ($this->isColumnModified(PublicationPeer::PUBLISHINGCOMPANY_ID)) {
            $modifiedColumns[':p' . $index++]  = '"publishingcompany_id"';
        }
        if ($this->isColumnModified(PublicationPeer::SOURCE_ID)) {
            $modifiedColumns[':p' . $index++]  = '"source_id"';
        }
        if ($this->isColumnModified(PublicationPeer::LEGACYGENRE)) {
            $modifiedColumns[':p' . $index++]  = '"legacygenre"';
        }
        if ($this->isColumnModified(PublicationPeer::LEGACYSUBGENRE)) {
            $modifiedColumns[':p' . $index++]  = '"legacysubgenre"';
        }
        if ($this->isColumnModified(PublicationPeer::DIRNAME)) {
            $modifiedColumns[':p' . $index++]  = '"dirname"';
        }
        if ($this->isColumnModified(PublicationPeer::USEDCOPYLOCATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '"usedcopylocation_id"';
        }
        if ($this->isColumnModified(PublicationPeer::PARTNER_ID)) {
            $modifiedColumns[':p' . $index++]  = '"partner_id"';
        }
        if ($this->isColumnModified(PublicationPeer::EDITIONDESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '"editiondescription"';
        }
        if ($this->isColumnModified(PublicationPeer::DIGITALEDITIONEDITOR)) {
            $modifiedColumns[':p' . $index++]  = '"digitaleditioneditor"';
        }
        if ($this->isColumnModified(PublicationPeer::TRANSCRIPTIONCOMMENT)) {
            $modifiedColumns[':p' . $index++]  = '"transcriptioncomment"';
        }
        if ($this->isColumnModified(PublicationPeer::NUMPAGES)) {
            $modifiedColumns[':p' . $index++]  = '"numpages"';
        }
        if ($this->isColumnModified(PublicationPeer::NUMPAGESNUMERIC)) {
            $modifiedColumns[':p' . $index++]  = '"numpagesnumeric"';
        }
        if ($this->isColumnModified(PublicationPeer::COMMENT)) {
            $modifiedColumns[':p' . $index++]  = '"comment"';
        }
        if ($this->isColumnModified(PublicationPeer::ENCODING_COMMENT)) {
            $modifiedColumns[':p' . $index++]  = '"encoding_comment"';
        }
        if ($this->isColumnModified(PublicationPeer::DOI)) {
            $modifiedColumns[':p' . $index++]  = '"doi"';
        }
        if ($this->isColumnModified(PublicationPeer::FORMAT)) {
            $modifiedColumns[':p' . $index++]  = '"format"';
        }
        if ($this->isColumnModified(PublicationPeer::DIRECTORYNAME)) {
            $modifiedColumns[':p' . $index++]  = '"directoryname"';
        }
        if ($this->isColumnModified(PublicationPeer::WWWREADY)) {
            $modifiedColumns[':p' . $index++]  = '"wwwready"';
        }
        if ($this->isColumnModified(PublicationPeer::LAST_CHANGED_BY_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '"last_changed_by_user_id"';
        }
        if ($this->isColumnModified(PublicationPeer::TREE_ID)) {
            $modifiedColumns[':p' . $index++]  = '"tree_id"';
        }
        if ($this->isColumnModified(PublicationPeer::TREE_LEFT)) {
            $modifiedColumns[':p' . $index++]  = '"tree_left"';
        }
        if ($this->isColumnModified(PublicationPeer::TREE_RIGHT)) {
            $modifiedColumns[':p' . $index++]  = '"tree_right"';
        }
        if ($this->isColumnModified(PublicationPeer::TREE_LEVEL)) {
            $modifiedColumns[':p' . $index++]  = '"tree_level"';
        }
        if ($this->isColumnModified(PublicationPeer::PUBLISHINGCOMPANY_ID_IS_RECONSTRUCTED)) {
            $modifiedColumns[':p' . $index++]  = '"publishingcompany_id_is_reconstructed"';
        }
        if ($this->isColumnModified(PublicationPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '"created_at"';
        }
        if ($this->isColumnModified(PublicationPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '"updated_at"';
        }

        $sql = sprintf(
            'INSERT INTO "publication" (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '"id"':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '"type"':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_INT);
                        break;
                    case '"legacytype"':
                        $stmt->bindValue($identifier, $this->legacytype, PDO::PARAM_STR);
                        break;
                    case '"title_id"':
                        $stmt->bindValue($identifier, $this->title_id, PDO::PARAM_INT);
                        break;
                    case '"firsteditionpublication_id"':
                        $stmt->bindValue($identifier, $this->firsteditionpublication_id, PDO::PARAM_INT);
                        break;
                    case '"place_id"':
                        $stmt->bindValue($identifier, $this->place_id, PDO::PARAM_INT);
                        break;
                    case '"publicationdate_id"':
                        $stmt->bindValue($identifier, $this->publicationdate_id, PDO::PARAM_INT);
                        break;
                    case '"creationdate_id"':
                        $stmt->bindValue($identifier, $this->creationdate_id, PDO::PARAM_INT);
                        break;
                    case '"publishingcompany_id"':
                        $stmt->bindValue($identifier, $this->publishingcompany_id, PDO::PARAM_INT);
                        break;
                    case '"source_id"':
                        $stmt->bindValue($identifier, $this->source_id, PDO::PARAM_INT);
                        break;
                    case '"legacygenre"':
                        $stmt->bindValue($identifier, $this->legacygenre, PDO::PARAM_STR);
                        break;
                    case '"legacysubgenre"':
                        $stmt->bindValue($identifier, $this->legacysubgenre, PDO::PARAM_STR);
                        break;
                    case '"dirname"':
                        $stmt->bindValue($identifier, $this->dirname, PDO::PARAM_STR);
                        break;
                    case '"usedcopylocation_id"':
                        $stmt->bindValue($identifier, $this->usedcopylocation_id, PDO::PARAM_INT);
                        break;
                    case '"partner_id"':
                        $stmt->bindValue($identifier, $this->partner_id, PDO::PARAM_INT);
                        break;
                    case '"editiondescription"':
                        $stmt->bindValue($identifier, $this->editiondescription, PDO::PARAM_STR);
                        break;
                    case '"digitaleditioneditor"':
                        $stmt->bindValue($identifier, $this->digitaleditioneditor, PDO::PARAM_STR);
                        break;
                    case '"transcriptioncomment"':
                        $stmt->bindValue($identifier, $this->transcriptioncomment, PDO::PARAM_STR);
                        break;
                    case '"numpages"':
                        $stmt->bindValue($identifier, $this->numpages, PDO::PARAM_STR);
                        break;
                    case '"numpagesnumeric"':
                        $stmt->bindValue($identifier, $this->numpagesnumeric, PDO::PARAM_INT);
                        break;
                    case '"comment"':
                        $stmt->bindValue($identifier, $this->comment, PDO::PARAM_STR);
                        break;
                    case '"encoding_comment"':
                        $stmt->bindValue($identifier, $this->encoding_comment, PDO::PARAM_STR);
                        break;
                    case '"doi"':
                        $stmt->bindValue($identifier, $this->doi, PDO::PARAM_STR);
                        break;
                    case '"format"':
                        $stmt->bindValue($identifier, $this->format, PDO::PARAM_STR);
                        break;
                    case '"directoryname"':
                        $stmt->bindValue($identifier, $this->directoryname, PDO::PARAM_STR);
                        break;
                    case '"wwwready"':
                        $stmt->bindValue($identifier, $this->wwwready, PDO::PARAM_INT);
                        break;
                    case '"last_changed_by_user_id"':
                        $stmt->bindValue($identifier, $this->last_changed_by_user_id, PDO::PARAM_INT);
                        break;
                    case '"tree_id"':
                        $stmt->bindValue($identifier, $this->tree_id, PDO::PARAM_INT);
                        break;
                    case '"tree_left"':
                        $stmt->bindValue($identifier, $this->tree_left, PDO::PARAM_INT);
                        break;
                    case '"tree_right"':
                        $stmt->bindValue($identifier, $this->tree_right, PDO::PARAM_INT);
                        break;
                    case '"tree_level"':
                        $stmt->bindValue($identifier, $this->tree_level, PDO::PARAM_INT);
                        break;
                    case '"publishingcompany_id_is_reconstructed"':
                        $stmt->bindValue($identifier, $this->publishingcompany_id_is_reconstructed, PDO::PARAM_BOOL);
                        break;
                    case '"created_at"':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '"updated_at"':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aTitle !== null) {
                if (!$this->aTitle->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aTitle->getValidationFailures());
                }
            }

            if ($this->aSource !== null) {
                if (!$this->aSource->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSource->getValidationFailures());
                }
            }

            if ($this->aPublishingcompany !== null) {
                if (!$this->aPublishingcompany->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPublishingcompany->getValidationFailures());
                }
            }

            if ($this->aPlace !== null) {
                if (!$this->aPlace->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPlace->getValidationFailures());
                }
            }

            if ($this->aDatespecificationRelatedByPublicationdateId !== null) {
                if (!$this->aDatespecificationRelatedByPublicationdateId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDatespecificationRelatedByPublicationdateId->getValidationFailures());
                }
            }

            if ($this->aDatespecificationRelatedByCreationdateId !== null) {
                if (!$this->aDatespecificationRelatedByCreationdateId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDatespecificationRelatedByCreationdateId->getValidationFailures());
                }
            }

            if ($this->aLastChangedByUser !== null) {
                if (!$this->aLastChangedByUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aLastChangedByUser->getValidationFailures());
                }
            }


            if (($retval = PublicationPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collMultiVolumes !== null) {
                    foreach ($this->collMultiVolumes as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collVolumes !== null) {
                    foreach ($this->collVolumes as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collChapters !== null) {
                    foreach ($this->collChapters as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collArticles !== null) {
                    foreach ($this->collArticles as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSequenceEntries !== null) {
                    foreach ($this->collSequenceEntries as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collLanguagePublications !== null) {
                    foreach ($this->collLanguagePublications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collGenrePublications !== null) {
                    foreach ($this->collGenrePublications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublicationTags !== null) {
                    foreach ($this->collPublicationTags as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCategoryPublications !== null) {
                    foreach ($this->collCategoryPublications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collFontPublications !== null) {
                    foreach ($this->collFontPublications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublicationPublicationgroups !== null) {
                    foreach ($this->collPublicationPublicationgroups as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPersonPublications !== null) {
                    foreach ($this->collPersonPublications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collRecentUses !== null) {
                    foreach ($this->collRecentUses as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collTasks !== null) {
                    foreach ($this->collTasks as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCopyLocations !== null) {
                    foreach ($this->collCopyLocations as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collImagesources !== null) {
                    foreach ($this->collImagesources as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collTextsources !== null) {
                    foreach ($this->collTextsources as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = PublicationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getType();
                break;
            case 2:
                return $this->getLegacytype();
                break;
            case 3:
                return $this->getTitleId();
                break;
            case 4:
                return $this->getFirsteditionpublicationId();
                break;
            case 5:
                return $this->getPlaceId();
                break;
            case 6:
                return $this->getPublicationdateId();
                break;
            case 7:
                return $this->getCreationdateId();
                break;
            case 8:
                return $this->getPublishingcompanyId();
                break;
            case 9:
                return $this->getSourceId();
                break;
            case 10:
                return $this->getLegacygenre();
                break;
            case 11:
                return $this->getLegacysubgenre();
                break;
            case 12:
                return $this->getDirname();
                break;
            case 13:
                return $this->getUsedcopylocationId();
                break;
            case 14:
                return $this->getPartnerId();
                break;
            case 15:
                return $this->getEditiondescription();
                break;
            case 16:
                return $this->getDigitaleditioneditor();
                break;
            case 17:
                return $this->getTranscriptioncomment();
                break;
            case 18:
                return $this->getNumpages();
                break;
            case 19:
                return $this->getNumpagesnumeric();
                break;
            case 20:
                return $this->getComment();
                break;
            case 21:
                return $this->getEncodingComment();
                break;
            case 22:
                return $this->getDoi();
                break;
            case 23:
                return $this->getFormat();
                break;
            case 24:
                return $this->getDirectoryname();
                break;
            case 25:
                return $this->getWwwready();
                break;
            case 26:
                return $this->getLastChangedByUserId();
                break;
            case 27:
                return $this->getTreeId();
                break;
            case 28:
                return $this->getTreeLeft();
                break;
            case 29:
                return $this->getTreeRight();
                break;
            case 30:
                return $this->getTreeLevel();
                break;
            case 31:
                return $this->getPublishingcompanyIdIsReconstructed();
                break;
            case 32:
                return $this->getCreatedAt();
                break;
            case 33:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Publication'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Publication'][$this->getPrimaryKey()] = true;
        $keys = PublicationPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getType(),
            $keys[2] => $this->getLegacytype(),
            $keys[3] => $this->getTitleId(),
            $keys[4] => $this->getFirsteditionpublicationId(),
            $keys[5] => $this->getPlaceId(),
            $keys[6] => $this->getPublicationdateId(),
            $keys[7] => $this->getCreationdateId(),
            $keys[8] => $this->getPublishingcompanyId(),
            $keys[9] => $this->getSourceId(),
            $keys[10] => $this->getLegacygenre(),
            $keys[11] => $this->getLegacysubgenre(),
            $keys[12] => $this->getDirname(),
            $keys[13] => $this->getUsedcopylocationId(),
            $keys[14] => $this->getPartnerId(),
            $keys[15] => $this->getEditiondescription(),
            $keys[16] => $this->getDigitaleditioneditor(),
            $keys[17] => $this->getTranscriptioncomment(),
            $keys[18] => $this->getNumpages(),
            $keys[19] => $this->getNumpagesnumeric(),
            $keys[20] => $this->getComment(),
            $keys[21] => $this->getEncodingComment(),
            $keys[22] => $this->getDoi(),
            $keys[23] => $this->getFormat(),
            $keys[24] => $this->getDirectoryname(),
            $keys[25] => $this->getWwwready(),
            $keys[26] => $this->getLastChangedByUserId(),
            $keys[27] => $this->getTreeId(),
            $keys[28] => $this->getTreeLeft(),
            $keys[29] => $this->getTreeRight(),
            $keys[30] => $this->getTreeLevel(),
            $keys[31] => $this->getPublishingcompanyIdIsReconstructed(),
            $keys[32] => $this->getCreatedAt(),
            $keys[33] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aTitle) {
                $result['Title'] = $this->aTitle->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSource) {
                $result['Source'] = $this->aSource->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPublishingcompany) {
                $result['Publishingcompany'] = $this->aPublishingcompany->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPlace) {
                $result['Place'] = $this->aPlace->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDatespecificationRelatedByPublicationdateId) {
                $result['DatespecificationRelatedByPublicationdateId'] = $this->aDatespecificationRelatedByPublicationdateId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDatespecificationRelatedByCreationdateId) {
                $result['DatespecificationRelatedByCreationdateId'] = $this->aDatespecificationRelatedByCreationdateId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLastChangedByUser) {
                $result['LastChangedByUser'] = $this->aLastChangedByUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collMultiVolumes) {
                $result['MultiVolumes'] = $this->collMultiVolumes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collVolumes) {
                $result['Volumes'] = $this->collVolumes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collChapters) {
                $result['Chapters'] = $this->collChapters->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collArticles) {
                $result['Articles'] = $this->collArticles->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSequenceEntries) {
                $result['SequenceEntries'] = $this->collSequenceEntries->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLanguagePublications) {
                $result['LanguagePublications'] = $this->collLanguagePublications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collGenrePublications) {
                $result['GenrePublications'] = $this->collGenrePublications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationTags) {
                $result['PublicationTags'] = $this->collPublicationTags->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCategoryPublications) {
                $result['CategoryPublications'] = $this->collCategoryPublications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFontPublications) {
                $result['FontPublications'] = $this->collFontPublications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationPublicationgroups) {
                $result['PublicationPublicationgroups'] = $this->collPublicationPublicationgroups->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPersonPublications) {
                $result['PersonPublications'] = $this->collPersonPublications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collRecentUses) {
                $result['RecentUses'] = $this->collRecentUses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTasks) {
                $result['Tasks'] = $this->collTasks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCopyLocations) {
                $result['CopyLocations'] = $this->collCopyLocations->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collImagesources) {
                $result['Imagesources'] = $this->collImagesources->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTextsources) {
                $result['Textsources'] = $this->collTextsources->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = PublicationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $valueSet = PublicationPeer::getValueSet(PublicationPeer::TYPE);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setType($value);
                break;
            case 2:
                $this->setLegacytype($value);
                break;
            case 3:
                $this->setTitleId($value);
                break;
            case 4:
                $this->setFirsteditionpublicationId($value);
                break;
            case 5:
                $this->setPlaceId($value);
                break;
            case 6:
                $this->setPublicationdateId($value);
                break;
            case 7:
                $this->setCreationdateId($value);
                break;
            case 8:
                $this->setPublishingcompanyId($value);
                break;
            case 9:
                $this->setSourceId($value);
                break;
            case 10:
                $this->setLegacygenre($value);
                break;
            case 11:
                $this->setLegacysubgenre($value);
                break;
            case 12:
                $this->setDirname($value);
                break;
            case 13:
                $this->setUsedcopylocationId($value);
                break;
            case 14:
                $this->setPartnerId($value);
                break;
            case 15:
                $this->setEditiondescription($value);
                break;
            case 16:
                $this->setDigitaleditioneditor($value);
                break;
            case 17:
                $this->setTranscriptioncomment($value);
                break;
            case 18:
                $this->setNumpages($value);
                break;
            case 19:
                $this->setNumpagesnumeric($value);
                break;
            case 20:
                $this->setComment($value);
                break;
            case 21:
                $this->setEncodingComment($value);
                break;
            case 22:
                $this->setDoi($value);
                break;
            case 23:
                $this->setFormat($value);
                break;
            case 24:
                $this->setDirectoryname($value);
                break;
            case 25:
                $this->setWwwready($value);
                break;
            case 26:
                $this->setLastChangedByUserId($value);
                break;
            case 27:
                $this->setTreeId($value);
                break;
            case 28:
                $this->setTreeLeft($value);
                break;
            case 29:
                $this->setTreeRight($value);
                break;
            case 30:
                $this->setTreeLevel($value);
                break;
            case 31:
                $this->setPublishingcompanyIdIsReconstructed($value);
                break;
            case 32:
                $this->setCreatedAt($value);
                break;
            case 33:
                $this->setUpdatedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = PublicationPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setType($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setLegacytype($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setTitleId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setFirsteditionpublicationId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setPlaceId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setPublicationdateId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setCreationdateId($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setPublishingcompanyId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setSourceId($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setLegacygenre($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setLegacysubgenre($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setDirname($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setUsedcopylocationId($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setPartnerId($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setEditiondescription($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setDigitaleditioneditor($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setTranscriptioncomment($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setNumpages($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setNumpagesnumeric($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setComment($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setEncodingComment($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setDoi($arr[$keys[22]]);
        if (array_key_exists($keys[23], $arr)) $this->setFormat($arr[$keys[23]]);
        if (array_key_exists($keys[24], $arr)) $this->setDirectoryname($arr[$keys[24]]);
        if (array_key_exists($keys[25], $arr)) $this->setWwwready($arr[$keys[25]]);
        if (array_key_exists($keys[26], $arr)) $this->setLastChangedByUserId($arr[$keys[26]]);
        if (array_key_exists($keys[27], $arr)) $this->setTreeId($arr[$keys[27]]);
        if (array_key_exists($keys[28], $arr)) $this->setTreeLeft($arr[$keys[28]]);
        if (array_key_exists($keys[29], $arr)) $this->setTreeRight($arr[$keys[29]]);
        if (array_key_exists($keys[30], $arr)) $this->setTreeLevel($arr[$keys[30]]);
        if (array_key_exists($keys[31], $arr)) $this->setPublishingcompanyIdIsReconstructed($arr[$keys[31]]);
        if (array_key_exists($keys[32], $arr)) $this->setCreatedAt($arr[$keys[32]]);
        if (array_key_exists($keys[33], $arr)) $this->setUpdatedAt($arr[$keys[33]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PublicationPeer::DATABASE_NAME);

        if ($this->isColumnModified(PublicationPeer::ID)) $criteria->add(PublicationPeer::ID, $this->id);
        if ($this->isColumnModified(PublicationPeer::TYPE)) $criteria->add(PublicationPeer::TYPE, $this->type);
        if ($this->isColumnModified(PublicationPeer::LEGACYTYPE)) $criteria->add(PublicationPeer::LEGACYTYPE, $this->legacytype);
        if ($this->isColumnModified(PublicationPeer::TITLE_ID)) $criteria->add(PublicationPeer::TITLE_ID, $this->title_id);
        if ($this->isColumnModified(PublicationPeer::FIRSTEDITIONPUBLICATION_ID)) $criteria->add(PublicationPeer::FIRSTEDITIONPUBLICATION_ID, $this->firsteditionpublication_id);
        if ($this->isColumnModified(PublicationPeer::PLACE_ID)) $criteria->add(PublicationPeer::PLACE_ID, $this->place_id);
        if ($this->isColumnModified(PublicationPeer::PUBLICATIONDATE_ID)) $criteria->add(PublicationPeer::PUBLICATIONDATE_ID, $this->publicationdate_id);
        if ($this->isColumnModified(PublicationPeer::CREATIONDATE_ID)) $criteria->add(PublicationPeer::CREATIONDATE_ID, $this->creationdate_id);
        if ($this->isColumnModified(PublicationPeer::PUBLISHINGCOMPANY_ID)) $criteria->add(PublicationPeer::PUBLISHINGCOMPANY_ID, $this->publishingcompany_id);
        if ($this->isColumnModified(PublicationPeer::SOURCE_ID)) $criteria->add(PublicationPeer::SOURCE_ID, $this->source_id);
        if ($this->isColumnModified(PublicationPeer::LEGACYGENRE)) $criteria->add(PublicationPeer::LEGACYGENRE, $this->legacygenre);
        if ($this->isColumnModified(PublicationPeer::LEGACYSUBGENRE)) $criteria->add(PublicationPeer::LEGACYSUBGENRE, $this->legacysubgenre);
        if ($this->isColumnModified(PublicationPeer::DIRNAME)) $criteria->add(PublicationPeer::DIRNAME, $this->dirname);
        if ($this->isColumnModified(PublicationPeer::USEDCOPYLOCATION_ID)) $criteria->add(PublicationPeer::USEDCOPYLOCATION_ID, $this->usedcopylocation_id);
        if ($this->isColumnModified(PublicationPeer::PARTNER_ID)) $criteria->add(PublicationPeer::PARTNER_ID, $this->partner_id);
        if ($this->isColumnModified(PublicationPeer::EDITIONDESCRIPTION)) $criteria->add(PublicationPeer::EDITIONDESCRIPTION, $this->editiondescription);
        if ($this->isColumnModified(PublicationPeer::DIGITALEDITIONEDITOR)) $criteria->add(PublicationPeer::DIGITALEDITIONEDITOR, $this->digitaleditioneditor);
        if ($this->isColumnModified(PublicationPeer::TRANSCRIPTIONCOMMENT)) $criteria->add(PublicationPeer::TRANSCRIPTIONCOMMENT, $this->transcriptioncomment);
        if ($this->isColumnModified(PublicationPeer::NUMPAGES)) $criteria->add(PublicationPeer::NUMPAGES, $this->numpages);
        if ($this->isColumnModified(PublicationPeer::NUMPAGESNUMERIC)) $criteria->add(PublicationPeer::NUMPAGESNUMERIC, $this->numpagesnumeric);
        if ($this->isColumnModified(PublicationPeer::COMMENT)) $criteria->add(PublicationPeer::COMMENT, $this->comment);
        if ($this->isColumnModified(PublicationPeer::ENCODING_COMMENT)) $criteria->add(PublicationPeer::ENCODING_COMMENT, $this->encoding_comment);
        if ($this->isColumnModified(PublicationPeer::DOI)) $criteria->add(PublicationPeer::DOI, $this->doi);
        if ($this->isColumnModified(PublicationPeer::FORMAT)) $criteria->add(PublicationPeer::FORMAT, $this->format);
        if ($this->isColumnModified(PublicationPeer::DIRECTORYNAME)) $criteria->add(PublicationPeer::DIRECTORYNAME, $this->directoryname);
        if ($this->isColumnModified(PublicationPeer::WWWREADY)) $criteria->add(PublicationPeer::WWWREADY, $this->wwwready);
        if ($this->isColumnModified(PublicationPeer::LAST_CHANGED_BY_USER_ID)) $criteria->add(PublicationPeer::LAST_CHANGED_BY_USER_ID, $this->last_changed_by_user_id);
        if ($this->isColumnModified(PublicationPeer::TREE_ID)) $criteria->add(PublicationPeer::TREE_ID, $this->tree_id);
        if ($this->isColumnModified(PublicationPeer::TREE_LEFT)) $criteria->add(PublicationPeer::TREE_LEFT, $this->tree_left);
        if ($this->isColumnModified(PublicationPeer::TREE_RIGHT)) $criteria->add(PublicationPeer::TREE_RIGHT, $this->tree_right);
        if ($this->isColumnModified(PublicationPeer::TREE_LEVEL)) $criteria->add(PublicationPeer::TREE_LEVEL, $this->tree_level);
        if ($this->isColumnModified(PublicationPeer::PUBLISHINGCOMPANY_ID_IS_RECONSTRUCTED)) $criteria->add(PublicationPeer::PUBLISHINGCOMPANY_ID_IS_RECONSTRUCTED, $this->publishingcompany_id_is_reconstructed);
        if ($this->isColumnModified(PublicationPeer::CREATED_AT)) $criteria->add(PublicationPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PublicationPeer::UPDATED_AT)) $criteria->add(PublicationPeer::UPDATED_AT, $this->updated_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(PublicationPeer::DATABASE_NAME);
        $criteria->add(PublicationPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Publication (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setType($this->getType());
        $copyObj->setLegacytype($this->getLegacytype());
        $copyObj->setTitleId($this->getTitleId());
        $copyObj->setFirsteditionpublicationId($this->getFirsteditionpublicationId());
        $copyObj->setPlaceId($this->getPlaceId());
        $copyObj->setPublicationdateId($this->getPublicationdateId());
        $copyObj->setCreationdateId($this->getCreationdateId());
        $copyObj->setPublishingcompanyId($this->getPublishingcompanyId());
        $copyObj->setSourceId($this->getSourceId());
        $copyObj->setLegacygenre($this->getLegacygenre());
        $copyObj->setLegacysubgenre($this->getLegacysubgenre());
        $copyObj->setDirname($this->getDirname());
        $copyObj->setUsedcopylocationId($this->getUsedcopylocationId());
        $copyObj->setPartnerId($this->getPartnerId());
        $copyObj->setEditiondescription($this->getEditiondescription());
        $copyObj->setDigitaleditioneditor($this->getDigitaleditioneditor());
        $copyObj->setTranscriptioncomment($this->getTranscriptioncomment());
        $copyObj->setNumpages($this->getNumpages());
        $copyObj->setNumpagesnumeric($this->getNumpagesnumeric());
        $copyObj->setComment($this->getComment());
        $copyObj->setEncodingComment($this->getEncodingComment());
        $copyObj->setDoi($this->getDoi());
        $copyObj->setFormat($this->getFormat());
        $copyObj->setDirectoryname($this->getDirectoryname());
        $copyObj->setWwwready($this->getWwwready());
        $copyObj->setLastChangedByUserId($this->getLastChangedByUserId());
        $copyObj->setTreeId($this->getTreeId());
        $copyObj->setTreeLeft($this->getTreeLeft());
        $copyObj->setTreeRight($this->getTreeRight());
        $copyObj->setTreeLevel($this->getTreeLevel());
        $copyObj->setPublishingcompanyIdIsReconstructed($this->getPublishingcompanyIdIsReconstructed());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getMultiVolumes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMultiVolume($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getVolumes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addVolume($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getChapters() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addChapter($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getArticles() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addArticle($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSequenceEntries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSequenceEntry($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLanguagePublications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLanguagePublication($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getGenrePublications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGenrePublication($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationTags() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationTag($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCategoryPublications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCategoryPublication($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFontPublications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFontPublication($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationPublicationgroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationPublicationgroup($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPersonPublications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPersonPublication($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRecentUses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRecentUse($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTasks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTask($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCopyLocations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCopyLocation($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getImagesources() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addImagesource($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTextsources() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTextsource($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Publication Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return PublicationPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PublicationPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Title object.
     *
     * @param                  Title $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTitle(Title $v = null)
    {
        if ($v === null) {
            $this->setTitleId(NULL);
        } else {
            $this->setTitleId($v->getId());
        }

        $this->aTitle = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Title object, it will not be re-added.
        if ($v !== null) {
            $v->addPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated Title object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Title The associated Title object.
     * @throws PropelException
     */
    public function getTitle(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aTitle === null && ($this->title_id !== null) && $doQuery) {
            $this->aTitle = TitleQuery::create()->findPk($this->title_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTitle->addPublications($this);
             */
        }

        return $this->aTitle;
    }

    /**
     * Declares an association between this object and a Source object.
     *
     * @param                  Source $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSource(Source $v = null)
    {
        if ($v === null) {
            $this->setSourceId(NULL);
        } else {
            $this->setSourceId($v->getId());
        }

        $this->aSource = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Source object, it will not be re-added.
        if ($v !== null) {
            $v->addPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated Source object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Source The associated Source object.
     * @throws PropelException
     */
    public function getSource(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aSource === null && ($this->source_id !== null) && $doQuery) {
            $this->aSource = SourceQuery::create()->findPk($this->source_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSource->addPublications($this);
             */
        }

        return $this->aSource;
    }

    /**
     * Declares an association between this object and a Publishingcompany object.
     *
     * @param                  Publishingcompany $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPublishingcompany(Publishingcompany $v = null)
    {
        if ($v === null) {
            $this->setPublishingcompanyId(NULL);
        } else {
            $this->setPublishingcompanyId($v->getId());
        }

        $this->aPublishingcompany = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Publishingcompany object, it will not be re-added.
        if ($v !== null) {
            $v->addPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated Publishingcompany object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Publishingcompany The associated Publishingcompany object.
     * @throws PropelException
     */
    public function getPublishingcompany(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPublishingcompany === null && ($this->publishingcompany_id !== null) && $doQuery) {
            $this->aPublishingcompany = PublishingcompanyQuery::create()->findPk($this->publishingcompany_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPublishingcompany->addPublications($this);
             */
        }

        return $this->aPublishingcompany;
    }

    /**
     * Declares an association between this object and a Place object.
     *
     * @param                  Place $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPlace(Place $v = null)
    {
        if ($v === null) {
            $this->setPlaceId(NULL);
        } else {
            $this->setPlaceId($v->getId());
        }

        $this->aPlace = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Place object, it will not be re-added.
        if ($v !== null) {
            $v->addPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated Place object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Place The associated Place object.
     * @throws PropelException
     */
    public function getPlace(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPlace === null && ($this->place_id !== null) && $doQuery) {
            $this->aPlace = PlaceQuery::create()->findPk($this->place_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPlace->addPublications($this);
             */
        }

        return $this->aPlace;
    }

    /**
     * Declares an association between this object and a Datespecification object.
     *
     * @param                  Datespecification $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDatespecificationRelatedByPublicationdateId(Datespecification $v = null)
    {
        if ($v === null) {
            $this->setPublicationdateId(NULL);
        } else {
            $this->setPublicationdateId($v->getId());
        }

        $this->aDatespecificationRelatedByPublicationdateId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Datespecification object, it will not be re-added.
        if ($v !== null) {
            $v->addPublicationRelatedByPublicationdateId($this);
        }


        return $this;
    }


    /**
     * Get the associated Datespecification object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Datespecification The associated Datespecification object.
     * @throws PropelException
     */
    public function getDatespecificationRelatedByPublicationdateId(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDatespecificationRelatedByPublicationdateId === null && ($this->publicationdate_id !== null) && $doQuery) {
            $this->aDatespecificationRelatedByPublicationdateId = DatespecificationQuery::create()->findPk($this->publicationdate_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDatespecificationRelatedByPublicationdateId->addPublicationsRelatedByPublicationdateId($this);
             */
        }

        return $this->aDatespecificationRelatedByPublicationdateId;
    }

    /**
     * Declares an association between this object and a Datespecification object.
     *
     * @param                  Datespecification $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDatespecificationRelatedByCreationdateId(Datespecification $v = null)
    {
        if ($v === null) {
            $this->setCreationdateId(NULL);
        } else {
            $this->setCreationdateId($v->getId());
        }

        $this->aDatespecificationRelatedByCreationdateId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Datespecification object, it will not be re-added.
        if ($v !== null) {
            $v->addPublicationRelatedByCreationdateId($this);
        }


        return $this;
    }


    /**
     * Get the associated Datespecification object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Datespecification The associated Datespecification object.
     * @throws PropelException
     */
    public function getDatespecificationRelatedByCreationdateId(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDatespecificationRelatedByCreationdateId === null && ($this->creationdate_id !== null) && $doQuery) {
            $this->aDatespecificationRelatedByCreationdateId = DatespecificationQuery::create()->findPk($this->creationdate_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDatespecificationRelatedByCreationdateId->addPublicationsRelatedByCreationdateId($this);
             */
        }

        return $this->aDatespecificationRelatedByCreationdateId;
    }

    /**
     * Declares an association between this object and a DtaUser object.
     *
     * @param                  DtaUser $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLastChangedByUser(DtaUser $v = null)
    {
        if ($v === null) {
            $this->setLastChangedByUserId(NULL);
        } else {
            $this->setLastChangedByUserId($v->getId());
        }

        $this->aLastChangedByUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the DtaUser object, it will not be re-added.
        if ($v !== null) {
            $v->addLastChangedPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated DtaUser object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return DtaUser The associated DtaUser object.
     * @throws PropelException
     */
    public function getLastChangedByUser(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aLastChangedByUser === null && ($this->last_changed_by_user_id !== null) && $doQuery) {
            $this->aLastChangedByUser = DtaUserQuery::create()->findPk($this->last_changed_by_user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aLastChangedByUser->addLastChangedPublications($this);
             */
        }

        return $this->aLastChangedByUser;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('MultiVolume' == $relationName) {
            $this->initMultiVolumes();
        }
        if ('Volume' == $relationName) {
            $this->initVolumes();
        }
        if ('Chapter' == $relationName) {
            $this->initChapters();
        }
        if ('Article' == $relationName) {
            $this->initArticles();
        }
        if ('SequenceEntry' == $relationName) {
            $this->initSequenceEntries();
        }
        if ('LanguagePublication' == $relationName) {
            $this->initLanguagePublications();
        }
        if ('GenrePublication' == $relationName) {
            $this->initGenrePublications();
        }
        if ('PublicationTag' == $relationName) {
            $this->initPublicationTags();
        }
        if ('CategoryPublication' == $relationName) {
            $this->initCategoryPublications();
        }
        if ('FontPublication' == $relationName) {
            $this->initFontPublications();
        }
        if ('PublicationPublicationgroup' == $relationName) {
            $this->initPublicationPublicationgroups();
        }
        if ('PersonPublication' == $relationName) {
            $this->initPersonPublications();
        }
        if ('RecentUse' == $relationName) {
            $this->initRecentUses();
        }
        if ('Task' == $relationName) {
            $this->initTasks();
        }
        if ('CopyLocation' == $relationName) {
            $this->initCopyLocations();
        }
        if ('Imagesource' == $relationName) {
            $this->initImagesources();
        }
        if ('Textsource' == $relationName) {
            $this->initTextsources();
        }
    }

    /**
     * Clears out the collMultiVolumes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addMultiVolumes()
     */
    public function clearMultiVolumes()
    {
        $this->collMultiVolumes = null; // important to set this to null since that means it is uninitialized
        $this->collMultiVolumesPartial = null;

        return $this;
    }

    /**
     * reset is the collMultiVolumes collection loaded partially
     *
     * @return void
     */
    public function resetPartialMultiVolumes($v = true)
    {
        $this->collMultiVolumesPartial = $v;
    }

    /**
     * Initializes the collMultiVolumes collection.
     *
     * By default this just sets the collMultiVolumes collection to an empty array (like clearcollMultiVolumes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMultiVolumes($overrideExisting = true)
    {
        if (null !== $this->collMultiVolumes && !$overrideExisting) {
            return;
        }
        $this->collMultiVolumes = new PropelObjectCollection();
        $this->collMultiVolumes->setModel('MultiVolume');
    }

    /**
     * Gets an array of MultiVolume objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|MultiVolume[] List of MultiVolume objects
     * @throws PropelException
     */
    public function getMultiVolumes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMultiVolumesPartial && !$this->isNew();
        if (null === $this->collMultiVolumes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMultiVolumes) {
                // return empty collection
                $this->initMultiVolumes();
            } else {
                $collMultiVolumes = MultiVolumeQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMultiVolumesPartial && count($collMultiVolumes)) {
                      $this->initMultiVolumes(false);

                      foreach ($collMultiVolumes as $obj) {
                        if (false == $this->collMultiVolumes->contains($obj)) {
                          $this->collMultiVolumes->append($obj);
                        }
                      }

                      $this->collMultiVolumesPartial = true;
                    }

                    $collMultiVolumes->getInternalIterator()->rewind();

                    return $collMultiVolumes;
                }

                if ($partial && $this->collMultiVolumes) {
                    foreach ($this->collMultiVolumes as $obj) {
                        if ($obj->isNew()) {
                            $collMultiVolumes[] = $obj;
                        }
                    }
                }

                $this->collMultiVolumes = $collMultiVolumes;
                $this->collMultiVolumesPartial = false;
            }
        }

        return $this->collMultiVolumes;
    }

    /**
     * Sets a collection of MultiVolume objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $multiVolumes A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setMultiVolumes(PropelCollection $multiVolumes, PropelPDO $con = null)
    {
        $multiVolumesToDelete = $this->getMultiVolumes(new Criteria(), $con)->diff($multiVolumes);


        $this->multiVolumesScheduledForDeletion = $multiVolumesToDelete;

        foreach ($multiVolumesToDelete as $multiVolumeRemoved) {
            $multiVolumeRemoved->setPublication(null);
        }

        $this->collMultiVolumes = null;
        foreach ($multiVolumes as $multiVolume) {
            $this->addMultiVolume($multiVolume);
        }

        $this->collMultiVolumes = $multiVolumes;
        $this->collMultiVolumesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related MultiVolume objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related MultiVolume objects.
     * @throws PropelException
     */
    public function countMultiVolumes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collMultiVolumesPartial && !$this->isNew();
        if (null === $this->collMultiVolumes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMultiVolumes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMultiVolumes());
            }
            $query = MultiVolumeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collMultiVolumes);
    }

    /**
     * Method called to associate a MultiVolume object to this object
     * through the MultiVolume foreign key attribute.
     *
     * @param    MultiVolume $l MultiVolume
     * @return Publication The current object (for fluent API support)
     */
    public function addMultiVolume(MultiVolume $l)
    {
        if ($this->collMultiVolumes === null) {
            $this->initMultiVolumes();
            $this->collMultiVolumesPartial = true;
        }

        if (!in_array($l, $this->collMultiVolumes->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMultiVolume($l);

            if ($this->multiVolumesScheduledForDeletion and $this->multiVolumesScheduledForDeletion->contains($l)) {
                $this->multiVolumesScheduledForDeletion->remove($this->multiVolumesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	MultiVolume $multiVolume The multiVolume object to add.
     */
    protected function doAddMultiVolume($multiVolume)
    {
        $this->collMultiVolumes[]= $multiVolume;
        $multiVolume->setPublication($this);
    }

    /**
     * @param	MultiVolume $multiVolume The multiVolume object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeMultiVolume($multiVolume)
    {
        if ($this->getMultiVolumes()->contains($multiVolume)) {
            $this->collMultiVolumes->remove($this->collMultiVolumes->search($multiVolume));
            if (null === $this->multiVolumesScheduledForDeletion) {
                $this->multiVolumesScheduledForDeletion = clone $this->collMultiVolumes;
                $this->multiVolumesScheduledForDeletion->clear();
            }
            $this->multiVolumesScheduledForDeletion[]= clone $multiVolume;
            $multiVolume->setPublication(null);
        }

        return $this;
    }

    /**
     * Clears out the collVolumes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addVolumes()
     */
    public function clearVolumes()
    {
        $this->collVolumes = null; // important to set this to null since that means it is uninitialized
        $this->collVolumesPartial = null;

        return $this;
    }

    /**
     * reset is the collVolumes collection loaded partially
     *
     * @return void
     */
    public function resetPartialVolumes($v = true)
    {
        $this->collVolumesPartial = $v;
    }

    /**
     * Initializes the collVolumes collection.
     *
     * By default this just sets the collVolumes collection to an empty array (like clearcollVolumes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initVolumes($overrideExisting = true)
    {
        if (null !== $this->collVolumes && !$overrideExisting) {
            return;
        }
        $this->collVolumes = new PropelObjectCollection();
        $this->collVolumes->setModel('Volume');
    }

    /**
     * Gets an array of Volume objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Volume[] List of Volume objects
     * @throws PropelException
     */
    public function getVolumes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collVolumesPartial && !$this->isNew();
        if (null === $this->collVolumes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collVolumes) {
                // return empty collection
                $this->initVolumes();
            } else {
                $collVolumes = VolumeQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collVolumesPartial && count($collVolumes)) {
                      $this->initVolumes(false);

                      foreach ($collVolumes as $obj) {
                        if (false == $this->collVolumes->contains($obj)) {
                          $this->collVolumes->append($obj);
                        }
                      }

                      $this->collVolumesPartial = true;
                    }

                    $collVolumes->getInternalIterator()->rewind();

                    return $collVolumes;
                }

                if ($partial && $this->collVolumes) {
                    foreach ($this->collVolumes as $obj) {
                        if ($obj->isNew()) {
                            $collVolumes[] = $obj;
                        }
                    }
                }

                $this->collVolumes = $collVolumes;
                $this->collVolumesPartial = false;
            }
        }

        return $this->collVolumes;
    }

    /**
     * Sets a collection of Volume objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $volumes A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setVolumes(PropelCollection $volumes, PropelPDO $con = null)
    {
        $volumesToDelete = $this->getVolumes(new Criteria(), $con)->diff($volumes);


        $this->volumesScheduledForDeletion = $volumesToDelete;

        foreach ($volumesToDelete as $volumeRemoved) {
            $volumeRemoved->setPublication(null);
        }

        $this->collVolumes = null;
        foreach ($volumes as $volume) {
            $this->addVolume($volume);
        }

        $this->collVolumes = $volumes;
        $this->collVolumesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Volume objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Volume objects.
     * @throws PropelException
     */
    public function countVolumes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collVolumesPartial && !$this->isNew();
        if (null === $this->collVolumes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collVolumes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getVolumes());
            }
            $query = VolumeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collVolumes);
    }

    /**
     * Method called to associate a Volume object to this object
     * through the Volume foreign key attribute.
     *
     * @param    Volume $l Volume
     * @return Publication The current object (for fluent API support)
     */
    public function addVolume(Volume $l)
    {
        if ($this->collVolumes === null) {
            $this->initVolumes();
            $this->collVolumesPartial = true;
        }

        if (!in_array($l, $this->collVolumes->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddVolume($l);

            if ($this->volumesScheduledForDeletion and $this->volumesScheduledForDeletion->contains($l)) {
                $this->volumesScheduledForDeletion->remove($this->volumesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Volume $volume The volume object to add.
     */
    protected function doAddVolume($volume)
    {
        $this->collVolumes[]= $volume;
        $volume->setPublication($this);
    }

    /**
     * @param	Volume $volume The volume object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeVolume($volume)
    {
        if ($this->getVolumes()->contains($volume)) {
            $this->collVolumes->remove($this->collVolumes->search($volume));
            if (null === $this->volumesScheduledForDeletion) {
                $this->volumesScheduledForDeletion = clone $this->collVolumes;
                $this->volumesScheduledForDeletion->clear();
            }
            $this->volumesScheduledForDeletion[]= clone $volume;
            $volume->setPublication(null);
        }

        return $this;
    }

    /**
     * Clears out the collChapters collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addChapters()
     */
    public function clearChapters()
    {
        $this->collChapters = null; // important to set this to null since that means it is uninitialized
        $this->collChaptersPartial = null;

        return $this;
    }

    /**
     * reset is the collChapters collection loaded partially
     *
     * @return void
     */
    public function resetPartialChapters($v = true)
    {
        $this->collChaptersPartial = $v;
    }

    /**
     * Initializes the collChapters collection.
     *
     * By default this just sets the collChapters collection to an empty array (like clearcollChapters());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initChapters($overrideExisting = true)
    {
        if (null !== $this->collChapters && !$overrideExisting) {
            return;
        }
        $this->collChapters = new PropelObjectCollection();
        $this->collChapters->setModel('Chapter');
    }

    /**
     * Gets an array of Chapter objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Chapter[] List of Chapter objects
     * @throws PropelException
     */
    public function getChapters($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collChaptersPartial && !$this->isNew();
        if (null === $this->collChapters || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collChapters) {
                // return empty collection
                $this->initChapters();
            } else {
                $collChapters = ChapterQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collChaptersPartial && count($collChapters)) {
                      $this->initChapters(false);

                      foreach ($collChapters as $obj) {
                        if (false == $this->collChapters->contains($obj)) {
                          $this->collChapters->append($obj);
                        }
                      }

                      $this->collChaptersPartial = true;
                    }

                    $collChapters->getInternalIterator()->rewind();

                    return $collChapters;
                }

                if ($partial && $this->collChapters) {
                    foreach ($this->collChapters as $obj) {
                        if ($obj->isNew()) {
                            $collChapters[] = $obj;
                        }
                    }
                }

                $this->collChapters = $collChapters;
                $this->collChaptersPartial = false;
            }
        }

        return $this->collChapters;
    }

    /**
     * Sets a collection of Chapter objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $chapters A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setChapters(PropelCollection $chapters, PropelPDO $con = null)
    {
        $chaptersToDelete = $this->getChapters(new Criteria(), $con)->diff($chapters);


        $this->chaptersScheduledForDeletion = $chaptersToDelete;

        foreach ($chaptersToDelete as $chapterRemoved) {
            $chapterRemoved->setPublication(null);
        }

        $this->collChapters = null;
        foreach ($chapters as $chapter) {
            $this->addChapter($chapter);
        }

        $this->collChapters = $chapters;
        $this->collChaptersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Chapter objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Chapter objects.
     * @throws PropelException
     */
    public function countChapters(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collChaptersPartial && !$this->isNew();
        if (null === $this->collChapters || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collChapters) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getChapters());
            }
            $query = ChapterQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collChapters);
    }

    /**
     * Method called to associate a Chapter object to this object
     * through the Chapter foreign key attribute.
     *
     * @param    Chapter $l Chapter
     * @return Publication The current object (for fluent API support)
     */
    public function addChapter(Chapter $l)
    {
        if ($this->collChapters === null) {
            $this->initChapters();
            $this->collChaptersPartial = true;
        }

        if (!in_array($l, $this->collChapters->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddChapter($l);

            if ($this->chaptersScheduledForDeletion and $this->chaptersScheduledForDeletion->contains($l)) {
                $this->chaptersScheduledForDeletion->remove($this->chaptersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Chapter $chapter The chapter object to add.
     */
    protected function doAddChapter($chapter)
    {
        $this->collChapters[]= $chapter;
        $chapter->setPublication($this);
    }

    /**
     * @param	Chapter $chapter The chapter object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeChapter($chapter)
    {
        if ($this->getChapters()->contains($chapter)) {
            $this->collChapters->remove($this->collChapters->search($chapter));
            if (null === $this->chaptersScheduledForDeletion) {
                $this->chaptersScheduledForDeletion = clone $this->collChapters;
                $this->chaptersScheduledForDeletion->clear();
            }
            $this->chaptersScheduledForDeletion[]= clone $chapter;
            $chapter->setPublication(null);
        }

        return $this;
    }

    /**
     * Clears out the collArticles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addArticles()
     */
    public function clearArticles()
    {
        $this->collArticles = null; // important to set this to null since that means it is uninitialized
        $this->collArticlesPartial = null;

        return $this;
    }

    /**
     * reset is the collArticles collection loaded partially
     *
     * @return void
     */
    public function resetPartialArticles($v = true)
    {
        $this->collArticlesPartial = $v;
    }

    /**
     * Initializes the collArticles collection.
     *
     * By default this just sets the collArticles collection to an empty array (like clearcollArticles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initArticles($overrideExisting = true)
    {
        if (null !== $this->collArticles && !$overrideExisting) {
            return;
        }
        $this->collArticles = new PropelObjectCollection();
        $this->collArticles->setModel('Article');
    }

    /**
     * Gets an array of Article objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Article[] List of Article objects
     * @throws PropelException
     */
    public function getArticles($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collArticlesPartial && !$this->isNew();
        if (null === $this->collArticles || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collArticles) {
                // return empty collection
                $this->initArticles();
            } else {
                $collArticles = ArticleQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collArticlesPartial && count($collArticles)) {
                      $this->initArticles(false);

                      foreach ($collArticles as $obj) {
                        if (false == $this->collArticles->contains($obj)) {
                          $this->collArticles->append($obj);
                        }
                      }

                      $this->collArticlesPartial = true;
                    }

                    $collArticles->getInternalIterator()->rewind();

                    return $collArticles;
                }

                if ($partial && $this->collArticles) {
                    foreach ($this->collArticles as $obj) {
                        if ($obj->isNew()) {
                            $collArticles[] = $obj;
                        }
                    }
                }

                $this->collArticles = $collArticles;
                $this->collArticlesPartial = false;
            }
        }

        return $this->collArticles;
    }

    /**
     * Sets a collection of Article objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $articles A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setArticles(PropelCollection $articles, PropelPDO $con = null)
    {
        $articlesToDelete = $this->getArticles(new Criteria(), $con)->diff($articles);


        $this->articlesScheduledForDeletion = $articlesToDelete;

        foreach ($articlesToDelete as $articleRemoved) {
            $articleRemoved->setPublication(null);
        }

        $this->collArticles = null;
        foreach ($articles as $article) {
            $this->addArticle($article);
        }

        $this->collArticles = $articles;
        $this->collArticlesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Article objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Article objects.
     * @throws PropelException
     */
    public function countArticles(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collArticlesPartial && !$this->isNew();
        if (null === $this->collArticles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collArticles) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getArticles());
            }
            $query = ArticleQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collArticles);
    }

    /**
     * Method called to associate a Article object to this object
     * through the Article foreign key attribute.
     *
     * @param    Article $l Article
     * @return Publication The current object (for fluent API support)
     */
    public function addArticle(Article $l)
    {
        if ($this->collArticles === null) {
            $this->initArticles();
            $this->collArticlesPartial = true;
        }

        if (!in_array($l, $this->collArticles->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddArticle($l);

            if ($this->articlesScheduledForDeletion and $this->articlesScheduledForDeletion->contains($l)) {
                $this->articlesScheduledForDeletion->remove($this->articlesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Article $article The article object to add.
     */
    protected function doAddArticle($article)
    {
        $this->collArticles[]= $article;
        $article->setPublication($this);
    }

    /**
     * @param	Article $article The article object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeArticle($article)
    {
        if ($this->getArticles()->contains($article)) {
            $this->collArticles->remove($this->collArticles->search($article));
            if (null === $this->articlesScheduledForDeletion) {
                $this->articlesScheduledForDeletion = clone $this->collArticles;
                $this->articlesScheduledForDeletion->clear();
            }
            $this->articlesScheduledForDeletion[]= clone $article;
            $article->setPublication(null);
        }

        return $this;
    }

    /**
     * Clears out the collSequenceEntries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addSequenceEntries()
     */
    public function clearSequenceEntries()
    {
        $this->collSequenceEntries = null; // important to set this to null since that means it is uninitialized
        $this->collSequenceEntriesPartial = null;

        return $this;
    }

    /**
     * reset is the collSequenceEntries collection loaded partially
     *
     * @return void
     */
    public function resetPartialSequenceEntries($v = true)
    {
        $this->collSequenceEntriesPartial = $v;
    }

    /**
     * Initializes the collSequenceEntries collection.
     *
     * By default this just sets the collSequenceEntries collection to an empty array (like clearcollSequenceEntries());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSequenceEntries($overrideExisting = true)
    {
        if (null !== $this->collSequenceEntries && !$overrideExisting) {
            return;
        }
        $this->collSequenceEntries = new PropelObjectCollection();
        $this->collSequenceEntries->setModel('SequenceEntry');
    }

    /**
     * Gets an array of SequenceEntry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SequenceEntry[] List of SequenceEntry objects
     * @throws PropelException
     */
    public function getSequenceEntries($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSequenceEntriesPartial && !$this->isNew();
        if (null === $this->collSequenceEntries || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSequenceEntries) {
                // return empty collection
                $this->initSequenceEntries();
            } else {
                $collSequenceEntries = SequenceEntryQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSequenceEntriesPartial && count($collSequenceEntries)) {
                      $this->initSequenceEntries(false);

                      foreach ($collSequenceEntries as $obj) {
                        if (false == $this->collSequenceEntries->contains($obj)) {
                          $this->collSequenceEntries->append($obj);
                        }
                      }

                      $this->collSequenceEntriesPartial = true;
                    }

                    $collSequenceEntries->getInternalIterator()->rewind();

                    return $collSequenceEntries;
                }

                if ($partial && $this->collSequenceEntries) {
                    foreach ($this->collSequenceEntries as $obj) {
                        if ($obj->isNew()) {
                            $collSequenceEntries[] = $obj;
                        }
                    }
                }

                $this->collSequenceEntries = $collSequenceEntries;
                $this->collSequenceEntriesPartial = false;
            }
        }

        return $this->collSequenceEntries;
    }

    /**
     * Sets a collection of SequenceEntry objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $sequenceEntries A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setSequenceEntries(PropelCollection $sequenceEntries, PropelPDO $con = null)
    {
        $sequenceEntriesToDelete = $this->getSequenceEntries(new Criteria(), $con)->diff($sequenceEntries);


        $this->sequenceEntriesScheduledForDeletion = $sequenceEntriesToDelete;

        foreach ($sequenceEntriesToDelete as $sequenceEntryRemoved) {
            $sequenceEntryRemoved->setPublication(null);
        }

        $this->collSequenceEntries = null;
        foreach ($sequenceEntries as $sequenceEntry) {
            $this->addSequenceEntry($sequenceEntry);
        }

        $this->collSequenceEntries = $sequenceEntries;
        $this->collSequenceEntriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SequenceEntry objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SequenceEntry objects.
     * @throws PropelException
     */
    public function countSequenceEntries(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSequenceEntriesPartial && !$this->isNew();
        if (null === $this->collSequenceEntries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSequenceEntries) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSequenceEntries());
            }
            $query = SequenceEntryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collSequenceEntries);
    }

    /**
     * Method called to associate a SequenceEntry object to this object
     * through the SequenceEntry foreign key attribute.
     *
     * @param    SequenceEntry $l SequenceEntry
     * @return Publication The current object (for fluent API support)
     */
    public function addSequenceEntry(SequenceEntry $l)
    {
        if ($this->collSequenceEntries === null) {
            $this->initSequenceEntries();
            $this->collSequenceEntriesPartial = true;
        }

        if (!in_array($l, $this->collSequenceEntries->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSequenceEntry($l);

            if ($this->sequenceEntriesScheduledForDeletion and $this->sequenceEntriesScheduledForDeletion->contains($l)) {
                $this->sequenceEntriesScheduledForDeletion->remove($this->sequenceEntriesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SequenceEntry $sequenceEntry The sequenceEntry object to add.
     */
    protected function doAddSequenceEntry($sequenceEntry)
    {
        $this->collSequenceEntries[]= $sequenceEntry;
        $sequenceEntry->setPublication($this);
    }

    /**
     * @param	SequenceEntry $sequenceEntry The sequenceEntry object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeSequenceEntry($sequenceEntry)
    {
        if ($this->getSequenceEntries()->contains($sequenceEntry)) {
            $this->collSequenceEntries->remove($this->collSequenceEntries->search($sequenceEntry));
            if (null === $this->sequenceEntriesScheduledForDeletion) {
                $this->sequenceEntriesScheduledForDeletion = clone $this->collSequenceEntries;
                $this->sequenceEntriesScheduledForDeletion->clear();
            }
            $this->sequenceEntriesScheduledForDeletion[]= clone $sequenceEntry;
            $sequenceEntry->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related SequenceEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SequenceEntry[] List of SequenceEntry objects
     */
    public function getSequenceEntriesJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SequenceEntryQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getSequenceEntries($query, $con);
    }

    /**
     * Clears out the collLanguagePublications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addLanguagePublications()
     */
    public function clearLanguagePublications()
    {
        $this->collLanguagePublications = null; // important to set this to null since that means it is uninitialized
        $this->collLanguagePublicationsPartial = null;

        return $this;
    }

    /**
     * reset is the collLanguagePublications collection loaded partially
     *
     * @return void
     */
    public function resetPartialLanguagePublications($v = true)
    {
        $this->collLanguagePublicationsPartial = $v;
    }

    /**
     * Initializes the collLanguagePublications collection.
     *
     * By default this just sets the collLanguagePublications collection to an empty array (like clearcollLanguagePublications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLanguagePublications($overrideExisting = true)
    {
        if (null !== $this->collLanguagePublications && !$overrideExisting) {
            return;
        }
        $this->collLanguagePublications = new PropelObjectCollection();
        $this->collLanguagePublications->setModel('LanguagePublication');
    }

    /**
     * Gets an array of LanguagePublication objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|LanguagePublication[] List of LanguagePublication objects
     * @throws PropelException
     */
    public function getLanguagePublications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collLanguagePublicationsPartial && !$this->isNew();
        if (null === $this->collLanguagePublications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLanguagePublications) {
                // return empty collection
                $this->initLanguagePublications();
            } else {
                $collLanguagePublications = LanguagePublicationQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collLanguagePublicationsPartial && count($collLanguagePublications)) {
                      $this->initLanguagePublications(false);

                      foreach ($collLanguagePublications as $obj) {
                        if (false == $this->collLanguagePublications->contains($obj)) {
                          $this->collLanguagePublications->append($obj);
                        }
                      }

                      $this->collLanguagePublicationsPartial = true;
                    }

                    $collLanguagePublications->getInternalIterator()->rewind();

                    return $collLanguagePublications;
                }

                if ($partial && $this->collLanguagePublications) {
                    foreach ($this->collLanguagePublications as $obj) {
                        if ($obj->isNew()) {
                            $collLanguagePublications[] = $obj;
                        }
                    }
                }

                $this->collLanguagePublications = $collLanguagePublications;
                $this->collLanguagePublicationsPartial = false;
            }
        }

        return $this->collLanguagePublications;
    }

    /**
     * Sets a collection of LanguagePublication objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $languagePublications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setLanguagePublications(PropelCollection $languagePublications, PropelPDO $con = null)
    {
        $languagePublicationsToDelete = $this->getLanguagePublications(new Criteria(), $con)->diff($languagePublications);


        $this->languagePublicationsScheduledForDeletion = $languagePublicationsToDelete;

        foreach ($languagePublicationsToDelete as $languagePublicationRemoved) {
            $languagePublicationRemoved->setPublication(null);
        }

        $this->collLanguagePublications = null;
        foreach ($languagePublications as $languagePublication) {
            $this->addLanguagePublication($languagePublication);
        }

        $this->collLanguagePublications = $languagePublications;
        $this->collLanguagePublicationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related LanguagePublication objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related LanguagePublication objects.
     * @throws PropelException
     */
    public function countLanguagePublications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collLanguagePublicationsPartial && !$this->isNew();
        if (null === $this->collLanguagePublications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLanguagePublications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLanguagePublications());
            }
            $query = LanguagePublicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collLanguagePublications);
    }

    /**
     * Method called to associate a LanguagePublication object to this object
     * through the LanguagePublication foreign key attribute.
     *
     * @param    LanguagePublication $l LanguagePublication
     * @return Publication The current object (for fluent API support)
     */
    public function addLanguagePublication(LanguagePublication $l)
    {
        if ($this->collLanguagePublications === null) {
            $this->initLanguagePublications();
            $this->collLanguagePublicationsPartial = true;
        }

        if (!in_array($l, $this->collLanguagePublications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddLanguagePublication($l);

            if ($this->languagePublicationsScheduledForDeletion and $this->languagePublicationsScheduledForDeletion->contains($l)) {
                $this->languagePublicationsScheduledForDeletion->remove($this->languagePublicationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	LanguagePublication $languagePublication The languagePublication object to add.
     */
    protected function doAddLanguagePublication($languagePublication)
    {
        $this->collLanguagePublications[]= $languagePublication;
        $languagePublication->setPublication($this);
    }

    /**
     * @param	LanguagePublication $languagePublication The languagePublication object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeLanguagePublication($languagePublication)
    {
        if ($this->getLanguagePublications()->contains($languagePublication)) {
            $this->collLanguagePublications->remove($this->collLanguagePublications->search($languagePublication));
            if (null === $this->languagePublicationsScheduledForDeletion) {
                $this->languagePublicationsScheduledForDeletion = clone $this->collLanguagePublications;
                $this->languagePublicationsScheduledForDeletion->clear();
            }
            $this->languagePublicationsScheduledForDeletion[]= clone $languagePublication;
            $languagePublication->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related LanguagePublications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|LanguagePublication[] List of LanguagePublication objects
     */
    public function getLanguagePublicationsJoinLanguage($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = LanguagePublicationQuery::create(null, $criteria);
        $query->joinWith('Language', $join_behavior);

        return $this->getLanguagePublications($query, $con);
    }

    /**
     * Clears out the collGenrePublications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addGenrePublications()
     */
    public function clearGenrePublications()
    {
        $this->collGenrePublications = null; // important to set this to null since that means it is uninitialized
        $this->collGenrePublicationsPartial = null;

        return $this;
    }

    /**
     * reset is the collGenrePublications collection loaded partially
     *
     * @return void
     */
    public function resetPartialGenrePublications($v = true)
    {
        $this->collGenrePublicationsPartial = $v;
    }

    /**
     * Initializes the collGenrePublications collection.
     *
     * By default this just sets the collGenrePublications collection to an empty array (like clearcollGenrePublications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGenrePublications($overrideExisting = true)
    {
        if (null !== $this->collGenrePublications && !$overrideExisting) {
            return;
        }
        $this->collGenrePublications = new PropelObjectCollection();
        $this->collGenrePublications->setModel('GenrePublication');
    }

    /**
     * Gets an array of GenrePublication objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|GenrePublication[] List of GenrePublication objects
     * @throws PropelException
     */
    public function getGenrePublications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collGenrePublicationsPartial && !$this->isNew();
        if (null === $this->collGenrePublications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGenrePublications) {
                // return empty collection
                $this->initGenrePublications();
            } else {
                $collGenrePublications = GenrePublicationQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collGenrePublicationsPartial && count($collGenrePublications)) {
                      $this->initGenrePublications(false);

                      foreach ($collGenrePublications as $obj) {
                        if (false == $this->collGenrePublications->contains($obj)) {
                          $this->collGenrePublications->append($obj);
                        }
                      }

                      $this->collGenrePublicationsPartial = true;
                    }

                    $collGenrePublications->getInternalIterator()->rewind();

                    return $collGenrePublications;
                }

                if ($partial && $this->collGenrePublications) {
                    foreach ($this->collGenrePublications as $obj) {
                        if ($obj->isNew()) {
                            $collGenrePublications[] = $obj;
                        }
                    }
                }

                $this->collGenrePublications = $collGenrePublications;
                $this->collGenrePublicationsPartial = false;
            }
        }

        return $this->collGenrePublications;
    }

    /**
     * Sets a collection of GenrePublication objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $genrePublications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setGenrePublications(PropelCollection $genrePublications, PropelPDO $con = null)
    {
        $genrePublicationsToDelete = $this->getGenrePublications(new Criteria(), $con)->diff($genrePublications);


        $this->genrePublicationsScheduledForDeletion = $genrePublicationsToDelete;

        foreach ($genrePublicationsToDelete as $genrePublicationRemoved) {
            $genrePublicationRemoved->setPublication(null);
        }

        $this->collGenrePublications = null;
        foreach ($genrePublications as $genrePublication) {
            $this->addGenrePublication($genrePublication);
        }

        $this->collGenrePublications = $genrePublications;
        $this->collGenrePublicationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related GenrePublication objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related GenrePublication objects.
     * @throws PropelException
     */
    public function countGenrePublications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collGenrePublicationsPartial && !$this->isNew();
        if (null === $this->collGenrePublications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGenrePublications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getGenrePublications());
            }
            $query = GenrePublicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collGenrePublications);
    }

    /**
     * Method called to associate a GenrePublication object to this object
     * through the GenrePublication foreign key attribute.
     *
     * @param    GenrePublication $l GenrePublication
     * @return Publication The current object (for fluent API support)
     */
    public function addGenrePublication(GenrePublication $l)
    {
        if ($this->collGenrePublications === null) {
            $this->initGenrePublications();
            $this->collGenrePublicationsPartial = true;
        }

        if (!in_array($l, $this->collGenrePublications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddGenrePublication($l);

            if ($this->genrePublicationsScheduledForDeletion and $this->genrePublicationsScheduledForDeletion->contains($l)) {
                $this->genrePublicationsScheduledForDeletion->remove($this->genrePublicationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	GenrePublication $genrePublication The genrePublication object to add.
     */
    protected function doAddGenrePublication($genrePublication)
    {
        $this->collGenrePublications[]= $genrePublication;
        $genrePublication->setPublication($this);
    }

    /**
     * @param	GenrePublication $genrePublication The genrePublication object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeGenrePublication($genrePublication)
    {
        if ($this->getGenrePublications()->contains($genrePublication)) {
            $this->collGenrePublications->remove($this->collGenrePublications->search($genrePublication));
            if (null === $this->genrePublicationsScheduledForDeletion) {
                $this->genrePublicationsScheduledForDeletion = clone $this->collGenrePublications;
                $this->genrePublicationsScheduledForDeletion->clear();
            }
            $this->genrePublicationsScheduledForDeletion[]= clone $genrePublication;
            $genrePublication->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related GenrePublications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|GenrePublication[] List of GenrePublication objects
     */
    public function getGenrePublicationsJoinGenre($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = GenrePublicationQuery::create(null, $criteria);
        $query->joinWith('Genre', $join_behavior);

        return $this->getGenrePublications($query, $con);
    }

    /**
     * Clears out the collPublicationTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPublicationTags()
     */
    public function clearPublicationTags()
    {
        $this->collPublicationTags = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationTagsPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationTags collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationTags($v = true)
    {
        $this->collPublicationTagsPartial = $v;
    }

    /**
     * Initializes the collPublicationTags collection.
     *
     * By default this just sets the collPublicationTags collection to an empty array (like clearcollPublicationTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationTags($overrideExisting = true)
    {
        if (null !== $this->collPublicationTags && !$overrideExisting) {
            return;
        }
        $this->collPublicationTags = new PropelObjectCollection();
        $this->collPublicationTags->setModel('PublicationTag');
    }

    /**
     * Gets an array of PublicationTag objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PublicationTag[] List of PublicationTag objects
     * @throws PropelException
     */
    public function getPublicationTags($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationTagsPartial && !$this->isNew();
        if (null === $this->collPublicationTags || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationTags) {
                // return empty collection
                $this->initPublicationTags();
            } else {
                $collPublicationTags = PublicationTagQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationTagsPartial && count($collPublicationTags)) {
                      $this->initPublicationTags(false);

                      foreach ($collPublicationTags as $obj) {
                        if (false == $this->collPublicationTags->contains($obj)) {
                          $this->collPublicationTags->append($obj);
                        }
                      }

                      $this->collPublicationTagsPartial = true;
                    }

                    $collPublicationTags->getInternalIterator()->rewind();

                    return $collPublicationTags;
                }

                if ($partial && $this->collPublicationTags) {
                    foreach ($this->collPublicationTags as $obj) {
                        if ($obj->isNew()) {
                            $collPublicationTags[] = $obj;
                        }
                    }
                }

                $this->collPublicationTags = $collPublicationTags;
                $this->collPublicationTagsPartial = false;
            }
        }

        return $this->collPublicationTags;
    }

    /**
     * Sets a collection of PublicationTag objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationTags A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationTags(PropelCollection $publicationTags, PropelPDO $con = null)
    {
        $publicationTagsToDelete = $this->getPublicationTags(new Criteria(), $con)->diff($publicationTags);


        $this->publicationTagsScheduledForDeletion = $publicationTagsToDelete;

        foreach ($publicationTagsToDelete as $publicationTagRemoved) {
            $publicationTagRemoved->setPublication(null);
        }

        $this->collPublicationTags = null;
        foreach ($publicationTags as $publicationTag) {
            $this->addPublicationTag($publicationTag);
        }

        $this->collPublicationTags = $publicationTags;
        $this->collPublicationTagsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PublicationTag objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PublicationTag objects.
     * @throws PropelException
     */
    public function countPublicationTags(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationTagsPartial && !$this->isNew();
        if (null === $this->collPublicationTags || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationTags) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPublicationTags());
            }
            $query = PublicationTagQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collPublicationTags);
    }

    /**
     * Method called to associate a PublicationTag object to this object
     * through the PublicationTag foreign key attribute.
     *
     * @param    PublicationTag $l PublicationTag
     * @return Publication The current object (for fluent API support)
     */
    public function addPublicationTag(PublicationTag $l)
    {
        if ($this->collPublicationTags === null) {
            $this->initPublicationTags();
            $this->collPublicationTagsPartial = true;
        }

        if (!in_array($l, $this->collPublicationTags->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationTag($l);

            if ($this->publicationTagsScheduledForDeletion and $this->publicationTagsScheduledForDeletion->contains($l)) {
                $this->publicationTagsScheduledForDeletion->remove($this->publicationTagsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PublicationTag $publicationTag The publicationTag object to add.
     */
    protected function doAddPublicationTag($publicationTag)
    {
        $this->collPublicationTags[]= $publicationTag;
        $publicationTag->setPublication($this);
    }

    /**
     * @param	PublicationTag $publicationTag The publicationTag object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removePublicationTag($publicationTag)
    {
        if ($this->getPublicationTags()->contains($publicationTag)) {
            $this->collPublicationTags->remove($this->collPublicationTags->search($publicationTag));
            if (null === $this->publicationTagsScheduledForDeletion) {
                $this->publicationTagsScheduledForDeletion = clone $this->collPublicationTags;
                $this->publicationTagsScheduledForDeletion->clear();
            }
            $this->publicationTagsScheduledForDeletion[]= clone $publicationTag;
            $publicationTag->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related PublicationTags from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationTag[] List of PublicationTag objects
     */
    public function getPublicationTagsJoinTag($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationTagQuery::create(null, $criteria);
        $query->joinWith('Tag', $join_behavior);

        return $this->getPublicationTags($query, $con);
    }

    /**
     * Clears out the collCategoryPublications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addCategoryPublications()
     */
    public function clearCategoryPublications()
    {
        $this->collCategoryPublications = null; // important to set this to null since that means it is uninitialized
        $this->collCategoryPublicationsPartial = null;

        return $this;
    }

    /**
     * reset is the collCategoryPublications collection loaded partially
     *
     * @return void
     */
    public function resetPartialCategoryPublications($v = true)
    {
        $this->collCategoryPublicationsPartial = $v;
    }

    /**
     * Initializes the collCategoryPublications collection.
     *
     * By default this just sets the collCategoryPublications collection to an empty array (like clearcollCategoryPublications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCategoryPublications($overrideExisting = true)
    {
        if (null !== $this->collCategoryPublications && !$overrideExisting) {
            return;
        }
        $this->collCategoryPublications = new PropelObjectCollection();
        $this->collCategoryPublications->setModel('CategoryPublication');
    }

    /**
     * Gets an array of CategoryPublication objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CategoryPublication[] List of CategoryPublication objects
     * @throws PropelException
     */
    public function getCategoryPublications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCategoryPublicationsPartial && !$this->isNew();
        if (null === $this->collCategoryPublications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCategoryPublications) {
                // return empty collection
                $this->initCategoryPublications();
            } else {
                $collCategoryPublications = CategoryPublicationQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCategoryPublicationsPartial && count($collCategoryPublications)) {
                      $this->initCategoryPublications(false);

                      foreach ($collCategoryPublications as $obj) {
                        if (false == $this->collCategoryPublications->contains($obj)) {
                          $this->collCategoryPublications->append($obj);
                        }
                      }

                      $this->collCategoryPublicationsPartial = true;
                    }

                    $collCategoryPublications->getInternalIterator()->rewind();

                    return $collCategoryPublications;
                }

                if ($partial && $this->collCategoryPublications) {
                    foreach ($this->collCategoryPublications as $obj) {
                        if ($obj->isNew()) {
                            $collCategoryPublications[] = $obj;
                        }
                    }
                }

                $this->collCategoryPublications = $collCategoryPublications;
                $this->collCategoryPublicationsPartial = false;
            }
        }

        return $this->collCategoryPublications;
    }

    /**
     * Sets a collection of CategoryPublication objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $categoryPublications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setCategoryPublications(PropelCollection $categoryPublications, PropelPDO $con = null)
    {
        $categoryPublicationsToDelete = $this->getCategoryPublications(new Criteria(), $con)->diff($categoryPublications);


        $this->categoryPublicationsScheduledForDeletion = $categoryPublicationsToDelete;

        foreach ($categoryPublicationsToDelete as $categoryPublicationRemoved) {
            $categoryPublicationRemoved->setPublication(null);
        }

        $this->collCategoryPublications = null;
        foreach ($categoryPublications as $categoryPublication) {
            $this->addCategoryPublication($categoryPublication);
        }

        $this->collCategoryPublications = $categoryPublications;
        $this->collCategoryPublicationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CategoryPublication objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CategoryPublication objects.
     * @throws PropelException
     */
    public function countCategoryPublications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCategoryPublicationsPartial && !$this->isNew();
        if (null === $this->collCategoryPublications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCategoryPublications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCategoryPublications());
            }
            $query = CategoryPublicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collCategoryPublications);
    }

    /**
     * Method called to associate a CategoryPublication object to this object
     * through the CategoryPublication foreign key attribute.
     *
     * @param    CategoryPublication $l CategoryPublication
     * @return Publication The current object (for fluent API support)
     */
    public function addCategoryPublication(CategoryPublication $l)
    {
        if ($this->collCategoryPublications === null) {
            $this->initCategoryPublications();
            $this->collCategoryPublicationsPartial = true;
        }

        if (!in_array($l, $this->collCategoryPublications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCategoryPublication($l);

            if ($this->categoryPublicationsScheduledForDeletion and $this->categoryPublicationsScheduledForDeletion->contains($l)) {
                $this->categoryPublicationsScheduledForDeletion->remove($this->categoryPublicationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CategoryPublication $categoryPublication The categoryPublication object to add.
     */
    protected function doAddCategoryPublication($categoryPublication)
    {
        $this->collCategoryPublications[]= $categoryPublication;
        $categoryPublication->setPublication($this);
    }

    /**
     * @param	CategoryPublication $categoryPublication The categoryPublication object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeCategoryPublication($categoryPublication)
    {
        if ($this->getCategoryPublications()->contains($categoryPublication)) {
            $this->collCategoryPublications->remove($this->collCategoryPublications->search($categoryPublication));
            if (null === $this->categoryPublicationsScheduledForDeletion) {
                $this->categoryPublicationsScheduledForDeletion = clone $this->collCategoryPublications;
                $this->categoryPublicationsScheduledForDeletion->clear();
            }
            $this->categoryPublicationsScheduledForDeletion[]= clone $categoryPublication;
            $categoryPublication->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related CategoryPublications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CategoryPublication[] List of CategoryPublication objects
     */
    public function getCategoryPublicationsJoinCategory($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CategoryPublicationQuery::create(null, $criteria);
        $query->joinWith('Category', $join_behavior);

        return $this->getCategoryPublications($query, $con);
    }

    /**
     * Clears out the collFontPublications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addFontPublications()
     */
    public function clearFontPublications()
    {
        $this->collFontPublications = null; // important to set this to null since that means it is uninitialized
        $this->collFontPublicationsPartial = null;

        return $this;
    }

    /**
     * reset is the collFontPublications collection loaded partially
     *
     * @return void
     */
    public function resetPartialFontPublications($v = true)
    {
        $this->collFontPublicationsPartial = $v;
    }

    /**
     * Initializes the collFontPublications collection.
     *
     * By default this just sets the collFontPublications collection to an empty array (like clearcollFontPublications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFontPublications($overrideExisting = true)
    {
        if (null !== $this->collFontPublications && !$overrideExisting) {
            return;
        }
        $this->collFontPublications = new PropelObjectCollection();
        $this->collFontPublications->setModel('FontPublication');
    }

    /**
     * Gets an array of FontPublication objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|FontPublication[] List of FontPublication objects
     * @throws PropelException
     */
    public function getFontPublications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collFontPublicationsPartial && !$this->isNew();
        if (null === $this->collFontPublications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFontPublications) {
                // return empty collection
                $this->initFontPublications();
            } else {
                $collFontPublications = FontPublicationQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collFontPublicationsPartial && count($collFontPublications)) {
                      $this->initFontPublications(false);

                      foreach ($collFontPublications as $obj) {
                        if (false == $this->collFontPublications->contains($obj)) {
                          $this->collFontPublications->append($obj);
                        }
                      }

                      $this->collFontPublicationsPartial = true;
                    }

                    $collFontPublications->getInternalIterator()->rewind();

                    return $collFontPublications;
                }

                if ($partial && $this->collFontPublications) {
                    foreach ($this->collFontPublications as $obj) {
                        if ($obj->isNew()) {
                            $collFontPublications[] = $obj;
                        }
                    }
                }

                $this->collFontPublications = $collFontPublications;
                $this->collFontPublicationsPartial = false;
            }
        }

        return $this->collFontPublications;
    }

    /**
     * Sets a collection of FontPublication objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $fontPublications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setFontPublications(PropelCollection $fontPublications, PropelPDO $con = null)
    {
        $fontPublicationsToDelete = $this->getFontPublications(new Criteria(), $con)->diff($fontPublications);


        $this->fontPublicationsScheduledForDeletion = $fontPublicationsToDelete;

        foreach ($fontPublicationsToDelete as $fontPublicationRemoved) {
            $fontPublicationRemoved->setPublication(null);
        }

        $this->collFontPublications = null;
        foreach ($fontPublications as $fontPublication) {
            $this->addFontPublication($fontPublication);
        }

        $this->collFontPublications = $fontPublications;
        $this->collFontPublicationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related FontPublication objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related FontPublication objects.
     * @throws PropelException
     */
    public function countFontPublications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collFontPublicationsPartial && !$this->isNew();
        if (null === $this->collFontPublications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFontPublications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFontPublications());
            }
            $query = FontPublicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collFontPublications);
    }

    /**
     * Method called to associate a FontPublication object to this object
     * through the FontPublication foreign key attribute.
     *
     * @param    FontPublication $l FontPublication
     * @return Publication The current object (for fluent API support)
     */
    public function addFontPublication(FontPublication $l)
    {
        if ($this->collFontPublications === null) {
            $this->initFontPublications();
            $this->collFontPublicationsPartial = true;
        }

        if (!in_array($l, $this->collFontPublications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddFontPublication($l);

            if ($this->fontPublicationsScheduledForDeletion and $this->fontPublicationsScheduledForDeletion->contains($l)) {
                $this->fontPublicationsScheduledForDeletion->remove($this->fontPublicationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	FontPublication $fontPublication The fontPublication object to add.
     */
    protected function doAddFontPublication($fontPublication)
    {
        $this->collFontPublications[]= $fontPublication;
        $fontPublication->setPublication($this);
    }

    /**
     * @param	FontPublication $fontPublication The fontPublication object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeFontPublication($fontPublication)
    {
        if ($this->getFontPublications()->contains($fontPublication)) {
            $this->collFontPublications->remove($this->collFontPublications->search($fontPublication));
            if (null === $this->fontPublicationsScheduledForDeletion) {
                $this->fontPublicationsScheduledForDeletion = clone $this->collFontPublications;
                $this->fontPublicationsScheduledForDeletion->clear();
            }
            $this->fontPublicationsScheduledForDeletion[]= clone $fontPublication;
            $fontPublication->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related FontPublications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|FontPublication[] List of FontPublication objects
     */
    public function getFontPublicationsJoinFont($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = FontPublicationQuery::create(null, $criteria);
        $query->joinWith('Font', $join_behavior);

        return $this->getFontPublications($query, $con);
    }

    /**
     * Clears out the collPublicationPublicationgroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPublicationPublicationgroups()
     */
    public function clearPublicationPublicationgroups()
    {
        $this->collPublicationPublicationgroups = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationPublicationgroupsPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationPublicationgroups collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationPublicationgroups($v = true)
    {
        $this->collPublicationPublicationgroupsPartial = $v;
    }

    /**
     * Initializes the collPublicationPublicationgroups collection.
     *
     * By default this just sets the collPublicationPublicationgroups collection to an empty array (like clearcollPublicationPublicationgroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationPublicationgroups($overrideExisting = true)
    {
        if (null !== $this->collPublicationPublicationgroups && !$overrideExisting) {
            return;
        }
        $this->collPublicationPublicationgroups = new PropelObjectCollection();
        $this->collPublicationPublicationgroups->setModel('PublicationPublicationgroup');
    }

    /**
     * Gets an array of PublicationPublicationgroup objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PublicationPublicationgroup[] List of PublicationPublicationgroup objects
     * @throws PropelException
     */
    public function getPublicationPublicationgroups($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationPublicationgroupsPartial && !$this->isNew();
        if (null === $this->collPublicationPublicationgroups || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationPublicationgroups) {
                // return empty collection
                $this->initPublicationPublicationgroups();
            } else {
                $collPublicationPublicationgroups = PublicationPublicationgroupQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationPublicationgroupsPartial && count($collPublicationPublicationgroups)) {
                      $this->initPublicationPublicationgroups(false);

                      foreach ($collPublicationPublicationgroups as $obj) {
                        if (false == $this->collPublicationPublicationgroups->contains($obj)) {
                          $this->collPublicationPublicationgroups->append($obj);
                        }
                      }

                      $this->collPublicationPublicationgroupsPartial = true;
                    }

                    $collPublicationPublicationgroups->getInternalIterator()->rewind();

                    return $collPublicationPublicationgroups;
                }

                if ($partial && $this->collPublicationPublicationgroups) {
                    foreach ($this->collPublicationPublicationgroups as $obj) {
                        if ($obj->isNew()) {
                            $collPublicationPublicationgroups[] = $obj;
                        }
                    }
                }

                $this->collPublicationPublicationgroups = $collPublicationPublicationgroups;
                $this->collPublicationPublicationgroupsPartial = false;
            }
        }

        return $this->collPublicationPublicationgroups;
    }

    /**
     * Sets a collection of PublicationPublicationgroup objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationPublicationgroups A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationPublicationgroups(PropelCollection $publicationPublicationgroups, PropelPDO $con = null)
    {
        $publicationPublicationgroupsToDelete = $this->getPublicationPublicationgroups(new Criteria(), $con)->diff($publicationPublicationgroups);


        $this->publicationPublicationgroupsScheduledForDeletion = $publicationPublicationgroupsToDelete;

        foreach ($publicationPublicationgroupsToDelete as $publicationPublicationgroupRemoved) {
            $publicationPublicationgroupRemoved->setPublication(null);
        }

        $this->collPublicationPublicationgroups = null;
        foreach ($publicationPublicationgroups as $publicationPublicationgroup) {
            $this->addPublicationPublicationgroup($publicationPublicationgroup);
        }

        $this->collPublicationPublicationgroups = $publicationPublicationgroups;
        $this->collPublicationPublicationgroupsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PublicationPublicationgroup objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PublicationPublicationgroup objects.
     * @throws PropelException
     */
    public function countPublicationPublicationgroups(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationPublicationgroupsPartial && !$this->isNew();
        if (null === $this->collPublicationPublicationgroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationPublicationgroups) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPublicationPublicationgroups());
            }
            $query = PublicationPublicationgroupQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collPublicationPublicationgroups);
    }

    /**
     * Method called to associate a PublicationPublicationgroup object to this object
     * through the PublicationPublicationgroup foreign key attribute.
     *
     * @param    PublicationPublicationgroup $l PublicationPublicationgroup
     * @return Publication The current object (for fluent API support)
     */
    public function addPublicationPublicationgroup(PublicationPublicationgroup $l)
    {
        if ($this->collPublicationPublicationgroups === null) {
            $this->initPublicationPublicationgroups();
            $this->collPublicationPublicationgroupsPartial = true;
        }

        if (!in_array($l, $this->collPublicationPublicationgroups->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationPublicationgroup($l);

            if ($this->publicationPublicationgroupsScheduledForDeletion and $this->publicationPublicationgroupsScheduledForDeletion->contains($l)) {
                $this->publicationPublicationgroupsScheduledForDeletion->remove($this->publicationPublicationgroupsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PublicationPublicationgroup $publicationPublicationgroup The publicationPublicationgroup object to add.
     */
    protected function doAddPublicationPublicationgroup($publicationPublicationgroup)
    {
        $this->collPublicationPublicationgroups[]= $publicationPublicationgroup;
        $publicationPublicationgroup->setPublication($this);
    }

    /**
     * @param	PublicationPublicationgroup $publicationPublicationgroup The publicationPublicationgroup object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removePublicationPublicationgroup($publicationPublicationgroup)
    {
        if ($this->getPublicationPublicationgroups()->contains($publicationPublicationgroup)) {
            $this->collPublicationPublicationgroups->remove($this->collPublicationPublicationgroups->search($publicationPublicationgroup));
            if (null === $this->publicationPublicationgroupsScheduledForDeletion) {
                $this->publicationPublicationgroupsScheduledForDeletion = clone $this->collPublicationPublicationgroups;
                $this->publicationPublicationgroupsScheduledForDeletion->clear();
            }
            $this->publicationPublicationgroupsScheduledForDeletion[]= clone $publicationPublicationgroup;
            $publicationPublicationgroup->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related PublicationPublicationgroups from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationPublicationgroup[] List of PublicationPublicationgroup objects
     */
    public function getPublicationPublicationgroupsJoinPublicationgroup($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationPublicationgroupQuery::create(null, $criteria);
        $query->joinWith('Publicationgroup', $join_behavior);

        return $this->getPublicationPublicationgroups($query, $con);
    }

    /**
     * Clears out the collPersonPublications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPersonPublications()
     */
    public function clearPersonPublications()
    {
        $this->collPersonPublications = null; // important to set this to null since that means it is uninitialized
        $this->collPersonPublicationsPartial = null;

        return $this;
    }

    /**
     * reset is the collPersonPublications collection loaded partially
     *
     * @return void
     */
    public function resetPartialPersonPublications($v = true)
    {
        $this->collPersonPublicationsPartial = $v;
    }

    /**
     * Initializes the collPersonPublications collection.
     *
     * By default this just sets the collPersonPublications collection to an empty array (like clearcollPersonPublications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPersonPublications($overrideExisting = true)
    {
        if (null !== $this->collPersonPublications && !$overrideExisting) {
            return;
        }
        $this->collPersonPublications = new PropelObjectCollection();
        $this->collPersonPublications->setModel('PersonPublication');
    }

    /**
     * Gets an array of PersonPublication objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PersonPublication[] List of PersonPublication objects
     * @throws PropelException
     */
    public function getPersonPublications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPersonPublicationsPartial && !$this->isNew();
        if (null === $this->collPersonPublications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPersonPublications) {
                // return empty collection
                $this->initPersonPublications();
            } else {
                $collPersonPublications = PersonPublicationQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPersonPublicationsPartial && count($collPersonPublications)) {
                      $this->initPersonPublications(false);

                      foreach ($collPersonPublications as $obj) {
                        if (false == $this->collPersonPublications->contains($obj)) {
                          $this->collPersonPublications->append($obj);
                        }
                      }

                      $this->collPersonPublicationsPartial = true;
                    }

                    $collPersonPublications->getInternalIterator()->rewind();

                    return $collPersonPublications;
                }

                if ($partial && $this->collPersonPublications) {
                    foreach ($this->collPersonPublications as $obj) {
                        if ($obj->isNew()) {
                            $collPersonPublications[] = $obj;
                        }
                    }
                }

                $this->collPersonPublications = $collPersonPublications;
                $this->collPersonPublicationsPartial = false;
            }
        }

        return $this->collPersonPublications;
    }

    /**
     * Sets a collection of PersonPublication objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $personPublications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPersonPublications(PropelCollection $personPublications, PropelPDO $con = null)
    {
        $personPublicationsToDelete = $this->getPersonPublications(new Criteria(), $con)->diff($personPublications);


        $this->personPublicationsScheduledForDeletion = $personPublicationsToDelete;

        foreach ($personPublicationsToDelete as $personPublicationRemoved) {
            $personPublicationRemoved->setPublication(null);
        }

        $this->collPersonPublications = null;
        foreach ($personPublications as $personPublication) {
            $this->addPersonPublication($personPublication);
        }

        $this->collPersonPublications = $personPublications;
        $this->collPersonPublicationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PersonPublication objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PersonPublication objects.
     * @throws PropelException
     */
    public function countPersonPublications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPersonPublicationsPartial && !$this->isNew();
        if (null === $this->collPersonPublications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPersonPublications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPersonPublications());
            }
            $query = PersonPublicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collPersonPublications);
    }

    /**
     * Method called to associate a PersonPublication object to this object
     * through the PersonPublication foreign key attribute.
     *
     * @param    PersonPublication $l PersonPublication
     * @return Publication The current object (for fluent API support)
     */
    public function addPersonPublication(PersonPublication $l)
    {
        if ($this->collPersonPublications === null) {
            $this->initPersonPublications();
            $this->collPersonPublicationsPartial = true;
        }

        if (!in_array($l, $this->collPersonPublications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPersonPublication($l);

            if ($this->personPublicationsScheduledForDeletion and $this->personPublicationsScheduledForDeletion->contains($l)) {
                $this->personPublicationsScheduledForDeletion->remove($this->personPublicationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PersonPublication $personPublication The personPublication object to add.
     */
    protected function doAddPersonPublication($personPublication)
    {
        $this->collPersonPublications[]= $personPublication;
        $personPublication->setPublication($this);
    }

    /**
     * @param	PersonPublication $personPublication The personPublication object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removePersonPublication($personPublication)
    {
        if ($this->getPersonPublications()->contains($personPublication)) {
            $this->collPersonPublications->remove($this->collPersonPublications->search($personPublication));
            if (null === $this->personPublicationsScheduledForDeletion) {
                $this->personPublicationsScheduledForDeletion = clone $this->collPersonPublications;
                $this->personPublicationsScheduledForDeletion->clear();
            }
            $this->personPublicationsScheduledForDeletion[]= clone $personPublication;
            $personPublication->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related PersonPublications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PersonPublication[] List of PersonPublication objects
     */
    public function getPersonPublicationsJoinPerson($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PersonPublicationQuery::create(null, $criteria);
        $query->joinWith('Person', $join_behavior);

        return $this->getPersonPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related PersonPublications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PersonPublication[] List of PersonPublication objects
     */
    public function getPersonPublicationsJoinPersonrole($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PersonPublicationQuery::create(null, $criteria);
        $query->joinWith('Personrole', $join_behavior);

        return $this->getPersonPublications($query, $con);
    }

    /**
     * Clears out the collRecentUses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addRecentUses()
     */
    public function clearRecentUses()
    {
        $this->collRecentUses = null; // important to set this to null since that means it is uninitialized
        $this->collRecentUsesPartial = null;

        return $this;
    }

    /**
     * reset is the collRecentUses collection loaded partially
     *
     * @return void
     */
    public function resetPartialRecentUses($v = true)
    {
        $this->collRecentUsesPartial = $v;
    }

    /**
     * Initializes the collRecentUses collection.
     *
     * By default this just sets the collRecentUses collection to an empty array (like clearcollRecentUses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRecentUses($overrideExisting = true)
    {
        if (null !== $this->collRecentUses && !$overrideExisting) {
            return;
        }
        $this->collRecentUses = new PropelObjectCollection();
        $this->collRecentUses->setModel('RecentUse');
    }

    /**
     * Gets an array of RecentUse objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|RecentUse[] List of RecentUse objects
     * @throws PropelException
     */
    public function getRecentUses($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collRecentUsesPartial && !$this->isNew();
        if (null === $this->collRecentUses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collRecentUses) {
                // return empty collection
                $this->initRecentUses();
            } else {
                $collRecentUses = RecentUseQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collRecentUsesPartial && count($collRecentUses)) {
                      $this->initRecentUses(false);

                      foreach ($collRecentUses as $obj) {
                        if (false == $this->collRecentUses->contains($obj)) {
                          $this->collRecentUses->append($obj);
                        }
                      }

                      $this->collRecentUsesPartial = true;
                    }

                    $collRecentUses->getInternalIterator()->rewind();

                    return $collRecentUses;
                }

                if ($partial && $this->collRecentUses) {
                    foreach ($this->collRecentUses as $obj) {
                        if ($obj->isNew()) {
                            $collRecentUses[] = $obj;
                        }
                    }
                }

                $this->collRecentUses = $collRecentUses;
                $this->collRecentUsesPartial = false;
            }
        }

        return $this->collRecentUses;
    }

    /**
     * Sets a collection of RecentUse objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $recentUses A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setRecentUses(PropelCollection $recentUses, PropelPDO $con = null)
    {
        $recentUsesToDelete = $this->getRecentUses(new Criteria(), $con)->diff($recentUses);


        $this->recentUsesScheduledForDeletion = $recentUsesToDelete;

        foreach ($recentUsesToDelete as $recentUseRemoved) {
            $recentUseRemoved->setPublication(null);
        }

        $this->collRecentUses = null;
        foreach ($recentUses as $recentUse) {
            $this->addRecentUse($recentUse);
        }

        $this->collRecentUses = $recentUses;
        $this->collRecentUsesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related RecentUse objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related RecentUse objects.
     * @throws PropelException
     */
    public function countRecentUses(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collRecentUsesPartial && !$this->isNew();
        if (null === $this->collRecentUses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRecentUses) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getRecentUses());
            }
            $query = RecentUseQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collRecentUses);
    }

    /**
     * Method called to associate a RecentUse object to this object
     * through the RecentUse foreign key attribute.
     *
     * @param    RecentUse $l RecentUse
     * @return Publication The current object (for fluent API support)
     */
    public function addRecentUse(RecentUse $l)
    {
        if ($this->collRecentUses === null) {
            $this->initRecentUses();
            $this->collRecentUsesPartial = true;
        }

        if (!in_array($l, $this->collRecentUses->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddRecentUse($l);

            if ($this->recentUsesScheduledForDeletion and $this->recentUsesScheduledForDeletion->contains($l)) {
                $this->recentUsesScheduledForDeletion->remove($this->recentUsesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	RecentUse $recentUse The recentUse object to add.
     */
    protected function doAddRecentUse($recentUse)
    {
        $this->collRecentUses[]= $recentUse;
        $recentUse->setPublication($this);
    }

    /**
     * @param	RecentUse $recentUse The recentUse object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeRecentUse($recentUse)
    {
        if ($this->getRecentUses()->contains($recentUse)) {
            $this->collRecentUses->remove($this->collRecentUses->search($recentUse));
            if (null === $this->recentUsesScheduledForDeletion) {
                $this->recentUsesScheduledForDeletion = clone $this->collRecentUses;
                $this->recentUsesScheduledForDeletion->clear();
            }
            $this->recentUsesScheduledForDeletion[]= clone $recentUse;
            $recentUse->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related RecentUses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|RecentUse[] List of RecentUse objects
     */
    public function getRecentUsesJoinDtaUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = RecentUseQuery::create(null, $criteria);
        $query->joinWith('DtaUser', $join_behavior);

        return $this->getRecentUses($query, $con);
    }

    /**
     * Clears out the collTasks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addTasks()
     */
    public function clearTasks()
    {
        $this->collTasks = null; // important to set this to null since that means it is uninitialized
        $this->collTasksPartial = null;

        return $this;
    }

    /**
     * reset is the collTasks collection loaded partially
     *
     * @return void
     */
    public function resetPartialTasks($v = true)
    {
        $this->collTasksPartial = $v;
    }

    /**
     * Initializes the collTasks collection.
     *
     * By default this just sets the collTasks collection to an empty array (like clearcollTasks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTasks($overrideExisting = true)
    {
        if (null !== $this->collTasks && !$overrideExisting) {
            return;
        }
        $this->collTasks = new PropelObjectCollection();
        $this->collTasks->setModel('Task');
    }

    /**
     * Gets an array of Task objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Task[] List of Task objects
     * @throws PropelException
     */
    public function getTasks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collTasksPartial && !$this->isNew();
        if (null === $this->collTasks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTasks) {
                // return empty collection
                $this->initTasks();
            } else {
                $collTasks = TaskQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collTasksPartial && count($collTasks)) {
                      $this->initTasks(false);

                      foreach ($collTasks as $obj) {
                        if (false == $this->collTasks->contains($obj)) {
                          $this->collTasks->append($obj);
                        }
                      }

                      $this->collTasksPartial = true;
                    }

                    $collTasks->getInternalIterator()->rewind();

                    return $collTasks;
                }

                if ($partial && $this->collTasks) {
                    foreach ($this->collTasks as $obj) {
                        if ($obj->isNew()) {
                            $collTasks[] = $obj;
                        }
                    }
                }

                $this->collTasks = $collTasks;
                $this->collTasksPartial = false;
            }
        }

        return $this->collTasks;
    }

    /**
     * Sets a collection of Task objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $tasks A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setTasks(PropelCollection $tasks, PropelPDO $con = null)
    {
        $tasksToDelete = $this->getTasks(new Criteria(), $con)->diff($tasks);


        $this->tasksScheduledForDeletion = $tasksToDelete;

        foreach ($tasksToDelete as $taskRemoved) {
            $taskRemoved->setPublication(null);
        }

        $this->collTasks = null;
        foreach ($tasks as $task) {
            $this->addTask($task);
        }

        $this->collTasks = $tasks;
        $this->collTasksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Task objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Task objects.
     * @throws PropelException
     */
    public function countTasks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collTasksPartial && !$this->isNew();
        if (null === $this->collTasks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTasks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTasks());
            }
            $query = TaskQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collTasks);
    }

    /**
     * Method called to associate a Task object to this object
     * through the Task foreign key attribute.
     *
     * @param    Task $l Task
     * @return Publication The current object (for fluent API support)
     */
    public function addTask(Task $l)
    {
        if ($this->collTasks === null) {
            $this->initTasks();
            $this->collTasksPartial = true;
        }

        if (!in_array($l, $this->collTasks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddTask($l);

            if ($this->tasksScheduledForDeletion and $this->tasksScheduledForDeletion->contains($l)) {
                $this->tasksScheduledForDeletion->remove($this->tasksScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Task $task The task object to add.
     */
    protected function doAddTask($task)
    {
        $this->collTasks[]= $task;
        $task->setPublication($this);
    }

    /**
     * @param	Task $task The task object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeTask($task)
    {
        if ($this->getTasks()->contains($task)) {
            $this->collTasks->remove($this->collTasks->search($task));
            if (null === $this->tasksScheduledForDeletion) {
                $this->tasksScheduledForDeletion = clone $this->collTasks;
                $this->tasksScheduledForDeletion->clear();
            }
            $this->tasksScheduledForDeletion[]= $task;
            $task->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Task[] List of Task objects
     */
    public function getTasksJoinTasktype($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TaskQuery::create(null, $criteria);
        $query->joinWith('Tasktype', $join_behavior);

        return $this->getTasks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Task[] List of Task objects
     */
    public function getTasksJoinPublicationgroup($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TaskQuery::create(null, $criteria);
        $query->joinWith('Publicationgroup', $join_behavior);

        return $this->getTasks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Task[] List of Task objects
     */
    public function getTasksJoinPartner($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TaskQuery::create(null, $criteria);
        $query->joinWith('Partner', $join_behavior);

        return $this->getTasks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Task[] List of Task objects
     */
    public function getTasksJoinDtaUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TaskQuery::create(null, $criteria);
        $query->joinWith('DtaUser', $join_behavior);

        return $this->getTasks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Task[] List of Task objects
     */
    public function getTasksJoinCopyLocation($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TaskQuery::create(null, $criteria);
        $query->joinWith('CopyLocation', $join_behavior);

        return $this->getTasks($query, $con);
    }

    /**
     * Clears out the collCopyLocations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addCopyLocations()
     */
    public function clearCopyLocations()
    {
        $this->collCopyLocations = null; // important to set this to null since that means it is uninitialized
        $this->collCopyLocationsPartial = null;

        return $this;
    }

    /**
     * reset is the collCopyLocations collection loaded partially
     *
     * @return void
     */
    public function resetPartialCopyLocations($v = true)
    {
        $this->collCopyLocationsPartial = $v;
    }

    /**
     * Initializes the collCopyLocations collection.
     *
     * By default this just sets the collCopyLocations collection to an empty array (like clearcollCopyLocations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCopyLocations($overrideExisting = true)
    {
        if (null !== $this->collCopyLocations && !$overrideExisting) {
            return;
        }
        $this->collCopyLocations = new PropelObjectCollection();
        $this->collCopyLocations->setModel('CopyLocation');
    }

    /**
     * Gets an array of CopyLocation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CopyLocation[] List of CopyLocation objects
     * @throws PropelException
     */
    public function getCopyLocations($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCopyLocationsPartial && !$this->isNew();
        if (null === $this->collCopyLocations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCopyLocations) {
                // return empty collection
                $this->initCopyLocations();
            } else {
                $collCopyLocations = CopyLocationQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCopyLocationsPartial && count($collCopyLocations)) {
                      $this->initCopyLocations(false);

                      foreach ($collCopyLocations as $obj) {
                        if (false == $this->collCopyLocations->contains($obj)) {
                          $this->collCopyLocations->append($obj);
                        }
                      }

                      $this->collCopyLocationsPartial = true;
                    }

                    $collCopyLocations->getInternalIterator()->rewind();

                    return $collCopyLocations;
                }

                if ($partial && $this->collCopyLocations) {
                    foreach ($this->collCopyLocations as $obj) {
                        if ($obj->isNew()) {
                            $collCopyLocations[] = $obj;
                        }
                    }
                }

                $this->collCopyLocations = $collCopyLocations;
                $this->collCopyLocationsPartial = false;
            }
        }

        return $this->collCopyLocations;
    }

    /**
     * Sets a collection of CopyLocation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $copyLocations A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setCopyLocations(PropelCollection $copyLocations, PropelPDO $con = null)
    {
        $copyLocationsToDelete = $this->getCopyLocations(new Criteria(), $con)->diff($copyLocations);


        $this->copyLocationsScheduledForDeletion = $copyLocationsToDelete;

        foreach ($copyLocationsToDelete as $copyLocationRemoved) {
            $copyLocationRemoved->setPublication(null);
        }

        $this->collCopyLocations = null;
        foreach ($copyLocations as $copyLocation) {
            $this->addCopyLocation($copyLocation);
        }

        $this->collCopyLocations = $copyLocations;
        $this->collCopyLocationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CopyLocation objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CopyLocation objects.
     * @throws PropelException
     */
    public function countCopyLocations(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCopyLocationsPartial && !$this->isNew();
        if (null === $this->collCopyLocations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCopyLocations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCopyLocations());
            }
            $query = CopyLocationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collCopyLocations);
    }

    /**
     * Method called to associate a CopyLocation object to this object
     * through the CopyLocation foreign key attribute.
     *
     * @param    CopyLocation $l CopyLocation
     * @return Publication The current object (for fluent API support)
     */
    public function addCopyLocation(CopyLocation $l)
    {
        if ($this->collCopyLocations === null) {
            $this->initCopyLocations();
            $this->collCopyLocationsPartial = true;
        }

        if (!in_array($l, $this->collCopyLocations->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCopyLocation($l);

            if ($this->copyLocationsScheduledForDeletion and $this->copyLocationsScheduledForDeletion->contains($l)) {
                $this->copyLocationsScheduledForDeletion->remove($this->copyLocationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CopyLocation $copyLocation The copyLocation object to add.
     */
    protected function doAddCopyLocation($copyLocation)
    {
        $this->collCopyLocations[]= $copyLocation;
        $copyLocation->setPublication($this);
    }

    /**
     * @param	CopyLocation $copyLocation The copyLocation object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeCopyLocation($copyLocation)
    {
        if ($this->getCopyLocations()->contains($copyLocation)) {
            $this->collCopyLocations->remove($this->collCopyLocations->search($copyLocation));
            if (null === $this->copyLocationsScheduledForDeletion) {
                $this->copyLocationsScheduledForDeletion = clone $this->collCopyLocations;
                $this->copyLocationsScheduledForDeletion->clear();
            }
            $this->copyLocationsScheduledForDeletion[]= clone $copyLocation;
            $copyLocation->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related CopyLocations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CopyLocation[] List of CopyLocation objects
     */
    public function getCopyLocationsJoinPartner($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CopyLocationQuery::create(null, $criteria);
        $query->joinWith('Partner', $join_behavior);

        return $this->getCopyLocations($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related CopyLocations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CopyLocation[] List of CopyLocation objects
     */
    public function getCopyLocationsJoinLicense($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CopyLocationQuery::create(null, $criteria);
        $query->joinWith('License', $join_behavior);

        return $this->getCopyLocations($query, $con);
    }

    /**
     * Clears out the collImagesources collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addImagesources()
     */
    public function clearImagesources()
    {
        $this->collImagesources = null; // important to set this to null since that means it is uninitialized
        $this->collImagesourcesPartial = null;

        return $this;
    }

    /**
     * reset is the collImagesources collection loaded partially
     *
     * @return void
     */
    public function resetPartialImagesources($v = true)
    {
        $this->collImagesourcesPartial = $v;
    }

    /**
     * Initializes the collImagesources collection.
     *
     * By default this just sets the collImagesources collection to an empty array (like clearcollImagesources());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initImagesources($overrideExisting = true)
    {
        if (null !== $this->collImagesources && !$overrideExisting) {
            return;
        }
        $this->collImagesources = new PropelObjectCollection();
        $this->collImagesources->setModel('Imagesource');
    }

    /**
     * Gets an array of Imagesource objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Imagesource[] List of Imagesource objects
     * @throws PropelException
     */
    public function getImagesources($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collImagesourcesPartial && !$this->isNew();
        if (null === $this->collImagesources || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collImagesources) {
                // return empty collection
                $this->initImagesources();
            } else {
                $collImagesources = ImagesourceQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collImagesourcesPartial && count($collImagesources)) {
                      $this->initImagesources(false);

                      foreach ($collImagesources as $obj) {
                        if (false == $this->collImagesources->contains($obj)) {
                          $this->collImagesources->append($obj);
                        }
                      }

                      $this->collImagesourcesPartial = true;
                    }

                    $collImagesources->getInternalIterator()->rewind();

                    return $collImagesources;
                }

                if ($partial && $this->collImagesources) {
                    foreach ($this->collImagesources as $obj) {
                        if ($obj->isNew()) {
                            $collImagesources[] = $obj;
                        }
                    }
                }

                $this->collImagesources = $collImagesources;
                $this->collImagesourcesPartial = false;
            }
        }

        return $this->collImagesources;
    }

    /**
     * Sets a collection of Imagesource objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $imagesources A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setImagesources(PropelCollection $imagesources, PropelPDO $con = null)
    {
        $imagesourcesToDelete = $this->getImagesources(new Criteria(), $con)->diff($imagesources);


        $this->imagesourcesScheduledForDeletion = $imagesourcesToDelete;

        foreach ($imagesourcesToDelete as $imagesourceRemoved) {
            $imagesourceRemoved->setPublication(null);
        }

        $this->collImagesources = null;
        foreach ($imagesources as $imagesource) {
            $this->addImagesource($imagesource);
        }

        $this->collImagesources = $imagesources;
        $this->collImagesourcesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Imagesource objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Imagesource objects.
     * @throws PropelException
     */
    public function countImagesources(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collImagesourcesPartial && !$this->isNew();
        if (null === $this->collImagesources || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collImagesources) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getImagesources());
            }
            $query = ImagesourceQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collImagesources);
    }

    /**
     * Method called to associate a Imagesource object to this object
     * through the Imagesource foreign key attribute.
     *
     * @param    Imagesource $l Imagesource
     * @return Publication The current object (for fluent API support)
     */
    public function addImagesource(Imagesource $l)
    {
        if ($this->collImagesources === null) {
            $this->initImagesources();
            $this->collImagesourcesPartial = true;
        }

        if (!in_array($l, $this->collImagesources->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddImagesource($l);

            if ($this->imagesourcesScheduledForDeletion and $this->imagesourcesScheduledForDeletion->contains($l)) {
                $this->imagesourcesScheduledForDeletion->remove($this->imagesourcesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Imagesource $imagesource The imagesource object to add.
     */
    protected function doAddImagesource($imagesource)
    {
        $this->collImagesources[]= $imagesource;
        $imagesource->setPublication($this);
    }

    /**
     * @param	Imagesource $imagesource The imagesource object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeImagesource($imagesource)
    {
        if ($this->getImagesources()->contains($imagesource)) {
            $this->collImagesources->remove($this->collImagesources->search($imagesource));
            if (null === $this->imagesourcesScheduledForDeletion) {
                $this->imagesourcesScheduledForDeletion = clone $this->collImagesources;
                $this->imagesourcesScheduledForDeletion->clear();
            }
            $this->imagesourcesScheduledForDeletion[]= clone $imagesource;
            $imagesource->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related Imagesources from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Imagesource[] List of Imagesource objects
     */
    public function getImagesourcesJoinPartner($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ImagesourceQuery::create(null, $criteria);
        $query->joinWith('Partner', $join_behavior);

        return $this->getImagesources($query, $con);
    }

    /**
     * Clears out the collTextsources collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addTextsources()
     */
    public function clearTextsources()
    {
        $this->collTextsources = null; // important to set this to null since that means it is uninitialized
        $this->collTextsourcesPartial = null;

        return $this;
    }

    /**
     * reset is the collTextsources collection loaded partially
     *
     * @return void
     */
    public function resetPartialTextsources($v = true)
    {
        $this->collTextsourcesPartial = $v;
    }

    /**
     * Initializes the collTextsources collection.
     *
     * By default this just sets the collTextsources collection to an empty array (like clearcollTextsources());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTextsources($overrideExisting = true)
    {
        if (null !== $this->collTextsources && !$overrideExisting) {
            return;
        }
        $this->collTextsources = new PropelObjectCollection();
        $this->collTextsources->setModel('Textsource');
    }

    /**
     * Gets an array of Textsource objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Textsource[] List of Textsource objects
     * @throws PropelException
     */
    public function getTextsources($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collTextsourcesPartial && !$this->isNew();
        if (null === $this->collTextsources || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTextsources) {
                // return empty collection
                $this->initTextsources();
            } else {
                $collTextsources = TextsourceQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collTextsourcesPartial && count($collTextsources)) {
                      $this->initTextsources(false);

                      foreach ($collTextsources as $obj) {
                        if (false == $this->collTextsources->contains($obj)) {
                          $this->collTextsources->append($obj);
                        }
                      }

                      $this->collTextsourcesPartial = true;
                    }

                    $collTextsources->getInternalIterator()->rewind();

                    return $collTextsources;
                }

                if ($partial && $this->collTextsources) {
                    foreach ($this->collTextsources as $obj) {
                        if ($obj->isNew()) {
                            $collTextsources[] = $obj;
                        }
                    }
                }

                $this->collTextsources = $collTextsources;
                $this->collTextsourcesPartial = false;
            }
        }

        return $this->collTextsources;
    }

    /**
     * Sets a collection of Textsource objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $textsources A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setTextsources(PropelCollection $textsources, PropelPDO $con = null)
    {
        $textsourcesToDelete = $this->getTextsources(new Criteria(), $con)->diff($textsources);


        $this->textsourcesScheduledForDeletion = $textsourcesToDelete;

        foreach ($textsourcesToDelete as $textsourceRemoved) {
            $textsourceRemoved->setPublication(null);
        }

        $this->collTextsources = null;
        foreach ($textsources as $textsource) {
            $this->addTextsource($textsource);
        }

        $this->collTextsources = $textsources;
        $this->collTextsourcesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Textsource objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Textsource objects.
     * @throws PropelException
     */
    public function countTextsources(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collTextsourcesPartial && !$this->isNew();
        if (null === $this->collTextsources || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTextsources) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTextsources());
            }
            $query = TextsourceQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collTextsources);
    }

    /**
     * Method called to associate a Textsource object to this object
     * through the Textsource foreign key attribute.
     *
     * @param    Textsource $l Textsource
     * @return Publication The current object (for fluent API support)
     */
    public function addTextsource(Textsource $l)
    {
        if ($this->collTextsources === null) {
            $this->initTextsources();
            $this->collTextsourcesPartial = true;
        }

        if (!in_array($l, $this->collTextsources->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddTextsource($l);

            if ($this->textsourcesScheduledForDeletion and $this->textsourcesScheduledForDeletion->contains($l)) {
                $this->textsourcesScheduledForDeletion->remove($this->textsourcesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Textsource $textsource The textsource object to add.
     */
    protected function doAddTextsource($textsource)
    {
        $this->collTextsources[]= $textsource;
        $textsource->setPublication($this);
    }

    /**
     * @param	Textsource $textsource The textsource object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeTextsource($textsource)
    {
        if ($this->getTextsources()->contains($textsource)) {
            $this->collTextsources->remove($this->collTextsources->search($textsource));
            if (null === $this->textsourcesScheduledForDeletion) {
                $this->textsourcesScheduledForDeletion = clone $this->collTextsources;
                $this->textsourcesScheduledForDeletion->clear();
            }
            $this->textsourcesScheduledForDeletion[]= clone $textsource;
            $textsource->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related Textsources from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Textsource[] List of Textsource objects
     */
    public function getTextsourcesJoinLicense($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TextsourceQuery::create(null, $criteria);
        $query->joinWith('License', $join_behavior);

        return $this->getTextsources($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related Textsources from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Textsource[] List of Textsource objects
     */
    public function getTextsourcesJoinPartner($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TextsourceQuery::create(null, $criteria);
        $query->joinWith('Partner', $join_behavior);

        return $this->getTextsources($query, $con);
    }

    /**
     * Clears out the collLanguages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addLanguages()
     */
    public function clearLanguages()
    {
        $this->collLanguages = null; // important to set this to null since that means it is uninitialized
        $this->collLanguagesPartial = null;

        return $this;
    }

    /**
     * Initializes the collLanguages collection.
     *
     * By default this just sets the collLanguages collection to an empty collection (like clearLanguages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initLanguages()
    {
        $this->collLanguages = new PropelObjectCollection();
        $this->collLanguages->setModel('Language');
    }

    /**
     * Gets a collection of Language objects related by a many-to-many relationship
     * to the current object by way of the language_publication cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Language[] List of Language objects
     */
    public function getLanguages($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collLanguages || null !== $criteria) {
            if ($this->isNew() && null === $this->collLanguages) {
                // return empty collection
                $this->initLanguages();
            } else {
                $collLanguages = LanguageQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collLanguages;
                }
                $this->collLanguages = $collLanguages;
            }
        }

        return $this->collLanguages;
    }

    /**
     * Sets a collection of Language objects related by a many-to-many relationship
     * to the current object by way of the language_publication cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $languages A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setLanguages(PropelCollection $languages, PropelPDO $con = null)
    {
        $this->clearLanguages();
        $currentLanguages = $this->getLanguages(null, $con);

        $this->languagesScheduledForDeletion = $currentLanguages->diff($languages);

        foreach ($languages as $language) {
            if (!$currentLanguages->contains($language)) {
                $this->doAddLanguage($language);
            }
        }

        $this->collLanguages = $languages;

        return $this;
    }

    /**
     * Gets the number of Language objects related by a many-to-many relationship
     * to the current object by way of the language_publication cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Language objects
     */
    public function countLanguages($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collLanguages || null !== $criteria) {
            if ($this->isNew() && null === $this->collLanguages) {
                return 0;
            } else {
                $query = LanguageQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPublication($this)
                    ->count($con);
            }
        } else {
            return count($this->collLanguages);
        }
    }

    /**
     * Associate a Language object to this object
     * through the language_publication cross reference table.
     *
     * @param  Language $language The LanguagePublication object to relate
     * @return Publication The current object (for fluent API support)
     */
    public function addLanguage(Language $language)
    {
        if ($this->collLanguages === null) {
            $this->initLanguages();
        }

        if (!$this->collLanguages->contains($language)) { // only add it if the **same** object is not already associated
            $this->doAddLanguage($language);
            $this->collLanguages[] = $language;

            if ($this->languagesScheduledForDeletion and $this->languagesScheduledForDeletion->contains($language)) {
                $this->languagesScheduledForDeletion->remove($this->languagesScheduledForDeletion->search($language));
            }
        }

        return $this;
    }

    /**
     * @param	Language $language The language object to add.
     */
    protected function doAddLanguage(Language $language)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$language->getPublications()->contains($this)) {
            $languagePublication = new LanguagePublication();
            $languagePublication->setLanguage($language);
            $this->addLanguagePublication($languagePublication);

            $foreignCollection = $language->getPublications();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Language object to this object
     * through the language_publication cross reference table.
     *
     * @param Language $language The LanguagePublication object to relate
     * @return Publication The current object (for fluent API support)
     */
    public function removeLanguage(Language $language)
    {
        if ($this->getLanguages()->contains($language)) {
            $this->collLanguages->remove($this->collLanguages->search($language));
            if (null === $this->languagesScheduledForDeletion) {
                $this->languagesScheduledForDeletion = clone $this->collLanguages;
                $this->languagesScheduledForDeletion->clear();
            }
            $this->languagesScheduledForDeletion[]= $language;
        }

        return $this;
    }

    /**
     * Clears out the collGenres collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addGenres()
     */
    public function clearGenres()
    {
        $this->collGenres = null; // important to set this to null since that means it is uninitialized
        $this->collGenresPartial = null;

        return $this;
    }

    /**
     * Initializes the collGenres collection.
     *
     * By default this just sets the collGenres collection to an empty collection (like clearGenres());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initGenres()
    {
        $this->collGenres = new PropelObjectCollection();
        $this->collGenres->setModel('Genre');
    }

    /**
     * Gets a collection of Genre objects related by a many-to-many relationship
     * to the current object by way of the genre_publication cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Genre[] List of Genre objects
     */
    public function getGenres($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collGenres || null !== $criteria) {
            if ($this->isNew() && null === $this->collGenres) {
                // return empty collection
                $this->initGenres();
            } else {
                $collGenres = GenreQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collGenres;
                }
                $this->collGenres = $collGenres;
            }
        }

        return $this->collGenres;
    }

    /**
     * Sets a collection of Genre objects related by a many-to-many relationship
     * to the current object by way of the genre_publication cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $genres A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setGenres(PropelCollection $genres, PropelPDO $con = null)
    {
        $this->clearGenres();
        $currentGenres = $this->getGenres(null, $con);

        $this->genresScheduledForDeletion = $currentGenres->diff($genres);

        foreach ($genres as $genre) {
            if (!$currentGenres->contains($genre)) {
                $this->doAddGenre($genre);
            }
        }

        $this->collGenres = $genres;

        return $this;
    }

    /**
     * Gets the number of Genre objects related by a many-to-many relationship
     * to the current object by way of the genre_publication cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Genre objects
     */
    public function countGenres($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collGenres || null !== $criteria) {
            if ($this->isNew() && null === $this->collGenres) {
                return 0;
            } else {
                $query = GenreQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPublication($this)
                    ->count($con);
            }
        } else {
            return count($this->collGenres);
        }
    }

    /**
     * Associate a Genre object to this object
     * through the genre_publication cross reference table.
     *
     * @param  Genre $genre The GenrePublication object to relate
     * @return Publication The current object (for fluent API support)
     */
    public function addGenre(Genre $genre)
    {
        if ($this->collGenres === null) {
            $this->initGenres();
        }

        if (!$this->collGenres->contains($genre)) { // only add it if the **same** object is not already associated
            $this->doAddGenre($genre);
            $this->collGenres[] = $genre;

            if ($this->genresScheduledForDeletion and $this->genresScheduledForDeletion->contains($genre)) {
                $this->genresScheduledForDeletion->remove($this->genresScheduledForDeletion->search($genre));
            }
        }

        return $this;
    }

    /**
     * @param	Genre $genre The genre object to add.
     */
    protected function doAddGenre(Genre $genre)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$genre->getPublications()->contains($this)) {
            $genrePublication = new GenrePublication();
            $genrePublication->setGenre($genre);
            $this->addGenrePublication($genrePublication);

            $foreignCollection = $genre->getPublications();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Genre object to this object
     * through the genre_publication cross reference table.
     *
     * @param Genre $genre The GenrePublication object to relate
     * @return Publication The current object (for fluent API support)
     */
    public function removeGenre(Genre $genre)
    {
        if ($this->getGenres()->contains($genre)) {
            $this->collGenres->remove($this->collGenres->search($genre));
            if (null === $this->genresScheduledForDeletion) {
                $this->genresScheduledForDeletion = clone $this->collGenres;
                $this->genresScheduledForDeletion->clear();
            }
            $this->genresScheduledForDeletion[]= $genre;
        }

        return $this;
    }

    /**
     * Clears out the collTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addTags()
     */
    public function clearTags()
    {
        $this->collTags = null; // important to set this to null since that means it is uninitialized
        $this->collTagsPartial = null;

        return $this;
    }

    /**
     * Initializes the collTags collection.
     *
     * By default this just sets the collTags collection to an empty collection (like clearTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initTags()
    {
        $this->collTags = new PropelObjectCollection();
        $this->collTags->setModel('Tag');
    }

    /**
     * Gets a collection of Tag objects related by a many-to-many relationship
     * to the current object by way of the publication_tag cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Tag[] List of Tag objects
     */
    public function getTags($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collTags || null !== $criteria) {
            if ($this->isNew() && null === $this->collTags) {
                // return empty collection
                $this->initTags();
            } else {
                $collTags = TagQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collTags;
                }
                $this->collTags = $collTags;
            }
        }

        return $this->collTags;
    }

    /**
     * Sets a collection of Tag objects related by a many-to-many relationship
     * to the current object by way of the publication_tag cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $tags A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setTags(PropelCollection $tags, PropelPDO $con = null)
    {
        $this->clearTags();
        $currentTags = $this->getTags(null, $con);

        $this->tagsScheduledForDeletion = $currentTags->diff($tags);

        foreach ($tags as $tag) {
            if (!$currentTags->contains($tag)) {
                $this->doAddTag($tag);
            }
        }

        $this->collTags = $tags;

        return $this;
    }

    /**
     * Gets the number of Tag objects related by a many-to-many relationship
     * to the current object by way of the publication_tag cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Tag objects
     */
    public function countTags($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collTags || null !== $criteria) {
            if ($this->isNew() && null === $this->collTags) {
                return 0;
            } else {
                $query = TagQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPublication($this)
                    ->count($con);
            }
        } else {
            return count($this->collTags);
        }
    }

    /**
     * Associate a Tag object to this object
     * through the publication_tag cross reference table.
     *
     * @param  Tag $tag The PublicationTag object to relate
     * @return Publication The current object (for fluent API support)
     */
    public function addTag(Tag $tag)
    {
        if ($this->collTags === null) {
            $this->initTags();
        }

        if (!$this->collTags->contains($tag)) { // only add it if the **same** object is not already associated
            $this->doAddTag($tag);
            $this->collTags[] = $tag;

            if ($this->tagsScheduledForDeletion and $this->tagsScheduledForDeletion->contains($tag)) {
                $this->tagsScheduledForDeletion->remove($this->tagsScheduledForDeletion->search($tag));
            }
        }

        return $this;
    }

    /**
     * @param	Tag $tag The tag object to add.
     */
    protected function doAddTag(Tag $tag)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$tag->getPublications()->contains($this)) {
            $publicationTag = new PublicationTag();
            $publicationTag->setTag($tag);
            $this->addPublicationTag($publicationTag);

            $foreignCollection = $tag->getPublications();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Tag object to this object
     * through the publication_tag cross reference table.
     *
     * @param Tag $tag The PublicationTag object to relate
     * @return Publication The current object (for fluent API support)
     */
    public function removeTag(Tag $tag)
    {
        if ($this->getTags()->contains($tag)) {
            $this->collTags->remove($this->collTags->search($tag));
            if (null === $this->tagsScheduledForDeletion) {
                $this->tagsScheduledForDeletion = clone $this->collTags;
                $this->tagsScheduledForDeletion->clear();
            }
            $this->tagsScheduledForDeletion[]= $tag;
        }

        return $this;
    }

    /**
     * Clears out the collCategories collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addCategories()
     */
    public function clearCategories()
    {
        $this->collCategories = null; // important to set this to null since that means it is uninitialized
        $this->collCategoriesPartial = null;

        return $this;
    }

    /**
     * Initializes the collCategories collection.
     *
     * By default this just sets the collCategories collection to an empty collection (like clearCategories());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initCategories()
    {
        $this->collCategories = new PropelObjectCollection();
        $this->collCategories->setModel('Category');
    }

    /**
     * Gets a collection of Category objects related by a many-to-many relationship
     * to the current object by way of the category_publication cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Category[] List of Category objects
     */
    public function getCategories($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collCategories || null !== $criteria) {
            if ($this->isNew() && null === $this->collCategories) {
                // return empty collection
                $this->initCategories();
            } else {
                $collCategories = CategoryQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collCategories;
                }
                $this->collCategories = $collCategories;
            }
        }

        return $this->collCategories;
    }

    /**
     * Sets a collection of Category objects related by a many-to-many relationship
     * to the current object by way of the category_publication cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $categories A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setCategories(PropelCollection $categories, PropelPDO $con = null)
    {
        $this->clearCategories();
        $currentCategories = $this->getCategories(null, $con);

        $this->categoriesScheduledForDeletion = $currentCategories->diff($categories);

        foreach ($categories as $category) {
            if (!$currentCategories->contains($category)) {
                $this->doAddCategory($category);
            }
        }

        $this->collCategories = $categories;

        return $this;
    }

    /**
     * Gets the number of Category objects related by a many-to-many relationship
     * to the current object by way of the category_publication cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Category objects
     */
    public function countCategories($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collCategories || null !== $criteria) {
            if ($this->isNew() && null === $this->collCategories) {
                return 0;
            } else {
                $query = CategoryQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPublication($this)
                    ->count($con);
            }
        } else {
            return count($this->collCategories);
        }
    }

    /**
     * Associate a Category object to this object
     * through the category_publication cross reference table.
     *
     * @param  Category $category The CategoryPublication object to relate
     * @return Publication The current object (for fluent API support)
     */
    public function addCategory(Category $category)
    {
        if ($this->collCategories === null) {
            $this->initCategories();
        }

        if (!$this->collCategories->contains($category)) { // only add it if the **same** object is not already associated
            $this->doAddCategory($category);
            $this->collCategories[] = $category;

            if ($this->categoriesScheduledForDeletion and $this->categoriesScheduledForDeletion->contains($category)) {
                $this->categoriesScheduledForDeletion->remove($this->categoriesScheduledForDeletion->search($category));
            }
        }

        return $this;
    }

    /**
     * @param	Category $category The category object to add.
     */
    protected function doAddCategory(Category $category)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$category->getPublications()->contains($this)) {
            $categoryPublication = new CategoryPublication();
            $categoryPublication->setCategory($category);
            $this->addCategoryPublication($categoryPublication);

            $foreignCollection = $category->getPublications();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Category object to this object
     * through the category_publication cross reference table.
     *
     * @param Category $category The CategoryPublication object to relate
     * @return Publication The current object (for fluent API support)
     */
    public function removeCategory(Category $category)
    {
        if ($this->getCategories()->contains($category)) {
            $this->collCategories->remove($this->collCategories->search($category));
            if (null === $this->categoriesScheduledForDeletion) {
                $this->categoriesScheduledForDeletion = clone $this->collCategories;
                $this->categoriesScheduledForDeletion->clear();
            }
            $this->categoriesScheduledForDeletion[]= $category;
        }

        return $this;
    }

    /**
     * Clears out the collFonts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addFonts()
     */
    public function clearFonts()
    {
        $this->collFonts = null; // important to set this to null since that means it is uninitialized
        $this->collFontsPartial = null;

        return $this;
    }

    /**
     * Initializes the collFonts collection.
     *
     * By default this just sets the collFonts collection to an empty collection (like clearFonts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initFonts()
    {
        $this->collFonts = new PropelObjectCollection();
        $this->collFonts->setModel('Font');
    }

    /**
     * Gets a collection of Font objects related by a many-to-many relationship
     * to the current object by way of the font_publication cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Font[] List of Font objects
     */
    public function getFonts($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collFonts || null !== $criteria) {
            if ($this->isNew() && null === $this->collFonts) {
                // return empty collection
                $this->initFonts();
            } else {
                $collFonts = FontQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collFonts;
                }
                $this->collFonts = $collFonts;
            }
        }

        return $this->collFonts;
    }

    /**
     * Sets a collection of Font objects related by a many-to-many relationship
     * to the current object by way of the font_publication cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $fonts A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setFonts(PropelCollection $fonts, PropelPDO $con = null)
    {
        $this->clearFonts();
        $currentFonts = $this->getFonts(null, $con);

        $this->fontsScheduledForDeletion = $currentFonts->diff($fonts);

        foreach ($fonts as $font) {
            if (!$currentFonts->contains($font)) {
                $this->doAddFont($font);
            }
        }

        $this->collFonts = $fonts;

        return $this;
    }

    /**
     * Gets the number of Font objects related by a many-to-many relationship
     * to the current object by way of the font_publication cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Font objects
     */
    public function countFonts($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collFonts || null !== $criteria) {
            if ($this->isNew() && null === $this->collFonts) {
                return 0;
            } else {
                $query = FontQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPublication($this)
                    ->count($con);
            }
        } else {
            return count($this->collFonts);
        }
    }

    /**
     * Associate a Font object to this object
     * through the font_publication cross reference table.
     *
     * @param  Font $font The FontPublication object to relate
     * @return Publication The current object (for fluent API support)
     */
    public function addFont(Font $font)
    {
        if ($this->collFonts === null) {
            $this->initFonts();
        }

        if (!$this->collFonts->contains($font)) { // only add it if the **same** object is not already associated
            $this->doAddFont($font);
            $this->collFonts[] = $font;

            if ($this->fontsScheduledForDeletion and $this->fontsScheduledForDeletion->contains($font)) {
                $this->fontsScheduledForDeletion->remove($this->fontsScheduledForDeletion->search($font));
            }
        }

        return $this;
    }

    /**
     * @param	Font $font The font object to add.
     */
    protected function doAddFont(Font $font)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$font->getPublications()->contains($this)) {
            $fontPublication = new FontPublication();
            $fontPublication->setFont($font);
            $this->addFontPublication($fontPublication);

            $foreignCollection = $font->getPublications();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Font object to this object
     * through the font_publication cross reference table.
     *
     * @param Font $font The FontPublication object to relate
     * @return Publication The current object (for fluent API support)
     */
    public function removeFont(Font $font)
    {
        if ($this->getFonts()->contains($font)) {
            $this->collFonts->remove($this->collFonts->search($font));
            if (null === $this->fontsScheduledForDeletion) {
                $this->fontsScheduledForDeletion = clone $this->collFonts;
                $this->fontsScheduledForDeletion->clear();
            }
            $this->fontsScheduledForDeletion[]= $font;
        }

        return $this;
    }

    /**
     * Clears out the collPublicationgroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPublicationgroups()
     */
    public function clearPublicationgroups()
    {
        $this->collPublicationgroups = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationgroupsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPublicationgroups collection.
     *
     * By default this just sets the collPublicationgroups collection to an empty collection (like clearPublicationgroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPublicationgroups()
    {
        $this->collPublicationgroups = new PropelObjectCollection();
        $this->collPublicationgroups->setModel('Publicationgroup');
    }

    /**
     * Gets a collection of Publicationgroup objects related by a many-to-many relationship
     * to the current object by way of the publication_publicationgroup cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Publicationgroup[] List of Publicationgroup objects
     */
    public function getPublicationgroups($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPublicationgroups || null !== $criteria) {
            if ($this->isNew() && null === $this->collPublicationgroups) {
                // return empty collection
                $this->initPublicationgroups();
            } else {
                $collPublicationgroups = PublicationgroupQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPublicationgroups;
                }
                $this->collPublicationgroups = $collPublicationgroups;
            }
        }

        return $this->collPublicationgroups;
    }

    /**
     * Sets a collection of Publicationgroup objects related by a many-to-many relationship
     * to the current object by way of the publication_publicationgroup cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationgroups A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationgroups(PropelCollection $publicationgroups, PropelPDO $con = null)
    {
        $this->clearPublicationgroups();
        $currentPublicationgroups = $this->getPublicationgroups(null, $con);

        $this->publicationgroupsScheduledForDeletion = $currentPublicationgroups->diff($publicationgroups);

        foreach ($publicationgroups as $publicationgroup) {
            if (!$currentPublicationgroups->contains($publicationgroup)) {
                $this->doAddPublicationgroup($publicationgroup);
            }
        }

        $this->collPublicationgroups = $publicationgroups;

        return $this;
    }

    /**
     * Gets the number of Publicationgroup objects related by a many-to-many relationship
     * to the current object by way of the publication_publicationgroup cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Publicationgroup objects
     */
    public function countPublicationgroups($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPublicationgroups || null !== $criteria) {
            if ($this->isNew() && null === $this->collPublicationgroups) {
                return 0;
            } else {
                $query = PublicationgroupQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPublication($this)
                    ->count($con);
            }
        } else {
            return count($this->collPublicationgroups);
        }
    }

    /**
     * Associate a Publicationgroup object to this object
     * through the publication_publicationgroup cross reference table.
     *
     * @param  Publicationgroup $publicationgroup The PublicationPublicationgroup object to relate
     * @return Publication The current object (for fluent API support)
     */
    public function addPublicationgroup(Publicationgroup $publicationgroup)
    {
        if ($this->collPublicationgroups === null) {
            $this->initPublicationgroups();
        }

        if (!$this->collPublicationgroups->contains($publicationgroup)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationgroup($publicationgroup);
            $this->collPublicationgroups[] = $publicationgroup;

            if ($this->publicationgroupsScheduledForDeletion and $this->publicationgroupsScheduledForDeletion->contains($publicationgroup)) {
                $this->publicationgroupsScheduledForDeletion->remove($this->publicationgroupsScheduledForDeletion->search($publicationgroup));
            }
        }

        return $this;
    }

    /**
     * @param	Publicationgroup $publicationgroup The publicationgroup object to add.
     */
    protected function doAddPublicationgroup(Publicationgroup $publicationgroup)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$publicationgroup->getPublications()->contains($this)) {
            $publicationPublicationgroup = new PublicationPublicationgroup();
            $publicationPublicationgroup->setPublicationgroup($publicationgroup);
            $this->addPublicationPublicationgroup($publicationPublicationgroup);

            $foreignCollection = $publicationgroup->getPublications();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Publicationgroup object to this object
     * through the publication_publicationgroup cross reference table.
     *
     * @param Publicationgroup $publicationgroup The PublicationPublicationgroup object to relate
     * @return Publication The current object (for fluent API support)
     */
    public function removePublicationgroup(Publicationgroup $publicationgroup)
    {
        if ($this->getPublicationgroups()->contains($publicationgroup)) {
            $this->collPublicationgroups->remove($this->collPublicationgroups->search($publicationgroup));
            if (null === $this->publicationgroupsScheduledForDeletion) {
                $this->publicationgroupsScheduledForDeletion = clone $this->collPublicationgroups;
                $this->publicationgroupsScheduledForDeletion->clear();
            }
            $this->publicationgroupsScheduledForDeletion[]= $publicationgroup;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->type = null;
        $this->legacytype = null;
        $this->title_id = null;
        $this->firsteditionpublication_id = null;
        $this->place_id = null;
        $this->publicationdate_id = null;
        $this->creationdate_id = null;
        $this->publishingcompany_id = null;
        $this->source_id = null;
        $this->legacygenre = null;
        $this->legacysubgenre = null;
        $this->dirname = null;
        $this->usedcopylocation_id = null;
        $this->partner_id = null;
        $this->editiondescription = null;
        $this->digitaleditioneditor = null;
        $this->transcriptioncomment = null;
        $this->numpages = null;
        $this->numpagesnumeric = null;
        $this->comment = null;
        $this->encoding_comment = null;
        $this->doi = null;
        $this->format = null;
        $this->directoryname = null;
        $this->wwwready = null;
        $this->last_changed_by_user_id = null;
        $this->tree_id = null;
        $this->tree_left = null;
        $this->tree_right = null;
        $this->tree_level = null;
        $this->publishingcompany_id_is_reconstructed = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collMultiVolumes) {
                foreach ($this->collMultiVolumes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collVolumes) {
                foreach ($this->collVolumes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collChapters) {
                foreach ($this->collChapters as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collArticles) {
                foreach ($this->collArticles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSequenceEntries) {
                foreach ($this->collSequenceEntries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLanguagePublications) {
                foreach ($this->collLanguagePublications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGenrePublications) {
                foreach ($this->collGenrePublications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationTags) {
                foreach ($this->collPublicationTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCategoryPublications) {
                foreach ($this->collCategoryPublications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFontPublications) {
                foreach ($this->collFontPublications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationPublicationgroups) {
                foreach ($this->collPublicationPublicationgroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPersonPublications) {
                foreach ($this->collPersonPublications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRecentUses) {
                foreach ($this->collRecentUses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTasks) {
                foreach ($this->collTasks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCopyLocations) {
                foreach ($this->collCopyLocations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collImagesources) {
                foreach ($this->collImagesources as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTextsources) {
                foreach ($this->collTextsources as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLanguages) {
                foreach ($this->collLanguages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGenres) {
                foreach ($this->collGenres as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTags) {
                foreach ($this->collTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCategories) {
                foreach ($this->collCategories as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFonts) {
                foreach ($this->collFonts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationgroups) {
                foreach ($this->collPublicationgroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aTitle instanceof Persistent) {
              $this->aTitle->clearAllReferences($deep);
            }
            if ($this->aSource instanceof Persistent) {
              $this->aSource->clearAllReferences($deep);
            }
            if ($this->aPublishingcompany instanceof Persistent) {
              $this->aPublishingcompany->clearAllReferences($deep);
            }
            if ($this->aPlace instanceof Persistent) {
              $this->aPlace->clearAllReferences($deep);
            }
            if ($this->aDatespecificationRelatedByPublicationdateId instanceof Persistent) {
              $this->aDatespecificationRelatedByPublicationdateId->clearAllReferences($deep);
            }
            if ($this->aDatespecificationRelatedByCreationdateId instanceof Persistent) {
              $this->aDatespecificationRelatedByCreationdateId->clearAllReferences($deep);
            }
            if ($this->aLastChangedByUser instanceof Persistent) {
              $this->aLastChangedByUser->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        // nested_set behavior
        $this->collNestedSetChildren = null;
        $this->aNestedSetParent = null;
        if ($this->collMultiVolumes instanceof PropelCollection) {
            $this->collMultiVolumes->clearIterator();
        }
        $this->collMultiVolumes = null;
        if ($this->collVolumes instanceof PropelCollection) {
            $this->collVolumes->clearIterator();
        }
        $this->collVolumes = null;
        if ($this->collChapters instanceof PropelCollection) {
            $this->collChapters->clearIterator();
        }
        $this->collChapters = null;
        if ($this->collArticles instanceof PropelCollection) {
            $this->collArticles->clearIterator();
        }
        $this->collArticles = null;
        if ($this->collSequenceEntries instanceof PropelCollection) {
            $this->collSequenceEntries->clearIterator();
        }
        $this->collSequenceEntries = null;
        if ($this->collLanguagePublications instanceof PropelCollection) {
            $this->collLanguagePublications->clearIterator();
        }
        $this->collLanguagePublications = null;
        if ($this->collGenrePublications instanceof PropelCollection) {
            $this->collGenrePublications->clearIterator();
        }
        $this->collGenrePublications = null;
        if ($this->collPublicationTags instanceof PropelCollection) {
            $this->collPublicationTags->clearIterator();
        }
        $this->collPublicationTags = null;
        if ($this->collCategoryPublications instanceof PropelCollection) {
            $this->collCategoryPublications->clearIterator();
        }
        $this->collCategoryPublications = null;
        if ($this->collFontPublications instanceof PropelCollection) {
            $this->collFontPublications->clearIterator();
        }
        $this->collFontPublications = null;
        if ($this->collPublicationPublicationgroups instanceof PropelCollection) {
            $this->collPublicationPublicationgroups->clearIterator();
        }
        $this->collPublicationPublicationgroups = null;
        if ($this->collPersonPublications instanceof PropelCollection) {
            $this->collPersonPublications->clearIterator();
        }
        $this->collPersonPublications = null;
        if ($this->collRecentUses instanceof PropelCollection) {
            $this->collRecentUses->clearIterator();
        }
        $this->collRecentUses = null;
        if ($this->collTasks instanceof PropelCollection) {
            $this->collTasks->clearIterator();
        }
        $this->collTasks = null;
        if ($this->collCopyLocations instanceof PropelCollection) {
            $this->collCopyLocations->clearIterator();
        }
        $this->collCopyLocations = null;
        if ($this->collImagesources instanceof PropelCollection) {
            $this->collImagesources->clearIterator();
        }
        $this->collImagesources = null;
        if ($this->collTextsources instanceof PropelCollection) {
            $this->collTextsources->clearIterator();
        }
        $this->collTextsources = null;
        if ($this->collLanguages instanceof PropelCollection) {
            $this->collLanguages->clearIterator();
        }
        $this->collLanguages = null;
        if ($this->collGenres instanceof PropelCollection) {
            $this->collGenres->clearIterator();
        }
        $this->collGenres = null;
        if ($this->collTags instanceof PropelCollection) {
            $this->collTags->clearIterator();
        }
        $this->collTags = null;
        if ($this->collCategories instanceof PropelCollection) {
            $this->collCategories->clearIterator();
        }
        $this->collCategories = null;
        if ($this->collFonts instanceof PropelCollection) {
            $this->collFonts->clearIterator();
        }
        $this->collFonts = null;
        if ($this->collPublicationgroups instanceof PropelCollection) {
            $this->collPublicationgroups->clearIterator();
        }
        $this->collPublicationgroups = null;
        $this->aTitle = null;
        $this->aSource = null;
        $this->aPublishingcompany = null;
        $this->aPlace = null;
        $this->aDatespecificationRelatedByPublicationdateId = null;
        $this->aDatespecificationRelatedByCreationdateId = null;
        $this->aLastChangedByUser = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PublicationPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // nested_set behavior

    /**
     * Execute queries that were saved to be run inside the save transaction
     */
    protected function processNestedSetQueries($con)
    {
        foreach ($this->nestedSetQueries as $query) {
            $query['arguments'][]= $con;
            call_user_func_array($query['callable'], $query['arguments']);
        }
        $this->nestedSetQueries = array();
    }

    /**
     * Proxy getter method for the left value of the nested set model.
     * It provides a generic way to get the value, whatever the actual column name is.
     *
     * @return     int The nested set left value
     */
    public function getLeftValue()
    {
        return $this->tree_left;
    }

    /**
     * Proxy getter method for the right value of the nested set model.
     * It provides a generic way to get the value, whatever the actual column name is.
     *
     * @return     int The nested set right value
     */
    public function getRightValue()
    {
        return $this->tree_right;
    }

    /**
     * Proxy getter method for the level value of the nested set model.
     * It provides a generic way to get the value, whatever the actual column name is.
     *
     * @return     int The nested set level value
     */
    public function getLevel()
    {
        return $this->tree_level;
    }

    /**
     * Proxy getter method for the scope value of the nested set model.
     * It provides a generic way to get the value, whatever the actual column name is.
     *
     * @return     int The nested set scope value
     */
    public function getScopeValue()
    {
        return $this->tree_id;
    }

    /**
     * Proxy setter method for the left value of the nested set model.
     * It provides a generic way to set the value, whatever the actual column name is.
     *
     * @param      int $v The nested set left value
     * @return     Publication The current object (for fluent API support)
     */
    public function setLeftValue($v)
    {
        return $this->setTreeLeft($v);
    }

    /**
     * Proxy setter method for the right value of the nested set model.
     * It provides a generic way to set the value, whatever the actual column name is.
     *
     * @param      int $v The nested set right value
     * @return     Publication The current object (for fluent API support)
     */
    public function setRightValue($v)
    {
        return $this->setTreeRight($v);
    }

    /**
     * Proxy setter method for the level value of the nested set model.
     * It provides a generic way to set the value, whatever the actual column name is.
     *
     * @param      int $v The nested set level value
     * @return     Publication The current object (for fluent API support)
     */
    public function setLevel($v)
    {
        return $this->setTreeLevel($v);
    }

    /**
     * Proxy setter method for the scope value of the nested set model.
     * It provides a generic way to set the value, whatever the actual column name is.
     *
     * @param      int $v The nested set scope value
     * @return     Publication The current object (for fluent API support)
     */
    public function setScopeValue($v)
    {
        return $this->setTreeId($v);
    }

    /**
     * Creates the supplied node as the root node.
     *
     * @return     Publication The current object (for fluent API support)
     * @throws     PropelException
     */
    public function makeRoot()
    {
        if ($this->getLeftValue() || $this->getRightValue()) {
            throw new PropelException('Cannot turn an existing node into a root node.');
        }

        $this->setLeftValue(1);
        $this->setRightValue(2);
        $this->setLevel(0);

        return $this;
    }

    /**
     * Tests if onbject is a node, i.e. if it is inserted in the tree
     *
     * @return     bool
     */
    public function isInTree()
    {
        return $this->getLeftValue() > 0 && $this->getRightValue() > $this->getLeftValue();
    }

    /**
     * Tests if node is a root
     *
     * @return     bool
     */
    public function isRoot()
    {
        return $this->isInTree() && $this->getLeftValue() == 1;
    }

    /**
     * Tests if node is a leaf
     *
     * @return     bool
     */
    public function isLeaf()
    {
        return $this->isInTree() &&  ($this->getRightValue() - $this->getLeftValue()) == 1;
    }

    /**
     * Tests if node is a descendant of another node
     *
     * @param      Publication $node Propel node object
     * @return     bool
     */
    public function isDescendantOf($parent)
    {
        if ($this->getScopeValue() !== $parent->getScopeValue()) {
            return false; //since the `this` and $parent are in different scopes, there's no way that `this` is be a descendant of $parent.
        }

        return $this->isInTree() && $this->getLeftValue() > $parent->getLeftValue() && $this->getRightValue() < $parent->getRightValue();
    }

    /**
     * Tests if node is a ancestor of another node
     *
     * @param      Publication $node Propel node object
     * @return     bool
     */
    public function isAncestorOf($child)
    {
        return $child->isDescendantOf($this);
    }

    /**
     * Tests if object has an ancestor
     *
     * @param      PropelPDO $con Connection to use.
     * @return     bool
     */
    public function hasParent(PropelPDO $con = null)
    {
        return $this->getLevel() > 0;
    }

    /**
     * Sets the cache for parent node of the current object.
     * Warning: this does not move the current object in the tree.
     * Use moveTofirstChildOf() or moveToLastChildOf() for that purpose
     *
     * @param      Publication $parent
     * @return     Publication The current object, for fluid interface
     */
    public function setParent($parent = null)
    {
        $this->aNestedSetParent = $parent;

        return $this;
    }

    /**
     * Gets parent node for the current object if it exists
     * The result is cached so further calls to the same method don't issue any queries
     *
     * @param      PropelPDO $con Connection to use.
     * @return     mixed 		Propel object if exists else false
     */
    public function getParent(PropelPDO $con = null)
    {
        if ($this->aNestedSetParent === null && $this->hasParent()) {
            $this->aNestedSetParent = PublicationQuery::create()
                ->ancestorsOf($this)
                ->orderByLevel(true)
                ->findOne($con);
        }

        return $this->aNestedSetParent;
    }

    /**
     * Determines if the node has previous sibling
     *
     * @param      PropelPDO $con Connection to use.
     * @return     bool
     */
    public function hasPrevSibling(PropelPDO $con = null)
    {
        if (!PublicationPeer::isValid($this)) {
            return false;
        }

        return PublicationQuery::create()
            ->filterByTreeRight($this->getLeftValue() - 1)
            ->inTree($this->getScopeValue())
            ->count($con) > 0;
    }

    /**
     * Gets previous sibling for the given node if it exists
     *
     * @param      PropelPDO $con Connection to use.
     * @return     mixed 		Propel object if exists else false
     */
    public function getPrevSibling(PropelPDO $con = null)
    {
        return PublicationQuery::create()
            ->filterByTreeRight($this->getLeftValue() - 1)
            ->inTree($this->getScopeValue())
            ->findOne($con);
    }

    /**
     * Determines if the node has next sibling
     *
     * @param      PropelPDO $con Connection to use.
     * @return     bool
     */
    public function hasNextSibling(PropelPDO $con = null)
    {
        if (!PublicationPeer::isValid($this)) {
            return false;
        }

        return PublicationQuery::create()
            ->filterByTreeLeft($this->getRightValue() + 1)
            ->inTree($this->getScopeValue())
            ->count($con) > 0;
    }

    /**
     * Gets next sibling for the given node if it exists
     *
     * @param      PropelPDO $con Connection to use.
     * @return     mixed 		Propel object if exists else false
     */
    public function getNextSibling(PropelPDO $con = null)
    {
        return PublicationQuery::create()
            ->filterByTreeLeft($this->getRightValue() + 1)
            ->inTree($this->getScopeValue())
            ->findOne($con);
    }

    /**
     * Clears out the $collNestedSetChildren collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return     void
     */
    public function clearNestedSetChildren()
    {
        $this->collNestedSetChildren = null;
    }

    /**
     * Initializes the $collNestedSetChildren collection.
     *
     * @return     void
     */
    public function initNestedSetChildren()
    {
        $this->collNestedSetChildren = new PropelObjectCollection();
        $this->collNestedSetChildren->setModel('Publication');
    }

    /**
     * Adds an element to the internal $collNestedSetChildren collection.
     * Beware that this doesn't insert a node in the tree.
     * This method is only used to facilitate children hydration.
     *
     * @param      Publication $publication
     *
     * @return     void
     */
    public function addNestedSetChild($publication)
    {
        if ($this->collNestedSetChildren === null) {
            $this->initNestedSetChildren();
        }
        if (!in_array($publication, $this->collNestedSetChildren->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->collNestedSetChildren[]= $publication;
            $publication->setParent($this);
        }
    }

    /**
     * Tests if node has children
     *
     * @return     bool
     */
    public function hasChildren()
    {
        return ($this->getRightValue() - $this->getLeftValue()) > 1;
    }

    /**
     * Gets the children of the given node
     *
     * @param      Criteria  $criteria Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array     List of Publication objects
     */
    public function getChildren($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collNestedSetChildren || null !== $criteria) {
            if ($this->isLeaf() || ($this->isNew() && null === $this->collNestedSetChildren)) {
                // return empty collection
                $this->initNestedSetChildren();
            } else {
                $collNestedSetChildren = PublicationQuery::create(null, $criteria)
                  ->childrenOf($this)
                  ->orderByBranch()
                    ->find($con);
                if (null !== $criteria) {
                    return $collNestedSetChildren;
                }
                $this->collNestedSetChildren = $collNestedSetChildren;
            }
        }

        return $this->collNestedSetChildren;
    }

    /**
     * Gets number of children for the given node
     *
     * @param      Criteria  $criteria Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     int       Number of children
     */
    public function countChildren($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collNestedSetChildren || null !== $criteria) {
            if ($this->isLeaf() || ($this->isNew() && null === $this->collNestedSetChildren)) {
                return 0;
            } else {
                return PublicationQuery::create(null, $criteria)
                    ->childrenOf($this)
                    ->count($con);
            }
        } else {
            return count($this->collNestedSetChildren);
        }
    }

    /**
     * Gets the first child of the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array 		List of Publication objects
     */
    public function getFirstChild($query = null, PropelPDO $con = null)
    {
        if ($this->isLeaf()) {
            return array();
        } else {
            return PublicationQuery::create(null, $query)
                ->childrenOf($this)
                ->orderByBranch()
                ->findOne($con);
        }
    }

    /**
     * Gets the last child of the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array 		List of Publication objects
     */
    public function getLastChild($query = null, PropelPDO $con = null)
    {
        if ($this->isLeaf()) {
            return array();
        } else {
            return PublicationQuery::create(null, $query)
                ->childrenOf($this)
                ->orderByBranch(true)
                ->findOne($con);
        }
    }

    /**
     * Gets the siblings of the given node
     *
     * @param      bool			$includeNode Whether to include the current node or not
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     *
     * @return     array 		List of Publication objects
     */
    public function getSiblings($includeNode = false, $query = null, PropelPDO $con = null)
    {
        if ($this->isRoot()) {
            return array();
        } else {
             $query = PublicationQuery::create(null, $query)
                    ->childrenOf($this->getParent($con))
                    ->orderByBranch();
            if (!$includeNode) {
                $query->prune($this);
            }

            return $query->find($con);
        }
    }

    /**
     * Gets descendants for the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array 		List of Publication objects
     */
    public function getDescendants($query = null, PropelPDO $con = null)
    {
        if ($this->isLeaf()) {
            return array();
        } else {
            return PublicationQuery::create(null, $query)
                ->descendantsOf($this)
                ->orderByBranch()
                ->find($con);
        }
    }

    /**
     * Gets number of descendants for the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     int 		Number of descendants
     */
    public function countDescendants($query = null, PropelPDO $con = null)
    {
        if ($this->isLeaf()) {
            // save one query
            return 0;
        } else {
            return PublicationQuery::create(null, $query)
                ->descendantsOf($this)
                ->count($con);
        }
    }

    /**
     * Gets descendants for the given node, plus the current node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array 		List of Publication objects
     */
    public function getBranch($query = null, PropelPDO $con = null)
    {
        return PublicationQuery::create(null, $query)
            ->branchOf($this)
            ->orderByBranch()
            ->find($con);
    }

    /**
     * Gets ancestors for the given node, starting with the root node
     * Use it for breadcrumb paths for instance
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array 		List of Publication objects
     */
    public function getAncestors($query = null, PropelPDO $con = null)
    {
        if ($this->isRoot()) {
            // save one query
            return array();
        } else {
            return PublicationQuery::create(null, $query)
                ->ancestorsOf($this)
                ->orderByBranch()
                ->find($con);
        }
    }

    /**
     * Inserts the given $child node as first child of current
     * The modifications in the current object and the tree
     * are not persisted until the child object is saved.
     *
     * @param      Publication $child	Propel object for child node
     *
     * @return     Publication The current Propel object
     */
    public function addChild(Publication $child)
    {
        if ($this->isNew()) {
            throw new PropelException('A Publication object must not be new to accept children.');
        }
        $child->insertAsFirstChildOf($this);

        return $this;
    }

    /**
     * Inserts the current node as first child of given $parent node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param      Publication $parent	Propel object for parent node
     *
     * @return     Publication The current Propel object
     */
    public function insertAsFirstChildOf($parent)
    {
        if ($this->isInTree()) {
            throw new PropelException('A Publication object must not already be in the tree to be inserted. Use the moveToFirstChildOf() instead.');
        }
        $left = $parent->getLeftValue() + 1;
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($parent->getLevel() + 1);
        $scope = $parent->getScopeValue();
        $this->setScopeValue($scope);
        // update the children collection of the parent
        $parent->addNestedSetChild($this);

        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\\DTA\MetadataBundle\Model\Data\\PublicationPeer', 'makeRoomForLeaf'),
            'arguments' => array($left, $scope, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Inserts the current node as last child of given $parent node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param      Publication $parent	Propel object for parent node
     *
     * @return     Publication The current Propel object
     */
    public function insertAsLastChildOf($parent)
    {
        if ($this->isInTree()) {
            throw new PropelException('A Publication object must not already be in the tree to be inserted. Use the moveToLastChildOf() instead.');
        }
        $left = $parent->getRightValue();
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($parent->getLevel() + 1);
        $scope = $parent->getScopeValue();
        $this->setScopeValue($scope);
        // update the children collection of the parent
        $parent->addNestedSetChild($this);

        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\\DTA\MetadataBundle\Model\Data\\PublicationPeer', 'makeRoomForLeaf'),
            'arguments' => array($left, $scope, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Inserts the current node as prev sibling given $sibling node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param      Publication $sibling	Propel object for parent node
     *
     * @return     Publication The current Propel object
     */
    public function insertAsPrevSiblingOf($sibling)
    {
        if ($this->isInTree()) {
            throw new PropelException('A Publication object must not already be in the tree to be inserted. Use the moveToPrevSiblingOf() instead.');
        }
        $left = $sibling->getLeftValue();
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($sibling->getLevel());
        $scope = $sibling->getScopeValue();
        $this->setScopeValue($scope);
        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\\DTA\MetadataBundle\Model\Data\\PublicationPeer', 'makeRoomForLeaf'),
            'arguments' => array($left, $scope, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Inserts the current node as next sibling given $sibling node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param      Publication $sibling	Propel object for parent node
     *
     * @return     Publication The current Propel object
     */
    public function insertAsNextSiblingOf($sibling)
    {
        if ($this->isInTree()) {
            throw new PropelException('A Publication object must not already be in the tree to be inserted. Use the moveToNextSiblingOf() instead.');
        }
        $left = $sibling->getRightValue() + 1;
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($sibling->getLevel());
        $scope = $sibling->getScopeValue();
        $this->setScopeValue($scope);
        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\\DTA\MetadataBundle\Model\Data\\PublicationPeer', 'makeRoomForLeaf'),
            'arguments' => array($left, $scope, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Moves current node and its subtree to be the first child of $parent
     * The modifications in the current object and the tree are immediate
     *
     * @param      Publication $parent	Propel object for parent node
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     Publication The current Propel object
     */
    public function moveToFirstChildOf($parent, PropelPDO $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A Publication object must be already in the tree to be moved. Use the insertAsFirstChildOf() instead.');
        }
        if ($parent->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as child of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($parent->getLeftValue() + 1, $parent->getLevel() - $this->getLevel() + 1, $parent->getScopeValue(), $con);

        return $this;
    }

    /**
     * Moves current node and its subtree to be the last child of $parent
     * The modifications in the current object and the tree are immediate
     *
     * @param      Publication $parent	Propel object for parent node
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     Publication The current Propel object
     */
    public function moveToLastChildOf($parent, PropelPDO $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A Publication object must be already in the tree to be moved. Use the insertAsLastChildOf() instead.');
        }
        if ($parent->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as child of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($parent->getRightValue(), $parent->getLevel() - $this->getLevel() + 1, $parent->getScopeValue(), $con);

        return $this;
    }

    /**
     * Moves current node and its subtree to be the previous sibling of $sibling
     * The modifications in the current object and the tree are immediate
     *
     * @param      Publication $sibling	Propel object for sibling node
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     Publication The current Propel object
     */
    public function moveToPrevSiblingOf($sibling, PropelPDO $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A Publication object must be already in the tree to be moved. Use the insertAsPrevSiblingOf() instead.');
        }
        if ($sibling->isRoot()) {
            throw new PropelException('Cannot move to previous sibling of a root node.');
        }
        if ($sibling->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as sibling of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($sibling->getLeftValue(), $sibling->getLevel() - $this->getLevel(), $sibling->getScopeValue(), $con);

        return $this;
    }

    /**
     * Moves current node and its subtree to be the next sibling of $sibling
     * The modifications in the current object and the tree are immediate
     *
     * @param      Publication $sibling	Propel object for sibling node
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     Publication The current Propel object
     */
    public function moveToNextSiblingOf($sibling, PropelPDO $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A Publication object must be already in the tree to be moved. Use the insertAsNextSiblingOf() instead.');
        }
        if ($sibling->isRoot()) {
            throw new PropelException('Cannot move to next sibling of a root node.');
        }
        if ($sibling->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as sibling of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($sibling->getRightValue() + 1, $sibling->getLevel() - $this->getLevel(), $sibling->getScopeValue(), $con);

        return $this;
    }

    /**
     * Move current node and its children to location $destLeft and updates rest of tree
     *
     * @param      int	$destLeft Destination left value
     * @param      int	$levelDelta Delta to add to the levels
     * @param      PropelPDO $con		Connection to use.
     */
    protected function moveSubtreeTo($destLeft, $levelDelta, $targetScope = null, PropelPDO $con = null)
    {
        $preventDefault = false;
        $left  = $this->getLeftValue();
        $right = $this->getRightValue();
        $scope = $this->getScopeValue();

        if ($targetScope === null) {
            $targetScope = $scope;
        }


        $treeSize = $right - $left +1;

        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {

            // make room next to the target for the subtree
            PublicationPeer::shiftRLValues($treeSize, $destLeft, null, $targetScope, $con);



            if ($targetScope != $scope) {

                //move subtree to < 0, so the items are out of scope.
                PublicationPeer::shiftRLValues(-$right, $left, $right, $scope, $con);

                //update scopes
                PublicationPeer::setNegativeScope($targetScope, $con);

                //update levels
                PublicationPeer::shiftLevel($levelDelta, $left - $right, 0, $targetScope, $con);

                //move the subtree to the target
                PublicationPeer::shiftRLValues(($right - $left) + $destLeft, $left - $right, 0, $targetScope, $con);


                $preventDefault = true;
            }


            if (!$preventDefault) {


                if ($left >= $destLeft) { // src was shifted too?
                    $left += $treeSize;
                    $right += $treeSize;
                }

                if ($levelDelta) {
                    // update the levels of the subtree
                    PublicationPeer::shiftLevel($levelDelta, $left, $right, $scope, $con);
                }

                // move the subtree to the target
                PublicationPeer::shiftRLValues($destLeft - $left, $left, $right, $scope, $con);
            }

            // remove the empty room at the previous location of the subtree
            PublicationPeer::shiftRLValues(-$treeSize, $right + 1, null, $scope, $con);

            // update all loaded nodes
            PublicationPeer::updateLoadedNodes(null, $con);

            $con->commit();
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Deletes all descendants for the given node
     * Instance pooling is wiped out by this command,
     * so existing Publication instances are probably invalid (except for the current one)
     *
     * @param      PropelPDO $con Connection to use.
     *
     * @return     int 		number of deleted nodes
     */
    public function deleteDescendants(PropelPDO $con = null)
    {
        if ($this->isLeaf()) {
            // save one query
            return;
        }
        if ($con === null) {
            $con = Propel::getConnection(PublicationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $left = $this->getLeftValue();
        $right = $this->getRightValue();
        $scope = $this->getScopeValue();
        $con->beginTransaction();
        try {
            // delete descendant nodes (will empty the instance pool)
            $ret = PublicationQuery::create()
                ->descendantsOf($this)
                ->delete($con);

            // fill up the room that was used by descendants
            PublicationPeer::shiftRLValues($left - $right + 1, $right, null, $scope, $con);

            // fix the right value for the current node, which is now a leaf
            $this->setRightValue($left + 1);

            $con->commit();
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }

        return $ret;
    }

    /**
     * Returns a pre-order iterator for this node and its children.
     *
     * @return     RecursiveIterator
     */
    public function getIterator()
    {
        return new NestedSetRecursiveIterator($this);
    }

    // table_row_view behavior
    /**
     * To specify which columns are to be visible in the user display
     * (In the view that lists all database records of a class as a table)
     */
    public static function getTableViewColumnNames(){
        $rc = new \ReflectionClass(get_called_class());
        return $rc->getStaticPropertyValue("tableRowViewCaptions");
    }

    /**
     * To access the data using the specified column names.
     * @param string columnName
     */
    public function getAttributeByTableViewColumName($columnName){

        $accessor = $this->tableRowViewAccessors[$columnName];

        // don't use propel standard getters for user defined accessors
        // or for representative selector functions
        if(!strncmp($accessor, "accessor:", strlen("accessor:"))){
            $accessor = substr($accessor, strlen("accessor:"));
            return call_user_func(array($this, $accessor));
        } else {
            $result = $this->getByName($accessor, \BasePeer::TYPE_PHPNAME);
            if( is_a($result, 'DateTime') )
                $result = $result->format('d/m/Y');
            return $result;
        }
    }

    /**
     * @return The propel query object for retrieving the records.
     */
    public static function getRowViewQueryObject(){
        $rc = new \ReflectionClass(get_called_class());
        $queryConstructionString = $rc->getStaticPropertyValue("queryConstructionString");
        if($queryConstructionString === NULL){
            $classShortName = $rc->getShortName();
            $package = \DTA\MetadataBundle\Controller\ORMController::getPackageName($rc->getName());
            $queryClass = \DTA\MetadataBundle\Controller\ORMController::relatedClassNames($package, $classShortName)['query'];
            return new $queryClass;
        } else {
            return eval('return '.$queryConstructionString);
        }
    }


    // reconstructed_flaggable behavior
    /**
    * Returns all columns that can be flagged as reconstructed.
    */
    public function getReconstructedFlaggableColumns(){
        return array('PublishingcompanyId', );
    }

    /**
    * Returns whether a column is flagged as reconstructed.
    * @param $column the PHP name of the column as defined in the schema
    */
    public function isReconstructedByName($column){
        return $this->getByName($column."IsReconstructed");
    }

    /**
    * Returns the marked column value, e.g. in brackets to denote that the value is reconstructed.
    * e.g. getMarkedByName('name') return '[<name>]'.
    * @param $column the PHP name of the column as defined in the schema
    */
    public function getMarkedByName($column){
        if($this->isReconstructedByName($column))
            return '[' . $this->getByName($column) . ']';
        else
            return $this->getByName($column);
    }

    /**
    * Returns all columns that are flagged as reconstructed.
    */
    public function getReconstructedFlaggedColumns(){
        $flaggableColumns = $this->getReconstructedFlaggableColumns();
        $flaggedColumns = array();
        foreach($flaggableColumns as $column){
            if($this->isReconstructedByName($column))
            $flaggedColumns[] = $column;
        }
        return $flaggedColumns;
    }
    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     Publication The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PublicationPeer::UPDATED_AT;

        return $this;
    }

}
