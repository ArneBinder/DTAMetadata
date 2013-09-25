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
use DTA\MetadataBundle\Model\Data\Datespecification;
use DTA\MetadataBundle\Model\Data\DatespecificationQuery;
use DTA\MetadataBundle\Model\Data\Font;
use DTA\MetadataBundle\Model\Data\FontQuery;
use DTA\MetadataBundle\Model\Data\Place;
use DTA\MetadataBundle\Model\Data\PlaceQuery;
use DTA\MetadataBundle\Model\Data\Printrun;
use DTA\MetadataBundle\Model\Data\PrintrunQuery;
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
use DTA\MetadataBundle\Model\Data\PublicationMm;
use DTA\MetadataBundle\Model\Data\PublicationMmQuery;
use DTA\MetadataBundle\Model\Data\PublicationMms;
use DTA\MetadataBundle\Model\Data\PublicationMmsQuery;
use DTA\MetadataBundle\Model\Data\PublicationMs;
use DTA\MetadataBundle\Model\Data\PublicationMsQuery;
use DTA\MetadataBundle\Model\Data\PublicationPeer;
use DTA\MetadataBundle\Model\Data\PublicationQuery;
use DTA\MetadataBundle\Model\Data\Publishingcompany;
use DTA\MetadataBundle\Model\Data\PublishingcompanyQuery;
use DTA\MetadataBundle\Model\Data\Work;
use DTA\MetadataBundle\Model\Data\WorkQuery;
use DTA\MetadataBundle\Model\Master\PersonPublication;
use DTA\MetadataBundle\Model\Master\PersonPublicationQuery;
use DTA\MetadataBundle\Model\Master\PublicationPublicationgroup;
use DTA\MetadataBundle\Model\Master\PublicationPublicationgroupQuery;
use DTA\MetadataBundle\Model\Workflow\Imagesource;
use DTA\MetadataBundle\Model\Workflow\ImagesourceQuery;
use DTA\MetadataBundle\Model\Workflow\Publicationgroup;
use DTA\MetadataBundle\Model\Workflow\PublicationgroupQuery;
use DTA\MetadataBundle\Model\Workflow\Relatedset;
use DTA\MetadataBundle\Model\Workflow\RelatedsetQuery;
use DTA\MetadataBundle\Model\Workflow\Task;
use DTA\MetadataBundle\Model\Workflow\TaskQuery;
use DTA\MetadataBundle\Model\Workflow\Textsource;
use DTA\MetadataBundle\Model\Workflow\TextsourceQuery;

