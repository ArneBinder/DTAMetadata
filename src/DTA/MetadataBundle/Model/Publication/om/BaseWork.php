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
use DTA\MetadataBundle\Model\Classification\Dwdsgenre;
use DTA\MetadataBundle\Model\Classification\DwdsgenreQuery;
use DTA\MetadataBundle\Model\Classification\Genre;
use DTA\MetadataBundle\Model\Classification\GenreQuery;
use DTA\MetadataBundle\Model\Description\Datespecification;
use DTA\MetadataBundle\Model\Description\DatespecificationQuery;
use DTA\MetadataBundle\Model\HistoricalPerson\Author;
use DTA\MetadataBundle\Model\HistoricalPerson\AuthorQuery;
use DTA\MetadataBundle\Model\HistoricalPerson\AuthorWork;
use DTA\MetadataBundle\Model\HistoricalPerson\AuthorWorkQuery;
use DTA\MetadataBundle\Model\Publication\Work;
use DTA\MetadataBundle\Model\Publication\WorkPeer;
use DTA\MetadataBundle\Model\Publication\WorkQuery;
use DTA\MetadataBundle\Model\Publication\Writ;
use DTA\MetadataBundle\Model\Publication\WritQuery;
use DTA\MetadataBundle\Model\Workflow\Status;
use DTA\MetadataBundle\Model\Workflow\StatusQuery;

