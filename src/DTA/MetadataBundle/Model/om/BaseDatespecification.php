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
use DTA\MetadataBundle\Model\DatespecificationPeer;
use DTA\MetadataBundle\Model\DatespecificationQuery;
use DTA\MetadataBundle\Model\Essay;
use DTA\MetadataBundle\Model\EssayQuery;
use DTA\MetadataBundle\Model\Magazine;
use DTA\MetadataBundle\Model\MagazineQuery;
use DTA\MetadataBundle\Model\Publication;
use DTA\MetadataBundle\Model\PublicationQuery;
use DTA\MetadataBundle\Model\Series;
use DTA\MetadataBundle\Model\SeriesQuery;
use DTA\MetadataBundle\Model\Work;
use DTA\MetadataBundle\Model\WorkQuery;

abstract class BaseDatespecification extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\DatespecificationPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        DatespecificationPeer
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
     * The value for the year field.
     * @var        int
     */
    protected $year;

    /**
     * The value for the comments field.
     * @var        string
     */
    protected $comments;

    /**
     * The value for the year_is_reconstructed field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $year_is_reconstructed;

    /**
     * @var        PropelObjectCollection|Publication[] Collection to store aggregation of Publication objects.
     */
    protected $collPublicationsRelatedByPublicationdateId;
    protected $collPublicationsRelatedByPublicationdateIdPartial;

    /**
     * @var        PropelObjectCollection|Publication[] Collection to store aggregation of Publication objects.
     */
    protected $collPublicationsRelatedByOrigindateId;
    protected $collPublicationsRelatedByOrigindateIdPartial;

    /**
     * @var        PropelObjectCollection|Work[] Collection to store aggregation of Work objects.
     */
    protected $collWorks;
    protected $collWorksPartial;

    /**
     * @var        PropelObjectCollection|Essay[] Collection to store aggregation of Essay objects.
     */
    protected $collEssaysRelatedByPublicationdateId;
    protected $collEssaysRelatedByPublicationdateIdPartial;

    /**
     * @var        PropelObjectCollection|Essay[] Collection to store aggregation of Essay objects.
     */
    protected $collEssaysRelatedByOrigindateId;
    protected $collEssaysRelatedByOrigindateIdPartial;

    /**
     * @var        PropelObjectCollection|Magazine[] Collection to store aggregation of Magazine objects.
     */
    protected $collMagazinesRelatedByPublicationdateId;
    protected $collMagazinesRelatedByPublicationdateIdPartial;

    /**
     * @var        PropelObjectCollection|Magazine[] Collection to store aggregation of Magazine objects.
     */
    protected $collMagazinesRelatedByOrigindateId;
    protected $collMagazinesRelatedByOrigindateIdPartial;

    /**
     * @var        PropelObjectCollection|Series[] Collection to store aggregation of Series objects.
     */
    protected $collSeriesRelatedByPublicationdateId;
    protected $collSeriesRelatedByPublicationdateIdPartial;

    /**
     * @var        PropelObjectCollection|Series[] Collection to store aggregation of Series objects.
     */
    protected $collSeriesRelatedByOrigindateId;
    protected $collSeriesRelatedByOrigindateIdPartial;

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
    protected $publicationsRelatedByPublicationdateIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationsRelatedByOrigindateIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $worksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $essaysRelatedByPublicationdateIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $essaysRelatedByOrigindateIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $magazinesRelatedByPublicationdateIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $magazinesRelatedByOrigindateIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $seriesRelatedByPublicationdateIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $seriesRelatedByOrigindateIdScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->year_is_reconstructed = false;
    }

    /**
     * Initializes internal state of BaseDatespecification object.
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
     * Get the [year] column value.
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Get the [comments] column value.
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Get the [year_is_reconstructed] column value.
     *
     * @return boolean
     */
    public function getYearIsReconstructed()
    {
        return $this->year_is_reconstructed;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Datespecification The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = DatespecificationPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [year] column.
     *
     * @param int $v new value
     * @return Datespecification The current object (for fluent API support)
     */
    public function setYear($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->year !== $v) {
            $this->year = $v;
            $this->modifiedColumns[] = DatespecificationPeer::YEAR;
        }


        return $this;
    } // setYear()

    /**
     * Set the value of [comments] column.
     *
     * @param string $v new value
     * @return Datespecification The current object (for fluent API support)
     */
    public function setComments($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->comments !== $v) {
            $this->comments = $v;
            $this->modifiedColumns[] = DatespecificationPeer::COMMENTS;
        }


        return $this;
    } // setComments()

    /**
     * Sets the value of the [year_is_reconstructed] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Datespecification The current object (for fluent API support)
     */
    public function setYearIsReconstructed($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->year_is_reconstructed !== $v) {
            $this->year_is_reconstructed = $v;
            $this->modifiedColumns[] = DatespecificationPeer::YEAR_IS_RECONSTRUCTED;
        }


        return $this;
    } // setYearIsReconstructed()

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
            if ($this->year_is_reconstructed !== false) {
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
            $this->year = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->comments = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->year_is_reconstructed = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 4; // 4 = DatespecificationPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Datespecification object", $e);
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
            $con = Propel::getConnection(DatespecificationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = DatespecificationPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collPublicationsRelatedByPublicationdateId = null;

            $this->collPublicationsRelatedByOrigindateId = null;

            $this->collWorks = null;

            $this->collEssaysRelatedByPublicationdateId = null;

            $this->collEssaysRelatedByOrigindateId = null;

            $this->collMagazinesRelatedByPublicationdateId = null;

            $this->collMagazinesRelatedByOrigindateId = null;

            $this->collSeriesRelatedByPublicationdateId = null;

            $this->collSeriesRelatedByOrigindateId = null;

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
            $con = Propel::getConnection(DatespecificationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = DatespecificationQuery::create()
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
            $con = Propel::getConnection(DatespecificationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                DatespecificationPeer::addInstanceToPool($this);
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

            if ($this->publicationsRelatedByPublicationdateIdScheduledForDeletion !== null) {
                if (!$this->publicationsRelatedByPublicationdateIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->publicationsRelatedByPublicationdateIdScheduledForDeletion as $publicationRelatedByPublicationdateId) {
                        // need to save related object because we set the relation to null
                        $publicationRelatedByPublicationdateId->save($con);
                    }
                    $this->publicationsRelatedByPublicationdateIdScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationsRelatedByPublicationdateId !== null) {
                foreach ($this->collPublicationsRelatedByPublicationdateId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->publicationsRelatedByOrigindateIdScheduledForDeletion !== null) {
                if (!$this->publicationsRelatedByOrigindateIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->publicationsRelatedByOrigindateIdScheduledForDeletion as $publicationRelatedByOrigindateId) {
                        // need to save related object because we set the relation to null
                        $publicationRelatedByOrigindateId->save($con);
                    }
                    $this->publicationsRelatedByOrigindateIdScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationsRelatedByOrigindateId !== null) {
                foreach ($this->collPublicationsRelatedByOrigindateId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->worksScheduledForDeletion !== null) {
                if (!$this->worksScheduledForDeletion->isEmpty()) {
                    foreach ($this->worksScheduledForDeletion as $work) {
                        // need to save related object because we set the relation to null
                        $work->save($con);
                    }
                    $this->worksScheduledForDeletion = null;
                }
            }

            if ($this->collWorks !== null) {
                foreach ($this->collWorks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->essaysRelatedByPublicationdateIdScheduledForDeletion !== null) {
                if (!$this->essaysRelatedByPublicationdateIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->essaysRelatedByPublicationdateIdScheduledForDeletion as $essayRelatedByPublicationdateId) {
                        // need to save related object because we set the relation to null
                        $essayRelatedByPublicationdateId->save($con);
                    }
                    $this->essaysRelatedByPublicationdateIdScheduledForDeletion = null;
                }
            }

            if ($this->collEssaysRelatedByPublicationdateId !== null) {
                foreach ($this->collEssaysRelatedByPublicationdateId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->essaysRelatedByOrigindateIdScheduledForDeletion !== null) {
                if (!$this->essaysRelatedByOrigindateIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->essaysRelatedByOrigindateIdScheduledForDeletion as $essayRelatedByOrigindateId) {
                        // need to save related object because we set the relation to null
                        $essayRelatedByOrigindateId->save($con);
                    }
                    $this->essaysRelatedByOrigindateIdScheduledForDeletion = null;
                }
            }

            if ($this->collEssaysRelatedByOrigindateId !== null) {
                foreach ($this->collEssaysRelatedByOrigindateId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->magazinesRelatedByPublicationdateIdScheduledForDeletion !== null) {
                if (!$this->magazinesRelatedByPublicationdateIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->magazinesRelatedByPublicationdateIdScheduledForDeletion as $magazineRelatedByPublicationdateId) {
                        // need to save related object because we set the relation to null
                        $magazineRelatedByPublicationdateId->save($con);
                    }
                    $this->magazinesRelatedByPublicationdateIdScheduledForDeletion = null;
                }
            }

            if ($this->collMagazinesRelatedByPublicationdateId !== null) {
                foreach ($this->collMagazinesRelatedByPublicationdateId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->magazinesRelatedByOrigindateIdScheduledForDeletion !== null) {
                if (!$this->magazinesRelatedByOrigindateIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->magazinesRelatedByOrigindateIdScheduledForDeletion as $magazineRelatedByOrigindateId) {
                        // need to save related object because we set the relation to null
                        $magazineRelatedByOrigindateId->save($con);
                    }
                    $this->magazinesRelatedByOrigindateIdScheduledForDeletion = null;
                }
            }

            if ($this->collMagazinesRelatedByOrigindateId !== null) {
                foreach ($this->collMagazinesRelatedByOrigindateId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->seriesRelatedByPublicationdateIdScheduledForDeletion !== null) {
                if (!$this->seriesRelatedByPublicationdateIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->seriesRelatedByPublicationdateIdScheduledForDeletion as $seriesRelatedByPublicationdateId) {
                        // need to save related object because we set the relation to null
                        $seriesRelatedByPublicationdateId->save($con);
                    }
                    $this->seriesRelatedByPublicationdateIdScheduledForDeletion = null;
                }
            }

            if ($this->collSeriesRelatedByPublicationdateId !== null) {
                foreach ($this->collSeriesRelatedByPublicationdateId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->seriesRelatedByOrigindateIdScheduledForDeletion !== null) {
                if (!$this->seriesRelatedByOrigindateIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->seriesRelatedByOrigindateIdScheduledForDeletion as $seriesRelatedByOrigindateId) {
                        // need to save related object because we set the relation to null
                        $seriesRelatedByOrigindateId->save($con);
                    }
                    $this->seriesRelatedByOrigindateIdScheduledForDeletion = null;
                }
            }

            if ($this->collSeriesRelatedByOrigindateId !== null) {
                foreach ($this->collSeriesRelatedByOrigindateId as $referrerFK) {
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

        $this->modifiedColumns[] = DatespecificationPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DatespecificationPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DatespecificationPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(DatespecificationPeer::YEAR)) {
            $modifiedColumns[':p' . $index++]  = '`year`';
        }
        if ($this->isColumnModified(DatespecificationPeer::COMMENTS)) {
            $modifiedColumns[':p' . $index++]  = '`comments`';
        }
        if ($this->isColumnModified(DatespecificationPeer::YEAR_IS_RECONSTRUCTED)) {
            $modifiedColumns[':p' . $index++]  = '`year_is_reconstructed`';
        }

        $sql = sprintf(
            'INSERT INTO `dateSpecification` (%s) VALUES (%s)',
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
                    case '`year`':
                        $stmt->bindValue($identifier, $this->year, PDO::PARAM_INT);
                        break;
                    case '`comments`':
                        $stmt->bindValue($identifier, $this->comments, PDO::PARAM_STR);
                        break;
                    case '`year_is_reconstructed`':
                        $stmt->bindValue($identifier, (int) $this->year_is_reconstructed, PDO::PARAM_INT);
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


            if (($retval = DatespecificationPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collPublicationsRelatedByPublicationdateId !== null) {
                    foreach ($this->collPublicationsRelatedByPublicationdateId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublicationsRelatedByOrigindateId !== null) {
                    foreach ($this->collPublicationsRelatedByOrigindateId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collWorks !== null) {
                    foreach ($this->collWorks as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEssaysRelatedByPublicationdateId !== null) {
                    foreach ($this->collEssaysRelatedByPublicationdateId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEssaysRelatedByOrigindateId !== null) {
                    foreach ($this->collEssaysRelatedByOrigindateId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collMagazinesRelatedByPublicationdateId !== null) {
                    foreach ($this->collMagazinesRelatedByPublicationdateId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collMagazinesRelatedByOrigindateId !== null) {
                    foreach ($this->collMagazinesRelatedByOrigindateId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSeriesRelatedByPublicationdateId !== null) {
                    foreach ($this->collSeriesRelatedByPublicationdateId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSeriesRelatedByOrigindateId !== null) {
                    foreach ($this->collSeriesRelatedByOrigindateId as $referrerFK) {
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
        $pos = DatespecificationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getYear();
                break;
            case 2:
                return $this->getComments();
                break;
            case 3:
                return $this->getYearIsReconstructed();
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
        if (isset($alreadyDumpedObjects['Datespecification'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Datespecification'][$this->getPrimaryKey()] = true;
        $keys = DatespecificationPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getYear(),
            $keys[2] => $this->getComments(),
            $keys[3] => $this->getYearIsReconstructed(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->collPublicationsRelatedByPublicationdateId) {
                $result['PublicationsRelatedByPublicationdateId'] = $this->collPublicationsRelatedByPublicationdateId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationsRelatedByOrigindateId) {
                $result['PublicationsRelatedByOrigindateId'] = $this->collPublicationsRelatedByOrigindateId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collWorks) {
                $result['Works'] = $this->collWorks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEssaysRelatedByPublicationdateId) {
                $result['EssaysRelatedByPublicationdateId'] = $this->collEssaysRelatedByPublicationdateId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEssaysRelatedByOrigindateId) {
                $result['EssaysRelatedByOrigindateId'] = $this->collEssaysRelatedByOrigindateId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMagazinesRelatedByPublicationdateId) {
                $result['MagazinesRelatedByPublicationdateId'] = $this->collMagazinesRelatedByPublicationdateId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMagazinesRelatedByOrigindateId) {
                $result['MagazinesRelatedByOrigindateId'] = $this->collMagazinesRelatedByOrigindateId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSeriesRelatedByPublicationdateId) {
                $result['SeriesRelatedByPublicationdateId'] = $this->collSeriesRelatedByPublicationdateId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSeriesRelatedByOrigindateId) {
                $result['SeriesRelatedByOrigindateId'] = $this->collSeriesRelatedByOrigindateId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = DatespecificationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setYear($value);
                break;
            case 2:
                $this->setComments($value);
                break;
            case 3:
                $this->setYearIsReconstructed($value);
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
        $keys = DatespecificationPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setYear($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setComments($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setYearIsReconstructed($arr[$keys[3]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(DatespecificationPeer::DATABASE_NAME);

        if ($this->isColumnModified(DatespecificationPeer::ID)) $criteria->add(DatespecificationPeer::ID, $this->id);
        if ($this->isColumnModified(DatespecificationPeer::YEAR)) $criteria->add(DatespecificationPeer::YEAR, $this->year);
        if ($this->isColumnModified(DatespecificationPeer::COMMENTS)) $criteria->add(DatespecificationPeer::COMMENTS, $this->comments);
        if ($this->isColumnModified(DatespecificationPeer::YEAR_IS_RECONSTRUCTED)) $criteria->add(DatespecificationPeer::YEAR_IS_RECONSTRUCTED, $this->year_is_reconstructed);

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
        $criteria = new Criteria(DatespecificationPeer::DATABASE_NAME);
        $criteria->add(DatespecificationPeer::ID, $this->id);

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
     * @param object $copyObj An object of Datespecification (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setYear($this->getYear());
        $copyObj->setComments($this->getComments());
        $copyObj->setYearIsReconstructed($this->getYearIsReconstructed());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPublicationsRelatedByPublicationdateId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationRelatedByPublicationdateId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationsRelatedByOrigindateId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationRelatedByOrigindateId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getWorks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addWork($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEssaysRelatedByPublicationdateId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEssayRelatedByPublicationdateId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEssaysRelatedByOrigindateId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEssayRelatedByOrigindateId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMagazinesRelatedByPublicationdateId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMagazineRelatedByPublicationdateId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMagazinesRelatedByOrigindateId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMagazineRelatedByOrigindateId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSeriesRelatedByPublicationdateId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSeriesRelatedByPublicationdateId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSeriesRelatedByOrigindateId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSeriesRelatedByOrigindateId($relObj->copy($deepCopy));
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
     * @return Datespecification Clone of current object.
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
     * @return DatespecificationPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new DatespecificationPeer();
        }

        return self::$peer;
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
        if ('PublicationRelatedByPublicationdateId' == $relationName) {
            $this->initPublicationsRelatedByPublicationdateId();
        }
        if ('PublicationRelatedByOrigindateId' == $relationName) {
            $this->initPublicationsRelatedByOrigindateId();
        }
        if ('Work' == $relationName) {
            $this->initWorks();
        }
        if ('EssayRelatedByPublicationdateId' == $relationName) {
            $this->initEssaysRelatedByPublicationdateId();
        }
        if ('EssayRelatedByOrigindateId' == $relationName) {
            $this->initEssaysRelatedByOrigindateId();
        }
        if ('MagazineRelatedByPublicationdateId' == $relationName) {
            $this->initMagazinesRelatedByPublicationdateId();
        }
        if ('MagazineRelatedByOrigindateId' == $relationName) {
            $this->initMagazinesRelatedByOrigindateId();
        }
        if ('SeriesRelatedByPublicationdateId' == $relationName) {
            $this->initSeriesRelatedByPublicationdateId();
        }
        if ('SeriesRelatedByOrigindateId' == $relationName) {
            $this->initSeriesRelatedByOrigindateId();
        }
    }

    /**
     * Clears out the collPublicationsRelatedByPublicationdateId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addPublicationsRelatedByPublicationdateId()
     */
    public function clearPublicationsRelatedByPublicationdateId()
    {
        $this->collPublicationsRelatedByPublicationdateId = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationsRelatedByPublicationdateIdPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationsRelatedByPublicationdateId collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationsRelatedByPublicationdateId($v = true)
    {
        $this->collPublicationsRelatedByPublicationdateIdPartial = $v;
    }

    /**
     * Initializes the collPublicationsRelatedByPublicationdateId collection.
     *
     * By default this just sets the collPublicationsRelatedByPublicationdateId collection to an empty array (like clearcollPublicationsRelatedByPublicationdateId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationsRelatedByPublicationdateId($overrideExisting = true)
    {
        if (null !== $this->collPublicationsRelatedByPublicationdateId && !$overrideExisting) {
            return;
        }
        $this->collPublicationsRelatedByPublicationdateId = new PropelObjectCollection();
        $this->collPublicationsRelatedByPublicationdateId->setModel('Publication');
    }

    /**
     * Gets an array of Publication objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Datespecification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Publication[] List of Publication objects
     * @throws PropelException
     */
    public function getPublicationsRelatedByPublicationdateId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationsRelatedByPublicationdateIdPartial && !$this->isNew();
        if (null === $this->collPublicationsRelatedByPublicationdateId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationsRelatedByPublicationdateId) {
                // return empty collection
                $this->initPublicationsRelatedByPublicationdateId();
            } else {
                $collPublicationsRelatedByPublicationdateId = PublicationQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByPublicationdateId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationsRelatedByPublicationdateIdPartial && count($collPublicationsRelatedByPublicationdateId)) {
                      $this->initPublicationsRelatedByPublicationdateId(false);

                      foreach($collPublicationsRelatedByPublicationdateId as $obj) {
                        if (false == $this->collPublicationsRelatedByPublicationdateId->contains($obj)) {
                          $this->collPublicationsRelatedByPublicationdateId->append($obj);
                        }
                      }

                      $this->collPublicationsRelatedByPublicationdateIdPartial = true;
                    }

                    $collPublicationsRelatedByPublicationdateId->getInternalIterator()->rewind();
                    return $collPublicationsRelatedByPublicationdateId;
                }

                if($partial && $this->collPublicationsRelatedByPublicationdateId) {
                    foreach($this->collPublicationsRelatedByPublicationdateId as $obj) {
                        if($obj->isNew()) {
                            $collPublicationsRelatedByPublicationdateId[] = $obj;
                        }
                    }
                }

                $this->collPublicationsRelatedByPublicationdateId = $collPublicationsRelatedByPublicationdateId;
                $this->collPublicationsRelatedByPublicationdateIdPartial = false;
            }
        }

        return $this->collPublicationsRelatedByPublicationdateId;
    }

    /**
     * Sets a collection of PublicationRelatedByPublicationdateId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationsRelatedByPublicationdateId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setPublicationsRelatedByPublicationdateId(PropelCollection $publicationsRelatedByPublicationdateId, PropelPDO $con = null)
    {
        $publicationsRelatedByPublicationdateIdToDelete = $this->getPublicationsRelatedByPublicationdateId(new Criteria(), $con)->diff($publicationsRelatedByPublicationdateId);

        $this->publicationsRelatedByPublicationdateIdScheduledForDeletion = unserialize(serialize($publicationsRelatedByPublicationdateIdToDelete));

        foreach ($publicationsRelatedByPublicationdateIdToDelete as $publicationRelatedByPublicationdateIdRemoved) {
            $publicationRelatedByPublicationdateIdRemoved->setDatespecificationRelatedByPublicationdateId(null);
        }

        $this->collPublicationsRelatedByPublicationdateId = null;
        foreach ($publicationsRelatedByPublicationdateId as $publicationRelatedByPublicationdateId) {
            $this->addPublicationRelatedByPublicationdateId($publicationRelatedByPublicationdateId);
        }

        $this->collPublicationsRelatedByPublicationdateId = $publicationsRelatedByPublicationdateId;
        $this->collPublicationsRelatedByPublicationdateIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Publication objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Publication objects.
     * @throws PropelException
     */
    public function countPublicationsRelatedByPublicationdateId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationsRelatedByPublicationdateIdPartial && !$this->isNew();
        if (null === $this->collPublicationsRelatedByPublicationdateId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationsRelatedByPublicationdateId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationsRelatedByPublicationdateId());
            }
            $query = PublicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByPublicationdateId($this)
                ->count($con);
        }

        return count($this->collPublicationsRelatedByPublicationdateId);
    }

    /**
     * Method called to associate a Publication object to this object
     * through the Publication foreign key attribute.
     *
     * @param    Publication $l Publication
     * @return Datespecification The current object (for fluent API support)
     */
    public function addPublicationRelatedByPublicationdateId(Publication $l)
    {
        if ($this->collPublicationsRelatedByPublicationdateId === null) {
            $this->initPublicationsRelatedByPublicationdateId();
            $this->collPublicationsRelatedByPublicationdateIdPartial = true;
        }
        if (!in_array($l, $this->collPublicationsRelatedByPublicationdateId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationRelatedByPublicationdateId($l);
        }

        return $this;
    }

    /**
     * @param	PublicationRelatedByPublicationdateId $publicationRelatedByPublicationdateId The publicationRelatedByPublicationdateId object to add.
     */
    protected function doAddPublicationRelatedByPublicationdateId($publicationRelatedByPublicationdateId)
    {
        $this->collPublicationsRelatedByPublicationdateId[]= $publicationRelatedByPublicationdateId;
        $publicationRelatedByPublicationdateId->setDatespecificationRelatedByPublicationdateId($this);
    }

    /**
     * @param	PublicationRelatedByPublicationdateId $publicationRelatedByPublicationdateId The publicationRelatedByPublicationdateId object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removePublicationRelatedByPublicationdateId($publicationRelatedByPublicationdateId)
    {
        if ($this->getPublicationsRelatedByPublicationdateId()->contains($publicationRelatedByPublicationdateId)) {
            $this->collPublicationsRelatedByPublicationdateId->remove($this->collPublicationsRelatedByPublicationdateId->search($publicationRelatedByPublicationdateId));
            if (null === $this->publicationsRelatedByPublicationdateIdScheduledForDeletion) {
                $this->publicationsRelatedByPublicationdateIdScheduledForDeletion = clone $this->collPublicationsRelatedByPublicationdateId;
                $this->publicationsRelatedByPublicationdateIdScheduledForDeletion->clear();
            }
            $this->publicationsRelatedByPublicationdateIdScheduledForDeletion[]= $publicationRelatedByPublicationdateId;
            $publicationRelatedByPublicationdateId->setDatespecificationRelatedByPublicationdateId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByPublicationdateIdJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getPublicationsRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByPublicationdateIdJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getPublicationsRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByPublicationdateIdJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getPublicationsRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByPublicationdateIdJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getPublicationsRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByPublicationdateIdJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getPublicationsRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByPublicationdateIdJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getPublicationsRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByPublicationdateIdJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getPublicationsRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByPublicationdateIdJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getPublicationsRelatedByPublicationdateId($query, $con);
    }

    /**
     * Clears out the collPublicationsRelatedByOrigindateId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addPublicationsRelatedByOrigindateId()
     */
    public function clearPublicationsRelatedByOrigindateId()
    {
        $this->collPublicationsRelatedByOrigindateId = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationsRelatedByOrigindateIdPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationsRelatedByOrigindateId collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationsRelatedByOrigindateId($v = true)
    {
        $this->collPublicationsRelatedByOrigindateIdPartial = $v;
    }

    /**
     * Initializes the collPublicationsRelatedByOrigindateId collection.
     *
     * By default this just sets the collPublicationsRelatedByOrigindateId collection to an empty array (like clearcollPublicationsRelatedByOrigindateId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationsRelatedByOrigindateId($overrideExisting = true)
    {
        if (null !== $this->collPublicationsRelatedByOrigindateId && !$overrideExisting) {
            return;
        }
        $this->collPublicationsRelatedByOrigindateId = new PropelObjectCollection();
        $this->collPublicationsRelatedByOrigindateId->setModel('Publication');
    }

    /**
     * Gets an array of Publication objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Datespecification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Publication[] List of Publication objects
     * @throws PropelException
     */
    public function getPublicationsRelatedByOrigindateId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationsRelatedByOrigindateIdPartial && !$this->isNew();
        if (null === $this->collPublicationsRelatedByOrigindateId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationsRelatedByOrigindateId) {
                // return empty collection
                $this->initPublicationsRelatedByOrigindateId();
            } else {
                $collPublicationsRelatedByOrigindateId = PublicationQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByOrigindateId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationsRelatedByOrigindateIdPartial && count($collPublicationsRelatedByOrigindateId)) {
                      $this->initPublicationsRelatedByOrigindateId(false);

                      foreach($collPublicationsRelatedByOrigindateId as $obj) {
                        if (false == $this->collPublicationsRelatedByOrigindateId->contains($obj)) {
                          $this->collPublicationsRelatedByOrigindateId->append($obj);
                        }
                      }

                      $this->collPublicationsRelatedByOrigindateIdPartial = true;
                    }

                    $collPublicationsRelatedByOrigindateId->getInternalIterator()->rewind();
                    return $collPublicationsRelatedByOrigindateId;
                }

                if($partial && $this->collPublicationsRelatedByOrigindateId) {
                    foreach($this->collPublicationsRelatedByOrigindateId as $obj) {
                        if($obj->isNew()) {
                            $collPublicationsRelatedByOrigindateId[] = $obj;
                        }
                    }
                }

                $this->collPublicationsRelatedByOrigindateId = $collPublicationsRelatedByOrigindateId;
                $this->collPublicationsRelatedByOrigindateIdPartial = false;
            }
        }

        return $this->collPublicationsRelatedByOrigindateId;
    }

    /**
     * Sets a collection of PublicationRelatedByOrigindateId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationsRelatedByOrigindateId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setPublicationsRelatedByOrigindateId(PropelCollection $publicationsRelatedByOrigindateId, PropelPDO $con = null)
    {
        $publicationsRelatedByOrigindateIdToDelete = $this->getPublicationsRelatedByOrigindateId(new Criteria(), $con)->diff($publicationsRelatedByOrigindateId);

        $this->publicationsRelatedByOrigindateIdScheduledForDeletion = unserialize(serialize($publicationsRelatedByOrigindateIdToDelete));

        foreach ($publicationsRelatedByOrigindateIdToDelete as $publicationRelatedByOrigindateIdRemoved) {
            $publicationRelatedByOrigindateIdRemoved->setDatespecificationRelatedByOrigindateId(null);
        }

        $this->collPublicationsRelatedByOrigindateId = null;
        foreach ($publicationsRelatedByOrigindateId as $publicationRelatedByOrigindateId) {
            $this->addPublicationRelatedByOrigindateId($publicationRelatedByOrigindateId);
        }

        $this->collPublicationsRelatedByOrigindateId = $publicationsRelatedByOrigindateId;
        $this->collPublicationsRelatedByOrigindateIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Publication objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Publication objects.
     * @throws PropelException
     */
    public function countPublicationsRelatedByOrigindateId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationsRelatedByOrigindateIdPartial && !$this->isNew();
        if (null === $this->collPublicationsRelatedByOrigindateId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationsRelatedByOrigindateId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationsRelatedByOrigindateId());
            }
            $query = PublicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByOrigindateId($this)
                ->count($con);
        }

        return count($this->collPublicationsRelatedByOrigindateId);
    }

    /**
     * Method called to associate a Publication object to this object
     * through the Publication foreign key attribute.
     *
     * @param    Publication $l Publication
     * @return Datespecification The current object (for fluent API support)
     */
    public function addPublicationRelatedByOrigindateId(Publication $l)
    {
        if ($this->collPublicationsRelatedByOrigindateId === null) {
            $this->initPublicationsRelatedByOrigindateId();
            $this->collPublicationsRelatedByOrigindateIdPartial = true;
        }
        if (!in_array($l, $this->collPublicationsRelatedByOrigindateId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationRelatedByOrigindateId($l);
        }

        return $this;
    }

    /**
     * @param	PublicationRelatedByOrigindateId $publicationRelatedByOrigindateId The publicationRelatedByOrigindateId object to add.
     */
    protected function doAddPublicationRelatedByOrigindateId($publicationRelatedByOrigindateId)
    {
        $this->collPublicationsRelatedByOrigindateId[]= $publicationRelatedByOrigindateId;
        $publicationRelatedByOrigindateId->setDatespecificationRelatedByOrigindateId($this);
    }

    /**
     * @param	PublicationRelatedByOrigindateId $publicationRelatedByOrigindateId The publicationRelatedByOrigindateId object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removePublicationRelatedByOrigindateId($publicationRelatedByOrigindateId)
    {
        if ($this->getPublicationsRelatedByOrigindateId()->contains($publicationRelatedByOrigindateId)) {
            $this->collPublicationsRelatedByOrigindateId->remove($this->collPublicationsRelatedByOrigindateId->search($publicationRelatedByOrigindateId));
            if (null === $this->publicationsRelatedByOrigindateIdScheduledForDeletion) {
                $this->publicationsRelatedByOrigindateIdScheduledForDeletion = clone $this->collPublicationsRelatedByOrigindateId;
                $this->publicationsRelatedByOrigindateIdScheduledForDeletion->clear();
            }
            $this->publicationsRelatedByOrigindateIdScheduledForDeletion[]= $publicationRelatedByOrigindateId;
            $publicationRelatedByOrigindateId->setDatespecificationRelatedByOrigindateId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByOrigindateIdJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getPublicationsRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByOrigindateIdJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getPublicationsRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByOrigindateIdJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getPublicationsRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByOrigindateIdJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getPublicationsRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByOrigindateIdJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getPublicationsRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByOrigindateIdJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getPublicationsRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByOrigindateIdJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getPublicationsRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsRelatedByOrigindateIdJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getPublicationsRelatedByOrigindateId($query, $con);
    }

    /**
     * Clears out the collWorks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addWorks()
     */
    public function clearWorks()
    {
        $this->collWorks = null; // important to set this to null since that means it is uninitialized
        $this->collWorksPartial = null;

        return $this;
    }

    /**
     * reset is the collWorks collection loaded partially
     *
     * @return void
     */
    public function resetPartialWorks($v = true)
    {
        $this->collWorksPartial = $v;
    }

    /**
     * Initializes the collWorks collection.
     *
     * By default this just sets the collWorks collection to an empty array (like clearcollWorks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initWorks($overrideExisting = true)
    {
        if (null !== $this->collWorks && !$overrideExisting) {
            return;
        }
        $this->collWorks = new PropelObjectCollection();
        $this->collWorks->setModel('Work');
    }

    /**
     * Gets an array of Work objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Datespecification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Work[] List of Work objects
     * @throws PropelException
     */
    public function getWorks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collWorksPartial && !$this->isNew();
        if (null === $this->collWorks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collWorks) {
                // return empty collection
                $this->initWorks();
            } else {
                $collWorks = WorkQuery::create(null, $criteria)
                    ->filterByDatespecification($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collWorksPartial && count($collWorks)) {
                      $this->initWorks(false);

                      foreach($collWorks as $obj) {
                        if (false == $this->collWorks->contains($obj)) {
                          $this->collWorks->append($obj);
                        }
                      }

                      $this->collWorksPartial = true;
                    }

                    $collWorks->getInternalIterator()->rewind();
                    return $collWorks;
                }

                if($partial && $this->collWorks) {
                    foreach($this->collWorks as $obj) {
                        if($obj->isNew()) {
                            $collWorks[] = $obj;
                        }
                    }
                }

                $this->collWorks = $collWorks;
                $this->collWorksPartial = false;
            }
        }

        return $this->collWorks;
    }

    /**
     * Sets a collection of Work objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $works A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setWorks(PropelCollection $works, PropelPDO $con = null)
    {
        $worksToDelete = $this->getWorks(new Criteria(), $con)->diff($works);

        $this->worksScheduledForDeletion = unserialize(serialize($worksToDelete));

        foreach ($worksToDelete as $workRemoved) {
            $workRemoved->setDatespecification(null);
        }

        $this->collWorks = null;
        foreach ($works as $work) {
            $this->addWork($work);
        }

        $this->collWorks = $works;
        $this->collWorksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Work objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Work objects.
     * @throws PropelException
     */
    public function countWorks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collWorksPartial && !$this->isNew();
        if (null === $this->collWorks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collWorks) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getWorks());
            }
            $query = WorkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecification($this)
                ->count($con);
        }

        return count($this->collWorks);
    }

    /**
     * Method called to associate a Work object to this object
     * through the Work foreign key attribute.
     *
     * @param    Work $l Work
     * @return Datespecification The current object (for fluent API support)
     */
    public function addWork(Work $l)
    {
        if ($this->collWorks === null) {
            $this->initWorks();
            $this->collWorksPartial = true;
        }
        if (!in_array($l, $this->collWorks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddWork($l);
        }

        return $this;
    }

    /**
     * @param	Work $work The work object to add.
     */
    protected function doAddWork($work)
    {
        $this->collWorks[]= $work;
        $work->setDatespecification($this);
    }

    /**
     * @param	Work $work The work object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removeWork($work)
    {
        if ($this->getWorks()->contains($work)) {
            $this->collWorks->remove($this->collWorks->search($work));
            if (null === $this->worksScheduledForDeletion) {
                $this->worksScheduledForDeletion = clone $this->collWorks;
                $this->worksScheduledForDeletion->clear();
            }
            $this->worksScheduledForDeletion[]= $work;
            $work->setDatespecification(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related Works from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksJoinStatus($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('Status', $join_behavior);

        return $this->getWorks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related Works from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksJoinGenreRelatedByGenreId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('GenreRelatedByGenreId', $join_behavior);

        return $this->getWorks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related Works from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksJoinGenreRelatedBySubgenreId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('GenreRelatedBySubgenreId', $join_behavior);

        return $this->getWorks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related Works from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksJoinDwdsgenreRelatedByDwdsgenreId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('DwdsgenreRelatedByDwdsgenreId', $join_behavior);

        return $this->getWorks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related Works from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksJoinDwdsgenreRelatedByDwdssubgenreId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('DwdsgenreRelatedByDwdssubgenreId', $join_behavior);

        return $this->getWorks($query, $con);
    }

    /**
     * Clears out the collEssaysRelatedByPublicationdateId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addEssaysRelatedByPublicationdateId()
     */
    public function clearEssaysRelatedByPublicationdateId()
    {
        $this->collEssaysRelatedByPublicationdateId = null; // important to set this to null since that means it is uninitialized
        $this->collEssaysRelatedByPublicationdateIdPartial = null;

        return $this;
    }

    /**
     * reset is the collEssaysRelatedByPublicationdateId collection loaded partially
     *
     * @return void
     */
    public function resetPartialEssaysRelatedByPublicationdateId($v = true)
    {
        $this->collEssaysRelatedByPublicationdateIdPartial = $v;
    }

    /**
     * Initializes the collEssaysRelatedByPublicationdateId collection.
     *
     * By default this just sets the collEssaysRelatedByPublicationdateId collection to an empty array (like clearcollEssaysRelatedByPublicationdateId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEssaysRelatedByPublicationdateId($overrideExisting = true)
    {
        if (null !== $this->collEssaysRelatedByPublicationdateId && !$overrideExisting) {
            return;
        }
        $this->collEssaysRelatedByPublicationdateId = new PropelObjectCollection();
        $this->collEssaysRelatedByPublicationdateId->setModel('Essay');
    }

    /**
     * Gets an array of Essay objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Datespecification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Essay[] List of Essay objects
     * @throws PropelException
     */
    public function getEssaysRelatedByPublicationdateId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEssaysRelatedByPublicationdateIdPartial && !$this->isNew();
        if (null === $this->collEssaysRelatedByPublicationdateId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEssaysRelatedByPublicationdateId) {
                // return empty collection
                $this->initEssaysRelatedByPublicationdateId();
            } else {
                $collEssaysRelatedByPublicationdateId = EssayQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByPublicationdateId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEssaysRelatedByPublicationdateIdPartial && count($collEssaysRelatedByPublicationdateId)) {
                      $this->initEssaysRelatedByPublicationdateId(false);

                      foreach($collEssaysRelatedByPublicationdateId as $obj) {
                        if (false == $this->collEssaysRelatedByPublicationdateId->contains($obj)) {
                          $this->collEssaysRelatedByPublicationdateId->append($obj);
                        }
                      }

                      $this->collEssaysRelatedByPublicationdateIdPartial = true;
                    }

                    $collEssaysRelatedByPublicationdateId->getInternalIterator()->rewind();
                    return $collEssaysRelatedByPublicationdateId;
                }

                if($partial && $this->collEssaysRelatedByPublicationdateId) {
                    foreach($this->collEssaysRelatedByPublicationdateId as $obj) {
                        if($obj->isNew()) {
                            $collEssaysRelatedByPublicationdateId[] = $obj;
                        }
                    }
                }

                $this->collEssaysRelatedByPublicationdateId = $collEssaysRelatedByPublicationdateId;
                $this->collEssaysRelatedByPublicationdateIdPartial = false;
            }
        }

        return $this->collEssaysRelatedByPublicationdateId;
    }

    /**
     * Sets a collection of EssayRelatedByPublicationdateId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $essaysRelatedByPublicationdateId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setEssaysRelatedByPublicationdateId(PropelCollection $essaysRelatedByPublicationdateId, PropelPDO $con = null)
    {
        $essaysRelatedByPublicationdateIdToDelete = $this->getEssaysRelatedByPublicationdateId(new Criteria(), $con)->diff($essaysRelatedByPublicationdateId);

        $this->essaysRelatedByPublicationdateIdScheduledForDeletion = unserialize(serialize($essaysRelatedByPublicationdateIdToDelete));

        foreach ($essaysRelatedByPublicationdateIdToDelete as $essayRelatedByPublicationdateIdRemoved) {
            $essayRelatedByPublicationdateIdRemoved->setDatespecificationRelatedByPublicationdateId(null);
        }

        $this->collEssaysRelatedByPublicationdateId = null;
        foreach ($essaysRelatedByPublicationdateId as $essayRelatedByPublicationdateId) {
            $this->addEssayRelatedByPublicationdateId($essayRelatedByPublicationdateId);
        }

        $this->collEssaysRelatedByPublicationdateId = $essaysRelatedByPublicationdateId;
        $this->collEssaysRelatedByPublicationdateIdPartial = false;

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
    public function countEssaysRelatedByPublicationdateId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEssaysRelatedByPublicationdateIdPartial && !$this->isNew();
        if (null === $this->collEssaysRelatedByPublicationdateId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEssaysRelatedByPublicationdateId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getEssaysRelatedByPublicationdateId());
            }
            $query = EssayQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByPublicationdateId($this)
                ->count($con);
        }

        return count($this->collEssaysRelatedByPublicationdateId);
    }

    /**
     * Method called to associate a Essay object to this object
     * through the Essay foreign key attribute.
     *
     * @param    Essay $l Essay
     * @return Datespecification The current object (for fluent API support)
     */
    public function addEssayRelatedByPublicationdateId(Essay $l)
    {
        if ($this->collEssaysRelatedByPublicationdateId === null) {
            $this->initEssaysRelatedByPublicationdateId();
            $this->collEssaysRelatedByPublicationdateIdPartial = true;
        }
        if (!in_array($l, $this->collEssaysRelatedByPublicationdateId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEssayRelatedByPublicationdateId($l);
        }

        return $this;
    }

    /**
     * @param	EssayRelatedByPublicationdateId $essayRelatedByPublicationdateId The essayRelatedByPublicationdateId object to add.
     */
    protected function doAddEssayRelatedByPublicationdateId($essayRelatedByPublicationdateId)
    {
        $this->collEssaysRelatedByPublicationdateId[]= $essayRelatedByPublicationdateId;
        $essayRelatedByPublicationdateId->setDatespecificationRelatedByPublicationdateId($this);
    }

    /**
     * @param	EssayRelatedByPublicationdateId $essayRelatedByPublicationdateId The essayRelatedByPublicationdateId object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removeEssayRelatedByPublicationdateId($essayRelatedByPublicationdateId)
    {
        if ($this->getEssaysRelatedByPublicationdateId()->contains($essayRelatedByPublicationdateId)) {
            $this->collEssaysRelatedByPublicationdateId->remove($this->collEssaysRelatedByPublicationdateId->search($essayRelatedByPublicationdateId));
            if (null === $this->essaysRelatedByPublicationdateIdScheduledForDeletion) {
                $this->essaysRelatedByPublicationdateIdScheduledForDeletion = clone $this->collEssaysRelatedByPublicationdateId;
                $this->essaysRelatedByPublicationdateIdScheduledForDeletion->clear();
            }
            $this->essaysRelatedByPublicationdateIdScheduledForDeletion[]= $essayRelatedByPublicationdateId;
            $essayRelatedByPublicationdateId->setDatespecificationRelatedByPublicationdateId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByPublicationdateIdJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getEssaysRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByPublicationdateIdJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getEssaysRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByPublicationdateIdJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getEssaysRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByPublicationdateIdJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getEssaysRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByPublicationdateIdJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getEssaysRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByPublicationdateIdJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getEssaysRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByPublicationdateIdJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getEssaysRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByPublicationdateIdJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getEssaysRelatedByPublicationdateId($query, $con);
    }

    /**
     * Clears out the collEssaysRelatedByOrigindateId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addEssaysRelatedByOrigindateId()
     */
    public function clearEssaysRelatedByOrigindateId()
    {
        $this->collEssaysRelatedByOrigindateId = null; // important to set this to null since that means it is uninitialized
        $this->collEssaysRelatedByOrigindateIdPartial = null;

        return $this;
    }

    /**
     * reset is the collEssaysRelatedByOrigindateId collection loaded partially
     *
     * @return void
     */
    public function resetPartialEssaysRelatedByOrigindateId($v = true)
    {
        $this->collEssaysRelatedByOrigindateIdPartial = $v;
    }

    /**
     * Initializes the collEssaysRelatedByOrigindateId collection.
     *
     * By default this just sets the collEssaysRelatedByOrigindateId collection to an empty array (like clearcollEssaysRelatedByOrigindateId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEssaysRelatedByOrigindateId($overrideExisting = true)
    {
        if (null !== $this->collEssaysRelatedByOrigindateId && !$overrideExisting) {
            return;
        }
        $this->collEssaysRelatedByOrigindateId = new PropelObjectCollection();
        $this->collEssaysRelatedByOrigindateId->setModel('Essay');
    }

    /**
     * Gets an array of Essay objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Datespecification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Essay[] List of Essay objects
     * @throws PropelException
     */
    public function getEssaysRelatedByOrigindateId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEssaysRelatedByOrigindateIdPartial && !$this->isNew();
        if (null === $this->collEssaysRelatedByOrigindateId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEssaysRelatedByOrigindateId) {
                // return empty collection
                $this->initEssaysRelatedByOrigindateId();
            } else {
                $collEssaysRelatedByOrigindateId = EssayQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByOrigindateId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEssaysRelatedByOrigindateIdPartial && count($collEssaysRelatedByOrigindateId)) {
                      $this->initEssaysRelatedByOrigindateId(false);

                      foreach($collEssaysRelatedByOrigindateId as $obj) {
                        if (false == $this->collEssaysRelatedByOrigindateId->contains($obj)) {
                          $this->collEssaysRelatedByOrigindateId->append($obj);
                        }
                      }

                      $this->collEssaysRelatedByOrigindateIdPartial = true;
                    }

                    $collEssaysRelatedByOrigindateId->getInternalIterator()->rewind();
                    return $collEssaysRelatedByOrigindateId;
                }

                if($partial && $this->collEssaysRelatedByOrigindateId) {
                    foreach($this->collEssaysRelatedByOrigindateId as $obj) {
                        if($obj->isNew()) {
                            $collEssaysRelatedByOrigindateId[] = $obj;
                        }
                    }
                }

                $this->collEssaysRelatedByOrigindateId = $collEssaysRelatedByOrigindateId;
                $this->collEssaysRelatedByOrigindateIdPartial = false;
            }
        }

        return $this->collEssaysRelatedByOrigindateId;
    }

    /**
     * Sets a collection of EssayRelatedByOrigindateId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $essaysRelatedByOrigindateId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setEssaysRelatedByOrigindateId(PropelCollection $essaysRelatedByOrigindateId, PropelPDO $con = null)
    {
        $essaysRelatedByOrigindateIdToDelete = $this->getEssaysRelatedByOrigindateId(new Criteria(), $con)->diff($essaysRelatedByOrigindateId);

        $this->essaysRelatedByOrigindateIdScheduledForDeletion = unserialize(serialize($essaysRelatedByOrigindateIdToDelete));

        foreach ($essaysRelatedByOrigindateIdToDelete as $essayRelatedByOrigindateIdRemoved) {
            $essayRelatedByOrigindateIdRemoved->setDatespecificationRelatedByOrigindateId(null);
        }

        $this->collEssaysRelatedByOrigindateId = null;
        foreach ($essaysRelatedByOrigindateId as $essayRelatedByOrigindateId) {
            $this->addEssayRelatedByOrigindateId($essayRelatedByOrigindateId);
        }

        $this->collEssaysRelatedByOrigindateId = $essaysRelatedByOrigindateId;
        $this->collEssaysRelatedByOrigindateIdPartial = false;

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
    public function countEssaysRelatedByOrigindateId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEssaysRelatedByOrigindateIdPartial && !$this->isNew();
        if (null === $this->collEssaysRelatedByOrigindateId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEssaysRelatedByOrigindateId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getEssaysRelatedByOrigindateId());
            }
            $query = EssayQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByOrigindateId($this)
                ->count($con);
        }

        return count($this->collEssaysRelatedByOrigindateId);
    }

    /**
     * Method called to associate a Essay object to this object
     * through the Essay foreign key attribute.
     *
     * @param    Essay $l Essay
     * @return Datespecification The current object (for fluent API support)
     */
    public function addEssayRelatedByOrigindateId(Essay $l)
    {
        if ($this->collEssaysRelatedByOrigindateId === null) {
            $this->initEssaysRelatedByOrigindateId();
            $this->collEssaysRelatedByOrigindateIdPartial = true;
        }
        if (!in_array($l, $this->collEssaysRelatedByOrigindateId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEssayRelatedByOrigindateId($l);
        }

        return $this;
    }

    /**
     * @param	EssayRelatedByOrigindateId $essayRelatedByOrigindateId The essayRelatedByOrigindateId object to add.
     */
    protected function doAddEssayRelatedByOrigindateId($essayRelatedByOrigindateId)
    {
        $this->collEssaysRelatedByOrigindateId[]= $essayRelatedByOrigindateId;
        $essayRelatedByOrigindateId->setDatespecificationRelatedByOrigindateId($this);
    }

    /**
     * @param	EssayRelatedByOrigindateId $essayRelatedByOrigindateId The essayRelatedByOrigindateId object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removeEssayRelatedByOrigindateId($essayRelatedByOrigindateId)
    {
        if ($this->getEssaysRelatedByOrigindateId()->contains($essayRelatedByOrigindateId)) {
            $this->collEssaysRelatedByOrigindateId->remove($this->collEssaysRelatedByOrigindateId->search($essayRelatedByOrigindateId));
            if (null === $this->essaysRelatedByOrigindateIdScheduledForDeletion) {
                $this->essaysRelatedByOrigindateIdScheduledForDeletion = clone $this->collEssaysRelatedByOrigindateId;
                $this->essaysRelatedByOrigindateIdScheduledForDeletion->clear();
            }
            $this->essaysRelatedByOrigindateIdScheduledForDeletion[]= $essayRelatedByOrigindateId;
            $essayRelatedByOrigindateId->setDatespecificationRelatedByOrigindateId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByOrigindateIdJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getEssaysRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByOrigindateIdJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getEssaysRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByOrigindateIdJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getEssaysRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByOrigindateIdJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getEssaysRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByOrigindateIdJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getEssaysRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByOrigindateIdJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getEssaysRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByOrigindateIdJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getEssaysRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysRelatedByOrigindateIdJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getEssaysRelatedByOrigindateId($query, $con);
    }

    /**
     * Clears out the collMagazinesRelatedByPublicationdateId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addMagazinesRelatedByPublicationdateId()
     */
    public function clearMagazinesRelatedByPublicationdateId()
    {
        $this->collMagazinesRelatedByPublicationdateId = null; // important to set this to null since that means it is uninitialized
        $this->collMagazinesRelatedByPublicationdateIdPartial = null;

        return $this;
    }

    /**
     * reset is the collMagazinesRelatedByPublicationdateId collection loaded partially
     *
     * @return void
     */
    public function resetPartialMagazinesRelatedByPublicationdateId($v = true)
    {
        $this->collMagazinesRelatedByPublicationdateIdPartial = $v;
    }

    /**
     * Initializes the collMagazinesRelatedByPublicationdateId collection.
     *
     * By default this just sets the collMagazinesRelatedByPublicationdateId collection to an empty array (like clearcollMagazinesRelatedByPublicationdateId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMagazinesRelatedByPublicationdateId($overrideExisting = true)
    {
        if (null !== $this->collMagazinesRelatedByPublicationdateId && !$overrideExisting) {
            return;
        }
        $this->collMagazinesRelatedByPublicationdateId = new PropelObjectCollection();
        $this->collMagazinesRelatedByPublicationdateId->setModel('Magazine');
    }

    /**
     * Gets an array of Magazine objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Datespecification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     * @throws PropelException
     */
    public function getMagazinesRelatedByPublicationdateId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMagazinesRelatedByPublicationdateIdPartial && !$this->isNew();
        if (null === $this->collMagazinesRelatedByPublicationdateId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMagazinesRelatedByPublicationdateId) {
                // return empty collection
                $this->initMagazinesRelatedByPublicationdateId();
            } else {
                $collMagazinesRelatedByPublicationdateId = MagazineQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByPublicationdateId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMagazinesRelatedByPublicationdateIdPartial && count($collMagazinesRelatedByPublicationdateId)) {
                      $this->initMagazinesRelatedByPublicationdateId(false);

                      foreach($collMagazinesRelatedByPublicationdateId as $obj) {
                        if (false == $this->collMagazinesRelatedByPublicationdateId->contains($obj)) {
                          $this->collMagazinesRelatedByPublicationdateId->append($obj);
                        }
                      }

                      $this->collMagazinesRelatedByPublicationdateIdPartial = true;
                    }

                    $collMagazinesRelatedByPublicationdateId->getInternalIterator()->rewind();
                    return $collMagazinesRelatedByPublicationdateId;
                }

                if($partial && $this->collMagazinesRelatedByPublicationdateId) {
                    foreach($this->collMagazinesRelatedByPublicationdateId as $obj) {
                        if($obj->isNew()) {
                            $collMagazinesRelatedByPublicationdateId[] = $obj;
                        }
                    }
                }

                $this->collMagazinesRelatedByPublicationdateId = $collMagazinesRelatedByPublicationdateId;
                $this->collMagazinesRelatedByPublicationdateIdPartial = false;
            }
        }

        return $this->collMagazinesRelatedByPublicationdateId;
    }

    /**
     * Sets a collection of MagazineRelatedByPublicationdateId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $magazinesRelatedByPublicationdateId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setMagazinesRelatedByPublicationdateId(PropelCollection $magazinesRelatedByPublicationdateId, PropelPDO $con = null)
    {
        $magazinesRelatedByPublicationdateIdToDelete = $this->getMagazinesRelatedByPublicationdateId(new Criteria(), $con)->diff($magazinesRelatedByPublicationdateId);

        $this->magazinesRelatedByPublicationdateIdScheduledForDeletion = unserialize(serialize($magazinesRelatedByPublicationdateIdToDelete));

        foreach ($magazinesRelatedByPublicationdateIdToDelete as $magazineRelatedByPublicationdateIdRemoved) {
            $magazineRelatedByPublicationdateIdRemoved->setDatespecificationRelatedByPublicationdateId(null);
        }

        $this->collMagazinesRelatedByPublicationdateId = null;
        foreach ($magazinesRelatedByPublicationdateId as $magazineRelatedByPublicationdateId) {
            $this->addMagazineRelatedByPublicationdateId($magazineRelatedByPublicationdateId);
        }

        $this->collMagazinesRelatedByPublicationdateId = $magazinesRelatedByPublicationdateId;
        $this->collMagazinesRelatedByPublicationdateIdPartial = false;

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
    public function countMagazinesRelatedByPublicationdateId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collMagazinesRelatedByPublicationdateIdPartial && !$this->isNew();
        if (null === $this->collMagazinesRelatedByPublicationdateId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMagazinesRelatedByPublicationdateId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getMagazinesRelatedByPublicationdateId());
            }
            $query = MagazineQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByPublicationdateId($this)
                ->count($con);
        }

        return count($this->collMagazinesRelatedByPublicationdateId);
    }

    /**
     * Method called to associate a Magazine object to this object
     * through the Magazine foreign key attribute.
     *
     * @param    Magazine $l Magazine
     * @return Datespecification The current object (for fluent API support)
     */
    public function addMagazineRelatedByPublicationdateId(Magazine $l)
    {
        if ($this->collMagazinesRelatedByPublicationdateId === null) {
            $this->initMagazinesRelatedByPublicationdateId();
            $this->collMagazinesRelatedByPublicationdateIdPartial = true;
        }
        if (!in_array($l, $this->collMagazinesRelatedByPublicationdateId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMagazineRelatedByPublicationdateId($l);
        }

        return $this;
    }

    /**
     * @param	MagazineRelatedByPublicationdateId $magazineRelatedByPublicationdateId The magazineRelatedByPublicationdateId object to add.
     */
    protected function doAddMagazineRelatedByPublicationdateId($magazineRelatedByPublicationdateId)
    {
        $this->collMagazinesRelatedByPublicationdateId[]= $magazineRelatedByPublicationdateId;
        $magazineRelatedByPublicationdateId->setDatespecificationRelatedByPublicationdateId($this);
    }

    /**
     * @param	MagazineRelatedByPublicationdateId $magazineRelatedByPublicationdateId The magazineRelatedByPublicationdateId object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removeMagazineRelatedByPublicationdateId($magazineRelatedByPublicationdateId)
    {
        if ($this->getMagazinesRelatedByPublicationdateId()->contains($magazineRelatedByPublicationdateId)) {
            $this->collMagazinesRelatedByPublicationdateId->remove($this->collMagazinesRelatedByPublicationdateId->search($magazineRelatedByPublicationdateId));
            if (null === $this->magazinesRelatedByPublicationdateIdScheduledForDeletion) {
                $this->magazinesRelatedByPublicationdateIdScheduledForDeletion = clone $this->collMagazinesRelatedByPublicationdateId;
                $this->magazinesRelatedByPublicationdateIdScheduledForDeletion->clear();
            }
            $this->magazinesRelatedByPublicationdateIdScheduledForDeletion[]= $magazineRelatedByPublicationdateId;
            $magazineRelatedByPublicationdateId->setDatespecificationRelatedByPublicationdateId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByPublicationdateIdJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getMagazinesRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByPublicationdateIdJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getMagazinesRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByPublicationdateIdJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getMagazinesRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByPublicationdateIdJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getMagazinesRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByPublicationdateIdJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getMagazinesRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByPublicationdateIdJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getMagazinesRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByPublicationdateIdJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getMagazinesRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByPublicationdateIdJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getMagazinesRelatedByPublicationdateId($query, $con);
    }

    /**
     * Clears out the collMagazinesRelatedByOrigindateId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addMagazinesRelatedByOrigindateId()
     */
    public function clearMagazinesRelatedByOrigindateId()
    {
        $this->collMagazinesRelatedByOrigindateId = null; // important to set this to null since that means it is uninitialized
        $this->collMagazinesRelatedByOrigindateIdPartial = null;

        return $this;
    }

    /**
     * reset is the collMagazinesRelatedByOrigindateId collection loaded partially
     *
     * @return void
     */
    public function resetPartialMagazinesRelatedByOrigindateId($v = true)
    {
        $this->collMagazinesRelatedByOrigindateIdPartial = $v;
    }

    /**
     * Initializes the collMagazinesRelatedByOrigindateId collection.
     *
     * By default this just sets the collMagazinesRelatedByOrigindateId collection to an empty array (like clearcollMagazinesRelatedByOrigindateId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMagazinesRelatedByOrigindateId($overrideExisting = true)
    {
        if (null !== $this->collMagazinesRelatedByOrigindateId && !$overrideExisting) {
            return;
        }
        $this->collMagazinesRelatedByOrigindateId = new PropelObjectCollection();
        $this->collMagazinesRelatedByOrigindateId->setModel('Magazine');
    }

    /**
     * Gets an array of Magazine objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Datespecification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     * @throws PropelException
     */
    public function getMagazinesRelatedByOrigindateId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMagazinesRelatedByOrigindateIdPartial && !$this->isNew();
        if (null === $this->collMagazinesRelatedByOrigindateId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMagazinesRelatedByOrigindateId) {
                // return empty collection
                $this->initMagazinesRelatedByOrigindateId();
            } else {
                $collMagazinesRelatedByOrigindateId = MagazineQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByOrigindateId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMagazinesRelatedByOrigindateIdPartial && count($collMagazinesRelatedByOrigindateId)) {
                      $this->initMagazinesRelatedByOrigindateId(false);

                      foreach($collMagazinesRelatedByOrigindateId as $obj) {
                        if (false == $this->collMagazinesRelatedByOrigindateId->contains($obj)) {
                          $this->collMagazinesRelatedByOrigindateId->append($obj);
                        }
                      }

                      $this->collMagazinesRelatedByOrigindateIdPartial = true;
                    }

                    $collMagazinesRelatedByOrigindateId->getInternalIterator()->rewind();
                    return $collMagazinesRelatedByOrigindateId;
                }

                if($partial && $this->collMagazinesRelatedByOrigindateId) {
                    foreach($this->collMagazinesRelatedByOrigindateId as $obj) {
                        if($obj->isNew()) {
                            $collMagazinesRelatedByOrigindateId[] = $obj;
                        }
                    }
                }

                $this->collMagazinesRelatedByOrigindateId = $collMagazinesRelatedByOrigindateId;
                $this->collMagazinesRelatedByOrigindateIdPartial = false;
            }
        }

        return $this->collMagazinesRelatedByOrigindateId;
    }

    /**
     * Sets a collection of MagazineRelatedByOrigindateId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $magazinesRelatedByOrigindateId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setMagazinesRelatedByOrigindateId(PropelCollection $magazinesRelatedByOrigindateId, PropelPDO $con = null)
    {
        $magazinesRelatedByOrigindateIdToDelete = $this->getMagazinesRelatedByOrigindateId(new Criteria(), $con)->diff($magazinesRelatedByOrigindateId);

        $this->magazinesRelatedByOrigindateIdScheduledForDeletion = unserialize(serialize($magazinesRelatedByOrigindateIdToDelete));

        foreach ($magazinesRelatedByOrigindateIdToDelete as $magazineRelatedByOrigindateIdRemoved) {
            $magazineRelatedByOrigindateIdRemoved->setDatespecificationRelatedByOrigindateId(null);
        }

        $this->collMagazinesRelatedByOrigindateId = null;
        foreach ($magazinesRelatedByOrigindateId as $magazineRelatedByOrigindateId) {
            $this->addMagazineRelatedByOrigindateId($magazineRelatedByOrigindateId);
        }

        $this->collMagazinesRelatedByOrigindateId = $magazinesRelatedByOrigindateId;
        $this->collMagazinesRelatedByOrigindateIdPartial = false;

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
    public function countMagazinesRelatedByOrigindateId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collMagazinesRelatedByOrigindateIdPartial && !$this->isNew();
        if (null === $this->collMagazinesRelatedByOrigindateId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMagazinesRelatedByOrigindateId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getMagazinesRelatedByOrigindateId());
            }
            $query = MagazineQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByOrigindateId($this)
                ->count($con);
        }

        return count($this->collMagazinesRelatedByOrigindateId);
    }

    /**
     * Method called to associate a Magazine object to this object
     * through the Magazine foreign key attribute.
     *
     * @param    Magazine $l Magazine
     * @return Datespecification The current object (for fluent API support)
     */
    public function addMagazineRelatedByOrigindateId(Magazine $l)
    {
        if ($this->collMagazinesRelatedByOrigindateId === null) {
            $this->initMagazinesRelatedByOrigindateId();
            $this->collMagazinesRelatedByOrigindateIdPartial = true;
        }
        if (!in_array($l, $this->collMagazinesRelatedByOrigindateId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMagazineRelatedByOrigindateId($l);
        }

        return $this;
    }

    /**
     * @param	MagazineRelatedByOrigindateId $magazineRelatedByOrigindateId The magazineRelatedByOrigindateId object to add.
     */
    protected function doAddMagazineRelatedByOrigindateId($magazineRelatedByOrigindateId)
    {
        $this->collMagazinesRelatedByOrigindateId[]= $magazineRelatedByOrigindateId;
        $magazineRelatedByOrigindateId->setDatespecificationRelatedByOrigindateId($this);
    }

    /**
     * @param	MagazineRelatedByOrigindateId $magazineRelatedByOrigindateId The magazineRelatedByOrigindateId object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removeMagazineRelatedByOrigindateId($magazineRelatedByOrigindateId)
    {
        if ($this->getMagazinesRelatedByOrigindateId()->contains($magazineRelatedByOrigindateId)) {
            $this->collMagazinesRelatedByOrigindateId->remove($this->collMagazinesRelatedByOrigindateId->search($magazineRelatedByOrigindateId));
            if (null === $this->magazinesRelatedByOrigindateIdScheduledForDeletion) {
                $this->magazinesRelatedByOrigindateIdScheduledForDeletion = clone $this->collMagazinesRelatedByOrigindateId;
                $this->magazinesRelatedByOrigindateIdScheduledForDeletion->clear();
            }
            $this->magazinesRelatedByOrigindateIdScheduledForDeletion[]= $magazineRelatedByOrigindateId;
            $magazineRelatedByOrigindateId->setDatespecificationRelatedByOrigindateId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByOrigindateIdJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getMagazinesRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByOrigindateIdJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getMagazinesRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByOrigindateIdJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getMagazinesRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByOrigindateIdJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getMagazinesRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByOrigindateIdJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getMagazinesRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByOrigindateIdJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getMagazinesRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByOrigindateIdJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getMagazinesRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesRelatedByOrigindateIdJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getMagazinesRelatedByOrigindateId($query, $con);
    }

    /**
     * Clears out the collSeriesRelatedByPublicationdateId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addSeriesRelatedByPublicationdateId()
     */
    public function clearSeriesRelatedByPublicationdateId()
    {
        $this->collSeriesRelatedByPublicationdateId = null; // important to set this to null since that means it is uninitialized
        $this->collSeriesRelatedByPublicationdateIdPartial = null;

        return $this;
    }

    /**
     * reset is the collSeriesRelatedByPublicationdateId collection loaded partially
     *
     * @return void
     */
    public function resetPartialSeriesRelatedByPublicationdateId($v = true)
    {
        $this->collSeriesRelatedByPublicationdateIdPartial = $v;
    }

    /**
     * Initializes the collSeriesRelatedByPublicationdateId collection.
     *
     * By default this just sets the collSeriesRelatedByPublicationdateId collection to an empty array (like clearcollSeriesRelatedByPublicationdateId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSeriesRelatedByPublicationdateId($overrideExisting = true)
    {
        if (null !== $this->collSeriesRelatedByPublicationdateId && !$overrideExisting) {
            return;
        }
        $this->collSeriesRelatedByPublicationdateId = new PropelObjectCollection();
        $this->collSeriesRelatedByPublicationdateId->setModel('Series');
    }

    /**
     * Gets an array of Series objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Datespecification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Series[] List of Series objects
     * @throws PropelException
     */
    public function getSeriesRelatedByPublicationdateId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSeriesRelatedByPublicationdateIdPartial && !$this->isNew();
        if (null === $this->collSeriesRelatedByPublicationdateId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSeriesRelatedByPublicationdateId) {
                // return empty collection
                $this->initSeriesRelatedByPublicationdateId();
            } else {
                $collSeriesRelatedByPublicationdateId = SeriesQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByPublicationdateId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSeriesRelatedByPublicationdateIdPartial && count($collSeriesRelatedByPublicationdateId)) {
                      $this->initSeriesRelatedByPublicationdateId(false);

                      foreach($collSeriesRelatedByPublicationdateId as $obj) {
                        if (false == $this->collSeriesRelatedByPublicationdateId->contains($obj)) {
                          $this->collSeriesRelatedByPublicationdateId->append($obj);
                        }
                      }

                      $this->collSeriesRelatedByPublicationdateIdPartial = true;
                    }

                    $collSeriesRelatedByPublicationdateId->getInternalIterator()->rewind();
                    return $collSeriesRelatedByPublicationdateId;
                }

                if($partial && $this->collSeriesRelatedByPublicationdateId) {
                    foreach($this->collSeriesRelatedByPublicationdateId as $obj) {
                        if($obj->isNew()) {
                            $collSeriesRelatedByPublicationdateId[] = $obj;
                        }
                    }
                }

                $this->collSeriesRelatedByPublicationdateId = $collSeriesRelatedByPublicationdateId;
                $this->collSeriesRelatedByPublicationdateIdPartial = false;
            }
        }

        return $this->collSeriesRelatedByPublicationdateId;
    }

    /**
     * Sets a collection of SeriesRelatedByPublicationdateId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $seriesRelatedByPublicationdateId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setSeriesRelatedByPublicationdateId(PropelCollection $seriesRelatedByPublicationdateId, PropelPDO $con = null)
    {
        $seriesRelatedByPublicationdateIdToDelete = $this->getSeriesRelatedByPublicationdateId(new Criteria(), $con)->diff($seriesRelatedByPublicationdateId);

        $this->seriesRelatedByPublicationdateIdScheduledForDeletion = unserialize(serialize($seriesRelatedByPublicationdateIdToDelete));

        foreach ($seriesRelatedByPublicationdateIdToDelete as $seriesRelatedByPublicationdateIdRemoved) {
            $seriesRelatedByPublicationdateIdRemoved->setDatespecificationRelatedByPublicationdateId(null);
        }

        $this->collSeriesRelatedByPublicationdateId = null;
        foreach ($seriesRelatedByPublicationdateId as $seriesRelatedByPublicationdateId) {
            $this->addSeriesRelatedByPublicationdateId($seriesRelatedByPublicationdateId);
        }

        $this->collSeriesRelatedByPublicationdateId = $seriesRelatedByPublicationdateId;
        $this->collSeriesRelatedByPublicationdateIdPartial = false;

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
    public function countSeriesRelatedByPublicationdateId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSeriesRelatedByPublicationdateIdPartial && !$this->isNew();
        if (null === $this->collSeriesRelatedByPublicationdateId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSeriesRelatedByPublicationdateId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getSeriesRelatedByPublicationdateId());
            }
            $query = SeriesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByPublicationdateId($this)
                ->count($con);
        }

        return count($this->collSeriesRelatedByPublicationdateId);
    }

    /**
     * Method called to associate a Series object to this object
     * through the Series foreign key attribute.
     *
     * @param    Series $l Series
     * @return Datespecification The current object (for fluent API support)
     */
    public function addSeriesRelatedByPublicationdateId(Series $l)
    {
        if ($this->collSeriesRelatedByPublicationdateId === null) {
            $this->initSeriesRelatedByPublicationdateId();
            $this->collSeriesRelatedByPublicationdateIdPartial = true;
        }
        if (!in_array($l, $this->collSeriesRelatedByPublicationdateId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSeriesRelatedByPublicationdateId($l);
        }

        return $this;
    }

    /**
     * @param	SeriesRelatedByPublicationdateId $seriesRelatedByPublicationdateId The seriesRelatedByPublicationdateId object to add.
     */
    protected function doAddSeriesRelatedByPublicationdateId($seriesRelatedByPublicationdateId)
    {
        $this->collSeriesRelatedByPublicationdateId[]= $seriesRelatedByPublicationdateId;
        $seriesRelatedByPublicationdateId->setDatespecificationRelatedByPublicationdateId($this);
    }

    /**
     * @param	SeriesRelatedByPublicationdateId $seriesRelatedByPublicationdateId The seriesRelatedByPublicationdateId object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removeSeriesRelatedByPublicationdateId($seriesRelatedByPublicationdateId)
    {
        if ($this->getSeriesRelatedByPublicationdateId()->contains($seriesRelatedByPublicationdateId)) {
            $this->collSeriesRelatedByPublicationdateId->remove($this->collSeriesRelatedByPublicationdateId->search($seriesRelatedByPublicationdateId));
            if (null === $this->seriesRelatedByPublicationdateIdScheduledForDeletion) {
                $this->seriesRelatedByPublicationdateIdScheduledForDeletion = clone $this->collSeriesRelatedByPublicationdateId;
                $this->seriesRelatedByPublicationdateIdScheduledForDeletion->clear();
            }
            $this->seriesRelatedByPublicationdateIdScheduledForDeletion[]= $seriesRelatedByPublicationdateId;
            $seriesRelatedByPublicationdateId->setDatespecificationRelatedByPublicationdateId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByPublicationdateIdJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getSeriesRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByPublicationdateIdJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getSeriesRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByPublicationdateIdJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getSeriesRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByPublicationdateIdJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getSeriesRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByPublicationdateIdJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getSeriesRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByPublicationdateIdJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getSeriesRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByPublicationdateIdJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getSeriesRelatedByPublicationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationdateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByPublicationdateIdJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getSeriesRelatedByPublicationdateId($query, $con);
    }

    /**
     * Clears out the collSeriesRelatedByOrigindateId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addSeriesRelatedByOrigindateId()
     */
    public function clearSeriesRelatedByOrigindateId()
    {
        $this->collSeriesRelatedByOrigindateId = null; // important to set this to null since that means it is uninitialized
        $this->collSeriesRelatedByOrigindateIdPartial = null;

        return $this;
    }

    /**
     * reset is the collSeriesRelatedByOrigindateId collection loaded partially
     *
     * @return void
     */
    public function resetPartialSeriesRelatedByOrigindateId($v = true)
    {
        $this->collSeriesRelatedByOrigindateIdPartial = $v;
    }

    /**
     * Initializes the collSeriesRelatedByOrigindateId collection.
     *
     * By default this just sets the collSeriesRelatedByOrigindateId collection to an empty array (like clearcollSeriesRelatedByOrigindateId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSeriesRelatedByOrigindateId($overrideExisting = true)
    {
        if (null !== $this->collSeriesRelatedByOrigindateId && !$overrideExisting) {
            return;
        }
        $this->collSeriesRelatedByOrigindateId = new PropelObjectCollection();
        $this->collSeriesRelatedByOrigindateId->setModel('Series');
    }

    /**
     * Gets an array of Series objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Datespecification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Series[] List of Series objects
     * @throws PropelException
     */
    public function getSeriesRelatedByOrigindateId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSeriesRelatedByOrigindateIdPartial && !$this->isNew();
        if (null === $this->collSeriesRelatedByOrigindateId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSeriesRelatedByOrigindateId) {
                // return empty collection
                $this->initSeriesRelatedByOrigindateId();
            } else {
                $collSeriesRelatedByOrigindateId = SeriesQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByOrigindateId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSeriesRelatedByOrigindateIdPartial && count($collSeriesRelatedByOrigindateId)) {
                      $this->initSeriesRelatedByOrigindateId(false);

                      foreach($collSeriesRelatedByOrigindateId as $obj) {
                        if (false == $this->collSeriesRelatedByOrigindateId->contains($obj)) {
                          $this->collSeriesRelatedByOrigindateId->append($obj);
                        }
                      }

                      $this->collSeriesRelatedByOrigindateIdPartial = true;
                    }

                    $collSeriesRelatedByOrigindateId->getInternalIterator()->rewind();
                    return $collSeriesRelatedByOrigindateId;
                }

                if($partial && $this->collSeriesRelatedByOrigindateId) {
                    foreach($this->collSeriesRelatedByOrigindateId as $obj) {
                        if($obj->isNew()) {
                            $collSeriesRelatedByOrigindateId[] = $obj;
                        }
                    }
                }

                $this->collSeriesRelatedByOrigindateId = $collSeriesRelatedByOrigindateId;
                $this->collSeriesRelatedByOrigindateIdPartial = false;
            }
        }

        return $this->collSeriesRelatedByOrigindateId;
    }

    /**
     * Sets a collection of SeriesRelatedByOrigindateId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $seriesRelatedByOrigindateId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setSeriesRelatedByOrigindateId(PropelCollection $seriesRelatedByOrigindateId, PropelPDO $con = null)
    {
        $seriesRelatedByOrigindateIdToDelete = $this->getSeriesRelatedByOrigindateId(new Criteria(), $con)->diff($seriesRelatedByOrigindateId);

        $this->seriesRelatedByOrigindateIdScheduledForDeletion = unserialize(serialize($seriesRelatedByOrigindateIdToDelete));

        foreach ($seriesRelatedByOrigindateIdToDelete as $seriesRelatedByOrigindateIdRemoved) {
            $seriesRelatedByOrigindateIdRemoved->setDatespecificationRelatedByOrigindateId(null);
        }

        $this->collSeriesRelatedByOrigindateId = null;
        foreach ($seriesRelatedByOrigindateId as $seriesRelatedByOrigindateId) {
            $this->addSeriesRelatedByOrigindateId($seriesRelatedByOrigindateId);
        }

        $this->collSeriesRelatedByOrigindateId = $seriesRelatedByOrigindateId;
        $this->collSeriesRelatedByOrigindateIdPartial = false;

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
    public function countSeriesRelatedByOrigindateId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSeriesRelatedByOrigindateIdPartial && !$this->isNew();
        if (null === $this->collSeriesRelatedByOrigindateId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSeriesRelatedByOrigindateId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getSeriesRelatedByOrigindateId());
            }
            $query = SeriesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByOrigindateId($this)
                ->count($con);
        }

        return count($this->collSeriesRelatedByOrigindateId);
    }

    /**
     * Method called to associate a Series object to this object
     * through the Series foreign key attribute.
     *
     * @param    Series $l Series
     * @return Datespecification The current object (for fluent API support)
     */
    public function addSeriesRelatedByOrigindateId(Series $l)
    {
        if ($this->collSeriesRelatedByOrigindateId === null) {
            $this->initSeriesRelatedByOrigindateId();
            $this->collSeriesRelatedByOrigindateIdPartial = true;
        }
        if (!in_array($l, $this->collSeriesRelatedByOrigindateId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSeriesRelatedByOrigindateId($l);
        }

        return $this;
    }

    /**
     * @param	SeriesRelatedByOrigindateId $seriesRelatedByOrigindateId The seriesRelatedByOrigindateId object to add.
     */
    protected function doAddSeriesRelatedByOrigindateId($seriesRelatedByOrigindateId)
    {
        $this->collSeriesRelatedByOrigindateId[]= $seriesRelatedByOrigindateId;
        $seriesRelatedByOrigindateId->setDatespecificationRelatedByOrigindateId($this);
    }

    /**
     * @param	SeriesRelatedByOrigindateId $seriesRelatedByOrigindateId The seriesRelatedByOrigindateId object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removeSeriesRelatedByOrigindateId($seriesRelatedByOrigindateId)
    {
        if ($this->getSeriesRelatedByOrigindateId()->contains($seriesRelatedByOrigindateId)) {
            $this->collSeriesRelatedByOrigindateId->remove($this->collSeriesRelatedByOrigindateId->search($seriesRelatedByOrigindateId));
            if (null === $this->seriesRelatedByOrigindateIdScheduledForDeletion) {
                $this->seriesRelatedByOrigindateIdScheduledForDeletion = clone $this->collSeriesRelatedByOrigindateId;
                $this->seriesRelatedByOrigindateIdScheduledForDeletion->clear();
            }
            $this->seriesRelatedByOrigindateIdScheduledForDeletion[]= $seriesRelatedByOrigindateId;
            $seriesRelatedByOrigindateId->setDatespecificationRelatedByOrigindateId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByOrigindateIdJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getSeriesRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByOrigindateIdJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getSeriesRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByOrigindateIdJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getSeriesRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByOrigindateIdJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getSeriesRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByOrigindateIdJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getSeriesRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByOrigindateIdJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getSeriesRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByOrigindateIdJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getSeriesRelatedByOrigindateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOrigindateId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Datespecification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesRelatedByOrigindateIdJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getSeriesRelatedByOrigindateId($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->year = null;
        $this->comments = null;
        $this->year_is_reconstructed = null;
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
            if ($this->collPublicationsRelatedByPublicationdateId) {
                foreach ($this->collPublicationsRelatedByPublicationdateId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationsRelatedByOrigindateId) {
                foreach ($this->collPublicationsRelatedByOrigindateId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWorks) {
                foreach ($this->collWorks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEssaysRelatedByPublicationdateId) {
                foreach ($this->collEssaysRelatedByPublicationdateId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEssaysRelatedByOrigindateId) {
                foreach ($this->collEssaysRelatedByOrigindateId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMagazinesRelatedByPublicationdateId) {
                foreach ($this->collMagazinesRelatedByPublicationdateId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMagazinesRelatedByOrigindateId) {
                foreach ($this->collMagazinesRelatedByOrigindateId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSeriesRelatedByPublicationdateId) {
                foreach ($this->collSeriesRelatedByPublicationdateId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSeriesRelatedByOrigindateId) {
                foreach ($this->collSeriesRelatedByOrigindateId as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPublicationsRelatedByPublicationdateId instanceof PropelCollection) {
            $this->collPublicationsRelatedByPublicationdateId->clearIterator();
        }
        $this->collPublicationsRelatedByPublicationdateId = null;
        if ($this->collPublicationsRelatedByOrigindateId instanceof PropelCollection) {
            $this->collPublicationsRelatedByOrigindateId->clearIterator();
        }
        $this->collPublicationsRelatedByOrigindateId = null;
        if ($this->collWorks instanceof PropelCollection) {
            $this->collWorks->clearIterator();
        }
        $this->collWorks = null;
        if ($this->collEssaysRelatedByPublicationdateId instanceof PropelCollection) {
            $this->collEssaysRelatedByPublicationdateId->clearIterator();
        }
        $this->collEssaysRelatedByPublicationdateId = null;
        if ($this->collEssaysRelatedByOrigindateId instanceof PropelCollection) {
            $this->collEssaysRelatedByOrigindateId->clearIterator();
        }
        $this->collEssaysRelatedByOrigindateId = null;
        if ($this->collMagazinesRelatedByPublicationdateId instanceof PropelCollection) {
            $this->collMagazinesRelatedByPublicationdateId->clearIterator();
        }
        $this->collMagazinesRelatedByPublicationdateId = null;
        if ($this->collMagazinesRelatedByOrigindateId instanceof PropelCollection) {
            $this->collMagazinesRelatedByOrigindateId->clearIterator();
        }
        $this->collMagazinesRelatedByOrigindateId = null;
        if ($this->collSeriesRelatedByPublicationdateId instanceof PropelCollection) {
            $this->collSeriesRelatedByPublicationdateId->clearIterator();
        }
        $this->collSeriesRelatedByPublicationdateId = null;
        if ($this->collSeriesRelatedByOrigindateId instanceof PropelCollection) {
            $this->collSeriesRelatedByOrigindateId->clearIterator();
        }
        $this->collSeriesRelatedByOrigindateId = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(DatespecificationPeer::DEFAULT_STRING_FORMAT);
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

    // reconstructed_flaggable behavior
    /**
    * Returns all columns that can be flagged as reconstructed.
    */
    public function getReconstructedFlaggableColumns(){
        return array('Year', );
    }

    /**
    * Returns whether a column is flagged as reconstructed.
    * @param $column the PHP name of the column as defined in the schema
    */
    public function isReconstructedByName($column){
        return $this->getByName($column."IsReconstructed");
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
