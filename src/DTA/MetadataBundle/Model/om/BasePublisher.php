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
use DTA\MetadataBundle\Model\Essay;
use DTA\MetadataBundle\Model\EssayQuery;
use DTA\MetadataBundle\Model\Magazine;
use DTA\MetadataBundle\Model\MagazineQuery;
use DTA\MetadataBundle\Model\Person;
use DTA\MetadataBundle\Model\PersonQuery;
use DTA\MetadataBundle\Model\Publication;
use DTA\MetadataBundle\Model\PublicationQuery;
use DTA\MetadataBundle\Model\Publisher;
use DTA\MetadataBundle\Model\PublisherPeer;
use DTA\MetadataBundle\Model\PublisherQuery;
use DTA\MetadataBundle\Model\Series;
use DTA\MetadataBundle\Model\SeriesQuery;

abstract class BasePublisher extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\PublisherPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PublisherPeer
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
     * The value for the person_id field.
     * @var        int
     */
    protected $person_id;

    /**
     * @var        Person
     */
    protected $aPerson;

    /**
     * @var        PropelObjectCollection|Publication[] Collection to store aggregation of Publication objects.
     */
    protected $collPublications;
    protected $collPublicationsPartial;

    /**
     * @var        PropelObjectCollection|Essay[] Collection to store aggregation of Essay objects.
     */
    protected $collEssays;
    protected $collEssaysPartial;

    /**
     * @var        PropelObjectCollection|Magazine[] Collection to store aggregation of Magazine objects.
     */
    protected $collMagazines;
    protected $collMagazinesPartial;

    /**
     * @var        PropelObjectCollection|Series[] Collection to store aggregation of Series objects.
     */
    protected $collSeries;
    protected $collSeriesPartial;

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
    protected $publicationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $essaysScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $magazinesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $seriesScheduledForDeletion = null;

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
     * Get the [person_id] column value.
     *
     * @return int
     */
    public function getPersonId()
    {
        return $this->person_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Publisher The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PublisherPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [person_id] column.
     *
     * @param int $v new value
     * @return Publisher The current object (for fluent API support)
     */
    public function setPersonId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->person_id !== $v) {
            $this->person_id = $v;
            $this->modifiedColumns[] = PublisherPeer::PERSON_ID;
        }

        if ($this->aPerson !== null && $this->aPerson->getId() !== $v) {
            $this->aPerson = null;
        }


        return $this;
    } // setPersonId()

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
            $this->person_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 2; // 2 = PublisherPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Publisher object", $e);
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

        if ($this->aPerson !== null && $this->person_id !== $this->aPerson->getId()) {
            $this->aPerson = null;
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
            $con = Propel::getConnection(PublisherPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PublisherPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPerson = null;
            $this->collPublications = null;

            $this->collEssays = null;

            $this->collMagazines = null;

            $this->collSeries = null;

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
            $con = Propel::getConnection(PublisherPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PublisherQuery::create()
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
            $con = Propel::getConnection(PublisherPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                PublisherPeer::addInstanceToPool($this);
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

            if ($this->aPerson !== null) {
                if ($this->aPerson->isModified() || $this->aPerson->isNew()) {
                    $affectedRows += $this->aPerson->save($con);
                }
                $this->setPerson($this->aPerson);
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

            if ($this->publicationsScheduledForDeletion !== null) {
                if (!$this->publicationsScheduledForDeletion->isEmpty()) {
                    foreach ($this->publicationsScheduledForDeletion as $publication) {
                        // need to save related object because we set the relation to null
                        $publication->save($con);
                    }
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

            if ($this->essaysScheduledForDeletion !== null) {
                if (!$this->essaysScheduledForDeletion->isEmpty()) {
                    foreach ($this->essaysScheduledForDeletion as $essay) {
                        // need to save related object because we set the relation to null
                        $essay->save($con);
                    }
                    $this->essaysScheduledForDeletion = null;
                }
            }

            if ($this->collEssays !== null) {
                foreach ($this->collEssays as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->magazinesScheduledForDeletion !== null) {
                if (!$this->magazinesScheduledForDeletion->isEmpty()) {
                    foreach ($this->magazinesScheduledForDeletion as $magazine) {
                        // need to save related object because we set the relation to null
                        $magazine->save($con);
                    }
                    $this->magazinesScheduledForDeletion = null;
                }
            }

            if ($this->collMagazines !== null) {
                foreach ($this->collMagazines as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->seriesScheduledForDeletion !== null) {
                if (!$this->seriesScheduledForDeletion->isEmpty()) {
                    foreach ($this->seriesScheduledForDeletion as $series) {
                        // need to save related object because we set the relation to null
                        $series->save($con);
                    }
                    $this->seriesScheduledForDeletion = null;
                }
            }

            if ($this->collSeries !== null) {
                foreach ($this->collSeries as $referrerFK) {
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

        $this->modifiedColumns[] = PublisherPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PublisherPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PublisherPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PublisherPeer::PERSON_ID)) {
            $modifiedColumns[':p' . $index++]  = '`person_id`';
        }

        $sql = sprintf(
            'INSERT INTO `publisher` (%s) VALUES (%s)',
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
                    case '`person_id`':
                        $stmt->bindValue($identifier, $this->person_id, PDO::PARAM_INT);
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

            if ($this->aPerson !== null) {
                if (!$this->aPerson->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPerson->getValidationFailures());
                }
            }


            if (($retval = PublisherPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collPublications !== null) {
                    foreach ($this->collPublications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEssays !== null) {
                    foreach ($this->collEssays as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collMagazines !== null) {
                    foreach ($this->collMagazines as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSeries !== null) {
                    foreach ($this->collSeries as $referrerFK) {
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
        $pos = PublisherPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPersonId();
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
        if (isset($alreadyDumpedObjects['Publisher'][serialize($this->getPrimaryKey())])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Publisher'][serialize($this->getPrimaryKey())] = true;
        $keys = PublisherPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getPersonId(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aPerson) {
                $result['Person'] = $this->aPerson->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPublications) {
                $result['Publications'] = $this->collPublications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEssays) {
                $result['Essays'] = $this->collEssays->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMagazines) {
                $result['Magazines'] = $this->collMagazines->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSeries) {
                $result['Series'] = $this->collSeries->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PublisherPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPersonId($value);
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
        $keys = PublisherPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setPersonId($arr[$keys[1]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PublisherPeer::DATABASE_NAME);

        if ($this->isColumnModified(PublisherPeer::ID)) $criteria->add(PublisherPeer::ID, $this->id);
        if ($this->isColumnModified(PublisherPeer::PERSON_ID)) $criteria->add(PublisherPeer::PERSON_ID, $this->person_id);

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
        $criteria = new Criteria(PublisherPeer::DATABASE_NAME);
        $criteria->add(PublisherPeer::ID, $this->id);
        $criteria->add(PublisherPeer::PERSON_ID, $this->person_id);

        return $criteria;
    }

    /**
     * Returns the composite primary key for this object.
     * The array elements will be in same order as specified in XML.
     * @return array
     */
    public function getPrimaryKey()
    {
        $pks = array();
        $pks[0] = $this->getId();
        $pks[1] = $this->getPersonId();

        return $pks;
    }

    /**
     * Set the [composite] primary key.
     *
     * @param array $keys The elements of the composite key (order must match the order in XML file).
     * @return void
     */
    public function setPrimaryKey($keys)
    {
        $this->setId($keys[0]);
        $this->setPersonId($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return (null === $this->getId()) && (null === $this->getPersonId());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Publisher (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPersonId($this->getPersonId());

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

            foreach ($this->getEssays() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEssay($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMagazines() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMagazine($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSeries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSeries($relObj->copy($deepCopy));
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
     * @return Publisher Clone of current object.
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
     * @return PublisherPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PublisherPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Person object.
     *
     * @param             Person $v
     * @return Publisher The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPerson(Person $v = null)
    {
        if ($v === null) {
            $this->setPersonId(NULL);
        } else {
            $this->setPersonId($v->getId());
        }

        $this->aPerson = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Person object, it will not be re-added.
        if ($v !== null) {
            $v->addPublisher($this);
        }


        return $this;
    }


    /**
     * Get the associated Person object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Person The associated Person object.
     * @throws PropelException
     */
    public function getPerson(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPerson === null && ($this->person_id !== null) && $doQuery) {
            $this->aPerson = PersonQuery::create()->findPk($this->person_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPerson->addPublishers($this);
             */
        }

        return $this->aPerson;
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
        if ('Essay' == $relationName) {
            $this->initEssays();
        }
        if ('Magazine' == $relationName) {
            $this->initMagazines();
        }
        if ('Series' == $relationName) {
            $this->initSeries();
        }
    }

    /**
     * Clears out the collPublications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publisher The current object (for fluent API support)
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
     * If this Publisher is new, it will return
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
                    ->filterByPublisher($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationsPartial && count($collPublications)) {
                      $this->initPublications(false);

                      foreach($collPublications as $obj) {
                        if (false == $this->collPublications->contains($obj)) {
                          $this->collPublications->append($obj);
                        }
                      }

                      $this->collPublicationsPartial = true;
                    }

                    $collPublications->getInternalIterator()->rewind();
                    return $collPublications;
                }

                if($partial && $this->collPublications) {
                    foreach($this->collPublications as $obj) {
                        if($obj->isNew()) {
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
     * @return Publisher The current object (for fluent API support)
     */
    public function setPublications(PropelCollection $publications, PropelPDO $con = null)
    {
        $publicationsToDelete = $this->getPublications(new Criteria(), $con)->diff($publications);

        $this->publicationsScheduledForDeletion = unserialize(serialize($publicationsToDelete));

        foreach ($publicationsToDelete as $publicationRemoved) {
            $publicationRemoved->setPublisher(null);
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

            if($partial && !$criteria) {
                return count($this->getPublications());
            }
            $query = PublicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublisher($this)
                ->count($con);
        }

        return count($this->collPublications);
    }

    /**
     * Method called to associate a Publication object to this object
     * through the Publication foreign key attribute.
     *
     * @param    Publication $l Publication
     * @return Publisher The current object (for fluent API support)
     */
    public function addPublication(Publication $l)
    {
        if ($this->collPublications === null) {
            $this->initPublications();
            $this->collPublicationsPartial = true;
        }
        if (!in_array($l, $this->collPublications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublication($l);
        }

        return $this;
    }

    /**
     * @param	Publication $publication The publication object to add.
     */
    protected function doAddPublication($publication)
    {
        $this->collPublications[]= $publication;
        $publication->setPublisher($this);
    }

    /**
     * @param	Publication $publication The publication object to remove.
     * @return Publisher The current object (for fluent API support)
     */
    public function removePublication($publication)
    {
        if ($this->getPublications()->contains($publication)) {
            $this->collPublications->remove($this->collPublications->search($publication));
            if (null === $this->publicationsScheduledForDeletion) {
                $this->publicationsScheduledForDeletion = clone $this->collPublications;
                $this->publicationsScheduledForDeletion->clear();
            }
            $this->publicationsScheduledForDeletion[]= $publication;
            $publication->setPublisher(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
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
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
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
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinDatespecification($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Datespecification', $join_behavior);

        return $this->getPublications($query, $con);
    }

    /**
     * Clears out the collEssays collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publisher The current object (for fluent API support)
     * @see        addEssays()
     */
    public function clearEssays()
    {
        $this->collEssays = null; // important to set this to null since that means it is uninitialized
        $this->collEssaysPartial = null;

        return $this;
    }

    /**
     * reset is the collEssays collection loaded partially
     *
     * @return void
     */
    public function resetPartialEssays($v = true)
    {
        $this->collEssaysPartial = $v;
    }

    /**
     * Initializes the collEssays collection.
     *
     * By default this just sets the collEssays collection to an empty array (like clearcollEssays());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEssays($overrideExisting = true)
    {
        if (null !== $this->collEssays && !$overrideExisting) {
            return;
        }
        $this->collEssays = new PropelObjectCollection();
        $this->collEssays->setModel('Essay');
    }

    /**
     * Gets an array of Essay objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publisher is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Essay[] List of Essay objects
     * @throws PropelException
     */
    public function getEssays($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEssaysPartial && !$this->isNew();
        if (null === $this->collEssays || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEssays) {
                // return empty collection
                $this->initEssays();
            } else {
                $collEssays = EssayQuery::create(null, $criteria)
                    ->filterByPublisher($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEssaysPartial && count($collEssays)) {
                      $this->initEssays(false);

                      foreach($collEssays as $obj) {
                        if (false == $this->collEssays->contains($obj)) {
                          $this->collEssays->append($obj);
                        }
                      }

                      $this->collEssaysPartial = true;
                    }

                    $collEssays->getInternalIterator()->rewind();
                    return $collEssays;
                }

                if($partial && $this->collEssays) {
                    foreach($this->collEssays as $obj) {
                        if($obj->isNew()) {
                            $collEssays[] = $obj;
                        }
                    }
                }

                $this->collEssays = $collEssays;
                $this->collEssaysPartial = false;
            }
        }

        return $this->collEssays;
    }

    /**
     * Sets a collection of Essay objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $essays A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publisher The current object (for fluent API support)
     */
    public function setEssays(PropelCollection $essays, PropelPDO $con = null)
    {
        $essaysToDelete = $this->getEssays(new Criteria(), $con)->diff($essays);

        $this->essaysScheduledForDeletion = unserialize(serialize($essaysToDelete));

        foreach ($essaysToDelete as $essayRemoved) {
            $essayRemoved->setPublisher(null);
        }

        $this->collEssays = null;
        foreach ($essays as $essay) {
            $this->addEssay($essay);
        }

        $this->collEssays = $essays;
        $this->collEssaysPartial = false;

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
    public function countEssays(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEssaysPartial && !$this->isNew();
        if (null === $this->collEssays || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEssays) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getEssays());
            }
            $query = EssayQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublisher($this)
                ->count($con);
        }

        return count($this->collEssays);
    }

    /**
     * Method called to associate a Essay object to this object
     * through the Essay foreign key attribute.
     *
     * @param    Essay $l Essay
     * @return Publisher The current object (for fluent API support)
     */
    public function addEssay(Essay $l)
    {
        if ($this->collEssays === null) {
            $this->initEssays();
            $this->collEssaysPartial = true;
        }
        if (!in_array($l, $this->collEssays->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddEssay($l);
        }

        return $this;
    }

    /**
     * @param	Essay $essay The essay object to add.
     */
    protected function doAddEssay($essay)
    {
        $this->collEssays[]= $essay;
        $essay->setPublisher($this);
    }

    /**
     * @param	Essay $essay The essay object to remove.
     * @return Publisher The current object (for fluent API support)
     */
    public function removeEssay($essay)
    {
        if ($this->getEssays()->contains($essay)) {
            $this->collEssays->remove($this->collEssays->search($essay));
            if (null === $this->essaysScheduledForDeletion) {
                $this->essaysScheduledForDeletion = clone $this->collEssays;
                $this->essaysScheduledForDeletion->clear();
            }
            $this->essaysScheduledForDeletion[]= $essay;
            $essay->setPublisher(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Essays from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getEssays($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Essays from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getEssays($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Essays from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getEssays($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Essays from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getEssays($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Essays from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getEssays($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Essays from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getEssays($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Essays from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getEssays($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Essays from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Essay[] List of Essay objects
     */
    public function getEssaysJoinDatespecification($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EssayQuery::create(null, $criteria);
        $query->joinWith('Datespecification', $join_behavior);

        return $this->getEssays($query, $con);
    }

    /**
     * Clears out the collMagazines collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publisher The current object (for fluent API support)
     * @see        addMagazines()
     */
    public function clearMagazines()
    {
        $this->collMagazines = null; // important to set this to null since that means it is uninitialized
        $this->collMagazinesPartial = null;

        return $this;
    }

    /**
     * reset is the collMagazines collection loaded partially
     *
     * @return void
     */
    public function resetPartialMagazines($v = true)
    {
        $this->collMagazinesPartial = $v;
    }

    /**
     * Initializes the collMagazines collection.
     *
     * By default this just sets the collMagazines collection to an empty array (like clearcollMagazines());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMagazines($overrideExisting = true)
    {
        if (null !== $this->collMagazines && !$overrideExisting) {
            return;
        }
        $this->collMagazines = new PropelObjectCollection();
        $this->collMagazines->setModel('Magazine');
    }

    /**
     * Gets an array of Magazine objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publisher is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     * @throws PropelException
     */
    public function getMagazines($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMagazinesPartial && !$this->isNew();
        if (null === $this->collMagazines || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMagazines) {
                // return empty collection
                $this->initMagazines();
            } else {
                $collMagazines = MagazineQuery::create(null, $criteria)
                    ->filterByPublisher($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMagazinesPartial && count($collMagazines)) {
                      $this->initMagazines(false);

                      foreach($collMagazines as $obj) {
                        if (false == $this->collMagazines->contains($obj)) {
                          $this->collMagazines->append($obj);
                        }
                      }

                      $this->collMagazinesPartial = true;
                    }

                    $collMagazines->getInternalIterator()->rewind();
                    return $collMagazines;
                }

                if($partial && $this->collMagazines) {
                    foreach($this->collMagazines as $obj) {
                        if($obj->isNew()) {
                            $collMagazines[] = $obj;
                        }
                    }
                }

                $this->collMagazines = $collMagazines;
                $this->collMagazinesPartial = false;
            }
        }

        return $this->collMagazines;
    }

    /**
     * Sets a collection of Magazine objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $magazines A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publisher The current object (for fluent API support)
     */
    public function setMagazines(PropelCollection $magazines, PropelPDO $con = null)
    {
        $magazinesToDelete = $this->getMagazines(new Criteria(), $con)->diff($magazines);

        $this->magazinesScheduledForDeletion = unserialize(serialize($magazinesToDelete));

        foreach ($magazinesToDelete as $magazineRemoved) {
            $magazineRemoved->setPublisher(null);
        }

        $this->collMagazines = null;
        foreach ($magazines as $magazine) {
            $this->addMagazine($magazine);
        }

        $this->collMagazines = $magazines;
        $this->collMagazinesPartial = false;

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
    public function countMagazines(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collMagazinesPartial && !$this->isNew();
        if (null === $this->collMagazines || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMagazines) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getMagazines());
            }
            $query = MagazineQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublisher($this)
                ->count($con);
        }

        return count($this->collMagazines);
    }

    /**
     * Method called to associate a Magazine object to this object
     * through the Magazine foreign key attribute.
     *
     * @param    Magazine $l Magazine
     * @return Publisher The current object (for fluent API support)
     */
    public function addMagazine(Magazine $l)
    {
        if ($this->collMagazines === null) {
            $this->initMagazines();
            $this->collMagazinesPartial = true;
        }
        if (!in_array($l, $this->collMagazines->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMagazine($l);
        }

        return $this;
    }

    /**
     * @param	Magazine $magazine The magazine object to add.
     */
    protected function doAddMagazine($magazine)
    {
        $this->collMagazines[]= $magazine;
        $magazine->setPublisher($this);
    }

    /**
     * @param	Magazine $magazine The magazine object to remove.
     * @return Publisher The current object (for fluent API support)
     */
    public function removeMagazine($magazine)
    {
        if ($this->getMagazines()->contains($magazine)) {
            $this->collMagazines->remove($this->collMagazines->search($magazine));
            if (null === $this->magazinesScheduledForDeletion) {
                $this->magazinesScheduledForDeletion = clone $this->collMagazines;
                $this->magazinesScheduledForDeletion->clear();
            }
            $this->magazinesScheduledForDeletion[]= $magazine;
            $magazine->setPublisher(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Magazines from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getMagazines($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Magazines from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getMagazines($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Magazines from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getMagazines($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Magazines from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getMagazines($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Magazines from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getMagazines($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Magazines from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getMagazines($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Magazines from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getMagazines($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Magazines from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Magazine[] List of Magazine objects
     */
    public function getMagazinesJoinDatespecification($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = MagazineQuery::create(null, $criteria);
        $query->joinWith('Datespecification', $join_behavior);

        return $this->getMagazines($query, $con);
    }

    /**
     * Clears out the collSeries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Publisher The current object (for fluent API support)
     * @see        addSeries()
     */
    public function clearSeries()
    {
        $this->collSeries = null; // important to set this to null since that means it is uninitialized
        $this->collSeriesPartial = null;

        return $this;
    }

    /**
     * reset is the collSeries collection loaded partially
     *
     * @return void
     */
    public function resetPartialSeries($v = true)
    {
        $this->collSeriesPartial = $v;
    }

    /**
     * Initializes the collSeries collection.
     *
     * By default this just sets the collSeries collection to an empty array (like clearcollSeries());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSeries($overrideExisting = true)
    {
        if (null !== $this->collSeries && !$overrideExisting) {
            return;
        }
        $this->collSeries = new PropelObjectCollection();
        $this->collSeries->setModel('Series');
    }

    /**
     * Gets an array of Series objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Publisher is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Series[] List of Series objects
     * @throws PropelException
     */
    public function getSeries($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSeriesPartial && !$this->isNew();
        if (null === $this->collSeries || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSeries) {
                // return empty collection
                $this->initSeries();
            } else {
                $collSeries = SeriesQuery::create(null, $criteria)
                    ->filterByPublisher($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSeriesPartial && count($collSeries)) {
                      $this->initSeries(false);

                      foreach($collSeries as $obj) {
                        if (false == $this->collSeries->contains($obj)) {
                          $this->collSeries->append($obj);
                        }
                      }

                      $this->collSeriesPartial = true;
                    }

                    $collSeries->getInternalIterator()->rewind();
                    return $collSeries;
                }

                if($partial && $this->collSeries) {
                    foreach($this->collSeries as $obj) {
                        if($obj->isNew()) {
                            $collSeries[] = $obj;
                        }
                    }
                }

                $this->collSeries = $collSeries;
                $this->collSeriesPartial = false;
            }
        }

        return $this->collSeries;
    }

    /**
     * Sets a collection of Series objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $series A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Publisher The current object (for fluent API support)
     */
    public function setSeries(PropelCollection $series, PropelPDO $con = null)
    {
        $seriesToDelete = $this->getSeries(new Criteria(), $con)->diff($series);

        $this->seriesScheduledForDeletion = unserialize(serialize($seriesToDelete));

        foreach ($seriesToDelete as $seriesRemoved) {
            $seriesRemoved->setPublisher(null);
        }

        $this->collSeries = null;
        foreach ($series as $series) {
            $this->addSeries($series);
        }

        $this->collSeries = $series;
        $this->collSeriesPartial = false;

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
    public function countSeries(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSeriesPartial && !$this->isNew();
        if (null === $this->collSeries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSeries) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getSeries());
            }
            $query = SeriesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublisher($this)
                ->count($con);
        }

        return count($this->collSeries);
    }

    /**
     * Method called to associate a Series object to this object
     * through the Series foreign key attribute.
     *
     * @param    Series $l Series
     * @return Publisher The current object (for fluent API support)
     */
    public function addSeries(Series $l)
    {
        if ($this->collSeries === null) {
            $this->initSeries();
            $this->collSeriesPartial = true;
        }
        if (!in_array($l, $this->collSeries->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSeries($l);
        }

        return $this;
    }

    /**
     * @param	Series $series The series object to add.
     */
    protected function doAddSeries($series)
    {
        $this->collSeries[]= $series;
        $series->setPublisher($this);
    }

    /**
     * @param	Series $series The series object to remove.
     * @return Publisher The current object (for fluent API support)
     */
    public function removeSeries($series)
    {
        if ($this->getSeries()->contains($series)) {
            $this->collSeries->remove($this->collSeries->search($series));
            if (null === $this->seriesScheduledForDeletion) {
                $this->seriesScheduledForDeletion = clone $this->collSeries;
                $this->seriesScheduledForDeletion->clear();
            }
            $this->seriesScheduledForDeletion[]= $series;
            $series->setPublisher(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Series from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesJoinWork($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Work', $join_behavior);

        return $this->getSeries($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Series from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesJoinPrinter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Printer', $join_behavior);

        return $this->getSeries($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Series from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesJoinTranslator($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Translator', $join_behavior);

        return $this->getSeries($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Series from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesJoinRelatedset($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Relatedset', $join_behavior);

        return $this->getSeries($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Series from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getSeries($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Series from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getSeries($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Series from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getSeries($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Series from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Series[] List of Series objects
     */
    public function getSeriesJoinDatespecification($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeriesQuery::create(null, $criteria);
        $query->joinWith('Datespecification', $join_behavior);

        return $this->getSeries($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->person_id = null;
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
            if ($this->collPublications) {
                foreach ($this->collPublications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEssays) {
                foreach ($this->collEssays as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMagazines) {
                foreach ($this->collMagazines as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSeries) {
                foreach ($this->collSeries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPerson instanceof Persistent) {
              $this->aPerson->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPublications instanceof PropelCollection) {
            $this->collPublications->clearIterator();
        }
        $this->collPublications = null;
        if ($this->collEssays instanceof PropelCollection) {
            $this->collEssays->clearIterator();
        }
        $this->collEssays = null;
        if ($this->collMagazines instanceof PropelCollection) {
            $this->collMagazines->clearIterator();
        }
        $this->collMagazines = null;
        if ($this->collSeries instanceof PropelCollection) {
            $this->collSeries->clearIterator();
        }
        $this->collSeries = null;
        $this->aPerson = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PublisherPeer::DEFAULT_STRING_FORMAT);
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
