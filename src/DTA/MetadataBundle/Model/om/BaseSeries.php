<?php

namespace DTA\MetadataBundle\Model\om;

use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelException;
use \PropelPDO;
use DTA\MetadataBundle\Model\Datespecification;
use DTA\MetadataBundle\Model\DatespecificationQuery;
use DTA\MetadataBundle\Model\Place;
use DTA\MetadataBundle\Model\PlaceQuery;
use DTA\MetadataBundle\Model\Printer;
use DTA\MetadataBundle\Model\PrinterQuery;
use DTA\MetadataBundle\Model\Publication;
use DTA\MetadataBundle\Model\Publisher;
use DTA\MetadataBundle\Model\PublisherQuery;
use DTA\MetadataBundle\Model\Publishingcompany;
use DTA\MetadataBundle\Model\PublishingcompanyQuery;
use DTA\MetadataBundle\Model\Relatedset;
use DTA\MetadataBundle\Model\RelatedsetQuery;
use DTA\MetadataBundle\Model\Series;
use DTA\MetadataBundle\Model\SeriesPeer;
use DTA\MetadataBundle\Model\SeriesQuery;
use DTA\MetadataBundle\Model\Title;
use DTA\MetadataBundle\Model\TitleQuery;
use DTA\MetadataBundle\Model\Translator;
use DTA\MetadataBundle\Model\TranslatorQuery;
use DTA\MetadataBundle\Model\Work;
use DTA\MetadataBundle\Model\WorkQuery;

