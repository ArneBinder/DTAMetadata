<?php

namespace DTA\MetadataBundle\Model\Publication\om;

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
use DTA\MetadataBundle\Model\Description\Datespecification;
use DTA\MetadataBundle\Model\Description\DatespecificationQuery;
use DTA\MetadataBundle\Model\Description\Place;
use DTA\MetadataBundle\Model\Description\PlaceQuery;
use DTA\MetadataBundle\Model\Description\Title;
use DTA\MetadataBundle\Model\Description\TitleQuery;
use DTA\MetadataBundle\Model\Publication\Essay;
use DTA\MetadataBundle\Model\Publication\EssayQuery;
use DTA\MetadataBundle\Model\Publication\Magazine;
use DTA\MetadataBundle\Model\Publication\MagazineQuery;
use DTA\MetadataBundle\Model\Publication\Monograph;
use DTA\MetadataBundle\Model\Publication\MonographQuery;
use DTA\MetadataBundle\Model\Publication\Publication;
use DTA\MetadataBundle\Model\Publication\PublicationPeer;
use DTA\MetadataBundle\Model\Publication\PublicationQuery;
use DTA\MetadataBundle\Model\Publication\Publishingcompany;
use DTA\MetadataBundle\Model\Publication\PublishingcompanyQuery;
use DTA\MetadataBundle\Model\Publication\Series;
use DTA\MetadataBundle\Model\Publication\SeriesQuery;
use DTA\MetadataBundle\Model\Publication\Writ;
use DTA\MetadataBundle\Model\Publication\WritQuery;

