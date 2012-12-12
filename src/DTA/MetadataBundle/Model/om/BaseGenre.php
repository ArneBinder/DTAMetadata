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
use DTA\MetadataBundle\Model\Genre;
use DTA\MetadataBundle\Model\GenrePeer;
use DTA\MetadataBundle\Model\GenreQuery;
use DTA\MetadataBundle\Model\Work;
use DTA\MetadataBundle\Model\WorkQuery;

abstract class BaseGenre extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\GenrePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        GenrePeer
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
     * @var        Genre
     */
    protected $aGenreRelatedByChildof;

    /**
     * @var        PropelObjectCollection|Genre[] Collection to store aggregation of Genre objects.
     */
    protected $collGenresRelatedById;
    protected $collGenresRelatedByIdPartial;

    /**
     * @var        PropelObjectCollection|Work[] Collection to store aggregation of Work objects.
     */
    protected $collWorksRelatedByGenreId;
    protected $collWorksRelatedByGenreIdPartial;

    /**
     * @var        PropelObjectCollection|Work[] Collection to store aggregation of Work objects.
     */
    protected $collWorksRelatedBySubgenreId;
    protected $collWorksRelatedBySubgenreIdPartial;

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
    protected $genresRelatedByIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $worksRelatedByGenreIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $worksRelatedBySubgenreIdScheduledForDeletion = null;

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
     * @return Genre The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = GenrePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return Genre The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = GenrePeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [childof] column.
     *
     * @param int $v new value
     * @return Genre The current object (for fluent API support)
     */
    public function setChildof($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->childof !== $v) {
            $this->childof = $v;
            $this->modifiedColumns[] = GenrePeer::CHILDOF;
        }

        if ($this->aGenreRelatedByChildof !== null && $this->aGenreRelatedByChildof->getId() !== $v) {
            $this->aGenreRelatedByChildof = null;
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
            return $startcol + 3; // 3 = GenrePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Genre object", $e);
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

        if ($this->aGenreRelatedByChildof !== null && $this->childof !== $this->aGenreRelatedByChildof->getId()) {
            $this->aGenreRelatedByChildof = null;
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
            $con = Propel::getConnection(GenrePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = GenrePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aGenreRelatedByChildof = null;
            $this->collGenresRelatedById = null;

            $this->collWorksRelatedByGenreId = null;

            $this->collWorksRelatedBySubgenreId = null;

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
            $con = Propel::getConnection(GenrePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = GenreQuery::create()
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
            $con = Propel::getConnection(GenrePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                GenrePeer::addInstanceToPool($this);
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

            if ($this->aGenreRelatedByChildof !== null) {
                if ($this->aGenreRelatedByChildof->isModified() || $this->aGenreRelatedByChildof->isNew()) {
                    $affectedRows += $this->aGenreRelatedByChildof->save($con);
                }
                $this->setGenreRelatedByChildof($this->aGenreRelatedByChildof);
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

            if ($this->genresRelatedByIdScheduledForDeletion !== null) {
                if (!$this->genresRelatedByIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->genresRelatedByIdScheduledForDeletion as $genreRelatedById) {
                        // need to save related object because we set the relation to null
                        $genreRelatedById->save($con);
                    }
                    $this->genresRelatedByIdScheduledForDeletion = null;
                }
            }

            if ($this->collGenresRelatedById !== null) {
                foreach ($this->collGenresRelatedById as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->worksRelatedByGenreIdScheduledForDeletion !== null) {
                if (!$this->worksRelatedByGenreIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->worksRelatedByGenreIdScheduledForDeletion as $workRelatedByGenreId) {
                        // need to save related object because we set the relation to null
                        $workRelatedByGenreId->save($con);
                    }
                    $this->worksRelatedByGenreIdScheduledForDeletion = null;
                }
            }

            if ($this->collWorksRelatedByGenreId !== null) {
                foreach ($this->collWorksRelatedByGenreId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->worksRelatedBySubgenreIdScheduledForDeletion !== null) {
                if (!$this->worksRelatedBySubgenreIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->worksRelatedBySubgenreIdScheduledForDeletion as $workRelatedBySubgenreId) {
                        // need to save related object because we set the relation to null
                        $workRelatedBySubgenreId->save($con);
                    }
                    $this->worksRelatedBySubgenreIdScheduledForDeletion = null;
                }
            }

            if ($this->collWorksRelatedBySubgenreId !== null) {
                foreach ($this->collWorksRelatedBySubgenreId as $referrerFK) {
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

        $this->modifiedColumns[] = GenrePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . GenrePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(GenrePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(GenrePeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(GenrePeer::CHILDOF)) {
            $modifiedColumns[':p' . $index++]  = '`childOf`';
        }

        $sql = sprintf(
            'INSERT INTO `genre` (%s) VALUES (%s)',
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

            if ($this->aGenreRelatedByChildof !== null) {
                if (!$this->aGenreRelatedByChildof->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aGenreRelatedByChildof->getValidationFailures());
                }
            }


            if (($retval = GenrePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collGenresRelatedById !== null) {
                    foreach ($this->collGenresRelatedById as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collWorksRelatedByGenreId !== null) {
                    foreach ($this->collWorksRelatedByGenreId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collWorksRelatedBySubgenreId !== null) {
                    foreach ($this->collWorksRelatedBySubgenreId as $referrerFK) {
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
        $pos = GenrePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
        if (isset($alreadyDumpedObjects['Genre'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Genre'][$this->getPrimaryKey()] = true;
        $keys = GenrePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getChildof(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aGenreRelatedByChildof) {
                $result['GenreRelatedByChildof'] = $this->aGenreRelatedByChildof->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collGenresRelatedById) {
                $result['GenresRelatedById'] = $this->collGenresRelatedById->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collWorksRelatedByGenreId) {
                $result['WorksRelatedByGenreId'] = $this->collWorksRelatedByGenreId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collWorksRelatedBySubgenreId) {
                $result['WorksRelatedBySubgenreId'] = $this->collWorksRelatedBySubgenreId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = GenrePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
        $keys = GenrePeer::getFieldNames($keyType);

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
        $criteria = new Criteria(GenrePeer::DATABASE_NAME);

        if ($this->isColumnModified(GenrePeer::ID)) $criteria->add(GenrePeer::ID, $this->id);
        if ($this->isColumnModified(GenrePeer::NAME)) $criteria->add(GenrePeer::NAME, $this->name);
        if ($this->isColumnModified(GenrePeer::CHILDOF)) $criteria->add(GenrePeer::CHILDOF, $this->childof);

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
        $criteria = new Criteria(GenrePeer::DATABASE_NAME);
        $criteria->add(GenrePeer::ID, $this->id);

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
     * @param object $copyObj An object of Genre (or compatible) type.
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

            foreach ($this->getGenresRelatedById() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGenreRelatedById($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getWorksRelatedByGenreId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addWorkRelatedByGenreId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getWorksRelatedBySubgenreId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addWorkRelatedBySubgenreId($relObj->copy($deepCopy));
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
     * @return Genre Clone of current object.
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
     * @return GenrePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new GenrePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Genre object.
     *
     * @param             Genre $v
     * @return Genre The current object (for fluent API support)
     * @throws PropelException
     */
    public function setGenreRelatedByChildof(Genre $v = null)
    {
        if ($v === null) {
            $this->setChildof(NULL);
        } else {
            $this->setChildof($v->getId());
        }

        $this->aGenreRelatedByChildof = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Genre object, it will not be re-added.
        if ($v !== null) {
            $v->addGenreRelatedById($this);
        }


        return $this;
    }


    /**
     * Get the associated Genre object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Genre The associated Genre object.
     * @throws PropelException
     */
    public function getGenreRelatedByChildof(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aGenreRelatedByChildof === null && ($this->childof !== null) && $doQuery) {
            $this->aGenreRelatedByChildof = GenreQuery::create()->findPk($this->childof, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aGenreRelatedByChildof->addGenresRelatedById($this);
             */
        }

        return $this->aGenreRelatedByChildof;
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
        if ('GenreRelatedById' == $relationName) {
            $this->initGenresRelatedById();
        }
        if ('WorkRelatedByGenreId' == $relationName) {
            $this->initWorksRelatedByGenreId();
        }
        if ('WorkRelatedBySubgenreId' == $relationName) {
            $this->initWorksRelatedBySubgenreId();
        }
    }

    /**
     * Clears out the collGenresRelatedById collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Genre The current object (for fluent API support)
     * @see        addGenresRelatedById()
     */
    public function clearGenresRelatedById()
    {
        $this->collGenresRelatedById = null; // important to set this to null since that means it is uninitialized
        $this->collGenresRelatedByIdPartial = null;

        return $this;
    }

    /**
     * reset is the collGenresRelatedById collection loaded partially
     *
     * @return void
     */
    public function resetPartialGenresRelatedById($v = true)
    {
        $this->collGenresRelatedByIdPartial = $v;
    }

    /**
     * Initializes the collGenresRelatedById collection.
     *
     * By default this just sets the collGenresRelatedById collection to an empty array (like clearcollGenresRelatedById());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGenresRelatedById($overrideExisting = true)
    {
        if (null !== $this->collGenresRelatedById && !$overrideExisting) {
            return;
        }
        $this->collGenresRelatedById = new PropelObjectCollection();
        $this->collGenresRelatedById->setModel('Genre');
    }

    /**
     * Gets an array of Genre objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Genre is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Genre[] List of Genre objects
     * @throws PropelException
     */
    public function getGenresRelatedById($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collGenresRelatedByIdPartial && !$this->isNew();
        if (null === $this->collGenresRelatedById || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGenresRelatedById) {
                // return empty collection
                $this->initGenresRelatedById();
            } else {
                $collGenresRelatedById = GenreQuery::create(null, $criteria)
                    ->filterByGenreRelatedByChildof($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collGenresRelatedByIdPartial && count($collGenresRelatedById)) {
                      $this->initGenresRelatedById(false);

                      foreach($collGenresRelatedById as $obj) {
                        if (false == $this->collGenresRelatedById->contains($obj)) {
                          $this->collGenresRelatedById->append($obj);
                        }
                      }

                      $this->collGenresRelatedByIdPartial = true;
                    }

                    return $collGenresRelatedById;
                }

                if($partial && $this->collGenresRelatedById) {
                    foreach($this->collGenresRelatedById as $obj) {
                        if($obj->isNew()) {
                            $collGenresRelatedById[] = $obj;
                        }
                    }
                }

                $this->collGenresRelatedById = $collGenresRelatedById;
                $this->collGenresRelatedByIdPartial = false;
            }
        }

        return $this->collGenresRelatedById;
    }

    /**
     * Sets a collection of GenreRelatedById objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $genresRelatedById A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Genre The current object (for fluent API support)
     */
    public function setGenresRelatedById(PropelCollection $genresRelatedById, PropelPDO $con = null)
    {
        $this->genresRelatedByIdScheduledForDeletion = $this->getGenresRelatedById(new Criteria(), $con)->diff($genresRelatedById);

        foreach ($this->genresRelatedByIdScheduledForDeletion as $genreRelatedByIdRemoved) {
            $genreRelatedByIdRemoved->setGenreRelatedByChildof(null);
        }

        $this->collGenresRelatedById = null;
        foreach ($genresRelatedById as $genreRelatedById) {
            $this->addGenreRelatedById($genreRelatedById);
        }

        $this->collGenresRelatedById = $genresRelatedById;
        $this->collGenresRelatedByIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Genre objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Genre objects.
     * @throws PropelException
     */
    public function countGenresRelatedById(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collGenresRelatedByIdPartial && !$this->isNew();
        if (null === $this->collGenresRelatedById || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGenresRelatedById) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getGenresRelatedById());
            }
            $query = GenreQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGenreRelatedByChildof($this)
                ->count($con);
        }

        return count($this->collGenresRelatedById);
    }

    /**
     * Method called to associate a Genre object to this object
     * through the Genre foreign key attribute.
     *
     * @param    Genre $l Genre
     * @return Genre The current object (for fluent API support)
     */
    public function addGenreRelatedById(Genre $l)
    {
        if ($this->collGenresRelatedById === null) {
            $this->initGenresRelatedById();
            $this->collGenresRelatedByIdPartial = true;
        }
        if (!in_array($l, $this->collGenresRelatedById->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddGenreRelatedById($l);
        }

        return $this;
    }

    /**
     * @param	GenreRelatedById $genreRelatedById The genreRelatedById object to add.
     */
    protected function doAddGenreRelatedById($genreRelatedById)
    {
        $this->collGenresRelatedById[]= $genreRelatedById;
        $genreRelatedById->setGenreRelatedByChildof($this);
    }

    /**
     * @param	GenreRelatedById $genreRelatedById The genreRelatedById object to remove.
     * @return Genre The current object (for fluent API support)
     */
    public function removeGenreRelatedById($genreRelatedById)
    {
        if ($this->getGenresRelatedById()->contains($genreRelatedById)) {
            $this->collGenresRelatedById->remove($this->collGenresRelatedById->search($genreRelatedById));
            if (null === $this->genresRelatedByIdScheduledForDeletion) {
                $this->genresRelatedByIdScheduledForDeletion = clone $this->collGenresRelatedById;
                $this->genresRelatedByIdScheduledForDeletion->clear();
            }
            $this->genresRelatedByIdScheduledForDeletion[]= $genreRelatedById;
            $genreRelatedById->setGenreRelatedByChildof(null);
        }

        return $this;
    }

    /**
     * Clears out the collWorksRelatedByGenreId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Genre The current object (for fluent API support)
     * @see        addWorksRelatedByGenreId()
     */
    public function clearWorksRelatedByGenreId()
    {
        $this->collWorksRelatedByGenreId = null; // important to set this to null since that means it is uninitialized
        $this->collWorksRelatedByGenreIdPartial = null;

        return $this;
    }

    /**
     * reset is the collWorksRelatedByGenreId collection loaded partially
     *
     * @return void
     */
    public function resetPartialWorksRelatedByGenreId($v = true)
    {
        $this->collWorksRelatedByGenreIdPartial = $v;
    }

    /**
     * Initializes the collWorksRelatedByGenreId collection.
     *
     * By default this just sets the collWorksRelatedByGenreId collection to an empty array (like clearcollWorksRelatedByGenreId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initWorksRelatedByGenreId($overrideExisting = true)
    {
        if (null !== $this->collWorksRelatedByGenreId && !$overrideExisting) {
            return;
        }
        $this->collWorksRelatedByGenreId = new PropelObjectCollection();
        $this->collWorksRelatedByGenreId->setModel('Work');
    }

    /**
     * Gets an array of Work objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Genre is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Work[] List of Work objects
     * @throws PropelException
     */
    public function getWorksRelatedByGenreId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collWorksRelatedByGenreIdPartial && !$this->isNew();
        if (null === $this->collWorksRelatedByGenreId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collWorksRelatedByGenreId) {
                // return empty collection
                $this->initWorksRelatedByGenreId();
            } else {
                $collWorksRelatedByGenreId = WorkQuery::create(null, $criteria)
                    ->filterByGenreRelatedByGenreId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collWorksRelatedByGenreIdPartial && count($collWorksRelatedByGenreId)) {
                      $this->initWorksRelatedByGenreId(false);

                      foreach($collWorksRelatedByGenreId as $obj) {
                        if (false == $this->collWorksRelatedByGenreId->contains($obj)) {
                          $this->collWorksRelatedByGenreId->append($obj);
                        }
                      }

                      $this->collWorksRelatedByGenreIdPartial = true;
                    }

                    return $collWorksRelatedByGenreId;
                }

                if($partial && $this->collWorksRelatedByGenreId) {
                    foreach($this->collWorksRelatedByGenreId as $obj) {
                        if($obj->isNew()) {
                            $collWorksRelatedByGenreId[] = $obj;
                        }
                    }
                }

                $this->collWorksRelatedByGenreId = $collWorksRelatedByGenreId;
                $this->collWorksRelatedByGenreIdPartial = false;
            }
        }

        return $this->collWorksRelatedByGenreId;
    }

    /**
     * Sets a collection of WorkRelatedByGenreId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $worksRelatedByGenreId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Genre The current object (for fluent API support)
     */
    public function setWorksRelatedByGenreId(PropelCollection $worksRelatedByGenreId, PropelPDO $con = null)
    {
        $this->worksRelatedByGenreIdScheduledForDeletion = $this->getWorksRelatedByGenreId(new Criteria(), $con)->diff($worksRelatedByGenreId);

        foreach ($this->worksRelatedByGenreIdScheduledForDeletion as $workRelatedByGenreIdRemoved) {
            $workRelatedByGenreIdRemoved->setGenreRelatedByGenreId(null);
        }

        $this->collWorksRelatedByGenreId = null;
        foreach ($worksRelatedByGenreId as $workRelatedByGenreId) {
            $this->addWorkRelatedByGenreId($workRelatedByGenreId);
        }

        $this->collWorksRelatedByGenreId = $worksRelatedByGenreId;
        $this->collWorksRelatedByGenreIdPartial = false;

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
    public function countWorksRelatedByGenreId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collWorksRelatedByGenreIdPartial && !$this->isNew();
        if (null === $this->collWorksRelatedByGenreId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collWorksRelatedByGenreId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getWorksRelatedByGenreId());
            }
            $query = WorkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGenreRelatedByGenreId($this)
                ->count($con);
        }

        return count($this->collWorksRelatedByGenreId);
    }

    /**
     * Method called to associate a Work object to this object
     * through the Work foreign key attribute.
     *
     * @param    Work $l Work
     * @return Genre The current object (for fluent API support)
     */
    public function addWorkRelatedByGenreId(Work $l)
    {
        if ($this->collWorksRelatedByGenreId === null) {
            $this->initWorksRelatedByGenreId();
            $this->collWorksRelatedByGenreIdPartial = true;
        }
        if (!in_array($l, $this->collWorksRelatedByGenreId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddWorkRelatedByGenreId($l);
        }

        return $this;
    }

    /**
     * @param	WorkRelatedByGenreId $workRelatedByGenreId The workRelatedByGenreId object to add.
     */
    protected function doAddWorkRelatedByGenreId($workRelatedByGenreId)
    {
        $this->collWorksRelatedByGenreId[]= $workRelatedByGenreId;
        $workRelatedByGenreId->setGenreRelatedByGenreId($this);
    }

    /**
     * @param	WorkRelatedByGenreId $workRelatedByGenreId The workRelatedByGenreId object to remove.
     * @return Genre The current object (for fluent API support)
     */
    public function removeWorkRelatedByGenreId($workRelatedByGenreId)
    {
        if ($this->getWorksRelatedByGenreId()->contains($workRelatedByGenreId)) {
            $this->collWorksRelatedByGenreId->remove($this->collWorksRelatedByGenreId->search($workRelatedByGenreId));
            if (null === $this->worksRelatedByGenreIdScheduledForDeletion) {
                $this->worksRelatedByGenreIdScheduledForDeletion = clone $this->collWorksRelatedByGenreId;
                $this->worksRelatedByGenreIdScheduledForDeletion->clear();
            }
            $this->worksRelatedByGenreIdScheduledForDeletion[]= $workRelatedByGenreId;
            $workRelatedByGenreId->setGenreRelatedByGenreId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Genre is new, it will return
     * an empty collection; or if this Genre has previously
     * been saved, it will retrieve related WorksRelatedByGenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Genre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedByGenreIdJoinStatus($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('Status', $join_behavior);

        return $this->getWorksRelatedByGenreId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Genre is new, it will return
     * an empty collection; or if this Genre has previously
     * been saved, it will retrieve related WorksRelatedByGenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Genre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedByGenreIdJoinDwdsgenreRelatedByDwdsgenreId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('DwdsgenreRelatedByDwdsgenreId', $join_behavior);

        return $this->getWorksRelatedByGenreId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Genre is new, it will return
     * an empty collection; or if this Genre has previously
     * been saved, it will retrieve related WorksRelatedByGenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Genre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedByGenreIdJoinDwdsgenreRelatedByDwdssubgenreId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('DwdsgenreRelatedByDwdssubgenreId', $join_behavior);

        return $this->getWorksRelatedByGenreId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Genre is new, it will return
     * an empty collection; or if this Genre has previously
     * been saved, it will retrieve related WorksRelatedByGenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Genre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedByGenreIdJoinDatespecification($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('Datespecification', $join_behavior);

        return $this->getWorksRelatedByGenreId($query, $con);
    }

    /**
     * Clears out the collWorksRelatedBySubgenreId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Genre The current object (for fluent API support)
     * @see        addWorksRelatedBySubgenreId()
     */
    public function clearWorksRelatedBySubgenreId()
    {
        $this->collWorksRelatedBySubgenreId = null; // important to set this to null since that means it is uninitialized
        $this->collWorksRelatedBySubgenreIdPartial = null;

        return $this;
    }

    /**
     * reset is the collWorksRelatedBySubgenreId collection loaded partially
     *
     * @return void
     */
    public function resetPartialWorksRelatedBySubgenreId($v = true)
    {
        $this->collWorksRelatedBySubgenreIdPartial = $v;
    }

    /**
     * Initializes the collWorksRelatedBySubgenreId collection.
     *
     * By default this just sets the collWorksRelatedBySubgenreId collection to an empty array (like clearcollWorksRelatedBySubgenreId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initWorksRelatedBySubgenreId($overrideExisting = true)
    {
        if (null !== $this->collWorksRelatedBySubgenreId && !$overrideExisting) {
            return;
        }
        $this->collWorksRelatedBySubgenreId = new PropelObjectCollection();
        $this->collWorksRelatedBySubgenreId->setModel('Work');
    }

    /**
     * Gets an array of Work objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Genre is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Work[] List of Work objects
     * @throws PropelException
     */
    public function getWorksRelatedBySubgenreId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collWorksRelatedBySubgenreIdPartial && !$this->isNew();
        if (null === $this->collWorksRelatedBySubgenreId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collWorksRelatedBySubgenreId) {
                // return empty collection
                $this->initWorksRelatedBySubgenreId();
            } else {
                $collWorksRelatedBySubgenreId = WorkQuery::create(null, $criteria)
                    ->filterByGenreRelatedBySubgenreId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collWorksRelatedBySubgenreIdPartial && count($collWorksRelatedBySubgenreId)) {
                      $this->initWorksRelatedBySubgenreId(false);

                      foreach($collWorksRelatedBySubgenreId as $obj) {
                        if (false == $this->collWorksRelatedBySubgenreId->contains($obj)) {
                          $this->collWorksRelatedBySubgenreId->append($obj);
                        }
                      }

                      $this->collWorksRelatedBySubgenreIdPartial = true;
                    }

                    return $collWorksRelatedBySubgenreId;
                }

                if($partial && $this->collWorksRelatedBySubgenreId) {
                    foreach($this->collWorksRelatedBySubgenreId as $obj) {
                        if($obj->isNew()) {
                            $collWorksRelatedBySubgenreId[] = $obj;
                        }
                    }
                }

                $this->collWorksRelatedBySubgenreId = $collWorksRelatedBySubgenreId;
                $this->collWorksRelatedBySubgenreIdPartial = false;
            }
        }

        return $this->collWorksRelatedBySubgenreId;
    }

    /**
     * Sets a collection of WorkRelatedBySubgenreId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $worksRelatedBySubgenreId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Genre The current object (for fluent API support)
     */
    public function setWorksRelatedBySubgenreId(PropelCollection $worksRelatedBySubgenreId, PropelPDO $con = null)
    {
        $this->worksRelatedBySubgenreIdScheduledForDeletion = $this->getWorksRelatedBySubgenreId(new Criteria(), $con)->diff($worksRelatedBySubgenreId);

        foreach ($this->worksRelatedBySubgenreIdScheduledForDeletion as $workRelatedBySubgenreIdRemoved) {
            $workRelatedBySubgenreIdRemoved->setGenreRelatedBySubgenreId(null);
        }

        $this->collWorksRelatedBySubgenreId = null;
        foreach ($worksRelatedBySubgenreId as $workRelatedBySubgenreId) {
            $this->addWorkRelatedBySubgenreId($workRelatedBySubgenreId);
        }

        $this->collWorksRelatedBySubgenreId = $worksRelatedBySubgenreId;
        $this->collWorksRelatedBySubgenreIdPartial = false;

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
    public function countWorksRelatedBySubgenreId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collWorksRelatedBySubgenreIdPartial && !$this->isNew();
        if (null === $this->collWorksRelatedBySubgenreId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collWorksRelatedBySubgenreId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getWorksRelatedBySubgenreId());
            }
            $query = WorkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGenreRelatedBySubgenreId($this)
                ->count($con);
        }

        return count($this->collWorksRelatedBySubgenreId);
    }

    /**
     * Method called to associate a Work object to this object
     * through the Work foreign key attribute.
     *
     * @param    Work $l Work
     * @return Genre The current object (for fluent API support)
     */
    public function addWorkRelatedBySubgenreId(Work $l)
    {
        if ($this->collWorksRelatedBySubgenreId === null) {
            $this->initWorksRelatedBySubgenreId();
            $this->collWorksRelatedBySubgenreIdPartial = true;
        }
        if (!in_array($l, $this->collWorksRelatedBySubgenreId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddWorkRelatedBySubgenreId($l);
        }

        return $this;
    }

    /**
     * @param	WorkRelatedBySubgenreId $workRelatedBySubgenreId The workRelatedBySubgenreId object to add.
     */
    protected function doAddWorkRelatedBySubgenreId($workRelatedBySubgenreId)
    {
        $this->collWorksRelatedBySubgenreId[]= $workRelatedBySubgenreId;
        $workRelatedBySubgenreId->setGenreRelatedBySubgenreId($this);
    }

    /**
     * @param	WorkRelatedBySubgenreId $workRelatedBySubgenreId The workRelatedBySubgenreId object to remove.
     * @return Genre The current object (for fluent API support)
     */
    public function removeWorkRelatedBySubgenreId($workRelatedBySubgenreId)
    {
        if ($this->getWorksRelatedBySubgenreId()->contains($workRelatedBySubgenreId)) {
            $this->collWorksRelatedBySubgenreId->remove($this->collWorksRelatedBySubgenreId->search($workRelatedBySubgenreId));
            if (null === $this->worksRelatedBySubgenreIdScheduledForDeletion) {
                $this->worksRelatedBySubgenreIdScheduledForDeletion = clone $this->collWorksRelatedBySubgenreId;
                $this->worksRelatedBySubgenreIdScheduledForDeletion->clear();
            }
            $this->worksRelatedBySubgenreIdScheduledForDeletion[]= $workRelatedBySubgenreId;
            $workRelatedBySubgenreId->setGenreRelatedBySubgenreId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Genre is new, it will return
     * an empty collection; or if this Genre has previously
     * been saved, it will retrieve related WorksRelatedBySubgenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Genre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedBySubgenreIdJoinStatus($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('Status', $join_behavior);

        return $this->getWorksRelatedBySubgenreId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Genre is new, it will return
     * an empty collection; or if this Genre has previously
     * been saved, it will retrieve related WorksRelatedBySubgenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Genre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedBySubgenreIdJoinDwdsgenreRelatedByDwdsgenreId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('DwdsgenreRelatedByDwdsgenreId', $join_behavior);

        return $this->getWorksRelatedBySubgenreId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Genre is new, it will return
     * an empty collection; or if this Genre has previously
     * been saved, it will retrieve related WorksRelatedBySubgenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Genre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedBySubgenreIdJoinDwdsgenreRelatedByDwdssubgenreId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('DwdsgenreRelatedByDwdssubgenreId', $join_behavior);

        return $this->getWorksRelatedBySubgenreId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Genre is new, it will return
     * an empty collection; or if this Genre has previously
     * been saved, it will retrieve related WorksRelatedBySubgenreId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Genre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorksRelatedBySubgenreIdJoinDatespecification($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkQuery::create(null, $criteria);
        $query->joinWith('Datespecification', $join_behavior);

        return $this->getWorksRelatedBySubgenreId($query, $con);
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
            if ($this->collGenresRelatedById) {
                foreach ($this->collGenresRelatedById as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWorksRelatedByGenreId) {
                foreach ($this->collWorksRelatedByGenreId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWorksRelatedBySubgenreId) {
                foreach ($this->collWorksRelatedBySubgenreId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collGenresRelatedById instanceof PropelCollection) {
            $this->collGenresRelatedById->clearIterator();
        }
        $this->collGenresRelatedById = null;
        if ($this->collWorksRelatedByGenreId instanceof PropelCollection) {
            $this->collWorksRelatedByGenreId->clearIterator();
        }
        $this->collWorksRelatedByGenreId = null;
        if ($this->collWorksRelatedBySubgenreId instanceof PropelCollection) {
            $this->collWorksRelatedBySubgenreId->clearIterator();
        }
        $this->collWorksRelatedBySubgenreId = null;
        $this->aGenreRelatedByChildof = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(GenrePeer::DEFAULT_STRING_FORMAT);
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