abstract class BaseSeries extends Publication implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\SeriesPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        SeriesPeer
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
     * The value for the volume field.
     * @var        string
     */
    protected $volume;

    /**
     * The value for the printrun field.
     * @var        string
     */
    protected $printrun;

    /**
     * The value for the edition field.
     * @var        string
     */
    protected $edition;

    /**
     * The value for the editionnumerical field.
     * @var        string
     */
    protected $editionnumerical;

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
    protected $aDatespecificationRelatedByPublicationDate;

    /**
     * @var        Datespecification
     */
    protected $aDatespecificationRelatedByOriginDate;

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
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [volume] column value.
     *
     * @return string
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * Get the [printrun] column value.
     * Bezeichnung der Auflage
     * @return string
     */
    public function getPrintrun()
    {
        return $this->printrun;
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
     * Get the [editionnumerical] column value.
     *
     * @return string
     */
    public function getEditionnumerical()
    {
        return $this->editionnumerical;
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
    public function getPublicationDate()
    {
        return $this->publicationdate_id;
    }

    /**
     * Get the [origindate_id] column value.
     *
     * @return int
     */
    public function getOriginDate()
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
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = SeriesPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [volume] column.
     *
     * @param string $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setVolume($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->volume !== $v) {
            $this->volume = $v;
            $this->modifiedColumns[] = SeriesPeer::VOLUME;
        }


        return $this;
    } // setVolume()

    /**
     * Set the value of [printrun] column.
     * Bezeichnung der Auflage
     * @param string $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setPrintrun($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->printrun !== $v) {
            $this->printrun = $v;
            $this->modifiedColumns[] = SeriesPeer::PRINTRUN;
        }


        return $this;
    } // setPrintrun()

    /**
     * Set the value of [edition] column.
     *
     * @param string $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setEdition($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->edition !== $v) {
            $this->edition = $v;
            $this->modifiedColumns[] = SeriesPeer::EDITION;
        }


        return $this;
    } // setEdition()

    /**
     * Set the value of [editionnumerical] column.
     *
     * @param string $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setEditionnumerical($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->editionnumerical !== $v) {
            $this->editionnumerical = $v;
            $this->modifiedColumns[] = SeriesPeer::EDITIONNUMERICAL;
        }


        return $this;
    } // setEditionnumerical()

    /**
     * Set the value of [numpages] column.
     *
     * @param int $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setNumpages($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->numpages !== $v) {
            $this->numpages = $v;
            $this->modifiedColumns[] = SeriesPeer::NUMPAGES;
        }


        return $this;
    } // setNumpages()

    /**
     * Set the value of [numpagesnormed] column.
     *
     * @param int $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setNumpagesnormed($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->numpagesnormed !== $v) {
            $this->numpagesnormed = $v;
            $this->modifiedColumns[] = SeriesPeer::NUMPAGESNORMED;
        }


        return $this;
    } // setNumpagesnormed()

    /**
     * Set the value of [bibliographiccitation] column.
     *
     * @param string $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setBibliographiccitation($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->bibliographiccitation !== $v) {
            $this->bibliographiccitation = $v;
            $this->modifiedColumns[] = SeriesPeer::BIBLIOGRAPHICCITATION;
        }


        return $this;
    } // setBibliographiccitation()

    /**
     * Set the value of [title_id] column.
     *
     * @param int $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setTitleId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->title_id !== $v) {
            $this->title_id = $v;
            $this->modifiedColumns[] = SeriesPeer::TITLE_ID;
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
     * @return Series The current object (for fluent API support)
     */
    public function setPublishingcompanyId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->publishingcompany_id !== $v) {
            $this->publishingcompany_id = $v;
            $this->modifiedColumns[] = SeriesPeer::PUBLISHINGCOMPANY_ID;
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
     * @return Series The current object (for fluent API support)
     */
    public function setPlaceId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->place_id !== $v) {
            $this->place_id = $v;
            $this->modifiedColumns[] = SeriesPeer::PLACE_ID;
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
     * @return Series The current object (for fluent API support)
     */
    public function setPublicationDate($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->publicationdate_id !== $v) {
            $this->publicationdate_id = $v;
            $this->modifiedColumns[] = SeriesPeer::PUBLICATIONDATE_ID;
        }

        if ($this->aDatespecificationRelatedByPublicationDate !== null && $this->aDatespecificationRelatedByPublicationDate->getId() !== $v) {
            $this->aDatespecificationRelatedByPublicationDate = null;
        }


        return $this;
    } // setPublicationDate()

    /**
     * Set the value of [origindate_id] column.
     *
     * @param int $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setOriginDate($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->origindate_id !== $v) {
            $this->origindate_id = $v;
            $this->modifiedColumns[] = SeriesPeer::ORIGINDATE_ID;
        }

        if ($this->aDatespecificationRelatedByOriginDate !== null && $this->aDatespecificationRelatedByOriginDate->getId() !== $v) {
            $this->aDatespecificationRelatedByOriginDate = null;
        }


        return $this;
    } // setOriginDate()

    /**
     * Set the value of [relatedset_id] column.
     *
     * @param int $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setRelatedsetId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->relatedset_id !== $v) {
            $this->relatedset_id = $v;
            $this->modifiedColumns[] = SeriesPeer::RELATEDSET_ID;
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
     * @return Series The current object (for fluent API support)
     */
    public function setWorkId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->work_id !== $v) {
            $this->work_id = $v;
            $this->modifiedColumns[] = SeriesPeer::WORK_ID;
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
     * @return Series The current object (for fluent API support)
     */
    public function setPublisherId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->publisher_id !== $v) {
            $this->publisher_id = $v;
            $this->modifiedColumns[] = SeriesPeer::PUBLISHER_ID;
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
     * @return Series The current object (for fluent API support)
     */
    public function setPrinterId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->printer_id !== $v) {
            $this->printer_id = $v;
            $this->modifiedColumns[] = SeriesPeer::PRINTER_ID;
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
     * @return Series The current object (for fluent API support)
     */
    public function setTranslatorId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->translator_id !== $v) {
            $this->translator_id = $v;
            $this->modifiedColumns[] = SeriesPeer::TRANSLATOR_ID;
        }

        if ($this->aTranslator !== null && $this->aTranslator->getId() !== $v) {
            $this->aTranslator = null;
        }


        return $this;
    } // setTranslatorId()

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
            $this->volume = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->printrun = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->edition = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->editionnumerical = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->numpages = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->numpagesnormed = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->bibliographiccitation = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->title_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->publishingcompany_id = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->place_id = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->publicationdate_id = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
            $this->origindate_id = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->relatedset_id = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
            $this->work_id = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
            $this->publisher_id = ($row[$startcol + 15] !== null) ? (int) $row[$startcol + 15] : null;
            $this->printer_id = ($row[$startcol + 16] !== null) ? (int) $row[$startcol + 16] : null;
            $this->translator_id = ($row[$startcol + 17] !== null) ? (int) $row[$startcol + 17] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 18; // 18 = SeriesPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Series object", $e);
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
        if ($this->aDatespecificationRelatedByPublicationDate !== null && $this->publicationdate_id !== $this->aDatespecificationRelatedByPublicationDate->getId()) {
            $this->aDatespecificationRelatedByPublicationDate = null;
        }
        if ($this->aDatespecificationRelatedByOriginDate !== null && $this->origindate_id !== $this->aDatespecificationRelatedByOriginDate->getId()) {
            $this->aDatespecificationRelatedByOriginDate = null;
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
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = SeriesPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
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
            $this->aDatespecificationRelatedByPublicationDate = null;
            $this->aDatespecificationRelatedByOriginDate = null;
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
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = SeriesQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                // concrete_inheritance behavior
                $this->getParentOrCreate($con)->delete($con);

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
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // concrete_inheritance behavior
            $parent = $this->getSyncParent($con);
            $parent->save($con);
            $this->setPrimaryKey($parent->getPrimaryKey());

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
                SeriesPeer::addInstanceToPool($this);
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

            if ($this->aDatespecificationRelatedByPublicationDate !== null) {
                if ($this->aDatespecificationRelatedByPublicationDate->isModified() || $this->aDatespecificationRelatedByPublicationDate->isNew()) {
                    $affectedRows += $this->aDatespecificationRelatedByPublicationDate->save($con);
                }
                $this->setDatespecificationRelatedByPublicationDate($this->aDatespecificationRelatedByPublicationDate);
            }

            if ($this->aDatespecificationRelatedByOriginDate !== null) {
                if ($this->aDatespecificationRelatedByOriginDate->isModified() || $this->aDatespecificationRelatedByOriginDate->isNew()) {
                    $affectedRows += $this->aDatespecificationRelatedByOriginDate->save($con);
                }
                $this->setDatespecificationRelatedByOriginDate($this->aDatespecificationRelatedByOriginDate);
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

        $this->modifiedColumns[] = SeriesPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SeriesPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SeriesPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(SeriesPeer::VOLUME)) {
            $modifiedColumns[':p' . $index++]  = '`volume`';
        }
        if ($this->isColumnModified(SeriesPeer::PRINTRUN)) {
            $modifiedColumns[':p' . $index++]  = '`printRun`';
        }
        if ($this->isColumnModified(SeriesPeer::EDITION)) {
            $modifiedColumns[':p' . $index++]  = '`edition`';
        }
        if ($this->isColumnModified(SeriesPeer::EDITIONNUMERICAL)) {
            $modifiedColumns[':p' . $index++]  = '`editionNumerical`';
        }
        if ($this->isColumnModified(SeriesPeer::NUMPAGES)) {
            $modifiedColumns[':p' . $index++]  = '`numPages`';
        }
        if ($this->isColumnModified(SeriesPeer::NUMPAGESNORMED)) {
            $modifiedColumns[':p' . $index++]  = '`numPagesNormed`';
        }
        if ($this->isColumnModified(SeriesPeer::BIBLIOGRAPHICCITATION)) {
            $modifiedColumns[':p' . $index++]  = '`bibliographicCitation`';
        }
        if ($this->isColumnModified(SeriesPeer::TITLE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`title_id`';
        }
        if ($this->isColumnModified(SeriesPeer::PUBLISHINGCOMPANY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`publishingCompany_id`';
        }
        if ($this->isColumnModified(SeriesPeer::PLACE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`place_id`';
        }
        if ($this->isColumnModified(SeriesPeer::PUBLICATIONDATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`publicationDate_id`';
        }
        if ($this->isColumnModified(SeriesPeer::ORIGINDATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`originDate_id`';
        }
        if ($this->isColumnModified(SeriesPeer::RELATEDSET_ID)) {
            $modifiedColumns[':p' . $index++]  = '`relatedSet_id`';
        }
        if ($this->isColumnModified(SeriesPeer::WORK_ID)) {
            $modifiedColumns[':p' . $index++]  = '`work_id`';
        }
        if ($this->isColumnModified(SeriesPeer::PUBLISHER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`publisher_id`';
        }
        if ($this->isColumnModified(SeriesPeer::PRINTER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`printer_id`';
        }
        if ($this->isColumnModified(SeriesPeer::TRANSLATOR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`translator_id`';
        }

        $sql = sprintf(
            'INSERT INTO `series` (%s) VALUES (%s)',
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
                    case '`volume`':
                        $stmt->bindValue($identifier, $this->volume, PDO::PARAM_STR);
                        break;
                    case '`printRun`':
                        $stmt->bindValue($identifier, $this->printrun, PDO::PARAM_STR);
                        break;
                    case '`edition`':
                        $stmt->bindValue($identifier, $this->edition, PDO::PARAM_STR);
                        break;
                    case '`editionNumerical`':
                        $stmt->bindValue($identifier, $this->editionnumerical, PDO::PARAM_STR);
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

            if ($this->aDatespecificationRelatedByPublicationDate !== null) {
                if (!$this->aDatespecificationRelatedByPublicationDate->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDatespecificationRelatedByPublicationDate->getValidationFailures());
                }
            }

            if ($this->aDatespecificationRelatedByOriginDate !== null) {
                if (!$this->aDatespecificationRelatedByOriginDate->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDatespecificationRelatedByOriginDate->getValidationFailures());
                }
            }


            if (($retval = SeriesPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
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
        $pos = SeriesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getVolume();
                break;
            case 2:
                return $this->getPrintrun();
                break;
            case 3:
                return $this->getEdition();
                break;
            case 4:
                return $this->getEditionnumerical();
                break;
            case 5:
                return $this->getNumpages();
                break;
            case 6:
                return $this->getNumpagesnormed();
                break;
            case 7:
                return $this->getBibliographiccitation();
                break;
            case 8:
                return $this->getTitleId();
                break;
            case 9:
                return $this->getPublishingcompanyId();
                break;
            case 10:
                return $this->getPlaceId();
                break;
            case 11:
                return $this->getPublicationDate();
                break;
            case 12:
                return $this->getOriginDate();
                break;
            case 13:
                return $this->getRelatedsetId();
                break;
            case 14:
                return $this->getWorkId();
                break;
            case 15:
                return $this->getPublisherId();
                break;
            case 16:
                return $this->getPrinterId();
                break;
            case 17:
                return $this->getTranslatorId();
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
        if (isset($alreadyDumpedObjects['Series'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Series'][$this->getPrimaryKey()] = true;
        $keys = SeriesPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getVolume(),
            $keys[2] => $this->getPrintrun(),
            $keys[3] => $this->getEdition(),
            $keys[4] => $this->getEditionnumerical(),
            $keys[5] => $this->getNumpages(),
            $keys[6] => $this->getNumpagesnormed(),
            $keys[7] => $this->getBibliographiccitation(),
            $keys[8] => $this->getTitleId(),
            $keys[9] => $this->getPublishingcompanyId(),
            $keys[10] => $this->getPlaceId(),
            $keys[11] => $this->getPublicationDate(),
            $keys[12] => $this->getOriginDate(),
            $keys[13] => $this->getRelatedsetId(),
            $keys[14] => $this->getWorkId(),
            $keys[15] => $this->getPublisherId(),
            $keys[16] => $this->getPrinterId(),
            $keys[17] => $this->getTranslatorId(),
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
            if (null !== $this->aDatespecificationRelatedByPublicationDate) {
                $result['DatespecificationRelatedByPublicationDate'] = $this->aDatespecificationRelatedByPublicationDate->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDatespecificationRelatedByOriginDate) {
                $result['DatespecificationRelatedByOriginDate'] = $this->aDatespecificationRelatedByOriginDate->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = SeriesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setVolume($value);
                break;
            case 2:
                $this->setPrintrun($value);
                break;
            case 3:
                $this->setEdition($value);
                break;
            case 4:
                $this->setEditionnumerical($value);
                break;
            case 5:
                $this->setNumpages($value);
                break;
            case 6:
                $this->setNumpagesnormed($value);
                break;
            case 7:
                $this->setBibliographiccitation($value);
                break;
            case 8:
                $this->setTitleId($value);
                break;
            case 9:
                $this->setPublishingcompanyId($value);
                break;
            case 10:
                $this->setPlaceId($value);
                break;
            case 11:
                $this->setPublicationDate($value);
                break;
            case 12:
                $this->setOriginDate($value);
                break;
            case 13:
                $this->setRelatedsetId($value);
                break;
            case 14:
                $this->setWorkId($value);
                break;
            case 15:
                $this->setPublisherId($value);
                break;
            case 16:
                $this->setPrinterId($value);
                break;
            case 17:
                $this->setTranslatorId($value);
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
        $keys = SeriesPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setVolume($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPrintrun($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setEdition($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setEditionnumerical($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setNumpages($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setNumpagesnormed($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setBibliographiccitation($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setTitleId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setPublishingcompanyId($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setPlaceId($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setPublicationDate($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setOriginDate($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setRelatedsetId($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setWorkId($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setPublisherId($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setPrinterId($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setTranslatorId($arr[$keys[17]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(SeriesPeer::DATABASE_NAME);

        if ($this->isColumnModified(SeriesPeer::ID)) $criteria->add(SeriesPeer::ID, $this->id);
        if ($this->isColumnModified(SeriesPeer::VOLUME)) $criteria->add(SeriesPeer::VOLUME, $this->volume);
        if ($this->isColumnModified(SeriesPeer::PRINTRUN)) $criteria->add(SeriesPeer::PRINTRUN, $this->printrun);
        if ($this->isColumnModified(SeriesPeer::EDITION)) $criteria->add(SeriesPeer::EDITION, $this->edition);
        if ($this->isColumnModified(SeriesPeer::EDITIONNUMERICAL)) $criteria->add(SeriesPeer::EDITIONNUMERICAL, $this->editionnumerical);
        if ($this->isColumnModified(SeriesPeer::NUMPAGES)) $criteria->add(SeriesPeer::NUMPAGES, $this->numpages);
        if ($this->isColumnModified(SeriesPeer::NUMPAGESNORMED)) $criteria->add(SeriesPeer::NUMPAGESNORMED, $this->numpagesnormed);
        if ($this->isColumnModified(SeriesPeer::BIBLIOGRAPHICCITATION)) $criteria->add(SeriesPeer::BIBLIOGRAPHICCITATION, $this->bibliographiccitation);
        if ($this->isColumnModified(SeriesPeer::TITLE_ID)) $criteria->add(SeriesPeer::TITLE_ID, $this->title_id);
        if ($this->isColumnModified(SeriesPeer::PUBLISHINGCOMPANY_ID)) $criteria->add(SeriesPeer::PUBLISHINGCOMPANY_ID, $this->publishingcompany_id);
        if ($this->isColumnModified(SeriesPeer::PLACE_ID)) $criteria->add(SeriesPeer::PLACE_ID, $this->place_id);
        if ($this->isColumnModified(SeriesPeer::PUBLICATIONDATE_ID)) $criteria->add(SeriesPeer::PUBLICATIONDATE_ID, $this->publicationdate_id);
        if ($this->isColumnModified(SeriesPeer::ORIGINDATE_ID)) $criteria->add(SeriesPeer::ORIGINDATE_ID, $this->origindate_id);
        if ($this->isColumnModified(SeriesPeer::RELATEDSET_ID)) $criteria->add(SeriesPeer::RELATEDSET_ID, $this->relatedset_id);
        if ($this->isColumnModified(SeriesPeer::WORK_ID)) $criteria->add(SeriesPeer::WORK_ID, $this->work_id);
        if ($this->isColumnModified(SeriesPeer::PUBLISHER_ID)) $criteria->add(SeriesPeer::PUBLISHER_ID, $this->publisher_id);
        if ($this->isColumnModified(SeriesPeer::PRINTER_ID)) $criteria->add(SeriesPeer::PRINTER_ID, $this->printer_id);
        if ($this->isColumnModified(SeriesPeer::TRANSLATOR_ID)) $criteria->add(SeriesPeer::TRANSLATOR_ID, $this->translator_id);

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
        $criteria = new Criteria(SeriesPeer::DATABASE_NAME);
        $criteria->add(SeriesPeer::ID, $this->id);

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
     * @param object $copyObj An object of Series (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setVolume($this->getVolume());
        $copyObj->setPrintrun($this->getPrintrun());
        $copyObj->setEdition($this->getEdition());
        $copyObj->setEditionnumerical($this->getEditionnumerical());
        $copyObj->setNumpages($this->getNumpages());
        $copyObj->setNumpagesnormed($this->getNumpagesnormed());
        $copyObj->setBibliographiccitation($this->getBibliographiccitation());
        $copyObj->setTitleId($this->getTitleId());
        $copyObj->setPublishingcompanyId($this->getPublishingcompanyId());
        $copyObj->setPlaceId($this->getPlaceId());
        $copyObj->setPublicationDate($this->getPublicationDate());
        $copyObj->setOriginDate($this->getOriginDate());
        $copyObj->setRelatedsetId($this->getRelatedsetId());
        $copyObj->setWorkId($this->getWorkId());
        $copyObj->setPublisherId($this->getPublisherId());
        $copyObj->setPrinterId($this->getPrinterId());
        $copyObj->setTranslatorId($this->getTranslatorId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

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
     * @return Series Clone of current object.
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
     * @return SeriesPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new SeriesPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Work object.
     *
     * @param             Work $v
     * @return Series The current object (for fluent API support)
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
            $v->addSeries($this);
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
                $this->aWork->addSeries($this);
             */
        }

        return $this->aWork;
    }

    /**
     * Declares an association between this object and a Publisher object.
     *
     * @param             Publisher $v
     * @return Series The current object (for fluent API support)
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
            $v->addSeries($this);
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
                ->filterBySeries($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPublisher->addSeries($this);
             */
        }

        return $this->aPublisher;
    }

    /**
     * Declares an association between this object and a Printer object.
     *
     * @param             Printer $v
     * @return Series The current object (for fluent API support)
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
            $v->addSeries($this);
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
                ->filterBySeries($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPrinter->addSeries($this);
             */
        }

        return $this->aPrinter;
    }

    /**
     * Declares an association between this object and a Translator object.
     *
     * @param             Translator $v
     * @return Series The current object (for fluent API support)
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
            $v->addSeries($this);
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
                ->filterBySeries($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTranslator->addSeries($this);
             */
        }

        return $this->aTranslator;
    }

    /**
     * Declares an association between this object and a Relatedset object.
     *
     * @param             Relatedset $v
     * @return Series The current object (for fluent API support)
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
            $v->addSeries($this);
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
                $this->aRelatedset->addSeries($this);
             */
        }

        return $this->aRelatedset;
    }

    /**
     * Declares an association between this object and a Title object.
     *
     * @param             Title $v
     * @return Series The current object (for fluent API support)
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
            $v->addSeries($this);
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
                $this->aTitle->addSeries($this);
             */
        }

        return $this->aTitle;
    }

    /**
     * Declares an association between this object and a Publishingcompany object.
     *
     * @param             Publishingcompany $v
     * @return Series The current object (for fluent API support)
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
            $v->addSeries($this);
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
                $this->aPublishingcompany->addSeries($this);
             */
        }

        return $this->aPublishingcompany;
    }

    /**
     * Declares an association between this object and a Place object.
     *
     * @param             Place $v
     * @return Series The current object (for fluent API support)
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
            $v->addSeries($this);
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
                $this->aPlace->addSeries($this);
             */
        }

        return $this->aPlace;
    }

    /**
     * Declares an association between this object and a Datespecification object.
     *
     * @param             Datespecification $v
     * @return Series The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDatespecificationRelatedByPublicationDate(Datespecification $v = null)
    {
        if ($v === null) {
            $this->setPublicationDate(NULL);
        } else {
            $this->setPublicationDate($v->getId());
        }

        $this->aDatespecificationRelatedByPublicationDate = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Datespecification object, it will not be re-added.
        if ($v !== null) {
            $v->addSeriesRelatedByPublicationDate($this);
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
    public function getDatespecificationRelatedByPublicationDate(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDatespecificationRelatedByPublicationDate === null && ($this->publicationdate_id !== null) && $doQuery) {
            $this->aDatespecificationRelatedByPublicationDate = DatespecificationQuery::create()->findPk($this->publicationdate_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDatespecificationRelatedByPublicationDate->addSeriesRelatedByPublicationDate($this);
             */
        }

        return $this->aDatespecificationRelatedByPublicationDate;
    }

    /**
     * Declares an association between this object and a Datespecification object.
     *
     * @param             Datespecification $v
     * @return Series The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDatespecificationRelatedByOriginDate(Datespecification $v = null)
    {
        if ($v === null) {
            $this->setOriginDate(NULL);
        } else {
            $this->setOriginDate($v->getId());
        }

        $this->aDatespecificationRelatedByOriginDate = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Datespecification object, it will not be re-added.
        if ($v !== null) {
            $v->addSeriesRelatedByOriginDate($this);
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
    public function getDatespecificationRelatedByOriginDate(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDatespecificationRelatedByOriginDate === null && ($this->origindate_id !== null) && $doQuery) {
            $this->aDatespecificationRelatedByOriginDate = DatespecificationQuery::create()->findPk($this->origindate_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDatespecificationRelatedByOriginDate->addSeriesRelatedByOriginDate($this);
             */
        }

        return $this->aDatespecificationRelatedByOriginDate;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->volume = null;
        $this->printrun = null;
        $this->edition = null;
        $this->editionnumerical = null;
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
            if ($this->aDatespecificationRelatedByPublicationDate instanceof Persistent) {
              $this->aDatespecificationRelatedByPublicationDate->clearAllReferences($deep);
            }
            if ($this->aDatespecificationRelatedByOriginDate instanceof Persistent) {
              $this->aDatespecificationRelatedByOriginDate->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aWork = null;
        $this->aPublisher = null;
        $this->aPrinter = null;
        $this->aTranslator = null;
        $this->aRelatedset = null;
        $this->aTitle = null;
        $this->aPublishingcompany = null;
        $this->aPlace = null;
        $this->aDatespecificationRelatedByPublicationDate = null;
        $this->aDatespecificationRelatedByOriginDate = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SeriesPeer::DEFAULT_STRING_FORMAT);
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

    // concrete_inheritance behavior

    /**
     * Get or Create the parent Publication object of the current object
     *
     * @return    Publication The parent object
     */
    public function getParentOrCreate($con = null)
    {
        if ($this->isNew()) {
            if ($this->isPrimaryKeyNull()) {
                //this prevent issue with deep copy & save parent object
                if (null === ($parent = $this->getPublication($con))) {
                    $parent = new Publication();
                }
                $parent->setDescendantClass('DTA\MetadataBundle\Model\Series');

                return $parent;
            } else {
                $parent = PublicationQuery::create()->findPk($this->getPrimaryKey(), $con);
                if (null === $parent || null !== $parent->getDescendantClass()) {
                    $parent = new Publication();
                    $parent->setPrimaryKey($this->getPrimaryKey());
                    $parent->setDescendantClass('Series');
                }

                return $parent;
            }
        }

        return PublicationQuery::create()->findPk($this->getPrimaryKey(), $con);
    }

    /**
     * Create or Update the parent Publication object
     * And return its primary key
     *
     * @return    int The primary key of the parent object
     */
    public function getSyncParent($con = null)
    {
        $parent = $this->getParentOrCreate($con);
        $parent->setPrintrun($this->getPrintrun());
        $parent->setEdition($this->getEdition());
        $parent->setEditionnumerical($this->getEditionnumerical());
        $parent->setNumpages($this->getNumpages());
        $parent->setNumpagesnormed($this->getNumpagesnormed());
        $parent->setBibliographiccitation($this->getBibliographiccitation());
        $parent->setTitleId($this->getTitleId());
        $parent->setPublishingcompanyId($this->getPublishingcompanyId());
        $parent->setPlaceId($this->getPlaceId());
        $parent->setPublicationDate($this->getPublicationDate());
        $parent->setOriginDate($this->getOriginDate());
        $parent->setRelatedsetId($this->getRelatedsetId());
        $parent->setWorkId($this->getWorkId());
        $parent->setPublisherId($this->getPublisherId());
        $parent->setPrinterId($this->getPrinterId());
        $parent->setTranslatorId($this->getTranslatorId());
        if ($this->getWork() && $this->getWork()->isNew()) {
            $parent->setWork($this->getWork());
        }
        if ($this->getPublisher() && $this->getPublisher()->isNew()) {
            $parent->setPublisher($this->getPublisher());
        }
        if ($this->getPrinter() && $this->getPrinter()->isNew()) {
            $parent->setPrinter($this->getPrinter());
        }
        if ($this->getTranslator() && $this->getTranslator()->isNew()) {
            $parent->setTranslator($this->getTranslator());
        }
        if ($this->getRelatedset() && $this->getRelatedset()->isNew()) {
            $parent->setRelatedset($this->getRelatedset());
        }
        if ($this->getTitle() && $this->getTitle()->isNew()) {
            $parent->setTitle($this->getTitle());
        }
        if ($this->getPublishingcompany() && $this->getPublishingcompany()->isNew()) {
            $parent->setPublishingcompany($this->getPublishingcompany());
        }
        if ($this->getPlace() && $this->getPlace()->isNew()) {
            $parent->setPlace($this->getPlace());
        }
        if ($this->getDatespecificationRelatedByPublicationDate() && $this->getDatespecificationRelatedByPublicationDate()->isNew()) {
            $parent->setDatespecificationRelatedByPublicationDate($this->getDatespecificationRelatedByPublicationDate());
        }
        if ($this->getDatespecificationRelatedByOriginDate() && $this->getDatespecificationRelatedByOriginDate()->isNew()) {
            $parent->setDatespecificationRelatedByOriginDate($this->getDatespecificationRelatedByOriginDate());
        }

        return $parent;
    }

}