abstract class BasePublication extends BaseObject implements Persistent, \DTA\MetadataBundle\Model\table_row_view\TableRowViewInterface
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
     * The value for the work_id field.
     * @var        int
     */
    protected $work_id;

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
     * The value for the firstpublicationdate_id field.
     * @var        int
     */
    protected $firstpublicationdate_id;

    /**
     * The value for the printrun_id field.
     * @var        int
     */
    protected $printrun_id;

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
     * The value for the font_id field.
     * @var        int
     */
    protected $font_id;

    /**
     * The value for the comment field.
     * @var        string
     */
    protected $comment;

    /**
     * The value for the relatedset_id field.
     * @var        int
     */
    protected $relatedset_id;

    /**
     * @var        Work
     */
    protected $aWork;

    /**
     * @var        Publishingcompany
     */
    protected $aPublishingcompany;

    /**
     * @var        Place
     */
    protected $aPlace;

    /**
     * @var        Printrun
     */
    protected $aPrintrun;

    /**
     * @var        Relatedset
     */
    protected $aRelatedset;

    /**
     * @var        Datespecification
     */
    protected $aDatespecificationRelatedByPublicationdateId;

    /**
     * @var        Datespecification
     */
    protected $aDatespecificationRelatedByFirstpublicationdateId;

    /**
     * @var        Font
     */
    protected $aFont;

    /**
     * @var        PropelObjectCollection|PublicationM[] Collection to store aggregation of PublicationM objects.
     */
    protected $collPublicationMs;
    protected $collPublicationMsPartial;

    /**
     * @var        PropelObjectCollection|PublicationDm[] Collection to store aggregation of PublicationDm objects.
     */
    protected $collPublicationDmsRelatedByPublicationId;
    protected $collPublicationDmsRelatedByPublicationIdPartial;

    /**
     * @var        PropelObjectCollection|PublicationDm[] Collection to store aggregation of PublicationDm objects.
     */
    protected $collPublicationDmsRelatedByParent;
    protected $collPublicationDmsRelatedByParentPartial;

    /**
     * @var        PropelObjectCollection|PublicationMm[] Collection to store aggregation of PublicationMm objects.
     */
    protected $collPublicationMms;
    protected $collPublicationMmsPartial;

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
    protected $collPublicationJasRelatedByPublicationId;
    protected $collPublicationJasRelatedByPublicationIdPartial;

    /**
     * @var        PropelObjectCollection|PublicationJa[] Collection to store aggregation of PublicationJa objects.
     */
    protected $collPublicationJasRelatedByParent;
    protected $collPublicationJasRelatedByParentPartial;

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
    public static $tableRowViewCaptions = array('Titel', 'erster Autor', 'entstanden', 'veröffentlicht', );	public   $tableRowViewAccessors = array('Titel'=>'accessor:getEmbeddedColumn1OfWork', 'erster Autor'=>'accessor:getEmbeddedColumn2OfWork', 'entstanden'=>'accessor:getEmbeddedColumn3OfWork', 'veröffentlicht'=>'accessor:getDatespecificationRelatedByPublicationdateId', );
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
    protected $publicationDmsRelatedByPublicationIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationDmsRelatedByParentScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationMmsScheduledForDeletion = null;

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
    protected $publicationJasRelatedByPublicationIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationJasRelatedByParentScheduledForDeletion = null;

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
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [work_id] column value.
     *
     * @return int
     */
    public function getWorkId()
    {
        return $this->work_id;
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
     * Get the [firstpublicationdate_id] column value.
     * Erscheinungsjahr der Erstausgabe
     * @return int
     */
    public function getFirstpublicationdateId()
    {
        return $this->firstpublicationdate_id;
    }

    /**
     * Get the [printrun_id] column value.
     * Informationen zur Auflage
     * @return int
     */
    public function getPrintrunId()
    {
        return $this->printrun_id;
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
     * Get the [font_id] column value.
     * Vorherrschende Schriftart
     * @return int
     */
    public function getFontId()
    {
        return $this->font_id;
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
     * Get the [relatedset_id] column value.
     *
     * @return int
     */
    public function getRelatedsetId()
    {
        return $this->relatedset_id;
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
     * Set the value of [work_id] column.
     *
     * @param int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setWorkId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->work_id !== $v) {
            $this->work_id = $v;
            $this->modifiedColumns[] = PublicationPeer::WORK_ID;
        }

        if ($this->aWork !== null && $this->aWork->getId() !== $v) {
            $this->aWork = null;
        }


        return $this;
    } // setWorkId()

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
     * Set the value of [firstpublicationdate_id] column.
     * Erscheinungsjahr der Erstausgabe
     * @param int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setFirstpublicationdateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->firstpublicationdate_id !== $v) {
            $this->firstpublicationdate_id = $v;
            $this->modifiedColumns[] = PublicationPeer::FIRSTPUBLICATIONDATE_ID;
        }

        if ($this->aDatespecificationRelatedByFirstpublicationdateId !== null && $this->aDatespecificationRelatedByFirstpublicationdateId->getId() !== $v) {
            $this->aDatespecificationRelatedByFirstpublicationdateId = null;
        }


        return $this;
    } // setFirstpublicationdateId()

    /**
     * Set the value of [printrun_id] column.
     * Informationen zur Auflage
     * @param int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setPrintrunId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->printrun_id !== $v) {
            $this->printrun_id = $v;
            $this->modifiedColumns[] = PublicationPeer::PRINTRUN_ID;
        }

        if ($this->aPrintrun !== null && $this->aPrintrun->getId() !== $v) {
            $this->aPrintrun = null;
        }


        return $this;
    } // setPrintrunId()

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
     * Set the value of [font_id] column.
     * Vorherrschende Schriftart
     * @param int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setFontId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->font_id !== $v) {
            $this->font_id = $v;
            $this->modifiedColumns[] = PublicationPeer::FONT_ID;
        }

        if ($this->aFont !== null && $this->aFont->getId() !== $v) {
            $this->aFont = null;
        }


        return $this;
    } // setFontId()

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
     * Set the value of [relatedset_id] column.
     *
     * @param int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setRelatedsetId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->relatedset_id !== $v) {
            $this->relatedset_id = $v;
            $this->modifiedColumns[] = PublicationPeer::RELATEDSET_ID;
        }

        if ($this->aRelatedset !== null && $this->aRelatedset->getId() !== $v) {
            $this->aRelatedset = null;
        }


        return $this;
    } // setRelatedsetId()

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
            $this->work_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->place_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->publicationdate_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->firstpublicationdate_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->printrun_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->publishingcompany_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->partner_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->editiondescription = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->digitaleditioneditor = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->transcriptioncomment = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->font_id = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
            $this->comment = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->relatedset_id = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 14; // 14 = PublicationPeer::NUM_HYDRATE_COLUMNS.

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

        if ($this->aWork !== null && $this->work_id !== $this->aWork->getId()) {
            $this->aWork = null;
        }
        if ($this->aPlace !== null && $this->place_id !== $this->aPlace->getId()) {
            $this->aPlace = null;
        }
        if ($this->aDatespecificationRelatedByPublicationdateId !== null && $this->publicationdate_id !== $this->aDatespecificationRelatedByPublicationdateId->getId()) {
            $this->aDatespecificationRelatedByPublicationdateId = null;
        }
        if ($this->aDatespecificationRelatedByFirstpublicationdateId !== null && $this->firstpublicationdate_id !== $this->aDatespecificationRelatedByFirstpublicationdateId->getId()) {
            $this->aDatespecificationRelatedByFirstpublicationdateId = null;
        }
        if ($this->aPrintrun !== null && $this->printrun_id !== $this->aPrintrun->getId()) {
            $this->aPrintrun = null;
        }
        if ($this->aPublishingcompany !== null && $this->publishingcompany_id !== $this->aPublishingcompany->getId()) {
            $this->aPublishingcompany = null;
        }
        if ($this->aFont !== null && $this->font_id !== $this->aFont->getId()) {
            $this->aFont = null;
        }
        if ($this->aRelatedset !== null && $this->relatedset_id !== $this->aRelatedset->getId()) {
            $this->aRelatedset = null;
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

            $this->aWork = null;
            $this->aPublishingcompany = null;
            $this->aPlace = null;
            $this->aPrintrun = null;
            $this->aRelatedset = null;
            $this->aDatespecificationRelatedByPublicationdateId = null;
            $this->aDatespecificationRelatedByFirstpublicationdateId = null;
            $this->aFont = null;
            $this->collPublicationMs = null;

            $this->collPublicationDmsRelatedByPublicationId = null;

            $this->collPublicationDmsRelatedByParent = null;

            $this->collPublicationMms = null;

            $this->collPublicationDss = null;

            $this->collPublicationMss = null;

            $this->collPublicationJasRelatedByPublicationId = null;

            $this->collPublicationJasRelatedByParent = null;

            $this->collPublicationMmss = null;

            $this->collPublicationJs = null;

            $this->collPublicationPublicationgroups = null;

            $this->collPersonPublications = null;

            $this->collTasks = null;

            $this->collImagesources = null;

            $this->collTextsources = null;

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

            if ($this->aWork !== null) {
                if ($this->aWork->isModified() || $this->aWork->isNew()) {
                    $affectedRows += $this->aWork->save($con);
                }
                $this->setWork($this->aWork);
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

            if ($this->aPrintrun !== null) {
                if ($this->aPrintrun->isModified() || $this->aPrintrun->isNew()) {
                    $affectedRows += $this->aPrintrun->save($con);
                }
                $this->setPrintrun($this->aPrintrun);
            }

            if ($this->aRelatedset !== null) {
                if ($this->aRelatedset->isModified() || $this->aRelatedset->isNew()) {
                    $affectedRows += $this->aRelatedset->save($con);
                }
                $this->setRelatedset($this->aRelatedset);
            }

            if ($this->aDatespecificationRelatedByPublicationdateId !== null) {
                if ($this->aDatespecificationRelatedByPublicationdateId->isModified() || $this->aDatespecificationRelatedByPublicationdateId->isNew()) {
                    $affectedRows += $this->aDatespecificationRelatedByPublicationdateId->save($con);
                }
                $this->setDatespecificationRelatedByPublicationdateId($this->aDatespecificationRelatedByPublicationdateId);
            }

            if ($this->aDatespecificationRelatedByFirstpublicationdateId !== null) {
                if ($this->aDatespecificationRelatedByFirstpublicationdateId->isModified() || $this->aDatespecificationRelatedByFirstpublicationdateId->isNew()) {
                    $affectedRows += $this->aDatespecificationRelatedByFirstpublicationdateId->save($con);
                }
                $this->setDatespecificationRelatedByFirstpublicationdateId($this->aDatespecificationRelatedByFirstpublicationdateId);
            }

            if ($this->aFont !== null) {
                if ($this->aFont->isModified() || $this->aFont->isNew()) {
                    $affectedRows += $this->aFont->save($con);
                }
                $this->setFont($this->aFont);
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

            if ($this->publicationDmsRelatedByPublicationIdScheduledForDeletion !== null) {
                if (!$this->publicationDmsRelatedByPublicationIdScheduledForDeletion->isEmpty()) {
                    PublicationDmQuery::create()
                        ->filterByPrimaryKeys($this->publicationDmsRelatedByPublicationIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationDmsRelatedByPublicationIdScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationDmsRelatedByPublicationId !== null) {
                foreach ($this->collPublicationDmsRelatedByPublicationId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->publicationDmsRelatedByParentScheduledForDeletion !== null) {
                if (!$this->publicationDmsRelatedByParentScheduledForDeletion->isEmpty()) {
                    foreach ($this->publicationDmsRelatedByParentScheduledForDeletion as $publicationDmRelatedByParent) {
                        // need to save related object because we set the relation to null
                        $publicationDmRelatedByParent->save($con);
                    }
                    $this->publicationDmsRelatedByParentScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationDmsRelatedByParent !== null) {
                foreach ($this->collPublicationDmsRelatedByParent as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->publicationMmsScheduledForDeletion !== null) {
                if (!$this->publicationMmsScheduledForDeletion->isEmpty()) {
                    PublicationMmQuery::create()
                        ->filterByPrimaryKeys($this->publicationMmsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationMmsScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationMms !== null) {
                foreach ($this->collPublicationMms as $referrerFK) {
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

            if ($this->publicationJasRelatedByPublicationIdScheduledForDeletion !== null) {
                if (!$this->publicationJasRelatedByPublicationIdScheduledForDeletion->isEmpty()) {
                    PublicationJaQuery::create()
                        ->filterByPrimaryKeys($this->publicationJasRelatedByPublicationIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationJasRelatedByPublicationIdScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationJasRelatedByPublicationId !== null) {
                foreach ($this->collPublicationJasRelatedByPublicationId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->publicationJasRelatedByParentScheduledForDeletion !== null) {
                if (!$this->publicationJasRelatedByParentScheduledForDeletion->isEmpty()) {
                    PublicationJaQuery::create()
                        ->filterByPrimaryKeys($this->publicationJasRelatedByParentScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationJasRelatedByParentScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationJasRelatedByParent !== null) {
                foreach ($this->collPublicationJasRelatedByParent as $referrerFK) {
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
        if ($this->isColumnModified(PublicationPeer::WORK_ID)) {
            $modifiedColumns[':p' . $index++]  = '"work_id"';
        }
        if ($this->isColumnModified(PublicationPeer::PLACE_ID)) {
            $modifiedColumns[':p' . $index++]  = '"place_id"';
        }
        if ($this->isColumnModified(PublicationPeer::PUBLICATIONDATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '"publicationdate_id"';
        }
        if ($this->isColumnModified(PublicationPeer::FIRSTPUBLICATIONDATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '"firstpublicationdate_id"';
        }
        if ($this->isColumnModified(PublicationPeer::PRINTRUN_ID)) {
            $modifiedColumns[':p' . $index++]  = '"printrun_id"';
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
        if ($this->isColumnModified(PublicationPeer::FONT_ID)) {
            $modifiedColumns[':p' . $index++]  = '"font_id"';
        }
        if ($this->isColumnModified(PublicationPeer::COMMENT)) {
            $modifiedColumns[':p' . $index++]  = '"comment"';
        }
        if ($this->isColumnModified(PublicationPeer::RELATEDSET_ID)) {
            $modifiedColumns[':p' . $index++]  = '"relatedset_id"';
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
                    case '"work_id"':
                        $stmt->bindValue($identifier, $this->work_id, PDO::PARAM_INT);
                        break;
                    case '"place_id"':
                        $stmt->bindValue($identifier, $this->place_id, PDO::PARAM_INT);
                        break;
                    case '"publicationdate_id"':
                        $stmt->bindValue($identifier, $this->publicationdate_id, PDO::PARAM_INT);
                        break;
                    case '"firstpublicationdate_id"':
                        $stmt->bindValue($identifier, $this->firstpublicationdate_id, PDO::PARAM_INT);
                        break;
                    case '"printrun_id"':
                        $stmt->bindValue($identifier, $this->printrun_id, PDO::PARAM_INT);
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
                    case '"font_id"':
                        $stmt->bindValue($identifier, $this->font_id, PDO::PARAM_INT);
                        break;
                    case '"comment"':
                        $stmt->bindValue($identifier, $this->comment, PDO::PARAM_STR);
                        break;
                    case '"relatedset_id"':
                        $stmt->bindValue($identifier, $this->relatedset_id, PDO::PARAM_INT);
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

            if ($this->aWork !== null) {
                if (!$this->aWork->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aWork->getValidationFailures());
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

            if ($this->aPrintrun !== null) {
                if (!$this->aPrintrun->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPrintrun->getValidationFailures());
                }
            }

            if ($this->aRelatedset !== null) {
                if (!$this->aRelatedset->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aRelatedset->getValidationFailures());
                }
            }

            if ($this->aDatespecificationRelatedByPublicationdateId !== null) {
                if (!$this->aDatespecificationRelatedByPublicationdateId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDatespecificationRelatedByPublicationdateId->getValidationFailures());
                }
            }

            if ($this->aDatespecificationRelatedByFirstpublicationdateId !== null) {
                if (!$this->aDatespecificationRelatedByFirstpublicationdateId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDatespecificationRelatedByFirstpublicationdateId->getValidationFailures());
                }
            }

            if ($this->aFont !== null) {
                if (!$this->aFont->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFont->getValidationFailures());
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

                if ($this->collPublicationDmsRelatedByPublicationId !== null) {
                    foreach ($this->collPublicationDmsRelatedByPublicationId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublicationDmsRelatedByParent !== null) {
                    foreach ($this->collPublicationDmsRelatedByParent as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublicationMms !== null) {
                    foreach ($this->collPublicationMms as $referrerFK) {
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

                if ($this->collPublicationJasRelatedByPublicationId !== null) {
                    foreach ($this->collPublicationJasRelatedByPublicationId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublicationJasRelatedByParent !== null) {
                    foreach ($this->collPublicationJasRelatedByParent as $referrerFK) {
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
                return $this->getWorkId();
                break;
            case 2:
                return $this->getPlaceId();
                break;
            case 3:
                return $this->getPublicationdateId();
                break;
            case 4:
                return $this->getFirstpublicationdateId();
                break;
            case 5:
                return $this->getPrintrunId();
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
                return $this->getFontId();
                break;
            case 12:
                return $this->getComment();
                break;
            case 13:
                return $this->getRelatedsetId();
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
            $keys[1] => $this->getWorkId(),
            $keys[2] => $this->getPlaceId(),
            $keys[3] => $this->getPublicationdateId(),
            $keys[4] => $this->getFirstpublicationdateId(),
            $keys[5] => $this->getPrintrunId(),
            $keys[6] => $this->getPublishingcompanyId(),
            $keys[7] => $this->getPartnerId(),
            $keys[8] => $this->getEditiondescription(),
            $keys[9] => $this->getDigitaleditioneditor(),
            $keys[10] => $this->getTranscriptioncomment(),
            $keys[11] => $this->getFontId(),
            $keys[12] => $this->getComment(),
            $keys[13] => $this->getRelatedsetId(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aWork) {
                $result['Work'] = $this->aWork->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPublishingcompany) {
                $result['Publishingcompany'] = $this->aPublishingcompany->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPlace) {
                $result['Place'] = $this->aPlace->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPrintrun) {
                $result['Printrun'] = $this->aPrintrun->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aRelatedset) {
                $result['Relatedset'] = $this->aRelatedset->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDatespecificationRelatedByPublicationdateId) {
                $result['DatespecificationRelatedByPublicationdateId'] = $this->aDatespecificationRelatedByPublicationdateId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDatespecificationRelatedByFirstpublicationdateId) {
                $result['DatespecificationRelatedByFirstpublicationdateId'] = $this->aDatespecificationRelatedByFirstpublicationdateId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aFont) {
                $result['Font'] = $this->aFont->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPublicationMs) {
                $result['PublicationMs'] = $this->collPublicationMs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationDmsRelatedByPublicationId) {
                $result['PublicationDmsRelatedByPublicationId'] = $this->collPublicationDmsRelatedByPublicationId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationDmsRelatedByParent) {
                $result['PublicationDmsRelatedByParent'] = $this->collPublicationDmsRelatedByParent->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationMms) {
                $result['PublicationMms'] = $this->collPublicationMms->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationDss) {
                $result['PublicationDss'] = $this->collPublicationDss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationMss) {
                $result['PublicationMss'] = $this->collPublicationMss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationJasRelatedByPublicationId) {
                $result['PublicationJasRelatedByPublicationId'] = $this->collPublicationJasRelatedByPublicationId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationJasRelatedByParent) {
                $result['PublicationJasRelatedByParent'] = $this->collPublicationJasRelatedByParent->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationMmss) {
                $result['PublicationMmss'] = $this->collPublicationMmss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationJs) {
                $result['PublicationJs'] = $this->collPublicationJs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $this->setWorkId($value);
                break;
            case 2:
                $this->setPlaceId($value);
                break;
            case 3:
                $this->setPublicationdateId($value);
                break;
            case 4:
                $this->setFirstpublicationdateId($value);
                break;
            case 5:
                $this->setPrintrunId($value);
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
                $this->setFontId($value);
                break;
            case 12:
                $this->setComment($value);
                break;
            case 13:
                $this->setRelatedsetId($value);
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
        if (array_key_exists($keys[1], $arr)) $this->setWorkId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPlaceId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPublicationdateId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setFirstpublicationdateId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setPrintrunId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setPublishingcompanyId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setPartnerId($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setEditiondescription($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setDigitaleditioneditor($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setTranscriptioncomment($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setFontId($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setComment($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setRelatedsetId($arr[$keys[13]]);
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
        if ($this->isColumnModified(PublicationPeer::WORK_ID)) $criteria->add(PublicationPeer::WORK_ID, $this->work_id);
        if ($this->isColumnModified(PublicationPeer::PLACE_ID)) $criteria->add(PublicationPeer::PLACE_ID, $this->place_id);
        if ($this->isColumnModified(PublicationPeer::PUBLICATIONDATE_ID)) $criteria->add(PublicationPeer::PUBLICATIONDATE_ID, $this->publicationdate_id);
        if ($this->isColumnModified(PublicationPeer::FIRSTPUBLICATIONDATE_ID)) $criteria->add(PublicationPeer::FIRSTPUBLICATIONDATE_ID, $this->firstpublicationdate_id);
        if ($this->isColumnModified(PublicationPeer::PRINTRUN_ID)) $criteria->add(PublicationPeer::PRINTRUN_ID, $this->printrun_id);
        if ($this->isColumnModified(PublicationPeer::PUBLISHINGCOMPANY_ID)) $criteria->add(PublicationPeer::PUBLISHINGCOMPANY_ID, $this->publishingcompany_id);
        if ($this->isColumnModified(PublicationPeer::PARTNER_ID)) $criteria->add(PublicationPeer::PARTNER_ID, $this->partner_id);
        if ($this->isColumnModified(PublicationPeer::EDITIONDESCRIPTION)) $criteria->add(PublicationPeer::EDITIONDESCRIPTION, $this->editiondescription);
        if ($this->isColumnModified(PublicationPeer::DIGITALEDITIONEDITOR)) $criteria->add(PublicationPeer::DIGITALEDITIONEDITOR, $this->digitaleditioneditor);
        if ($this->isColumnModified(PublicationPeer::TRANSCRIPTIONCOMMENT)) $criteria->add(PublicationPeer::TRANSCRIPTIONCOMMENT, $this->transcriptioncomment);
        if ($this->isColumnModified(PublicationPeer::FONT_ID)) $criteria->add(PublicationPeer::FONT_ID, $this->font_id);
        if ($this->isColumnModified(PublicationPeer::COMMENT)) $criteria->add(PublicationPeer::COMMENT, $this->comment);
        if ($this->isColumnModified(PublicationPeer::RELATEDSET_ID)) $criteria->add(PublicationPeer::RELATEDSET_ID, $this->relatedset_id);

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
        $copyObj->setWorkId($this->getWorkId());
        $copyObj->setPlaceId($this->getPlaceId());
        $copyObj->setPublicationdateId($this->getPublicationdateId());
        $copyObj->setFirstpublicationdateId($this->getFirstpublicationdateId());
        $copyObj->setPrintrunId($this->getPrintrunId());
        $copyObj->setPublishingcompanyId($this->getPublishingcompanyId());
        $copyObj->setPartnerId($this->getPartnerId());
        $copyObj->setEditiondescription($this->getEditiondescription());
        $copyObj->setDigitaleditioneditor($this->getDigitaleditioneditor());
        $copyObj->setTranscriptioncomment($this->getTranscriptioncomment());
        $copyObj->setFontId($this->getFontId());
        $copyObj->setComment($this->getComment());
        $copyObj->setRelatedsetId($this->getRelatedsetId());

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

            foreach ($this->getPublicationDmsRelatedByPublicationId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationDmRelatedByPublicationId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationDmsRelatedByParent() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationDmRelatedByParent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationMms() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationMm($relObj->copy($deepCopy));
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

            foreach ($this->getPublicationJasRelatedByPublicationId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationJaRelatedByPublicationId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationJasRelatedByParent() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationJaRelatedByParent($relObj->copy($deepCopy));
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
     * Declares an association between this object and a Work object.
     *
     * @param             Work $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setWork(Work $v = null)
    {
        if ($v === null) {
            $this->setWorkId(NULL);
        } else {
            $this->setWorkId($v->getId());
        }

        $this->aWork = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Work object, it will not be re-added.
        if ($v !== null) {
            $v->addPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated Work object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Work The associated Work object.
     * @throws PropelException
     */
    public function getWork(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aWork === null && ($this->work_id !== null) && $doQuery) {
            $this->aWork = WorkQuery::create()->findPk($this->work_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aWork->addPublications($this);
             */
        }

        return $this->aWork;
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
     * Declares an association between this object and a Printrun object.
     *
     * @param             Printrun $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPrintrun(Printrun $v = null)
    {
        if ($v === null) {
            $this->setPrintrunId(NULL);
        } else {
            $this->setPrintrunId($v->getId());
        }

        $this->aPrintrun = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Printrun object, it will not be re-added.
        if ($v !== null) {
            $v->addPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated Printrun object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Printrun The associated Printrun object.
     * @throws PropelException
     */
    public function getPrintrun(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPrintrun === null && ($this->printrun_id !== null) && $doQuery) {
            $this->aPrintrun = PrintrunQuery::create()->findPk($this->printrun_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPrintrun->addPublications($this);
             */
        }

        return $this->aPrintrun;
    }

    /**
     * Declares an association between this object and a Relatedset object.
     *
     * @param             Relatedset $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setRelatedset(Relatedset $v = null)
    {
        if ($v === null) {
            $this->setRelatedsetId(NULL);
        } else {
            $this->setRelatedsetId($v->getId());
        }

        $this->aRelatedset = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Relatedset object, it will not be re-added.
        if ($v !== null) {
            $v->addPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated Relatedset object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Relatedset The associated Relatedset object.
     * @throws PropelException
     */
    public function getRelatedset(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aRelatedset === null && ($this->relatedset_id !== null) && $doQuery) {
            $this->aRelatedset = RelatedsetQuery::create()->findPk($this->relatedset_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aRelatedset->addPublications($this);
             */
        }

        return $this->aRelatedset;
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
    public function setDatespecificationRelatedByFirstpublicationdateId(Datespecification $v = null)
    {
        if ($v === null) {
            $this->setFirstpublicationdateId(NULL);
        } else {
            $this->setFirstpublicationdateId($v->getId());
        }

        $this->aDatespecificationRelatedByFirstpublicationdateId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Datespecification object, it will not be re-added.
        if ($v !== null) {
            $v->addPublicationRelatedByFirstpublicationdateId($this);
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
    public function getDatespecificationRelatedByFirstpublicationdateId(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDatespecificationRelatedByFirstpublicationdateId === null && ($this->firstpublicationdate_id !== null) && $doQuery) {
            $this->aDatespecificationRelatedByFirstpublicationdateId = DatespecificationQuery::create()->findPk($this->firstpublicationdate_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDatespecificationRelatedByFirstpublicationdateId->addPublicationsRelatedByFirstpublicationdateId($this);
             */
        }

        return $this->aDatespecificationRelatedByFirstpublicationdateId;
    }

    /**
     * Declares an association between this object and a Font object.
     *
     * @param             Font $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFont(Font $v = null)
    {
        if ($v === null) {
            $this->setFontId(NULL);
        } else {
            $this->setFontId($v->getId());
        }

        $this->aFont = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Font object, it will not be re-added.
        if ($v !== null) {
            $v->addPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated Font object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Font The associated Font object.
     * @throws PropelException
     */
    public function getFont(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aFont === null && ($this->font_id !== null) && $doQuery) {
            $this->aFont = FontQuery::create()->findPk($this->font_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFont->addPublications($this);
             */
        }

        return $this->aFont;
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
        if ('PublicationDmRelatedByPublicationId' == $relationName) {
            $this->initPublicationDmsRelatedByPublicationId();
        }
        if ('PublicationDmRelatedByParent' == $relationName) {
            $this->initPublicationDmsRelatedByParent();
        }
        if ('PublicationMm' == $relationName) {
            $this->initPublicationMms();
        }
        if ('PublicationDs' == $relationName) {
            $this->initPublicationDss();
        }
        if ('PublicationMs' == $relationName) {
            $this->initPublicationMss();
        }
        if ('PublicationJaRelatedByPublicationId' == $relationName) {
            $this->initPublicationJasRelatedByPublicationId();
        }
        if ('PublicationJaRelatedByParent' == $relationName) {
            $this->initPublicationJasRelatedByParent();
        }
        if ('PublicationMms' == $relationName) {
            $this->initPublicationMmss();
        }
        if ('PublicationJ' == $relationName) {
            $this->initPublicationJs();
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
     * Clears out the collPublicationDmsRelatedByPublicationId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPublicationDmsRelatedByPublicationId()
     */
    public function clearPublicationDmsRelatedByPublicationId()
    {
        $this->collPublicationDmsRelatedByPublicationId = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationDmsRelatedByPublicationIdPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationDmsRelatedByPublicationId collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationDmsRelatedByPublicationId($v = true)
    {
        $this->collPublicationDmsRelatedByPublicationIdPartial = $v;
    }

    /**
     * Initializes the collPublicationDmsRelatedByPublicationId collection.
     *
     * By default this just sets the collPublicationDmsRelatedByPublicationId collection to an empty array (like clearcollPublicationDmsRelatedByPublicationId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationDmsRelatedByPublicationId($overrideExisting = true)
    {
        if (null !== $this->collPublicationDmsRelatedByPublicationId && !$overrideExisting) {
            return;
        }
        $this->collPublicationDmsRelatedByPublicationId = new PropelObjectCollection();
        $this->collPublicationDmsRelatedByPublicationId->setModel('PublicationDm');
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
    public function getPublicationDmsRelatedByPublicationId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationDmsRelatedByPublicationIdPartial && !$this->isNew();
        if (null === $this->collPublicationDmsRelatedByPublicationId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationDmsRelatedByPublicationId) {
                // return empty collection
                $this->initPublicationDmsRelatedByPublicationId();
            } else {
                $collPublicationDmsRelatedByPublicationId = PublicationDmQuery::create(null, $criteria)
                    ->filterByPublicationRelatedByPublicationId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationDmsRelatedByPublicationIdPartial && count($collPublicationDmsRelatedByPublicationId)) {
                      $this->initPublicationDmsRelatedByPublicationId(false);

                      foreach($collPublicationDmsRelatedByPublicationId as $obj) {
                        if (false == $this->collPublicationDmsRelatedByPublicationId->contains($obj)) {
                          $this->collPublicationDmsRelatedByPublicationId->append($obj);
                        }
                      }

                      $this->collPublicationDmsRelatedByPublicationIdPartial = true;
                    }

                    $collPublicationDmsRelatedByPublicationId->getInternalIterator()->rewind();
                    return $collPublicationDmsRelatedByPublicationId;
                }

                if($partial && $this->collPublicationDmsRelatedByPublicationId) {
                    foreach($this->collPublicationDmsRelatedByPublicationId as $obj) {
                        if($obj->isNew()) {
                            $collPublicationDmsRelatedByPublicationId[] = $obj;
                        }
                    }
                }

                $this->collPublicationDmsRelatedByPublicationId = $collPublicationDmsRelatedByPublicationId;
                $this->collPublicationDmsRelatedByPublicationIdPartial = false;
            }
        }

        return $this->collPublicationDmsRelatedByPublicationId;
    }

    /**
     * Sets a collection of PublicationDmRelatedByPublicationId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationDmsRelatedByPublicationId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationDmsRelatedByPublicationId(PropelCollection $publicationDmsRelatedByPublicationId, PropelPDO $con = null)
    {
        $publicationDmsRelatedByPublicationIdToDelete = $this->getPublicationDmsRelatedByPublicationId(new Criteria(), $con)->diff($publicationDmsRelatedByPublicationId);

        $this->publicationDmsRelatedByPublicationIdScheduledForDeletion = unserialize(serialize($publicationDmsRelatedByPublicationIdToDelete));

        foreach ($publicationDmsRelatedByPublicationIdToDelete as $publicationDmRelatedByPublicationIdRemoved) {
            $publicationDmRelatedByPublicationIdRemoved->setPublicationRelatedByPublicationId(null);
        }

        $this->collPublicationDmsRelatedByPublicationId = null;
        foreach ($publicationDmsRelatedByPublicationId as $publicationDmRelatedByPublicationId) {
            $this->addPublicationDmRelatedByPublicationId($publicationDmRelatedByPublicationId);
        }

        $this->collPublicationDmsRelatedByPublicationId = $publicationDmsRelatedByPublicationId;
        $this->collPublicationDmsRelatedByPublicationIdPartial = false;

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
    public function countPublicationDmsRelatedByPublicationId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationDmsRelatedByPublicationIdPartial && !$this->isNew();
        if (null === $this->collPublicationDmsRelatedByPublicationId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationDmsRelatedByPublicationId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationDmsRelatedByPublicationId());
            }
            $query = PublicationDmQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublicationRelatedByPublicationId($this)
                ->count($con);
        }

        return count($this->collPublicationDmsRelatedByPublicationId);
    }

    /**
     * Method called to associate a PublicationDm object to this object
     * through the PublicationDm foreign key attribute.
     *
     * @param    PublicationDm $l PublicationDm
     * @return Publication The current object (for fluent API support)
     */
    public function addPublicationDmRelatedByPublicationId(PublicationDm $l)
    {
        if ($this->collPublicationDmsRelatedByPublicationId === null) {
            $this->initPublicationDmsRelatedByPublicationId();
            $this->collPublicationDmsRelatedByPublicationIdPartial = true;
        }
        if (!in_array($l, $this->collPublicationDmsRelatedByPublicationId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationDmRelatedByPublicationId($l);
        }

        return $this;
    }

    /**
     * @param	PublicationDmRelatedByPublicationId $publicationDmRelatedByPublicationId The publicationDmRelatedByPublicationId object to add.
     */
    protected function doAddPublicationDmRelatedByPublicationId($publicationDmRelatedByPublicationId)
    {
        $this->collPublicationDmsRelatedByPublicationId[]= $publicationDmRelatedByPublicationId;
        $publicationDmRelatedByPublicationId->setPublicationRelatedByPublicationId($this);
    }

    /**
     * @param	PublicationDmRelatedByPublicationId $publicationDmRelatedByPublicationId The publicationDmRelatedByPublicationId object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removePublicationDmRelatedByPublicationId($publicationDmRelatedByPublicationId)
    {
        if ($this->getPublicationDmsRelatedByPublicationId()->contains($publicationDmRelatedByPublicationId)) {
            $this->collPublicationDmsRelatedByPublicationId->remove($this->collPublicationDmsRelatedByPublicationId->search($publicationDmRelatedByPublicationId));
            if (null === $this->publicationDmsRelatedByPublicationIdScheduledForDeletion) {
                $this->publicationDmsRelatedByPublicationIdScheduledForDeletion = clone $this->collPublicationDmsRelatedByPublicationId;
                $this->publicationDmsRelatedByPublicationIdScheduledForDeletion->clear();
            }
            $this->publicationDmsRelatedByPublicationIdScheduledForDeletion[]= clone $publicationDmRelatedByPublicationId;
            $publicationDmRelatedByPublicationId->setPublicationRelatedByPublicationId(null);
        }

        return $this;
    }

    /**
     * Clears out the collPublicationDmsRelatedByParent collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPublicationDmsRelatedByParent()
     */
    public function clearPublicationDmsRelatedByParent()
    {
        $this->collPublicationDmsRelatedByParent = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationDmsRelatedByParentPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationDmsRelatedByParent collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationDmsRelatedByParent($v = true)
    {
        $this->collPublicationDmsRelatedByParentPartial = $v;
    }

    /**
     * Initializes the collPublicationDmsRelatedByParent collection.
     *
     * By default this just sets the collPublicationDmsRelatedByParent collection to an empty array (like clearcollPublicationDmsRelatedByParent());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationDmsRelatedByParent($overrideExisting = true)
    {
        if (null !== $this->collPublicationDmsRelatedByParent && !$overrideExisting) {
            return;
        }
        $this->collPublicationDmsRelatedByParent = new PropelObjectCollection();
        $this->collPublicationDmsRelatedByParent->setModel('PublicationDm');
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
    public function getPublicationDmsRelatedByParent($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationDmsRelatedByParentPartial && !$this->isNew();
        if (null === $this->collPublicationDmsRelatedByParent || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationDmsRelatedByParent) {
                // return empty collection
                $this->initPublicationDmsRelatedByParent();
            } else {
                $collPublicationDmsRelatedByParent = PublicationDmQuery::create(null, $criteria)
                    ->filterByPublicationRelatedByParent($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationDmsRelatedByParentPartial && count($collPublicationDmsRelatedByParent)) {
                      $this->initPublicationDmsRelatedByParent(false);

                      foreach($collPublicationDmsRelatedByParent as $obj) {
                        if (false == $this->collPublicationDmsRelatedByParent->contains($obj)) {
                          $this->collPublicationDmsRelatedByParent->append($obj);
                        }
                      }

                      $this->collPublicationDmsRelatedByParentPartial = true;
                    }

                    $collPublicationDmsRelatedByParent->getInternalIterator()->rewind();
                    return $collPublicationDmsRelatedByParent;
                }

                if($partial && $this->collPublicationDmsRelatedByParent) {
                    foreach($this->collPublicationDmsRelatedByParent as $obj) {
                        if($obj->isNew()) {
                            $collPublicationDmsRelatedByParent[] = $obj;
                        }
                    }
                }

                $this->collPublicationDmsRelatedByParent = $collPublicationDmsRelatedByParent;
                $this->collPublicationDmsRelatedByParentPartial = false;
            }
        }

        return $this->collPublicationDmsRelatedByParent;
    }

    /**
     * Sets a collection of PublicationDmRelatedByParent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationDmsRelatedByParent A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationDmsRelatedByParent(PropelCollection $publicationDmsRelatedByParent, PropelPDO $con = null)
    {
        $publicationDmsRelatedByParentToDelete = $this->getPublicationDmsRelatedByParent(new Criteria(), $con)->diff($publicationDmsRelatedByParent);

        $this->publicationDmsRelatedByParentScheduledForDeletion = unserialize(serialize($publicationDmsRelatedByParentToDelete));

        foreach ($publicationDmsRelatedByParentToDelete as $publicationDmRelatedByParentRemoved) {
            $publicationDmRelatedByParentRemoved->setPublicationRelatedByParent(null);
        }

        $this->collPublicationDmsRelatedByParent = null;
        foreach ($publicationDmsRelatedByParent as $publicationDmRelatedByParent) {
            $this->addPublicationDmRelatedByParent($publicationDmRelatedByParent);
        }

        $this->collPublicationDmsRelatedByParent = $publicationDmsRelatedByParent;
        $this->collPublicationDmsRelatedByParentPartial = false;

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
    public function countPublicationDmsRelatedByParent(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationDmsRelatedByParentPartial && !$this->isNew();
        if (null === $this->collPublicationDmsRelatedByParent || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationDmsRelatedByParent) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationDmsRelatedByParent());
            }
            $query = PublicationDmQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublicationRelatedByParent($this)
                ->count($con);
        }

        return count($this->collPublicationDmsRelatedByParent);
    }

    /**
     * Method called to associate a PublicationDm object to this object
     * through the PublicationDm foreign key attribute.
     *
     * @param    PublicationDm $l PublicationDm
     * @return Publication The current object (for fluent API support)
     */
    public function addPublicationDmRelatedByParent(PublicationDm $l)
    {
        if ($this->collPublicationDmsRelatedByParent === null) {
            $this->initPublicationDmsRelatedByParent();
            $this->collPublicationDmsRelatedByParentPartial = true;
        }
        if (!in_array($l, $this->collPublicationDmsRelatedByParent->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationDmRelatedByParent($l);
        }

        return $this;
    }

    /**
     * @param	PublicationDmRelatedByParent $publicationDmRelatedByParent The publicationDmRelatedByParent object to add.
     */
    protected function doAddPublicationDmRelatedByParent($publicationDmRelatedByParent)
    {
        $this->collPublicationDmsRelatedByParent[]= $publicationDmRelatedByParent;
        $publicationDmRelatedByParent->setPublicationRelatedByParent($this);
    }

    /**
     * @param	PublicationDmRelatedByParent $publicationDmRelatedByParent The publicationDmRelatedByParent object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removePublicationDmRelatedByParent($publicationDmRelatedByParent)
    {
        if ($this->getPublicationDmsRelatedByParent()->contains($publicationDmRelatedByParent)) {
            $this->collPublicationDmsRelatedByParent->remove($this->collPublicationDmsRelatedByParent->search($publicationDmRelatedByParent));
            if (null === $this->publicationDmsRelatedByParentScheduledForDeletion) {
                $this->publicationDmsRelatedByParentScheduledForDeletion = clone $this->collPublicationDmsRelatedByParent;
                $this->publicationDmsRelatedByParentScheduledForDeletion->clear();
            }
            $this->publicationDmsRelatedByParentScheduledForDeletion[]= $publicationDmRelatedByParent;
            $publicationDmRelatedByParent->setPublicationRelatedByParent(null);
        }

        return $this;
    }

    /**
     * Clears out the collPublicationMms collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPublicationMms()
     */
    public function clearPublicationMms()
    {
        $this->collPublicationMms = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationMmsPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationMms collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationMms($v = true)
    {
        $this->collPublicationMmsPartial = $v;
    }

    /**
     * Initializes the collPublicationMms collection.
     *
     * By default this just sets the collPublicationMms collection to an empty array (like clearcollPublicationMms());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationMms($overrideExisting = true)
    {
        if (null !== $this->collPublicationMms && !$overrideExisting) {
            return;
        }
        $this->collPublicationMms = new PropelObjectCollection();
        $this->collPublicationMms->setModel('PublicationMm');
    }

    /**
     * Gets an array of PublicationMm objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PublicationMm[] List of PublicationMm objects
     * @throws PropelException
     */
    public function getPublicationMms($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationMmsPartial && !$this->isNew();
        if (null === $this->collPublicationMms || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationMms) {
                // return empty collection
                $this->initPublicationMms();
            } else {
                $collPublicationMms = PublicationMmQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationMmsPartial && count($collPublicationMms)) {
                      $this->initPublicationMms(false);

                      foreach($collPublicationMms as $obj) {
                        if (false == $this->collPublicationMms->contains($obj)) {
                          $this->collPublicationMms->append($obj);
                        }
                      }

                      $this->collPublicationMmsPartial = true;
                    }

                    $collPublicationMms->getInternalIterator()->rewind();
                    return $collPublicationMms;
                }

                if($partial && $this->collPublicationMms) {
                    foreach($this->collPublicationMms as $obj) {
                        if($obj->isNew()) {
                            $collPublicationMms[] = $obj;
                        }
                    }
                }

                $this->collPublicationMms = $collPublicationMms;
                $this->collPublicationMmsPartial = false;
            }
        }

        return $this->collPublicationMms;
    }

    /**
     * Sets a collection of PublicationMm objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationMms A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationMms(PropelCollection $publicationMms, PropelPDO $con = null)
    {
        $publicationMmsToDelete = $this->getPublicationMms(new Criteria(), $con)->diff($publicationMms);

        $this->publicationMmsScheduledForDeletion = unserialize(serialize($publicationMmsToDelete));

        foreach ($publicationMmsToDelete as $publicationMmRemoved) {
            $publicationMmRemoved->setPublication(null);
        }

        $this->collPublicationMms = null;
        foreach ($publicationMms as $publicationMm) {
            $this->addPublicationMm($publicationMm);
        }

        $this->collPublicationMms = $publicationMms;
        $this->collPublicationMmsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PublicationMm objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PublicationMm objects.
     * @throws PropelException
     */
    public function countPublicationMms(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationMmsPartial && !$this->isNew();
        if (null === $this->collPublicationMms || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationMms) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationMms());
            }
            $query = PublicationMmQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collPublicationMms);
    }

    /**
     * Method called to associate a PublicationMm object to this object
     * through the PublicationMm foreign key attribute.
     *
     * @param    PublicationMm $l PublicationMm
     * @return Publication The current object (for fluent API support)
     */
    public function addPublicationMm(PublicationMm $l)
    {
        if ($this->collPublicationMms === null) {
            $this->initPublicationMms();
            $this->collPublicationMmsPartial = true;
        }
        if (!in_array($l, $this->collPublicationMms->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationMm($l);
        }

        return $this;
    }

    /**
     * @param	PublicationMm $publicationMm The publicationMm object to add.
     */
    protected function doAddPublicationMm($publicationMm)
    {
        $this->collPublicationMms[]= $publicationMm;
        $publicationMm->setPublication($this);
    }

    /**
     * @param	PublicationMm $publicationMm The publicationMm object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removePublicationMm($publicationMm)
    {
        if ($this->getPublicationMms()->contains($publicationMm)) {
            $this->collPublicationMms->remove($this->collPublicationMms->search($publicationMm));
            if (null === $this->publicationMmsScheduledForDeletion) {
                $this->publicationMmsScheduledForDeletion = clone $this->collPublicationMms;
                $this->publicationMmsScheduledForDeletion->clear();
            }
            $this->publicationMmsScheduledForDeletion[]= clone $publicationMm;
            $publicationMm->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related PublicationMms from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationMm[] List of PublicationMm objects
     */
    public function getPublicationMmsJoinVolume($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationMmQuery::create(null, $criteria);
        $query->joinWith('Volume', $join_behavior);

        return $this->getPublicationMms($query, $con);
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
    public function getPublicationDssJoinVolume($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationDsQuery::create(null, $criteria);
        $query->joinWith('Volume', $join_behavior);

        return $this->getPublicationDss($query, $con);
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
     * Clears out the collPublicationJasRelatedByPublicationId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPublicationJasRelatedByPublicationId()
     */
    public function clearPublicationJasRelatedByPublicationId()
    {
        $this->collPublicationJasRelatedByPublicationId = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationJasRelatedByPublicationIdPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationJasRelatedByPublicationId collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationJasRelatedByPublicationId($v = true)
    {
        $this->collPublicationJasRelatedByPublicationIdPartial = $v;
    }

    /**
     * Initializes the collPublicationJasRelatedByPublicationId collection.
     *
     * By default this just sets the collPublicationJasRelatedByPublicationId collection to an empty array (like clearcollPublicationJasRelatedByPublicationId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationJasRelatedByPublicationId($overrideExisting = true)
    {
        if (null !== $this->collPublicationJasRelatedByPublicationId && !$overrideExisting) {
            return;
        }
        $this->collPublicationJasRelatedByPublicationId = new PropelObjectCollection();
        $this->collPublicationJasRelatedByPublicationId->setModel('PublicationJa');
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
    public function getPublicationJasRelatedByPublicationId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationJasRelatedByPublicationIdPartial && !$this->isNew();
        if (null === $this->collPublicationJasRelatedByPublicationId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationJasRelatedByPublicationId) {
                // return empty collection
                $this->initPublicationJasRelatedByPublicationId();
            } else {
                $collPublicationJasRelatedByPublicationId = PublicationJaQuery::create(null, $criteria)
                    ->filterByPublicationRelatedByPublicationId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationJasRelatedByPublicationIdPartial && count($collPublicationJasRelatedByPublicationId)) {
                      $this->initPublicationJasRelatedByPublicationId(false);

                      foreach($collPublicationJasRelatedByPublicationId as $obj) {
                        if (false == $this->collPublicationJasRelatedByPublicationId->contains($obj)) {
                          $this->collPublicationJasRelatedByPublicationId->append($obj);
                        }
                      }

                      $this->collPublicationJasRelatedByPublicationIdPartial = true;
                    }

                    $collPublicationJasRelatedByPublicationId->getInternalIterator()->rewind();
                    return $collPublicationJasRelatedByPublicationId;
                }

                if($partial && $this->collPublicationJasRelatedByPublicationId) {
                    foreach($this->collPublicationJasRelatedByPublicationId as $obj) {
                        if($obj->isNew()) {
                            $collPublicationJasRelatedByPublicationId[] = $obj;
                        }
                    }
                }

                $this->collPublicationJasRelatedByPublicationId = $collPublicationJasRelatedByPublicationId;
                $this->collPublicationJasRelatedByPublicationIdPartial = false;
            }
        }

        return $this->collPublicationJasRelatedByPublicationId;
    }

    /**
     * Sets a collection of PublicationJaRelatedByPublicationId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationJasRelatedByPublicationId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationJasRelatedByPublicationId(PropelCollection $publicationJasRelatedByPublicationId, PropelPDO $con = null)
    {
        $publicationJasRelatedByPublicationIdToDelete = $this->getPublicationJasRelatedByPublicationId(new Criteria(), $con)->diff($publicationJasRelatedByPublicationId);

        $this->publicationJasRelatedByPublicationIdScheduledForDeletion = unserialize(serialize($publicationJasRelatedByPublicationIdToDelete));

        foreach ($publicationJasRelatedByPublicationIdToDelete as $publicationJaRelatedByPublicationIdRemoved) {
            $publicationJaRelatedByPublicationIdRemoved->setPublicationRelatedByPublicationId(null);
        }

        $this->collPublicationJasRelatedByPublicationId = null;
        foreach ($publicationJasRelatedByPublicationId as $publicationJaRelatedByPublicationId) {
            $this->addPublicationJaRelatedByPublicationId($publicationJaRelatedByPublicationId);
        }

        $this->collPublicationJasRelatedByPublicationId = $publicationJasRelatedByPublicationId;
        $this->collPublicationJasRelatedByPublicationIdPartial = false;

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
    public function countPublicationJasRelatedByPublicationId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationJasRelatedByPublicationIdPartial && !$this->isNew();
        if (null === $this->collPublicationJasRelatedByPublicationId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationJasRelatedByPublicationId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationJasRelatedByPublicationId());
            }
            $query = PublicationJaQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublicationRelatedByPublicationId($this)
                ->count($con);
        }

        return count($this->collPublicationJasRelatedByPublicationId);
    }

    /**
     * Method called to associate a PublicationJa object to this object
     * through the PublicationJa foreign key attribute.
     *
     * @param    PublicationJa $l PublicationJa
     * @return Publication The current object (for fluent API support)
     */
    public function addPublicationJaRelatedByPublicationId(PublicationJa $l)
    {
        if ($this->collPublicationJasRelatedByPublicationId === null) {
            $this->initPublicationJasRelatedByPublicationId();
            $this->collPublicationJasRelatedByPublicationIdPartial = true;
        }
        if (!in_array($l, $this->collPublicationJasRelatedByPublicationId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationJaRelatedByPublicationId($l);
        }

        return $this;
    }

    /**
     * @param	PublicationJaRelatedByPublicationId $publicationJaRelatedByPublicationId The publicationJaRelatedByPublicationId object to add.
     */
    protected function doAddPublicationJaRelatedByPublicationId($publicationJaRelatedByPublicationId)
    {
        $this->collPublicationJasRelatedByPublicationId[]= $publicationJaRelatedByPublicationId;
        $publicationJaRelatedByPublicationId->setPublicationRelatedByPublicationId($this);
    }

    /**
     * @param	PublicationJaRelatedByPublicationId $publicationJaRelatedByPublicationId The publicationJaRelatedByPublicationId object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removePublicationJaRelatedByPublicationId($publicationJaRelatedByPublicationId)
    {
        if ($this->getPublicationJasRelatedByPublicationId()->contains($publicationJaRelatedByPublicationId)) {
            $this->collPublicationJasRelatedByPublicationId->remove($this->collPublicationJasRelatedByPublicationId->search($publicationJaRelatedByPublicationId));
            if (null === $this->publicationJasRelatedByPublicationIdScheduledForDeletion) {
                $this->publicationJasRelatedByPublicationIdScheduledForDeletion = clone $this->collPublicationJasRelatedByPublicationId;
                $this->publicationJasRelatedByPublicationIdScheduledForDeletion->clear();
            }
            $this->publicationJasRelatedByPublicationIdScheduledForDeletion[]= clone $publicationJaRelatedByPublicationId;
            $publicationJaRelatedByPublicationId->setPublicationRelatedByPublicationId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related PublicationJasRelatedByPublicationId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationJa[] List of PublicationJa objects
     */
    public function getPublicationJasRelatedByPublicationIdJoinVolume($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationJaQuery::create(null, $criteria);
        $query->joinWith('Volume', $join_behavior);

        return $this->getPublicationJasRelatedByPublicationId($query, $con);
    }

    /**
     * Clears out the collPublicationJasRelatedByParent collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addPublicationJasRelatedByParent()
     */
    public function clearPublicationJasRelatedByParent()
    {
        $this->collPublicationJasRelatedByParent = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationJasRelatedByParentPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationJasRelatedByParent collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationJasRelatedByParent($v = true)
    {
        $this->collPublicationJasRelatedByParentPartial = $v;
    }

    /**
     * Initializes the collPublicationJasRelatedByParent collection.
     *
     * By default this just sets the collPublicationJasRelatedByParent collection to an empty array (like clearcollPublicationJasRelatedByParent());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationJasRelatedByParent($overrideExisting = true)
    {
        if (null !== $this->collPublicationJasRelatedByParent && !$overrideExisting) {
            return;
        }
        $this->collPublicationJasRelatedByParent = new PropelObjectCollection();
        $this->collPublicationJasRelatedByParent->setModel('PublicationJa');
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
    public function getPublicationJasRelatedByParent($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationJasRelatedByParentPartial && !$this->isNew();
        if (null === $this->collPublicationJasRelatedByParent || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationJasRelatedByParent) {
                // return empty collection
                $this->initPublicationJasRelatedByParent();
            } else {
                $collPublicationJasRelatedByParent = PublicationJaQuery::create(null, $criteria)
                    ->filterByPublicationRelatedByParent($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationJasRelatedByParentPartial && count($collPublicationJasRelatedByParent)) {
                      $this->initPublicationJasRelatedByParent(false);

                      foreach($collPublicationJasRelatedByParent as $obj) {
                        if (false == $this->collPublicationJasRelatedByParent->contains($obj)) {
                          $this->collPublicationJasRelatedByParent->append($obj);
                        }
                      }

                      $this->collPublicationJasRelatedByParentPartial = true;
                    }

                    $collPublicationJasRelatedByParent->getInternalIterator()->rewind();
                    return $collPublicationJasRelatedByParent;
                }

                if($partial && $this->collPublicationJasRelatedByParent) {
                    foreach($this->collPublicationJasRelatedByParent as $obj) {
                        if($obj->isNew()) {
                            $collPublicationJasRelatedByParent[] = $obj;
                        }
                    }
                }

                $this->collPublicationJasRelatedByParent = $collPublicationJasRelatedByParent;
                $this->collPublicationJasRelatedByParentPartial = false;
            }
        }

        return $this->collPublicationJasRelatedByParent;
    }

    /**
     * Sets a collection of PublicationJaRelatedByParent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationJasRelatedByParent A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setPublicationJasRelatedByParent(PropelCollection $publicationJasRelatedByParent, PropelPDO $con = null)
    {
        $publicationJasRelatedByParentToDelete = $this->getPublicationJasRelatedByParent(new Criteria(), $con)->diff($publicationJasRelatedByParent);

        $this->publicationJasRelatedByParentScheduledForDeletion = unserialize(serialize($publicationJasRelatedByParentToDelete));

        foreach ($publicationJasRelatedByParentToDelete as $publicationJaRelatedByParentRemoved) {
            $publicationJaRelatedByParentRemoved->setPublicationRelatedByParent(null);
        }

        $this->collPublicationJasRelatedByParent = null;
        foreach ($publicationJasRelatedByParent as $publicationJaRelatedByParent) {
            $this->addPublicationJaRelatedByParent($publicationJaRelatedByParent);
        }

        $this->collPublicationJasRelatedByParent = $publicationJasRelatedByParent;
        $this->collPublicationJasRelatedByParentPartial = false;

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
    public function countPublicationJasRelatedByParent(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationJasRelatedByParentPartial && !$this->isNew();
        if (null === $this->collPublicationJasRelatedByParent || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationJasRelatedByParent) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationJasRelatedByParent());
            }
            $query = PublicationJaQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublicationRelatedByParent($this)
                ->count($con);
        }

        return count($this->collPublicationJasRelatedByParent);
    }

    /**
     * Method called to associate a PublicationJa object to this object
     * through the PublicationJa foreign key attribute.
     *
     * @param    PublicationJa $l PublicationJa
     * @return Publication The current object (for fluent API support)
     */
    public function addPublicationJaRelatedByParent(PublicationJa $l)
    {
        if ($this->collPublicationJasRelatedByParent === null) {
            $this->initPublicationJasRelatedByParent();
            $this->collPublicationJasRelatedByParentPartial = true;
        }
        if (!in_array($l, $this->collPublicationJasRelatedByParent->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationJaRelatedByParent($l);
        }

        return $this;
    }

    /**
     * @param	PublicationJaRelatedByParent $publicationJaRelatedByParent The publicationJaRelatedByParent object to add.
     */
    protected function doAddPublicationJaRelatedByParent($publicationJaRelatedByParent)
    {
        $this->collPublicationJasRelatedByParent[]= $publicationJaRelatedByParent;
        $publicationJaRelatedByParent->setPublicationRelatedByParent($this);
    }

    /**
     * @param	PublicationJaRelatedByParent $publicationJaRelatedByParent The publicationJaRelatedByParent object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removePublicationJaRelatedByParent($publicationJaRelatedByParent)
    {
        if ($this->getPublicationJasRelatedByParent()->contains($publicationJaRelatedByParent)) {
            $this->collPublicationJasRelatedByParent->remove($this->collPublicationJasRelatedByParent->search($publicationJaRelatedByParent));
            if (null === $this->publicationJasRelatedByParentScheduledForDeletion) {
                $this->publicationJasRelatedByParentScheduledForDeletion = clone $this->collPublicationJasRelatedByParent;
                $this->publicationJasRelatedByParentScheduledForDeletion->clear();
            }
            $this->publicationJasRelatedByParentScheduledForDeletion[]= clone $publicationJaRelatedByParent;
            $publicationJaRelatedByParent->setPublicationRelatedByParent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related PublicationJasRelatedByParent from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationJa[] List of PublicationJa objects
     */
    public function getPublicationJasRelatedByParentJoinVolume($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationJaQuery::create(null, $criteria);
        $query->joinWith('Volume', $join_behavior);

        return $this->getPublicationJasRelatedByParent($query, $con);
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
    public function getPublicationMmssJoinVolume($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationMmsQuery::create(null, $criteria);
        $query->joinWith('Volume', $join_behavior);

        return $this->getPublicationMmss($query, $con);
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
    public function getPersonPublicationsJoinPersonrole($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PersonPublicationQuery::create(null, $criteria);
        $query->joinWith('Personrole', $join_behavior);

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
    public function getPersonPublicationsJoinPerson($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PersonPublicationQuery::create(null, $criteria);
        $query->joinWith('Person', $join_behavior);

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
        $this->work_id = null;
        $this->place_id = null;
        $this->publicationdate_id = null;
        $this->firstpublicationdate_id = null;
        $this->printrun_id = null;
        $this->publishingcompany_id = null;
        $this->partner_id = null;
        $this->editiondescription = null;
        $this->digitaleditioneditor = null;
        $this->transcriptioncomment = null;
        $this->font_id = null;
        $this->comment = null;
        $this->relatedset_id = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
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
            if ($this->collPublicationDmsRelatedByPublicationId) {
                foreach ($this->collPublicationDmsRelatedByPublicationId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationDmsRelatedByParent) {
                foreach ($this->collPublicationDmsRelatedByParent as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationMms) {
                foreach ($this->collPublicationMms as $o) {
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
            if ($this->collPublicationJasRelatedByPublicationId) {
                foreach ($this->collPublicationJasRelatedByPublicationId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationJasRelatedByParent) {
                foreach ($this->collPublicationJasRelatedByParent as $o) {
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
            if ($this->collPublicationgroups) {
                foreach ($this->collPublicationgroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aWork instanceof Persistent) {
              $this->aWork->clearAllReferences($deep);
            }
            if ($this->aPublishingcompany instanceof Persistent) {
              $this->aPublishingcompany->clearAllReferences($deep);
            }
            if ($this->aPlace instanceof Persistent) {
              $this->aPlace->clearAllReferences($deep);
            }
            if ($this->aPrintrun instanceof Persistent) {
              $this->aPrintrun->clearAllReferences($deep);
            }
            if ($this->aRelatedset instanceof Persistent) {
              $this->aRelatedset->clearAllReferences($deep);
            }
            if ($this->aDatespecificationRelatedByPublicationdateId instanceof Persistent) {
              $this->aDatespecificationRelatedByPublicationdateId->clearAllReferences($deep);
            }
            if ($this->aDatespecificationRelatedByFirstpublicationdateId instanceof Persistent) {
              $this->aDatespecificationRelatedByFirstpublicationdateId->clearAllReferences($deep);
            }
            if ($this->aFont instanceof Persistent) {
              $this->aFont->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPublicationMs instanceof PropelCollection) {
            $this->collPublicationMs->clearIterator();
        }
        $this->collPublicationMs = null;
        if ($this->collPublicationDmsRelatedByPublicationId instanceof PropelCollection) {
            $this->collPublicationDmsRelatedByPublicationId->clearIterator();
        }
        $this->collPublicationDmsRelatedByPublicationId = null;
        if ($this->collPublicationDmsRelatedByParent instanceof PropelCollection) {
            $this->collPublicationDmsRelatedByParent->clearIterator();
        }
        $this->collPublicationDmsRelatedByParent = null;
        if ($this->collPublicationMms instanceof PropelCollection) {
            $this->collPublicationMms->clearIterator();
        }
        $this->collPublicationMms = null;
        if ($this->collPublicationDss instanceof PropelCollection) {
            $this->collPublicationDss->clearIterator();
        }
        $this->collPublicationDss = null;
        if ($this->collPublicationMss instanceof PropelCollection) {
            $this->collPublicationMss->clearIterator();
        }
        $this->collPublicationMss = null;
        if ($this->collPublicationJasRelatedByPublicationId instanceof PropelCollection) {
            $this->collPublicationJasRelatedByPublicationId->clearIterator();
        }
        $this->collPublicationJasRelatedByPublicationId = null;
        if ($this->collPublicationJasRelatedByParent instanceof PropelCollection) {
            $this->collPublicationJasRelatedByParent->clearIterator();
        }
        $this->collPublicationJasRelatedByParent = null;
        if ($this->collPublicationMmss instanceof PropelCollection) {
            $this->collPublicationMmss->clearIterator();
        }
        $this->collPublicationMmss = null;
        if ($this->collPublicationJs instanceof PropelCollection) {
            $this->collPublicationJs->clearIterator();
        }
        $this->collPublicationJs = null;
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
        if ($this->collPublicationgroups instanceof PropelCollection) {
            $this->collPublicationgroups->clearIterator();
        }
        $this->collPublicationgroups = null;
        $this->aWork = null;
        $this->aPublishingcompany = null;
        $this->aPlace = null;
        $this->aPrintrun = null;
        $this->aRelatedset = null;
        $this->aDatespecificationRelatedByPublicationdateId = null;
        $this->aDatespecificationRelatedByFirstpublicationdateId = null;
        $this->aFont = null;
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
    }    /**
     * Cascades the get to a related entity (possibly recursively)
     */

    public function getEmbeddedColumn1OfWork(){

        $relatedEntity = $this->getWork();
        return $relatedEntity->getAttributeByTableViewColumName("Titel");

    }    /**
     * Cascades the get to a related entity (possibly recursively)
     */

    public function getEmbeddedColumn2OfWork(){

        $relatedEntity = $this->getWork();
        return $relatedEntity->getAttributeByTableViewColumName("erster Autor");

    }    /**
     * Cascades the get to a related entity (possibly recursively)
     */

    public function getEmbeddedColumn3OfWork(){

        $relatedEntity = $this->getWork();
        return $relatedEntity->getAttributeByTableViewColumName("entstanden");

    }
}
