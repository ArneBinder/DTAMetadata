<?php

namespace DTA\MetadataBundle\Model\Data\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use DTA\MetadataBundle\Model\Data\PublicationDs;
use DTA\MetadataBundle\Model\Data\PublicationDsQuery;
use DTA\MetadataBundle\Model\Data\PublicationMms;
use DTA\MetadataBundle\Model\Data\PublicationMmsQuery;
use DTA\MetadataBundle\Model\Data\PublicationMs;
use DTA\MetadataBundle\Model\Data\PublicationMsQuery;
use DTA\MetadataBundle\Model\Data\Series;
use DTA\MetadataBundle\Model\Data\SeriesPeer;
use DTA\MetadataBundle\Model\Data\SeriesQuery;
use DTA\MetadataBundle\Model\Data\Title;
use DTA\MetadataBundle\Model\Data\TitleQuery;

abstract class BaseSeries extends BaseObject implements Persistent, \DTA\MetadataBundle\Model\table_row_view\TableRowViewInterface
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\Data\\SeriesPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        SeriesPeer
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
     * The value for the title_id field.
     * @var        int
     */
    protected $title_id;

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
     * @var        PropelObjectCollection|PublicationMms[] Collection to store aggregation of PublicationMms objects.
     */
    protected $collPublicationMmss;
    protected $collPublicationMmssPartial;

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
    public static $tableRowViewCaptions = array('Id', 'TitleId', 'CreatedAt', 'UpdatedAt', );	public   $tableRowViewAccessors = array('Id'=>'Id', 'TitleId'=>'TitleId', 'CreatedAt'=>'CreatedAt', 'UpdatedAt'=>'UpdatedAt', );	public static $queryConstructionString = NULL;
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
    protected $publicationMmssScheduledForDeletion = null;

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
     * Set the value of [title_id] column.
     *
     * @param  int $v new value
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
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Series The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = SeriesPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Series The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = SeriesPeer::UPDATED_AT;
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
            $this->title_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->created_at = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->updated_at = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 4; // 4 = SeriesPeer::NUM_HYDRATE_COLUMNS.

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

            $this->aTitle = null;
            $this->collPublicationDss = null;

            $this->collPublicationMss = null;

            $this->collPublicationMmss = null;

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
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(SeriesPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(SeriesPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(SeriesPeer::UPDATED_AT)) {
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
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aTitle !== null) {
                if ($this->aTitle->isModified() || $this->aTitle->isNew()) {
                    $affectedRows += $this->aTitle->save($con);
                }
                $this->setTitle($this->aTitle);
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
        if (null === $this->id) {
            try {
                $stmt = $con->query("SELECT nextval('series_id_seq')");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $this->id = $row[0];
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SeriesPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '"id"';
        }
        if ($this->isColumnModified(SeriesPeer::TITLE_ID)) {
            $modifiedColumns[':p' . $index++]  = '"title_id"';
        }
        if ($this->isColumnModified(SeriesPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '"created_at"';
        }
        if ($this->isColumnModified(SeriesPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '"updated_at"';
        }

        $sql = sprintf(
            'INSERT INTO "series" (%s) VALUES (%s)',
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


            if (($retval = SeriesPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
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

                if ($this->collPublicationMmss !== null) {
                    foreach ($this->collPublicationMmss as $referrerFK) {
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
                return $this->getTitleId();
                break;
            case 2:
                return $this->getCreatedAt();
                break;
            case 3:
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
        if (isset($alreadyDumpedObjects['Series'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Series'][$this->getPrimaryKey()] = true;
        $keys = SeriesPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitleId(),
            $keys[2] => $this->getCreatedAt(),
            $keys[3] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aTitle) {
                $result['Title'] = $this->aTitle->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPublicationDss) {
                $result['PublicationDss'] = $this->collPublicationDss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationMss) {
                $result['PublicationMss'] = $this->collPublicationMss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationMmss) {
                $result['PublicationMmss'] = $this->collPublicationMmss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $this->setTitleId($value);
                break;
            case 2:
                $this->setCreatedAt($value);
                break;
            case 3:
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
        $keys = SeriesPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setTitleId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setCreatedAt($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setUpdatedAt($arr[$keys[3]]);
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
        if ($this->isColumnModified(SeriesPeer::TITLE_ID)) $criteria->add(SeriesPeer::TITLE_ID, $this->title_id);
        if ($this->isColumnModified(SeriesPeer::CREATED_AT)) $criteria->add(SeriesPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(SeriesPeer::UPDATED_AT)) $criteria->add(SeriesPeer::UPDATED_AT, $this->updated_at);

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
        $copyObj->setTitleId($this->getTitleId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

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

            foreach ($this->getPublicationMmss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationMms($relObj->copy($deepCopy));
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
     * Declares an association between this object and a Title object.
     *
     * @param                  Title $v
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('PublicationDs' == $relationName) {
            $this->initPublicationDss();
        }
        if ('PublicationMs' == $relationName) {
            $this->initPublicationMss();
        }
        if ('PublicationMms' == $relationName) {
            $this->initPublicationMmss();
        }
    }

    /**
     * Clears out the collPublicationDss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Series The current object (for fluent API support)
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
     * If this Series is new, it will return
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
                    ->filterBySeries($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationDssPartial && count($collPublicationDss)) {
                      $this->initPublicationDss(false);

                      foreach ($collPublicationDss as $obj) {
                        if (false == $this->collPublicationDss->contains($obj)) {
                          $this->collPublicationDss->append($obj);
                        }
                      }

                      $this->collPublicationDssPartial = true;
                    }

                    $collPublicationDss->getInternalIterator()->rewind();

                    return $collPublicationDss;
                }

                if ($partial && $this->collPublicationDss) {
                    foreach ($this->collPublicationDss as $obj) {
                        if ($obj->isNew()) {
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
     * @return Series The current object (for fluent API support)
     */
    public function setPublicationDss(PropelCollection $publicationDss, PropelPDO $con = null)
    {
        $publicationDssToDelete = $this->getPublicationDss(new Criteria(), $con)->diff($publicationDss);


        $this->publicationDssScheduledForDeletion = $publicationDssToDelete;

        foreach ($publicationDssToDelete as $publicationDsRemoved) {
            $publicationDsRemoved->setSeries(null);
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

            if ($partial && !$criteria) {
                return count($this->getPublicationDss());
            }
            $query = PublicationDsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySeries($this)
                ->count($con);
        }

        return count($this->collPublicationDss);
    }

    /**
     * Method called to associate a PublicationDs object to this object
     * through the PublicationDs foreign key attribute.
     *
     * @param    PublicationDs $l PublicationDs
     * @return Series The current object (for fluent API support)
     */
    public function addPublicationDs(PublicationDs $l)
    {
        if ($this->collPublicationDss === null) {
            $this->initPublicationDss();
            $this->collPublicationDssPartial = true;
        }

        if (!in_array($l, $this->collPublicationDss->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationDs($l);

            if ($this->publicationDssScheduledForDeletion and $this->publicationDssScheduledForDeletion->contains($l)) {
                $this->publicationDssScheduledForDeletion->remove($this->publicationDssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PublicationDs $publicationDs The publicationDs object to add.
     */
    protected function doAddPublicationDs($publicationDs)
    {
        $this->collPublicationDss[]= $publicationDs;
        $publicationDs->setSeries($this);
    }

    /**
     * @param	PublicationDs $publicationDs The publicationDs object to remove.
     * @return Series The current object (for fluent API support)
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
            $publicationDs->setSeries(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Series is new, it will return
     * an empty collection; or if this Series has previously
     * been saved, it will retrieve related PublicationDss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Series.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationDs[] List of PublicationDs objects
     */
    public function getPublicationDssJoinPublication($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationDsQuery::create(null, $criteria);
        $query->joinWith('Publication', $join_behavior);

        return $this->getPublicationDss($query, $con);
    }

    /**
     * Clears out the collPublicationMss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Series The current object (for fluent API support)
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
     * If this Series is new, it will return
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
                    ->filterBySeries($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationMssPartial && count($collPublicationMss)) {
                      $this->initPublicationMss(false);

                      foreach ($collPublicationMss as $obj) {
                        if (false == $this->collPublicationMss->contains($obj)) {
                          $this->collPublicationMss->append($obj);
                        }
                      }

                      $this->collPublicationMssPartial = true;
                    }

                    $collPublicationMss->getInternalIterator()->rewind();

                    return $collPublicationMss;
                }

                if ($partial && $this->collPublicationMss) {
                    foreach ($this->collPublicationMss as $obj) {
                        if ($obj->isNew()) {
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
     * @return Series The current object (for fluent API support)
     */
    public function setPublicationMss(PropelCollection $publicationMss, PropelPDO $con = null)
    {
        $publicationMssToDelete = $this->getPublicationMss(new Criteria(), $con)->diff($publicationMss);


        $this->publicationMssScheduledForDeletion = $publicationMssToDelete;

        foreach ($publicationMssToDelete as $publicationMsRemoved) {
            $publicationMsRemoved->setSeries(null);
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

            if ($partial && !$criteria) {
                return count($this->getPublicationMss());
            }
            $query = PublicationMsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySeries($this)
                ->count($con);
        }

        return count($this->collPublicationMss);
    }

    /**
     * Method called to associate a PublicationMs object to this object
     * through the PublicationMs foreign key attribute.
     *
     * @param    PublicationMs $l PublicationMs
     * @return Series The current object (for fluent API support)
     */
    public function addPublicationMs(PublicationMs $l)
    {
        if ($this->collPublicationMss === null) {
            $this->initPublicationMss();
            $this->collPublicationMssPartial = true;
        }

        if (!in_array($l, $this->collPublicationMss->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationMs($l);

            if ($this->publicationMssScheduledForDeletion and $this->publicationMssScheduledForDeletion->contains($l)) {
                $this->publicationMssScheduledForDeletion->remove($this->publicationMssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PublicationMs $publicationMs The publicationMs object to add.
     */
    protected function doAddPublicationMs($publicationMs)
    {
        $this->collPublicationMss[]= $publicationMs;
        $publicationMs->setSeries($this);
    }

    /**
     * @param	PublicationMs $publicationMs The publicationMs object to remove.
     * @return Series The current object (for fluent API support)
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
            $publicationMs->setSeries(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Series is new, it will return
     * an empty collection; or if this Series has previously
     * been saved, it will retrieve related PublicationMss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Series.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationMs[] List of PublicationMs objects
     */
    public function getPublicationMssJoinPublication($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationMsQuery::create(null, $criteria);
        $query->joinWith('Publication', $join_behavior);

        return $this->getPublicationMss($query, $con);
    }

    /**
     * Clears out the collPublicationMmss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Series The current object (for fluent API support)
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
     * If this Series is new, it will return
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
                    ->filterBySeries($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationMmssPartial && count($collPublicationMmss)) {
                      $this->initPublicationMmss(false);

                      foreach ($collPublicationMmss as $obj) {
                        if (false == $this->collPublicationMmss->contains($obj)) {
                          $this->collPublicationMmss->append($obj);
                        }
                      }

                      $this->collPublicationMmssPartial = true;
                    }

                    $collPublicationMmss->getInternalIterator()->rewind();

                    return $collPublicationMmss;
                }

                if ($partial && $this->collPublicationMmss) {
                    foreach ($this->collPublicationMmss as $obj) {
                        if ($obj->isNew()) {
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
     * @return Series The current object (for fluent API support)
     */
    public function setPublicationMmss(PropelCollection $publicationMmss, PropelPDO $con = null)
    {
        $publicationMmssToDelete = $this->getPublicationMmss(new Criteria(), $con)->diff($publicationMmss);


        $this->publicationMmssScheduledForDeletion = $publicationMmssToDelete;

        foreach ($publicationMmssToDelete as $publicationMmsRemoved) {
            $publicationMmsRemoved->setSeries(null);
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

            if ($partial && !$criteria) {
                return count($this->getPublicationMmss());
            }
            $query = PublicationMmsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySeries($this)
                ->count($con);
        }

        return count($this->collPublicationMmss);
    }

    /**
     * Method called to associate a PublicationMms object to this object
     * through the PublicationMms foreign key attribute.
     *
     * @param    PublicationMms $l PublicationMms
     * @return Series The current object (for fluent API support)
     */
    public function addPublicationMms(PublicationMms $l)
    {
        if ($this->collPublicationMmss === null) {
            $this->initPublicationMmss();
            $this->collPublicationMmssPartial = true;
        }

        if (!in_array($l, $this->collPublicationMmss->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationMms($l);

            if ($this->publicationMmssScheduledForDeletion and $this->publicationMmssScheduledForDeletion->contains($l)) {
                $this->publicationMmssScheduledForDeletion->remove($this->publicationMmssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PublicationMms $publicationMms The publicationMms object to add.
     */
    protected function doAddPublicationMms($publicationMms)
    {
        $this->collPublicationMmss[]= $publicationMms;
        $publicationMms->setSeries($this);
    }

    /**
     * @param	PublicationMms $publicationMms The publicationMms object to remove.
     * @return Series The current object (for fluent API support)
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
            $publicationMms->setSeries(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Series is new, it will return
     * an empty collection; or if this Series has previously
     * been saved, it will retrieve related PublicationMmss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Series.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationMms[] List of PublicationMms objects
     */
    public function getPublicationMmssJoinPublication($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationMmsQuery::create(null, $criteria);
        $query->joinWith('Publication', $join_behavior);

        return $this->getPublicationMmss($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->title_id = null;
        $this->created_at = null;
        $this->updated_at = null;
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
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
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
            if ($this->collPublicationMmss) {
                foreach ($this->collPublicationMmss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aTitle instanceof Persistent) {
              $this->aTitle->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPublicationDss instanceof PropelCollection) {
            $this->collPublicationDss->clearIterator();
        }
        $this->collPublicationDss = null;
        if ($this->collPublicationMss instanceof PropelCollection) {
            $this->collPublicationMss->clearIterator();
        }
        $this->collPublicationMss = null;
        if ($this->collPublicationMmss instanceof PropelCollection) {
            $this->collPublicationMmss->clearIterator();
        }
        $this->collPublicationMmss = null;
        $this->aTitle = null;
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

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     Series The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = SeriesPeer::UPDATED_AT;

        return $this;
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


}
