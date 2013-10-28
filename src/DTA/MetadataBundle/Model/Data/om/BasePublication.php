<?php

namespace DTA\MetadataBundle\Model\Data\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use DTA\MetadataBundle\Model\Classification\Category;
use DTA\MetadataBundle\Model\Classification\CategoryQuery;
use DTA\MetadataBundle\Model\Classification\Genre;
use DTA\MetadataBundle\Model\Classification\GenreQuery;
use DTA\MetadataBundle\Model\Classification\Tag;
use DTA\MetadataBundle\Model\Classification\TagQuery;
use DTA\MetadataBundle\Model\Data\Datespecification;
use DTA\MetadataBundle\Model\Data\DatespecificationQuery;
use DTA\MetadataBundle\Model\Data\Font;
use DTA\MetadataBundle\Model\Data\FontQuery;
use DTA\MetadataBundle\Model\Data\Language;
use DTA\MetadataBundle\Model\Data\LanguageQuery;
use DTA\MetadataBundle\Model\Data\Place;
use DTA\MetadataBundle\Model\Data\PlaceQuery;
use DTA\MetadataBundle\Model\Data\Publication;
use DTA\MetadataBundle\Model\Data\PublicationDm;
use DTA\MetadataBundle\Model\Data\PublicationDmQuery;
use DTA\MetadataBundle\Model\Data\PublicationDs;
use DTA\MetadataBundle\Model\Data\PublicationDsQuery;
use DTA\MetadataBundle\Model\Data\PublicationJ;
use DTA\MetadataBundle\Model\Data\PublicationJQuery;
use DTA\MetadataBundle\Model\Data\PublicationJa;
use DTA\MetadataBundle\Model\Data\PublicationJaQuery;
use DTA\MetadataBundle\Model\Data\PublicationM;
use DTA\MetadataBundle\Model\Data\PublicationMQuery;
use DTA\MetadataBundle\Model\Data\PublicationMms;
use DTA\MetadataBundle\Model\Data\PublicationMmsQuery;
use DTA\MetadataBundle\Model\Data\PublicationMs;
use DTA\MetadataBundle\Model\Data\PublicationMsQuery;
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
     * The flag var to prevent infinit loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

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
     * The value for the legacy_book_id field.
     * @var        int
     */
    protected $legacy_book_id;

    /**
     * The value for the publishingcompany_id_is_reconstructed field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $publishingcompany_id_is_reconstructed;

    /**
     * @var        Title
     */
    protected $aTitle;

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
     * @var        PropelObjectCollection|PublicationM[] Collection to store aggregation of PublicationM objects.
     */
    protected $collPublicationMs;
    protected $collPublicationMsPartial;

    /**
     * @var        PropelObjectCollection|PublicationDm[] Collection to store aggregation of PublicationDm objects.
     */
    protected $collPublicationDms;
    protected $collPublicationDmsPartial;

    /**
     * @var        PropelObjectCollection|PublicationDs[] Collection to store aggregation of PublicationDs objects.
     */
    protected $collPublicationDss;
    protected $collPublicationDssPartial;

    /**
     * @var        PropelObjectCollection|PublicationMs[] Collection to store aggregation of PublicationMs objects.
     */
    protected $collPublicationMss;
    protected $collPublicationMssPartial;

    /**
     * @var        PropelObjectCollection|PublicationJa[] Collection to store aggregation of PublicationJa objects.
     */
    protected $collPublicationJas;
    protected $collPublicationJasPartial;

    /**
     * @var        PropelObjectCollection|PublicationMms[] Collection to store aggregation of PublicationMms objects.
     */
    protected $collPublicationMmss;
    protected $collPublicationMmssPartial;

    /**
     * @var        PropelObjectCollection|PublicationJ[] Collection to store aggregation of PublicationJ objects.
     */
    protected $collPublicationJs;
    protected $collPublicationJsPartial;

    /**
     * @var        PropelObjectCollection|Volume[] Collection to store aggregation of Volume objects.
     */
    protected $collVolumesRelatedByPublicationId;
    protected $collVolumesRelatedByPublicationIdPartial;

    /**
     * @var        PropelObjectCollection|Volume[] Collection to store aggregation of Volume objects.
     */
    protected $collVolumesRelatedByParentpublicationId;
    protected $collVolumesRelatedByParentpublicationIdPartial;

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
     * @var        PropelObjectCollection|Task[] Collection to store aggregation of Task objects.
     */
    protected $collTasks;
    protected $collTasksPartial;

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

    // table_row_view behavior
    public static $tableRowViewCaptions = array('Titel', 'erster Autor', 'entstanden', 'veröffentlicht', );	public   $tableRowViewAccessors = array('Titel'=>'accessor:getTitle', 'erster Autor'=>'accessor:getFirstAuthor', 'entstanden'=>'accessor:getDatespecification', 'veröffentlicht'=>'accessor:getDatespecificationRelatedByPublicationdateId', );
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
    protected $publicationMsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationDmsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationDssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationMssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationJasScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationMmssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationJsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $volumesRelatedByPublicationIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $volumesRelatedByParentpublicationIdScheduledForDeletion = null;

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
    protected $tasksScheduledForDeletion = null;

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
     * Get the [legacy_book_id] column value.
     * id_book des Datensatzes aus der alten Datenbank, der dem neuen Datensatz zugrundeliegt.
     * @return int
     */
    public function getLegacyBookId()
    {
        return $this->legacy_book_id;
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
     * Set the value of [id] column.
     *
     * @param int $v new value
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
     * Set the value of [title_id] column.
     *
     * @param int $v new value
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
     * @param int $v new value
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
     * @param int $v new value
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
     * @param int $v new value
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
     * @param int $v new value
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
     * @param int $v new value
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
     * Set the value of [partner_id] column.
     * akquiriert über
     * @param int $v new value
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
     * @param string $v new value
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
     * @param string $v new value
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
     * @param string $v new value
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
     * @param string $v new value
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
     * @param int $v new value
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
     * @param string $v new value
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
     * Set the value of [doi] column.
     *
     * @param string $v new value
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
     * @param string $v new value
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
     * @param string $v new value
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
     * @param int $v new value
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
     * Set the value of [legacy_book_id] column.
     * id_book des Datensatzes aus der alten Datenbank, der dem neuen Datensatz zugrundeliegt.
     * @param int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setLegacyBookId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->legacy_book_id !== $v) {
            $this->legacy_book_id = $v;
            $this->modifiedColumns[] = PublicationPeer::LEGACY_BOOK_ID;
        }


        return $this;
    } // setLegacyBookId()

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
     * @param int $startcol 0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->title_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->firsteditionpublication_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->place_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->publicationdate_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->creationdate_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->publishingcompany_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->partner_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->editiondescription = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->digitaleditioneditor = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->transcriptioncomment = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->numpages = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->numpagesnumeric = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->comment = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->doi = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
            $this->format = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->directoryname = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
            $this->wwwready = ($row[$startcol + 17] !== null) ? (int) $row[$startcol + 17] : null;
            $this->legacy_book_id = ($row[$startcol + 18] !== null) ? (int) $row[$startcol + 18] : null;
            $this->publishingcompany_id_is_reconstructed = ($row[$startcol + 19] !== null) ? (boolean) $row[$startcol + 19] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 20; // 20 = PublicationPeer::NUM_HYDRATE_COLUMNS.

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
            $this->aPublishingcompany = null;
            $this->aPlace = null;
            $this->aDatespecificationRelatedByPublicationdateId = null;
            $this->aDatespecificationRelatedByCreationdateId = null;
            $this->collPublicationMs = null;

            $this->collPublicationDms = null;

            $this->collPublicationDss = null;

            $this->collPublicationMss = null;

            $this->collPublicationJas = null;

            $this->collPublicationMmss = null;

            $this->collPublicationJs = null;

            $this->collVolumesRelatedByPublicationId = null;

            $this->collVolumesRelatedByParentpublicationId = null;

            $this->collLanguagePublications = null;

            $this->collGenrePublications = null;

            $this->collPublicationTags = null;

            $this->collCategoryPublications = null;

            $this->collFontPublications = null;

            $this->collPublicationPublicationgroups = null;

            $this->collPersonPublications = null;

            $this->collTasks = null;

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
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
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
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
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
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aTitle !== null) {
                if ($this->aTitle->isModified() || $this->aTitle->isNew()) {
                    $affectedRows += $this->aTitle->save($con);
                }
                $this->setTitle($this->aTitle);
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

            if ($this->publicationMsScheduledForDeletion !== null) {
                if (!$this->publicationMsScheduledForDeletion->isEmpty()) {
                    PublicationMQuery::create()
                        ->filterByPrimaryKeys($this->publicationMsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationMsScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationMs !== null) {
                foreach ($this->collPublicationMs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->publicationDmsScheduledForDeletion !== null) {
                if (!$this->publicationDmsScheduledForDeletion->isEmpty()) {
                    PublicationDmQuery::create()
                        ->filterByPrimaryKeys($this->publicationDmsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationDmsScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationDms !== null) {
                foreach ($this->collPublicationDms as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->publicationDssScheduledForDeletion !== null) {
                if (!$this->publicationDssScheduledForDeletion->isEmpty()) {
                    PublicationDsQuery::create()
                        ->filterByPrimaryKeys($this->publicationDssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationDssScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationDss !== null) {
                foreach ($this->collPublicationDss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->publicationMssScheduledForDeletion !== null) {
                if (!$this->publicationMssScheduledForDeletion->isEmpty()) {
                    PublicationMsQuery::create()
                        ->filterByPrimaryKeys($this->publicationMssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationMssScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationMss !== null) {
                foreach ($this->collPublicationMss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->publicationJasScheduledForDeletion !== null) {
                if (!$this->publicationJasScheduledForDeletion->isEmpty()) {
                    PublicationJaQuery::create()
                        ->filterByPrimaryKeys($this->publicationJasScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationJasScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationJas !== null) {
                foreach ($this->collPublicationJas as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->publicationMmssScheduledForDeletion !== null) {
                if (!$this->publicationMmssScheduledForDeletion->isEmpty()) {
                    PublicationMmsQuery::create()
                        ->filterByPrimaryKeys($this->publicationMmssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationMmssScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationMmss !== null) {
                foreach ($this->collPublicationMmss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->publicationJsScheduledForDeletion !== null) {
                if (!$this->publicationJsScheduledForDeletion->isEmpty()) {
                    PublicationJQuery::create()
                        ->filterByPrimaryKeys($this->publicationJsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationJsScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationJs !== null) {
                foreach ($this->collPublicationJs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->volumesRelatedByPublicationIdScheduledForDeletion !== null) {
                if (!$this->volumesRelatedByPublicationIdScheduledForDeletion->isEmpty()) {
                    VolumeQuery::create()
                        ->filterByPrimaryKeys($this->volumesRelatedByPublicationIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->volumesRelatedByPublicationIdScheduledForDeletion = null;
                }
            }

            if ($this->collVolumesRelatedByPublicationId !== null) {
                foreach ($this->collVolumesRelatedByPublicationId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->volumesRelatedByParentpublicationIdScheduledForDeletion !== null) {
                if (!$this->volumesRelatedByParentpublicationIdScheduledForDeletion->isEmpty()) {
                    VolumeQuery::create()
                        ->filterByPrimaryKeys($this->volumesRelatedByParentpublicationIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->volumesRelatedByParentpublicationIdScheduledForDeletion = null;
                }
            }

            if ($this->collVolumesRelatedByParentpublicationId !== null) {
                foreach ($this->collVolumesRelatedByParentpublicationId as $referrerFK) {
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

        $this->modifiedColumns[] = PublicationPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PublicationPeer::ID . ')');
        }
        if (null === $this->id) {
            try {
                $stmt = $con->query("SELECT nextval('publication_id_seq')");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $this->id = $row[0];
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PublicationPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '"id"';
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
        if ($this->isColumnModified(PublicationPeer::LEGACY_BOOK_ID)) {
            $modifiedColumns[':p' . $index++]  = '"legacy_book_id"';
        }
        if ($this->isColumnModified(PublicationPeer::PUBLISHINGCOMPANY_ID_IS_RECONSTRUCTED)) {
            $modifiedColumns[':p' . $index++]  = '"publishingcompany_id_is_reconstructed"';
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
                    case '"legacy_book_id"':
                        $stmt->bindValue($identifier, $this->legacy_book_id, PDO::PARAM_INT);
                        break;
                    case '"publishingcompany_id_is_reconstructed"':
                        $stmt->bindValue($identifier, $this->publishingcompany_id_is_reconstructed, PDO::PARAM_BOOL);
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
     * an aggreagated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aTitle !== null) {
                if (!$this->aTitle->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aTitle->getValidationFailures());
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


            if (($retval = PublicationPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collPublicationMs !== null) {
                    foreach ($this->collPublicationMs as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublicationDms !== null) {
                    foreach ($this->collPublicationDms as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublicationDss !== null) {
                    foreach ($this->collPublicationDss as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublicationMss !== null) {
                    foreach ($this->collPublicationMss as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublicationJas !== null) {
                    foreach ($this->collPublicationJas as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublicationMmss !== null) {
                    foreach ($this->collPublicationMmss as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublicationJs !== null) {
                    foreach ($this->collPublicationJs as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collVolumesRelatedByPublicationId !== null) {
                    foreach ($this->collVolumesRelatedByPublicationId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collVolumesRelatedByParentpublicationId !== null) {
                    foreach ($this->collVolumesRelatedByParentpublicationId as $referrerFK) {
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

                if ($this->collTasks !== null) {
                    foreach ($this->collTasks as $referrerFK) {
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
                return $this->getTitleId();
                break;
            case 2:
                return $this->getFirsteditionpublicationId();
                break;
            case 3:
                return $this->getPlaceId();
                break;
            case 4:
                return $this->getPublicationdateId();
                break;
            case 5:
                return $this->getCreationdateId();
                break;
            case 6:
                return $this->getPublishingcompanyId();
                break;
            case 7:
                return $this->getPartnerId();
                break;
            case 8:
                return $this->getEditiondescription();
                break;
            case 9:
                return $this->getDigitaleditioneditor();
                break;
            case 10:
                return $this->getTranscriptioncomment();
                break;
            case 11:
                return $this->getNumpages();
                break;
            case 12:
                return $this->getNumpagesnumeric();
                break;
            case 13:
                return $this->getComment();
                break;
            case 14:
                return $this->getDoi();
                break;
            case 15:
                return $this->getFormat();
                break;
            case 16:
                return $this->getDirectoryname();
                break;
            case 17:
                return $this->getWwwready();
                break;
            case 18:
                return $this->getLegacyBookId();
                break;
            case 19:
                return $this->getPublishingcompanyIdIsReconstructed();
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
            $keys[1] => $this->getTitleId(),
            $keys[2] => $this->getFirsteditionpublicationId(),
            $keys[3] => $this->getPlaceId(),
            $keys[4] => $this->getPublicationdateId(),
            $keys[5] => $this->getCreationdateId(),
            $keys[6] => $this->getPublishingcompanyId(),
            $keys[7] => $this->getPartnerId(),
            $keys[8] => $this->getEditiondescription(),
            $keys[9] => $this->getDigitaleditioneditor(),
            $keys[10] => $this->getTranscriptioncomment(),
            $keys[11] => $this->getNumpages(),
            $keys[12] => $this->getNumpagesnumeric(),
            $keys[13] => $this->getComment(),
            $keys[14] => $this->getDoi(),
            $keys[15] => $this->getFormat(),
            $keys[16] => $this->getDirectoryname(),
            $keys[17] => $this->getWwwready(),
            $keys[18] => $this->getLegacyBookId(),
            $keys[19] => $this->getPublishingcompanyIdIsReconstructed(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aTitle) {
                $result['Title'] = $this->aTitle->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
            if (null !== $this->collPublicationMs) {
                $result['PublicationMs'] = $this->collPublicationMs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationDms) {
                $result['PublicationDms'] = $this->collPublicationDms->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationDss) {
                $result['PublicationDss'] = $this->collPublicationDss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationMss) {
                $result['PublicationMss'] = $this->collPublicationMss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationJas) {
                $result['PublicationJas'] = $this->collPublicationJas->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationMmss) {
                $result['PublicationMmss'] = $this->collPublicationMmss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationJs) {
                $result['PublicationJs'] = $this->collPublicationJs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collVolumesRelatedByPublicationId) {
                $result['VolumesRelatedByPublicationId'] = $this->collVolumesRelatedByPublicationId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collVolumesRelatedByParentpublicationId) {
                $result['VolumesRelatedByParentpublicationId'] = $this->collVolumesRelatedByParentpublicationId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collTasks) {
                $result['Tasks'] = $this->collTasks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $this->setTitleId($value);
                break;
            case 2:
                $this->setFirsteditionpublicationId($value);
                break;
            case 3:
                $this->setPlaceId($value);
                break;
            case 4:
                $this->setPublicationdateId($value);
                break;
            case 5:
                $this->setCreationdateId($value);
                break;
            case 6:
                $this->setPublishingcompanyId($value);
                break;
            case 7:
                $this->setPartnerId($value);
                break;
            case 8:
                $this->setEditiondescription($value);
                break;
            case 9:
                $this->setDigitaleditioneditor($value);
                break;
            case 10:
                $this->setTranscriptioncomment($value);
                break;
            case 11:
                $this->setNumpages($value);
                break;
            case 12:
                $this->setNumpagesnumeric($value);
                break;
            case 13:
                $this->setComment($value);
                break;
            case 14:
                $this->setDoi($value);
                break;
            case 15:
                $this->setFormat($value);
                break;
            case 16:
                $this->setDirectoryname($value);
                break;
            case 17:
                $this->setWwwready($value);
                break;
            case 18:
                $this->setLegacyBookId($value);
                break;
            case 19:
                $this->setPublishingcompanyIdIsReconstructed($value);
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
        if (array_key_exists($keys[1], $arr)) $this->setTitleId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setFirsteditionpublicationId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPlaceId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setPublicationdateId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setCreationdateId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setPublishingcompanyId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setPartnerId($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setEditiondescription($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setDigitaleditioneditor($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setTranscriptioncomment($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setNumpages($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setNumpagesnumeric($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setComment($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setDoi($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setFormat($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setDirectoryname($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setWwwready($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setLegacyBookId($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setPublishingcompanyIdIsReconstructed($arr[$keys[19]]);
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
        if ($this->isColumnModified(PublicationPeer::TITLE_ID)) $criteria->add(PublicationPeer::TITLE_ID, $this->title_id);
        if ($this->isColumnModified(PublicationPeer::FIRSTEDITIONPUBLICATION_ID)) $criteria->add(PublicationPeer::FIRSTEDITIONPUBLICATION_ID, $this->firsteditionpublication_id);
        if ($this->isColumnModified(PublicationPeer::PLACE_ID)) $criteria->add(PublicationPeer::PLACE_ID, $this->place_id);
        if ($this->isColumnModified(PublicationPeer::PUBLICATIONDATE_ID)) $criteria->add(PublicationPeer::PUBLICATIONDATE_ID, $this->publicationdate_id);
        if ($this->isColumnModified(PublicationPeer::CREATIONDATE_ID)) $criteria->add(PublicationPeer::CREATIONDATE_ID, $this->creationdate_id);
        if ($this->isColumnModified(PublicationPeer::PUBLISHINGCOMPANY_ID)) $criteria->add(PublicationPeer::PUBLISHINGCOMPANY_ID, $this->publishingcompany_id);
        if ($this->isColumnModified(PublicationPeer::PARTNER_ID)) $criteria->add(PublicationPeer::PARTNER_ID, $this->partner_id);
        if ($this->isColumnModified(PublicationPeer::EDITIONDESCRIPTION)) $criteria->add(PublicationPeer::EDITIONDESCRIPTION, $this->editiondescription);
        if ($this->isColumnModified(PublicationPeer::DIGITALEDITIONEDITOR)) $criteria->add(PublicationPeer::DIGITALEDITIONEDITOR, $this->digitaleditioneditor);
        if ($this->isColumnModified(PublicationPeer::TRANSCRIPTIONCOMMENT)) $criteria->add(PublicationPeer::TRANSCRIPTIONCOMMENT, $this->transcriptioncomment);
        if ($this->isColumnModified(PublicationPeer::NUMPAGES)) $criteria->add(PublicationPeer::NUMPAGES, $this->numpages);
        if ($this->isColumnModified(PublicationPeer::NUMPAGESNUMERIC)) $criteria->add(PublicationPeer::NUMPAGESNUMERIC, $this->numpagesnumeric);
        if ($this->isColumnModified(PublicationPeer::COMMENT)) $criteria->add(PublicationPeer::COMMENT, $this->comment);
        if ($this->isColumnModified(PublicationPeer::DOI)) $criteria->add(PublicationPeer::DOI, $this->doi);
        if ($this->isColumnModified(PublicationPeer::FORMAT)) $criteria->add(PublicationPeer::FORMAT, $this->format);
        if ($this->isColumnModified(PublicationPeer::DIRECTORYNAME)) $criteria->add(PublicationPeer::DIRECTORYNAME, $this->directoryname);
        if ($this->isColumnModified(PublicationPeer::WWWREADY)) $criteria->add(PublicationPeer::WWWREADY, $this->wwwready);
        if ($this->isColumnModified(PublicationPeer::LEGACY_BOOK_ID)) $criteria->add(PublicationPeer::LEGACY_BOOK_ID, $this->legacy_book_id);
        if ($this->isColumnModified(PublicationPeer::PUBLISHINGCOMPANY_ID_IS_RECONSTRUCTED)) $criteria->add(PublicationPeer::PUBLISHINGCOMPANY_ID_IS_RECONSTRUCTED, $this->publishingcompany_id_is_reconstructed);

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
        $copyObj->setTitleId($this->getTitleId());
        $copyObj->setFirsteditionpublicationId($this->getFirsteditionpublicationId());
        $copyObj->setPlaceId($this->getPlaceId());
        $copyObj->setPublicationdateId($this->getPublicationdateId());
        $copyObj->setCreationdateId($this->getCreationdateId());
        $copyObj->setPublishingcompanyId($this->getPublishingcompanyId());
        $copyObj->setPartnerId($this->getPartnerId());
        $copyObj->setEditiondescription($this->getEditiondescription());
        $copyObj->setDigitaleditioneditor($this->getDigitaleditioneditor());
        $copyObj->setTranscriptioncomment($this->getTranscriptioncomment());
        $copyObj->setNumpages($this->getNumpages());
        $copyObj->setNumpagesnumeric($this->getNumpagesnumeric());
        $copyObj->setComment($this->getComment());
        $copyObj->setDoi($this->getDoi());
        $copyObj->setFormat($this->getFormat());
        $copyObj->setDirectoryname($this->getDirectoryname());
        $copyObj->setWwwready($this->getWwwready());
        $copyObj->setLegacyBookId($this->getLegacyBookId());
        $copyObj->setPublishingcompanyIdIsReconstructed($this->getPublishingcompanyIdIsReconstructed());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPublicationMs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationM($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationDms() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationDm($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationDss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationDs($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationMss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationMs($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationJas() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationJa($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationMmss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationMms($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationJs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationJ($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getVolumesRelatedByPublicationId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addVolumeRelatedByPublicationId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getVolumesRelatedByParentpublicationId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addVolumeRelatedByParentpublicationId($relObj->copy($deepCopy));
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

            foreach ($this->getTasks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTask($relObj->copy($deepCopy));
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
     * @param             Title $v
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
     * Declares an association between this object and a Publishingcompany object.
     *
     * @param             Publishingcompany $v
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
     * @param             Place $v
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
     * @param             Datespecification $v
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
     * @param             Datespecification $v
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('PublicationM' == $relationName) {
            $this->initPublicationMs();
        }
        if ('PublicationDm' == $relationName) {
            $this->initPublicationDms();
        }
        if ('PublicationDs' == $relationName) {
            $this->initPublicationDss();
        }
        if ('PublicationMs' == $relationName) {
            $this->initPublicationMss();
        }
        if ('PublicationJa' == $relationName) {
            $this->initPublicationJas();
        }
        if ('PublicationMms' == $relationName) {
            $this->initPublicationMmss();
        }
        if ('PublicationJ' == $relationName) {
            $this->initPublicationJs();
        }
        if ('VolumeRelatedByPublicationId' == $relationName) {
            $this->initVolumesRelatedByPublicationId();
        }
        if ('VolumeRelatedByParentpublicationId' == $relationName) {
            $this->initVolumesRelatedByParentpublicationId();
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
        if ('Task' == $relationName) {
            $this->initTasks();
        }
        if ('Imagesource' == $relationName) {
            $this->initImagesources();
        }
        if ('Textsource' == $relationName) {
            $this->initTextsources();
        }
    }

    /**
     * Clears out the collPublicationMs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPublicationMs()
     */
    public function clearPublicationMs()
    {
        $this->collPublicationMs = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationMsPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationMs collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationMs($v = true)
    {
        $this->collPublicationMsPartial = $v;
    }

    /**
     * Initializes the collPublicationMs collection.
     *
     * By default this just sets the collPublicationMs collection to an empty array (like clearcollPublicationMs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationMs($overrideExisting = true)
    {
        if (null !== $this->collPublicationMs && !$overrideExisting) {
            return;
        }
        $this->collPublicationMs = new PropelObjectCollection();
        $this->collPublicationMs->setModel('PublicationM');
    }

    /**
     * Gets an array of PublicationM objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PublicationM[] List of PublicationM objects
     * @throws PropelException
     */
    public function getPublicationMs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationMsPartial && !$this->isNew();
        if (null === $this->collPublicationMs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationMs) {
                // return empty collection
                $this->initPublicationMs();
            } else {
                $collPublicationMs = PublicationMQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationMsPartial && count($collPublicationMs)) {
                      $this->initPublicationMs(false);

                      foreach($collPublicationMs as $obj) {
                        if (false == $this->collPublicationMs->contains($obj)) {
                          $this->collPublicationMs->append($obj);
                        }
                      }

                      $this->collPublicationMsPartial = true;
                    }

                    $collPublicationMs->getInternalIterator()->rewind();
                    return $collPublicationMs;
                }

                if($partial && $this->collPublicationMs) {
                    foreach($this->collPublicationMs as $obj) {
                        if($obj->isNew()) {
                            $collPublicationMs[] = $obj;
                        }
                    }
                }

                $this->collPublicationMs = $collPublicationMs;
                $this->collPublicationMsPartial = false;
            }
        }

        return $this->collPublicationMs;
    }

    /**
     * Sets a collection of PublicationM objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationMs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationMs(PropelCollection $publicationMs, PropelPDO $con = null)
    {
        $publicationMsToDelete = $this->getPublicationMs(new Criteria(), $con)->diff($publicationMs);

        $this->publicationMsScheduledForDeletion = unserialize(serialize($publicationMsToDelete));

        foreach ($publicationMsToDelete as $publicationMRemoved) {
            $publicationMRemoved->setPublication(null);
        }

        $this->collPublicationMs = null;
        foreach ($publicationMs as $publicationM) {
            $this->addPublicationM($publicationM);
        }

        $this->collPublicationMs = $publicationMs;
        $this->collPublicationMsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PublicationM objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PublicationM objects.
     * @throws PropelException
     */
    public function countPublicationMs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationMsPartial && !$this->isNew();
        if (null === $this->collPublicationMs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationMs) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationMs());
            }
            $query = PublicationMQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collPublicationMs);
    }

    /**
     * Method called to associate a PublicationM object to this object
     * through the PublicationM foreign key attribute.
     *
     * @param    PublicationM $l PublicationM
     * @return Publication The current object (for fluent API support)
     */
    public function addPublicationM(PublicationM $l)
    {
        if ($this->collPublicationMs === null) {
            $this->initPublicationMs();
            $this->collPublicationMsPartial = true;
        }
        if (!in_array($l, $this->collPublicationMs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationM($l);
        }

        return $this;
    }

    /**
     * @param	PublicationM $publicationM The publicationM object to add.
     */
    protected function doAddPublicationM($publicationM)
    {
        $this->collPublicationMs[]= $publicationM;
        $publicationM->setPublication($this);
    }

    /**
     * @param	PublicationM $publicationM The publicationM object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removePublicationM($publicationM)
    {
        if ($this->getPublicationMs()->contains($publicationM)) {
            $this->collPublicationMs->remove($this->collPublicationMs->search($publicationM));
            if (null === $this->publicationMsScheduledForDeletion) {
                $this->publicationMsScheduledForDeletion = clone $this->collPublicationMs;
                $this->publicationMsScheduledForDeletion->clear();
            }
            $this->publicationMsScheduledForDeletion[]= clone $publicationM;
            $publicationM->setPublication(null);
        }

        return $this;
    }

    /**
     * Clears out the collPublicationDms collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPublicationDms()
     */
    public function clearPublicationDms()
    {
        $this->collPublicationDms = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationDmsPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationDms collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationDms($v = true)
    {
        $this->collPublicationDmsPartial = $v;
    }

    /**
     * Initializes the collPublicationDms collection.
     *
     * By default this just sets the collPublicationDms collection to an empty array (like clearcollPublicationDms());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationDms($overrideExisting = true)
    {
        if (null !== $this->collPublicationDms && !$overrideExisting) {
            return;
        }
        $this->collPublicationDms = new PropelObjectCollection();
        $this->collPublicationDms->setModel('PublicationDm');
    }

    /**
     * Gets an array of PublicationDm objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PublicationDm[] List of PublicationDm objects
     * @throws PropelException
     */
    public function getPublicationDms($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationDmsPartial && !$this->isNew();
        if (null === $this->collPublicationDms || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationDms) {
                // return empty collection
                $this->initPublicationDms();
            } else {
                $collPublicationDms = PublicationDmQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationDmsPartial && count($collPublicationDms)) {
                      $this->initPublicationDms(false);

                      foreach($collPublicationDms as $obj) {
                        if (false == $this->collPublicationDms->contains($obj)) {
                          $this->collPublicationDms->append($obj);
                        }
                      }

                      $this->collPublicationDmsPartial = true;
                    }

                    $collPublicationDms->getInternalIterator()->rewind();
                    return $collPublicationDms;
                }

                if($partial && $this->collPublicationDms) {
                    foreach($this->collPublicationDms as $obj) {
                        if($obj->isNew()) {
                            $collPublicationDms[] = $obj;
                        }
                    }
                }

                $this->collPublicationDms = $collPublicationDms;
                $this->collPublicationDmsPartial = false;
            }
        }

        return $this->collPublicationDms;
    }

    /**
     * Sets a collection of PublicationDm objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationDms A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationDms(PropelCollection $publicationDms, PropelPDO $con = null)
    {
        $publicationDmsToDelete = $this->getPublicationDms(new Criteria(), $con)->diff($publicationDms);

        $this->publicationDmsScheduledForDeletion = unserialize(serialize($publicationDmsToDelete));

        foreach ($publicationDmsToDelete as $publicationDmRemoved) {
            $publicationDmRemoved->setPublication(null);
        }

        $this->collPublicationDms = null;
        foreach ($publicationDms as $publicationDm) {
            $this->addPublicationDm($publicationDm);
        }

        $this->collPublicationDms = $publicationDms;
        $this->collPublicationDmsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PublicationDm objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PublicationDm objects.
     * @throws PropelException
     */
    public function countPublicationDms(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationDmsPartial && !$this->isNew();
        if (null === $this->collPublicationDms || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationDms) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationDms());
            }
            $query = PublicationDmQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collPublicationDms);
    }

    /**
     * Method called to associate a PublicationDm object to this object
     * through the PublicationDm foreign key attribute.
     *
     * @param    PublicationDm $l PublicationDm
     * @return Publication The current object (for fluent API support)
     */
    public function addPublicationDm(PublicationDm $l)
    {
        if ($this->collPublicationDms === null) {
            $this->initPublicationDms();
            $this->collPublicationDmsPartial = true;
        }
        if (!in_array($l, $this->collPublicationDms->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationDm($l);
        }

        return $this;
    }

    /**
     * @param	PublicationDm $publicationDm The publicationDm object to add.
     */
    protected function doAddPublicationDm($publicationDm)
    {
        $this->collPublicationDms[]= $publicationDm;
        $publicationDm->setPublication($this);
    }

    /**
     * @param	PublicationDm $publicationDm The publicationDm object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removePublicationDm($publicationDm)
    {
        if ($this->getPublicationDms()->contains($publicationDm)) {
            $this->collPublicationDms->remove($this->collPublicationDms->search($publicationDm));
            if (null === $this->publicationDmsScheduledForDeletion) {
                $this->publicationDmsScheduledForDeletion = clone $this->collPublicationDms;
                $this->publicationDmsScheduledForDeletion->clear();
            }
            $this->publicationDmsScheduledForDeletion[]= clone $publicationDm;
            $publicationDm->setPublication(null);
        }

        return $this;
    }

    /**
     * Clears out the collPublicationDss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPublicationDss()
     */
    public function clearPublicationDss()
    {
        $this->collPublicationDss = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationDssPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationDss collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationDss($v = true)
    {
        $this->collPublicationDssPartial = $v;
    }

    /**
     * Initializes the collPublicationDss collection.
     *
     * By default this just sets the collPublicationDss collection to an empty array (like clearcollPublicationDss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationDss($overrideExisting = true)
    {
        if (null !== $this->collPublicationDss && !$overrideExisting) {
            return;
        }
        $this->collPublicationDss = new PropelObjectCollection();
        $this->collPublicationDss->setModel('PublicationDs');
    }

    /**
     * Gets an array of PublicationDs objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PublicationDs[] List of PublicationDs objects
     * @throws PropelException
     */
    public function getPublicationDss($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationDssPartial && !$this->isNew();
        if (null === $this->collPublicationDss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationDss) {
                // return empty collection
                $this->initPublicationDss();
            } else {
                $collPublicationDss = PublicationDsQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationDssPartial && count($collPublicationDss)) {
                      $this->initPublicationDss(false);

                      foreach($collPublicationDss as $obj) {
                        if (false == $this->collPublicationDss->contains($obj)) {
                          $this->collPublicationDss->append($obj);
                        }
                      }

                      $this->collPublicationDssPartial = true;
                    }

                    $collPublicationDss->getInternalIterator()->rewind();
                    return $collPublicationDss;
                }

                if($partial && $this->collPublicationDss) {
                    foreach($this->collPublicationDss as $obj) {
                        if($obj->isNew()) {
                            $collPublicationDss[] = $obj;
                        }
                    }
                }

                $this->collPublicationDss = $collPublicationDss;
                $this->collPublicationDssPartial = false;
            }
        }

        return $this->collPublicationDss;
    }

    /**
     * Sets a collection of PublicationDs objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationDss A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationDss(PropelCollection $publicationDss, PropelPDO $con = null)
    {
        $publicationDssToDelete = $this->getPublicationDss(new Criteria(), $con)->diff($publicationDss);

        $this->publicationDssScheduledForDeletion = unserialize(serialize($publicationDssToDelete));

        foreach ($publicationDssToDelete as $publicationDsRemoved) {
            $publicationDsRemoved->setPublication(null);
        }

        $this->collPublicationDss = null;
        foreach ($publicationDss as $publicationDs) {
            $this->addPublicationDs($publicationDs);
        }

        $this->collPublicationDss = $publicationDss;
        $this->collPublicationDssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PublicationDs objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PublicationDs objects.
     * @throws PropelException
     */
    public function countPublicationDss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationDssPartial && !$this->isNew();
        if (null === $this->collPublicationDss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationDss) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationDss());
            }
            $query = PublicationDsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collPublicationDss);
    }

    /**
     * Method called to associate a PublicationDs object to this object
     * through the PublicationDs foreign key attribute.
     *
     * @param    PublicationDs $l PublicationDs
     * @return Publication The current object (for fluent API support)
     */
    public function addPublicationDs(PublicationDs $l)
    {
        if ($this->collPublicationDss === null) {
            $this->initPublicationDss();
            $this->collPublicationDssPartial = true;
        }
        if (!in_array($l, $this->collPublicationDss->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationDs($l);
        }

        return $this;
    }

    /**
     * @param	PublicationDs $publicationDs The publicationDs object to add.
     */
    protected function doAddPublicationDs($publicationDs)
    {
        $this->collPublicationDss[]= $publicationDs;
        $publicationDs->setPublication($this);
    }

    /**
     * @param	PublicationDs $publicationDs The publicationDs object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removePublicationDs($publicationDs)
    {
        if ($this->getPublicationDss()->contains($publicationDs)) {
            $this->collPublicationDss->remove($this->collPublicationDss->search($publicationDs));
            if (null === $this->publicationDssScheduledForDeletion) {
                $this->publicationDssScheduledForDeletion = clone $this->collPublicationDss;
                $this->publicationDssScheduledForDeletion->clear();
            }
            $this->publicationDssScheduledForDeletion[]= clone $publicationDs;
            $publicationDs->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related PublicationDss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationDs[] List of PublicationDs objects
     */
    public function getPublicationDssJoinSeries($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationDsQuery::create(null, $criteria);
        $query->joinWith('Series', $join_behavior);

        return $this->getPublicationDss($query, $con);
    }

    /**
     * Clears out the collPublicationMss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPublicationMss()
     */
    public function clearPublicationMss()
    {
        $this->collPublicationMss = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationMssPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationMss collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationMss($v = true)
    {
        $this->collPublicationMssPartial = $v;
    }

    /**
     * Initializes the collPublicationMss collection.
     *
     * By default this just sets the collPublicationMss collection to an empty array (like clearcollPublicationMss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationMss($overrideExisting = true)
    {
        if (null !== $this->collPublicationMss && !$overrideExisting) {
            return;
        }
        $this->collPublicationMss = new PropelObjectCollection();
        $this->collPublicationMss->setModel('PublicationMs');
    }

    /**
     * Gets an array of PublicationMs objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PublicationMs[] List of PublicationMs objects
     * @throws PropelException
     */
    public function getPublicationMss($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationMssPartial && !$this->isNew();
        if (null === $this->collPublicationMss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationMss) {
                // return empty collection
                $this->initPublicationMss();
            } else {
                $collPublicationMss = PublicationMsQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationMssPartial && count($collPublicationMss)) {
                      $this->initPublicationMss(false);

                      foreach($collPublicationMss as $obj) {
                        if (false == $this->collPublicationMss->contains($obj)) {
                          $this->collPublicationMss->append($obj);
                        }
                      }

                      $this->collPublicationMssPartial = true;
                    }

                    $collPublicationMss->getInternalIterator()->rewind();
                    return $collPublicationMss;
                }

                if($partial && $this->collPublicationMss) {
                    foreach($this->collPublicationMss as $obj) {
                        if($obj->isNew()) {
                            $collPublicationMss[] = $obj;
                        }
                    }
                }

                $this->collPublicationMss = $collPublicationMss;
                $this->collPublicationMssPartial = false;
            }
        }

        return $this->collPublicationMss;
    }

    /**
     * Sets a collection of PublicationMs objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationMss A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationMss(PropelCollection $publicationMss, PropelPDO $con = null)
    {
        $publicationMssToDelete = $this->getPublicationMss(new Criteria(), $con)->diff($publicationMss);

        $this->publicationMssScheduledForDeletion = unserialize(serialize($publicationMssToDelete));

        foreach ($publicationMssToDelete as $publicationMsRemoved) {
            $publicationMsRemoved->setPublication(null);
        }

        $this->collPublicationMss = null;
        foreach ($publicationMss as $publicationMs) {
            $this->addPublicationMs($publicationMs);
        }

        $this->collPublicationMss = $publicationMss;
        $this->collPublicationMssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PublicationMs objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PublicationMs objects.
     * @throws PropelException
     */
    public function countPublicationMss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationMssPartial && !$this->isNew();
        if (null === $this->collPublicationMss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationMss) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationMss());
            }
            $query = PublicationMsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collPublicationMss);
    }

    /**
     * Method called to associate a PublicationMs object to this object
     * through the PublicationMs foreign key attribute.
     *
     * @param    PublicationMs $l PublicationMs
     * @return Publication The current object (for fluent API support)
     */
    public function addPublicationMs(PublicationMs $l)
    {
        if ($this->collPublicationMss === null) {
            $this->initPublicationMss();
            $this->collPublicationMssPartial = true;
        }
        if (!in_array($l, $this->collPublicationMss->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationMs($l);
        }

        return $this;
    }

    /**
     * @param	PublicationMs $publicationMs The publicationMs object to add.
     */
    protected function doAddPublicationMs($publicationMs)
    {
        $this->collPublicationMss[]= $publicationMs;
        $publicationMs->setPublication($this);
    }

    /**
     * @param	PublicationMs $publicationMs The publicationMs object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removePublicationMs($publicationMs)
    {
        if ($this->getPublicationMss()->contains($publicationMs)) {
            $this->collPublicationMss->remove($this->collPublicationMss->search($publicationMs));
            if (null === $this->publicationMssScheduledForDeletion) {
                $this->publicationMssScheduledForDeletion = clone $this->collPublicationMss;
                $this->publicationMssScheduledForDeletion->clear();
            }
            $this->publicationMssScheduledForDeletion[]= clone $publicationMs;
            $publicationMs->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related PublicationMss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationMs[] List of PublicationMs objects
     */
    public function getPublicationMssJoinSeries($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationMsQuery::create(null, $criteria);
        $query->joinWith('Series', $join_behavior);

        return $this->getPublicationMss($query, $con);
    }

    /**
     * Clears out the collPublicationJas collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPublicationJas()
     */
    public function clearPublicationJas()
    {
        $this->collPublicationJas = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationJasPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationJas collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationJas($v = true)
    {
        $this->collPublicationJasPartial = $v;
    }

    /**
     * Initializes the collPublicationJas collection.
     *
     * By default this just sets the collPublicationJas collection to an empty array (like clearcollPublicationJas());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationJas($overrideExisting = true)
    {
        if (null !== $this->collPublicationJas && !$overrideExisting) {
            return;
        }
        $this->collPublicationJas = new PropelObjectCollection();
        $this->collPublicationJas->setModel('PublicationJa');
    }

    /**
     * Gets an array of PublicationJa objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PublicationJa[] List of PublicationJa objects
     * @throws PropelException
     */
    public function getPublicationJas($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationJasPartial && !$this->isNew();
        if (null === $this->collPublicationJas || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationJas) {
                // return empty collection
                $this->initPublicationJas();
            } else {
                $collPublicationJas = PublicationJaQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationJasPartial && count($collPublicationJas)) {
                      $this->initPublicationJas(false);

                      foreach($collPublicationJas as $obj) {
                        if (false == $this->collPublicationJas->contains($obj)) {
                          $this->collPublicationJas->append($obj);
                        }
                      }

                      $this->collPublicationJasPartial = true;
                    }

                    $collPublicationJas->getInternalIterator()->rewind();
                    return $collPublicationJas;
                }

                if($partial && $this->collPublicationJas) {
                    foreach($this->collPublicationJas as $obj) {
                        if($obj->isNew()) {
                            $collPublicationJas[] = $obj;
                        }
                    }
                }

                $this->collPublicationJas = $collPublicationJas;
                $this->collPublicationJasPartial = false;
            }
        }

        return $this->collPublicationJas;
    }

    /**
     * Sets a collection of PublicationJa objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationJas A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationJas(PropelCollection $publicationJas, PropelPDO $con = null)
    {
        $publicationJasToDelete = $this->getPublicationJas(new Criteria(), $con)->diff($publicationJas);

        $this->publicationJasScheduledForDeletion = unserialize(serialize($publicationJasToDelete));

        foreach ($publicationJasToDelete as $publicationJaRemoved) {
            $publicationJaRemoved->setPublication(null);
        }

        $this->collPublicationJas = null;
        foreach ($publicationJas as $publicationJa) {
            $this->addPublicationJa($publicationJa);
        }

        $this->collPublicationJas = $publicationJas;
        $this->collPublicationJasPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PublicationJa objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PublicationJa objects.
     * @throws PropelException
     */
    public function countPublicationJas(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationJasPartial && !$this->isNew();
        if (null === $this->collPublicationJas || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationJas) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationJas());
            }
            $query = PublicationJaQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collPublicationJas);
    }

    /**
     * Method called to associate a PublicationJa object to this object
     * through the PublicationJa foreign key attribute.
     *
     * @param    PublicationJa $l PublicationJa
     * @return Publication The current object (for fluent API support)
     */
    public function addPublicationJa(PublicationJa $l)
    {
        if ($this->collPublicationJas === null) {
            $this->initPublicationJas();
            $this->collPublicationJasPartial = true;
        }
        if (!in_array($l, $this->collPublicationJas->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationJa($l);
        }

        return $this;
    }

    /**
     * @param	PublicationJa $publicationJa The publicationJa object to add.
     */
    protected function doAddPublicationJa($publicationJa)
    {
        $this->collPublicationJas[]= $publicationJa;
        $publicationJa->setPublication($this);
    }

    /**
     * @param	PublicationJa $publicationJa The publicationJa object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removePublicationJa($publicationJa)
    {
        if ($this->getPublicationJas()->contains($publicationJa)) {
            $this->collPublicationJas->remove($this->collPublicationJas->search($publicationJa));
            if (null === $this->publicationJasScheduledForDeletion) {
                $this->publicationJasScheduledForDeletion = clone $this->collPublicationJas;
                $this->publicationJasScheduledForDeletion->clear();
            }
            $this->publicationJasScheduledForDeletion[]= clone $publicationJa;
            $publicationJa->setPublication(null);
        }

        return $this;
    }

    /**
     * Clears out the collPublicationMmss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPublicationMmss()
     */
    public function clearPublicationMmss()
    {
        $this->collPublicationMmss = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationMmssPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationMmss collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationMmss($v = true)
    {
        $this->collPublicationMmssPartial = $v;
    }

    /**
     * Initializes the collPublicationMmss collection.
     *
     * By default this just sets the collPublicationMmss collection to an empty array (like clearcollPublicationMmss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationMmss($overrideExisting = true)
    {
        if (null !== $this->collPublicationMmss && !$overrideExisting) {
            return;
        }
        $this->collPublicationMmss = new PropelObjectCollection();
        $this->collPublicationMmss->setModel('PublicationMms');
    }

    /**
     * Gets an array of PublicationMms objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PublicationMms[] List of PublicationMms objects
     * @throws PropelException
     */
    public function getPublicationMmss($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationMmssPartial && !$this->isNew();
        if (null === $this->collPublicationMmss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationMmss) {
                // return empty collection
                $this->initPublicationMmss();
            } else {
                $collPublicationMmss = PublicationMmsQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationMmssPartial && count($collPublicationMmss)) {
                      $this->initPublicationMmss(false);

                      foreach($collPublicationMmss as $obj) {
                        if (false == $this->collPublicationMmss->contains($obj)) {
                          $this->collPublicationMmss->append($obj);
                        }
                      }

                      $this->collPublicationMmssPartial = true;
                    }

                    $collPublicationMmss->getInternalIterator()->rewind();
                    return $collPublicationMmss;
                }

                if($partial && $this->collPublicationMmss) {
                    foreach($this->collPublicationMmss as $obj) {
                        if($obj->isNew()) {
                            $collPublicationMmss[] = $obj;
                        }
                    }
                }

                $this->collPublicationMmss = $collPublicationMmss;
                $this->collPublicationMmssPartial = false;
            }
        }

        return $this->collPublicationMmss;
    }

    /**
     * Sets a collection of PublicationMms objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationMmss A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationMmss(PropelCollection $publicationMmss, PropelPDO $con = null)
    {
        $publicationMmssToDelete = $this->getPublicationMmss(new Criteria(), $con)->diff($publicationMmss);

        $this->publicationMmssScheduledForDeletion = unserialize(serialize($publicationMmssToDelete));

        foreach ($publicationMmssToDelete as $publicationMmsRemoved) {
            $publicationMmsRemoved->setPublication(null);
        }

        $this->collPublicationMmss = null;
        foreach ($publicationMmss as $publicationMms) {
            $this->addPublicationMms($publicationMms);
        }

        $this->collPublicationMmss = $publicationMmss;
        $this->collPublicationMmssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PublicationMms objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PublicationMms objects.
     * @throws PropelException
     */
    public function countPublicationMmss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationMmssPartial && !$this->isNew();
        if (null === $this->collPublicationMmss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationMmss) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationMmss());
            }
            $query = PublicationMmsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collPublicationMmss);
    }

    /**
     * Method called to associate a PublicationMms object to this object
     * through the PublicationMms foreign key attribute.
     *
     * @param    PublicationMms $l PublicationMms
     * @return Publication The current object (for fluent API support)
     */
    public function addPublicationMms(PublicationMms $l)
    {
        if ($this->collPublicationMmss === null) {
            $this->initPublicationMmss();
            $this->collPublicationMmssPartial = true;
        }
        if (!in_array($l, $this->collPublicationMmss->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationMms($l);
        }

        return $this;
    }

    /**
     * @param	PublicationMms $publicationMms The publicationMms object to add.
     */
    protected function doAddPublicationMms($publicationMms)
    {
        $this->collPublicationMmss[]= $publicationMms;
        $publicationMms->setPublication($this);
    }

    /**
     * @param	PublicationMms $publicationMms The publicationMms object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removePublicationMms($publicationMms)
    {
        if ($this->getPublicationMmss()->contains($publicationMms)) {
            $this->collPublicationMmss->remove($this->collPublicationMmss->search($publicationMms));
            if (null === $this->publicationMmssScheduledForDeletion) {
                $this->publicationMmssScheduledForDeletion = clone $this->collPublicationMmss;
                $this->publicationMmssScheduledForDeletion->clear();
            }
            $this->publicationMmssScheduledForDeletion[]= clone $publicationMms;
            $publicationMms->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related PublicationMmss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationMms[] List of PublicationMms objects
     */
    public function getPublicationMmssJoinSeries($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationMmsQuery::create(null, $criteria);
        $query->joinWith('Series', $join_behavior);

        return $this->getPublicationMmss($query, $con);
    }

    /**
     * Clears out the collPublicationJs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPublicationJs()
     */
    public function clearPublicationJs()
    {
        $this->collPublicationJs = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationJsPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationJs collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationJs($v = true)
    {
        $this->collPublicationJsPartial = $v;
    }

    /**
     * Initializes the collPublicationJs collection.
     *
     * By default this just sets the collPublicationJs collection to an empty array (like clearcollPublicationJs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationJs($overrideExisting = true)
    {
        if (null !== $this->collPublicationJs && !$overrideExisting) {
            return;
        }
        $this->collPublicationJs = new PropelObjectCollection();
        $this->collPublicationJs->setModel('PublicationJ');
    }

    /**
     * Gets an array of PublicationJ objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PublicationJ[] List of PublicationJ objects
     * @throws PropelException
     */
    public function getPublicationJs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationJsPartial && !$this->isNew();
        if (null === $this->collPublicationJs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationJs) {
                // return empty collection
                $this->initPublicationJs();
            } else {
                $collPublicationJs = PublicationJQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationJsPartial && count($collPublicationJs)) {
                      $this->initPublicationJs(false);

                      foreach($collPublicationJs as $obj) {
                        if (false == $this->collPublicationJs->contains($obj)) {
                          $this->collPublicationJs->append($obj);
                        }
                      }

                      $this->collPublicationJsPartial = true;
                    }

                    $collPublicationJs->getInternalIterator()->rewind();
                    return $collPublicationJs;
                }

                if($partial && $this->collPublicationJs) {
                    foreach($this->collPublicationJs as $obj) {
                        if($obj->isNew()) {
                            $collPublicationJs[] = $obj;
                        }
                    }
                }

                $this->collPublicationJs = $collPublicationJs;
                $this->collPublicationJsPartial = false;
            }
        }

        return $this->collPublicationJs;
    }

    /**
     * Sets a collection of PublicationJ objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationJs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationJs(PropelCollection $publicationJs, PropelPDO $con = null)
    {
        $publicationJsToDelete = $this->getPublicationJs(new Criteria(), $con)->diff($publicationJs);

        $this->publicationJsScheduledForDeletion = unserialize(serialize($publicationJsToDelete));

        foreach ($publicationJsToDelete as $publicationJRemoved) {
            $publicationJRemoved->setPublication(null);
        }

        $this->collPublicationJs = null;
        foreach ($publicationJs as $publicationJ) {
            $this->addPublicationJ($publicationJ);
        }

        $this->collPublicationJs = $publicationJs;
        $this->collPublicationJsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PublicationJ objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PublicationJ objects.
     * @throws PropelException
     */
    public function countPublicationJs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationJsPartial && !$this->isNew();
        if (null === $this->collPublicationJs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationJs) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationJs());
            }
            $query = PublicationJQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collPublicationJs);
    }

    /**
     * Method called to associate a PublicationJ object to this object
     * through the PublicationJ foreign key attribute.
     *
     * @param    PublicationJ $l PublicationJ
     * @return Publication The current object (for fluent API support)
     */
    public function addPublicationJ(PublicationJ $l)
    {
        if ($this->collPublicationJs === null) {
            $this->initPublicationJs();
            $this->collPublicationJsPartial = true;
        }
        if (!in_array($l, $this->collPublicationJs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationJ($l);
        }

        return $this;
    }

    /**
     * @param	PublicationJ $publicationJ The publicationJ object to add.
     */
    protected function doAddPublicationJ($publicationJ)
    {
        $this->collPublicationJs[]= $publicationJ;
        $publicationJ->setPublication($this);
    }

    /**
     * @param	PublicationJ $publicationJ The publicationJ object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removePublicationJ($publicationJ)
    {
        if ($this->getPublicationJs()->contains($publicationJ)) {
            $this->collPublicationJs->remove($this->collPublicationJs->search($publicationJ));
            if (null === $this->publicationJsScheduledForDeletion) {
                $this->publicationJsScheduledForDeletion = clone $this->collPublicationJs;
                $this->publicationJsScheduledForDeletion->clear();
            }
            $this->publicationJsScheduledForDeletion[]= clone $publicationJ;
            $publicationJ->setPublication(null);
        }

        return $this;
    }

    /**
     * Clears out the collVolumesRelatedByPublicationId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addVolumesRelatedByPublicationId()
     */
    public function clearVolumesRelatedByPublicationId()
    {
        $this->collVolumesRelatedByPublicationId = null; // important to set this to null since that means it is uninitialized
        $this->collVolumesRelatedByPublicationIdPartial = null;

        return $this;
    }

    /**
     * reset is the collVolumesRelatedByPublicationId collection loaded partially
     *
     * @return void
     */
    public function resetPartialVolumesRelatedByPublicationId($v = true)
    {
        $this->collVolumesRelatedByPublicationIdPartial = $v;
    }

    /**
     * Initializes the collVolumesRelatedByPublicationId collection.
     *
     * By default this just sets the collVolumesRelatedByPublicationId collection to an empty array (like clearcollVolumesRelatedByPublicationId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initVolumesRelatedByPublicationId($overrideExisting = true)
    {
        if (null !== $this->collVolumesRelatedByPublicationId && !$overrideExisting) {
            return;
        }
        $this->collVolumesRelatedByPublicationId = new PropelObjectCollection();
        $this->collVolumesRelatedByPublicationId->setModel('Volume');
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
    public function getVolumesRelatedByPublicationId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collVolumesRelatedByPublicationIdPartial && !$this->isNew();
        if (null === $this->collVolumesRelatedByPublicationId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collVolumesRelatedByPublicationId) {
                // return empty collection
                $this->initVolumesRelatedByPublicationId();
            } else {
                $collVolumesRelatedByPublicationId = VolumeQuery::create(null, $criteria)
                    ->filterByPublicationRelatedByPublicationId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collVolumesRelatedByPublicationIdPartial && count($collVolumesRelatedByPublicationId)) {
                      $this->initVolumesRelatedByPublicationId(false);

                      foreach($collVolumesRelatedByPublicationId as $obj) {
                        if (false == $this->collVolumesRelatedByPublicationId->contains($obj)) {
                          $this->collVolumesRelatedByPublicationId->append($obj);
                        }
                      }

                      $this->collVolumesRelatedByPublicationIdPartial = true;
                    }

                    $collVolumesRelatedByPublicationId->getInternalIterator()->rewind();
                    return $collVolumesRelatedByPublicationId;
                }

                if($partial && $this->collVolumesRelatedByPublicationId) {
                    foreach($this->collVolumesRelatedByPublicationId as $obj) {
                        if($obj->isNew()) {
                            $collVolumesRelatedByPublicationId[] = $obj;
                        }
                    }
                }

                $this->collVolumesRelatedByPublicationId = $collVolumesRelatedByPublicationId;
                $this->collVolumesRelatedByPublicationIdPartial = false;
            }
        }

        return $this->collVolumesRelatedByPublicationId;
    }

    /**
     * Sets a collection of VolumeRelatedByPublicationId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $volumesRelatedByPublicationId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setVolumesRelatedByPublicationId(PropelCollection $volumesRelatedByPublicationId, PropelPDO $con = null)
    {
        $volumesRelatedByPublicationIdToDelete = $this->getVolumesRelatedByPublicationId(new Criteria(), $con)->diff($volumesRelatedByPublicationId);

        $this->volumesRelatedByPublicationIdScheduledForDeletion = unserialize(serialize($volumesRelatedByPublicationIdToDelete));

        foreach ($volumesRelatedByPublicationIdToDelete as $volumeRelatedByPublicationIdRemoved) {
            $volumeRelatedByPublicationIdRemoved->setPublicationRelatedByPublicationId(null);
        }

        $this->collVolumesRelatedByPublicationId = null;
        foreach ($volumesRelatedByPublicationId as $volumeRelatedByPublicationId) {
            $this->addVolumeRelatedByPublicationId($volumeRelatedByPublicationId);
        }

        $this->collVolumesRelatedByPublicationId = $volumesRelatedByPublicationId;
        $this->collVolumesRelatedByPublicationIdPartial = false;

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
    public function countVolumesRelatedByPublicationId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collVolumesRelatedByPublicationIdPartial && !$this->isNew();
        if (null === $this->collVolumesRelatedByPublicationId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collVolumesRelatedByPublicationId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getVolumesRelatedByPublicationId());
            }
            $query = VolumeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublicationRelatedByPublicationId($this)
                ->count($con);
        }

        return count($this->collVolumesRelatedByPublicationId);
    }

    /**
     * Method called to associate a Volume object to this object
     * through the Volume foreign key attribute.
     *
     * @param    Volume $l Volume
     * @return Publication The current object (for fluent API support)
     */
    public function addVolumeRelatedByPublicationId(Volume $l)
    {
        if ($this->collVolumesRelatedByPublicationId === null) {
            $this->initVolumesRelatedByPublicationId();
            $this->collVolumesRelatedByPublicationIdPartial = true;
        }
        if (!in_array($l, $this->collVolumesRelatedByPublicationId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddVolumeRelatedByPublicationId($l);
        }

        return $this;
    }

    /**
     * @param	VolumeRelatedByPublicationId $volumeRelatedByPublicationId The volumeRelatedByPublicationId object to add.
     */
    protected function doAddVolumeRelatedByPublicationId($volumeRelatedByPublicationId)
    {
        $this->collVolumesRelatedByPublicationId[]= $volumeRelatedByPublicationId;
        $volumeRelatedByPublicationId->setPublicationRelatedByPublicationId($this);
    }

    /**
     * @param	VolumeRelatedByPublicationId $volumeRelatedByPublicationId The volumeRelatedByPublicationId object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeVolumeRelatedByPublicationId($volumeRelatedByPublicationId)
    {
        if ($this->getVolumesRelatedByPublicationId()->contains($volumeRelatedByPublicationId)) {
            $this->collVolumesRelatedByPublicationId->remove($this->collVolumesRelatedByPublicationId->search($volumeRelatedByPublicationId));
            if (null === $this->volumesRelatedByPublicationIdScheduledForDeletion) {
                $this->volumesRelatedByPublicationIdScheduledForDeletion = clone $this->collVolumesRelatedByPublicationId;
                $this->volumesRelatedByPublicationIdScheduledForDeletion->clear();
            }
            $this->volumesRelatedByPublicationIdScheduledForDeletion[]= clone $volumeRelatedByPublicationId;
            $volumeRelatedByPublicationId->setPublicationRelatedByPublicationId(null);
        }

        return $this;
    }

    /**
     * Clears out the collVolumesRelatedByParentpublicationId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addVolumesRelatedByParentpublicationId()
     */
    public function clearVolumesRelatedByParentpublicationId()
    {
        $this->collVolumesRelatedByParentpublicationId = null; // important to set this to null since that means it is uninitialized
        $this->collVolumesRelatedByParentpublicationIdPartial = null;

        return $this;
    }

    /**
     * reset is the collVolumesRelatedByParentpublicationId collection loaded partially
     *
     * @return void
     */
    public function resetPartialVolumesRelatedByParentpublicationId($v = true)
    {
        $this->collVolumesRelatedByParentpublicationIdPartial = $v;
    }

    /**
     * Initializes the collVolumesRelatedByParentpublicationId collection.
     *
     * By default this just sets the collVolumesRelatedByParentpublicationId collection to an empty array (like clearcollVolumesRelatedByParentpublicationId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initVolumesRelatedByParentpublicationId($overrideExisting = true)
    {
        if (null !== $this->collVolumesRelatedByParentpublicationId && !$overrideExisting) {
            return;
        }
        $this->collVolumesRelatedByParentpublicationId = new PropelObjectCollection();
        $this->collVolumesRelatedByParentpublicationId->setModel('Volume');
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
    public function getVolumesRelatedByParentpublicationId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collVolumesRelatedByParentpublicationIdPartial && !$this->isNew();
        if (null === $this->collVolumesRelatedByParentpublicationId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collVolumesRelatedByParentpublicationId) {
                // return empty collection
                $this->initVolumesRelatedByParentpublicationId();
            } else {
                $collVolumesRelatedByParentpublicationId = VolumeQuery::create(null, $criteria)
                    ->filterByPublicationRelatedByParentpublicationId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collVolumesRelatedByParentpublicationIdPartial && count($collVolumesRelatedByParentpublicationId)) {
                      $this->initVolumesRelatedByParentpublicationId(false);

                      foreach($collVolumesRelatedByParentpublicationId as $obj) {
                        if (false == $this->collVolumesRelatedByParentpublicationId->contains($obj)) {
                          $this->collVolumesRelatedByParentpublicationId->append($obj);
                        }
                      }

                      $this->collVolumesRelatedByParentpublicationIdPartial = true;
                    }

                    $collVolumesRelatedByParentpublicationId->getInternalIterator()->rewind();
                    return $collVolumesRelatedByParentpublicationId;
                }

                if($partial && $this->collVolumesRelatedByParentpublicationId) {
                    foreach($this->collVolumesRelatedByParentpublicationId as $obj) {
                        if($obj->isNew()) {
                            $collVolumesRelatedByParentpublicationId[] = $obj;
                        }
                    }
                }

                $this->collVolumesRelatedByParentpublicationId = $collVolumesRelatedByParentpublicationId;
                $this->collVolumesRelatedByParentpublicationIdPartial = false;
            }
        }

        return $this->collVolumesRelatedByParentpublicationId;
    }

    /**
     * Sets a collection of VolumeRelatedByParentpublicationId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $volumesRelatedByParentpublicationId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setVolumesRelatedByParentpublicationId(PropelCollection $volumesRelatedByParentpublicationId, PropelPDO $con = null)
    {
        $volumesRelatedByParentpublicationIdToDelete = $this->getVolumesRelatedByParentpublicationId(new Criteria(), $con)->diff($volumesRelatedByParentpublicationId);

        $this->volumesRelatedByParentpublicationIdScheduledForDeletion = unserialize(serialize($volumesRelatedByParentpublicationIdToDelete));

        foreach ($volumesRelatedByParentpublicationIdToDelete as $volumeRelatedByParentpublicationIdRemoved) {
            $volumeRelatedByParentpublicationIdRemoved->setPublicationRelatedByParentpublicationId(null);
        }

        $this->collVolumesRelatedByParentpublicationId = null;
        foreach ($volumesRelatedByParentpublicationId as $volumeRelatedByParentpublicationId) {
            $this->addVolumeRelatedByParentpublicationId($volumeRelatedByParentpublicationId);
        }

        $this->collVolumesRelatedByParentpublicationId = $volumesRelatedByParentpublicationId;
        $this->collVolumesRelatedByParentpublicationIdPartial = false;

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
    public function countVolumesRelatedByParentpublicationId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collVolumesRelatedByParentpublicationIdPartial && !$this->isNew();
        if (null === $this->collVolumesRelatedByParentpublicationId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collVolumesRelatedByParentpublicationId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getVolumesRelatedByParentpublicationId());
            }
            $query = VolumeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublicationRelatedByParentpublicationId($this)
                ->count($con);
        }

        return count($this->collVolumesRelatedByParentpublicationId);
    }

    /**
     * Method called to associate a Volume object to this object
     * through the Volume foreign key attribute.
     *
     * @param    Volume $l Volume
     * @return Publication The current object (for fluent API support)
     */
    public function addVolumeRelatedByParentpublicationId(Volume $l)
    {
        if ($this->collVolumesRelatedByParentpublicationId === null) {
            $this->initVolumesRelatedByParentpublicationId();
            $this->collVolumesRelatedByParentpublicationIdPartial = true;
        }
        if (!in_array($l, $this->collVolumesRelatedByParentpublicationId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddVolumeRelatedByParentpublicationId($l);
        }

        return $this;
    }

    /**
     * @param	VolumeRelatedByParentpublicationId $volumeRelatedByParentpublicationId The volumeRelatedByParentpublicationId object to add.
     */
    protected function doAddVolumeRelatedByParentpublicationId($volumeRelatedByParentpublicationId)
    {
        $this->collVolumesRelatedByParentpublicationId[]= $volumeRelatedByParentpublicationId;
        $volumeRelatedByParentpublicationId->setPublicationRelatedByParentpublicationId($this);
    }

    /**
     * @param	VolumeRelatedByParentpublicationId $volumeRelatedByParentpublicationId The volumeRelatedByParentpublicationId object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeVolumeRelatedByParentpublicationId($volumeRelatedByParentpublicationId)
    {
        if ($this->getVolumesRelatedByParentpublicationId()->contains($volumeRelatedByParentpublicationId)) {
            $this->collVolumesRelatedByParentpublicationId->remove($this->collVolumesRelatedByParentpublicationId->search($volumeRelatedByParentpublicationId));
            if (null === $this->volumesRelatedByParentpublicationIdScheduledForDeletion) {
                $this->volumesRelatedByParentpublicationIdScheduledForDeletion = clone $this->collVolumesRelatedByParentpublicationId;
                $this->volumesRelatedByParentpublicationIdScheduledForDeletion->clear();
            }
            $this->volumesRelatedByParentpublicationIdScheduledForDeletion[]= clone $volumeRelatedByParentpublicationId;
            $volumeRelatedByParentpublicationId->setPublicationRelatedByParentpublicationId(null);
        }

        return $this;
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

                      foreach($collLanguagePublications as $obj) {
                        if (false == $this->collLanguagePublications->contains($obj)) {
                          $this->collLanguagePublications->append($obj);
                        }
                      }

                      $this->collLanguagePublicationsPartial = true;
                    }

                    $collLanguagePublications->getInternalIterator()->rewind();
                    return $collLanguagePublications;
                }

                if($partial && $this->collLanguagePublications) {
                    foreach($this->collLanguagePublications as $obj) {
                        if($obj->isNew()) {
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

        $this->languagePublicationsScheduledForDeletion = unserialize(serialize($languagePublicationsToDelete));

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

            if($partial && !$criteria) {
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

                      foreach($collGenrePublications as $obj) {
                        if (false == $this->collGenrePublications->contains($obj)) {
                          $this->collGenrePublications->append($obj);
                        }
                      }

                      $this->collGenrePublicationsPartial = true;
                    }

                    $collGenrePublications->getInternalIterator()->rewind();
                    return $collGenrePublications;
                }

                if($partial && $this->collGenrePublications) {
                    foreach($this->collGenrePublications as $obj) {
                        if($obj->isNew()) {
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

        $this->genrePublicationsScheduledForDeletion = unserialize(serialize($genrePublicationsToDelete));

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

            if($partial && !$criteria) {
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

                      foreach($collPublicationTags as $obj) {
                        if (false == $this->collPublicationTags->contains($obj)) {
                          $this->collPublicationTags->append($obj);
                        }
                      }

                      $this->collPublicationTagsPartial = true;
                    }

                    $collPublicationTags->getInternalIterator()->rewind();
                    return $collPublicationTags;
                }

                if($partial && $this->collPublicationTags) {
                    foreach($this->collPublicationTags as $obj) {
                        if($obj->isNew()) {
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

        $this->publicationTagsScheduledForDeletion = unserialize(serialize($publicationTagsToDelete));

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

            if($partial && !$criteria) {
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

                      foreach($collCategoryPublications as $obj) {
                        if (false == $this->collCategoryPublications->contains($obj)) {
                          $this->collCategoryPublications->append($obj);
                        }
                      }

                      $this->collCategoryPublicationsPartial = true;
                    }

                    $collCategoryPublications->getInternalIterator()->rewind();
                    return $collCategoryPublications;
                }

                if($partial && $this->collCategoryPublications) {
                    foreach($this->collCategoryPublications as $obj) {
                        if($obj->isNew()) {
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

        $this->categoryPublicationsScheduledForDeletion = unserialize(serialize($categoryPublicationsToDelete));

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

            if($partial && !$criteria) {
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

                      foreach($collFontPublications as $obj) {
                        if (false == $this->collFontPublications->contains($obj)) {
                          $this->collFontPublications->append($obj);
                        }
                      }

                      $this->collFontPublicationsPartial = true;
                    }

                    $collFontPublications->getInternalIterator()->rewind();
                    return $collFontPublications;
                }

                if($partial && $this->collFontPublications) {
                    foreach($this->collFontPublications as $obj) {
                        if($obj->isNew()) {
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

        $this->fontPublicationsScheduledForDeletion = unserialize(serialize($fontPublicationsToDelete));

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

            if($partial && !$criteria) {
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

                      foreach($collPublicationPublicationgroups as $obj) {
                        if (false == $this->collPublicationPublicationgroups->contains($obj)) {
                          $this->collPublicationPublicationgroups->append($obj);
                        }
                      }

                      $this->collPublicationPublicationgroupsPartial = true;
                    }

                    $collPublicationPublicationgroups->getInternalIterator()->rewind();
                    return $collPublicationPublicationgroups;
                }

                if($partial && $this->collPublicationPublicationgroups) {
                    foreach($this->collPublicationPublicationgroups as $obj) {
                        if($obj->isNew()) {
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

        $this->publicationPublicationgroupsScheduledForDeletion = unserialize(serialize($publicationPublicationgroupsToDelete));

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

            if($partial && !$criteria) {
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

                      foreach($collPersonPublications as $obj) {
                        if (false == $this->collPersonPublications->contains($obj)) {
                          $this->collPersonPublications->append($obj);
                        }
                      }

                      $this->collPersonPublicationsPartial = true;
                    }

                    $collPersonPublications->getInternalIterator()->rewind();
                    return $collPersonPublications;
                }

                if($partial && $this->collPersonPublications) {
                    foreach($this->collPersonPublications as $obj) {
                        if($obj->isNew()) {
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

        $this->personPublicationsScheduledForDeletion = unserialize(serialize($personPublicationsToDelete));

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

            if($partial && !$criteria) {
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

                      foreach($collTasks as $obj) {
                        if (false == $this->collTasks->contains($obj)) {
                          $this->collTasks->append($obj);
                        }
                      }

                      $this->collTasksPartial = true;
                    }

                    $collTasks->getInternalIterator()->rewind();
                    return $collTasks;
                }

                if($partial && $this->collTasks) {
                    foreach($this->collTasks as $obj) {
                        if($obj->isNew()) {
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

        $this->tasksScheduledForDeletion = unserialize(serialize($tasksToDelete));

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

            if($partial && !$criteria) {
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

                      foreach($collImagesources as $obj) {
                        if (false == $this->collImagesources->contains($obj)) {
                          $this->collImagesources->append($obj);
                        }
                      }

                      $this->collImagesourcesPartial = true;
                    }

                    $collImagesources->getInternalIterator()->rewind();
                    return $collImagesources;
                }

                if($partial && $this->collImagesources) {
                    foreach($this->collImagesources as $obj) {
                        if($obj->isNew()) {
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

        $this->imagesourcesScheduledForDeletion = unserialize(serialize($imagesourcesToDelete));

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

            if($partial && !$criteria) {
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
    public function getImagesourcesJoinLicense($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ImagesourceQuery::create(null, $criteria);
        $query->joinWith('License', $join_behavior);

        return $this->getImagesources($query, $con);
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

                      foreach($collTextsources as $obj) {
                        if (false == $this->collTextsources->contains($obj)) {
                          $this->collTextsources->append($obj);
                        }
                      }

                      $this->collTextsourcesPartial = true;
                    }

                    $collTextsources->getInternalIterator()->rewind();
                    return $collTextsources;
                }

                if($partial && $this->collTextsources) {
                    foreach($this->collTextsources as $obj) {
                        if($obj->isNew()) {
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

        $this->textsourcesScheduledForDeletion = unserialize(serialize($textsourcesToDelete));

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

            if($partial && !$criteria) {
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
        $currentLanguages = $this->getLanguages();

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

            $this->collLanguages[]= $language;
        }

        return $this;
    }

    /**
     * @param	Language $language The language object to add.
     */
    protected function doAddLanguage($language)
    {
        $languagePublication = new LanguagePublication();
        $languagePublication->setLanguage($language);
        $this->addLanguagePublication($languagePublication);
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
        $currentGenres = $this->getGenres();

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

            $this->collGenres[]= $genre;
        }

        return $this;
    }

    /**
     * @param	Genre $genre The genre object to add.
     */
    protected function doAddGenre($genre)
    {
        $genrePublication = new GenrePublication();
        $genrePublication->setGenre($genre);
        $this->addGenrePublication($genrePublication);
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
        $currentTags = $this->getTags();

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

            $this->collTags[]= $tag;
        }

        return $this;
    }

    /**
     * @param	Tag $tag The tag object to add.
     */
    protected function doAddTag($tag)
    {
        $publicationTag = new PublicationTag();
        $publicationTag->setTag($tag);
        $this->addPublicationTag($publicationTag);
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
        $currentCategories = $this->getCategories();

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

            $this->collCategories[]= $category;
        }

        return $this;
    }

    /**
     * @param	Category $category The category object to add.
     */
    protected function doAddCategory($category)
    {
        $categoryPublication = new CategoryPublication();
        $categoryPublication->setCategory($category);
        $this->addCategoryPublication($categoryPublication);
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
        $currentFonts = $this->getFonts();

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

            $this->collFonts[]= $font;
        }

        return $this;
    }

    /**
     * @param	Font $font The font object to add.
     */
    protected function doAddFont($font)
    {
        $fontPublication = new FontPublication();
        $fontPublication->setFont($font);
        $this->addFontPublication($fontPublication);
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
        $currentPublicationgroups = $this->getPublicationgroups();

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

            $this->collPublicationgroups[]= $publicationgroup;
        }

        return $this;
    }

    /**
     * @param	Publicationgroup $publicationgroup The publicationgroup object to add.
     */
    protected function doAddPublicationgroup($publicationgroup)
    {
        $publicationPublicationgroup = new PublicationPublicationgroup();
        $publicationPublicationgroup->setPublicationgroup($publicationgroup);
        $this->addPublicationPublicationgroup($publicationPublicationgroup);
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
        $this->title_id = null;
        $this->firsteditionpublication_id = null;
        $this->place_id = null;
        $this->publicationdate_id = null;
        $this->creationdate_id = null;
        $this->publishingcompany_id = null;
        $this->partner_id = null;
        $this->editiondescription = null;
        $this->digitaleditioneditor = null;
        $this->transcriptioncomment = null;
        $this->numpages = null;
        $this->numpagesnumeric = null;
        $this->comment = null;
        $this->doi = null;
        $this->format = null;
        $this->directoryname = null;
        $this->wwwready = null;
        $this->legacy_book_id = null;
        $this->publishingcompany_id_is_reconstructed = null;
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
     * when using Propel in certain daemon or large-volumne/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collPublicationMs) {
                foreach ($this->collPublicationMs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationDms) {
                foreach ($this->collPublicationDms as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationDss) {
                foreach ($this->collPublicationDss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationMss) {
                foreach ($this->collPublicationMss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationJas) {
                foreach ($this->collPublicationJas as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationMmss) {
                foreach ($this->collPublicationMmss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationJs) {
                foreach ($this->collPublicationJs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collVolumesRelatedByPublicationId) {
                foreach ($this->collVolumesRelatedByPublicationId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collVolumesRelatedByParentpublicationId) {
                foreach ($this->collVolumesRelatedByParentpublicationId as $o) {
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
            if ($this->collTasks) {
                foreach ($this->collTasks as $o) {
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

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPublicationMs instanceof PropelCollection) {
            $this->collPublicationMs->clearIterator();
        }
        $this->collPublicationMs = null;
        if ($this->collPublicationDms instanceof PropelCollection) {
            $this->collPublicationDms->clearIterator();
        }
        $this->collPublicationDms = null;
        if ($this->collPublicationDss instanceof PropelCollection) {
            $this->collPublicationDss->clearIterator();
        }
        $this->collPublicationDss = null;
        if ($this->collPublicationMss instanceof PropelCollection) {
            $this->collPublicationMss->clearIterator();
        }
        $this->collPublicationMss = null;
        if ($this->collPublicationJas instanceof PropelCollection) {
            $this->collPublicationJas->clearIterator();
        }
        $this->collPublicationJas = null;
        if ($this->collPublicationMmss instanceof PropelCollection) {
            $this->collPublicationMmss->clearIterator();
        }
        $this->collPublicationMmss = null;
        if ($this->collPublicationJs instanceof PropelCollection) {
            $this->collPublicationJs->clearIterator();
        }
        $this->collPublicationJs = null;
        if ($this->collVolumesRelatedByPublicationId instanceof PropelCollection) {
            $this->collVolumesRelatedByPublicationId->clearIterator();
        }
        $this->collVolumesRelatedByPublicationId = null;
        if ($this->collVolumesRelatedByParentpublicationId instanceof PropelCollection) {
            $this->collVolumesRelatedByParentpublicationId->clearIterator();
        }
        $this->collVolumesRelatedByParentpublicationId = null;
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
        if ($this->collTasks instanceof PropelCollection) {
            $this->collTasks->clearIterator();
        }
        $this->collTasks = null;
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
        $this->aPublishingcompany = null;
        $this->aPlace = null;
        $this->aDatespecificationRelatedByPublicationdateId = null;
        $this->aDatespecificationRelatedByCreationdateId = null;
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
     * Selects one of many related entities
     */

    public function getRepresentativeTitle(){

        if ($this->countTitles() > 0) {

            $pn = $this->getTitles();

            // sort by rank if available
            $rc = new \ReflectionClass(new Title());
            if ( $rc->hasMethod('getSortableRank')) {
                $pn->uasort(function($a, $b) {
                            return $a->getSortableRank() - $b->getSortableRank();
                        });
            }

            $pn = $pn->toKeyValue();
            return array_shift($pn);

        } else {
            return "-";
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
}
