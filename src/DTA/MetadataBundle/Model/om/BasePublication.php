<?php

namespace DTA\MetadataBundle\Model\om;

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
use \PropelQuery;
use DTA\MetadataBundle\Model\Datespecification;
use DTA\MetadataBundle\Model\DatespecificationQuery;
use DTA\MetadataBundle\Model\Monograph;
use DTA\MetadataBundle\Model\MonographQuery;
use DTA\MetadataBundle\Model\Place;
use DTA\MetadataBundle\Model\PlaceQuery;
use DTA\MetadataBundle\Model\Printer;
use DTA\MetadataBundle\Model\PrinterQuery;
use DTA\MetadataBundle\Model\Publication;
use DTA\MetadataBundle\Model\PublicationPeer;
use DTA\MetadataBundle\Model\PublicationPublicationgroup;
use DTA\MetadataBundle\Model\PublicationPublicationgroupQuery;
use DTA\MetadataBundle\Model\PublicationQuery;
use DTA\MetadataBundle\Model\Publicationgroup;
use DTA\MetadataBundle\Model\PublicationgroupQuery;
use DTA\MetadataBundle\Model\Publisher;
use DTA\MetadataBundle\Model\PublisherQuery;
use DTA\MetadataBundle\Model\Publishingcompany;
use DTA\MetadataBundle\Model\PublishingcompanyQuery;
use DTA\MetadataBundle\Model\Relatedset;
use DTA\MetadataBundle\Model\RelatedsetQuery;
use DTA\MetadataBundle\Model\Source;
use DTA\MetadataBundle\Model\SourceQuery;
use DTA\MetadataBundle\Model\Task;
use DTA\MetadataBundle\Model\TaskQuery;
use DTA\MetadataBundle\Model\Title;
use DTA\MetadataBundle\Model\TitleQuery;
use DTA\MetadataBundle\Model\Translator;
use DTA\MetadataBundle\Model\TranslatorQuery;
use DTA\MetadataBundle\Model\Work;
use DTA\MetadataBundle\Model\WorkQuery;

