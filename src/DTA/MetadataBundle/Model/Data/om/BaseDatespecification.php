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
use DTA\MetadataBundle\Model\Data\DatespecificationPeer;
use DTA\MetadataBundle\Model\Data\DatespecificationQuery;
use DTA\MetadataBundle\Model\Data\Publication;
use DTA\MetadataBundle\Model\Data\PublicationQuery;

abstract class BaseDatespecification extends BaseObject implements Persistent, \DTA\MetadataBundle\Model\table_row_view\TableRowViewInterface, \DTA\MetadataBundle\Model\reconstructed_flaggable\ReconstructedFlaggableInterface
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\Data\\DatespecificationPeer';

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
    protected $collPublicationsRelatedByCreationdateId;
    protected $collPublicationsRelatedByCreationdateIdPartial;

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
    public static $tableRowViewCaptions = array('Id', 'Year', 'Comments', 'YearIsReconstructed', );	public   $tableRowViewAccessors = array('Id'=>'Id', 'Year'=>'Year', 'Comments'=>'Comments', 'YearIsReconstructed'=>'YearIsReconstructed', );
    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationsRelatedByPublicationdateIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationsRelatedByCreationdateIdScheduledForDeletion = null;

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

            $this->collPublicationsRelatedByCreationdateId = null;

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

            if ($this->publicationsRelatedByCreationdateIdScheduledForDeletion !== null) {
                if (!$this->publicationsRelatedByCreationdateIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->publicationsRelatedByCreationdateIdScheduledForDeletion as $publicationRelatedByCreationdateId) {
                        // need to save related object because we set the relation to null
                        $publicationRelatedByCreationdateId->save($con);
                    }
                    $this->publicationsRelatedByCreationdateIdScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationsRelatedByCreationdateId !== null) {
                foreach ($this->collPublicationsRelatedByCreationdateId as $referrerFK) {
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
        if (null === $this->id) {
            try {
                $stmt = $con->query("SELECT nextval('datespecification_id_seq')");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $this->id = $row[0];
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DatespecificationPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '"id"';
        }
        if ($this->isColumnModified(DatespecificationPeer::YEAR)) {
            $modifiedColumns[':p' . $index++]  = '"year"';
        }
        if ($this->isColumnModified(DatespecificationPeer::COMMENTS)) {
            $modifiedColumns[':p' . $index++]  = '"comments"';
        }
        if ($this->isColumnModified(DatespecificationPeer::YEAR_IS_RECONSTRUCTED)) {
            $modifiedColumns[':p' . $index++]  = '"year_is_reconstructed"';
        }

        $sql = sprintf(
            'INSERT INTO "datespecification" (%s) VALUES (%s)',
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
                    case '"year"':
                        $stmt->bindValue($identifier, $this->year, PDO::PARAM_INT);
                        break;
                    case '"comments"':
                        $stmt->bindValue($identifier, $this->comments, PDO::PARAM_STR);
                        break;
                    case '"year_is_reconstructed"':
                        $stmt->bindValue($identifier, $this->year_is_reconstructed, PDO::PARAM_BOOL);
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

                if ($this->collPublicationsRelatedByCreationdateId !== null) {
                    foreach ($this->collPublicationsRelatedByCreationdateId as $referrerFK) {
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
            if (null !== $this->collPublicationsRelatedByCreationdateId) {
                $result['PublicationsRelatedByCreationdateId'] = $this->collPublicationsRelatedByCreationdateId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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

            foreach ($this->getPublicationsRelatedByCreationdateId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationRelatedByCreationdateId($relObj->copy($deepCopy));
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
        if ('PublicationRelatedByCreationdateId' == $relationName) {
            $this->initPublicationsRelatedByCreationdateId();
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
    public function getPublicationsRelatedByPublicationdateIdJoinLastChangedByUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('LastChangedByUser', $join_behavior);

        return $this->getPublicationsRelatedByPublicationdateId($query, $con);
    }

    /**
     * Clears out the collPublicationsRelatedByCreationdateId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Datespecification The current object (for fluent API support)
     * @see        addPublicationsRelatedByCreationdateId()
     */
    public function clearPublicationsRelatedByCreationdateId()
    {
        $this->collPublicationsRelatedByCreationdateId = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationsRelatedByCreationdateIdPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationsRelatedByCreationdateId collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationsRelatedByCreationdateId($v = true)
    {
        $this->collPublicationsRelatedByCreationdateIdPartial = $v;
    }

    /**
     * Initializes the collPublicationsRelatedByCreationdateId collection.
     *
     * By default this just sets the collPublicationsRelatedByCreationdateId collection to an empty array (like clearcollPublicationsRelatedByCreationdateId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationsRelatedByCreationdateId($overrideExisting = true)
    {
        if (null !== $this->collPublicationsRelatedByCreationdateId && !$overrideExisting) {
            return;
        }
        $this->collPublicationsRelatedByCreationdateId = new PropelObjectCollection();
        $this->collPublicationsRelatedByCreationdateId->setModel('Publication');
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
    public function getPublicationsRelatedByCreationdateId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationsRelatedByCreationdateIdPartial && !$this->isNew();
        if (null === $this->collPublicationsRelatedByCreationdateId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationsRelatedByCreationdateId) {
                // return empty collection
                $this->initPublicationsRelatedByCreationdateId();
            } else {
                $collPublicationsRelatedByCreationdateId = PublicationQuery::create(null, $criteria)
                    ->filterByDatespecificationRelatedByCreationdateId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationsRelatedByCreationdateIdPartial && count($collPublicationsRelatedByCreationdateId)) {
                      $this->initPublicationsRelatedByCreationdateId(false);

                      foreach($collPublicationsRelatedByCreationdateId as $obj) {
                        if (false == $this->collPublicationsRelatedByCreationdateId->contains($obj)) {
                          $this->collPublicationsRelatedByCreationdateId->append($obj);
                        }
                      }

                      $this->collPublicationsRelatedByCreationdateIdPartial = true;
                    }

                    $collPublicationsRelatedByCreationdateId->getInternalIterator()->rewind();
                    return $collPublicationsRelatedByCreationdateId;
                }

                if($partial && $this->collPublicationsRelatedByCreationdateId) {
                    foreach($this->collPublicationsRelatedByCreationdateId as $obj) {
                        if($obj->isNew()) {
                            $collPublicationsRelatedByCreationdateId[] = $obj;
                        }
                    }
                }

                $this->collPublicationsRelatedByCreationdateId = $collPublicationsRelatedByCreationdateId;
                $this->collPublicationsRelatedByCreationdateIdPartial = false;
            }
        }

        return $this->collPublicationsRelatedByCreationdateId;
    }

    /**
     * Sets a collection of PublicationRelatedByCreationdateId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationsRelatedByCreationdateId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Datespecification The current object (for fluent API support)
     */
    public function setPublicationsRelatedByCreationdateId(PropelCollection $publicationsRelatedByCreationdateId, PropelPDO $con = null)
    {
        $publicationsRelatedByCreationdateIdToDelete = $this->getPublicationsRelatedByCreationdateId(new Criteria(), $con)->diff($publicationsRelatedByCreationdateId);

        $this->publicationsRelatedByCreationdateIdScheduledForDeletion = unserialize(serialize($publicationsRelatedByCreationdateIdToDelete));

        foreach ($publicationsRelatedByCreationdateIdToDelete as $publicationRelatedByCreationdateIdRemoved) {
            $publicationRelatedByCreationdateIdRemoved->setDatespecificationRelatedByCreationdateId(null);
        }

        $this->collPublicationsRelatedByCreationdateId = null;
        foreach ($publicationsRelatedByCreationdateId as $publicationRelatedByCreationdateId) {
            $this->addPublicationRelatedByCreationdateId($publicationRelatedByCreationdateId);
        }

        $this->collPublicationsRelatedByCreationdateId = $publicationsRelatedByCreationdateId;
        $this->collPublicationsRelatedByCreationdateIdPartial = false;

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
    public function countPublicationsRelatedByCreationdateId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationsRelatedByCreationdateIdPartial && !$this->isNew();
        if (null === $this->collPublicationsRelatedByCreationdateId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationsRelatedByCreationdateId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationsRelatedByCreationdateId());
            }
            $query = PublicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDatespecificationRelatedByCreationdateId($this)
                ->count($con);
        }

        return count($this->collPublicationsRelatedByCreationdateId);
    }

    /**
     * Method called to associate a Publication object to this object
     * through the Publication foreign key attribute.
     *
     * @param    Publication $l Publication
     * @return Datespecification The current object (for fluent API support)
     */
    public function addPublicationRelatedByCreationdateId(Publication $l)
    {
        if ($this->collPublicationsRelatedByCreationdateId === null) {
            $this->initPublicationsRelatedByCreationdateId();
            $this->collPublicationsRelatedByCreationdateIdPartial = true;
        }
        if (!in_array($l, $this->collPublicationsRelatedByCreationdateId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationRelatedByCreationdateId($l);
        }

        return $this;
    }

    /**
     * @param	PublicationRelatedByCreationdateId $publicationRelatedByCreationdateId The publicationRelatedByCreationdateId object to add.
     */
    protected function doAddPublicationRelatedByCreationdateId($publicationRelatedByCreationdateId)
    {
        $this->collPublicationsRelatedByCreationdateId[]= $publicationRelatedByCreationdateId;
        $publicationRelatedByCreationdateId->setDatespecificationRelatedByCreationdateId($this);
    }

    /**
     * @param	PublicationRelatedByCreationdateId $publicationRelatedByCreationdateId The publicationRelatedByCreationdateId object to remove.
     * @return Datespecification The current object (for fluent API support)
     */
    public function removePublicationRelatedByCreationdateId($publicationRelatedByCreationdateId)
    {
        if ($this->getPublicationsRelatedByCreationdateId()->contains($publicationRelatedByCreationdateId)) {
            $this->collPublicationsRelatedByCreationdateId->remove($this->collPublicationsRelatedByCreationdateId->search($publicationRelatedByCreationdateId));
            if (null === $this->publicationsRelatedByCreationdateIdScheduledForDeletion) {
                $this->publicationsRelatedByCreationdateIdScheduledForDeletion = clone $this->collPublicationsRelatedByCreationdateId;
                $this->publicationsRelatedByCreationdateIdScheduledForDeletion->clear();
            }
            $this->publicationsRelatedByCreationdateIdScheduledForDeletion[]= $publicationRelatedByCreationdateId;
            $publicationRelatedByCreationdateId->setDatespecificationRelatedByCreationdateId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByCreationdateId from storage.
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
    public function getPublicationsRelatedByCreationdateIdJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getPublicationsRelatedByCreationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByCreationdateId from storage.
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
    public function getPublicationsRelatedByCreationdateIdJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getPublicationsRelatedByCreationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByCreationdateId from storage.
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
    public function getPublicationsRelatedByCreationdateIdJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getPublicationsRelatedByCreationdateId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Datespecification is new, it will return
     * an empty collection; or if this Datespecification has previously
     * been saved, it will retrieve related PublicationsRelatedByCreationdateId from storage.
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
    public function getPublicationsRelatedByCreationdateIdJoinLastChangedByUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('LastChangedByUser', $join_behavior);

        return $this->getPublicationsRelatedByCreationdateId($query, $con);
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
            if ($this->collPublicationsRelatedByCreationdateId) {
                foreach ($this->collPublicationsRelatedByCreationdateId as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPublicationsRelatedByPublicationdateId instanceof PropelCollection) {
            $this->collPublicationsRelatedByPublicationdateId->clearIterator();
        }
        $this->collPublicationsRelatedByPublicationdateId = null;
        if ($this->collPublicationsRelatedByCreationdateId instanceof PropelCollection) {
            $this->collPublicationsRelatedByCreationdateId->clearIterator();
        }
        $this->collPublicationsRelatedByCreationdateId = null;
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
    * Returns the marked column value, e.g. in brackets to denote that the value is reconstructed.
    * e.g. getMarkedByName('name') return '[<name>]'.
    * @param $column the PHP name of the column as defined in the schema
    */
    public function getMarkedByName($column){
        if($this->isReconstructedByName($column))
            return '[' . $this->getByName($column) . ']';
        else
            return $this->getByName($column);
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


}
