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
use DTA\MetadataBundle\Model\Data\Publication;
use DTA\MetadataBundle\Model\Data\PublicationQuery;
use DTA\MetadataBundle\Model\Data\Title;
use DTA\MetadataBundle\Model\Data\TitlePeer;
use DTA\MetadataBundle\Model\Data\TitleQuery;
use DTA\MetadataBundle\Model\Data\Titlefragment;
use DTA\MetadataBundle\Model\Data\TitlefragmentQuery;
use DTA\MetadataBundle\Model\Master\SequenceEntry;
use DTA\MetadataBundle\Model\Master\SequenceEntryQuery;

abstract class BaseTitle extends BaseObject implements Persistent, \DTA\MetadataBundle\Model\table_row_view\TableRowViewInterface
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\Data\\TitlePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        TitlePeer
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
     * @var        PropelObjectCollection|Publication[] Collection to store aggregation of Publication objects.
     */
    protected $collPublications;
    protected $collPublicationsPartial;

    /**
     * @var        PropelObjectCollection|Titlefragment[] Collection to store aggregation of Titlefragment objects.
     */
    protected $collTitlefragments;
    protected $collTitlefragmentsPartial;

    /**
     * @var        PropelObjectCollection|SequenceEntry[] Collection to store aggregation of SequenceEntry objects.
     */
    protected $collSequenceEntries;
    protected $collSequenceEntriesPartial;

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
    public static $tableRowViewCaptions = array('titel', );	public   $tableRowViewAccessors = array('titel'=>'accessor:__toString', );	public static $queryConstructionString = NULL;
    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $titlefragmentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $sequenceEntriesScheduledForDeletion = null;

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
     * @return Title The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = TitlePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Title The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = TitlePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Title The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = TitlePeer::UPDATED_AT;
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
            $this->created_at = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->updated_at = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 3; // 3 = TitlePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Title object", $e);
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
            $con = Propel::getConnection(TitlePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = TitlePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collPublications = null;

            $this->collTitlefragments = null;

            $this->collSequenceEntries = null;

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
            $con = Propel::getConnection(TitlePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = TitleQuery::create()
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
            $con = Propel::getConnection(TitlePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(TitlePeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(TitlePeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(TitlePeer::UPDATED_AT)) {
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
                TitlePeer::addInstanceToPool($this);
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

            if ($this->publicationsScheduledForDeletion !== null) {
                if (!$this->publicationsScheduledForDeletion->isEmpty()) {
                    PublicationQuery::create()
                        ->filterByPrimaryKeys($this->publicationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationsScheduledForDeletion = null;
                }
            }

            if ($this->collPublications !== null) {
                foreach ($this->collPublications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->titlefragmentsScheduledForDeletion !== null) {
                if (!$this->titlefragmentsScheduledForDeletion->isEmpty()) {
                    TitlefragmentQuery::create()
                        ->filterByPrimaryKeys($this->titlefragmentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->titlefragmentsScheduledForDeletion = null;
                }
            }

            if ($this->collTitlefragments !== null) {
                foreach ($this->collTitlefragments as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->sequenceEntriesScheduledForDeletion !== null) {
                if (!$this->sequenceEntriesScheduledForDeletion->isEmpty()) {
                    SequenceEntryQuery::create()
                        ->filterByPrimaryKeys($this->sequenceEntriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->sequenceEntriesScheduledForDeletion = null;
                }
            }

            if ($this->collSequenceEntries !== null) {
                foreach ($this->collSequenceEntries as $referrerFK) {
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

        $this->modifiedColumns[] = TitlePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . TitlePeer::ID . ')');
        }
        if (null === $this->id) {
            try {
                $stmt = $con->query("SELECT nextval('title_id_seq')");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $this->id = $row[0];
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(TitlePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '"id"';
        }
        if ($this->isColumnModified(TitlePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '"created_at"';
        }
        if ($this->isColumnModified(TitlePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '"updated_at"';
        }

        $sql = sprintf(
            'INSERT INTO "title" (%s) VALUES (%s)',
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


            if (($retval = TitlePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collPublications !== null) {
                    foreach ($this->collPublications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collTitlefragments !== null) {
                    foreach ($this->collTitlefragments as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSequenceEntries !== null) {
                    foreach ($this->collSequenceEntries as $referrerFK) {
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
        $pos = TitlePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getCreatedAt();
                break;
            case 2:
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
        if (isset($alreadyDumpedObjects['Title'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Title'][$this->getPrimaryKey()] = true;
        $keys = TitlePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCreatedAt(),
            $keys[2] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collPublications) {
                $result['Publications'] = $this->collPublications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTitlefragments) {
                $result['Titlefragments'] = $this->collTitlefragments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSequenceEntries) {
                $result['SequenceEntries'] = $this->collSequenceEntries->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = TitlePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setCreatedAt($value);
                break;
            case 2:
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
        $keys = TitlePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setCreatedAt($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setUpdatedAt($arr[$keys[2]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(TitlePeer::DATABASE_NAME);

        if ($this->isColumnModified(TitlePeer::ID)) $criteria->add(TitlePeer::ID, $this->id);
        if ($this->isColumnModified(TitlePeer::CREATED_AT)) $criteria->add(TitlePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(TitlePeer::UPDATED_AT)) $criteria->add(TitlePeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(TitlePeer::DATABASE_NAME);
        $criteria->add(TitlePeer::ID, $this->id);

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
     * @param object $copyObj An object of Title (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPublications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublication($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTitlefragments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTitlefragment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSequenceEntries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSequenceEntry($relObj->copy($deepCopy));
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
     * @return Title Clone of current object.
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
     * @return TitlePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new TitlePeer();
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
        if ('Publication' == $relationName) {
            $this->initPublications();
        }
        if ('Titlefragment' == $relationName) {
            $this->initTitlefragments();
        }
        if ('SequenceEntry' == $relationName) {
            $this->initSequenceEntries();
        }
    }

    /**
     * Clears out the collPublications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Title The current object (for fluent API support)
     * @see        addPublications()
     */
    public function clearPublications()
    {
        $this->collPublications = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationsPartial = null;

        return $this;
    }

    /**
     * reset is the collPublications collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublications($v = true)
    {
        $this->collPublicationsPartial = $v;
    }

    /**
     * Initializes the collPublications collection.
     *
     * By default this just sets the collPublications collection to an empty array (like clearcollPublications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublications($overrideExisting = true)
    {
        if (null !== $this->collPublications && !$overrideExisting) {
            return;
        }
        $this->collPublications = new PropelObjectCollection();
        $this->collPublications->setModel('Publication');
    }

    /**
     * Gets an array of Publication objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Title is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Publication[] List of Publication objects
     * @throws PropelException
     */
    public function getPublications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationsPartial && !$this->isNew();
        if (null === $this->collPublications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublications) {
                // return empty collection
                $this->initPublications();
            } else {
                $collPublications = PublicationQuery::create(null, $criteria)
                    ->filterByTitle($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationsPartial && count($collPublications)) {
                      $this->initPublications(false);

                      foreach ($collPublications as $obj) {
                        if (false == $this->collPublications->contains($obj)) {
                          $this->collPublications->append($obj);
                        }
                      }

                      $this->collPublicationsPartial = true;
                    }

                    $collPublications->getInternalIterator()->rewind();

                    return $collPublications;
                }

                if ($partial && $this->collPublications) {
                    foreach ($this->collPublications as $obj) {
                        if ($obj->isNew()) {
                            $collPublications[] = $obj;
                        }
                    }
                }

                $this->collPublications = $collPublications;
                $this->collPublicationsPartial = false;
            }
        }

        return $this->collPublications;
    }

    /**
     * Sets a collection of Publication objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Title The current object (for fluent API support)
     */
    public function setPublications(PropelCollection $publications, PropelPDO $con = null)
    {
        $publicationsToDelete = $this->getPublications(new Criteria(), $con)->diff($publications);


        $this->publicationsScheduledForDeletion = $publicationsToDelete;

        foreach ($publicationsToDelete as $publicationRemoved) {
            $publicationRemoved->setTitle(null);
        }

        $this->collPublications = null;
        foreach ($publications as $publication) {
            $this->addPublication($publication);
        }

        $this->collPublications = $publications;
        $this->collPublicationsPartial = false;

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
    public function countPublications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationsPartial && !$this->isNew();
        if (null === $this->collPublications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPublications());
            }
            $query = PublicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTitle($this)
                ->count($con);
        }

        return count($this->collPublications);
    }

    /**
     * Method called to associate a Publication object to this object
     * through the Publication foreign key attribute.
     *
     * @param    Publication $l Publication
     * @return Title The current object (for fluent API support)
     */
    public function addPublication(Publication $l)
    {
        if ($this->collPublications === null) {
            $this->initPublications();
            $this->collPublicationsPartial = true;
        }

        if (!in_array($l, $this->collPublications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublication($l);

            if ($this->publicationsScheduledForDeletion and $this->publicationsScheduledForDeletion->contains($l)) {
                $this->publicationsScheduledForDeletion->remove($this->publicationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Publication $publication The publication object to add.
     */
    protected function doAddPublication($publication)
    {
        $this->collPublications[]= $publication;
        $publication->setTitle($this);
    }

    /**
     * @param	Publication $publication The publication object to remove.
     * @return Title The current object (for fluent API support)
     */
    public function removePublication($publication)
    {
        if ($this->getPublications()->contains($publication)) {
            $this->collPublications->remove($this->collPublications->search($publication));
            if (null === $this->publicationsScheduledForDeletion) {
                $this->publicationsScheduledForDeletion = clone $this->collPublications;
                $this->publicationsScheduledForDeletion->clear();
            }
            $this->publicationsScheduledForDeletion[]= clone $publication;
            $publication->setTitle(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Title is new, it will return
     * an empty collection; or if this Title has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Title.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinSource($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Source', $join_behavior);

        return $this->getPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Title is new, it will return
     * an empty collection; or if this Title has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Title.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Title is new, it will return
     * an empty collection; or if this Title has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Title.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Title is new, it will return
     * an empty collection; or if this Title has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Title.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinDatespecificationRelatedByPublicationdateId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('DatespecificationRelatedByPublicationdateId', $join_behavior);

        return $this->getPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Title is new, it will return
     * an empty collection; or if this Title has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Title.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinDatespecificationRelatedByCreationdateId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('DatespecificationRelatedByCreationdateId', $join_behavior);

        return $this->getPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Title is new, it will return
     * an empty collection; or if this Title has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Title.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinLastChangedByUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('LastChangedByUser', $join_behavior);

        return $this->getPublications($query, $con);
    }

    /**
     * Clears out the collTitlefragments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Title The current object (for fluent API support)
     * @see        addTitlefragments()
     */
    public function clearTitlefragments()
    {
        $this->collTitlefragments = null; // important to set this to null since that means it is uninitialized
        $this->collTitlefragmentsPartial = null;

        return $this;
    }

    /**
     * reset is the collTitlefragments collection loaded partially
     *
     * @return void
     */
    public function resetPartialTitlefragments($v = true)
    {
        $this->collTitlefragmentsPartial = $v;
    }

    /**
     * Initializes the collTitlefragments collection.
     *
     * By default this just sets the collTitlefragments collection to an empty array (like clearcollTitlefragments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTitlefragments($overrideExisting = true)
    {
        if (null !== $this->collTitlefragments && !$overrideExisting) {
            return;
        }
        $this->collTitlefragments = new PropelObjectCollection();
        $this->collTitlefragments->setModel('Titlefragment');
    }

    /**
     * Gets an array of Titlefragment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Title is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Titlefragment[] List of Titlefragment objects
     * @throws PropelException
     */
    public function getTitlefragments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collTitlefragmentsPartial && !$this->isNew();
        if (null === $this->collTitlefragments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTitlefragments) {
                // return empty collection
                $this->initTitlefragments();
            } else {
                $collTitlefragments = TitlefragmentQuery::create(null, $criteria)
                    ->filterByTitle($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collTitlefragmentsPartial && count($collTitlefragments)) {
                      $this->initTitlefragments(false);

                      foreach ($collTitlefragments as $obj) {
                        if (false == $this->collTitlefragments->contains($obj)) {
                          $this->collTitlefragments->append($obj);
                        }
                      }

                      $this->collTitlefragmentsPartial = true;
                    }

                    $collTitlefragments->getInternalIterator()->rewind();

                    return $collTitlefragments;
                }

                if ($partial && $this->collTitlefragments) {
                    foreach ($this->collTitlefragments as $obj) {
                        if ($obj->isNew()) {
                            $collTitlefragments[] = $obj;
                        }
                    }
                }

                $this->collTitlefragments = $collTitlefragments;
                $this->collTitlefragmentsPartial = false;
            }
        }

        return $this->collTitlefragments;
    }

    /**
     * Sets a collection of Titlefragment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $titlefragments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Title The current object (for fluent API support)
     */
    public function setTitlefragments(PropelCollection $titlefragments, PropelPDO $con = null)
    {
        $titlefragmentsToDelete = $this->getTitlefragments(new Criteria(), $con)->diff($titlefragments);


        $this->titlefragmentsScheduledForDeletion = $titlefragmentsToDelete;

        foreach ($titlefragmentsToDelete as $titlefragmentRemoved) {
            $titlefragmentRemoved->setTitle(null);
        }

        $this->collTitlefragments = null;
        foreach ($titlefragments as $titlefragment) {
            $this->addTitlefragment($titlefragment);
        }

        $this->collTitlefragments = $titlefragments;
        $this->collTitlefragmentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Titlefragment objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Titlefragment objects.
     * @throws PropelException
     */
    public function countTitlefragments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collTitlefragmentsPartial && !$this->isNew();
        if (null === $this->collTitlefragments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTitlefragments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTitlefragments());
            }
            $query = TitlefragmentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTitle($this)
                ->count($con);
        }

        return count($this->collTitlefragments);
    }

    /**
     * Method called to associate a Titlefragment object to this object
     * through the Titlefragment foreign key attribute.
     *
     * @param    Titlefragment $l Titlefragment
     * @return Title The current object (for fluent API support)
     */
    public function addTitlefragment(Titlefragment $l)
    {
        if ($this->collTitlefragments === null) {
            $this->initTitlefragments();
            $this->collTitlefragmentsPartial = true;
        }

        if (!in_array($l, $this->collTitlefragments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddTitlefragment($l);

            if ($this->titlefragmentsScheduledForDeletion and $this->titlefragmentsScheduledForDeletion->contains($l)) {
                $this->titlefragmentsScheduledForDeletion->remove($this->titlefragmentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Titlefragment $titlefragment The titlefragment object to add.
     */
    protected function doAddTitlefragment($titlefragment)
    {
        $this->collTitlefragments[]= $titlefragment;
        $titlefragment->setTitle($this);
    }

    /**
     * @param	Titlefragment $titlefragment The titlefragment object to remove.
     * @return Title The current object (for fluent API support)
     */
    public function removeTitlefragment($titlefragment)
    {
        if ($this->getTitlefragments()->contains($titlefragment)) {
            $this->collTitlefragments->remove($this->collTitlefragments->search($titlefragment));
            if (null === $this->titlefragmentsScheduledForDeletion) {
                $this->titlefragmentsScheduledForDeletion = clone $this->collTitlefragments;
                $this->titlefragmentsScheduledForDeletion->clear();
            }
            $this->titlefragmentsScheduledForDeletion[]= clone $titlefragment;
            $titlefragment->setTitle(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Title is new, it will return
     * an empty collection; or if this Title has previously
     * been saved, it will retrieve related Titlefragments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Title.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Titlefragment[] List of Titlefragment objects
     */
    public function getTitlefragmentsJoinTitlefragmenttype($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TitlefragmentQuery::create(null, $criteria);
        $query->joinWith('Titlefragmenttype', $join_behavior);

        return $this->getTitlefragments($query, $con);
    }

    /**
     * Clears out the collSequenceEntries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Title The current object (for fluent API support)
     * @see        addSequenceEntries()
     */
    public function clearSequenceEntries()
    {
        $this->collSequenceEntries = null; // important to set this to null since that means it is uninitialized
        $this->collSequenceEntriesPartial = null;

        return $this;
    }

    /**
     * reset is the collSequenceEntries collection loaded partially
     *
     * @return void
     */
    public function resetPartialSequenceEntries($v = true)
    {
        $this->collSequenceEntriesPartial = $v;
    }

    /**
     * Initializes the collSequenceEntries collection.
     *
     * By default this just sets the collSequenceEntries collection to an empty array (like clearcollSequenceEntries());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSequenceEntries($overrideExisting = true)
    {
        if (null !== $this->collSequenceEntries && !$overrideExisting) {
            return;
        }
        $this->collSequenceEntries = new PropelObjectCollection();
        $this->collSequenceEntries->setModel('SequenceEntry');
    }

    /**
     * Gets an array of SequenceEntry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Title is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SequenceEntry[] List of SequenceEntry objects
     * @throws PropelException
     */
    public function getSequenceEntries($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSequenceEntriesPartial && !$this->isNew();
        if (null === $this->collSequenceEntries || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSequenceEntries) {
                // return empty collection
                $this->initSequenceEntries();
            } else {
                $collSequenceEntries = SequenceEntryQuery::create(null, $criteria)
                    ->filterByTitle($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSequenceEntriesPartial && count($collSequenceEntries)) {
                      $this->initSequenceEntries(false);

                      foreach ($collSequenceEntries as $obj) {
                        if (false == $this->collSequenceEntries->contains($obj)) {
                          $this->collSequenceEntries->append($obj);
                        }
                      }

                      $this->collSequenceEntriesPartial = true;
                    }

                    $collSequenceEntries->getInternalIterator()->rewind();

                    return $collSequenceEntries;
                }

                if ($partial && $this->collSequenceEntries) {
                    foreach ($this->collSequenceEntries as $obj) {
                        if ($obj->isNew()) {
                            $collSequenceEntries[] = $obj;
                        }
                    }
                }

                $this->collSequenceEntries = $collSequenceEntries;
                $this->collSequenceEntriesPartial = false;
            }
        }

        return $this->collSequenceEntries;
    }

    /**
     * Sets a collection of SequenceEntry objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $sequenceEntries A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Title The current object (for fluent API support)
     */
    public function setSequenceEntries(PropelCollection $sequenceEntries, PropelPDO $con = null)
    {
        $sequenceEntriesToDelete = $this->getSequenceEntries(new Criteria(), $con)->diff($sequenceEntries);


        $this->sequenceEntriesScheduledForDeletion = $sequenceEntriesToDelete;

        foreach ($sequenceEntriesToDelete as $sequenceEntryRemoved) {
            $sequenceEntryRemoved->setTitle(null);
        }

        $this->collSequenceEntries = null;
        foreach ($sequenceEntries as $sequenceEntry) {
            $this->addSequenceEntry($sequenceEntry);
        }

        $this->collSequenceEntries = $sequenceEntries;
        $this->collSequenceEntriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SequenceEntry objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SequenceEntry objects.
     * @throws PropelException
     */
    public function countSequenceEntries(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSequenceEntriesPartial && !$this->isNew();
        if (null === $this->collSequenceEntries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSequenceEntries) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSequenceEntries());
            }
            $query = SequenceEntryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTitle($this)
                ->count($con);
        }

        return count($this->collSequenceEntries);
    }

    /**
     * Method called to associate a SequenceEntry object to this object
     * through the SequenceEntry foreign key attribute.
     *
     * @param    SequenceEntry $l SequenceEntry
     * @return Title The current object (for fluent API support)
     */
    public function addSequenceEntry(SequenceEntry $l)
    {
        if ($this->collSequenceEntries === null) {
            $this->initSequenceEntries();
            $this->collSequenceEntriesPartial = true;
        }

        if (!in_array($l, $this->collSequenceEntries->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSequenceEntry($l);

            if ($this->sequenceEntriesScheduledForDeletion and $this->sequenceEntriesScheduledForDeletion->contains($l)) {
                $this->sequenceEntriesScheduledForDeletion->remove($this->sequenceEntriesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	SequenceEntry $sequenceEntry The sequenceEntry object to add.
     */
    protected function doAddSequenceEntry($sequenceEntry)
    {
        $this->collSequenceEntries[]= $sequenceEntry;
        $sequenceEntry->setTitle($this);
    }

    /**
     * @param	SequenceEntry $sequenceEntry The sequenceEntry object to remove.
     * @return Title The current object (for fluent API support)
     */
    public function removeSequenceEntry($sequenceEntry)
    {
        if ($this->getSequenceEntries()->contains($sequenceEntry)) {
            $this->collSequenceEntries->remove($this->collSequenceEntries->search($sequenceEntry));
            if (null === $this->sequenceEntriesScheduledForDeletion) {
                $this->sequenceEntriesScheduledForDeletion = clone $this->collSequenceEntries;
                $this->sequenceEntriesScheduledForDeletion->clear();
            }
            $this->sequenceEntriesScheduledForDeletion[]= clone $sequenceEntry;
            $sequenceEntry->setTitle(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Title is new, it will return
     * an empty collection; or if this Title has previously
     * been saved, it will retrieve related SequenceEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Title.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SequenceEntry[] List of SequenceEntry objects
     */
    public function getSequenceEntriesJoinPublication($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SequenceEntryQuery::create(null, $criteria);
        $query->joinWith('Publication', $join_behavior);

        return $this->getSequenceEntries($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
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
            if ($this->collPublications) {
                foreach ($this->collPublications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTitlefragments) {
                foreach ($this->collTitlefragments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSequenceEntries) {
                foreach ($this->collSequenceEntries as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPublications instanceof PropelCollection) {
            $this->collPublications->clearIterator();
        }
        $this->collPublications = null;
        if ($this->collTitlefragments instanceof PropelCollection) {
            $this->collTitlefragments->clearIterator();
        }
        $this->collTitlefragments = null;
        if ($this->collSequenceEntries instanceof PropelCollection) {
            $this->collSequenceEntries->clearIterator();
        }
        $this->collSequenceEntries = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(TitlePeer::DEFAULT_STRING_FORMAT);
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


    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     Title The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = TitlePeer::UPDATED_AT;

        return $this;
    }

}