abstract class BasePublication extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\Publication\\PublicationPeer';

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
     * @var        PropelObjectCollection|Essay[] Collection to store aggregation of Essay objects.
     */
    protected $collEssays;
    protected $collEssaysPartial;

    /**
     * @var        PropelObjectCollection|Magazine[] Collection to store aggregation of Magazine objects.
     */
    protected $collMagazines;
    protected $collMagazinesPartial;

    /**
     * @var        PropelObjectCollection|Monograph[] Collection to store aggregation of Monograph objects.
     */
    protected $collMonographs;
    protected $collMonographsPartial;

    /**
     * @var        PropelObjectCollection|Series[] Collection to store aggregation of Series objects.
     */
    protected $collSeries;
    protected $collSeriesPartial;

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
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $essaysScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $magazinesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $monographsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $seriesScheduledForDeletion = null;

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
     *
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
        if ($v !== null) {
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
        if ($v !== null) {
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
        if ($v !== null) {
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
        if ($v !== null) {
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
        if ($v !== null) {
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
     *
     * @param string $v new value
     * @return Publication The current object (for fluent API support)
     */
    public function setPrintrun($v)
    {
        if ($v !== null) {
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
        if ($v !== null) {
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
        if ($v !== null) {
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
        if ($v !== null) {
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
        if ($v !== null) {
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
        if ($v !== null) {
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
            $this->collEssays = null;

            $this->collMagazines = null;

            $this->collMonographs = null;

            $this->collSeries = null;

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

            if ($this->essaysScheduledForDeletion !== null) {
                if (!$this->essaysScheduledForDeletion->isEmpty()) {
                    EssayQuery::create()
                        ->filterByPrimaryKeys($this->essaysScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->essaysScheduledForDeletion = null;
                }
            }

            if ($this->collEssays !== null) {
                foreach ($this->collEssays as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->magazinesScheduledForDeletion !== null) {
                if (!$this->magazinesScheduledForDeletion->isEmpty()) {
                    MagazineQuery::create()
                        ->filterByPrimaryKeys($this->magazinesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->magazinesScheduledForDeletion = null;
                }
            }

            if ($this->collMagazines !== null) {
                foreach ($this->collMagazines as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
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
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->seriesScheduledForDeletion !== null) {
                if (!$this->seriesScheduledForDeletion->isEmpty()) {
                    SeriesQuery::create()
                        ->filterByPrimaryKeys($this->seriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->seriesScheduledForDeletion = null;
                }
            }

            if ($this->collSeries !== null) {
                foreach ($this->collSeries as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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
                    if (!$referrerFK->isDeleted()) {
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
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(PublicationPeer::TITLE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`TITLE_ID`';
        }
        if ($this->isColumnModified(PublicationPeer::PUBLISHINGCOMPANY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`PUBLISHINGCOMPANY_ID`';
        }
        if ($this->isColumnModified(PublicationPeer::PLACE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`PLACE_ID`';
        }
        if ($this->isColumnModified(PublicationPeer::DATESPECIFICATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`DATESPECIFICATION_ID`';
        }
        if ($this->isColumnModified(PublicationPeer::PRINTRUN)) {
            $modifiedColumns[':p' . $index++]  = '`PRINTRUN`';
        }
        if ($this->isColumnModified(PublicationPeer::PRINTRUNCOMMENT)) {
            $modifiedColumns[':p' . $index++]  = '`PRINTRUNCOMMENT`';
        }
        if ($this->isColumnModified(PublicationPeer::EDITION)) {
            $modifiedColumns[':p' . $index++]  = '`EDITION`';
        }
        if ($this->isColumnModified(PublicationPeer::NUMPAGES)) {
            $modifiedColumns[':p' . $index++]  = '`NUMPAGES`';
        }
        if ($this->isColumnModified(PublicationPeer::NUMPAGESNORMED)) {
            $modifiedColumns[':p' . $index++]  = '`NUMPAGESNORMED`';
        }
        if ($this->isColumnModified(PublicationPeer::BIBLIOGRAPHICCITATION)) {
            $modifiedColumns[':p' . $index++]  = '`BIBLIOGRAPHICCITATION`';
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
                    case '`ID`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`TITLE_ID`':
                        $stmt->bindValue($identifier, $this->title_id, PDO::PARAM_INT);
                        break;
                    case '`PUBLISHINGCOMPANY_ID`':
                        $stmt->bindValue($identifier, $this->publishingcompany_id, PDO::PARAM_INT);
                        break;
                    case '`PLACE_ID`':
                        $stmt->bindValue($identifier, $this->place_id, PDO::PARAM_INT);
                        break;
                    case '`DATESPECIFICATION_ID`':
                        $stmt->bindValue($identifier, $this->datespecification_id, PDO::PARAM_INT);
                        break;
                    case '`PRINTRUN`':
                        $stmt->bindValue($identifier, $this->printrun, PDO::PARAM_STR);
                        break;
                    case '`PRINTRUNCOMMENT`':
                        $stmt->bindValue($identifier, $this->printruncomment, PDO::PARAM_STR);
                        break;
                    case '`EDITION`':
                        $stmt->bindValue($identifier, $this->edition, PDO::PARAM_STR);
                        break;
                    case '`NUMPAGES`':
                        $stmt->bindValue($identifier, $this->numpages, PDO::PARAM_INT);
                        break;
                    case '`NUMPAGESNORMED`':
                        $stmt->bindValue($identifier, $this->numpagesnormed, PDO::PARAM_INT);
                        break;
                    case '`BIBLIOGRAPHICCITATION`':
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
        } else {
            $this->validationFailures = $res;

            return false;
        }
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


            if (($retval = PublicationPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collEssays !== null) {
                    foreach ($this->collEssays as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collMagazines !== null) {
                    foreach ($this->collMagazines as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collMonographs !== null) {
                    foreach ($this->collMonographs as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSeries !== null) {
                    foreach ($this->collSeries as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
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
            if (null !== $this->collEssays) {
                $result['Essays'] = $this->collEssays->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMagazines) {
                $result['Magazines'] = $this->collMagazines->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMonographs) {
                $result['Monographs'] = $this->collMonographs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSeries) {
                $result['Series'] = $this->collSeries->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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

            foreach ($this->getEssays() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEssay($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMagazines() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMagazine($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMonographs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMonograph($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSeries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSeries($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getWrits() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addWrit($relObj->copy($deepCopy));
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
     * @return Title The associated Title object.
     * @throws PropelException
     */
    public function getTitle(PropelPDO $con = null)
    {
        if ($this->aTitle === null && ($this->title_id !== null)) {
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
     * @return Publishingcompany The associated Publishingcompany object.
     * @throws PropelException
     */
    public function getPublishingcompany(PropelPDO $con = null)
    {
        if ($this->aPublishingcompany === null && ($this->publishingcompany_id !== null)) {
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
     * @return Place The associated Place object.
     * @throws PropelException
     */
    public function getPlace(PropelPDO $con = null)
    {
        if ($this->aPlace === null && ($this->place_id !== null)) {
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
     * @return Datespecification The associated Datespecification object.
     * @throws PropelException
     */
    public function getDatespecification(PropelPDO $con = null)
    {
        if ($this->aDatespecification === null && ($this->datespecification_id !== null)) {
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Essay' == $relationName) {
            $this->initEssays();
        }
        if ('Magazine' == $relationName) {
            $this->initMagazines();
        }
        if ('Monograph' == $relationName) {
            $this->initMonographs();
        }
        if ('Series' == $relationName) {
            $this->initSeries();
        }
        if ('Writ' == $relationName) {
            $this->initWrits();
        }
    }

    /**
     * Clears out the collEssays collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addEssays()
     */
    public function clearEssays()
    {
        $this->collEssays = null; // important to set this to null since that means it is uninitialized
        $this->collEssaysPartial = null;

        return $this;
    }

    /**
     * reset is the collEssays collection loaded partially
     *
     * @return void
     */
    public function resetPartialEssays($v = true)
    {
        $this->collEssaysPartial = $v;
    }

    /**
     * Initializes the collEssays collection.
     *
     * By default this just sets the collEssays collection to an empty array (like clearcollEssays());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEssays($overrideExisting = true)
    {
        if (null !== $this->collEssays && !$overrideExisting) {
            return;
        }
        $this->collEssays = new PropelObjectCollection();
        $this->collEssays->setModel('Essay');
    }

    /**
     * Gets an array of Essay objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Essay[] List of Essay objects
     * @throws PropelException
     */
    public function getEssays($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEssaysPartial && !$this->isNew();
        if (null === $this->collEssays || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEssays) {
                // return empty collection
                $this->initEssays();
            } else {
                $collEssays = EssayQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEssaysPartial && count($collEssays)) {
                      $this->initEssays(false);

                      foreach($collEssays as $obj) {
                        if (false == $this->collEssays->contains($obj)) {
                          $this->collEssays->append($obj);
                        }
                      }

                      $this->collEssaysPartial = true;
                    }

                    return $collEssays;
                }

                if($partial && $this->collEssays) {
                    foreach($this->collEssays as $obj) {
                        if($obj->isNew()) {
                            $collEssays[] = $obj;
                        }
                    }
                }

                $this->collEssays = $collEssays;
                $this->collEssaysPartial = false;
            }
        }

        return $this->collEssays;
    }

    /**
     * Sets a collection of Essay objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $essays A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setEssays(PropelCollection $essays, PropelPDO $con = null)
    {
        $this->essaysScheduledForDeletion = $this->getEssays(new Criteria(), $con)->diff($essays);

        foreach ($this->essaysScheduledForDeletion as $essayRemoved) {
            $essayRemoved->setPublication(null);
        }

        $this->collEssays = null;
        foreach ($essays as $essay) {
            $this->addEssay($essay);
        }

        $this->collEssays = $essays;
        $this->collEssaysPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Essay objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Essay objects.
     * @throws PropelException
     */
    public function countEssays(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEssaysPartial && !$this->isNew();
        if (null === $this->collEssays || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEssays) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getEssays());
                }
                $query = EssayQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPublication($this)
                    ->count($con);
            }
        } else {
            return count($this->collEssays);
        }
    }

    /**
     * Method called to associate a Essay object to this object
     * through the Essay foreign key attribute.
     *
     * @param    Essay $l Essay
     * @return Publication The current object (for fluent API support)
     */
    public function addEssay(Essay $l)
    {
        if ($this->collEssays === null) {
            $this->initEssays();
            $this->collEssaysPartial = true;
        }
        if (!in_array($l, $this->collEssays->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEssay($l);
        }

        return $this;
    }

    /**
     * @param	Essay $essay The essay object to add.
     */
    protected function doAddEssay($essay)
    {
        $this->collEssays[]= $essay;
        $essay->setPublication($this);
    }

    /**
     * @param	Essay $essay The essay object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeEssay($essay)
    {
        if ($this->getEssays()->contains($essay)) {
            $this->collEssays->remove($this->collEssays->search($essay));
            if (null === $this->essaysScheduledForDeletion) {
                $this->essaysScheduledForDeletion = clone $this->collEssays;
                $this->essaysScheduledForDeletion->clear();
            }
            $this->essaysScheduledForDeletion[]= $essay;
            $essay->setPublication(null);
        }

        return $this;
    }

    /**
     * Clears out the collMagazines collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addMagazines()
     */
    public function clearMagazines()
    {
        $this->collMagazines = null; // important to set this to null since that means it is uninitialized
        $this->collMagazinesPartial = null;

        return $this;
    }

    /**
     * reset is the collMagazines collection loaded partially
     *
     * @return void
     */
    public function resetPartialMagazines($v = true)
    {
        $this->collMagazinesPartial = $v;
    }

    /**
     * Initializes the collMagazines collection.
     *
     * By default this just sets the collMagazines collection to an empty array (like clearcollMagazines());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMagazines($overrideExisting = true)
    {
        if (null !== $this->collMagazines && !$overrideExisting) {
            return;
        }
        $this->collMagazines = new PropelObjectCollection();
        $this->collMagazines->setModel('Magazine');
    }

    /**
     * Gets an array of Magazine objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     * @throws PropelException
     */
    public function getMagazines($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMagazinesPartial && !$this->isNew();
        if (null === $this->collMagazines || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMagazines) {
                // return empty collection
                $this->initMagazines();
            } else {
                $collMagazines = MagazineQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMagazinesPartial && count($collMagazines)) {
                      $this->initMagazines(false);

                      foreach($collMagazines as $obj) {
                        if (false == $this->collMagazines->contains($obj)) {
                          $this->collMagazines->append($obj);
                        }
                      }

                      $this->collMagazinesPartial = true;
                    }

                    return $collMagazines;
                }

                if($partial && $this->collMagazines) {
                    foreach($this->collMagazines as $obj) {
                        if($obj->isNew()) {
                            $collMagazines[] = $obj;
                        }
                    }
                }

                $this->collMagazines = $collMagazines;
                $this->collMagazinesPartial = false;
            }
        }

        return $this->collMagazines;
    }

    /**
     * Sets a collection of Magazine objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $magazines A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setMagazines(PropelCollection $magazines, PropelPDO $con = null)
    {
        $this->magazinesScheduledForDeletion = $this->getMagazines(new Criteria(), $con)->diff($magazines);

        foreach ($this->magazinesScheduledForDeletion as $magazineRemoved) {
            $magazineRemoved->setPublication(null);
        }

        $this->collMagazines = null;
        foreach ($magazines as $magazine) {
            $this->addMagazine($magazine);
        }

        $this->collMagazines = $magazines;
        $this->collMagazinesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Magazine objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Magazine objects.
     * @throws PropelException
     */
    public function countMagazines(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collMagazinesPartial && !$this->isNew();
        if (null === $this->collMagazines || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMagazines) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getMagazines());
                }
                $query = MagazineQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPublication($this)
                    ->count($con);
            }
        } else {
            return count($this->collMagazines);
        }
    }

    /**
     * Method called to associate a Magazine object to this object
     * through the Magazine foreign key attribute.
     *
     * @param    Magazine $l Magazine
     * @return Publication The current object (for fluent API support)
     */
    public function addMagazine(Magazine $l)
    {
        if ($this->collMagazines === null) {
            $this->initMagazines();
            $this->collMagazinesPartial = true;
        }
        if (!in_array($l, $this->collMagazines->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMagazine($l);
        }

        return $this;
    }

    /**
     * @param	Magazine $magazine The magazine object to add.
     */
    protected function doAddMagazine($magazine)
    {
        $this->collMagazines[]= $magazine;
        $magazine->setPublication($this);
    }

    /**
     * @param	Magazine $magazine The magazine object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeMagazine($magazine)
    {
        if ($this->getMagazines()->contains($magazine)) {
            $this->collMagazines->remove($this->collMagazines->search($magazine));
            if (null === $this->magazinesScheduledForDeletion) {
                $this->magazinesScheduledForDeletion = clone $this->collMagazines;
                $this->magazinesScheduledForDeletion->clear();
            }
            $this->magazinesScheduledForDeletion[]= $magazine;
            $magazine->setPublication(null);
        }

        return $this;
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
        $this->monographsScheduledForDeletion = $this->getMonographs(new Criteria(), $con)->diff($monographs);

        foreach ($this->monographsScheduledForDeletion as $monographRemoved) {
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
            } else {
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
        } else {
            return count($this->collMonographs);
        }
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
            $this->monographsScheduledForDeletion[]= $monograph;
            $monograph->setPublication(null);
        }

        return $this;
    }

    /**
     * Clears out the collSeries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publication The current object (for fluent API support)
     * @see        addSeries()
     */
    public function clearSeries()
    {
        $this->collSeries = null; // important to set this to null since that means it is uninitialized
        $this->collSeriesPartial = null;

        return $this;
    }

    /**
     * reset is the collSeries collection loaded partially
     *
     * @return void
     */
    public function resetPartialSeries($v = true)
    {
        $this->collSeriesPartial = $v;
    }

    /**
     * Initializes the collSeries collection.
     *
     * By default this just sets the collSeries collection to an empty array (like clearcollSeries());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSeries($overrideExisting = true)
    {
        if (null !== $this->collSeries && !$overrideExisting) {
            return;
        }
        $this->collSeries = new PropelObjectCollection();
        $this->collSeries->setModel('Series');
    }

    /**
     * Gets an array of Series objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publication is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Series[] List of Series objects
     * @throws PropelException
     */
    public function getSeries($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSeriesPartial && !$this->isNew();
        if (null === $this->collSeries || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSeries) {
                // return empty collection
                $this->initSeries();
            } else {
                $collSeries = SeriesQuery::create(null, $criteria)
                    ->filterByPublication($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSeriesPartial && count($collSeries)) {
                      $this->initSeries(false);

                      foreach($collSeries as $obj) {
                        if (false == $this->collSeries->contains($obj)) {
                          $this->collSeries->append($obj);
                        }
                      }

                      $this->collSeriesPartial = true;
                    }

                    return $collSeries;
                }

                if($partial && $this->collSeries) {
                    foreach($this->collSeries as $obj) {
                        if($obj->isNew()) {
                            $collSeries[] = $obj;
                        }
                    }
                }

                $this->collSeries = $collSeries;
                $this->collSeriesPartial = false;
            }
        }

        return $this->collSeries;
    }

    /**
     * Sets a collection of Series objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $series A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publication The current object (for fluent API support)
     */
    public function setSeries(PropelCollection $series, PropelPDO $con = null)
    {
        $this->seriesScheduledForDeletion = $this->getSeries(new Criteria(), $con)->diff($series);

        foreach ($this->seriesScheduledForDeletion as $seriesRemoved) {
            $seriesRemoved->setPublication(null);
        }

        $this->collSeries = null;
        foreach ($series as $series) {
            $this->addSeries($series);
        }

        $this->collSeries = $series;
        $this->collSeriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Series objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Series objects.
     * @throws PropelException
     */
    public function countSeries(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSeriesPartial && !$this->isNew();
        if (null === $this->collSeries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSeries) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getSeries());
                }
                $query = SeriesQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPublication($this)
                    ->count($con);
            }
        } else {
            return count($this->collSeries);
        }
    }

    /**
     * Method called to associate a Series object to this object
     * through the Series foreign key attribute.
     *
     * @param    Series $l Series
     * @return Publication The current object (for fluent API support)
     */
    public function addSeries(Series $l)
    {
        if ($this->collSeries === null) {
            $this->initSeries();
            $this->collSeriesPartial = true;
        }
        if (!in_array($l, $this->collSeries->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSeries($l);
        }

        return $this;
    }

    /**
     * @param	Series $series The series object to add.
     */
    protected function doAddSeries($series)
    {
        $this->collSeries[]= $series;
        $series->setPublication($this);
    }

    /**
     * @param	Series $series The series object to remove.
     * @return Publication The current object (for fluent API support)
     */
    public function removeSeries($series)
    {
        if ($this->getSeries()->contains($series)) {
            $this->collSeries->remove($this->collSeries->search($series));
            if (null === $this->seriesScheduledForDeletion) {
                $this->seriesScheduledForDeletion = clone $this->collSeries;
                $this->seriesScheduledForDeletion->clear();
            }
            $this->seriesScheduledForDeletion[]= $series;
            $series->setPublication(null);
        }

        return $this;
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
        $this->writsScheduledForDeletion = $this->getWrits(new Criteria(), $con)->diff($writs);

        foreach ($this->writsScheduledForDeletion as $writRemoved) {
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
            } else {
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
        } else {
            return count($this->collWrits);
        }
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
            $this->writsScheduledForDeletion[]= $writ;
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
        if ($deep) {
            if ($this->collEssays) {
                foreach ($this->collEssays as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMagazines) {
                foreach ($this->collMagazines as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMonographs) {
                foreach ($this->collMonographs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSeries) {
                foreach ($this->collSeries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWrits) {
                foreach ($this->collWrits as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collEssays instanceof PropelCollection) {
            $this->collEssays->clearIterator();
        }
        $this->collEssays = null;
        if ($this->collMagazines instanceof PropelCollection) {
            $this->collMagazines->clearIterator();
        }
        $this->collMagazines = null;
        if ($this->collMonographs instanceof PropelCollection) {
            $this->collMonographs->clearIterator();
        }
        $this->collMonographs = null;
        if ($this->collSeries instanceof PropelCollection) {
            $this->collSeries->clearIterator();
        }
        $this->collSeries = null;
        if ($this->collWrits instanceof PropelCollection) {
            $this->collWrits->clearIterator();
        }
        $this->collWrits = null;
        $this->aTitle = null;
        $this->aPublishingcompany = null;
        $this->aPlace = null;
        $this->aDatespecification = null;
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
