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
use DTA\MetadataBundle\Model\Dwdsgenre;
use DTA\MetadataBundle\Model\DwdsgenrePeer;
use DTA\MetadataBundle\Model\DwdsgenreQuery;
use DTA\MetadataBundle\Model\Work;
use DTA\MetadataBundle\Model\WorkQuery;

abstract class BaseDwdsgenre extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\DwdsgenrePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        DwdsgenrePeer
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
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the childof field.
     * @var        int
     */
    protected $childof;

    /**
     * @var        Dwdsgenre
     */
    protected $aDwdsgenreRelatedByChildof;

    /**
     * @var        PropelObjectCollection|Dwdsgenre[] Collection to store aggregation of Dwdsgenre objects.
     */
    protected $collDwdsgenresRelatedById;
    protected $collDwdsgenresRelatedByIdPartial;

    /**
     * @var        PropelObjectCollection|Work[] Collection to store aggregation of Work objects.
     */
    protected $collWorksRelatedByDwdsgenreId;
    protected $collWorksRelatedByDwdsgenreIdPartial;

    /**
     * @var        PropelObjectCollection|Work[] Collection to store aggregation of Work objects.
     */
    protected $collWorksRelatedByDwdssubgenreId;
    protected $collWorksRelatedByDwdssubgenreIdPartial;

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
    protected $dwdsgenresRelatedByIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $worksRelatedByDwdsgenreIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $worksRelatedByDwdssubgenreIdScheduledForDeletion = null;

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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [childof] column value.
     *
     * @return int
     */
    public function getChildof()
    {
        return $this->childof;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Dwdsgenre The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = DwdsgenrePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return Dwdsgenre The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = DwdsgenrePeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [childof] column.
     *
     * @param int $v new value
     * @return Dwdsgenre The current object (for fluent API support)
     */
    public function setChildof($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->childof !== $v) {
            $this->childof = $v;
            $this->modifiedColumns[] = DwdsgenrePeer::CHILDOF;
        }

        if ($this->aDwdsgenreRelatedByChildof !== null && $this->aDwdsgenreRelatedByChildof->getId() !== $v) {
            $this->aDwdsgenreRelatedByChildof = null;
        }


        return $this;
    } // setChildof()

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
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->childof = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 3; // 3 = DwdsgenrePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Dwdsgenre object", $e);
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

        if ($this->aDwdsgenreRelatedByChildof !== null && $this->childof !== $this->aDwdsgenreRelatedByChildof->getId()) {
            $this->aDwdsgenreRelatedByChildof = null;
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
            $con = Propel::getConnection(DwdsgenrePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = DwdsgenrePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aDwdsgenreRelatedByChildof = null;
            $this->collDwdsgenresRelatedById = null;

            $this->collWorksRelatedByDwdsgenreId = null;

            $this->collWorksRelatedByDwdssubgenreId = null;

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
            $con = Propel::getConnection(DwdsgenrePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = DwdsgenreQuery::create()
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
            $con = Propel::getConnection(DwdsgenrePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                DwdsgenrePeer::addInstanceToPool($this);
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

            if ($this->aDwdsgenreRelatedByChildof !== null) {
                if ($this->aDwdsgenreRelatedByChildof->isModified() || $this->aDwdsgenreRelatedByChildof->isNew()) {
                    $affectedRows += $this->aDwdsgenreRelatedByChildof->save($con);
                }
                $this->setDwdsgenreRelatedByChildof($this->aDwdsgenreRelatedByChildof);
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

            if ($this->dwdsgenresRelatedByIdScheduledForDeletion !== null) {
                if (!$this->dwdsgenresRelatedByIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->dwdsgenresRelatedByIdScheduledForDeletion as $dwdsgenreRelatedById) {
                        // need to save related object because we set the relation to null
                        $dwdsgenreRelatedById->save($con);
                    }
                    $this->dwdsgenresRelatedByIdScheduledForDeletion = null;
                }
            }

            if ($this->collDwdsgenresRelatedById !== null) {
                foreach ($this->collDwdsgenresRelatedById as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->worksRelatedByDwdsgenreIdScheduledForDeletion !== null) {
                if (!$this->worksRelatedByDwdsgenreIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->worksRelatedByDwdsgenreIdScheduledForDeletion as $workRelatedByDwdsgenreId) {
                        // need to save related object because we set the relation to null
                        $workRelatedByDwdsgenreId->save($con);
                    }
                    $this->worksRelatedByDwdsgenreIdScheduledForDeletion = null;
                }
            }

            if ($this->collWorksRelatedByDwdsgenreId !== null) {
                foreach ($this->collWorksRelatedByDwdsgenreId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->worksRelatedByDwdssubgenreIdScheduledForDeletion !== null) {
                if (!$this->worksRelatedByDwdssubgenreIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->worksRelatedByDwdssubgenreIdScheduledForDeletion as $workRelatedByDwdssubgenreId) {
                        // need to save related object because we set the relation to null
                        $workRelatedByDwdssubgenreId->save($con);
                    }
                    $this->worksRelatedByDwdssubgenreIdScheduledForDeletion = null;
                }
            }

            if ($this->collWorksRelatedByDwdssubgenreId !== null) {
                foreach ($this->collWorksRelatedByDwdssubgenreId as $referrerFK) {
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

        $this->modifiedColumns[] = DwdsgenrePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DwdsgenrePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DwdsgenrePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(DwdsgenrePeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(DwdsgenrePeer::CHILDOF)) {
            $modifiedColumns[':p' . $index++]  = '`childOf`';
        }

        $sql = sprintf(
            'INSERT INTO `dwdsGenre` (%s) VALUES (%s)',
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
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`childOf`':
                        $stmt->bindValue($identifier, $this->childof, PDO::PARAM_INT);
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

            if ($this->aDwdsgenreRelatedByChildof !== null) {
                if (!$this->aDwdsgenreRelatedByChildof->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDwdsgenreRelatedByChildof->getValidationFailures());
                }
            }


            if (($retval = DwdsgenrePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collDwdsgenresRelatedById !== null) {
                    foreach ($this->collDwdsgenresRelatedById as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collWorksRelatedByDwdsgenreId !== null) {
                    foreach ($this->collWorksRelatedByDwdsgenreId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collWorksRelatedByDwdssubgenreId !== null) {
                    foreach ($this->collWorksRelatedByDwdssubgenreId as $referrerFK) {
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
        $pos = DwdsgenrePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getName();
                break;
            case 2:
                return $this->getChildof();
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
        if (isset($alreadyDumpedObjects['Dwdsgenre'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Dwdsgenre'][$this->getPrimaryKey()] = true;
        $keys = DwdsgenrePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getChildof(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aDwdsgenreRelatedByChildof) {
                $result['DwdsgenreRelatedByChildof'] = $this->aDwdsgenreRelatedByChildof->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collDwdsgenresRelatedById) {
                $result['DwdsgenresRelatedById'] = $this->collDwdsgenresRelatedById->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collWorksRelatedByDwdsgenreId) {
                $result['WorksRelatedByDwdsgenreId'] = $this->collWorksRelatedByDwdsgenreId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collWorksRelatedByDwdssubgenreId) {
                $result['WorksRelatedByDwdssubgenreId'] = $this->collWorksRelatedByDwdssubgenreId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = DwdsgenrePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setName($value);
                break;
            case 2:
                $this->setChildof($value);
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
        $keys = DwdsgenrePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setChildof($arr[$keys[2]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(DwdsgenrePeer::DATABASE_NAME);

        if ($this->isColumnModified(DwdsgenrePeer::ID)) $criteria->add(DwdsgenrePeer::ID, $this->id);
        if ($this->isColumnModified(DwdsgenrePeer::NAME)) $criteria->add(DwdsgenrePeer::NAME, $this->name);
        if ($this->isColumnModified(DwdsgenrePeer::CHILDOF)) $criteria->add(DwdsgenrePeer::CHILDOF, $this->childof);

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
        $criteria = new Criteria(DwdsgenrePeer::DATABASE_NAME);
        $criteria->add(DwdsgenrePeer::ID, $this->id);

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
     * @param object $copyObj An object of Dwdsgenre (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setChildof($this->getChildof());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getDwdsgenresRelatedById() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDwdsgenreRelatedById($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getWorksRelatedByDwdsgenreId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addWorkRelatedByDwdsgenreId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getWorksRelatedByDwdssubgenreId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addWorkRelatedByDwdssubgenreId($relObj->copy($deepCopy));
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
     * @return Dwdsgenre Clone of current object.
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
     * @return DwdsgenrePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new DwdsgenrePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Dwdsgenre object.
     *
     * @param             Dwdsgenre $v
     * @return Dwdsgenre The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDwdsgenreRelatedByChildof(Dwdsgenre $v = null)
    {
        if ($v === null) {
            $this->setChildof(NULL);
        } else {
            $this->setChildof($v->getId());
        }

        $this->aDwdsgenreRelatedByChildof = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Dwdsgenre object, it will not be re-added.
        if ($v !== null) {
            $v->addDwdsgenreRelatedById($this);
        }


        return $this;
    }


    /**
     * Get the associated Dwdsgenre object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Dwdsgenre The associated Dwdsgenre object.
     * @throws PropelException
     */
    public function getDwdsgenreRelatedByChildof(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDwdsgenreRelatedByChildof === null && ($this->childof !== null) && $doQuery) {
            $this->aDwdsgenreRelatedByChildof = DwdsgenreQuery::create()->findPk($this->childof, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDwdsgenreRelatedByChildof->addDwdsgenresRelatedById($this);
             */
        }

        return $this->aDwdsgenreRelatedByChildof;
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
        if ('DwdsgenreRelatedById' == $relationName) {
            $this->initDwdsgenresRelatedById();
        }
        if ('WorkRelatedByDwdsgenreId' == $relationName) {
            $this->initWorksRelatedByDwdsgenreId();
        }
        if ('WorkRelatedByDwdssubgenreId' == $relationName) {
            $this->initWorksRelatedByDwdssubgenreId();
        }
    }

    /**
     * Clears out the collDwdsgenresRelatedById collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Dwdsgenre The current object (for fluent API support)
     * @see        addDwdsgenresRelatedById()
     */
    public function clearDwdsgenresRelatedById()
    {
        $this->collDwdsgenresRelatedById = null; // important to set this to null since that means it is uninitialized
        $this->collDwdsgenresRelatedByIdPartial = null;

        return $this;
    }

    /**
     * reset is the collDwdsgenresRelatedById collection loaded partially
     *
     * @return void
     */
    public function resetPartialDwdsgenresRelatedById($v = true)
    {
        $this->collDwdsgenresRelatedByIdPartial = $v;
    }

    /**
     * Initializes the collDwdsgenresRelatedById collection.
     *
     * By default this just sets the collDwdsgenresRelatedById collection to an empty array (like clearcollDwdsgenresRelatedById());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDwdsgenresRelatedById($overrideExisting = true)
    {
        if (null !== $this->collDwdsgenresRelatedById && !$overrideExisting) {
            return;
        }
        $this->collDwdsgenresRelatedById = new PropelObjectCollection();
        $this->collDwdsgenresRelatedById->setModel('Dwdsgenre');
    }

    /**
     * Gets an array of Dwdsgenre objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Dwdsgenre is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Dwdsgenre[] List of Dwdsgenre objects
     * @throws PropelException
     */
    public function getDwdsgenresRelatedById($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collDwdsgenresRelatedByIdPartial && !$this->isNew();
        if (null === $this->collDwdsgenresRelatedById || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDwdsgenresRelatedById) {
                // return empty collection
                $this->initDwdsgenresRelatedById();
            } else {
                $collDwdsgenresRelatedById = DwdsgenreQuery::create(null, $criteria)
                    ->filterByDwdsgenreRelatedByChildof($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collDwdsgenresRelatedByIdPartial && count($collDwdsgenresRelatedById)) {
                      $this->initDwdsgenresRelatedById(false);

                      foreach($collDwdsgenresRelatedById as $obj) {
                        if (false == $this->collDwdsgenresRelatedById->contains($obj)) {
                          $this->collDwdsgenresRelatedById->append($obj);
                        }
                      }

                      $this->collDwdsgenresRelatedByIdPartial = true;
                    }

                    $collDwdsgenresRelatedById->getInternalIterator()->rewind();
                    return $collDwdsgenresRelatedById;
                }

                if($partial && $this->collDwdsgenresRelatedById) {
                    foreach($this->collDwdsgenresRelatedById as $obj) {
                        if($obj->isNew()) {
                            $collDwdsgenresRelatedById[] = $obj;
                        }
                    }
                }

                $this->collDwdsgenresRelatedById = $collDwdsgenresRelatedById;
                $this->collDwdsgenresRelatedByIdPartial = false;
            }
        }

        return $this->collDwdsgenresRelatedById;
    }

    /**
     * Sets a collection of DwdsgenreRelatedById objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $dwdsgenresRelatedById A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Dwdsgenre The current object (for fluent API support)
     */
    public function setDwdsgenresRelatedById(PropelCollection $dwdsgenresRelatedById, PropelPDO $con = null)
    {
        $dwdsgenresRelatedByIdToDelete = $this->getDwdsgenresRelatedById(new Criteria(), $con)->diff($dwdsgenresRelatedById);

        $this->dwdsgenresRelatedByIdScheduledForDeletion = unserialize(serialize($dwdsgenresRelatedByIdToDelete));

        foreach ($dwdsgenresRelatedByIdToDelete as $dwdsgenreRelatedByIdRemoved) {
            $dwdsgenreRelatedByIdRemoved->setDwdsgenreRelatedByChildof(null);
        }

        $this->collDwdsgenresRelatedById = null;
        foreach ($dwdsgenresRelatedById as $dwdsgenreRelatedById) {
            $this->addDwdsgenreRelatedById($dwdsgenreRelatedById);
        }

        $this->collDwdsgenresRelatedById = $dwdsgenresRelatedById;
        $this->collDwdsgenresRelatedByIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Dwdsgenre objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Dwdsgenre objects.
     * @throws PropelException
     */
    public function countDwdsgenresRelatedById(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collDwdsgenresRelatedByIdPartial && !$this->isNew();
        if (null === $this->collDwdsgenresRelatedById || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDwdsgenresRelatedById) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getDwdsgenresRelatedById());
            }
            $query = DwdsgenreQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDwdsgenreRelatedByChildof($this)
                ->count($con);
        }

        return count($this->collDwdsgenresRelatedById);
    }

    /**
     * Method called to associate a Dwdsgenre object to this object
     * through the Dwdsgenre foreign key attribute.
     *
     * @param    Dwdsgenre $l Dwdsgenre
     * @return Dwdsgenre The current object (for fluent API support)
     */
    public function addDwdsgenreRelatedById(Dwdsgenre $l)
    {
        if ($this->collDwdsgenresRelatedById === null) {
            $this->initDwdsgenresRelatedById();
            $this->collDwdsgenresRelatedByIdPartial = true;
        }
        if (!in_array($l, $this->collDwdsgenresRelatedById->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddDwdsgenreRelatedById($l);
        }

        return $this;
    }

    /**
     * @param	DwdsgenreRelatedById $dwdsgenreRelatedById The dwdsgenreRelatedById object to add.
     */
    protected function doAddDwdsgenreRelatedById($dwdsgenreRelatedById)
    {
        $this->collDwdsgenresRelatedById[]= $dwdsgenreRelatedById;
        $dwdsgenreRelatedById->setDwdsgenreRelatedByChildof($this);
    }

    /**
     * @param	DwdsgenreRelatedById $dwdsgenreRelatedById The dwdsgenreRelatedById object to remove.
     * @return Dwdsgenre The current object (for fluent API support)
     */
    public function removeDwdsgenreRelatedById($dwdsgenreRelatedById)
    {
        if ($this->getDwdsgenresRelatedById()->contains($dwdsgenreRelatedById)) {
            $this->collDwdsgenresRelatedById->remove($this->collDwdsgenresRelatedById->search($dwdsgenreRelatedById));
            if (null === $this->dwdsgenresRelatedByIdScheduledForDeletion) {
                $this->dwdsgenresRelatedByIdScheduledForDeletion = clone $this->collDwdsgenresRelatedById;
                $this->dwdsgenresRelatedByIdScheduledForDeletion->clear();
            }
            $this->dwdsgenresRelatedByIdScheduledForDeletion[]= $dwdsgenreRelatedById;
            $dwdsgenreRelatedById->setDwdsgenreRelatedByChildof(null);
        }

        return $this;
    }

    /**
     * Clears out the collWorksRelatedByDwdsgenreId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Dwdsgenre The current object (for fluent API support)
     * @see        addWorksRelatedByDwdsgenreId()
     */
    public function clearWorksRelatedByDwdsgenreId()
    {
        $this->collWorksRelatedByDwdsgenreId = null; // important to set this to null since that means it is uninitialized
        $this->collWorksRelatedByDwdsgenreIdPartial = null;

        return $this;
    }

    /**
     * reset is the collWorksRelatedByDwdsgenreId collection loaded partially
     *
     * @return void
     */
    public function resetPartialWorksRelatedByDwdsgenreId($v = true)
    {
        $this->collWorksRelatedByDwdsgenreIdPartial = $v;
    }

    /**
     * Initializes the collWorksRelatedByDwdsgenreId collection.
     *
     * By default this just sets the collWorksRelatedByDwdsgenreId collection to an empty array (like clearcollWorksRelatedByDwdsgenreId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initWorksRelatedByDwdsgenreId($overrideExisting = true)
    {
        if (null !== $this->collWorksRelatedByDwdsgenreId && !$overrideExisting) {
            return;
        }
        $this->collWorksRelatedByDwdsgenreId = new PropelObjectCollection();
        $this->collWorksRelatedByDwdsgenreId->setModel('Work');
    }

    /**
     * Gets an array of Work objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Dwdsgenre is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Work[] List of Work objects
     * @throws PropelException
     */
    public function getWorksRelatedByDwdsgenreId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collWorksRelatedByDwdsgenreIdPartial && !$this->isNew();
        if (null === $this->collWorksRelatedByDwdsgenreId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collWorksRelatedByDwdsgenreId) {
                // return empty collection
                $this->initWorksRelatedByDwdsgenreId();
            } else {
                $collWorksRelatedByDwdsgenreId = WorkQuery::create(null, $criteria)
                    ->filterByDwdsgenreRelatedByDwdsgenreId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collWorksRelatedByDwdsgenreIdPartial && count($collWorksRelatedByDwdsgenreId)) {
                      $this->initWorksRelatedByDwdsgenreId(false);

                      foreach($collWorksRelatedByDwdsgenreId as $obj) {
                        if (false == $this->collWorksRelatedByDwdsgenreId->contains($obj)) {
                          $this->collWorksRelatedByDwdsgenreId->append($obj);
                        }
                      }

                      $this->collWorksRelatedByDwdsgenreIdPartial = true;
                    }

                    $collWorksRelatedByDwdsgenreId->getInternalIterator()->rewind();
                    return $collWorksRelatedByDwdsgenreId;
                }

                if($partial && $this->collWorksRelatedByDwdsgenreId) {
                    foreach($this->collWorksRelatedByDwdsgenreId as $obj) {
                        if($obj->isNew()) {
                            $collWorksRelatedByDwdsgenreId[] = $obj;
                        }
                    }
                }

                $this->collWorksRelatedByDwdsgenreId = $collWorksRelatedByDwdsgenreId;
                $this->collWorksRelatedByDwdsgenreIdPartial = false;
            }
        }

        return $this->collWorksRelatedByDwdsgenreId;
    }

    /**
     * Sets a collection of WorkRelatedByDwdsgenreId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $worksRelatedByDwdsgenreId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Dwdsgenre The current object (for fluent API support)
     */
    public function setWorksRelatedByDwdsgenreId(PropelCollection $worksRelatedByDwdsgenreId, PropelPDO $con = null)
    {
        $worksRelatedByDwdsgenreIdToDelete = $this->getWorksRelatedByDwdsgenreId(new Criteria(), $con)->diff($worksRelatedByDwdsgenreId);

        $this->worksRelatedByDwdsgenreIdScheduledForDeletion = unserialize(serialize($worksRelatedByDwdsgenreIdToDelete));

        foreach ($worksRelatedByDwdsgenreIdToDelete as $workRelatedByDwdsgenreIdRemoved) {
            $workRelatedByDwdsgenreIdRemoved->setDwdsgenreRelatedByDwdsgenreId(null);
        }

        $this->collWorksRelatedByDwdsgenreId = null;
        foreach ($worksRelatedByDwdsgenreId as $workRelatedByDwdsgenreId) {
            $this->addWorkRelatedByDwdsgenreId($workRelatedByDwdsgenreId);
        }

        $this->collWorksRelatedByDwdsgenreId = $worksRelatedByDwdsgenreId;
        $this->collWorksRelatedByDwdsgenreIdPartial = false;

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
    public function countWorksRelatedByDwdsgenreId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collWorksRelatedByDwdsgenreIdPartial && !$this->isNew();
        if (null === $this->collWorksRelatedByDwdsgenreId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collWorksRelatedByDwdsgenreId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getWorksRelatedByDwdsgenreId());
            }
            $query = WorkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDwdsgenreRelatedByDwdsgenreId($this)
                ->count($con);
        }

        return count($this->collWorksRelatedByDwdsgenreId);
    }

    /**
     * Method called to associate a Work object to this object
     * through the Work foreign key attribute.
     *
     * @param    Work $l Work
     * @return Dwdsgenre The current object (for fluent API support)
     */
    public function addWorkRelatedByDwdsgenreId(Work $l)
    {
        if ($this->collWorksRelatedByDwdsgenreId === null) {
            $this->initWorksRelatedByDwdsgenreId();
            $this->collWorksRelatedByDwdsgenreIdPartial = true;
        }
        if (!in_array($l, $this->collWorksRelatedByDwdsgenreId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddWorkRelatedByDwdsgenreId($l);
        }

        return $this;
    }

    /**
     * @param	WorkRelatedByDwdsgenreId $workRelatedByDwdsgenreId The workRelatedByDwdsgenreId object to add.
     */
    protected function doAddWorkRelatedByDwdsgenreId($workRelatedByDwdsgenreId)
    {
        $this->collWorksRelatedByDwdsgenreId[]= $workRelatedByDwdsgenreId;
        $workRelatedByDwdsgenreId->setDwdsgenreRelatedByDwdsgenreId($this);
    }

    /**
     * @param	WorkRelatedByDwdsgenreId $workRelatedByDwdsgenreId The workRelatedByDwdsgenreId object to remove.
     * @return Dwdsgenre The current object (for fluent API support)
     */
    public function removeWorkRelatedByDwdsgenreId($workRelatedByDwdsgenreId)
    {
        if ($this->getWorksRelatedByDwdsgenreId()->contains($workRelatedByDwdsgenreId)) {
            $this->collWorksRelatedByDwdsgenreId->remove($this->collWorksRelatedByDwdsgenreId->search($workRelatedByDwdsgenreId));
            if (null === $this->worksRelatedByDwdsgenreIdScheduledForDeletion) {
                $this->worksRelatedByDwdsgenreIdScheduledForDeletion = clone $this->collWorksRelatedByDwdsgenreId;
                $this->worksRelatedByDwdsgenreIdScheduledForDeletion->clear();
            }
            $this->worksRelatedByDwdsgenreIdScheduledForDeletion[]= $workRelatedByDwdsgenreId;
            $workRelatedByDwdsgenreId->setDwdsgenreRelatedByDwdsgenreId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Dwdsgenre is new, it will return
     * an empty collection; or if this Dwdsgenre has previously
     * been saved, it will retrieve related WorksRelatedByDwdsgenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Dwdsgenre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedByDwdsgenreIdJoinStatus($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('Status', $join_behavior);

        return $this->getWorksRelatedByDwdsgenreId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Dwdsgenre is new, it will return
     * an empty collection; or if this Dwdsgenre has previously
     * been saved, it will retrieve related WorksRelatedByDwdsgenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Dwdsgenre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedByDwdsgenreIdJoinGenreRelatedByGenreId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('GenreRelatedByGenreId', $join_behavior);

        return $this->getWorksRelatedByDwdsgenreId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Dwdsgenre is new, it will return
     * an empty collection; or if this Dwdsgenre has previously
     * been saved, it will retrieve related WorksRelatedByDwdsgenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Dwdsgenre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedByDwdsgenreIdJoinGenreRelatedBySubgenreId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('GenreRelatedBySubgenreId', $join_behavior);

        return $this->getWorksRelatedByDwdsgenreId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Dwdsgenre is new, it will return
     * an empty collection; or if this Dwdsgenre has previously
     * been saved, it will retrieve related WorksRelatedByDwdsgenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Dwdsgenre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedByDwdsgenreIdJoinDatespecification($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('Datespecification', $join_behavior);

        return $this->getWorksRelatedByDwdsgenreId($query, $con);
    }

    /**
     * Clears out the collWorksRelatedByDwdssubgenreId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Dwdsgenre The current object (for fluent API support)
     * @see        addWorksRelatedByDwdssubgenreId()
     */
    public function clearWorksRelatedByDwdssubgenreId()
    {
        $this->collWorksRelatedByDwdssubgenreId = null; // important to set this to null since that means it is uninitialized
        $this->collWorksRelatedByDwdssubgenreIdPartial = null;

        return $this;
    }

    /**
     * reset is the collWorksRelatedByDwdssubgenreId collection loaded partially
     *
     * @return void
     */
    public function resetPartialWorksRelatedByDwdssubgenreId($v = true)
    {
        $this->collWorksRelatedByDwdssubgenreIdPartial = $v;
    }

    /**
     * Initializes the collWorksRelatedByDwdssubgenreId collection.
     *
     * By default this just sets the collWorksRelatedByDwdssubgenreId collection to an empty array (like clearcollWorksRelatedByDwdssubgenreId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initWorksRelatedByDwdssubgenreId($overrideExisting = true)
    {
        if (null !== $this->collWorksRelatedByDwdssubgenreId && !$overrideExisting) {
            return;
        }
        $this->collWorksRelatedByDwdssubgenreId = new PropelObjectCollection();
        $this->collWorksRelatedByDwdssubgenreId->setModel('Work');
    }

    /**
     * Gets an array of Work objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Dwdsgenre is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Work[] List of Work objects
     * @throws PropelException
     */
    public function getWorksRelatedByDwdssubgenreId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collWorksRelatedByDwdssubgenreIdPartial && !$this->isNew();
        if (null === $this->collWorksRelatedByDwdssubgenreId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collWorksRelatedByDwdssubgenreId) {
                // return empty collection
                $this->initWorksRelatedByDwdssubgenreId();
            } else {
                $collWorksRelatedByDwdssubgenreId = WorkQuery::create(null, $criteria)
                    ->filterByDwdsgenreRelatedByDwdssubgenreId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collWorksRelatedByDwdssubgenreIdPartial && count($collWorksRelatedByDwdssubgenreId)) {
                      $this->initWorksRelatedByDwdssubgenreId(false);

                      foreach($collWorksRelatedByDwdssubgenreId as $obj) {
                        if (false == $this->collWorksRelatedByDwdssubgenreId->contains($obj)) {
                          $this->collWorksRelatedByDwdssubgenreId->append($obj);
                        }
                      }

                      $this->collWorksRelatedByDwdssubgenreIdPartial = true;
                    }

                    $collWorksRelatedByDwdssubgenreId->getInternalIterator()->rewind();
                    return $collWorksRelatedByDwdssubgenreId;
                }

                if($partial && $this->collWorksRelatedByDwdssubgenreId) {
                    foreach($this->collWorksRelatedByDwdssubgenreId as $obj) {
                        if($obj->isNew()) {
                            $collWorksRelatedByDwdssubgenreId[] = $obj;
                        }
                    }
                }

                $this->collWorksRelatedByDwdssubgenreId = $collWorksRelatedByDwdssubgenreId;
                $this->collWorksRelatedByDwdssubgenreIdPartial = false;
            }
        }

        return $this->collWorksRelatedByDwdssubgenreId;
    }

    /**
     * Sets a collection of WorkRelatedByDwdssubgenreId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $worksRelatedByDwdssubgenreId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Dwdsgenre The current object (for fluent API support)
     */
    public function setWorksRelatedByDwdssubgenreId(PropelCollection $worksRelatedByDwdssubgenreId, PropelPDO $con = null)
    {
        $worksRelatedByDwdssubgenreIdToDelete = $this->getWorksRelatedByDwdssubgenreId(new Criteria(), $con)->diff($worksRelatedByDwdssubgenreId);

        $this->worksRelatedByDwdssubgenreIdScheduledForDeletion = unserialize(serialize($worksRelatedByDwdssubgenreIdToDelete));

        foreach ($worksRelatedByDwdssubgenreIdToDelete as $workRelatedByDwdssubgenreIdRemoved) {
            $workRelatedByDwdssubgenreIdRemoved->setDwdsgenreRelatedByDwdssubgenreId(null);
        }

        $this->collWorksRelatedByDwdssubgenreId = null;
        foreach ($worksRelatedByDwdssubgenreId as $workRelatedByDwdssubgenreId) {
            $this->addWorkRelatedByDwdssubgenreId($workRelatedByDwdssubgenreId);
        }

        $this->collWorksRelatedByDwdssubgenreId = $worksRelatedByDwdssubgenreId;
        $this->collWorksRelatedByDwdssubgenreIdPartial = false;

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
    public function countWorksRelatedByDwdssubgenreId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collWorksRelatedByDwdssubgenreIdPartial && !$this->isNew();
        if (null === $this->collWorksRelatedByDwdssubgenreId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collWorksRelatedByDwdssubgenreId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getWorksRelatedByDwdssubgenreId());
            }
            $query = WorkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDwdsgenreRelatedByDwdssubgenreId($this)
                ->count($con);
        }

        return count($this->collWorksRelatedByDwdssubgenreId);
    }

    /**
     * Method called to associate a Work object to this object
     * through the Work foreign key attribute.
     *
     * @param    Work $l Work
     * @return Dwdsgenre The current object (for fluent API support)
     */
    public function addWorkRelatedByDwdssubgenreId(Work $l)
    {
        if ($this->collWorksRelatedByDwdssubgenreId === null) {
            $this->initWorksRelatedByDwdssubgenreId();
            $this->collWorksRelatedByDwdssubgenreIdPartial = true;
        }
        if (!in_array($l, $this->collWorksRelatedByDwdssubgenreId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddWorkRelatedByDwdssubgenreId($l);
        }

        return $this;
    }

    /**
     * @param	WorkRelatedByDwdssubgenreId $workRelatedByDwdssubgenreId The workRelatedByDwdssubgenreId object to add.
     */
    protected function doAddWorkRelatedByDwdssubgenreId($workRelatedByDwdssubgenreId)
    {
        $this->collWorksRelatedByDwdssubgenreId[]= $workRelatedByDwdssubgenreId;
        $workRelatedByDwdssubgenreId->setDwdsgenreRelatedByDwdssubgenreId($this);
    }

    /**
     * @param	WorkRelatedByDwdssubgenreId $workRelatedByDwdssubgenreId The workRelatedByDwdssubgenreId object to remove.
     * @return Dwdsgenre The current object (for fluent API support)
     */
    public function removeWorkRelatedByDwdssubgenreId($workRelatedByDwdssubgenreId)
    {
        if ($this->getWorksRelatedByDwdssubgenreId()->contains($workRelatedByDwdssubgenreId)) {
            $this->collWorksRelatedByDwdssubgenreId->remove($this->collWorksRelatedByDwdssubgenreId->search($workRelatedByDwdssubgenreId));
            if (null === $this->worksRelatedByDwdssubgenreIdScheduledForDeletion) {
                $this->worksRelatedByDwdssubgenreIdScheduledForDeletion = clone $this->collWorksRelatedByDwdssubgenreId;
                $this->worksRelatedByDwdssubgenreIdScheduledForDeletion->clear();
            }
            $this->worksRelatedByDwdssubgenreIdScheduledForDeletion[]= $workRelatedByDwdssubgenreId;
            $workRelatedByDwdssubgenreId->setDwdsgenreRelatedByDwdssubgenreId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Dwdsgenre is new, it will return
     * an empty collection; or if this Dwdsgenre has previously
     * been saved, it will retrieve related WorksRelatedByDwdssubgenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Dwdsgenre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedByDwdssubgenreIdJoinStatus($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('Status', $join_behavior);

        return $this->getWorksRelatedByDwdssubgenreId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Dwdsgenre is new, it will return
     * an empty collection; or if this Dwdsgenre has previously
     * been saved, it will retrieve related WorksRelatedByDwdssubgenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Dwdsgenre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedByDwdssubgenreIdJoinGenreRelatedByGenreId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('GenreRelatedByGenreId', $join_behavior);

        return $this->getWorksRelatedByDwdssubgenreId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Dwdsgenre is new, it will return
     * an empty collection; or if this Dwdsgenre has previously
     * been saved, it will retrieve related WorksRelatedByDwdssubgenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Dwdsgenre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedByDwdssubgenreIdJoinGenreRelatedBySubgenreId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('GenreRelatedBySubgenreId', $join_behavior);

        return $this->getWorksRelatedByDwdssubgenreId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Dwdsgenre is new, it will return
     * an empty collection; or if this Dwdsgenre has previously
     * been saved, it will retrieve related WorksRelatedByDwdssubgenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Dwdsgenre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedByDwdssubgenreIdJoinDatespecification($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('Datespecification', $join_behavior);

        return $this->getWorksRelatedByDwdssubgenreId($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->childof = null;
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
            if ($this->collDwdsgenresRelatedById) {
                foreach ($this->collDwdsgenresRelatedById as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWorksRelatedByDwdsgenreId) {
                foreach ($this->collWorksRelatedByDwdsgenreId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWorksRelatedByDwdssubgenreId) {
                foreach ($this->collWorksRelatedByDwdssubgenreId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aDwdsgenreRelatedByChildof instanceof Persistent) {
              $this->aDwdsgenreRelatedByChildof->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collDwdsgenresRelatedById instanceof PropelCollection) {
            $this->collDwdsgenresRelatedById->clearIterator();
        }
        $this->collDwdsgenresRelatedById = null;
        if ($this->collWorksRelatedByDwdsgenreId instanceof PropelCollection) {
            $this->collWorksRelatedByDwdsgenreId->clearIterator();
        }
        $this->collWorksRelatedByDwdsgenreId = null;
        if ($this->collWorksRelatedByDwdssubgenreId instanceof PropelCollection) {
            $this->collWorksRelatedByDwdssubgenreId->clearIterator();
        }
        $this->collWorksRelatedByDwdssubgenreId = null;
        $this->aDwdsgenreRelatedByChildof = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(DwdsgenrePeer::DEFAULT_STRING_FORMAT);
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
