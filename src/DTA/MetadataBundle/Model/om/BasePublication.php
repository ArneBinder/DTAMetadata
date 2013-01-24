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
use DTA\MetadataBundle\Model\Datespecification;
use DTA\MetadataBundle\Model\DatespecificationQuery;
use DTA\MetadataBundle\Model\Essay;
use DTA\MetadataBundle\Model\EssayQuery;
use DTA\MetadataBundle\Model\Magazine;
use DTA\MetadataBundle\Model\MagazineQuery;
use DTA\MetadataBundle\Model\Monograph;
use DTA\MetadataBundle\Model\MonographQuery;
use DTA\MetadataBundle\Model\Place;
use DTA\MetadataBundle\Model\PlaceQuery;
use DTA\MetadataBundle\Model\Publication;
use DTA\MetadataBundle\Model\PublicationPeer;
use DTA\MetadataBundle\Model\PublicationQuery;
use DTA\MetadataBundle\Model\Publishingcompany;
use DTA\MetadataBundle\Model\PublishingcompanyQuery;
use DTA\MetadataBundle\Model\Series;
use DTA\MetadataBundle\Model\SeriesQuery;
use DTA\MetadataBundle\Model\Title;
use DTA\MetadataBundle\Model\TitleQuery;
use DTA\MetadataBundle\Model\Writ;
use DTA\MetadataBundle\Model\WritQuery;

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
     * The value for the datespecification_id field.
     * @var        int
     */
    protected $datespecification_id;

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
    protected $aDatespecification;

    /**
     * @var        Essay
     */
    protected $aEssay;

    /**
     * @var        Magazine
     */
    protected $aMagazine;

    /**
     * @var        Monograph
     */
    protected $aMonograph;

    /**
     * @var        Series
     */
    protected $aSeries;

    /**
     * @var        PropelObjectCollection|Writ[] Collection to store aggregation of Writ objects.
     */
    protected $collWrits;
    protected $collWritsPartial;

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
    protected $writsScheduledForDeletion = null;

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
     * Get the [datespecification_id] column value.
     *
     * @return int
     */
    public function getDatespecificationId()
    {
        return $this->datespecification_id;
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

        if ($this->aEssay !== null && $this->aEssay->getId() !== $v) {
            $this->aEssay = null;
        }

        if ($this->aMagazine !== null && $this->aMagazine->getId() !== $v) {
            $this->aMagazine = null;
        }

        if ($this->aMonograph !== null && $this->aMonograph->getId() !== $v) {
            $this->aMonograph = null;
        }

        if ($this->aSeries !== null && $this->aSeries->getId() !== $v) {
            $this->aSeries = null;
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
     * Set the value of [datespecification_id] column.
     *
     * @param int $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setDatespecificationId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->datespecification_id !== $v) {
            $this->datespecification_id = $v;
            $this->modifiedColumns[] = PublicationPeer::DATESPECIFICATION_ID;
        }

        if ($this->aDatespecification !== null && $this->aDatespecification->getId() !== $v) {
            $this->aDatespecification = null;
        }


        return $this;
    } // setDatespecificationId()

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
            $this->title_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->publishingcompany_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->place_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->datespecification_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->printrun = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->printruncomment = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->edition = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->numpages = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->numpagesnormed = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->bibliographiccitation = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 11; // 11 = PublicationPeer::NUM_HYDRATE_COLUMNS.

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

        if ($this->aEssay !== null && $this->id !== $this->aEssay->getId()) {
            $this->aEssay = null;
        }
        if ($this->aMagazine !== null && $this->id !== $this->aMagazine->getId()) {
            $this->aMagazine = null;
        }
        if ($this->aMonograph !== null && $this->id !== $this->aMonograph->getId()) {
            $this->aMonograph = null;
        }
        if ($this->aSeries !== null && $this->id !== $this->aSeries->getId()) {
            $this->aSeries = null;
        }
        if ($this->aTitle !== null && $this->title_id !== $this->aTitle->getId()) {
            $this->aTitle = null;
        }
        if ($this->aPublishingcompany !== null && $this->publishingcompany_id !== $this->aPublishingcompany->getId()) {
            $this->aPublishingcompany = null;
        }
        if ($this->aPlace !== null && $this->place_id !== $this->aPlace->getId()) {
            $this->aPlace = null;
        }
        if ($this->aDatespecification !== null && $this->datespecification_id !== $this->aDatespecification->getId()) {
            $this->aDatespecification = null;
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
            $this->aDatespecification = null;
            $this->aEssay = null;
            $this->aMagazine = null;
            $this->aMonograph = null;
            $this->aSeries = null;
            $this->collWrits = null;

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

            if ($this->aDatespecification !== null) {
                if ($this->aDatespecification->isModified() || $this->aDatespecification->isNew()) {
                    $affectedRows += $this->aDatespecification->save($con);
                }
                $this->setDatespecification($this->aDatespecification);
            }

            if ($this->aEssay !== null) {
                if ($this->aEssay->isModified() || $this->aEssay->isNew()) {
                    $affectedRows += $this->aEssay->save($con);
                }
                $this->setEssay($this->aEssay);
            }

            if ($this->aMagazine !== null) {
                if ($this->aMagazine->isModified() || $this->aMagazine->isNew()) {
                    $affectedRows += $this->aMagazine->save($con);
                }
                $this->setMagazine($this->aMagazine);
            }

            if ($this->aMonograph !== null) {
                if ($this->aMonograph->isModified() || $this->aMonograph->isNew()) {
                    $affectedRows += $this->aMonograph->save($con);
                }
                $this->setMonograph($this->aMonograph);
            }

            if ($this->aSeries !== null) {
                if ($this->aSeries->isModified() || $this->aSeries->isNew()) {
                    $affectedRows += $this->aSeries->save($con);
                }
                $this->setSeries($this->aSeries);
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

            if ($this->writsScheduledForDeletion !== null) {
                if (!$this->writsScheduledForDeletion->isEmpty()) {
                    WritQuery::create()
                        ->filterByPrimaryKeys($this->writsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->writsScheduledForDeletion = null;
                }
            }

            if ($this->collWrits !== null) {
                foreach ($this->collWrits as $referrerFK) {
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
        if ($this->isColumnModified(PublicationPeer::TITLE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`title_id`';
        }
        if ($this->isColumnModified(PublicationPeer::PUBLISHINGCOMPANY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`publishingCompany_id`';
        }
        if ($this->isColumnModified(PublicationPeer::PLACE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`place_id`';
        }
        if ($this->isColumnModified(PublicationPeer::DATESPECIFICATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`dateSpecification_id`';
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
                    case '`title_id`':
                        $stmt->bindValue($identifier, $this->title_id, PDO::PARAM_INT);
                        break;
                    case '`publishingCompany_id`':
                        $stmt->bindValue($identifier, $this->publishingcompany_id, PDO::PARAM_INT);
                        break;
                    case '`place_id`':
                        $stmt->bindValue($identifier, $this->place_id, PDO::PARAM_INT);
                        break;
                    case '`dateSpecification_id`':
                        $stmt->bindValue($identifier, $this->datespecification_id, PDO::PARAM_INT);
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

            if ($this->aDatespecification !== null) {
                if (!$this->aDatespecification->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDatespecification->getValidationFailures());
                }
            }

            if ($this->aEssay !== null) {
                if (!$this->aEssay->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aEssay->getValidationFailures());
                }
            }

            if ($this->aMagazine !== null) {
                if (!$this->aMagazine->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aMagazine->getValidationFailures());
                }
            }

            if ($this->aMonograph !== null) {
                if (!$this->aMonograph->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aMonograph->getValidationFailures());
                }
            }

            if ($this->aSeries !== null) {
                if (!$this->aSeries->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSeries->getValidationFailures());
                }
            }


            if (($retval = PublicationPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collWrits !== null) {
                    foreach ($this->collWrits as $referrerFK) {
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
                return $this->getPublishingcompanyId();
                break;
            case 3:
                return $this->getPlaceId();
                break;
            case 4:
                return $this->getDatespecificationId();
                break;
            case 5:
                return $this->getPrintrun();
                break;
            case 6:
                return $this->getPrintruncomment();
                break;
            case 7:
                return $this->getEdition();
                break;
            case 8:
                return $this->getNumpages();
                break;
            case 9:
                return $this->getNumpagesnormed();
                break;
            case 10:
                return $this->getBibliographiccitation();
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
            $keys[2] => $this->getPublishingcompanyId(),
            $keys[3] => $this->getPlaceId(),
            $keys[4] => $this->getDatespecificationId(),
            $keys[5] => $this->getPrintrun(),
            $keys[6] => $this->getPrintruncomment(),
            $keys[7] => $this->getEdition(),
            $keys[8] => $this->getNumpages(),
            $keys[9] => $this->getNumpagesnormed(),
            $keys[10] => $this->getBibliographiccitation(),
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
            if (null !== $this->aDatespecification) {
                $result['Datespecification'] = $this->aDatespecification->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aEssay) {
                $result['Essay'] = $this->aEssay->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aMagazine) {
                $result['Magazine'] = $this->aMagazine->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aMonograph) {
                $result['Monograph'] = $this->aMonograph->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSeries) {
                $result['Series'] = $this->aSeries->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collWrits) {
                $result['Writs'] = $this->collWrits->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $this->setPublishingcompanyId($value);
                break;
            case 3:
                $this->setPlaceId($value);
                break;
            case 4:
                $this->setDatespecificationId($value);
                break;
            case 5:
                $this->setPrintrun($value);
                break;
            case 6:
                $this->setPrintruncomment($value);
                break;
            case 7:
                $this->setEdition($value);
                break;
            case 8:
                $this->setNumpages($value);
                break;
            case 9:
                $this->setNumpagesnormed($value);
                break;
            case 10:
                $this->setBibliographiccitation($value);
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
        if (array_key_exists($keys[2], $arr)) $this->setPublishingcompanyId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPlaceId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDatespecificationId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setPrintrun($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setPrintruncomment($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setEdition($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setNumpages($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setNumpagesnormed($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setBibliographiccitation($arr[$keys[10]]);
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
        if ($this->isColumnModified(PublicationPeer::PUBLISHINGCOMPANY_ID)) $criteria->add(PublicationPeer::PUBLISHINGCOMPANY_ID, $this->publishingcompany_id);
        if ($this->isColumnModified(PublicationPeer::PLACE_ID)) $criteria->add(PublicationPeer::PLACE_ID, $this->place_id);
        if ($this->isColumnModified(PublicationPeer::DATESPECIFICATION_ID)) $criteria->add(PublicationPeer::DATESPECIFICATION_ID, $this->datespecification_id);
        if ($this->isColumnModified(PublicationPeer::PRINTRUN)) $criteria->add(PublicationPeer::PRINTRUN, $this->printrun);
        if ($this->isColumnModified(PublicationPeer::PRINTRUNCOMMENT)) $criteria->add(PublicationPeer::PRINTRUNCOMMENT, $this->printruncomment);
        if ($this->isColumnModified(PublicationPeer::EDITION)) $criteria->add(PublicationPeer::EDITION, $this->edition);
        if ($this->isColumnModified(PublicationPeer::NUMPAGES)) $criteria->add(PublicationPeer::NUMPAGES, $this->numpages);
        if ($this->isColumnModified(PublicationPeer::NUMPAGESNORMED)) $criteria->add(PublicationPeer::NUMPAGESNORMED, $this->numpagesnormed);
        if ($this->isColumnModified(PublicationPeer::BIBLIOGRAPHICCITATION)) $criteria->add(PublicationPeer::BIBLIOGRAPHICCITATION, $this->bibliographiccitation);

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
        $copyObj->setPublishingcompanyId($this->getPublishingcompanyId());
        $copyObj->setPlaceId($this->getPlaceId());
        $copyObj->setDatespecificationId($this->getDatespecificationId());
        $copyObj->setPrintrun($this->getPrintrun());
        $copyObj->setPrintruncomment($this->getPrintruncomment());
        $copyObj->setEdition($this->getEdition());
        $copyObj->setNumpages($this->getNumpages());
        $copyObj->setNumpagesnormed($this->getNumpagesnormed());
        $copyObj->setBibliographiccitation($this->getBibliographiccitation());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getWrits() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addWrit($relObj->copy($deepCopy));
                }
            }

            $relObj = $this->getEssay();
            if ($relObj) {
                $copyObj->setEssay($relObj->copy($deepCopy));
            }

            $relObj = $this->getMagazine();
            if ($relObj) {
                $copyObj->setMagazine($relObj->copy($deepCopy));
            }

            $relObj = $this->getMonograph();
            if ($relObj) {
                $copyObj->setMonograph($relObj->copy($deepCopy));
            }

            $relObj = $this->getSeries();
            if ($relObj) {
                $copyObj->setSeries($relObj->copy($deepCopy));
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
    public function setDatespecification(Datespecification $v = null)
    {
        if ($v === null) {
            $this->setDatespecificationId(NULL);
        } else {
            $this->setDatespecificationId($v->getId());
        }

        $this->aDatespecification = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Datespecification object, it will not be re-added.
        if ($v !== null) {
            $v->addPublication($this);
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
    public function getDatespecification(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDatespecification === null && ($this->datespecification_id !== null) && $doQuery) {
            $this->aDatespecification = DatespecificationQuery::create()->findPk($this->datespecification_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDatespecification->addPublications($this);
             */
        }

        return $this->aDatespecification;
    }

    /**
     * Declares an association between this object and a Essay object.
     *
     * @param             Essay $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEssay(Essay $v = null)
    {
        if ($v === null) {
            $this->setId(NULL);
        } else {
            $this->setId($v->getId());
        }

        $this->aEssay = $v;

        // Add binding for other direction of this 1:1 relationship.
        if ($v !== null) {
            $v->setPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated Essay object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Essay The associated Essay object.
     * @throws PropelException
     */
    public function getEssay(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aEssay === null && ($this->id !== null) && $doQuery) {
            $this->aEssay = EssayQuery::create()->findPk($this->id, $con);
            // Because this foreign key represents a one-to-one relationship, we will create a bi-directional association.
            $this->aEssay->setPublication($this);
        }

        return $this->aEssay;
    }

    /**
     * Declares an association between this object and a Magazine object.
     *
     * @param             Magazine $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMagazine(Magazine $v = null)
    {
        if ($v === null) {
            $this->setId(NULL);
        } else {
            $this->setId($v->getId());
        }

        $this->aMagazine = $v;

        // Add binding for other direction of this 1:1 relationship.
        if ($v !== null) {
            $v->setPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated Magazine object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Magazine The associated Magazine object.
     * @throws PropelException
     */
    public function getMagazine(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aMagazine === null && ($this->id !== null) && $doQuery) {
            $this->aMagazine = MagazineQuery::create()->findPk($this->id, $con);
            // Because this foreign key represents a one-to-one relationship, we will create a bi-directional association.
            $this->aMagazine->setPublication($this);
        }

        return $this->aMagazine;
    }

    /**
     * Declares an association between this object and a Monograph object.
     *
     * @param             Monograph $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMonograph(Monograph $v = null)
    {
        if ($v === null) {
            $this->setId(NULL);
        } else {
            $this->setId($v->getId());
        }

        $this->aMonograph = $v;

        // Add binding for other direction of this 1:1 relationship.
        if ($v !== null) {
            $v->setPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated Monograph object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Monograph The associated Monograph object.
     * @throws PropelException
     */
    public function getMonograph(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aMonograph === null && ($this->id !== null) && $doQuery) {
            $this->aMonograph = MonographQuery::create()->findPk($this->id, $con);
            // Because this foreign key represents a one-to-one relationship, we will create a bi-directional association.
            $this->aMonograph->setPublication($this);
        }

        return $this->aMonograph;
    }

    /**
     * Declares an association between this object and a Series object.
     *
     * @param             Series $v
     * @return Publication The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSeries(Series $v = null)
    {
        if ($v === null) {
            $this->setId(NULL);
        } else {
            $this->setId($v->getId());
        }

        $this->aSeries = $v;

        // Add binding for other direction of this 1:1 relationship.
        if ($v !== null) {
            $v->setPublication($this);
        }


        return $this;
    }


    /**
     * Get the associated Series object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Series The associated Series object.
     * @throws PropelException
     */
    public function getSeries(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aSeries === null && ($this->id !== null) && $doQuery) {
            $this->aSeries = SeriesQuery::create()->findPk($this->id, $con);
            // Because this foreign key represents a one-to-one relationship, we will create a bi-directional association.
            $this->aSeries->setPublication($this);
        }

        return $this->aSeries;
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
        if ('Writ' == $relationName) {
            $this->initWrits();
        }
    }

    /**
     * Clears out the collWrits collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addWrits()
     */
    public function clearWrits()
    {
        $this->collWrits = null; // important to set this to null since that means it is uninitialized
        $this->collWritsPartial = null;

        return $this;
    }

    /**
     * reset is the collWrits collection loaded partially
     *
     * @return void
     */
    public function resetPartialWrits($v = true)
    {
        $this->collWritsPartial = $v;
    }

    /**
     * Initializes the collWrits collection.
     *
     * By default this just sets the collWrits collection to an empty array (like clearcollWrits());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initWrits($overrideExisting = true)
    {
        if (null !== $this->collWrits && !$overrideExisting) {
            return;
        }
        $this->collWrits = new PropelObjectCollection();
        $this->collWrits->setModel('Writ');
    }

    /**
     * Gets an array of Writ objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Writ[] List of Writ objects
     * @throws PropelException
     */
    public function getWrits($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collWritsPartial && !$this->isNew();
        if (null === $this->collWrits || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collWrits) {
                // return empty collection
                $this->initWrits();
            } else {
                $collWrits = WritQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collWritsPartial && count($collWrits)) {
                      $this->initWrits(false);

                      foreach($collWrits as $obj) {
                        if (false == $this->collWrits->contains($obj)) {
                          $this->collWrits->append($obj);
                        }
                      }

                      $this->collWritsPartial = true;
                    }

                    $collWrits->getInternalIterator()->rewind();
                    return $collWrits;
                }

                if($partial && $this->collWrits) {
                    foreach($this->collWrits as $obj) {
                        if($obj->isNew()) {
                            $collWrits[] = $obj;
                        }
                    }
                }

                $this->collWrits = $collWrits;
                $this->collWritsPartial = false;
            }
        }

        return $this->collWrits;
    }

    /**
     * Sets a collection of Writ objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $writs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setWrits(PropelCollection $writs, PropelPDO $con = null)
    {
        $writsToDelete = $this->getWrits(new Criteria(), $con)->diff($writs);

        $this->writsScheduledForDeletion = unserialize(serialize($writsToDelete));

        foreach ($writsToDelete as $writRemoved) {
            $writRemoved->setPublication(null);
        }

        $this->collWrits = null;
        foreach ($writs as $writ) {
            $this->addWrit($writ);
        }

        $this->collWrits = $writs;
        $this->collWritsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Writ objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Writ objects.
     * @throws PropelException
     */
    public function countWrits(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collWritsPartial && !$this->isNew();
        if (null === $this->collWrits || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collWrits) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getWrits());
            }
            $query = WritQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublication($this)
                ->count($con);
        }

        return count($this->collWrits);
    }

    /**
     * Method called to associate a Writ object to this object
     * through the Writ foreign key attribute.
     *
     * @param    Writ $l Writ
     * @return Publication The current object (for fluent API support)
     */
    public function addWrit(Writ $l)
    {
        if ($this->collWrits === null) {
            $this->initWrits();
            $this->collWritsPartial = true;
        }
        if (!in_array($l, $this->collWrits->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddWrit($l);
        }

        return $this;
    }

    /**
     * @param	Writ $writ The writ object to add.
     */
    protected function doAddWrit($writ)
    {
        $this->collWrits[]= $writ;
        $writ->setPublication($this);
    }

    /**
     * @param	Writ $writ The writ object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeWrit($writ)
    {
        if ($this->getWrits()->contains($writ)) {
            $this->collWrits->remove($this->collWrits->search($writ));
            if (null === $this->writsScheduledForDeletion) {
                $this->writsScheduledForDeletion = clone $this->collWrits;
                $this->writsScheduledForDeletion->clear();
            }
            $this->writsScheduledForDeletion[]= clone $writ;
            $writ->setPublication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related Writs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Writ[] List of Writ objects
     */
    public function getWritsJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WritQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getWrits($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related Writs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Writ[] List of Writ objects
     */
    public function getWritsJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WritQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getWrits($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related Writs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Writ[] List of Writ objects
     */
    public function getWritsJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WritQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getWrits($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related Writs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Writ[] List of Writ objects
     */
    public function getWritsJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WritQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getWrits($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publication is new, it will return
     * an empty collection; or if this Publication has previously
     * been saved, it will retrieve related Writs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publication.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Writ[] List of Writ objects
     */
    public function getWritsJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WritQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getWrits($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->title_id = null;
        $this->publishingcompany_id = null;
        $this->place_id = null;
        $this->datespecification_id = null;
        $this->printrun = null;
        $this->printruncomment = null;
        $this->edition = null;
        $this->numpages = null;
        $this->numpagesnormed = null;
        $this->bibliographiccitation = null;
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
            if ($this->collWrits) {
                foreach ($this->collWrits as $o) {
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
            if ($this->aDatespecification instanceof Persistent) {
              $this->aDatespecification->clearAllReferences($deep);
            }
            if ($this->aEssay instanceof Persistent) {
              $this->aEssay->clearAllReferences($deep);
            }
            if ($this->aMagazine instanceof Persistent) {
              $this->aMagazine->clearAllReferences($deep);
            }
            if ($this->aMonograph instanceof Persistent) {
              $this->aMonograph->clearAllReferences($deep);
            }
            if ($this->aSeries instanceof Persistent) {
              $this->aSeries->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collWrits instanceof PropelCollection) {
            $this->collWrits->clearIterator();
        }
        $this->collWrits = null;
        $this->aTitle = null;
        $this->aPublishingcompany = null;
        $this->aPlace = null;
        $this->aDatespecification = null;
        $this->aEssay = null;
        $this->aMagazine = null;
        $this->aMonograph = null;
        $this->aSeries = null;
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

}
