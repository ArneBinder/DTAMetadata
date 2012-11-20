<?php

namespace DTA\MetadataBundle\Model\HistoricalPerson\om;

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
use DTA\MetadataBundle\Model\Description\Personalname;
use DTA\MetadataBundle\Model\Description\PersonalnameQuery;
use DTA\MetadataBundle\Model\HistoricalPerson\Author;
use DTA\MetadataBundle\Model\HistoricalPerson\AuthorQuery;
use DTA\MetadataBundle\Model\HistoricalPerson\Person;
use DTA\MetadataBundle\Model\HistoricalPerson\PersonPeer;
use DTA\MetadataBundle\Model\HistoricalPerson\PersonQuery;
use DTA\MetadataBundle\Model\HistoricalPerson\Printer;
use DTA\MetadataBundle\Model\HistoricalPerson\PrinterQuery;
use DTA\MetadataBundle\Model\HistoricalPerson\Publisher;
use DTA\MetadataBundle\Model\HistoricalPerson\PublisherQuery;
use DTA\MetadataBundle\Model\HistoricalPerson\Translator;
use DTA\MetadataBundle\Model\HistoricalPerson\TranslatorQuery;

abstract class BasePerson extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\HistoricalPerson\\PersonPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PersonPeer
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
     * The value for the gnd field.
     * @var        string
     */
    protected $gnd;

    /**
     * @var        PropelObjectCollection|Author[] Collection to store aggregation of Author objects.
     */
    protected $collAuthors;
    protected $collAuthorsPartial;

    /**
     * @var        PropelObjectCollection|Personalname[] Collection to store aggregation of Personalname objects.
     */
    protected $collPersonalnames;
    protected $collPersonalnamesPartial;

    /**
     * @var        PropelObjectCollection|Printer[] Collection to store aggregation of Printer objects.
     */
    protected $collPrinters;
    protected $collPrintersPartial;

    /**
     * @var        PropelObjectCollection|Publisher[] Collection to store aggregation of Publisher objects.
     */
    protected $collPublishers;
    protected $collPublishersPartial;

    /**
     * @var        PropelObjectCollection|Translator[] Collection to store aggregation of Translator objects.
     */
    protected $collTranslators;
    protected $collTranslatorsPartial;

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
    protected $personalnamesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $printersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publishersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $translatorsScheduledForDeletion = null;

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
     * Get the [gnd] column value.
     *
     * @return string
     */
    public function getGnd()
    {
        return $this->gnd;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Person The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PersonPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [gnd] column.
     *
     * @param string $v new value
     * @return Person The current object (for fluent API support)
     */
    public function setGnd($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->gnd !== $v) {
            $this->gnd = $v;
            $this->modifiedColumns[] = PersonPeer::GND;
        }


        return $this;
    } // setGnd()

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
            $this->gnd = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 2; // 2 = PersonPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Person object", $e);
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
            $con = Propel::getConnection(PersonPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PersonPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collAuthors = null;

            $this->collPersonalnames = null;

            $this->collPrinters = null;

            $this->collPublishers = null;

            $this->collTranslators = null;

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
            $con = Propel::getConnection(PersonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PersonQuery::create()
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
            $con = Propel::getConnection(PersonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                PersonPeer::addInstanceToPool($this);
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

            if ($this->authorsScheduledForDeletion !== null) {
                if (!$this->authorsScheduledForDeletion->isEmpty()) {
                    AuthorQuery::create()
                        ->filterByPrimaryKeys($this->authorsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->authorsScheduledForDeletion = null;
                }
            }

            if ($this->collAuthors !== null) {
                foreach ($this->collAuthors as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->personalnamesScheduledForDeletion !== null) {
                if (!$this->personalnamesScheduledForDeletion->isEmpty()) {
                    PersonalnameQuery::create()
                        ->filterByPrimaryKeys($this->personalnamesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->personalnamesScheduledForDeletion = null;
                }
            }

            if ($this->collPersonalnames !== null) {
                foreach ($this->collPersonalnames as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->printersScheduledForDeletion !== null) {
                if (!$this->printersScheduledForDeletion->isEmpty()) {
                    PrinterQuery::create()
                        ->filterByPrimaryKeys($this->printersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->printersScheduledForDeletion = null;
                }
            }

            if ($this->collPrinters !== null) {
                foreach ($this->collPrinters as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->publishersScheduledForDeletion !== null) {
                if (!$this->publishersScheduledForDeletion->isEmpty()) {
                    PublisherQuery::create()
                        ->filterByPrimaryKeys($this->publishersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publishersScheduledForDeletion = null;
                }
            }

            if ($this->collPublishers !== null) {
                foreach ($this->collPublishers as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->translatorsScheduledForDeletion !== null) {
                if (!$this->translatorsScheduledForDeletion->isEmpty()) {
                    TranslatorQuery::create()
                        ->filterByPrimaryKeys($this->translatorsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->translatorsScheduledForDeletion = null;
                }
            }

            if ($this->collTranslators !== null) {
                foreach ($this->collTranslators as $referrerFK) {
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

        $this->modifiedColumns[] = PersonPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PersonPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PersonPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(PersonPeer::GND)) {
            $modifiedColumns[':p' . $index++]  = '`GND`';
        }

        $sql = sprintf(
            'INSERT INTO `person` (%s) VALUES (%s)',
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
                    case '`GND`':
                        $stmt->bindValue($identifier, $this->gnd, PDO::PARAM_STR);
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


            if (($retval = PersonPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collAuthors !== null) {
                    foreach ($this->collAuthors as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPersonalnames !== null) {
                    foreach ($this->collPersonalnames as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPrinters !== null) {
                    foreach ($this->collPrinters as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublishers !== null) {
                    foreach ($this->collPublishers as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collTranslators !== null) {
                    foreach ($this->collTranslators as $referrerFK) {
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
        $pos = PersonPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getGnd();
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
        if (isset($alreadyDumpedObjects['Person'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Person'][$this->getPrimaryKey()] = true;
        $keys = PersonPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getGnd(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->collAuthors) {
                $result['Authors'] = $this->collAuthors->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPersonalnames) {
                $result['Personalnames'] = $this->collPersonalnames->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPrinters) {
                $result['Printers'] = $this->collPrinters->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublishers) {
                $result['Publishers'] = $this->collPublishers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTranslators) {
                $result['Translators'] = $this->collTranslators->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PersonPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setGnd($value);
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
        $keys = PersonPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setGnd($arr[$keys[1]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PersonPeer::DATABASE_NAME);

        if ($this->isColumnModified(PersonPeer::ID)) $criteria->add(PersonPeer::ID, $this->id);
        if ($this->isColumnModified(PersonPeer::GND)) $criteria->add(PersonPeer::GND, $this->gnd);

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
        $criteria = new Criteria(PersonPeer::DATABASE_NAME);
        $criteria->add(PersonPeer::ID, $this->id);

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
     * @param object $copyObj An object of Person (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setGnd($this->getGnd());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getAuthors() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAuthor($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPersonalnames() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPersonalname($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPrinters() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPrinter($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublishers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublisher($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTranslators() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTranslator($relObj->copy($deepCopy));
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
     * @return Person Clone of current object.
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
     * @return PersonPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PersonPeer();
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
        if ('Author' == $relationName) {
            $this->initAuthors();
        }
        if ('Personalname' == $relationName) {
            $this->initPersonalnames();
        }
        if ('Printer' == $relationName) {
            $this->initPrinters();
        }
        if ('Publisher' == $relationName) {
            $this->initPublishers();
        }
        if ('Translator' == $relationName) {
            $this->initTranslators();
        }
    }

    /**
     * Clears out the collAuthors collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Person The current object (for fluent API support)
     * @see        addAuthors()
     */
    public function clearAuthors()
    {
        $this->collAuthors = null; // important to set this to null since that means it is uninitialized
        $this->collAuthorsPartial = null;

        return $this;
    }

    /**
     * reset is the collAuthors collection loaded partially
     *
     * @return void
     */
    public function resetPartialAuthors($v = true)
    {
        $this->collAuthorsPartial = $v;
    }

    /**
     * Initializes the collAuthors collection.
     *
     * By default this just sets the collAuthors collection to an empty array (like clearcollAuthors());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAuthors($overrideExisting = true)
    {
        if (null !== $this->collAuthors && !$overrideExisting) {
            return;
        }
        $this->collAuthors = new PropelObjectCollection();
        $this->collAuthors->setModel('Author');
    }

    /**
     * Gets an array of Author objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Person is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Author[] List of Author objects
     * @throws PropelException
     */
    public function getAuthors($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collAuthorsPartial && !$this->isNew();
        if (null === $this->collAuthors || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAuthors) {
                // return empty collection
                $this->initAuthors();
            } else {
                $collAuthors = AuthorQuery::create(null, $criteria)
                    ->filterByPerson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collAuthorsPartial && count($collAuthors)) {
                      $this->initAuthors(false);

                      foreach($collAuthors as $obj) {
                        if (false == $this->collAuthors->contains($obj)) {
                          $this->collAuthors->append($obj);
                        }
                      }

                      $this->collAuthorsPartial = true;
                    }

                    return $collAuthors;
                }

                if($partial && $this->collAuthors) {
                    foreach($this->collAuthors as $obj) {
                        if($obj->isNew()) {
                            $collAuthors[] = $obj;
                        }
                    }
                }

                $this->collAuthors = $collAuthors;
                $this->collAuthorsPartial = false;
            }
        }

        return $this->collAuthors;
    }

    /**
     * Sets a collection of Author objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $authors A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Person The current object (for fluent API support)
     */
    public function setAuthors(PropelCollection $authors, PropelPDO $con = null)
    {
        $this->authorsScheduledForDeletion = $this->getAuthors(new Criteria(), $con)->diff($authors);

        foreach ($this->authorsScheduledForDeletion as $authorRemoved) {
            $authorRemoved->setPerson(null);
        }

        $this->collAuthors = null;
        foreach ($authors as $author) {
            $this->addAuthor($author);
        }

        $this->collAuthors = $authors;
        $this->collAuthorsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Author objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Author objects.
     * @throws PropelException
     */
    public function countAuthors(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collAuthorsPartial && !$this->isNew();
        if (null === $this->collAuthors || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAuthors) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getAuthors());
                }
                $query = AuthorQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPerson($this)
                    ->count($con);
            }
        } else {
            return count($this->collAuthors);
        }
    }

    /**
     * Method called to associate a Author object to this object
     * through the Author foreign key attribute.
     *
     * @param    Author $l Author
     * @return Person The current object (for fluent API support)
     */
    public function addAuthor(Author $l)
    {
        if ($this->collAuthors === null) {
            $this->initAuthors();
            $this->collAuthorsPartial = true;
        }
        if (!in_array($l, $this->collAuthors->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddAuthor($l);
        }

        return $this;
    }

    /**
     * @param	Author $author The author object to add.
     */
    protected function doAddAuthor($author)
    {
        $this->collAuthors[]= $author;
        $author->setPerson($this);
    }

    /**
     * @param	Author $author The author object to remove.
     * @return Person The current object (for fluent API support)
     */
    public function removeAuthor($author)
    {
        if ($this->getAuthors()->contains($author)) {
            $this->collAuthors->remove($this->collAuthors->search($author));
            if (null === $this->authorsScheduledForDeletion) {
                $this->authorsScheduledForDeletion = clone $this->collAuthors;
                $this->authorsScheduledForDeletion->clear();
            }
            $this->authorsScheduledForDeletion[]= $author;
            $author->setPerson(null);
        }

        return $this;
    }

    /**
     * Clears out the collPersonalnames collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Person The current object (for fluent API support)
     * @see        addPersonalnames()
     */
    public function clearPersonalnames()
    {
        $this->collPersonalnames = null; // important to set this to null since that means it is uninitialized
        $this->collPersonalnamesPartial = null;

        return $this;
    }

    /**
     * reset is the collPersonalnames collection loaded partially
     *
     * @return void
     */
    public function resetPartialPersonalnames($v = true)
    {
        $this->collPersonalnamesPartial = $v;
    }

    /**
     * Initializes the collPersonalnames collection.
     *
     * By default this just sets the collPersonalnames collection to an empty array (like clearcollPersonalnames());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPersonalnames($overrideExisting = true)
    {
        if (null !== $this->collPersonalnames && !$overrideExisting) {
            return;
        }
        $this->collPersonalnames = new PropelObjectCollection();
        $this->collPersonalnames->setModel('Personalname');
    }

    /**
     * Gets an array of Personalname objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Person is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Personalname[] List of Personalname objects
     * @throws PropelException
     */
    public function getPersonalnames($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPersonalnamesPartial && !$this->isNew();
        if (null === $this->collPersonalnames || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPersonalnames) {
                // return empty collection
                $this->initPersonalnames();
            } else {
                $collPersonalnames = PersonalnameQuery::create(null, $criteria)
                    ->filterByPerson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPersonalnamesPartial && count($collPersonalnames)) {
                      $this->initPersonalnames(false);

                      foreach($collPersonalnames as $obj) {
                        if (false == $this->collPersonalnames->contains($obj)) {
                          $this->collPersonalnames->append($obj);
                        }
                      }

                      $this->collPersonalnamesPartial = true;
                    }

                    return $collPersonalnames;
                }

                if($partial && $this->collPersonalnames) {
                    foreach($this->collPersonalnames as $obj) {
                        if($obj->isNew()) {
                            $collPersonalnames[] = $obj;
                        }
                    }
                }

                $this->collPersonalnames = $collPersonalnames;
                $this->collPersonalnamesPartial = false;
            }
        }

        return $this->collPersonalnames;
    }

    /**
     * Sets a collection of Personalname objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $personalnames A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Person The current object (for fluent API support)
     */
    public function setPersonalnames(PropelCollection $personalnames, PropelPDO $con = null)
    {
        $this->personalnamesScheduledForDeletion = $this->getPersonalnames(new Criteria(), $con)->diff($personalnames);

        foreach ($this->personalnamesScheduledForDeletion as $personalnameRemoved) {
            $personalnameRemoved->setPerson(null);
        }

        $this->collPersonalnames = null;
        foreach ($personalnames as $personalname) {
            $this->addPersonalname($personalname);
        }

        $this->collPersonalnames = $personalnames;
        $this->collPersonalnamesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Personalname objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Personalname objects.
     * @throws PropelException
     */
    public function countPersonalnames(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPersonalnamesPartial && !$this->isNew();
        if (null === $this->collPersonalnames || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPersonalnames) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getPersonalnames());
                }
                $query = PersonalnameQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPerson($this)
                    ->count($con);
            }
        } else {
            return count($this->collPersonalnames);
        }
    }

    /**
     * Method called to associate a Personalname object to this object
     * through the Personalname foreign key attribute.
     *
     * @param    Personalname $l Personalname
     * @return Person The current object (for fluent API support)
     */
    public function addPersonalname(Personalname $l)
    {
        if ($this->collPersonalnames === null) {
            $this->initPersonalnames();
            $this->collPersonalnamesPartial = true;
        }
        if (!in_array($l, $this->collPersonalnames->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPersonalname($l);
        }

        return $this;
    }

    /**
     * @param	Personalname $personalname The personalname object to add.
     */
    protected function doAddPersonalname($personalname)
    {
        $this->collPersonalnames[]= $personalname;
        $personalname->setPerson($this);
    }

    /**
     * @param	Personalname $personalname The personalname object to remove.
     * @return Person The current object (for fluent API support)
     */
    public function removePersonalname($personalname)
    {
        if ($this->getPersonalnames()->contains($personalname)) {
            $this->collPersonalnames->remove($this->collPersonalnames->search($personalname));
            if (null === $this->personalnamesScheduledForDeletion) {
                $this->personalnamesScheduledForDeletion = clone $this->collPersonalnames;
                $this->personalnamesScheduledForDeletion->clear();
            }
            $this->personalnamesScheduledForDeletion[]= $personalname;
            $personalname->setPerson(null);
        }

        return $this;
    }

    /**
     * Clears out the collPrinters collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Person The current object (for fluent API support)
     * @see        addPrinters()
     */
    public function clearPrinters()
    {
        $this->collPrinters = null; // important to set this to null since that means it is uninitialized
        $this->collPrintersPartial = null;

        return $this;
    }

    /**
     * reset is the collPrinters collection loaded partially
     *
     * @return void
     */
    public function resetPartialPrinters($v = true)
    {
        $this->collPrintersPartial = $v;
    }

    /**
     * Initializes the collPrinters collection.
     *
     * By default this just sets the collPrinters collection to an empty array (like clearcollPrinters());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPrinters($overrideExisting = true)
    {
        if (null !== $this->collPrinters && !$overrideExisting) {
            return;
        }
        $this->collPrinters = new PropelObjectCollection();
        $this->collPrinters->setModel('Printer');
    }

    /**
     * Gets an array of Printer objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Person is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Printer[] List of Printer objects
     * @throws PropelException
     */
    public function getPrinters($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPrintersPartial && !$this->isNew();
        if (null === $this->collPrinters || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPrinters) {
                // return empty collection
                $this->initPrinters();
            } else {
                $collPrinters = PrinterQuery::create(null, $criteria)
                    ->filterByPerson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPrintersPartial && count($collPrinters)) {
                      $this->initPrinters(false);

                      foreach($collPrinters as $obj) {
                        if (false == $this->collPrinters->contains($obj)) {
                          $this->collPrinters->append($obj);
                        }
                      }

                      $this->collPrintersPartial = true;
                    }

                    return $collPrinters;
                }

                if($partial && $this->collPrinters) {
                    foreach($this->collPrinters as $obj) {
                        if($obj->isNew()) {
                            $collPrinters[] = $obj;
                        }
                    }
                }

                $this->collPrinters = $collPrinters;
                $this->collPrintersPartial = false;
            }
        }

        return $this->collPrinters;
    }

    /**
     * Sets a collection of Printer objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $printers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Person The current object (for fluent API support)
     */
    public function setPrinters(PropelCollection $printers, PropelPDO $con = null)
    {
        $this->printersScheduledForDeletion = $this->getPrinters(new Criteria(), $con)->diff($printers);

        foreach ($this->printersScheduledForDeletion as $printerRemoved) {
            $printerRemoved->setPerson(null);
        }

        $this->collPrinters = null;
        foreach ($printers as $printer) {
            $this->addPrinter($printer);
        }

        $this->collPrinters = $printers;
        $this->collPrintersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Printer objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Printer objects.
     * @throws PropelException
     */
    public function countPrinters(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPrintersPartial && !$this->isNew();
        if (null === $this->collPrinters || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPrinters) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getPrinters());
                }
                $query = PrinterQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPerson($this)
                    ->count($con);
            }
        } else {
            return count($this->collPrinters);
        }
    }

    /**
     * Method called to associate a Printer object to this object
     * through the Printer foreign key attribute.
     *
     * @param    Printer $l Printer
     * @return Person The current object (for fluent API support)
     */
    public function addPrinter(Printer $l)
    {
        if ($this->collPrinters === null) {
            $this->initPrinters();
            $this->collPrintersPartial = true;
        }
        if (!in_array($l, $this->collPrinters->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPrinter($l);
        }

        return $this;
    }

    /**
     * @param	Printer $printer The printer object to add.
     */
    protected function doAddPrinter($printer)
    {
        $this->collPrinters[]= $printer;
        $printer->setPerson($this);
    }

    /**
     * @param	Printer $printer The printer object to remove.
     * @return Person The current object (for fluent API support)
     */
    public function removePrinter($printer)
    {
        if ($this->getPrinters()->contains($printer)) {
            $this->collPrinters->remove($this->collPrinters->search($printer));
            if (null === $this->printersScheduledForDeletion) {
                $this->printersScheduledForDeletion = clone $this->collPrinters;
                $this->printersScheduledForDeletion->clear();
            }
            $this->printersScheduledForDeletion[]= $printer;
            $printer->setPerson(null);
        }

        return $this;
    }

    /**
     * Clears out the collPublishers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Person The current object (for fluent API support)
     * @see        addPublishers()
     */
    public function clearPublishers()
    {
        $this->collPublishers = null; // important to set this to null since that means it is uninitialized
        $this->collPublishersPartial = null;

        return $this;
    }

    /**
     * reset is the collPublishers collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublishers($v = true)
    {
        $this->collPublishersPartial = $v;
    }

    /**
     * Initializes the collPublishers collection.
     *
     * By default this just sets the collPublishers collection to an empty array (like clearcollPublishers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublishers($overrideExisting = true)
    {
        if (null !== $this->collPublishers && !$overrideExisting) {
            return;
        }
        $this->collPublishers = new PropelObjectCollection();
        $this->collPublishers->setModel('Publisher');
    }

    /**
     * Gets an array of Publisher objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Person is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Publisher[] List of Publisher objects
     * @throws PropelException
     */
    public function getPublishers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublishersPartial && !$this->isNew();
        if (null === $this->collPublishers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublishers) {
                // return empty collection
                $this->initPublishers();
            } else {
                $collPublishers = PublisherQuery::create(null, $criteria)
                    ->filterByPerson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublishersPartial && count($collPublishers)) {
                      $this->initPublishers(false);

                      foreach($collPublishers as $obj) {
                        if (false == $this->collPublishers->contains($obj)) {
                          $this->collPublishers->append($obj);
                        }
                      }

                      $this->collPublishersPartial = true;
                    }

                    return $collPublishers;
                }

                if($partial && $this->collPublishers) {
                    foreach($this->collPublishers as $obj) {
                        if($obj->isNew()) {
                            $collPublishers[] = $obj;
                        }
                    }
                }

                $this->collPublishers = $collPublishers;
                $this->collPublishersPartial = false;
            }
        }

        return $this->collPublishers;
    }

    /**
     * Sets a collection of Publisher objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publishers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Person The current object (for fluent API support)
     */
    public function setPublishers(PropelCollection $publishers, PropelPDO $con = null)
    {
        $this->publishersScheduledForDeletion = $this->getPublishers(new Criteria(), $con)->diff($publishers);

        foreach ($this->publishersScheduledForDeletion as $publisherRemoved) {
            $publisherRemoved->setPerson(null);
        }

        $this->collPublishers = null;
        foreach ($publishers as $publisher) {
            $this->addPublisher($publisher);
        }

        $this->collPublishers = $publishers;
        $this->collPublishersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Publisher objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Publisher objects.
     * @throws PropelException
     */
    public function countPublishers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublishersPartial && !$this->isNew();
        if (null === $this->collPublishers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublishers) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getPublishers());
                }
                $query = PublisherQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPerson($this)
                    ->count($con);
            }
        } else {
            return count($this->collPublishers);
        }
    }

    /**
     * Method called to associate a Publisher object to this object
     * through the Publisher foreign key attribute.
     *
     * @param    Publisher $l Publisher
     * @return Person The current object (for fluent API support)
     */
    public function addPublisher(Publisher $l)
    {
        if ($this->collPublishers === null) {
            $this->initPublishers();
            $this->collPublishersPartial = true;
        }
        if (!in_array($l, $this->collPublishers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublisher($l);
        }

        return $this;
    }

    /**
     * @param	Publisher $publisher The publisher object to add.
     */
    protected function doAddPublisher($publisher)
    {
        $this->collPublishers[]= $publisher;
        $publisher->setPerson($this);
    }

    /**
     * @param	Publisher $publisher The publisher object to remove.
     * @return Person The current object (for fluent API support)
     */
    public function removePublisher($publisher)
    {
        if ($this->getPublishers()->contains($publisher)) {
            $this->collPublishers->remove($this->collPublishers->search($publisher));
            if (null === $this->publishersScheduledForDeletion) {
                $this->publishersScheduledForDeletion = clone $this->collPublishers;
                $this->publishersScheduledForDeletion->clear();
            }
            $this->publishersScheduledForDeletion[]= $publisher;
            $publisher->setPerson(null);
        }

        return $this;
    }

    /**
     * Clears out the collTranslators collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Person The current object (for fluent API support)
     * @see        addTranslators()
     */
    public function clearTranslators()
    {
        $this->collTranslators = null; // important to set this to null since that means it is uninitialized
        $this->collTranslatorsPartial = null;

        return $this;
    }

    /**
     * reset is the collTranslators collection loaded partially
     *
     * @return void
     */
    public function resetPartialTranslators($v = true)
    {
        $this->collTranslatorsPartial = $v;
    }

    /**
     * Initializes the collTranslators collection.
     *
     * By default this just sets the collTranslators collection to an empty array (like clearcollTranslators());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTranslators($overrideExisting = true)
    {
        if (null !== $this->collTranslators && !$overrideExisting) {
            return;
        }
        $this->collTranslators = new PropelObjectCollection();
        $this->collTranslators->setModel('Translator');
    }

    /**
     * Gets an array of Translator objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Person is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Translator[] List of Translator objects
     * @throws PropelException
     */
    public function getTranslators($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collTranslatorsPartial && !$this->isNew();
        if (null === $this->collTranslators || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTranslators) {
                // return empty collection
                $this->initTranslators();
            } else {
                $collTranslators = TranslatorQuery::create(null, $criteria)
                    ->filterByPerson($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collTranslatorsPartial && count($collTranslators)) {
                      $this->initTranslators(false);

                      foreach($collTranslators as $obj) {
                        if (false == $this->collTranslators->contains($obj)) {
                          $this->collTranslators->append($obj);
                        }
                      }

                      $this->collTranslatorsPartial = true;
                    }

                    return $collTranslators;
                }

                if($partial && $this->collTranslators) {
                    foreach($this->collTranslators as $obj) {
                        if($obj->isNew()) {
                            $collTranslators[] = $obj;
                        }
                    }
                }

                $this->collTranslators = $collTranslators;
                $this->collTranslatorsPartial = false;
            }
        }

        return $this->collTranslators;
    }

    /**
     * Sets a collection of Translator objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $translators A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Person The current object (for fluent API support)
     */
    public function setTranslators(PropelCollection $translators, PropelPDO $con = null)
    {
        $this->translatorsScheduledForDeletion = $this->getTranslators(new Criteria(), $con)->diff($translators);

        foreach ($this->translatorsScheduledForDeletion as $translatorRemoved) {
            $translatorRemoved->setPerson(null);
        }

        $this->collTranslators = null;
        foreach ($translators as $translator) {
            $this->addTranslator($translator);
        }

        $this->collTranslators = $translators;
        $this->collTranslatorsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Translator objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Translator objects.
     * @throws PropelException
     */
    public function countTranslators(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collTranslatorsPartial && !$this->isNew();
        if (null === $this->collTranslators || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTranslators) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getTranslators());
                }
                $query = TranslatorQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPerson($this)
                    ->count($con);
            }
        } else {
            return count($this->collTranslators);
        }
    }

    /**
     * Method called to associate a Translator object to this object
     * through the Translator foreign key attribute.
     *
     * @param    Translator $l Translator
     * @return Person The current object (for fluent API support)
     */
    public function addTranslator(Translator $l)
    {
        if ($this->collTranslators === null) {
            $this->initTranslators();
            $this->collTranslatorsPartial = true;
        }
        if (!in_array($l, $this->collTranslators->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddTranslator($l);
        }

        return $this;
    }

    /**
     * @param	Translator $translator The translator object to add.
     */
    protected function doAddTranslator($translator)
    {
        $this->collTranslators[]= $translator;
        $translator->setPerson($this);
    }

    /**
     * @param	Translator $translator The translator object to remove.
     * @return Person The current object (for fluent API support)
     */
    public function removeTranslator($translator)
    {
        if ($this->getTranslators()->contains($translator)) {
            $this->collTranslators->remove($this->collTranslators->search($translator));
            if (null === $this->translatorsScheduledForDeletion) {
                $this->translatorsScheduledForDeletion = clone $this->collTranslators;
                $this->translatorsScheduledForDeletion->clear();
            }
            $this->translatorsScheduledForDeletion[]= $translator;
            $translator->setPerson(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->gnd = null;
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
            if ($this->collAuthors) {
                foreach ($this->collAuthors as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPersonalnames) {
                foreach ($this->collPersonalnames as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPrinters) {
                foreach ($this->collPrinters as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublishers) {
                foreach ($this->collPublishers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTranslators) {
                foreach ($this->collTranslators as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collAuthors instanceof PropelCollection) {
            $this->collAuthors->clearIterator();
        }
        $this->collAuthors = null;
        if ($this->collPersonalnames instanceof PropelCollection) {
            $this->collPersonalnames->clearIterator();
        }
        $this->collPersonalnames = null;
        if ($this->collPrinters instanceof PropelCollection) {
            $this->collPrinters->clearIterator();
        }
        $this->collPrinters = null;
        if ($this->collPublishers instanceof PropelCollection) {
            $this->collPublishers->clearIterator();
        }
        $this->collPublishers = null;
        if ($this->collTranslators instanceof PropelCollection) {
            $this->collTranslators->clearIterator();
        }
        $this->collTranslators = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PersonPeer::DEFAULT_STRING_FORMAT);
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
