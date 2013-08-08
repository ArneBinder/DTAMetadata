<?php

namespace DTA\MetadataBundle\Model\Classification\om;

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
use DTA\MetadataBundle\Model\Classification\Genre;
use DTA\MetadataBundle\Model\Classification\GenrePeer;
use DTA\MetadataBundle\Model\Classification\GenreQuery;
use DTA\MetadataBundle\Model\Data\Work;
use DTA\MetadataBundle\Model\Data\WorkQuery;
use DTA\MetadataBundle\Model\Master\GenreWork;
use DTA\MetadataBundle\Model\Master\GenreWorkQuery;

abstract class BaseGenre extends BaseObject implements Persistent, \DTA\MetadataBundle\Model\table_row_view\TableRowViewInterface
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\Classification\\GenrePeer';

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
     * @var        PropelObjectCollection|GenreWork[] Collection to store aggregation of GenreWork objects.
     */
    protected $collGenreWorks;
    protected $collGenreWorksPartial;

    /**
     * @var        PropelObjectCollection|Work[] Collection to store aggregation of Work objects.
     */
    protected $collWorks;

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
    public static $tableRowViewCaptions = array('Id', 'Name', 'Childof', );	public   $tableRowViewAccessors = array('Id'=>'Id', 'Name'=>'Name', 'Childof'=>'Childof', );
    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $worksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $genresRelatedByIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $genreWorksScheduledForDeletion = null;

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
        if ($v !== null && is_numeric($v)) {
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
        if ($v !== null && is_numeric($v)) {
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
        if ($v !== null && is_numeric($v)) {
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

            $this->collGenreWorks = null;

            $this->collWorks = null;
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

            if ($this->worksScheduledForDeletion !== null) {
                if (!$this->worksScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->worksScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    GenreWorkQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->worksScheduledForDeletion = null;
                }

                foreach ($this->getWorks() as $work) {
                    if ($work->isModified()) {
                        $work->save($con);
                    }
                }
            } elseif ($this->collWorks) {
                foreach ($this->collWorks as $work) {
                    if ($work->isModified()) {
                        $work->save($con);
                    }
                }
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

            if ($this->genreWorksScheduledForDeletion !== null) {
                if (!$this->genreWorksScheduledForDeletion->isEmpty()) {
                    GenreWorkQuery::create()
                        ->filterByPrimaryKeys($this->genreWorksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->genreWorksScheduledForDeletion = null;
                }
            }

            if ($this->collGenreWorks !== null) {
                foreach ($this->collGenreWorks as $referrerFK) {
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
        if (null === $this->id) {
            try {
                $stmt = $con->query("SELECT nextval('genre_id_seq')");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $this->id = $row[0];
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(GenrePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '"id"';
        }
        if ($this->isColumnModified(GenrePeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '"name"';
        }
        if ($this->isColumnModified(GenrePeer::CHILDOF)) {
            $modifiedColumns[':p' . $index++]  = '"childof"';
        }

        $sql = sprintf(
            'INSERT INTO "genre" (%s) VALUES (%s)',
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
                    case '"name"':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '"childof"':
                        $stmt->bindValue($identifier, $this->childof, PDO::PARAM_INT);
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

                if ($this->collGenreWorks !== null) {
                    foreach ($this->collGenreWorks as $referrerFK) {
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
            if (null !== $this->collGenreWorks) {
                $result['GenreWorks'] = $this->collGenreWorks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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

            foreach ($this->getGenreWorks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGenreWork($relObj->copy($deepCopy));
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
        if ('GenreWork' == $relationName) {
            $this->initGenreWorks();
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

                    $collGenresRelatedById->getInternalIterator()->rewind();
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
        $genresRelatedByIdToDelete = $this->getGenresRelatedById(new Criteria(), $con)->diff($genresRelatedById);

        $this->genresRelatedByIdScheduledForDeletion = unserialize(serialize($genresRelatedByIdToDelete));

        foreach ($genresRelatedByIdToDelete as $genreRelatedByIdRemoved) {
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
     * Clears out the collGenreWorks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Genre The current object (for fluent API support)
     * @see        addGenreWorks()
     */
    public function clearGenreWorks()
    {
        $this->collGenreWorks = null; // important to set this to null since that means it is uninitialized
        $this->collGenreWorksPartial = null;

        return $this;
    }

    /**
     * reset is the collGenreWorks collection loaded partially
     *
     * @return void
     */
    public function resetPartialGenreWorks($v = true)
    {
        $this->collGenreWorksPartial = $v;
    }

    /**
     * Initializes the collGenreWorks collection.
     *
     * By default this just sets the collGenreWorks collection to an empty array (like clearcollGenreWorks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGenreWorks($overrideExisting = true)
    {
        if (null !== $this->collGenreWorks && !$overrideExisting) {
            return;
        }
        $this->collGenreWorks = new PropelObjectCollection();
        $this->collGenreWorks->setModel('GenreWork');
    }

    /**
     * Gets an array of GenreWork objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Genre is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|GenreWork[] List of GenreWork objects
     * @throws PropelException
     */
    public function getGenreWorks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collGenreWorksPartial && !$this->isNew();
        if (null === $this->collGenreWorks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGenreWorks) {
                // return empty collection
                $this->initGenreWorks();
            } else {
                $collGenreWorks = GenreWorkQuery::create(null, $criteria)
                    ->filterByGenre($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collGenreWorksPartial && count($collGenreWorks)) {
                      $this->initGenreWorks(false);

                      foreach($collGenreWorks as $obj) {
                        if (false == $this->collGenreWorks->contains($obj)) {
                          $this->collGenreWorks->append($obj);
                        }
                      }

                      $this->collGenreWorksPartial = true;
                    }

                    $collGenreWorks->getInternalIterator()->rewind();
                    return $collGenreWorks;
                }

                if($partial && $this->collGenreWorks) {
                    foreach($this->collGenreWorks as $obj) {
                        if($obj->isNew()) {
                            $collGenreWorks[] = $obj;
                        }
                    }
                }

                $this->collGenreWorks = $collGenreWorks;
                $this->collGenreWorksPartial = false;
            }
        }

        return $this->collGenreWorks;
    }

    /**
     * Sets a collection of GenreWork objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $genreWorks A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Genre The current object (for fluent API support)
     */
    public function setGenreWorks(PropelCollection $genreWorks, PropelPDO $con = null)
    {
        $genreWorksToDelete = $this->getGenreWorks(new Criteria(), $con)->diff($genreWorks);

        $this->genreWorksScheduledForDeletion = unserialize(serialize($genreWorksToDelete));

        foreach ($genreWorksToDelete as $genreWorkRemoved) {
            $genreWorkRemoved->setGenre(null);
        }

        $this->collGenreWorks = null;
        foreach ($genreWorks as $genreWork) {
            $this->addGenreWork($genreWork);
        }

        $this->collGenreWorks = $genreWorks;
        $this->collGenreWorksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related GenreWork objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related GenreWork objects.
     * @throws PropelException
     */
    public function countGenreWorks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collGenreWorksPartial && !$this->isNew();
        if (null === $this->collGenreWorks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGenreWorks) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getGenreWorks());
            }
            $query = GenreWorkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGenre($this)
                ->count($con);
        }

        return count($this->collGenreWorks);
    }

    /**
     * Method called to associate a GenreWork object to this object
     * through the GenreWork foreign key attribute.
     *
     * @param    GenreWork $l GenreWork
     * @return Genre The current object (for fluent API support)
     */
    public function addGenreWork(GenreWork $l)
    {
        if ($this->collGenreWorks === null) {
            $this->initGenreWorks();
            $this->collGenreWorksPartial = true;
        }
        if (!in_array($l, $this->collGenreWorks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddGenreWork($l);
        }

        return $this;
    }

    /**
     * @param	GenreWork $genreWork The genreWork object to add.
     */
    protected function doAddGenreWork($genreWork)
    {
        $this->collGenreWorks[]= $genreWork;
        $genreWork->setGenre($this);
    }

    /**
     * @param	GenreWork $genreWork The genreWork object to remove.
     * @return Genre The current object (for fluent API support)
     */
    public function removeGenreWork($genreWork)
    {
        if ($this->getGenreWorks()->contains($genreWork)) {
            $this->collGenreWorks->remove($this->collGenreWorks->search($genreWork));
            if (null === $this->genreWorksScheduledForDeletion) {
                $this->genreWorksScheduledForDeletion = clone $this->collGenreWorks;
                $this->genreWorksScheduledForDeletion->clear();
            }
            $this->genreWorksScheduledForDeletion[]= clone $genreWork;
            $genreWork->setGenre(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Genre is new, it will return
     * an empty collection; or if this Genre has previously
     * been saved, it will retrieve related GenreWorks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Genre.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|GenreWork[] List of GenreWork objects
     */
    public function getGenreWorksJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = GenreWorkQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getGenreWorks($query, $con);
    }

    /**
     * Clears out the collWorks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Genre The current object (for fluent API support)
     * @see        addWorks()
     */
    public function clearWorks()
    {
        $this->collWorks = null; // important to set this to null since that means it is uninitialized
        $this->collWorksPartial = null;

        return $this;
    }

    /**
     * Initializes the collWorks collection.
     *
     * By default this just sets the collWorks collection to an empty collection (like clearWorks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initWorks()
    {
        $this->collWorks = new PropelObjectCollection();
        $this->collWorks->setModel('Work');
    }

    /**
     * Gets a collection of Work objects related by a many-to-many relationship
     * to the current object by way of the genre_work cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Genre is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Work[] List of Work objects
     */
    public function getWorks($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collWorks || null !== $criteria) {
            if ($this->isNew() && null === $this->collWorks) {
                // return empty collection
                $this->initWorks();
            } else {
                $collWorks = WorkQuery::create(null, $criteria)
                    ->filterByGenre($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collWorks;
                }
                $this->collWorks = $collWorks;
            }
        }

        return $this->collWorks;
    }

    /**
     * Sets a collection of Work objects related by a many-to-many relationship
     * to the current object by way of the genre_work cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $works A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Genre The current object (for fluent API support)
     */
    public function setWorks(PropelCollection $works, PropelPDO $con = null)
    {
        $this->clearWorks();
        $currentWorks = $this->getWorks();

        $this->worksScheduledForDeletion = $currentWorks->diff($works);

        foreach ($works as $work) {
            if (!$currentWorks->contains($work)) {
                $this->doAddWork($work);
            }
        }

        $this->collWorks = $works;

        return $this;
    }

    /**
     * Gets the number of Work objects related by a many-to-many relationship
     * to the current object by way of the genre_work cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Work objects
     */
    public function countWorks($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collWorks || null !== $criteria) {
            if ($this->isNew() && null === $this->collWorks) {
                return 0;
            } else {
                $query = WorkQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByGenre($this)
                    ->count($con);
            }
        } else {
            return count($this->collWorks);
        }
    }

    /**
     * Associate a Work object to this object
     * through the genre_work cross reference table.
     *
     * @param  Work $work The GenreWork object to relate
     * @return Genre The current object (for fluent API support)
     */
    public function addWork(Work $work)
    {
        if ($this->collWorks === null) {
            $this->initWorks();
        }
        if (!$this->collWorks->contains($work)) { // only add it if the **same** object is not already associated
            $this->doAddWork($work);

            $this->collWorks[]= $work;
        }

        return $this;
    }

    /**
     * @param	Work $work The work object to add.
     */
    protected function doAddWork($work)
    {
        $genreWork = new GenreWork();
        $genreWork->setWork($work);
        $this->addGenreWork($genreWork);
    }

    /**
     * Remove a Work object to this object
     * through the genre_work cross reference table.
     *
     * @param Work $work The GenreWork object to relate
     * @return Genre The current object (for fluent API support)
     */
    public function removeWork(Work $work)
    {
        if ($this->getWorks()->contains($work)) {
            $this->collWorks->remove($this->collWorks->search($work));
            if (null === $this->worksScheduledForDeletion) {
                $this->worksScheduledForDeletion = clone $this->collWorks;
                $this->worksScheduledForDeletion->clear();
            }
            $this->worksScheduledForDeletion[]= $work;
        }

        return $this;
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
            if ($this->collGenresRelatedById) {
                foreach ($this->collGenresRelatedById as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGenreWorks) {
                foreach ($this->collGenreWorks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWorks) {
                foreach ($this->collWorks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aGenreRelatedByChildof instanceof Persistent) {
              $this->aGenreRelatedByChildof->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collGenresRelatedById instanceof PropelCollection) {
            $this->collGenresRelatedById->clearIterator();
        }
        $this->collGenresRelatedById = null;
        if ($this->collGenreWorks instanceof PropelCollection) {
            $this->collGenreWorks->clearIterator();
        }
        $this->collGenreWorks = null;
        if ($this->collWorks instanceof PropelCollection) {
            $this->collWorks->clearIterator();
        }
        $this->collWorks = null;
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
