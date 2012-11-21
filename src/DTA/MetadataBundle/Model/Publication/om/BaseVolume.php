<?php

namespace DTA\MetadataBundle\Model\Publication\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelException;
use \PropelPDO;
use DTA\MetadataBundle\Model\Publication\Monograph;
use DTA\MetadataBundle\Model\Publication\MonographQuery;
use DTA\MetadataBundle\Model\Publication\Volume;
use DTA\MetadataBundle\Model\Publication\VolumePeer;
use DTA\MetadataBundle\Model\Publication\VolumeQuery;

abstract class BaseVolume extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\Publication\\VolumePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        VolumePeer
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
     * The value for the volumeindex field.
     * @var        string
     */
    protected $volumeindex;

    /**
     * The value for the volumeindexnumerical field.
     * @var        int
     */
    protected $volumeindexnumerical;

    /**
     * The value for the totalvolumes field.
     * @var        int
     */
    protected $totalvolumes;

    /**
     * The value for the monograph_id field.
     * @var        int
     */
    protected $monograph_id;

    /**
     * The value for the monograph_publication_id field.
     * @var        int
     */
    protected $monograph_publication_id;

    /**
     * @var        Monograph
     */
    protected $aMonograph;

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
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [volumeindex] column value.
     *
     * @return string
     */
    public function getVolumeindex()
    {
        return $this->volumeindex;
    }

    /**
     * Get the [volumeindexnumerical] column value.
     *
     * @return int
     */
    public function getVolumeindexnumerical()
    {
        return $this->volumeindexnumerical;
    }

    /**
     * Get the [totalvolumes] column value.
     *
     * @return int
     */
    public function getTotalvolumes()
    {
        return $this->totalvolumes;
    }

    /**
     * Get the [monograph_id] column value.
     *
     * @return int
     */
    public function getMonographId()
    {
        return $this->monograph_id;
    }

    /**
     * Get the [monograph_publication_id] column value.
     *
     * @return int
     */
    public function getMonographPublicationId()
    {
        return $this->monograph_publication_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Volume The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = VolumePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [volumeindex] column.
     *
     * @param string $v new value
     * @return Volume The current object (for fluent API support)
     */
    public function setVolumeindex($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->volumeindex !== $v) {
            $this->volumeindex = $v;
            $this->modifiedColumns[] = VolumePeer::VOLUMEINDEX;
        }


        return $this;
    } // setVolumeindex()

    /**
     * Set the value of [volumeindexnumerical] column.
     *
     * @param int $v new value
     * @return Volume The current object (for fluent API support)
     */
    public function setVolumeindexnumerical($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->volumeindexnumerical !== $v) {
            $this->volumeindexnumerical = $v;
            $this->modifiedColumns[] = VolumePeer::VOLUMEINDEXNUMERICAL;
        }


        return $this;
    } // setVolumeindexnumerical()

    /**
     * Set the value of [totalvolumes] column.
     *
     * @param int $v new value
     * @return Volume The current object (for fluent API support)
     */
    public function setTotalvolumes($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->totalvolumes !== $v) {
            $this->totalvolumes = $v;
            $this->modifiedColumns[] = VolumePeer::TOTALVOLUMES;
        }


        return $this;
    } // setTotalvolumes()

    /**
     * Set the value of [monograph_id] column.
     *
     * @param int $v new value
     * @return Volume The current object (for fluent API support)
     */
    public function setMonographId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->monograph_id !== $v) {
            $this->monograph_id = $v;
            $this->modifiedColumns[] = VolumePeer::MONOGRAPH_ID;
        }

        if ($this->aMonograph !== null && $this->aMonograph->getId() !== $v) {
            $this->aMonograph = null;
        }


        return $this;
    } // setMonographId()

    /**
     * Set the value of [monograph_publication_id] column.
     *
     * @param int $v new value
     * @return Volume The current object (for fluent API support)
     */
    public function setMonographPublicationId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->monograph_publication_id !== $v) {
            $this->monograph_publication_id = $v;
            $this->modifiedColumns[] = VolumePeer::MONOGRAPH_PUBLICATION_ID;
        }

        if ($this->aMonograph !== null && $this->aMonograph->getPublicationId() !== $v) {
            $this->aMonograph = null;
        }


        return $this;
    } // setMonographPublicationId()

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
            $this->volumeindex = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->volumeindexnumerical = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->totalvolumes = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->monograph_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->monograph_publication_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 6; // 6 = VolumePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Volume object", $e);
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

        if ($this->aMonograph !== null && $this->monograph_id !== $this->aMonograph->getId()) {
            $this->aMonograph = null;
        }
        if ($this->aMonograph !== null && $this->monograph_publication_id !== $this->aMonograph->getPublicationId()) {
            $this->aMonograph = null;
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
            $con = Propel::getConnection(VolumePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = VolumePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aMonograph = null;
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
            $con = Propel::getConnection(VolumePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = VolumeQuery::create()
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
            $con = Propel::getConnection(VolumePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                VolumePeer::addInstanceToPool($this);
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

            if ($this->aMonograph !== null) {
                if ($this->aMonograph->isModified() || $this->aMonograph->isNew()) {
                    $affectedRows += $this->aMonograph->save($con);
                }
                $this->setMonograph($this->aMonograph);
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

        $this->modifiedColumns[] = VolumePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . VolumePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(VolumePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(VolumePeer::VOLUMEINDEX)) {
            $modifiedColumns[':p' . $index++]  = '`VOLUMEINDEX`';
        }
        if ($this->isColumnModified(VolumePeer::VOLUMEINDEXNUMERICAL)) {
            $modifiedColumns[':p' . $index++]  = '`VOLUMEINDEXNUMERICAL`';
        }
        if ($this->isColumnModified(VolumePeer::TOTALVOLUMES)) {
            $modifiedColumns[':p' . $index++]  = '`TOTALVOLUMES`';
        }
        if ($this->isColumnModified(VolumePeer::MONOGRAPH_ID)) {
            $modifiedColumns[':p' . $index++]  = '`MONOGRAPH_ID`';
        }
        if ($this->isColumnModified(VolumePeer::MONOGRAPH_PUBLICATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`MONOGRAPH_PUBLICATION_ID`';
        }

        $sql = sprintf(
            'INSERT INTO `volume` (%s) VALUES (%s)',
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
                    case '`VOLUMEINDEX`':
                        $stmt->bindValue($identifier, $this->volumeindex, PDO::PARAM_STR);
                        break;
                    case '`VOLUMEINDEXNUMERICAL`':
                        $stmt->bindValue($identifier, $this->volumeindexnumerical, PDO::PARAM_INT);
                        break;
                    case '`TOTALVOLUMES`':
                        $stmt->bindValue($identifier, $this->totalvolumes, PDO::PARAM_INT);
                        break;
                    case '`MONOGRAPH_ID`':
                        $stmt->bindValue($identifier, $this->monograph_id, PDO::PARAM_INT);
                        break;
                    case '`MONOGRAPH_PUBLICATION_ID`':
                        $stmt->bindValue($identifier, $this->monograph_publication_id, PDO::PARAM_INT);
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

            if ($this->aMonograph !== null) {
                if (!$this->aMonograph->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aMonograph->getValidationFailures());
                }
            }


            if (($retval = VolumePeer::doValidate($this, $columns)) !== true) {
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
        $pos = VolumePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getVolumeindex();
                break;
            case 2:
                return $this->getVolumeindexnumerical();
                break;
            case 3:
                return $this->getTotalvolumes();
                break;
            case 4:
                return $this->getMonographId();
                break;
            case 5:
                return $this->getMonographPublicationId();
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
        if (isset($alreadyDumpedObjects['Volume'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Volume'][$this->getPrimaryKey()] = true;
        $keys = VolumePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getVolumeindex(),
            $keys[2] => $this->getVolumeindexnumerical(),
            $keys[3] => $this->getTotalvolumes(),
            $keys[4] => $this->getMonographId(),
            $keys[5] => $this->getMonographPublicationId(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aMonograph) {
                $result['Monograph'] = $this->aMonograph->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = VolumePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setVolumeindex($value);
                break;
            case 2:
                $this->setVolumeindexnumerical($value);
                break;
            case 3:
                $this->setTotalvolumes($value);
                break;
            case 4:
                $this->setMonographId($value);
                break;
            case 5:
                $this->setMonographPublicationId($value);
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
        $keys = VolumePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setVolumeindex($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setVolumeindexnumerical($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setTotalvolumes($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setMonographId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setMonographPublicationId($arr[$keys[5]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(VolumePeer::DATABASE_NAME);

        if ($this->isColumnModified(VolumePeer::ID)) $criteria->add(VolumePeer::ID, $this->id);
        if ($this->isColumnModified(VolumePeer::VOLUMEINDEX)) $criteria->add(VolumePeer::VOLUMEINDEX, $this->volumeindex);
        if ($this->isColumnModified(VolumePeer::VOLUMEINDEXNUMERICAL)) $criteria->add(VolumePeer::VOLUMEINDEXNUMERICAL, $this->volumeindexnumerical);
        if ($this->isColumnModified(VolumePeer::TOTALVOLUMES)) $criteria->add(VolumePeer::TOTALVOLUMES, $this->totalvolumes);
        if ($this->isColumnModified(VolumePeer::MONOGRAPH_ID)) $criteria->add(VolumePeer::MONOGRAPH_ID, $this->monograph_id);
        if ($this->isColumnModified(VolumePeer::MONOGRAPH_PUBLICATION_ID)) $criteria->add(VolumePeer::MONOGRAPH_PUBLICATION_ID, $this->monograph_publication_id);

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
        $criteria = new Criteria(VolumePeer::DATABASE_NAME);
        $criteria->add(VolumePeer::ID, $this->id);

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
     * @param object $copyObj An object of Volume (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setVolumeindex($this->getVolumeindex());
        $copyObj->setVolumeindexnumerical($this->getVolumeindexnumerical());
        $copyObj->setTotalvolumes($this->getTotalvolumes());
        $copyObj->setMonographId($this->getMonographId());
        $copyObj->setMonographPublicationId($this->getMonographPublicationId());

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
     * @return Volume Clone of current object.
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
     * @return VolumePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new VolumePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Monograph object.
     *
     * @param             Monograph $v
     * @return Volume The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMonograph(Monograph $v = null)
    {
        if ($v === null) {
            $this->setMonographId(NULL);
        } else {
            $this->setMonographId($v->getId());
        }

        if ($v === null) {
            $this->setMonographPublicationId(NULL);
        } else {
            $this->setMonographPublicationId($v->getPublicationId());
        }

        $this->aMonograph = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Monograph object, it will not be re-added.
        if ($v !== null) {
            $v->addVolume($this);
        }


        return $this;
    }


    /**
     * Get the associated Monograph object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Monograph The associated Monograph object.
     * @throws PropelException
     */
    public function getMonograph(PropelPDO $con = null)
    {
        if ($this->aMonograph === null && ($this->monograph_id !== null && $this->monograph_publication_id !== null)) {
            $this->aMonograph = MonographQuery::create()->findPk(array($this->monograph_id, $this->monograph_publication_id), $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMonograph->addVolumes($this);
             */
        }

        return $this->aMonograph;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->volumeindex = null;
        $this->volumeindexnumerical = null;
        $this->totalvolumes = null;
        $this->monograph_id = null;
        $this->monograph_publication_id = null;
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
        } // if ($deep)

        $this->aMonograph = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(VolumePeer::DEFAULT_STRING_FORMAT);
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