abstract class BaseWork extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\Publication\\WorkPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        WorkPeer
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
     * The value for the status_id field.
     * @var        int
     */
    protected $status_id;

    /**
     * The value for the datespecification_id field.
     * @var        int
     */
    protected $datespecification_id;

    /**
     * The value for the genre_id field.
     * @var        int
     */
    protected $genre_id;

    /**
     * The value for the subgenre_id field.
     * @var        int
     */
    protected $subgenre_id;

    /**
     * The value for the dwdsgenre_id field.
     * @var        int
     */
    protected $dwdsgenre_id;

    /**
     * The value for the dwdssubgenre_id field.
     * @var        int
     */
    protected $dwdssubgenre_id;

    /**
     * The value for the doi field.
     * @var        string
     */
    protected $doi;

    /**
     * The value for the comments field.
     * @var        string
     */
    protected $comments;

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
     * @var        Status
     */
    protected $aStatus;

    /**
     * @var        Genre
     */
    protected $aGenreRelatedByGenreId;

    /**
     * @var        Genre
     */
    protected $aGenreRelatedBySubgenreId;

    /**
     * @var        Dwdsgenre
     */
    protected $aDwdsgenreRelatedByDwdsgenreId;

    /**
     * @var        Dwdsgenre
     */
    protected $aDwdsgenreRelatedByDwdssubgenreId;

    /**
     * @var        Datespecification
     */
    protected $aDatespecification;

    /**
     * @var        PropelObjectCollection|AuthorWork[] Collection to store aggregation of AuthorWork objects.
     */
    protected $collAuthorWorks;
    protected $collAuthorWorksPartial;

    /**
     * @var        PropelObjectCollection|Writ[] Collection to store aggregation of Writ objects.
     */
    protected $collWrits;
    protected $collWritsPartial;

    /**
     * @var        PropelObjectCollection|Author[] Collection to store aggregation of Author objects.
     */
    protected $collAuthors;

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
    protected $authorsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $authorWorksScheduledForDeletion = null;

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
     * Get the [status_id] column value.
     *
     * @return int
     */
    public function getStatusId()
    {
        return $this->status_id;
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
     * Get the [genre_id] column value.
     *
     * @return int
     */
    public function getGenreId()
    {
        return $this->genre_id;
    }

    /**
     * Get the [subgenre_id] column value.
     *
     * @return int
     */
    public function getSubgenreId()
    {
        return $this->subgenre_id;
    }

    /**
     * Get the [dwdsgenre_id] column value.
     *
     * @return int
     */
    public function getDwdsgenreId()
    {
        return $this->dwdsgenre_id;
    }

    /**
     * Get the [dwdssubgenre_id] column value.
     *
     * @return int
     */
    public function getDwdssubgenreId()
    {
        return $this->dwdssubgenre_id;
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
     * Get the [comments] column value.
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
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
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = WorkPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [status_id] column.
     *
     * @param int $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setStatusId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->status_id !== $v) {
            $this->status_id = $v;
            $this->modifiedColumns[] = WorkPeer::STATUS_ID;
        }

        if ($this->aStatus !== null && $this->aStatus->getId() !== $v) {
            $this->aStatus = null;
        }


        return $this;
    } // setStatusId()

    /**
     * Set the value of [datespecification_id] column.
     *
     * @param int $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setDatespecificationId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->datespecification_id !== $v) {
            $this->datespecification_id = $v;
            $this->modifiedColumns[] = WorkPeer::DATESPECIFICATION_ID;
        }

        if ($this->aDatespecification !== null && $this->aDatespecification->getId() !== $v) {
            $this->aDatespecification = null;
        }


        return $this;
    } // setDatespecificationId()

    /**
     * Set the value of [genre_id] column.
     *
     * @param int $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setGenreId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->genre_id !== $v) {
            $this->genre_id = $v;
            $this->modifiedColumns[] = WorkPeer::GENRE_ID;
        }

        if ($this->aGenreRelatedByGenreId !== null && $this->aGenreRelatedByGenreId->getId() !== $v) {
            $this->aGenreRelatedByGenreId = null;
        }


        return $this;
    } // setGenreId()

    /**
     * Set the value of [subgenre_id] column.
     *
     * @param int $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setSubgenreId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->subgenre_id !== $v) {
            $this->subgenre_id = $v;
            $this->modifiedColumns[] = WorkPeer::SUBGENRE_ID;
        }

        if ($this->aGenreRelatedBySubgenreId !== null && $this->aGenreRelatedBySubgenreId->getId() !== $v) {
            $this->aGenreRelatedBySubgenreId = null;
        }


        return $this;
    } // setSubgenreId()

    /**
     * Set the value of [dwdsgenre_id] column.
     *
     * @param int $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setDwdsgenreId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->dwdsgenre_id !== $v) {
            $this->dwdsgenre_id = $v;
            $this->modifiedColumns[] = WorkPeer::DWDSGENRE_ID;
        }

        if ($this->aDwdsgenreRelatedByDwdsgenreId !== null && $this->aDwdsgenreRelatedByDwdsgenreId->getId() !== $v) {
            $this->aDwdsgenreRelatedByDwdsgenreId = null;
        }


        return $this;
    } // setDwdsgenreId()

    /**
     * Set the value of [dwdssubgenre_id] column.
     *
     * @param int $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setDwdssubgenreId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->dwdssubgenre_id !== $v) {
            $this->dwdssubgenre_id = $v;
            $this->modifiedColumns[] = WorkPeer::DWDSSUBGENRE_ID;
        }

        if ($this->aDwdsgenreRelatedByDwdssubgenreId !== null && $this->aDwdsgenreRelatedByDwdssubgenreId->getId() !== $v) {
            $this->aDwdsgenreRelatedByDwdssubgenreId = null;
        }


        return $this;
    } // setDwdssubgenreId()

    /**
     * Set the value of [doi] column.
     *
     * @param string $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setDoi($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->doi !== $v) {
            $this->doi = $v;
            $this->modifiedColumns[] = WorkPeer::DOI;
        }


        return $this;
    } // setDoi()

    /**
     * Set the value of [comments] column.
     *
     * @param string $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setComments($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->comments !== $v) {
            $this->comments = $v;
            $this->modifiedColumns[] = WorkPeer::COMMENTS;
        }


        return $this;
    } // setComments()

    /**
     * Set the value of [format] column.
     *
     * @param string $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setFormat($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->format !== $v) {
            $this->format = $v;
            $this->modifiedColumns[] = WorkPeer::FORMAT;
        }


        return $this;
    } // setFormat()

    /**
     * Set the value of [directoryname] column.
     *
     * @param string $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setDirectoryname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->directoryname !== $v) {
            $this->directoryname = $v;
            $this->modifiedColumns[] = WorkPeer::DIRECTORYNAME;
        }


        return $this;
    } // setDirectoryname()

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
            $this->status_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->datespecification_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->genre_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->subgenre_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->dwdsgenre_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->dwdssubgenre_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->doi = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->comments = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->format = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->directoryname = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 11; // 11 = WorkPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Work object", $e);
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

        if ($this->aStatus !== null && $this->status_id !== $this->aStatus->getId()) {
            $this->aStatus = null;
        }
        if ($this->aDatespecification !== null && $this->datespecification_id !== $this->aDatespecification->getId()) {
            $this->aDatespecification = null;
        }
        if ($this->aGenreRelatedByGenreId !== null && $this->genre_id !== $this->aGenreRelatedByGenreId->getId()) {
            $this->aGenreRelatedByGenreId = null;
        }
        if ($this->aGenreRelatedBySubgenreId !== null && $this->subgenre_id !== $this->aGenreRelatedBySubgenreId->getId()) {
            $this->aGenreRelatedBySubgenreId = null;
        }
        if ($this->aDwdsgenreRelatedByDwdsgenreId !== null && $this->dwdsgenre_id !== $this->aDwdsgenreRelatedByDwdsgenreId->getId()) {
            $this->aDwdsgenreRelatedByDwdsgenreId = null;
        }
        if ($this->aDwdsgenreRelatedByDwdssubgenreId !== null && $this->dwdssubgenre_id !== $this->aDwdsgenreRelatedByDwdssubgenreId->getId()) {
            $this->aDwdsgenreRelatedByDwdssubgenreId = null;
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
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = WorkPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aStatus = null;
            $this->aGenreRelatedByGenreId = null;
            $this->aGenreRelatedBySubgenreId = null;
            $this->aDwdsgenreRelatedByDwdsgenreId = null;
            $this->aDwdsgenreRelatedByDwdssubgenreId = null;
            $this->aDatespecification = null;
            $this->collAuthorWorks = null;

            $this->collWrits = null;

            $this->collAuthors = null;
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
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = WorkQuery::create()
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
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                WorkPeer::addInstanceToPool($this);
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

            if ($this->aStatus !== null) {
                if ($this->aStatus->isModified() || $this->aStatus->isNew()) {
                    $affectedRows += $this->aStatus->save($con);
                }
                $this->setStatus($this->aStatus);
            }

            if ($this->aGenreRelatedByGenreId !== null) {
                if ($this->aGenreRelatedByGenreId->isModified() || $this->aGenreRelatedByGenreId->isNew()) {
                    $affectedRows += $this->aGenreRelatedByGenreId->save($con);
                }
                $this->setGenreRelatedByGenreId($this->aGenreRelatedByGenreId);
            }

            if ($this->aGenreRelatedBySubgenreId !== null) {
                if ($this->aGenreRelatedBySubgenreId->isModified() || $this->aGenreRelatedBySubgenreId->isNew()) {
                    $affectedRows += $this->aGenreRelatedBySubgenreId->save($con);
                }
                $this->setGenreRelatedBySubgenreId($this->aGenreRelatedBySubgenreId);
            }

            if ($this->aDwdsgenreRelatedByDwdsgenreId !== null) {
                if ($this->aDwdsgenreRelatedByDwdsgenreId->isModified() || $this->aDwdsgenreRelatedByDwdsgenreId->isNew()) {
                    $affectedRows += $this->aDwdsgenreRelatedByDwdsgenreId->save($con);
                }
                $this->setDwdsgenreRelatedByDwdsgenreId($this->aDwdsgenreRelatedByDwdsgenreId);
            }

            if ($this->aDwdsgenreRelatedByDwdssubgenreId !== null) {
                if ($this->aDwdsgenreRelatedByDwdssubgenreId->isModified() || $this->aDwdsgenreRelatedByDwdssubgenreId->isNew()) {
                    $affectedRows += $this->aDwdsgenreRelatedByDwdssubgenreId->save($con);
                }
                $this->setDwdsgenreRelatedByDwdssubgenreId($this->aDwdsgenreRelatedByDwdssubgenreId);
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

            if ($this->authorsScheduledForDeletion !== null) {
                if (!$this->authorsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->authorsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    AuthorWorkQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->authorsScheduledForDeletion = null;
                }

                foreach ($this->getAuthors() as $author) {
                    if ($author->isModified()) {
                        $author->save($con);
                    }
                }
            }

            if ($this->authorWorksScheduledForDeletion !== null) {
                if (!$this->authorWorksScheduledForDeletion->isEmpty()) {
                    AuthorWorkQuery::create()
                        ->filterByPrimaryKeys($this->authorWorksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->authorWorksScheduledForDeletion = null;
                }
            }

            if ($this->collAuthorWorks !== null) {
                foreach ($this->collAuthorWorks as $referrerFK) {
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

        $this->modifiedColumns[] = WorkPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . WorkPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(WorkPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(WorkPeer::STATUS_ID)) {
            $modifiedColumns[':p' . $index++]  = '`STATUS_ID`';
        }
        if ($this->isColumnModified(WorkPeer::DATESPECIFICATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`DATESPECIFICATION_ID`';
        }
        if ($this->isColumnModified(WorkPeer::GENRE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`GENRE_ID`';
        }
        if ($this->isColumnModified(WorkPeer::SUBGENRE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`SUBGENRE_ID`';
        }
        if ($this->isColumnModified(WorkPeer::DWDSGENRE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`DWDSGENRE_ID`';
        }
        if ($this->isColumnModified(WorkPeer::DWDSSUBGENRE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`DWDSSUBGENRE_ID`';
        }
        if ($this->isColumnModified(WorkPeer::DOI)) {
            $modifiedColumns[':p' . $index++]  = '`DOI`';
        }
        if ($this->isColumnModified(WorkPeer::COMMENTS)) {
            $modifiedColumns[':p' . $index++]  = '`COMMENTS`';
        }
        if ($this->isColumnModified(WorkPeer::FORMAT)) {
            $modifiedColumns[':p' . $index++]  = '`FORMAT`';
        }
        if ($this->isColumnModified(WorkPeer::DIRECTORYNAME)) {
            $modifiedColumns[':p' . $index++]  = '`DIRECTORYNAME`';
        }

        $sql = sprintf(
            'INSERT INTO `work` (%s) VALUES (%s)',
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
                    case '`STATUS_ID`':
                        $stmt->bindValue($identifier, $this->status_id, PDO::PARAM_INT);
                        break;
                    case '`DATESPECIFICATION_ID`':
                        $stmt->bindValue($identifier, $this->datespecification_id, PDO::PARAM_INT);
                        break;
                    case '`GENRE_ID`':
                        $stmt->bindValue($identifier, $this->genre_id, PDO::PARAM_INT);
                        break;
                    case '`SUBGENRE_ID`':
                        $stmt->bindValue($identifier, $this->subgenre_id, PDO::PARAM_INT);
                        break;
                    case '`DWDSGENRE_ID`':
                        $stmt->bindValue($identifier, $this->dwdsgenre_id, PDO::PARAM_INT);
                        break;
                    case '`DWDSSUBGENRE_ID`':
                        $stmt->bindValue($identifier, $this->dwdssubgenre_id, PDO::PARAM_INT);
                        break;
                    case '`DOI`':
                        $stmt->bindValue($identifier, $this->doi, PDO::PARAM_STR);
                        break;
                    case '`COMMENTS`':
                        $stmt->bindValue($identifier, $this->comments, PDO::PARAM_STR);
                        break;
                    case '`FORMAT`':
                        $stmt->bindValue($identifier, $this->format, PDO::PARAM_STR);
                        break;
                    case '`DIRECTORYNAME`':
                        $stmt->bindValue($identifier, $this->directoryname, PDO::PARAM_STR);
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

            if ($this->aStatus !== null) {
                if (!$this->aStatus->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aStatus->getValidationFailures());
                }
            }

            if ($this->aGenreRelatedByGenreId !== null) {
                if (!$this->aGenreRelatedByGenreId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aGenreRelatedByGenreId->getValidationFailures());
                }
            }

            if ($this->aGenreRelatedBySubgenreId !== null) {
                if (!$this->aGenreRelatedBySubgenreId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aGenreRelatedBySubgenreId->getValidationFailures());
                }
            }

            if ($this->aDwdsgenreRelatedByDwdsgenreId !== null) {
                if (!$this->aDwdsgenreRelatedByDwdsgenreId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDwdsgenreRelatedByDwdsgenreId->getValidationFailures());
                }
            }

            if ($this->aDwdsgenreRelatedByDwdssubgenreId !== null) {
                if (!$this->aDwdsgenreRelatedByDwdssubgenreId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDwdsgenreRelatedByDwdssubgenreId->getValidationFailures());
                }
            }

            if ($this->aDatespecification !== null) {
                if (!$this->aDatespecification->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDatespecification->getValidationFailures());
                }
            }


            if (($retval = WorkPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collAuthorWorks !== null) {
                    foreach ($this->collAuthorWorks as $referrerFK) {
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
        $pos = WorkPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getStatusId();
                break;
            case 2:
                return $this->getDatespecificationId();
                break;
            case 3:
                return $this->getGenreId();
                break;
            case 4:
                return $this->getSubgenreId();
                break;
            case 5:
                return $this->getDwdsgenreId();
                break;
            case 6:
                return $this->getDwdssubgenreId();
                break;
            case 7:
                return $this->getDoi();
                break;
            case 8:
                return $this->getComments();
                break;
            case 9:
                return $this->getFormat();
                break;
            case 10:
                return $this->getDirectoryname();
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
        if (isset($alreadyDumpedObjects['Work'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Work'][$this->getPrimaryKey()] = true;
        $keys = WorkPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getStatusId(),
            $keys[2] => $this->getDatespecificationId(),
            $keys[3] => $this->getGenreId(),
            $keys[4] => $this->getSubgenreId(),
            $keys[5] => $this->getDwdsgenreId(),
            $keys[6] => $this->getDwdssubgenreId(),
            $keys[7] => $this->getDoi(),
            $keys[8] => $this->getComments(),
            $keys[9] => $this->getFormat(),
            $keys[10] => $this->getDirectoryname(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aStatus) {
                $result['Status'] = $this->aStatus->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aGenreRelatedByGenreId) {
                $result['GenreRelatedByGenreId'] = $this->aGenreRelatedByGenreId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aGenreRelatedBySubgenreId) {
                $result['GenreRelatedBySubgenreId'] = $this->aGenreRelatedBySubgenreId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDwdsgenreRelatedByDwdsgenreId) {
                $result['DwdsgenreRelatedByDwdsgenreId'] = $this->aDwdsgenreRelatedByDwdsgenreId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDwdsgenreRelatedByDwdssubgenreId) {
                $result['DwdsgenreRelatedByDwdssubgenreId'] = $this->aDwdsgenreRelatedByDwdssubgenreId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDatespecification) {
                $result['Datespecification'] = $this->aDatespecification->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collAuthorWorks) {
                $result['AuthorWorks'] = $this->collAuthorWorks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = WorkPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setStatusId($value);
                break;
            case 2:
                $this->setDatespecificationId($value);
                break;
            case 3:
                $this->setGenreId($value);
                break;
            case 4:
                $this->setSubgenreId($value);
                break;
            case 5:
                $this->setDwdsgenreId($value);
                break;
            case 6:
                $this->setDwdssubgenreId($value);
                break;
            case 7:
                $this->setDoi($value);
                break;
            case 8:
                $this->setComments($value);
                break;
            case 9:
                $this->setFormat($value);
                break;
            case 10:
                $this->setDirectoryname($value);
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
        $keys = WorkPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setStatusId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDatespecificationId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setGenreId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setSubgenreId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setDwdsgenreId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setDwdssubgenreId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setDoi($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setComments($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setFormat($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setDirectoryname($arr[$keys[10]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(WorkPeer::DATABASE_NAME);

        if ($this->isColumnModified(WorkPeer::ID)) $criteria->add(WorkPeer::ID, $this->id);
        if ($this->isColumnModified(WorkPeer::STATUS_ID)) $criteria->add(WorkPeer::STATUS_ID, $this->status_id);
        if ($this->isColumnModified(WorkPeer::DATESPECIFICATION_ID)) $criteria->add(WorkPeer::DATESPECIFICATION_ID, $this->datespecification_id);
        if ($this->isColumnModified(WorkPeer::GENRE_ID)) $criteria->add(WorkPeer::GENRE_ID, $this->genre_id);
        if ($this->isColumnModified(WorkPeer::SUBGENRE_ID)) $criteria->add(WorkPeer::SUBGENRE_ID, $this->subgenre_id);
        if ($this->isColumnModified(WorkPeer::DWDSGENRE_ID)) $criteria->add(WorkPeer::DWDSGENRE_ID, $this->dwdsgenre_id);
        if ($this->isColumnModified(WorkPeer::DWDSSUBGENRE_ID)) $criteria->add(WorkPeer::DWDSSUBGENRE_ID, $this->dwdssubgenre_id);
        if ($this->isColumnModified(WorkPeer::DOI)) $criteria->add(WorkPeer::DOI, $this->doi);
        if ($this->isColumnModified(WorkPeer::COMMENTS)) $criteria->add(WorkPeer::COMMENTS, $this->comments);
        if ($this->isColumnModified(WorkPeer::FORMAT)) $criteria->add(WorkPeer::FORMAT, $this->format);
        if ($this->isColumnModified(WorkPeer::DIRECTORYNAME)) $criteria->add(WorkPeer::DIRECTORYNAME, $this->directoryname);

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
        $criteria = new Criteria(WorkPeer::DATABASE_NAME);
        $criteria->add(WorkPeer::ID, $this->id);

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
     * @param object $copyObj An object of Work (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setStatusId($this->getStatusId());
        $copyObj->setDatespecificationId($this->getDatespecificationId());
        $copyObj->setGenreId($this->getGenreId());
        $copyObj->setSubgenreId($this->getSubgenreId());
        $copyObj->setDwdsgenreId($this->getDwdsgenreId());
        $copyObj->setDwdssubgenreId($this->getDwdssubgenreId());
        $copyObj->setDoi($this->getDoi());
        $copyObj->setComments($this->getComments());
        $copyObj->setFormat($this->getFormat());
        $copyObj->setDirectoryname($this->getDirectoryname());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getAuthorWorks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAuthorWork($relObj->copy($deepCopy));
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
     * @return Work Clone of current object.
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
     * @return WorkPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new WorkPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Status object.
     *
     * @param             Status $v
     * @return Work The current object (for fluent API support)
     * @throws PropelException
     */
    public function setStatus(Status $v = null)
    {
        if ($v === null) {
            $this->setStatusId(NULL);
        } else {
            $this->setStatusId($v->getId());
        }

        $this->aStatus = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Status object, it will not be re-added.
        if ($v !== null) {
            $v->addWork($this);
        }


        return $this;
    }


    /**
     * Get the associated Status object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Status The associated Status object.
     * @throws PropelException
     */
    public function getStatus(PropelPDO $con = null)
    {
        if ($this->aStatus === null && ($this->status_id !== null)) {
            $this->aStatus = StatusQuery::create()->findPk($this->status_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aStatus->addWorks($this);
             */
        }

        return $this->aStatus;
    }

    /**
     * Declares an association between this object and a Genre object.
     *
     * @param             Genre $v
     * @return Work The current object (for fluent API support)
     * @throws PropelException
     */
    public function setGenreRelatedByGenreId(Genre $v = null)
    {
        if ($v === null) {
            $this->setGenreId(NULL);
        } else {
            $this->setGenreId($v->getId());
        }

        $this->aGenreRelatedByGenreId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Genre object, it will not be re-added.
        if ($v !== null) {
            $v->addWorkRelatedByGenreId($this);
        }


        return $this;
    }


    /**
     * Get the associated Genre object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Genre The associated Genre object.
     * @throws PropelException
     */
    public function getGenreRelatedByGenreId(PropelPDO $con = null)
    {
        if ($this->aGenreRelatedByGenreId === null && ($this->genre_id !== null)) {
            $this->aGenreRelatedByGenreId = GenreQuery::create()->findPk($this->genre_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aGenreRelatedByGenreId->addWorksRelatedByGenreId($this);
             */
        }

        return $this->aGenreRelatedByGenreId;
    }

    /**
     * Declares an association between this object and a Genre object.
     *
     * @param             Genre $v
     * @return Work The current object (for fluent API support)
     * @throws PropelException
     */
    public function setGenreRelatedBySubgenreId(Genre $v = null)
    {
        if ($v === null) {
            $this->setSubgenreId(NULL);
        } else {
            $this->setSubgenreId($v->getId());
        }

        $this->aGenreRelatedBySubgenreId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Genre object, it will not be re-added.
        if ($v !== null) {
            $v->addWorkRelatedBySubgenreId($this);
        }


        return $this;
    }


    /**
     * Get the associated Genre object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Genre The associated Genre object.
     * @throws PropelException
     */
    public function getGenreRelatedBySubgenreId(PropelPDO $con = null)
    {
        if ($this->aGenreRelatedBySubgenreId === null && ($this->subgenre_id !== null)) {
            $this->aGenreRelatedBySubgenreId = GenreQuery::create()->findPk($this->subgenre_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aGenreRelatedBySubgenreId->addWorksRelatedBySubgenreId($this);
             */
        }

        return $this->aGenreRelatedBySubgenreId;
    }

    /**
     * Declares an association between this object and a Dwdsgenre object.
     *
     * @param             Dwdsgenre $v
     * @return Work The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDwdsgenreRelatedByDwdsgenreId(Dwdsgenre $v = null)
    {
        if ($v === null) {
            $this->setDwdsgenreId(NULL);
        } else {
            $this->setDwdsgenreId($v->getId());
        }

        $this->aDwdsgenreRelatedByDwdsgenreId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Dwdsgenre object, it will not be re-added.
        if ($v !== null) {
            $v->addWorkRelatedByDwdsgenreId($this);
        }


        return $this;
    }


    /**
     * Get the associated Dwdsgenre object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Dwdsgenre The associated Dwdsgenre object.
     * @throws PropelException
     */
    public function getDwdsgenreRelatedByDwdsgenreId(PropelPDO $con = null)
    {
        if ($this->aDwdsgenreRelatedByDwdsgenreId === null && ($this->dwdsgenre_id !== null)) {
            $this->aDwdsgenreRelatedByDwdsgenreId = DwdsgenreQuery::create()->findPk($this->dwdsgenre_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDwdsgenreRelatedByDwdsgenreId->addWorksRelatedByDwdsgenreId($this);
             */
        }

        return $this->aDwdsgenreRelatedByDwdsgenreId;
    }

    /**
     * Declares an association between this object and a Dwdsgenre object.
     *
     * @param             Dwdsgenre $v
     * @return Work The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDwdsgenreRelatedByDwdssubgenreId(Dwdsgenre $v = null)
    {
        if ($v === null) {
            $this->setDwdssubgenreId(NULL);
        } else {
            $this->setDwdssubgenreId($v->getId());
        }

        $this->aDwdsgenreRelatedByDwdssubgenreId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Dwdsgenre object, it will not be re-added.
        if ($v !== null) {
            $v->addWorkRelatedByDwdssubgenreId($this);
        }


        return $this;
    }


    /**
     * Get the associated Dwdsgenre object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Dwdsgenre The associated Dwdsgenre object.
     * @throws PropelException
     */
    public function getDwdsgenreRelatedByDwdssubgenreId(PropelPDO $con = null)
    {
        if ($this->aDwdsgenreRelatedByDwdssubgenreId === null && ($this->dwdssubgenre_id !== null)) {
            $this->aDwdsgenreRelatedByDwdssubgenreId = DwdsgenreQuery::create()->findPk($this->dwdssubgenre_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDwdsgenreRelatedByDwdssubgenreId->addWorksRelatedByDwdssubgenreId($this);
             */
        }

        return $this->aDwdsgenreRelatedByDwdssubgenreId;
    }

    /**
     * Declares an association between this object and a Datespecification object.
     *
     * @param             Datespecification $v
     * @return Work The current object (for fluent API support)
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
            $v->addWork($this);
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
                $this->aDatespecification->addWorks($this);
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
        if ('AuthorWork' == $relationName) {
            $this->initAuthorWorks();
        }
        if ('Writ' == $relationName) {
            $this->initWrits();
        }
    }

    /**
     * Clears out the collAuthorWorks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Work The current object (for fluent API support)
     * @see        addAuthorWorks()
     */
    public function clearAuthorWorks()
    {
        $this->collAuthorWorks = null; // important to set this to null since that means it is uninitialized
        $this->collAuthorWorksPartial = null;

        return $this;
    }

    /**
     * reset is the collAuthorWorks collection loaded partially
     *
     * @return void
     */
    public function resetPartialAuthorWorks($v = true)
    {
        $this->collAuthorWorksPartial = $v;
    }

    /**
     * Initializes the collAuthorWorks collection.
     *
     * By default this just sets the collAuthorWorks collection to an empty array (like clearcollAuthorWorks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAuthorWorks($overrideExisting = true)
    {
        if (null !== $this->collAuthorWorks && !$overrideExisting) {
            return;
        }
        $this->collAuthorWorks = new PropelObjectCollection();
        $this->collAuthorWorks->setModel('AuthorWork');
    }

    /**
     * Gets an array of AuthorWork objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Work is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|AuthorWork[] List of AuthorWork objects
     * @throws PropelException
     */
    public function getAuthorWorks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collAuthorWorksPartial && !$this->isNew();
        if (null === $this->collAuthorWorks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAuthorWorks) {
                // return empty collection
                $this->initAuthorWorks();
            } else {
                $collAuthorWorks = AuthorWorkQuery::create(null, $criteria)
                    ->filterByWork($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collAuthorWorksPartial && count($collAuthorWorks)) {
                      $this->initAuthorWorks(false);

                      foreach($collAuthorWorks as $obj) {
                        if (false == $this->collAuthorWorks->contains($obj)) {
                          $this->collAuthorWorks->append($obj);
                        }
                      }

                      $this->collAuthorWorksPartial = true;
                    }

                    return $collAuthorWorks;
                }

                if($partial && $this->collAuthorWorks) {
                    foreach($this->collAuthorWorks as $obj) {
                        if($obj->isNew()) {
                            $collAuthorWorks[] = $obj;
                        }
                    }
                }

                $this->collAuthorWorks = $collAuthorWorks;
                $this->collAuthorWorksPartial = false;
            }
        }

        return $this->collAuthorWorks;
    }

    /**
     * Sets a collection of AuthorWork objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $authorWorks A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Work The current object (for fluent API support)
     */
    public function setAuthorWorks(PropelCollection $authorWorks, PropelPDO $con = null)
    {
        $this->authorWorksScheduledForDeletion = $this->getAuthorWorks(new Criteria(), $con)->diff($authorWorks);

        foreach ($this->authorWorksScheduledForDeletion as $authorWorkRemoved) {
            $authorWorkRemoved->setWork(null);
        }

        $this->collAuthorWorks = null;
        foreach ($authorWorks as $authorWork) {
            $this->addAuthorWork($authorWork);
        }

        $this->collAuthorWorks = $authorWorks;
        $this->collAuthorWorksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related AuthorWork objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related AuthorWork objects.
     * @throws PropelException
     */
    public function countAuthorWorks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collAuthorWorksPartial && !$this->isNew();
        if (null === $this->collAuthorWorks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAuthorWorks) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getAuthorWorks());
                }
                $query = AuthorWorkQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByWork($this)
                    ->count($con);
            }
        } else {
            return count($this->collAuthorWorks);
        }
    }

    /**
     * Method called to associate a AuthorWork object to this object
     * through the AuthorWork foreign key attribute.
     *
     * @param    AuthorWork $l AuthorWork
     * @return Work The current object (for fluent API support)
     */
    public function addAuthorWork(AuthorWork $l)
    {
        if ($this->collAuthorWorks === null) {
            $this->initAuthorWorks();
            $this->collAuthorWorksPartial = true;
        }
        if (!in_array($l, $this->collAuthorWorks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddAuthorWork($l);
        }

        return $this;
    }

    /**
     * @param	AuthorWork $authorWork The authorWork object to add.
     */
    protected function doAddAuthorWork($authorWork)
    {
        $this->collAuthorWorks[]= $authorWork;
        $authorWork->setWork($this);
    }

    /**
     * @param	AuthorWork $authorWork The authorWork object to remove.
     * @return Work The current object (for fluent API support)
     */
    public function removeAuthorWork($authorWork)
    {
        if ($this->getAuthorWorks()->contains($authorWork)) {
            $this->collAuthorWorks->remove($this->collAuthorWorks->search($authorWork));
            if (null === $this->authorWorksScheduledForDeletion) {
                $this->authorWorksScheduledForDeletion = clone $this->collAuthorWorks;
                $this->authorWorksScheduledForDeletion->clear();
            }
            $this->authorWorksScheduledForDeletion[]= $authorWork;
            $authorWork->setWork(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related AuthorWorks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|AuthorWork[] List of AuthorWork objects
     */
    public function getAuthorWorksJoinAuthor($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = AuthorWorkQuery::create(null, $criteria);
        $query->joinWith('Author', $join_behavior);

        return $this->getAuthorWorks($query, $con);
    }

    /**
     * Clears out the collWrits collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Work The current object (for fluent API support)
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
     * If this Work is new, it will return
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
                    ->filterByWork($this)
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
     * @return Work The current object (for fluent API support)
     */
    public function setWrits(PropelCollection $writs, PropelPDO $con = null)
    {
        $this->writsScheduledForDeletion = $this->getWrits(new Criteria(), $con)->diff($writs);

        foreach ($this->writsScheduledForDeletion as $writRemoved) {
            $writRemoved->setWork(null);
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
                    ->filterByWork($this)
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
     * @return Work The current object (for fluent API support)
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
        $writ->setWork($this);
    }

    /**
     * @param	Writ $writ The writ object to remove.
     * @return Work The current object (for fluent API support)
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
            $writ->setWork(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related Writs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
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
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related Writs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
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
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related Writs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
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
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related Writs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Writ[] List of Writ objects
     */
    public function getWritsJoinPublication($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WritQuery::create(null, $criteria);
        $query->joinWith('Publication', $join_behavior);

        return $this->getWrits($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related Writs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
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
     * Clears out the collAuthors collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Work The current object (for fluent API support)
     * @see        addAuthors()
     */
    public function clearAuthors()
    {
        $this->collAuthors = null; // important to set this to null since that means it is uninitialized
        $this->collAuthorsPartial = null;

        return $this;
    }

    /**
     * Initializes the collAuthors collection.
     *
     * By default this just sets the collAuthors collection to an empty collection (like clearAuthors());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initAuthors()
    {
        $this->collAuthors = new PropelObjectCollection();
        $this->collAuthors->setModel('Author');
    }

    /**
     * Gets a collection of Author objects related by a many-to-many relationship
     * to the current object by way of the author_work cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Work is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Author[] List of Author objects
     */
    public function getAuthors($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collAuthors || null !== $criteria) {
            if ($this->isNew() && null === $this->collAuthors) {
                // return empty collection
                $this->initAuthors();
            } else {
                $collAuthors = AuthorQuery::create(null, $criteria)
                    ->filterByWork($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collAuthors;
                }
                $this->collAuthors = $collAuthors;
            }
        }

        return $this->collAuthors;
    }

    /**
     * Sets a collection of Author objects related by a many-to-many relationship
     * to the current object by way of the author_work cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $authors A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Work The current object (for fluent API support)
     */
    public function setAuthors(PropelCollection $authors, PropelPDO $con = null)
    {
        $this->clearAuthors();
        $currentAuthors = $this->getAuthors();

        $this->authorsScheduledForDeletion = $currentAuthors->diff($authors);

        foreach ($authors as $author) {
            if (!$currentAuthors->contains($author)) {
                $this->doAddAuthor($author);
            }
        }

        $this->collAuthors = $authors;

        return $this;
    }

    /**
     * Gets the number of Author objects related by a many-to-many relationship
     * to the current object by way of the author_work cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Author objects
     */
    public function countAuthors($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collAuthors || null !== $criteria) {
            if ($this->isNew() && null === $this->collAuthors) {
                return 0;
            } else {
                $query = AuthorQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByWork($this)
                    ->count($con);
            }
        } else {
            return count($this->collAuthors);
        }
    }

    /**
     * Associate a Author object to this object
     * through the author_work cross reference table.
     *
     * @param  Author $author The AuthorWork object to relate
     * @return Work The current object (for fluent API support)
     */
    public function addAuthor(Author $author)
    {
        if ($this->collAuthors === null) {
            $this->initAuthors();
        }
        if (!$this->collAuthors->contains($author)) { // only add it if the **same** object is not already associated
            $this->doAddAuthor($author);

            $this->collAuthors[]= $author;
        }

        return $this;
    }

    /**
     * @param	Author $author The author object to add.
     */
    protected function doAddAuthor($author)
    {
        $authorWork = new AuthorWork();
        $authorWork->setAuthor($author);
        $this->addAuthorWork($authorWork);
    }

    /**
     * Remove a Author object to this object
     * through the author_work cross reference table.
     *
     * @param Author $author The AuthorWork object to relate
     * @return Work The current object (for fluent API support)
     */
    public function removeAuthor(Author $author)
    {
        if ($this->getAuthors()->contains($author)) {
            $this->collAuthors->remove($this->collAuthors->search($author));
            if (null === $this->authorsScheduledForDeletion) {
                $this->authorsScheduledForDeletion = clone $this->collAuthors;
                $this->authorsScheduledForDeletion->clear();
            }
            $this->authorsScheduledForDeletion[]= $author;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->status_id = null;
        $this->datespecification_id = null;
        $this->genre_id = null;
        $this->subgenre_id = null;
        $this->dwdsgenre_id = null;
        $this->dwdssubgenre_id = null;
        $this->doi = null;
        $this->comments = null;
        $this->format = null;
        $this->directoryname = null;
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
            if ($this->collAuthorWorks) {
                foreach ($this->collAuthorWorks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWrits) {
                foreach ($this->collWrits as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAuthors) {
                foreach ($this->collAuthors as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collAuthorWorks instanceof PropelCollection) {
            $this->collAuthorWorks->clearIterator();
        }
        $this->collAuthorWorks = null;
        if ($this->collWrits instanceof PropelCollection) {
            $this->collWrits->clearIterator();
        }
        $this->collWrits = null;
        if ($this->collAuthors instanceof PropelCollection) {
            $this->collAuthors->clearIterator();
        }
        $this->collAuthors = null;
        $this->aStatus = null;
        $this->aGenreRelatedByGenreId = null;
        $this->aGenreRelatedBySubgenreId = null;
        $this->aDwdsgenreRelatedByDwdsgenreId = null;
        $this->aDwdsgenreRelatedByDwdssubgenreId = null;
        $this->aDatespecification = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(WorkPeer::DEFAULT_STRING_FORMAT);
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