abstract class BasePublication extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\PublicationPeer';

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
     * The value for the printrun field.
     * @var        string
     */
    protected $printrun;

    /**
     * The value for the printruncomment field.
     * @var        string
     */
    protected $printruncomment;

    /**
     * The value for the edition field.
     * @var        string
     */
    protected $edition;

    /**
     * The value for the numpages field.
     * @var        int
     */
    protected $numpages;

    /**
     * The value for the numpagesnormed field.
     * @var        int
     */
    protected $numpagesnormed;

    /**
     * The value for the bibliographiccitation field.
     * @var        string
     */
    protected $bibliographiccitation;

    /**
     * The value for the title_id field.
     * @var        int
     */
    protected $title_id;

    /**
     * The value for the publishingcompany_id field.
     * @var        int
     */
    protected $publishingcompany_id;

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
     * The value for the origindate_id field.
     * @var        int
     */
    protected $origindate_id;

    /**
     * The value for the relatedset_id field.
     * @var        int
     */
    protected $relatedset_id;

    /**
     * The value for the work_id field.
     * @var        int
     */
    protected $work_id;

    /**
     * The value for the publisher_id field.
     * @var        int
     */
    protected $publisher_id;

    /**
     * The value for the printer_id field.
     * @var        int
     */
    protected $printer_id;

    /**
     * The value for the translator_id field.
     * @var        int
     */
    protected $translator_id;

    /**
     * The value for the descendant_class field.
     * @var        string
     */
    protected $descendant_class;

    /**
     * @var        Work
     */
    protected $aWork;

    /**
     * @var        Publisher
     */
    protected $aPublisher;

    /**
     * @var        Printer
     */
    protected $aPrinter;

    /**
     * @var        Translator
     */
    protected $aTranslator;

    /**
     * @var        Relatedset
     */
    protected $aRelatedset;

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
    protected $aDatespecificationRelatedByOrigindateId;

    /**
     * @var        PropelObjectCollection|Monograph[] Collection to store aggregation of Monograph objects.
     */
    protected $collMonographs;
    protected $collMonographsPartial;

    /**
     * @var        PropelObjectCollection|PublicationPublicationgroup[] Collection to store aggregation of PublicationPublicationgroup objects.
     */
    protected $collPublicationPublicationgroups;
    protected $collPublicationPublicationgroupsPartial;

    /**
     * @var        PropelObjectCollection|Source[] Collection to store aggregation of Source objects.
     */
    protected $collSources;
    protected $collSourcesPartial;

    /**
     * @var        PropelObjectCollection|Task[] Collection to store aggregation of Task objects.
     */
    protected $collTasks;
    protected $collTasksPartial;

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

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationgroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $monographsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationPublicationgroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $sourcesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $tasksScheduledForDeletion = null;

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
     * Get the [printrun] column value.
     * Auflage
     * @return string
     */
    public function getPrintrun()
    {
        return $this->printrun;
    }

    /**
     * Get the [printruncomment] column value.
     *
     * @return string
     */
    public function getPrintruncomment()
    {
        return $this->printruncomment;
    }

    /**
     * Get the [edition] column value.
     *
     * @return string
     */
    public function getEdition()
    {
        return $this->edition;
    }

    /**
     * Get the [numpages] column value.
     *
     * @return int
     */
    public function getNumpages()
    {
        return $this->numpages;
    }

    /**
     * Get the [numpagesnormed] column value.
     *
     * @return int
     */
    public function getNumpagesnormed()
    {
        return $this->numpagesnormed;
    }

    /**
     * Get the [bibliographiccitation] column value.
     *
     * @return string
     */
    public function getBibliographiccitation()
    {
        return $this->bibliographiccitation;
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
     * Get the [publishingcompany_id] column value.
     *
     * @return int
     */
    public function getPublishingcompanyId()
    {
        return $this->publishingcompany_id;
    }

    /**
     * Get the [place_id] column value.
     *
     * @return int
     */
    public function getPlaceId()
    {
        return $this->place_id;
    }

    /**
     * Get the [publicationdate_id] column value.
     *
     * @return int
     */
    public function getPublicationdateId()
    {
        return $this->publicationdate_id;
    }

    /**
     * Get the [origindate_id] column value.
     *
     * @return int
     */
    public function getOrigindateId()
    {
        return $this->origindate_id;
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
     * Get the [work_id] column value.
     *
     * @return int
     */
    public function getWorkId()
    {
        return $this->work_id;
    }

    /**
     * Get the [publisher_id] column value.
     *
     * @return int
     */
    public function getPublisherId()
    {
        return $this->publisher_id;
    }

    /**
     * Get the [printer_id] column value.
     *
     * @return int
     */
    public function getPrinterId()
    {
        return $this->printer_id;
    }

    /**
     * Get the [translator_id] column value.
     *
     * @return int
     */
    public function getTranslatorId()
    {
        return $this->translator_id;
    }

    /**
     * Get the [descendant_class] column value.
     *
     * @return string
     */
    public function getDescendantClass()
    {
        return $this->descendant_class;
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
     * Set the value of [printrun] column.
     * Auflage
     * @param string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setPrintrun($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->printrun !== $v) {
            $this->printrun = $v;
            $this->modifiedColumns[] = PublicationPeer::PRINTRUN;
        }


        return $this;
    } // setPrintrun()

    /**
     * Set the value of [printruncomment] column.
     *
     * @param string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setPrintruncomment($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->printruncomment !== $v) {
            $this->printruncomment = $v;
            $this->modifiedColumns[] = PublicationPeer::PRINTRUNCOMMENT;
        }


        return $this;
    } // setPrintruncomment()

    /**
     * Set the value of [edition] column.
     *
     * @param string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setEdition($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->edition !== $v) {
            $this->edition = $v;
            $this->modifiedColumns[] = PublicationPeer::EDITION;
        }


        return $this;
    } // setEdition()

    /**
     * Set the value of [numpages] column.
     *
     * @param int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setNumpages($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->numpages !== $v) {
            $this->numpages = $v;
            $this->modifiedColumns[] = PublicationPeer::NUMPAGES;
        }


        return $this;
    } // setNumpages()

    /**
     * Set the value of [numpagesnormed] column.
     *
     * @param int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setNumpagesnormed($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->numpagesnormed !== $v) {
            $this->numpagesnormed = $v;
            $this->modifiedColumns[] = PublicationPeer::NUMPAGESNORMED;
        }


        return $this;
    } // setNumpagesnormed()

    /**
     * Set the value of [bibliographiccitation] column.
     *
     * @param string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setBibliographiccitation($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->bibliographiccitation !== $v) {
            $this->bibliographiccitation = $v;
            $this->modifiedColumns[] = PublicationPeer::BIBLIOGRAPHICCITATION;
        }


        return $this;
    } // setBibliographiccitation()

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
     * Set the value of [publishingcompany_id] column.
     *
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
     * Set the value of [place_id] column.
     *
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
     *
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
     * Set the value of [origindate_id] column.
     *
     * @param int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setOrigindateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->origindate_id !== $v) {
            $this->origindate_id = $v;
            $this->modifiedColumns[] = PublicationPeer::ORIGINDATE_ID;
        }

        if ($this->aDatespecificationRelatedByOrigindateId !== null && $this->aDatespecificationRelatedByOrigindateId->getId() !== $v) {
            $this->aDatespecificationRelatedByOrigindateId = null;
        }


        return $this;
    } // setOrigindateId()

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
     * Set the value of [publisher_id] column.
     *
     * @param int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setPublisherId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->publisher_id !== $v) {
            $this->publisher_id = $v;
            $this->modifiedColumns[] = PublicationPeer::PUBLISHER_ID;
        }

        if ($this->aPublisher !== null && $this->aPublisher->getId() !== $v) {
            $this->aPublisher = null;
        }


        return $this;
    } // setPublisherId()

    /**
     * Set the value of [printer_id] column.
     *
     * @param int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setPrinterId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->printer_id !== $v) {
            $this->printer_id = $v;
            $this->modifiedColumns[] = PublicationPeer::PRINTER_ID;
        }

        if ($this->aPrinter !== null && $this->aPrinter->getId() !== $v) {
            $this->aPrinter = null;
        }


        return $this;
    } // setPrinterId()

    /**
     * Set the value of [translator_id] column.
     *
     * @param int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setTranslatorId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->translator_id !== $v) {
            $this->translator_id = $v;
            $this->modifiedColumns[] = PublicationPeer::TRANSLATOR_ID;
        }

        if ($this->aTranslator !== null && $this->aTranslator->getId() !== $v) {
            $this->aTranslator = null;
        }


        return $this;
    } // setTranslatorId()

    /**
     * Set the value of [descendant_class] column.
     *
     * @param string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setDescendantClass($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->descendant_class !== $v) {
            $this->descendant_class = $v;
            $this->modifiedColumns[] = PublicationPeer::DESCENDANT_CLASS;
        }


        return $this;
    } // setDescendantClass()

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
            $this->printrun = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->printruncomment = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->edition = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->numpages = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->numpagesnormed = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->bibliographiccitation = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->title_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->publishingcompany_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->place_id = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->publicationdate_id = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->origindate_id = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
            $this->relatedset_id = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->work_id = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
            $this->publisher_id = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
            $this->printer_id = ($row[$startcol + 15] !== null) ? (int) $row[$startcol + 15] : null;
            $this->translator_id = ($row[$startcol + 16] !== null) ? (int) $row[$startcol + 16] : null;
            $this->descendant_class = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 18; // 18 = PublicationPeer::NUM_HYDRATE_COLUMNS.

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
        if ($this->aPublishingcompany !== null && $this->publishingcompany_id !== $this->aPublishingcompany->getId()) {
            $this->aPublishingcompany = null;
        }
        if ($this->aPlace !== null && $this->place_id !== $this->aPlace->getId()) {
            $this->aPlace = null;
        }
        if ($this->aDatespecificationRelatedByPublicationdateId !== null && $this->publicationdate_id !== $this->aDatespecificationRelatedByPublicationdateId->getId()) {
            $this->aDatespecificationRelatedByPublicationdateId = null;
        }
        if ($this->aDatespecificationRelatedByOrigindateId !== null && $this->origindate_id !== $this->aDatespecificationRelatedByOrigindateId->getId()) {
            $this->aDatespecificationRelatedByOrigindateId = null;
        }
        if ($this->aRelatedset !== null && $this->relatedset_id !== $this->aRelatedset->getId()) {
            $this->aRelatedset = null;
        }
        if ($this->aWork !== null && $this->work_id !== $this->aWork->getId()) {
            $this->aWork = null;
        }
        if ($this->aPublisher !== null && $this->publisher_id !== $this->aPublisher->getId()) {
            $this->aPublisher = null;
        }
        if ($this->aPrinter !== null && $this->printer_id !== $this->aPrinter->getId()) {
            $this->aPrinter = null;
        }
        if ($this->aTranslator !== null && $this->translator_id !== $this->aTranslator->getId()) {
            $this->aTranslator = null;
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
            $this->aPublisher = null;
            $this->aPrinter = null;
            $this->aTranslator = null;
            $this->aRelatedset = null;
            $this->aTitle = null;
            $this->aPublishingcompany = null;
            $this->aPlace = null;
            $this->aDatespecificationRelatedByPublicationdateId = null;
            $this->aDatespecificationRelatedByOrigindateId = null;
            $this->collMonographs = null;

            $this->collPublicationPublicationgroups = null;

            $this->collSources = null;

            $this->collTasks = null;

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

            if ($this->aPublisher !== null) {
                if ($this->aPublisher->isModified() || $this->aPublisher->isNew()) {
                    $affectedRows += $this->aPublisher->save($con);
                }
                $this->setPublisher($this->aPublisher);
            }

            if ($this->aPrinter !== null) {
                if ($this->aPrinter->isModified() || $this->aPrinter->isNew()) {
                    $affectedRows += $this->aPrinter->save($con);
                }
                $this->setPrinter($this->aPrinter);
            }

            if ($this->aTranslator !== null) {
                if ($this->aTranslator->isModified() || $this->aTranslator->isNew()) {
                    $affectedRows += $this->aTranslator->save($con);
                }
                $this->setTranslator($this->aTranslator);
            }

            if ($this->aRelatedset !== null) {
                if ($this->aRelatedset->isModified() || $this->aRelatedset->isNew()) {
                    $affectedRows += $this->aRelatedset->save($con);
                }
                $this->setRelatedset($this->aRelatedset);
            }

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

            if ($this->aDatespecificationRelatedByOrigindateId !== null) {
                if ($this->aDatespecificationRelatedByOrigindateId->isModified() || $this->aDatespecificationRelatedByOrigindateId->isNew()) {
                    $affectedRows += $this->aDatespecificationRelatedByOrigindateId->save($con);
                }
                $this->setDatespecificationRelatedByOrigindateId($this->aDatespecificationRelatedByOrigindateId);
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

            if ($this->monographsScheduledForDeletion !== null) {
                if (!$this->monographsScheduledForDeletion->isEmpty()) {
                    MonographQuery::create()
                        ->filterByPrimaryKeys($this->monographsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->monographsScheduledForDeletion = null;
                }
            }

            if ($this->collMonographs !== null) {
                foreach ($this->collMonographs as $referrerFK) {
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

            if ($this->sourcesScheduledForDeletion !== null) {
                if (!$this->sourcesScheduledForDeletion->isEmpty()) {
                    SourceQuery::create()
                        ->filterByPrimaryKeys($this->sourcesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->sourcesScheduledForDeletion = null;
                }
            }

            if ($this->collSources !== null) {
                foreach ($this->collSources as $referrerFK) {
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

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PublicationPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PublicationPeer::PRINTRUN)) {
            $modifiedColumns[':p' . $index++]  = '`printRun`';
        }
        if ($this->isColumnModified(PublicationPeer::PRINTRUNCOMMENT)) {
            $modifiedColumns[':p' . $index++]  = '`printRunComment`';
        }
        if ($this->isColumnModified(PublicationPeer::EDITION)) {
            $modifiedColumns[':p' . $index++]  = '`edition`';
        }
        if ($this->isColumnModified(PublicationPeer::NUMPAGES)) {
            $modifiedColumns[':p' . $index++]  = '`numPages`';
        }
        if ($this->isColumnModified(PublicationPeer::NUMPAGESNORMED)) {
            $modifiedColumns[':p' . $index++]  = '`numPagesNormed`';
        }
        if ($this->isColumnModified(PublicationPeer::BIBLIOGRAPHICCITATION)) {
            $modifiedColumns[':p' . $index++]  = '`bibliographicCitation`';
        }
        if ($this->isColumnModified(PublicationPeer::TITLE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`title_id`';
        }
        if ($this->isColumnModified(PublicationPeer::PUBLISHINGCOMPANY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`publishingCompany_id`';
        }
        if ($this->isColumnModified(PublicationPeer::PLACE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`place_id`';
        }
        if ($this->isColumnModified(PublicationPeer::PUBLICATIONDATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`publicationDate_id`';
        }
        if ($this->isColumnModified(PublicationPeer::ORIGINDATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`originDate_id`';
        }
        if ($this->isColumnModified(PublicationPeer::RELATEDSET_ID)) {
            $modifiedColumns[':p' . $index++]  = '`relatedSet_id`';
        }
        if ($this->isColumnModified(PublicationPeer::WORK_ID)) {
            $modifiedColumns[':p' . $index++]  = '`work_id`';
        }
        if ($this->isColumnModified(PublicationPeer::PUBLISHER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`publisher_id`';
        }
        if ($this->isColumnModified(PublicationPeer::PRINTER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`printer_id`';
        }
        if ($this->isColumnModified(PublicationPeer::TRANSLATOR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`translator_id`';
        }
        if ($this->isColumnModified(PublicationPeer::DESCENDANT_CLASS)) {
            $modifiedColumns[':p' . $index++]  = '`descendant_class`';
        }

        $sql = sprintf(
            'INSERT INTO `publication` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`printRun`':
                        $stmt->bindValue($identifier, $this->printrun, PDO::PARAM_STR);
                        break;
                    case '`printRunComment`':
                        $stmt->bindValue($identifier, $this->printruncomment, PDO::PARAM_STR);
                        break;
                    case '`edition`':
                        $stmt->bindValue($identifier, $this->edition, PDO::PARAM_STR);
                        break;
                    case '`numPages`':
                        $stmt->bindValue($identifier, $this->numpages, PDO::PARAM_INT);
                        break;
                    case '`numPagesNormed`':
                        $stmt->bindValue($identifier, $this->numpagesnormed, PDO::PARAM_INT);
                        break;
                    case '`bibliographicCitation`':
                        $stmt->bindValue($identifier, $this->bibliographiccitation, PDO::PARAM_STR);
                        break;
                    case '`title_id`':
                        $stmt->bindValue($identifier, $this->title_id, PDO::PARAM_INT);
                        break;
                    case '`publishingCompany_id`':
                        $stmt->bindValue($identifier, $this->publishingcompany_id, PDO::PARAM_INT);
                        break;
                    case '`place_id`':
                        $stmt->bindValue($identifier, $this->place_id, PDO::PARAM_INT);
                        break;
                    case '`publicationDate_id`':
                        $stmt->bindValue($identifier, $this->publicationdate_id, PDO::PARAM_INT);
                        break;
                    case '`originDate_id`':
                        $stmt->bindValue($identifier, $this->origindate_id, PDO::PARAM_INT);
                        break;
                    case '`relatedSet_id`':
                        $stmt->bindValue($identifier, $this->relatedset_id, PDO::PARAM_INT);
                        break;
                    case '`work_id`':
                        $stmt->bindValue($identifier, $this->work_id, PDO::PARAM_INT);
                        break;
                    case '`publisher_id`':
                        $stmt->bindValue($identifier, $this->publisher_id, PDO::PARAM_INT);
                        break;
                    case '`printer_id`':
                        $stmt->bindValue($identifier, $this->printer_id, PDO::PARAM_INT);
                        break;
                    case '`translator_id`':
                        $stmt->bindValue($identifier, $this->translator_id, PDO::PARAM_INT);
                        break;
                    case '`descendant_class`':
                        $stmt->bindValue($identifier, $this->descendant_class, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

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

            if ($this->aPublisher !== null) {
                if (!$this->aPublisher->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPublisher->getValidationFailures());
                }
            }

            if ($this->aPrinter !== null) {
                if (!$this->aPrinter->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPrinter->getValidationFailures());
                }
            }

            if ($this->aTranslator !== null) {
                if (!$this->aTranslator->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aTranslator->getValidationFailures());
                }
            }

            if ($this->aRelatedset !== null) {
                if (!$this->aRelatedset->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aRelatedset->getValidationFailures());
                }
            }

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

            if ($this->aDatespecificationRelatedByOrigindateId !== null) {
                if (!$this->aDatespecificationRelatedByOrigindateId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDatespecificationRelatedByOrigindateId->getValidationFailures());
                }
            }


            if (($retval = PublicationPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collMonographs !== null) {
                    foreach ($this->collMonographs as $referrerFK) {
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

                if ($this->collSources !== null) {
                    foreach ($this->collSources as $referrerFK) {
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
                return $this->getPrintrun();
                break;
            case 2:
                return $this->getPrintruncomment();
                break;
            case 3:
                return $this->getEdition();
                break;
            case 4:
                return $this->getNumpages();
                break;
            case 5:
                return $this->getNumpagesnormed();
                break;
            case 6:
                return $this->getBibliographiccitation();
                break;
            case 7:
                return $this->getTitleId();
                break;
            case 8:
                return $this->getPublishingcompanyId();
                break;
            case 9:
                return $this->getPlaceId();
                break;
            case 10:
                return $this->getPublicationdateId();
                break;
            case 11:
                return $this->getOrigindateId();
                break;
            case 12:
                return $this->getRelatedsetId();
                break;
            case 13:
                return $this->getWorkId();
                break;
            case 14:
                return $this->getPublisherId();
                break;
            case 15:
                return $this->getPrinterId();
                break;
            case 16:
                return $this->getTranslatorId();
                break;
            case 17:
                return $this->getDescendantClass();
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
            $keys[1] => $this->getPrintrun(),
            $keys[2] => $this->getPrintruncomment(),
            $keys[3] => $this->getEdition(),
            $keys[4] => $this->getNumpages(),
            $keys[5] => $this->getNumpagesnormed(),
            $keys[6] => $this->getBibliographiccitation(),
            $keys[7] => $this->getTitleId(),
            $keys[8] => $this->getPublishingcompanyId(),
            $keys[9] => $this->getPlaceId(),
            $keys[10] => $this->getPublicationdateId(),
            $keys[11] => $this->getOrigindateId(),
            $keys[12] => $this->getRelatedsetId(),
            $keys[13] => $this->getWorkId(),
            $keys[14] => $this->getPublisherId(),
            $keys[15] => $this->getPrinterId(),
            $keys[16] => $this->getTranslatorId(),
            $keys[17] => $this->getDescendantClass(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aWork) {
                $result['Work'] = $this->aWork->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPublisher) {
                $result['Publisher'] = $this->aPublisher->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPrinter) {
                $result['Printer'] = $this->aPrinter->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aTranslator) {
                $result['Translator'] = $this->aTranslator->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aRelatedset) {
                $result['Relatedset'] = $this->aRelatedset->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
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
            if (null !== $this->aDatespecificationRelatedByOrigindateId) {
                $result['DatespecificationRelatedByOrigindateId'] = $this->aDatespecificationRelatedByOrigindateId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collMonographs) {
                $result['Monographs'] = $this->collMonographs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationPublicationgroups) {
                $result['PublicationPublicationgroups'] = $this->collPublicationPublicationgroups->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSources) {
                $result['Sources'] = $this->collSources->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTasks) {
                $result['Tasks'] = $this->collTasks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $this->setPrintrun($value);
                break;
            case 2:
                $this->setPrintruncomment($value);
                break;
            case 3:
                $this->setEdition($value);
                break;
            case 4:
                $this->setNumpages($value);
                break;
            case 5:
                $this->setNumpagesnormed($value);
                break;
            case 6:
                $this->setBibliographiccitation($value);
                break;
            case 7:
                $this->setTitleId($value);
                break;
            case 8:
                $this->setPublishingcompanyId($value);
                break;
            case 9:
                $this->setPlaceId($value);
                break;
            case 10:
                $this->setPublicationdateId($value);
                break;
            case 11:
                $this->setOrigindateId($value);
                break;
            case 12:
                $this->setRelatedsetId($value);
                break;
            case 13:
                $this->setWorkId($value);
                break;
            case 14:
                $this->setPublisherId($value);
                break;
            case 15:
                $this->setPrinterId($value);
                break;
            case 16:
                $this->setTranslatorId($value);
                break;
            case 17:
                $this->setDescendantClass($value);
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
        if (array_key_exists($keys[1], $arr)) $this->setPrintrun($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPrintruncomment($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setEdition($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setNumpages($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setNumpagesnormed($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setBibliographiccitation($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setTitleId($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setPublishingcompanyId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setPlaceId($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setPublicationdateId($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setOrigindateId($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setRelatedsetId($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setWorkId($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setPublisherId($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setPrinterId($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setTranslatorId($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setDescendantClass($arr[$keys[17]]);
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
        if ($this->isColumnModified(PublicationPeer::PRINTRUN)) $criteria->add(PublicationPeer::PRINTRUN, $this->printrun);
        if ($this->isColumnModified(PublicationPeer::PRINTRUNCOMMENT)) $criteria->add(PublicationPeer::PRINTRUNCOMMENT, $this->printruncomment);
        if ($this->isColumnModified(PublicationPeer::EDITION)) $criteria->add(PublicationPeer::EDITION, $this->edition);
        if ($this->isColumnModified(PublicationPeer::NUMPAGES)) $criteria->add(PublicationPeer::NUMPAGES, $this->numpages);
        if ($this->isColumnModified(PublicationPeer::NUMPAGESNORMED)) $criteria->add(PublicationPeer::NUMPAGESNORMED, $this->numpagesnormed);
        if ($this->isColumnModified(PublicationPeer::BIBLIOGRAPHICCITATION)) $criteria->add(PublicationPeer::BIBLIOGRAPHICCITATION, $this->bibliographiccitation);
        if ($this->isColumnModified(PublicationPeer::TITLE_ID)) $criteria->add(PublicationPeer::TITLE_ID, $this->title_id);
        if ($this->isColumnModified(PublicationPeer::PUBLISHINGCOMPANY_ID)) $criteria->add(PublicationPeer::PUBLISHINGCOMPANY_ID, $this->publishingcompany_id);
        if ($this->isColumnModified(PublicationPeer::PLACE_ID)) $criteria->add(PublicationPeer::PLACE_ID, $this->place_id);
        if ($this->isColumnModified(PublicationPeer::PUBLICATIONDATE_ID)) $criteria->add(PublicationPeer::PUBLICATIONDATE_ID, $this->publicationdate_id);
        if ($this->isColumnModified(PublicationPeer::ORIGINDATE_ID)) $criteria->add(PublicationPeer::ORIGINDATE_ID, $this->origindate_id);
        if ($this->isColumnModified(PublicationPeer::RELATEDSET_ID)) $criteria->add(PublicationPeer::RELATEDSET_ID, $this->relatedset_id);
        if ($this->isColumnModified(PublicationPeer::WORK_ID)) $criteria->add(PublicationPeer::WORK_ID, $this->work_id);
        if ($this->isColumnModified(PublicationPeer::PUBLISHER_ID)) $criteria->add(PublicationPeer::PUBLISHER_ID, $this->publisher_id);
        if ($this->isColumnModified(PublicationPeer::PRINTER_ID)) $criteria->add(PublicationPeer::PRINTER_ID, $this->printer_id);
        if ($this->isColumnModified(PublicationPeer::TRANSLATOR_ID)) $criteria->add(PublicationPeer::TRANSLATOR_ID, $this->translator_id);
        if ($this->isColumnModified(PublicationPeer::DESCENDANT_CLASS)) $criteria->add(PublicationPeer::DESCENDANT_CLASS, $this->descendant_class);

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
        $copyObj->setPrintrun($this->getPrintrun());
        $copyObj->setPrintruncomment($this->getPrintruncomment());
        $copyObj->setEdition($this->getEdition());
        $copyObj->setNumpages($this->getNumpages());
        $copyObj->setNumpagesnormed($this->getNumpagesnormed());
        $copyObj->setBibliographiccitation($this->getBibliographiccitation());
        $copyObj->setTitleId($this->getTitleId());
        $copyObj->setPublishingcompanyId($this->getPublishingcompanyId());
        $copyObj->setPlaceId($this->getPlaceId());
        $copyObj->setPublicationdateId($this->getPublicationdateId());
        $copyObj->setOrigindateId($this->getOrigindateId());
        $copyObj->setRelatedsetId($this->getRelatedsetId());
        $copyObj->setWorkId($this->getWorkId());
        $copyObj->setPublisherId($this->getPublisherId());
        $copyObj->setPrinterId($this->getPrinterId());
        $copyObj->setTranslatorId($this->getTranslatorId());
        $copyObj->setDescendantClass($this->getDescendantClass());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getMonographs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMonograph($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationPublicationgroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationPublicationgroup($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSources() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSource($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTasks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTask($relObj->copy($deepCopy));
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
     * Declares an association between this object and a Publisher object.
     *
     * @param             Publisher $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPublisher(Publisher $v = null)
    {
        if ($v === null) {
            $this->setPublisherId(NULL);
        } else {
            $this->setPublisherId($v->getId());
        }

        $this->aPublisher = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Publisher object, it will not be re-added.
        if ($v !== null) {
            $v->addPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated Publisher object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Publisher The associated Publisher object.
     * @throws PropelException
     */
    public function getPublisher(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPublisher === null && ($this->publisher_id !== null) && $doQuery) {
            $this->aPublisher = PublisherQuery::create()
                ->filterByPublication($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPublisher->addPublications($this);
             */
        }

        return $this->aPublisher;
    }

    /**
     * Declares an association between this object and a Printer object.
     *
     * @param             Printer $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPrinter(Printer $v = null)
    {
        if ($v === null) {
            $this->setPrinterId(NULL);
        } else {
            $this->setPrinterId($v->getId());
        }

        $this->aPrinter = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Printer object, it will not be re-added.
        if ($v !== null) {
            $v->addPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated Printer object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Printer The associated Printer object.
     * @throws PropelException
     */
    public function getPrinter(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPrinter === null && ($this->printer_id !== null) && $doQuery) {
            $this->aPrinter = PrinterQuery::create()
                ->filterByPublication($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPrinter->addPublications($this);
             */
        }

        return $this->aPrinter;
    }

    /**
     * Declares an association between this object and a Translator object.
     *
     * @param             Translator $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTranslator(Translator $v = null)
    {
        if ($v === null) {
            $this->setTranslatorId(NULL);
        } else {
            $this->setTranslatorId($v->getId());
        }

        $this->aTranslator = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Translator object, it will not be re-added.
        if ($v !== null) {
            $v->addPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated Translator object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Translator The associated Translator object.
     * @throws PropelException
     */
    public function getTranslator(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aTranslator === null && ($this->translator_id !== null) && $doQuery) {
            $this->aTranslator = TranslatorQuery::create()
                ->filterByPublication($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTranslator->addPublications($this);
             */
        }

        return $this->aTranslator;
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
    public function setDatespecificationRelatedByOrigindateId(Datespecification $v = null)
    {
        if ($v === null) {
            $this->setOrigindateId(NULL);
        } else {
            $this->setOrigindateId($v->getId());
        }

        $this->aDatespecificationRelatedByOrigindateId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Datespecification object, it will not be re-added.
        if ($v !== null) {
            $v->addPublicationRelatedByOrigindateId($this);
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
    public function getDatespecificationRelatedByOrigindateId(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDatespecificationRelatedByOrigindateId === null && ($this->origindate_id !== null) && $doQuery) {
            $this->aDatespecificationRelatedByOrigindateId = DatespecificationQuery::create()->findPk($this->origindate_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDatespecificationRelatedByOrigindateId->addPublicationsRelatedByOrigindateId($this);
             */
        }

        return $this->aDatespecificationRelatedByOrigindateId;
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
        if ('Monograph' == $relationName) {
            $this->initMonographs();
        }
        if ('PublicationPublicationgroup' == $relationName) {
            $this->initPublicationPublicationgroups();
        }
        if ('Source' == $relationName) {
            $this->initSources();
        }
        if ('Task' == $relationName) {
            $this->initTasks();
        }
    }

    /**
     * Clears out the collMonographs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addMonographs()
     */
    public function clearMonographs()
    {
        $this->collMonographs = null; // important to set this to null since that means it is uninitialized
        $this->collMonographsPartial = null;

        return $this;
    }

    /**
     * reset is the collMonographs collection loaded partially
     *
     * @return void
     */
    public function resetPartialMonographs($v = true)
    {
        $this->collMonographsPartial = $v;
    }

    /**
     * Initializes the collMonographs collection.
     *
     * By default this just sets the collMonographs collection to an empty array (like clearcollMonographs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMonographs($overrideExisting = true)
    {
        if (null !== $this->collMonographs && !$overrideExisting) {
            return;
        }
        $this->collMonographs = new PropelObjectCollection();
        $this->collMonographs->setModel('Monograph');
    }

    /**
     * Gets an array of Monograph objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Monograph[] List of Monograph objects
     * @throws PropelException
     */
    public function getMonographs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMonographsPartial && !$this->isNew();
        if (null === $this->collMonographs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMonographs) {
                // return empty collection
                $this->initMonographs();
            } else {
                $collMonographs = MonographQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMonographsPartial && count($collMonographs)) {
                      $this->initMonographs(false);

                      foreach($collMonographs as $obj) {
                        if (false == $this->collMonographs->contains($obj)) {
                          $this->collMonographs->append($obj);
                        }
                      }

                      $this->collMonographsPartial = true;
                    }

                    $collMonographs->getInternalIterator()->rewind();
                    return $collMonographs;
                }

                if($partial && $this->collMonographs) {
                    foreach($this->collMonographs as $obj) {
                        if($obj->isNew()) {
                            $collMonographs[] = $obj;
                        }
                    }
                }

                $this->collMonographs = $collMonographs;
                $this->collMonographsPartial = false;
            }
        }

        return $this->collMonographs;
    }

    /**
     * Sets a collection of Monograph objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $monographs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setMonographs(PropelCollection $monographs, PropelPDO $con = null)
    {
        $monographsToDelete = $this->getMonographs(new Criteria(), $con)->diff($monographs);

        $this->monographsScheduledForDeletion = unserialize(serialize($monographsToDelete));

        foreach ($monographsToDelete as $monographRemoved) {
            $monographRemoved->setPublication(null);
        }

        $this->collMonographs = null;
        foreach ($monographs as $monograph) {
            $this->addMonograph($monograph);
        }

        $this->collMonographs = $monographs;
        $this->collMonographsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Monograph objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Monograph objects.
     * @throws PropelException
     */
    public function countMonographs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collMonographsPartial && !$this->isNew();
        if (null === $this->collMonographs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMonographs) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getMonographs());
            }
            $query = MonographQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collMonographs);
    }

    /**
     * Method called to associate a Monograph object to this object
     * through the Monograph foreign key attribute.
     *
     * @param    Monograph $l Monograph
     * @return Publication The current object (for fluent API support)
     */
    public function addMonograph(Monograph $l)
    {
        if ($this->collMonographs === null) {
            $this->initMonographs();
            $this->collMonographsPartial = true;
        }
        if (!in_array($l, $this->collMonographs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMonograph($l);
        }

        return $this;
    }

    /**
     * @param	Monograph $monograph The monograph object to add.
     */
    protected function doAddMonograph($monograph)
    {
        $this->collMonographs[]= $monograph;
        $monograph->setPublication($this);
    }

    /**
     * @param	Monograph $monograph The monograph object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeMonograph($monograph)
    {
        if ($this->getMonographs()->contains($monograph)) {
            $this->collMonographs->remove($this->collMonographs->search($monograph));
            if (null === $this->monographsScheduledForDeletion) {
                $this->monographsScheduledForDeletion = clone $this->collMonographs;
                $this->monographsScheduledForDeletion->clear();
            }
            $this->monographsScheduledForDeletion[]= clone $monograph;
            $monograph->setPublication(null);
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
     * Clears out the collSources collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addSources()
     */
    public function clearSources()
    {
        $this->collSources = null; // important to set this to null since that means it is uninitialized
        $this->collSourcesPartial = null;

        return $this;
    }

    /**
     * reset is the collSources collection loaded partially
     *
     * @return void
     */
    public function resetPartialSources($v = true)
    {
        $this->collSourcesPartial = $v;
    }

    /**
     * Initializes the collSources collection.
     *
     * By default this just sets the collSources collection to an empty array (like clearcollSources());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSources($overrideExisting = true)
    {
        if (null !== $this->collSources && !$overrideExisting) {
            return;
        }
        $this->collSources = new PropelObjectCollection();
        $this->collSources->setModel('Source');
    }

    /**
     * Gets an array of Source objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Source[] List of Source objects
     * @throws PropelException
     */
    public function getSources($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSourcesPartial && !$this->isNew();
        if (null === $this->collSources || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSources) {
                // return empty collection
                $this->initSources();
            } else {
                $collSources = SourceQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSourcesPartial && count($collSources)) {
                      $this->initSources(false);

                      foreach($collSources as $obj) {
                        if (false == $this->collSources->contains($obj)) {
                          $this->collSources->append($obj);
                        }
                      }

                      $this->collSourcesPartial = true;
                    }

                    $collSources->getInternalIterator()->rewind();
                    return $collSources;
                }

                if($partial && $this->collSources) {
                    foreach($this->collSources as $obj) {
                        if($obj->isNew()) {
                            $collSources[] = $obj;
                        }
                    }
                }

                $this->collSources = $collSources;
                $this->collSourcesPartial = false;
            }
        }

        return $this->collSources;
    }

    /**
     * Sets a collection of Source objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $sources A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setSources(PropelCollection $sources, PropelPDO $con = null)
    {
        $sourcesToDelete = $this->getSources(new Criteria(), $con)->diff($sources);

        $this->sourcesScheduledForDeletion = unserialize(serialize($sourcesToDelete));

        foreach ($sourcesToDelete as $sourceRemoved) {
            $sourceRemoved->setPublication(null);
        }

        $this->collSources = null;
        foreach ($sources as $source) {
            $this->addSource($source);
        }

        $this->collSources = $sources;
        $this->collSourcesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Source objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Source objects.
     * @throws PropelException
     */
    public function countSources(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSourcesPartial && !$this->isNew();
        if (null === $this->collSources || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSources) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getSources());
            }
            $query = SourceQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collSources);
    }

    /**
     * Method called to associate a Source object to this object
     * through the Source foreign key attribute.
     *
     * @param    Source $l Source
     * @return Publication The current object (for fluent API support)
     */
    public function addSource(Source $l)
    {
        if ($this->collSources === null) {
            $this->initSources();
            $this->collSourcesPartial = true;
        }
        if (!in_array($l, $this->collSources->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSource($l);
        }

        return $this;
    }

    /**
     * @param	Source $source The source object to add.
     */
    protected function doAddSource($source)
    {
        $this->collSources[]= $source;
        $source->setPublication($this);
    }

    /**
     * @param	Source $source The source object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeSource($source)
    {
        if ($this->getSources()->contains($source)) {
            $this->collSources->remove($this->collSources->search($source));
            if (null === $this->sourcesScheduledForDeletion) {
                $this->sourcesScheduledForDeletion = clone $this->collSources;
                $this->sourcesScheduledForDeletion->clear();
            }
            $this->sourcesScheduledForDeletion[]= clone $source;
            $source->setPublication(null);
        }

        return $this;
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
    public function getTasksJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TaskQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getTasks($query, $con);
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
     * to the current object by way of the publication_publicationGroup cross-reference table.
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
     * to the current object by way of the publication_publicationGroup cross-reference table.
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
     * to the current object by way of the publication_publicationGroup cross-reference table.
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
     * through the publication_publicationGroup cross reference table.
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
     * through the publication_publicationGroup cross reference table.
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
        $this->printrun = null;
        $this->printruncomment = null;
        $this->edition = null;
        $this->numpages = null;
        $this->numpagesnormed = null;
        $this->bibliographiccitation = null;
        $this->title_id = null;
        $this->publishingcompany_id = null;
        $this->place_id = null;
        $this->publicationdate_id = null;
        $this->origindate_id = null;
        $this->relatedset_id = null;
        $this->work_id = null;
        $this->publisher_id = null;
        $this->printer_id = null;
        $this->translator_id = null;
        $this->descendant_class = null;
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
            if ($this->collMonographs) {
                foreach ($this->collMonographs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationPublicationgroups) {
                foreach ($this->collPublicationPublicationgroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSources) {
                foreach ($this->collSources as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTasks) {
                foreach ($this->collTasks as $o) {
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
            if ($this->aPublisher instanceof Persistent) {
              $this->aPublisher->clearAllReferences($deep);
            }
            if ($this->aPrinter instanceof Persistent) {
              $this->aPrinter->clearAllReferences($deep);
            }
            if ($this->aTranslator instanceof Persistent) {
              $this->aTranslator->clearAllReferences($deep);
            }
            if ($this->aRelatedset instanceof Persistent) {
              $this->aRelatedset->clearAllReferences($deep);
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
            if ($this->aDatespecificationRelatedByOrigindateId instanceof Persistent) {
              $this->aDatespecificationRelatedByOrigindateId->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collMonographs instanceof PropelCollection) {
            $this->collMonographs->clearIterator();
        }
        $this->collMonographs = null;
        if ($this->collPublicationPublicationgroups instanceof PropelCollection) {
            $this->collPublicationPublicationgroups->clearIterator();
        }
        $this->collPublicationPublicationgroups = null;
        if ($this->collSources instanceof PropelCollection) {
            $this->collSources->clearIterator();
        }
        $this->collSources = null;
        if ($this->collTasks instanceof PropelCollection) {
            $this->collTasks->clearIterator();
        }
        $this->collTasks = null;
        if ($this->collPublicationgroups instanceof PropelCollection) {
            $this->collPublicationgroups->clearIterator();
        }
        $this->collPublicationgroups = null;
        $this->aWork = null;
        $this->aPublisher = null;
        $this->aPrinter = null;
        $this->aTranslator = null;
        $this->aRelatedset = null;
        $this->aTitle = null;
        $this->aPublishingcompany = null;
        $this->aPlace = null;
        $this->aDatespecificationRelatedByPublicationdateId = null;
        $this->aDatespecificationRelatedByOrigindateId = null;
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

    // concrete_inheritance_parent behavior

    /**
     * Whether or not this object is the parent of a child object
     *
     * @return    bool
     */
    public function hasChildObject()
    {
        return $this->getDescendantClass() !== null;
    }

    /**
     * Get the child object of this object
     *
     * @return    mixed
     */
    public function getChildObject()
    {
        if (!$this->hasChildObject()) {
            return null;
        }
        $childObjectClass = $this->getDescendantClass();
        $childObject = PropelQuery::from($childObjectClass)->findPk($this->getPrimaryKey());

        return $childObject->hasChildObject() ? $childObject->getChildObject() : $childObject;
    }

}
