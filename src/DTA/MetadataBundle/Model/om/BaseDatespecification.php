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
    protected $collPublicationsRelatedByPublicationDate;
    protected $collPublicationsRelatedByPublicationDatePartial;

    /**
     * @var        PropelObjectCollection|Publication[] Collection to store aggregation of Publication objects.
     */
    protected $collPublicationsRelatedByOriginDate;
    protected $collPublicationsRelatedByOriginDatePartial;

    /**
     * @var        PropelObjectCollection|Work[] Collection to store aggregation of Work objects.
     */
    protected $collWorks;
    protected $collWorksPartial;

    /**
     * @var        PropelObjectCollection|Essay[] Collection to store aggregation of Essay objects.
     */
    protected $collEssaysRelatedByPublicationDate;
    protected $collEssaysRelatedByPublicationDatePartial;

    /**
     * @var        PropelObjectCollection|Essay[] Collection to store aggregation of Essay objects.
     */
    protected $collEssaysRelatedByOriginDate;
    protected $collEssaysRelatedByOriginDatePartial;

    /**
     * @var        PropelObjectCollection|Magazine[] Collection to store aggregation of Magazine objects.
     */
    protected $collMagazinesRelatedByPublicationDate;
    protected $collMagazinesRelatedByPublicationDatePartial;

    /**
     * @var        PropelObjectCollection|Magazine[] Collection to store aggregation of Magazine objects.
     */
    protected $collMagazinesRelatedByOriginDate;
    protected $collMagazinesRelatedByOriginDatePartial;

    /**
     * @var        PropelObjectCollection|Series[] Collection to store aggregation of Series objects.
     */
    protected $collSeriesRelatedByPublicationDate;
    protected $collSeriesRelatedByPublicationDatePartial;

    /**
     * @var        PropelObjectCollection|Series[] Collection to store aggregation of Series objects.
     */
    protected $collSeriesRelatedByOriginDate;
    protected $collSeriesRelatedByOriginDatePartial;

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
    protected $publicationsRelatedByPublicationDateScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationsRelatedByOriginDateScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $worksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $essaysRelatedByPublicationDateScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $essaysRelatedByOriginDateScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $magazinesRelatedByPublicationDateScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $magazinesRelatedByOriginDateScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $seriesRelatedByPublicationDateScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $seriesRelatedByOriginDateScheduledForDeletion = null;

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

            $this->collPublicationsRelatedByPublicationDate = null;

            $this->collPublicationsRelatedByOriginDate = null;

            $this->collWorks = null;

            $this->collEssaysRelatedByPublicationDate = null;

            $this->collEssaysRelatedByOriginDate = null;

            $this->collMagazinesRelatedByPublicationDate = null;

            $this->collMagazinesRelatedByOriginDate = null;

            $this->collSeriesRelatedByPublicationDate = null;

            $this->collSeriesRelatedByOriginDate = null;

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

            if ($this->publicationsRelatedByPublicationDateScheduledForDeletion !== null) {
                if (!$this->publicationsRelatedByPublicationDateScheduledForDeletion->isEmpty()) {
                    foreach ($this->publicationsRelatedByPublicationDateScheduledForDeletion as $publicationRelatedByPublicationDate) {
                        // need to save related object because we set the relation to null
                        $publicationRelatedByPublicationDate->save($con);
                    }
                    $this->publicationsRelatedByPublicationDateScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationsRelatedByPublicationDate !== null) {
                foreach ($this->collPublicationsRelatedByPublicationDate as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->publicationsRelatedByOriginDateScheduledForDeletion !== null) {
                if (!$this->publicationsRelatedByOriginDateScheduledForDeletion->isEmpty()) {
                    foreach ($this->publicationsRelatedByOriginDateScheduledForDeletion as $publicationRelatedByOriginDate) {
                        // need to save related object because we set the relation to null
                        $publicationRelatedByOriginDate->save($con);
                    }
                    $this->publicationsRelatedByOriginDateScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationsRelatedByOriginDate !== null) {
                foreach ($this->collPublicationsRelatedByOriginDate as $referrerFK) {
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

            if ($this->essaysRelatedByPublicationDateScheduledForDeletion !== null) {
                if (!$this->essaysRelatedByPublicationDateScheduledForDeletion->isEmpty()) {
                    foreach ($this->essaysRelatedByPublicationDateScheduledForDeletion as $essayRelatedByPublicationDate) {
                        // need to save related object because we set the relation to null
                        $essayRelatedByPublicationDate->save($con);
                    }
                    $this->essaysRelatedByPublicationDateScheduledForDeletion = null;
                }
            }

            if ($this->collEssaysRelatedByPublicationDate !== null) {
                foreach ($this->collEssaysRelatedByPublicationDate as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->essaysRelatedByOriginDateScheduledForDeletion !== null) {
                if (!$this->essaysRelatedByOriginDateScheduledForDeletion->isEmpty()) {
                    foreach ($this->essaysRelatedByOriginDateScheduledForDeletion as $essayRelatedByOriginDate) {
                        // need to save related object because we set the relation to null
                        $essayRelatedByOriginDate->save($con);
                    }
                    $this->essaysRelatedByOriginDateScheduledForDeletion = null;
                }
            }

            if ($this->collEssaysRelatedByOriginDate !== null) {
                foreach ($this->collEssaysRelatedByOriginDate as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->magazinesRelatedByPublicationDateScheduledForDeletion !== null) {
                if (!$this->magazinesRelatedByPublicationDateScheduledForDeletion->isEmpty()) {
                    foreach ($this->magazinesRelatedByPublicationDateScheduledForDeletion as $magazineRelatedByPublicationDate) {
                        // need to save related object because we set the relation to null
                        $magazineRelatedByPublicationDate->save($con);
                    }
                    $this->magazinesRelatedByPublicationDateScheduledForDeletion = null;
                }
            }

            if ($this->collMagazinesRelatedByPublicationDate !== null) {
                foreach ($this->collMagazinesRelatedByPublicationDate as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->magazinesRelatedByOriginDateScheduledForDeletion !== null) {
                if (!$this->magazinesRelatedByOriginDateScheduledForDeletion->isEmpty()) {
                    foreach ($this->magazinesRelatedByOriginDateScheduledForDeletion as $magazineRelatedByOriginDate) {
                        // need to save related object because we set the relation to null
                        $magazineRelatedByOriginDate->save($con);
                    }
                    $this->magazinesRelatedByOriginDateScheduledForDeletion = null;
                }
            }

            if ($this->collMagazinesRelatedByOriginDate !== null) {
                foreach ($this->collMagazinesRelatedByOriginDate as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->seriesRelatedByPublicationDateScheduledForDeletion !== null) {
                if (!$this->seriesRelatedByPublicationDateScheduledForDeletion->isEmpty()) {
                    foreach ($this->seriesRelatedByPublicationDateScheduledForDeletion as $seriesRelatedByPublicationDate) {
                        // need to save related object because we set the relation to null
                        $seriesRelatedByPublicationDate->save($con);
                    }
                    $this->seriesRelatedByPublicationDateScheduledForDeletion = null;
                }
            }

            if ($this->collSeriesRelatedByPublicationDate !== null) {
                foreach ($this->collSeriesRelatedByPublicationDate as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->seriesRelatedByOriginDateScheduledForDeletion !== null) {
                if (!$this->seriesRelatedByOriginDateScheduledForDeletion->isEmpty()) {
                    foreach ($this->seriesRelatedByOriginDateScheduledForDeletion as $seriesRelatedByOriginDate) {
                        // need to save related object because we set the relation to null
                        $seriesRelatedByOriginDate->save($con);
                    }
                    $this->seriesRelatedByOriginDateScheduledForDeletion = null;
                }
            }

            if ($this->collSeriesRelatedByOriginDate !== null) {
                foreach ($this->collSeriesRelatedByOriginDate as $referrerFK) {
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


                if ($this->collPublicationsRelatedByPublicationDate !== null) {
                    foreach ($this->collPublicationsRelatedByPublicationDate as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublicationsRelatedByOriginDate !== null) {
                    foreach ($this->collPublicationsRelatedByOriginDate as $referrerFK) {
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

                if ($this->collEssaysRelatedByPublicationDate !== null) {
                    foreach ($this->collEssaysRelatedByPublicationDate as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEssaysRelatedByOriginDate !== null) {
                    foreach ($this->collEssaysRelatedByOriginDate as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collMagazinesRelatedByPublicationDate !== null) {
                    foreach ($this->collMagazinesRelatedByPublicationDate as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collMagazinesRelatedByOriginDate !== null) {
                    foreach ($this->collMagazinesRelatedByOriginDate as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSeriesRelatedByPublicationDate !== null) {
                    foreach ($this->collSeriesRelatedByPublicationDate as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSeriesRelatedByOriginDate !== null) {
                    foreach ($this->collSeriesRelatedByOriginDate as $referrerFK) {
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
            if (null !== $this->collPublicationsRelatedByPublicationDate) {
                $result['PublicationsRelatedByPublicationDate'] = $this->collPublicationsRelatedByPublicationDate->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationsRelatedByOriginDate) {
                $result['PublicationsRelatedByOriginDate'] = $this->collPublicationsRelatedByOriginDate->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collWorks) {
                $result['Works'] = $this->collWorks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEssaysRelatedByPublicationDate) {
                $result['EssaysRelatedByPublicationDate'] = $this->collEssaysRelatedByPublicationDate->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEssaysRelatedByOriginDate) {
                $result['EssaysRelatedByOriginDate'] = $this->collEssaysRelatedByOriginDate->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMagazinesRelatedByPublicationDate) {
                $result['MagazinesRelatedByPublicationDate'] = $this->collMagazinesRelatedByPublicationDate->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMagazinesRelatedByOriginDate) {
                $result['MagazinesRelatedByOriginDate'] = $this->collMagazinesRelatedByOriginDate->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSeriesRelatedByPublicationDate) {
                $result['SeriesRelatedByPublicationDate'] = $this->collSeriesRelatedByPublicationDate->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSeriesRelatedByOriginDate) {
                $result['SeriesRelatedByOriginDate'] = $this->collSeriesRelatedByOriginDate->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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

            foreach ($this->getPublicationsRelatedByPublicationDate() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationRelatedByPublicationDate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationsRelatedByOriginDate() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationRelatedByOriginDate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getWorks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addWork($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEssaysRelatedByPublicationDate() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEssayRelatedByPublicationDate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEssaysRelatedByOriginDate() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEssayRelatedByOriginDate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMagazinesRelatedByPublicationDate() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMagazineRelatedByPublicationDate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMagazinesRelatedByOriginDate() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMagazineRelatedByOriginDate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSeriesRelatedByPublicationDate() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSeriesRelatedByPublicationDate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSeriesRelatedByOriginDate() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSeriesRelatedByOriginDate($relObj->copy($deepCopy));
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
        if ('PublicationRelatedByPublicationDate' == $relationName) {
            $this->initPublicationsRelatedByPublicationDate();
        }
        if ('PublicationRelatedByOriginDate' == $relationName) {
            $this->initPublicationsRelatedByOriginDate();
        }
        if ('Work' == $relationName) {
            $this->initWorks();
        }
        if ('EssayRelatedByPublicationDate' == $relationName) {
            $this->initEssaysRelatedByPublicationDate();
        }
        if ('EssayRelatedByOriginDate' == $relationName) {
            $this->initEssaysRelatedByOriginDate();
        }
        if ('MagazineRelatedByPublicationDate' == $relationName) {
            $this->initMagazinesRelatedByPublicationDate();
        }
        if ('MagazineRelatedByOriginDate' == $relationName) {
            $this->initMagazinesRelatedByOriginDate();
        }
        if ('SeriesRelatedByPublicationDate' == $relationName) {
            $this->initSeriesRelatedByPublicationDate();
        }
        if ('SeriesRelatedByOriginDate' == $relationName) {
            $this->initSeriesRelatedByOriginDate();
        }
    }

    /**
     * Clears out the collPublicationsRelatedByPublicationDate collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addPublicationsRelatedByPublicationDate()
     */
    public function clearPublicationsRelatedByPublicationDate()
    {
        $this->collPublicationsRelatedByPublicationDate = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationsRelatedByPublicationDatePartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationsRelatedByPublicationDate collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationsRelatedByPublicationDate($v = true)
    {
        $this->collPublicationsRelatedByPublicationDatePartial = $v;
    }

    /**
     * Initializes the collPublicationsRelatedByPublicationDate collection.
     *
     * By default this just sets the collPublicationsRelatedByPublicationDate collection to an empty array (like clearcollPublicationsRelatedByPublicationDate());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationsRelatedByPublicationDate($overrideExisting = true)
    {
        if (null !== $this->collPublicationsRelatedByPublicationDate && !$overrideExisting) {
            return;
        }
        $this->collPublicationsRelatedByPublicationDate = new PropelObjectCollection();
        $this->collPublicationsRelatedByPublicationDate->setModel('Publication');
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
    public function getPublicationsRelatedByPublicationDate($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationsRelatedByPublicationDatePartial && !$this->isNew();
        if (null === $this->collPublicationsRelatedByPublicationDate || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationsRelatedByPublicationDate) {
                // return empty collection
                $this->initPublicationsRelatedByPublicationDate();
            } else {
                $collPublicationsRelatedByPublicationDate = PublicationQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByPublicationDate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationsRelatedByPublicationDatePartial && count($collPublicationsRelatedByPublicationDate)) {
                      $this->initPublicationsRelatedByPublicationDate(false);

                      foreach($collPublicationsRelatedByPublicationDate as $obj) {
                        if (false == $this->collPublicationsRelatedByPublicationDate->contains($obj)) {
                          $this->collPublicationsRelatedByPublicationDate->append($obj);
                        }
                      }

                      $this->collPublicationsRelatedByPublicationDatePartial = true;
                    }

                    $collPublicationsRelatedByPublicationDate->getInternalIterator()->rewind();
                    return $collPublicationsRelatedByPublicationDate;
                }

                if($partial && $this->collPublicationsRelatedByPublicationDate) {
                    foreach($this->collPublicationsRelatedByPublicationDate as $obj) {
                        if($obj->isNew()) {
                            $collPublicationsRelatedByPublicationDate[] = $obj;
                        }
                    }
                }

                $this->collPublicationsRelatedByPublicationDate = $collPublicationsRelatedByPublicationDate;
                $this->collPublicationsRelatedByPublicationDatePartial = false;
            }
        }

        return $this->collPublicationsRelatedByPublicationDate;
    }

    /**
     * Sets a collection of PublicationRelatedByPublicationDate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationsRelatedByPublicationDate A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setPublicationsRelatedByPublicationDate(PropelCollection $publicationsRelatedByPublicationDate, PropelPDO $con = null)
    {
        $publicationsRelatedByPublicationDateToDelete = $this->getPublicationsRelatedByPublicationDate(new Criteria(), $con)->diff($publicationsRelatedByPublicationDate);

        $this->publicationsRelatedByPublicationDateScheduledForDeletion = unserialize(serialize($publicationsRelatedByPublicationDateToDelete));

        foreach ($publicationsRelatedByPublicationDateToDelete as $publicationRelatedByPublicationDateRemoved) {
            $publicationRelatedByPublicationDateRemoved->setDatespecificationRelatedByPublicationDate(null);
        }

        $this->collPublicationsRelatedByPublicationDate = null;
        foreach ($publicationsRelatedByPublicationDate as $publicationRelatedByPublicationDate) {
            $this->addPublicationRelatedByPublicationDate($publicationRelatedByPublicationDate);
        }

        $this->collPublicationsRelatedByPublicationDate = $publicationsRelatedByPublicationDate;
        $this->collPublicationsRelatedByPublicationDatePartial = false;

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
    public function countPublicationsRelatedByPublicationDate(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationsRelatedByPublicationDatePartial && !$this->isNew();
        if (null === $this->collPublicationsRelatedByPublicationDate || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationsRelatedByPublicationDate) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationsRelatedByPublicationDate());
            }
            $query = PublicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByPublicationDate($this)
                ->count($con);
        }

        return count($this->collPublicationsRelatedByPublicationDate);
    }

    /**
     * Method called to associate a Publication object to this object
     * through the Publication foreign key attribute.
     *
     * @param    Publication $l Publication
     * @return Datespecification The current object (for fluent API support)
     */
    public function addPublicationRelatedByPublicationDate(Publication $l)
    {
        if ($this->collPublicationsRelatedByPublicationDate === null) {
            $this->initPublicationsRelatedByPublicationDate();
            $this->collPublicationsRelatedByPublicationDatePartial = true;
        }
        if (!in_array($l, $this->collPublicationsRelatedByPublicationDate->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationRelatedByPublicationDate($l);
        }

        return $this;
    }

    /**
     * @param	PublicationRelatedByPublicationDate $publicationRelatedByPublicationDate The publicationRelatedByPublicationDate object to add.
     */
    protected function doAddPublicationRelatedByPublicationDate($publicationRelatedByPublicationDate)
    {
        $this->collPublicationsRelatedByPublicationDate[]= $publicationRelatedByPublicationDate;
        $publicationRelatedByPublicationDate->setDatespecificationRelatedByPublicationDate($this);
    }

    /**
     * @param	PublicationRelatedByPublicationDate $publicationRelatedByPublicationDate The publicationRelatedByPublicationDate object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removePublicationRelatedByPublicationDate($publicationRelatedByPublicationDate)
    {
        if ($this->getPublicationsRelatedByPublicationDate()->contains($publicationRelatedByPublicationDate)) {
            $this->collPublicationsRelatedByPublicationDate->remove($this->collPublicationsRelatedByPublicationDate->search($publicationRelatedByPublicationDate));
            if (null === $this->publicationsRelatedByPublicationDateScheduledForDeletion) {
                $this->publicationsRelatedByPublicationDateScheduledForDeletion = clone $this->collPublicationsRelatedByPublicationDate;
                $this->publicationsRelatedByPublicationDateScheduledForDeletion->clear();
            }
            $this->publicationsRelatedByPublicationDateScheduledForDeletion[]= $publicationRelatedByPublicationDate;
            $publicationRelatedByPublicationDate->setDatespecificationRelatedByPublicationDate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationDate from storage.
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
    public function getPublicationsRelatedByPublicationDateJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getPublicationsRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationDate from storage.
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
    public function getPublicationsRelatedByPublicationDateJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getPublicationsRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationDate from storage.
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
    public function getPublicationsRelatedByPublicationDateJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getPublicationsRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationDate from storage.
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
    public function getPublicationsRelatedByPublicationDateJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getPublicationsRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationDate from storage.
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
    public function getPublicationsRelatedByPublicationDateJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getPublicationsRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationDate from storage.
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
    public function getPublicationsRelatedByPublicationDateJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getPublicationsRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationDate from storage.
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
    public function getPublicationsRelatedByPublicationDateJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getPublicationsRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByPublicationDate from storage.
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
    public function getPublicationsRelatedByPublicationDateJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getPublicationsRelatedByPublicationDate($query, $con);
    }

    /**
     * Clears out the collPublicationsRelatedByOriginDate collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addPublicationsRelatedByOriginDate()
     */
    public function clearPublicationsRelatedByOriginDate()
    {
        $this->collPublicationsRelatedByOriginDate = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationsRelatedByOriginDatePartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationsRelatedByOriginDate collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationsRelatedByOriginDate($v = true)
    {
        $this->collPublicationsRelatedByOriginDatePartial = $v;
    }

    /**
     * Initializes the collPublicationsRelatedByOriginDate collection.
     *
     * By default this just sets the collPublicationsRelatedByOriginDate collection to an empty array (like clearcollPublicationsRelatedByOriginDate());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationsRelatedByOriginDate($overrideExisting = true)
    {
        if (null !== $this->collPublicationsRelatedByOriginDate && !$overrideExisting) {
            return;
        }
        $this->collPublicationsRelatedByOriginDate = new PropelObjectCollection();
        $this->collPublicationsRelatedByOriginDate->setModel('Publication');
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
    public function getPublicationsRelatedByOriginDate($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationsRelatedByOriginDatePartial && !$this->isNew();
        if (null === $this->collPublicationsRelatedByOriginDate || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationsRelatedByOriginDate) {
                // return empty collection
                $this->initPublicationsRelatedByOriginDate();
            } else {
                $collPublicationsRelatedByOriginDate = PublicationQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByOriginDate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationsRelatedByOriginDatePartial && count($collPublicationsRelatedByOriginDate)) {
                      $this->initPublicationsRelatedByOriginDate(false);

                      foreach($collPublicationsRelatedByOriginDate as $obj) {
                        if (false == $this->collPublicationsRelatedByOriginDate->contains($obj)) {
                          $this->collPublicationsRelatedByOriginDate->append($obj);
                        }
                      }

                      $this->collPublicationsRelatedByOriginDatePartial = true;
                    }

                    $collPublicationsRelatedByOriginDate->getInternalIterator()->rewind();
                    return $collPublicationsRelatedByOriginDate;
                }

                if($partial && $this->collPublicationsRelatedByOriginDate) {
                    foreach($this->collPublicationsRelatedByOriginDate as $obj) {
                        if($obj->isNew()) {
                            $collPublicationsRelatedByOriginDate[] = $obj;
                        }
                    }
                }

                $this->collPublicationsRelatedByOriginDate = $collPublicationsRelatedByOriginDate;
                $this->collPublicationsRelatedByOriginDatePartial = false;
            }
        }

        return $this->collPublicationsRelatedByOriginDate;
    }

    /**
     * Sets a collection of PublicationRelatedByOriginDate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationsRelatedByOriginDate A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setPublicationsRelatedByOriginDate(PropelCollection $publicationsRelatedByOriginDate, PropelPDO $con = null)
    {
        $publicationsRelatedByOriginDateToDelete = $this->getPublicationsRelatedByOriginDate(new Criteria(), $con)->diff($publicationsRelatedByOriginDate);

        $this->publicationsRelatedByOriginDateScheduledForDeletion = unserialize(serialize($publicationsRelatedByOriginDateToDelete));

        foreach ($publicationsRelatedByOriginDateToDelete as $publicationRelatedByOriginDateRemoved) {
            $publicationRelatedByOriginDateRemoved->setDatespecificationRelatedByOriginDate(null);
        }

        $this->collPublicationsRelatedByOriginDate = null;
        foreach ($publicationsRelatedByOriginDate as $publicationRelatedByOriginDate) {
            $this->addPublicationRelatedByOriginDate($publicationRelatedByOriginDate);
        }

        $this->collPublicationsRelatedByOriginDate = $publicationsRelatedByOriginDate;
        $this->collPublicationsRelatedByOriginDatePartial = false;

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
    public function countPublicationsRelatedByOriginDate(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationsRelatedByOriginDatePartial && !$this->isNew();
        if (null === $this->collPublicationsRelatedByOriginDate || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationsRelatedByOriginDate) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationsRelatedByOriginDate());
            }
            $query = PublicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByOriginDate($this)
                ->count($con);
        }

        return count($this->collPublicationsRelatedByOriginDate);
    }

    /**
     * Method called to associate a Publication object to this object
     * through the Publication foreign key attribute.
     *
     * @param    Publication $l Publication
     * @return Datespecification The current object (for fluent API support)
     */
    public function addPublicationRelatedByOriginDate(Publication $l)
    {
        if ($this->collPublicationsRelatedByOriginDate === null) {
            $this->initPublicationsRelatedByOriginDate();
            $this->collPublicationsRelatedByOriginDatePartial = true;
        }
        if (!in_array($l, $this->collPublicationsRelatedByOriginDate->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationRelatedByOriginDate($l);
        }

        return $this;
    }

    /**
     * @param	PublicationRelatedByOriginDate $publicationRelatedByOriginDate The publicationRelatedByOriginDate object to add.
     */
    protected function doAddPublicationRelatedByOriginDate($publicationRelatedByOriginDate)
    {
        $this->collPublicationsRelatedByOriginDate[]= $publicationRelatedByOriginDate;
        $publicationRelatedByOriginDate->setDatespecificationRelatedByOriginDate($this);
    }

    /**
     * @param	PublicationRelatedByOriginDate $publicationRelatedByOriginDate The publicationRelatedByOriginDate object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removePublicationRelatedByOriginDate($publicationRelatedByOriginDate)
    {
        if ($this->getPublicationsRelatedByOriginDate()->contains($publicationRelatedByOriginDate)) {
            $this->collPublicationsRelatedByOriginDate->remove($this->collPublicationsRelatedByOriginDate->search($publicationRelatedByOriginDate));
            if (null === $this->publicationsRelatedByOriginDateScheduledForDeletion) {
                $this->publicationsRelatedByOriginDateScheduledForDeletion = clone $this->collPublicationsRelatedByOriginDate;
                $this->publicationsRelatedByOriginDateScheduledForDeletion->clear();
            }
            $this->publicationsRelatedByOriginDateScheduledForDeletion[]= $publicationRelatedByOriginDate;
            $publicationRelatedByOriginDate->setDatespecificationRelatedByOriginDate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOriginDate from storage.
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
    public function getPublicationsRelatedByOriginDateJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getPublicationsRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOriginDate from storage.
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
    public function getPublicationsRelatedByOriginDateJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getPublicationsRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOriginDate from storage.
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
    public function getPublicationsRelatedByOriginDateJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getPublicationsRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOriginDate from storage.
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
    public function getPublicationsRelatedByOriginDateJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getPublicationsRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOriginDate from storage.
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
    public function getPublicationsRelatedByOriginDateJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getPublicationsRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOriginDate from storage.
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
    public function getPublicationsRelatedByOriginDateJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getPublicationsRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOriginDate from storage.
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
    public function getPublicationsRelatedByOriginDateJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getPublicationsRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByOriginDate from storage.
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
    public function getPublicationsRelatedByOriginDateJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getPublicationsRelatedByOriginDate($query, $con);
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
     * Clears out the collEssaysRelatedByPublicationDate collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addEssaysRelatedByPublicationDate()
     */
    public function clearEssaysRelatedByPublicationDate()
    {
        $this->collEssaysRelatedByPublicationDate = null; // important to set this to null since that means it is uninitialized
        $this->collEssaysRelatedByPublicationDatePartial = null;

        return $this;
    }

    /**
     * reset is the collEssaysRelatedByPublicationDate collection loaded partially
     *
     * @return void
     */
    public function resetPartialEssaysRelatedByPublicationDate($v = true)
    {
        $this->collEssaysRelatedByPublicationDatePartial = $v;
    }

    /**
     * Initializes the collEssaysRelatedByPublicationDate collection.
     *
     * By default this just sets the collEssaysRelatedByPublicationDate collection to an empty array (like clearcollEssaysRelatedByPublicationDate());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEssaysRelatedByPublicationDate($overrideExisting = true)
    {
        if (null !== $this->collEssaysRelatedByPublicationDate && !$overrideExisting) {
            return;
        }
        $this->collEssaysRelatedByPublicationDate = new PropelObjectCollection();
        $this->collEssaysRelatedByPublicationDate->setModel('Essay');
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
    public function getEssaysRelatedByPublicationDate($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEssaysRelatedByPublicationDatePartial && !$this->isNew();
        if (null === $this->collEssaysRelatedByPublicationDate || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEssaysRelatedByPublicationDate) {
                // return empty collection
                $this->initEssaysRelatedByPublicationDate();
            } else {
                $collEssaysRelatedByPublicationDate = EssayQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByPublicationDate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEssaysRelatedByPublicationDatePartial && count($collEssaysRelatedByPublicationDate)) {
                      $this->initEssaysRelatedByPublicationDate(false);

                      foreach($collEssaysRelatedByPublicationDate as $obj) {
                        if (false == $this->collEssaysRelatedByPublicationDate->contains($obj)) {
                          $this->collEssaysRelatedByPublicationDate->append($obj);
                        }
                      }

                      $this->collEssaysRelatedByPublicationDatePartial = true;
                    }

                    $collEssaysRelatedByPublicationDate->getInternalIterator()->rewind();
                    return $collEssaysRelatedByPublicationDate;
                }

                if($partial && $this->collEssaysRelatedByPublicationDate) {
                    foreach($this->collEssaysRelatedByPublicationDate as $obj) {
                        if($obj->isNew()) {
                            $collEssaysRelatedByPublicationDate[] = $obj;
                        }
                    }
                }

                $this->collEssaysRelatedByPublicationDate = $collEssaysRelatedByPublicationDate;
                $this->collEssaysRelatedByPublicationDatePartial = false;
            }
        }

        return $this->collEssaysRelatedByPublicationDate;
    }

    /**
     * Sets a collection of EssayRelatedByPublicationDate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $essaysRelatedByPublicationDate A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setEssaysRelatedByPublicationDate(PropelCollection $essaysRelatedByPublicationDate, PropelPDO $con = null)
    {
        $essaysRelatedByPublicationDateToDelete = $this->getEssaysRelatedByPublicationDate(new Criteria(), $con)->diff($essaysRelatedByPublicationDate);

        $this->essaysRelatedByPublicationDateScheduledForDeletion = unserialize(serialize($essaysRelatedByPublicationDateToDelete));

        foreach ($essaysRelatedByPublicationDateToDelete as $essayRelatedByPublicationDateRemoved) {
            $essayRelatedByPublicationDateRemoved->setDatespecificationRelatedByPublicationDate(null);
        }

        $this->collEssaysRelatedByPublicationDate = null;
        foreach ($essaysRelatedByPublicationDate as $essayRelatedByPublicationDate) {
            $this->addEssayRelatedByPublicationDate($essayRelatedByPublicationDate);
        }

        $this->collEssaysRelatedByPublicationDate = $essaysRelatedByPublicationDate;
        $this->collEssaysRelatedByPublicationDatePartial = false;

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
    public function countEssaysRelatedByPublicationDate(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEssaysRelatedByPublicationDatePartial && !$this->isNew();
        if (null === $this->collEssaysRelatedByPublicationDate || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEssaysRelatedByPublicationDate) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getEssaysRelatedByPublicationDate());
            }
            $query = EssayQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByPublicationDate($this)
                ->count($con);
        }

        return count($this->collEssaysRelatedByPublicationDate);
    }

    /**
     * Method called to associate a Essay object to this object
     * through the Essay foreign key attribute.
     *
     * @param    Essay $l Essay
     * @return Datespecification The current object (for fluent API support)
     */
    public function addEssayRelatedByPublicationDate(Essay $l)
    {
        if ($this->collEssaysRelatedByPublicationDate === null) {
            $this->initEssaysRelatedByPublicationDate();
            $this->collEssaysRelatedByPublicationDatePartial = true;
        }
        if (!in_array($l, $this->collEssaysRelatedByPublicationDate->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEssayRelatedByPublicationDate($l);
        }

        return $this;
    }

    /**
     * @param	EssayRelatedByPublicationDate $essayRelatedByPublicationDate The essayRelatedByPublicationDate object to add.
     */
    protected function doAddEssayRelatedByPublicationDate($essayRelatedByPublicationDate)
    {
        $this->collEssaysRelatedByPublicationDate[]= $essayRelatedByPublicationDate;
        $essayRelatedByPublicationDate->setDatespecificationRelatedByPublicationDate($this);
    }

    /**
     * @param	EssayRelatedByPublicationDate $essayRelatedByPublicationDate The essayRelatedByPublicationDate object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removeEssayRelatedByPublicationDate($essayRelatedByPublicationDate)
    {
        if ($this->getEssaysRelatedByPublicationDate()->contains($essayRelatedByPublicationDate)) {
            $this->collEssaysRelatedByPublicationDate->remove($this->collEssaysRelatedByPublicationDate->search($essayRelatedByPublicationDate));
            if (null === $this->essaysRelatedByPublicationDateScheduledForDeletion) {
                $this->essaysRelatedByPublicationDateScheduledForDeletion = clone $this->collEssaysRelatedByPublicationDate;
                $this->essaysRelatedByPublicationDateScheduledForDeletion->clear();
            }
            $this->essaysRelatedByPublicationDateScheduledForDeletion[]= $essayRelatedByPublicationDate;
            $essayRelatedByPublicationDate->setDatespecificationRelatedByPublicationDate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationDate from storage.
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
    public function getEssaysRelatedByPublicationDateJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getEssaysRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationDate from storage.
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
    public function getEssaysRelatedByPublicationDateJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getEssaysRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationDate from storage.
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
    public function getEssaysRelatedByPublicationDateJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getEssaysRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationDate from storage.
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
    public function getEssaysRelatedByPublicationDateJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getEssaysRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationDate from storage.
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
    public function getEssaysRelatedByPublicationDateJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getEssaysRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationDate from storage.
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
    public function getEssaysRelatedByPublicationDateJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getEssaysRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationDate from storage.
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
    public function getEssaysRelatedByPublicationDateJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getEssaysRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByPublicationDate from storage.
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
    public function getEssaysRelatedByPublicationDateJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getEssaysRelatedByPublicationDate($query, $con);
    }

    /**
     * Clears out the collEssaysRelatedByOriginDate collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addEssaysRelatedByOriginDate()
     */
    public function clearEssaysRelatedByOriginDate()
    {
        $this->collEssaysRelatedByOriginDate = null; // important to set this to null since that means it is uninitialized
        $this->collEssaysRelatedByOriginDatePartial = null;

        return $this;
    }

    /**
     * reset is the collEssaysRelatedByOriginDate collection loaded partially
     *
     * @return void
     */
    public function resetPartialEssaysRelatedByOriginDate($v = true)
    {
        $this->collEssaysRelatedByOriginDatePartial = $v;
    }

    /**
     * Initializes the collEssaysRelatedByOriginDate collection.
     *
     * By default this just sets the collEssaysRelatedByOriginDate collection to an empty array (like clearcollEssaysRelatedByOriginDate());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEssaysRelatedByOriginDate($overrideExisting = true)
    {
        if (null !== $this->collEssaysRelatedByOriginDate && !$overrideExisting) {
            return;
        }
        $this->collEssaysRelatedByOriginDate = new PropelObjectCollection();
        $this->collEssaysRelatedByOriginDate->setModel('Essay');
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
    public function getEssaysRelatedByOriginDate($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEssaysRelatedByOriginDatePartial && !$this->isNew();
        if (null === $this->collEssaysRelatedByOriginDate || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEssaysRelatedByOriginDate) {
                // return empty collection
                $this->initEssaysRelatedByOriginDate();
            } else {
                $collEssaysRelatedByOriginDate = EssayQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByOriginDate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEssaysRelatedByOriginDatePartial && count($collEssaysRelatedByOriginDate)) {
                      $this->initEssaysRelatedByOriginDate(false);

                      foreach($collEssaysRelatedByOriginDate as $obj) {
                        if (false == $this->collEssaysRelatedByOriginDate->contains($obj)) {
                          $this->collEssaysRelatedByOriginDate->append($obj);
                        }
                      }

                      $this->collEssaysRelatedByOriginDatePartial = true;
                    }

                    $collEssaysRelatedByOriginDate->getInternalIterator()->rewind();
                    return $collEssaysRelatedByOriginDate;
                }

                if($partial && $this->collEssaysRelatedByOriginDate) {
                    foreach($this->collEssaysRelatedByOriginDate as $obj) {
                        if($obj->isNew()) {
                            $collEssaysRelatedByOriginDate[] = $obj;
                        }
                    }
                }

                $this->collEssaysRelatedByOriginDate = $collEssaysRelatedByOriginDate;
                $this->collEssaysRelatedByOriginDatePartial = false;
            }
        }

        return $this->collEssaysRelatedByOriginDate;
    }

    /**
     * Sets a collection of EssayRelatedByOriginDate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $essaysRelatedByOriginDate A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setEssaysRelatedByOriginDate(PropelCollection $essaysRelatedByOriginDate, PropelPDO $con = null)
    {
        $essaysRelatedByOriginDateToDelete = $this->getEssaysRelatedByOriginDate(new Criteria(), $con)->diff($essaysRelatedByOriginDate);

        $this->essaysRelatedByOriginDateScheduledForDeletion = unserialize(serialize($essaysRelatedByOriginDateToDelete));

        foreach ($essaysRelatedByOriginDateToDelete as $essayRelatedByOriginDateRemoved) {
            $essayRelatedByOriginDateRemoved->setDatespecificationRelatedByOriginDate(null);
        }

        $this->collEssaysRelatedByOriginDate = null;
        foreach ($essaysRelatedByOriginDate as $essayRelatedByOriginDate) {
            $this->addEssayRelatedByOriginDate($essayRelatedByOriginDate);
        }

        $this->collEssaysRelatedByOriginDate = $essaysRelatedByOriginDate;
        $this->collEssaysRelatedByOriginDatePartial = false;

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
    public function countEssaysRelatedByOriginDate(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEssaysRelatedByOriginDatePartial && !$this->isNew();
        if (null === $this->collEssaysRelatedByOriginDate || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEssaysRelatedByOriginDate) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getEssaysRelatedByOriginDate());
            }
            $query = EssayQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByOriginDate($this)
                ->count($con);
        }

        return count($this->collEssaysRelatedByOriginDate);
    }

    /**
     * Method called to associate a Essay object to this object
     * through the Essay foreign key attribute.
     *
     * @param    Essay $l Essay
     * @return Datespecification The current object (for fluent API support)
     */
    public function addEssayRelatedByOriginDate(Essay $l)
    {
        if ($this->collEssaysRelatedByOriginDate === null) {
            $this->initEssaysRelatedByOriginDate();
            $this->collEssaysRelatedByOriginDatePartial = true;
        }
        if (!in_array($l, $this->collEssaysRelatedByOriginDate->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEssayRelatedByOriginDate($l);
        }

        return $this;
    }

    /**
     * @param	EssayRelatedByOriginDate $essayRelatedByOriginDate The essayRelatedByOriginDate object to add.
     */
    protected function doAddEssayRelatedByOriginDate($essayRelatedByOriginDate)
    {
        $this->collEssaysRelatedByOriginDate[]= $essayRelatedByOriginDate;
        $essayRelatedByOriginDate->setDatespecificationRelatedByOriginDate($this);
    }

    /**
     * @param	EssayRelatedByOriginDate $essayRelatedByOriginDate The essayRelatedByOriginDate object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removeEssayRelatedByOriginDate($essayRelatedByOriginDate)
    {
        if ($this->getEssaysRelatedByOriginDate()->contains($essayRelatedByOriginDate)) {
            $this->collEssaysRelatedByOriginDate->remove($this->collEssaysRelatedByOriginDate->search($essayRelatedByOriginDate));
            if (null === $this->essaysRelatedByOriginDateScheduledForDeletion) {
                $this->essaysRelatedByOriginDateScheduledForDeletion = clone $this->collEssaysRelatedByOriginDate;
                $this->essaysRelatedByOriginDateScheduledForDeletion->clear();
            }
            $this->essaysRelatedByOriginDateScheduledForDeletion[]= $essayRelatedByOriginDate;
            $essayRelatedByOriginDate->setDatespecificationRelatedByOriginDate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOriginDate from storage.
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
    public function getEssaysRelatedByOriginDateJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getEssaysRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOriginDate from storage.
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
    public function getEssaysRelatedByOriginDateJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getEssaysRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOriginDate from storage.
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
    public function getEssaysRelatedByOriginDateJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getEssaysRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOriginDate from storage.
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
    public function getEssaysRelatedByOriginDateJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getEssaysRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOriginDate from storage.
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
    public function getEssaysRelatedByOriginDateJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getEssaysRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOriginDate from storage.
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
    public function getEssaysRelatedByOriginDateJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getEssaysRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOriginDate from storage.
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
    public function getEssaysRelatedByOriginDateJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getEssaysRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related EssaysRelatedByOriginDate from storage.
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
    public function getEssaysRelatedByOriginDateJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getEssaysRelatedByOriginDate($query, $con);
    }

    /**
     * Clears out the collMagazinesRelatedByPublicationDate collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addMagazinesRelatedByPublicationDate()
     */
    public function clearMagazinesRelatedByPublicationDate()
    {
        $this->collMagazinesRelatedByPublicationDate = null; // important to set this to null since that means it is uninitialized
        $this->collMagazinesRelatedByPublicationDatePartial = null;

        return $this;
    }

    /**
     * reset is the collMagazinesRelatedByPublicationDate collection loaded partially
     *
     * @return void
     */
    public function resetPartialMagazinesRelatedByPublicationDate($v = true)
    {
        $this->collMagazinesRelatedByPublicationDatePartial = $v;
    }

    /**
     * Initializes the collMagazinesRelatedByPublicationDate collection.
     *
     * By default this just sets the collMagazinesRelatedByPublicationDate collection to an empty array (like clearcollMagazinesRelatedByPublicationDate());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMagazinesRelatedByPublicationDate($overrideExisting = true)
    {
        if (null !== $this->collMagazinesRelatedByPublicationDate && !$overrideExisting) {
            return;
        }
        $this->collMagazinesRelatedByPublicationDate = new PropelObjectCollection();
        $this->collMagazinesRelatedByPublicationDate->setModel('Magazine');
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
    public function getMagazinesRelatedByPublicationDate($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMagazinesRelatedByPublicationDatePartial && !$this->isNew();
        if (null === $this->collMagazinesRelatedByPublicationDate || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMagazinesRelatedByPublicationDate) {
                // return empty collection
                $this->initMagazinesRelatedByPublicationDate();
            } else {
                $collMagazinesRelatedByPublicationDate = MagazineQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByPublicationDate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMagazinesRelatedByPublicationDatePartial && count($collMagazinesRelatedByPublicationDate)) {
                      $this->initMagazinesRelatedByPublicationDate(false);

                      foreach($collMagazinesRelatedByPublicationDate as $obj) {
                        if (false == $this->collMagazinesRelatedByPublicationDate->contains($obj)) {
                          $this->collMagazinesRelatedByPublicationDate->append($obj);
                        }
                      }

                      $this->collMagazinesRelatedByPublicationDatePartial = true;
                    }

                    $collMagazinesRelatedByPublicationDate->getInternalIterator()->rewind();
                    return $collMagazinesRelatedByPublicationDate;
                }

                if($partial && $this->collMagazinesRelatedByPublicationDate) {
                    foreach($this->collMagazinesRelatedByPublicationDate as $obj) {
                        if($obj->isNew()) {
                            $collMagazinesRelatedByPublicationDate[] = $obj;
                        }
                    }
                }

                $this->collMagazinesRelatedByPublicationDate = $collMagazinesRelatedByPublicationDate;
                $this->collMagazinesRelatedByPublicationDatePartial = false;
            }
        }

        return $this->collMagazinesRelatedByPublicationDate;
    }

    /**
     * Sets a collection of MagazineRelatedByPublicationDate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $magazinesRelatedByPublicationDate A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setMagazinesRelatedByPublicationDate(PropelCollection $magazinesRelatedByPublicationDate, PropelPDO $con = null)
    {
        $magazinesRelatedByPublicationDateToDelete = $this->getMagazinesRelatedByPublicationDate(new Criteria(), $con)->diff($magazinesRelatedByPublicationDate);

        $this->magazinesRelatedByPublicationDateScheduledForDeletion = unserialize(serialize($magazinesRelatedByPublicationDateToDelete));

        foreach ($magazinesRelatedByPublicationDateToDelete as $magazineRelatedByPublicationDateRemoved) {
            $magazineRelatedByPublicationDateRemoved->setDatespecificationRelatedByPublicationDate(null);
        }

        $this->collMagazinesRelatedByPublicationDate = null;
        foreach ($magazinesRelatedByPublicationDate as $magazineRelatedByPublicationDate) {
            $this->addMagazineRelatedByPublicationDate($magazineRelatedByPublicationDate);
        }

        $this->collMagazinesRelatedByPublicationDate = $magazinesRelatedByPublicationDate;
        $this->collMagazinesRelatedByPublicationDatePartial = false;

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
    public function countMagazinesRelatedByPublicationDate(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collMagazinesRelatedByPublicationDatePartial && !$this->isNew();
        if (null === $this->collMagazinesRelatedByPublicationDate || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMagazinesRelatedByPublicationDate) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getMagazinesRelatedByPublicationDate());
            }
            $query = MagazineQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByPublicationDate($this)
                ->count($con);
        }

        return count($this->collMagazinesRelatedByPublicationDate);
    }

    /**
     * Method called to associate a Magazine object to this object
     * through the Magazine foreign key attribute.
     *
     * @param    Magazine $l Magazine
     * @return Datespecification The current object (for fluent API support)
     */
    public function addMagazineRelatedByPublicationDate(Magazine $l)
    {
        if ($this->collMagazinesRelatedByPublicationDate === null) {
            $this->initMagazinesRelatedByPublicationDate();
            $this->collMagazinesRelatedByPublicationDatePartial = true;
        }
        if (!in_array($l, $this->collMagazinesRelatedByPublicationDate->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMagazineRelatedByPublicationDate($l);
        }

        return $this;
    }

    /**
     * @param	MagazineRelatedByPublicationDate $magazineRelatedByPublicationDate The magazineRelatedByPublicationDate object to add.
     */
    protected function doAddMagazineRelatedByPublicationDate($magazineRelatedByPublicationDate)
    {
        $this->collMagazinesRelatedByPublicationDate[]= $magazineRelatedByPublicationDate;
        $magazineRelatedByPublicationDate->setDatespecificationRelatedByPublicationDate($this);
    }

    /**
     * @param	MagazineRelatedByPublicationDate $magazineRelatedByPublicationDate The magazineRelatedByPublicationDate object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removeMagazineRelatedByPublicationDate($magazineRelatedByPublicationDate)
    {
        if ($this->getMagazinesRelatedByPublicationDate()->contains($magazineRelatedByPublicationDate)) {
            $this->collMagazinesRelatedByPublicationDate->remove($this->collMagazinesRelatedByPublicationDate->search($magazineRelatedByPublicationDate));
            if (null === $this->magazinesRelatedByPublicationDateScheduledForDeletion) {
                $this->magazinesRelatedByPublicationDateScheduledForDeletion = clone $this->collMagazinesRelatedByPublicationDate;
                $this->magazinesRelatedByPublicationDateScheduledForDeletion->clear();
            }
            $this->magazinesRelatedByPublicationDateScheduledForDeletion[]= $magazineRelatedByPublicationDate;
            $magazineRelatedByPublicationDate->setDatespecificationRelatedByPublicationDate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationDate from storage.
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
    public function getMagazinesRelatedByPublicationDateJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getMagazinesRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationDate from storage.
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
    public function getMagazinesRelatedByPublicationDateJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getMagazinesRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationDate from storage.
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
    public function getMagazinesRelatedByPublicationDateJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getMagazinesRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationDate from storage.
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
    public function getMagazinesRelatedByPublicationDateJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getMagazinesRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationDate from storage.
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
    public function getMagazinesRelatedByPublicationDateJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getMagazinesRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationDate from storage.
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
    public function getMagazinesRelatedByPublicationDateJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getMagazinesRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationDate from storage.
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
    public function getMagazinesRelatedByPublicationDateJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getMagazinesRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByPublicationDate from storage.
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
    public function getMagazinesRelatedByPublicationDateJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getMagazinesRelatedByPublicationDate($query, $con);
    }

    /**
     * Clears out the collMagazinesRelatedByOriginDate collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addMagazinesRelatedByOriginDate()
     */
    public function clearMagazinesRelatedByOriginDate()
    {
        $this->collMagazinesRelatedByOriginDate = null; // important to set this to null since that means it is uninitialized
        $this->collMagazinesRelatedByOriginDatePartial = null;

        return $this;
    }

    /**
     * reset is the collMagazinesRelatedByOriginDate collection loaded partially
     *
     * @return void
     */
    public function resetPartialMagazinesRelatedByOriginDate($v = true)
    {
        $this->collMagazinesRelatedByOriginDatePartial = $v;
    }

    /**
     * Initializes the collMagazinesRelatedByOriginDate collection.
     *
     * By default this just sets the collMagazinesRelatedByOriginDate collection to an empty array (like clearcollMagazinesRelatedByOriginDate());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMagazinesRelatedByOriginDate($overrideExisting = true)
    {
        if (null !== $this->collMagazinesRelatedByOriginDate && !$overrideExisting) {
            return;
        }
        $this->collMagazinesRelatedByOriginDate = new PropelObjectCollection();
        $this->collMagazinesRelatedByOriginDate->setModel('Magazine');
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
    public function getMagazinesRelatedByOriginDate($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMagazinesRelatedByOriginDatePartial && !$this->isNew();
        if (null === $this->collMagazinesRelatedByOriginDate || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMagazinesRelatedByOriginDate) {
                // return empty collection
                $this->initMagazinesRelatedByOriginDate();
            } else {
                $collMagazinesRelatedByOriginDate = MagazineQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByOriginDate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMagazinesRelatedByOriginDatePartial && count($collMagazinesRelatedByOriginDate)) {
                      $this->initMagazinesRelatedByOriginDate(false);

                      foreach($collMagazinesRelatedByOriginDate as $obj) {
                        if (false == $this->collMagazinesRelatedByOriginDate->contains($obj)) {
                          $this->collMagazinesRelatedByOriginDate->append($obj);
                        }
                      }

                      $this->collMagazinesRelatedByOriginDatePartial = true;
                    }

                    $collMagazinesRelatedByOriginDate->getInternalIterator()->rewind();
                    return $collMagazinesRelatedByOriginDate;
                }

                if($partial && $this->collMagazinesRelatedByOriginDate) {
                    foreach($this->collMagazinesRelatedByOriginDate as $obj) {
                        if($obj->isNew()) {
                            $collMagazinesRelatedByOriginDate[] = $obj;
                        }
                    }
                }

                $this->collMagazinesRelatedByOriginDate = $collMagazinesRelatedByOriginDate;
                $this->collMagazinesRelatedByOriginDatePartial = false;
            }
        }

        return $this->collMagazinesRelatedByOriginDate;
    }

    /**
     * Sets a collection of MagazineRelatedByOriginDate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $magazinesRelatedByOriginDate A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setMagazinesRelatedByOriginDate(PropelCollection $magazinesRelatedByOriginDate, PropelPDO $con = null)
    {
        $magazinesRelatedByOriginDateToDelete = $this->getMagazinesRelatedByOriginDate(new Criteria(), $con)->diff($magazinesRelatedByOriginDate);

        $this->magazinesRelatedByOriginDateScheduledForDeletion = unserialize(serialize($magazinesRelatedByOriginDateToDelete));

        foreach ($magazinesRelatedByOriginDateToDelete as $magazineRelatedByOriginDateRemoved) {
            $magazineRelatedByOriginDateRemoved->setDatespecificationRelatedByOriginDate(null);
        }

        $this->collMagazinesRelatedByOriginDate = null;
        foreach ($magazinesRelatedByOriginDate as $magazineRelatedByOriginDate) {
            $this->addMagazineRelatedByOriginDate($magazineRelatedByOriginDate);
        }

        $this->collMagazinesRelatedByOriginDate = $magazinesRelatedByOriginDate;
        $this->collMagazinesRelatedByOriginDatePartial = false;

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
    public function countMagazinesRelatedByOriginDate(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collMagazinesRelatedByOriginDatePartial && !$this->isNew();
        if (null === $this->collMagazinesRelatedByOriginDate || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMagazinesRelatedByOriginDate) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getMagazinesRelatedByOriginDate());
            }
            $query = MagazineQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByOriginDate($this)
                ->count($con);
        }

        return count($this->collMagazinesRelatedByOriginDate);
    }

    /**
     * Method called to associate a Magazine object to this object
     * through the Magazine foreign key attribute.
     *
     * @param    Magazine $l Magazine
     * @return Datespecification The current object (for fluent API support)
     */
    public function addMagazineRelatedByOriginDate(Magazine $l)
    {
        if ($this->collMagazinesRelatedByOriginDate === null) {
            $this->initMagazinesRelatedByOriginDate();
            $this->collMagazinesRelatedByOriginDatePartial = true;
        }
        if (!in_array($l, $this->collMagazinesRelatedByOriginDate->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMagazineRelatedByOriginDate($l);
        }

        return $this;
    }

    /**
     * @param	MagazineRelatedByOriginDate $magazineRelatedByOriginDate The magazineRelatedByOriginDate object to add.
     */
    protected function doAddMagazineRelatedByOriginDate($magazineRelatedByOriginDate)
    {
        $this->collMagazinesRelatedByOriginDate[]= $magazineRelatedByOriginDate;
        $magazineRelatedByOriginDate->setDatespecificationRelatedByOriginDate($this);
    }

    /**
     * @param	MagazineRelatedByOriginDate $magazineRelatedByOriginDate The magazineRelatedByOriginDate object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removeMagazineRelatedByOriginDate($magazineRelatedByOriginDate)
    {
        if ($this->getMagazinesRelatedByOriginDate()->contains($magazineRelatedByOriginDate)) {
            $this->collMagazinesRelatedByOriginDate->remove($this->collMagazinesRelatedByOriginDate->search($magazineRelatedByOriginDate));
            if (null === $this->magazinesRelatedByOriginDateScheduledForDeletion) {
                $this->magazinesRelatedByOriginDateScheduledForDeletion = clone $this->collMagazinesRelatedByOriginDate;
                $this->magazinesRelatedByOriginDateScheduledForDeletion->clear();
            }
            $this->magazinesRelatedByOriginDateScheduledForDeletion[]= $magazineRelatedByOriginDate;
            $magazineRelatedByOriginDate->setDatespecificationRelatedByOriginDate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOriginDate from storage.
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
    public function getMagazinesRelatedByOriginDateJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getMagazinesRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOriginDate from storage.
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
    public function getMagazinesRelatedByOriginDateJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getMagazinesRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOriginDate from storage.
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
    public function getMagazinesRelatedByOriginDateJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getMagazinesRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOriginDate from storage.
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
    public function getMagazinesRelatedByOriginDateJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getMagazinesRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOriginDate from storage.
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
    public function getMagazinesRelatedByOriginDateJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getMagazinesRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOriginDate from storage.
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
    public function getMagazinesRelatedByOriginDateJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getMagazinesRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOriginDate from storage.
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
    public function getMagazinesRelatedByOriginDateJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getMagazinesRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related MagazinesRelatedByOriginDate from storage.
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
    public function getMagazinesRelatedByOriginDateJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getMagazinesRelatedByOriginDate($query, $con);
    }

    /**
     * Clears out the collSeriesRelatedByPublicationDate collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addSeriesRelatedByPublicationDate()
     */
    public function clearSeriesRelatedByPublicationDate()
    {
        $this->collSeriesRelatedByPublicationDate = null; // important to set this to null since that means it is uninitialized
        $this->collSeriesRelatedByPublicationDatePartial = null;

        return $this;
    }

    /**
     * reset is the collSeriesRelatedByPublicationDate collection loaded partially
     *
     * @return void
     */
    public function resetPartialSeriesRelatedByPublicationDate($v = true)
    {
        $this->collSeriesRelatedByPublicationDatePartial = $v;
    }

    /**
     * Initializes the collSeriesRelatedByPublicationDate collection.
     *
     * By default this just sets the collSeriesRelatedByPublicationDate collection to an empty array (like clearcollSeriesRelatedByPublicationDate());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSeriesRelatedByPublicationDate($overrideExisting = true)
    {
        if (null !== $this->collSeriesRelatedByPublicationDate && !$overrideExisting) {
            return;
        }
        $this->collSeriesRelatedByPublicationDate = new PropelObjectCollection();
        $this->collSeriesRelatedByPublicationDate->setModel('Series');
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
    public function getSeriesRelatedByPublicationDate($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSeriesRelatedByPublicationDatePartial && !$this->isNew();
        if (null === $this->collSeriesRelatedByPublicationDate || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSeriesRelatedByPublicationDate) {
                // return empty collection
                $this->initSeriesRelatedByPublicationDate();
            } else {
                $collSeriesRelatedByPublicationDate = SeriesQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByPublicationDate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSeriesRelatedByPublicationDatePartial && count($collSeriesRelatedByPublicationDate)) {
                      $this->initSeriesRelatedByPublicationDate(false);

                      foreach($collSeriesRelatedByPublicationDate as $obj) {
                        if (false == $this->collSeriesRelatedByPublicationDate->contains($obj)) {
                          $this->collSeriesRelatedByPublicationDate->append($obj);
                        }
                      }

                      $this->collSeriesRelatedByPublicationDatePartial = true;
                    }

                    $collSeriesRelatedByPublicationDate->getInternalIterator()->rewind();
                    return $collSeriesRelatedByPublicationDate;
                }

                if($partial && $this->collSeriesRelatedByPublicationDate) {
                    foreach($this->collSeriesRelatedByPublicationDate as $obj) {
                        if($obj->isNew()) {
                            $collSeriesRelatedByPublicationDate[] = $obj;
                        }
                    }
                }

                $this->collSeriesRelatedByPublicationDate = $collSeriesRelatedByPublicationDate;
                $this->collSeriesRelatedByPublicationDatePartial = false;
            }
        }

        return $this->collSeriesRelatedByPublicationDate;
    }

    /**
     * Sets a collection of SeriesRelatedByPublicationDate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $seriesRelatedByPublicationDate A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setSeriesRelatedByPublicationDate(PropelCollection $seriesRelatedByPublicationDate, PropelPDO $con = null)
    {
        $seriesRelatedByPublicationDateToDelete = $this->getSeriesRelatedByPublicationDate(new Criteria(), $con)->diff($seriesRelatedByPublicationDate);

        $this->seriesRelatedByPublicationDateScheduledForDeletion = unserialize(serialize($seriesRelatedByPublicationDateToDelete));

        foreach ($seriesRelatedByPublicationDateToDelete as $seriesRelatedByPublicationDateRemoved) {
            $seriesRelatedByPublicationDateRemoved->setDatespecificationRelatedByPublicationDate(null);
        }

        $this->collSeriesRelatedByPublicationDate = null;
        foreach ($seriesRelatedByPublicationDate as $seriesRelatedByPublicationDate) {
            $this->addSeriesRelatedByPublicationDate($seriesRelatedByPublicationDate);
        }

        $this->collSeriesRelatedByPublicationDate = $seriesRelatedByPublicationDate;
        $this->collSeriesRelatedByPublicationDatePartial = false;

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
    public function countSeriesRelatedByPublicationDate(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSeriesRelatedByPublicationDatePartial && !$this->isNew();
        if (null === $this->collSeriesRelatedByPublicationDate || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSeriesRelatedByPublicationDate) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getSeriesRelatedByPublicationDate());
            }
            $query = SeriesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByPublicationDate($this)
                ->count($con);
        }

        return count($this->collSeriesRelatedByPublicationDate);
    }

    /**
     * Method called to associate a Series object to this object
     * through the Series foreign key attribute.
     *
     * @param    Series $l Series
     * @return Datespecification The current object (for fluent API support)
     */
    public function addSeriesRelatedByPublicationDate(Series $l)
    {
        if ($this->collSeriesRelatedByPublicationDate === null) {
            $this->initSeriesRelatedByPublicationDate();
            $this->collSeriesRelatedByPublicationDatePartial = true;
        }
        if (!in_array($l, $this->collSeriesRelatedByPublicationDate->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSeriesRelatedByPublicationDate($l);
        }

        return $this;
    }

    /**
     * @param	SeriesRelatedByPublicationDate $seriesRelatedByPublicationDate The seriesRelatedByPublicationDate object to add.
     */
    protected function doAddSeriesRelatedByPublicationDate($seriesRelatedByPublicationDate)
    {
        $this->collSeriesRelatedByPublicationDate[]= $seriesRelatedByPublicationDate;
        $seriesRelatedByPublicationDate->setDatespecificationRelatedByPublicationDate($this);
    }

    /**
     * @param	SeriesRelatedByPublicationDate $seriesRelatedByPublicationDate The seriesRelatedByPublicationDate object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removeSeriesRelatedByPublicationDate($seriesRelatedByPublicationDate)
    {
        if ($this->getSeriesRelatedByPublicationDate()->contains($seriesRelatedByPublicationDate)) {
            $this->collSeriesRelatedByPublicationDate->remove($this->collSeriesRelatedByPublicationDate->search($seriesRelatedByPublicationDate));
            if (null === $this->seriesRelatedByPublicationDateScheduledForDeletion) {
                $this->seriesRelatedByPublicationDateScheduledForDeletion = clone $this->collSeriesRelatedByPublicationDate;
                $this->seriesRelatedByPublicationDateScheduledForDeletion->clear();
            }
            $this->seriesRelatedByPublicationDateScheduledForDeletion[]= $seriesRelatedByPublicationDate;
            $seriesRelatedByPublicationDate->setDatespecificationRelatedByPublicationDate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationDate from storage.
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
    public function getSeriesRelatedByPublicationDateJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getSeriesRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationDate from storage.
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
    public function getSeriesRelatedByPublicationDateJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getSeriesRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationDate from storage.
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
    public function getSeriesRelatedByPublicationDateJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getSeriesRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationDate from storage.
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
    public function getSeriesRelatedByPublicationDateJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getSeriesRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationDate from storage.
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
    public function getSeriesRelatedByPublicationDateJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getSeriesRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationDate from storage.
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
    public function getSeriesRelatedByPublicationDateJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getSeriesRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationDate from storage.
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
    public function getSeriesRelatedByPublicationDateJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getSeriesRelatedByPublicationDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByPublicationDate from storage.
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
    public function getSeriesRelatedByPublicationDateJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getSeriesRelatedByPublicationDate($query, $con);
    }

    /**
     * Clears out the collSeriesRelatedByOriginDate collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addSeriesRelatedByOriginDate()
     */
    public function clearSeriesRelatedByOriginDate()
    {
        $this->collSeriesRelatedByOriginDate = null; // important to set this to null since that means it is uninitialized
        $this->collSeriesRelatedByOriginDatePartial = null;

        return $this;
    }

    /**
     * reset is the collSeriesRelatedByOriginDate collection loaded partially
     *
     * @return void
     */
    public function resetPartialSeriesRelatedByOriginDate($v = true)
    {
        $this->collSeriesRelatedByOriginDatePartial = $v;
    }

    /**
     * Initializes the collSeriesRelatedByOriginDate collection.
     *
     * By default this just sets the collSeriesRelatedByOriginDate collection to an empty array (like clearcollSeriesRelatedByOriginDate());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSeriesRelatedByOriginDate($overrideExisting = true)
    {
        if (null !== $this->collSeriesRelatedByOriginDate && !$overrideExisting) {
            return;
        }
        $this->collSeriesRelatedByOriginDate = new PropelObjectCollection();
        $this->collSeriesRelatedByOriginDate->setModel('Series');
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
    public function getSeriesRelatedByOriginDate($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSeriesRelatedByOriginDatePartial && !$this->isNew();
        if (null === $this->collSeriesRelatedByOriginDate || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSeriesRelatedByOriginDate) {
                // return empty collection
                $this->initSeriesRelatedByOriginDate();
            } else {
                $collSeriesRelatedByOriginDate = SeriesQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByOriginDate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSeriesRelatedByOriginDatePartial && count($collSeriesRelatedByOriginDate)) {
                      $this->initSeriesRelatedByOriginDate(false);

                      foreach($collSeriesRelatedByOriginDate as $obj) {
                        if (false == $this->collSeriesRelatedByOriginDate->contains($obj)) {
                          $this->collSeriesRelatedByOriginDate->append($obj);
                        }
                      }

                      $this->collSeriesRelatedByOriginDatePartial = true;
                    }

                    $collSeriesRelatedByOriginDate->getInternalIterator()->rewind();
                    return $collSeriesRelatedByOriginDate;
                }

                if($partial && $this->collSeriesRelatedByOriginDate) {
                    foreach($this->collSeriesRelatedByOriginDate as $obj) {
                        if($obj->isNew()) {
                            $collSeriesRelatedByOriginDate[] = $obj;
                        }
                    }
                }

                $this->collSeriesRelatedByOriginDate = $collSeriesRelatedByOriginDate;
                $this->collSeriesRelatedByOriginDatePartial = false;
            }
        }

        return $this->collSeriesRelatedByOriginDate;
    }

    /**
     * Sets a collection of SeriesRelatedByOriginDate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $seriesRelatedByOriginDate A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setSeriesRelatedByOriginDate(PropelCollection $seriesRelatedByOriginDate, PropelPDO $con = null)
    {
        $seriesRelatedByOriginDateToDelete = $this->getSeriesRelatedByOriginDate(new Criteria(), $con)->diff($seriesRelatedByOriginDate);

        $this->seriesRelatedByOriginDateScheduledForDeletion = unserialize(serialize($seriesRelatedByOriginDateToDelete));

        foreach ($seriesRelatedByOriginDateToDelete as $seriesRelatedByOriginDateRemoved) {
            $seriesRelatedByOriginDateRemoved->setDatespecificationRelatedByOriginDate(null);
        }

        $this->collSeriesRelatedByOriginDate = null;
        foreach ($seriesRelatedByOriginDate as $seriesRelatedByOriginDate) {
            $this->addSeriesRelatedByOriginDate($seriesRelatedByOriginDate);
        }

        $this->collSeriesRelatedByOriginDate = $seriesRelatedByOriginDate;
        $this->collSeriesRelatedByOriginDatePartial = false;

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
    public function countSeriesRelatedByOriginDate(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSeriesRelatedByOriginDatePartial && !$this->isNew();
        if (null === $this->collSeriesRelatedByOriginDate || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSeriesRelatedByOriginDate) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getSeriesRelatedByOriginDate());
            }
            $query = SeriesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByOriginDate($this)
                ->count($con);
        }

        return count($this->collSeriesRelatedByOriginDate);
    }

    /**
     * Method called to associate a Series object to this object
     * through the Series foreign key attribute.
     *
     * @param    Series $l Series
     * @return Datespecification The current object (for fluent API support)
     */
    public function addSeriesRelatedByOriginDate(Series $l)
    {
        if ($this->collSeriesRelatedByOriginDate === null) {
            $this->initSeriesRelatedByOriginDate();
            $this->collSeriesRelatedByOriginDatePartial = true;
        }
        if (!in_array($l, $this->collSeriesRelatedByOriginDate->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSeriesRelatedByOriginDate($l);
        }

        return $this;
    }

    /**
     * @param	SeriesRelatedByOriginDate $seriesRelatedByOriginDate The seriesRelatedByOriginDate object to add.
     */
    protected function doAddSeriesRelatedByOriginDate($seriesRelatedByOriginDate)
    {
        $this->collSeriesRelatedByOriginDate[]= $seriesRelatedByOriginDate;
        $seriesRelatedByOriginDate->setDatespecificationRelatedByOriginDate($this);
    }

    /**
     * @param	SeriesRelatedByOriginDate $seriesRelatedByOriginDate The seriesRelatedByOriginDate object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removeSeriesRelatedByOriginDate($seriesRelatedByOriginDate)
    {
        if ($this->getSeriesRelatedByOriginDate()->contains($seriesRelatedByOriginDate)) {
            $this->collSeriesRelatedByOriginDate->remove($this->collSeriesRelatedByOriginDate->search($seriesRelatedByOriginDate));
            if (null === $this->seriesRelatedByOriginDateScheduledForDeletion) {
                $this->seriesRelatedByOriginDateScheduledForDeletion = clone $this->collSeriesRelatedByOriginDate;
                $this->seriesRelatedByOriginDateScheduledForDeletion->clear();
            }
            $this->seriesRelatedByOriginDateScheduledForDeletion[]= $seriesRelatedByOriginDate;
            $seriesRelatedByOriginDate->setDatespecificationRelatedByOriginDate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOriginDate from storage.
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
    public function getSeriesRelatedByOriginDateJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getSeriesRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOriginDate from storage.
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
    public function getSeriesRelatedByOriginDateJoinPublisher($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Publisher', $join_behavior);

        return $this->getSeriesRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOriginDate from storage.
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
    public function getSeriesRelatedByOriginDateJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getSeriesRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOriginDate from storage.
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
    public function getSeriesRelatedByOriginDateJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getSeriesRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOriginDate from storage.
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
    public function getSeriesRelatedByOriginDateJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getSeriesRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOriginDate from storage.
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
    public function getSeriesRelatedByOriginDateJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getSeriesRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOriginDate from storage.
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
    public function getSeriesRelatedByOriginDateJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getSeriesRelatedByOriginDate($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related SeriesRelatedByOriginDate from storage.
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
    public function getSeriesRelatedByOriginDateJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getSeriesRelatedByOriginDate($query, $con);
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
            if ($this->collPublicationsRelatedByPublicationDate) {
                foreach ($this->collPublicationsRelatedByPublicationDate as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationsRelatedByOriginDate) {
                foreach ($this->collPublicationsRelatedByOriginDate as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWorks) {
                foreach ($this->collWorks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEssaysRelatedByPublicationDate) {
                foreach ($this->collEssaysRelatedByPublicationDate as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEssaysRelatedByOriginDate) {
                foreach ($this->collEssaysRelatedByOriginDate as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMagazinesRelatedByPublicationDate) {
                foreach ($this->collMagazinesRelatedByPublicationDate as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMagazinesRelatedByOriginDate) {
                foreach ($this->collMagazinesRelatedByOriginDate as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSeriesRelatedByPublicationDate) {
                foreach ($this->collSeriesRelatedByPublicationDate as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSeriesRelatedByOriginDate) {
                foreach ($this->collSeriesRelatedByOriginDate as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPublicationsRelatedByPublicationDate instanceof PropelCollection) {
            $this->collPublicationsRelatedByPublicationDate->clearIterator();
        }
        $this->collPublicationsRelatedByPublicationDate = null;
        if ($this->collPublicationsRelatedByOriginDate instanceof PropelCollection) {
            $this->collPublicationsRelatedByOriginDate->clearIterator();
        }
        $this->collPublicationsRelatedByOriginDate = null;
        if ($this->collWorks instanceof PropelCollection) {
            $this->collWorks->clearIterator();
        }
        $this->collWorks = null;
        if ($this->collEssaysRelatedByPublicationDate instanceof PropelCollection) {
            $this->collEssaysRelatedByPublicationDate->clearIterator();
        }
        $this->collEssaysRelatedByPublicationDate = null;
        if ($this->collEssaysRelatedByOriginDate instanceof PropelCollection) {
            $this->collEssaysRelatedByOriginDate->clearIterator();
        }
        $this->collEssaysRelatedByOriginDate = null;
        if ($this->collMagazinesRelatedByPublicationDate instanceof PropelCollection) {
            $this->collMagazinesRelatedByPublicationDate->clearIterator();
        }
        $this->collMagazinesRelatedByPublicationDate = null;
        if ($this->collMagazinesRelatedByOriginDate instanceof PropelCollection) {
            $this->collMagazinesRelatedByOriginDate->clearIterator();
        }
        $this->collMagazinesRelatedByOriginDate = null;
        if ($this->collSeriesRelatedByPublicationDate instanceof PropelCollection) {
            $this->collSeriesRelatedByPublicationDate->clearIterator();
        }
        $this->collSeriesRelatedByPublicationDate = null;
        if ($this->collSeriesRelatedByOriginDate instanceof PropelCollection) {
            $this->collSeriesRelatedByOriginDate->clearIterator();
        }
        $this->collSeriesRelatedByOriginDate = null;
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
