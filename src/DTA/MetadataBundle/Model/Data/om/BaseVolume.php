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
use DTA\MetadataBundle\Model\Data\PublicationDs;
use DTA\MetadataBundle\Model\Data\PublicationDsQuery;
use DTA\MetadataBundle\Model\Data\PublicationJa;
use DTA\MetadataBundle\Model\Data\PublicationJaQuery;
use DTA\MetadataBundle\Model\Data\PublicationMm;
use DTA\MetadataBundle\Model\Data\PublicationMmQuery;
use DTA\MetadataBundle\Model\Data\PublicationMms;
use DTA\MetadataBundle\Model\Data\PublicationMmsQuery;
use DTA\MetadataBundle\Model\Data\Volume;
use DTA\MetadataBundle\Model\Data\VolumePeer;
use DTA\MetadataBundle\Model\Data\VolumeQuery;

abstract class BaseVolume extends BaseObject implements Persistent, \DTA\MetadataBundle\Model\table_row_view\TableRowViewInterface
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\Data\\VolumePeer';

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
     * The value for the volumedescription field.
     * @var        int
     */
    protected $volumedescription;

    /**
     * The value for the volumenumeric field.
     * @var        string
     */
    protected $volumenumeric;

    /**
     * @var        PropelObjectCollection|PublicationMm[] Collection to store aggregation of PublicationMm objects.
     */
    protected $collPublicationMms;
    protected $collPublicationMmsPartial;

    /**
     * @var        PropelObjectCollection|PublicationDs[] Collection to store aggregation of PublicationDs objects.
     */
    protected $collPublicationDss;
    protected $collPublicationDssPartial;

    /**
     * @var        PropelObjectCollection|PublicationJa[] Collection to store aggregation of PublicationJa objects.
     */
    protected $collPublicationJas;
    protected $collPublicationJasPartial;

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
    public static $tableRowViewCaptions = array('Id', 'Volumedescription', 'Volumenumeric', );	public   $tableRowViewAccessors = array('Id'=>'Id', 'Volumedescription'=>'Volumedescription', 'Volumenumeric'=>'Volumenumeric', );
    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationMmsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationDssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationJasScheduledForDeletion = null;

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
     * Get the [volumedescription] column value.
     * Bezeichnung des Bandes
     * @return int
     */
    public function getVolumedescription()
    {
        return $this->volumedescription;
    }

    /**
     * Get the [volumenumeric] column value.
     * Numerische Bezeichnung des Bandes
     * @return string
     */
    public function getVolumenumeric()
    {
        return $this->volumenumeric;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Volume The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = VolumePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [volumedescription] column.
     * Bezeichnung des Bandes
     * @param int $v new value
     * @return Volume The current object (for fluent API support)
     */
    public function setVolumedescription($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->volumedescription !== $v) {
            $this->volumedescription = $v;
            $this->modifiedColumns[] = VolumePeer::VOLUMEDESCRIPTION;
        }


        return $this;
    } // setVolumedescription()

    /**
     * Set the value of [volumenumeric] column.
     * Numerische Bezeichnung des Bandes
     * @param string $v new value
     * @return Volume The current object (for fluent API support)
     */
    public function setVolumenumeric($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->volumenumeric !== $v) {
            $this->volumenumeric = $v;
            $this->modifiedColumns[] = VolumePeer::VOLUMENUMERIC;
        }


        return $this;
    } // setVolumenumeric()

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
            $this->volumedescription = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->volumenumeric = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 3; // 3 = VolumePeer::NUM_HYDRATE_COLUMNS.

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

            $this->collPublicationMms = null;

            $this->collPublicationDss = null;

            $this->collPublicationJas = null;

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

            if ($this->publicationMmsScheduledForDeletion !== null) {
                if (!$this->publicationMmsScheduledForDeletion->isEmpty()) {
                    PublicationMmQuery::create()
                        ->filterByPrimaryKeys($this->publicationMmsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationMmsScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationMms !== null) {
                foreach ($this->collPublicationMms as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

            if ($this->publicationJasScheduledForDeletion !== null) {
                if (!$this->publicationJasScheduledForDeletion->isEmpty()) {
                    PublicationJaQuery::create()
                        ->filterByPrimaryKeys($this->publicationJasScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->publicationJasScheduledForDeletion = null;
                }
            }

            if ($this->collPublicationJas !== null) {
                foreach ($this->collPublicationJas as $referrerFK) {
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

        $this->modifiedColumns[] = VolumePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . VolumePeer::ID . ')');
        }
        if (null === $this->id) {
            try {
                $stmt = $con->query("SELECT nextval('volume_id_seq')");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $this->id = $row[0];
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(VolumePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '"id"';
        }
        if ($this->isColumnModified(VolumePeer::VOLUMEDESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '"volumedescription"';
        }
        if ($this->isColumnModified(VolumePeer::VOLUMENUMERIC)) {
            $modifiedColumns[':p' . $index++]  = '"volumenumeric"';
        }

        $sql = sprintf(
            'INSERT INTO "volume" (%s) VALUES (%s)',
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
                    case '"volumedescription"':
                        $stmt->bindValue($identifier, $this->volumedescription, PDO::PARAM_INT);
                        break;
                    case '"volumenumeric"':
                        $stmt->bindValue($identifier, $this->volumenumeric, PDO::PARAM_STR);
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


            if (($retval = VolumePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collPublicationMms !== null) {
                    foreach ($this->collPublicationMms as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublicationDss !== null) {
                    foreach ($this->collPublicationDss as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPublicationJas !== null) {
                    foreach ($this->collPublicationJas as $referrerFK) {
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
                return $this->getVolumedescription();
                break;
            case 2:
                return $this->getVolumenumeric();
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
            $keys[1] => $this->getVolumedescription(),
            $keys[2] => $this->getVolumenumeric(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->collPublicationMms) {
                $result['PublicationMms'] = $this->collPublicationMms->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationDss) {
                $result['PublicationDss'] = $this->collPublicationDss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPublicationJas) {
                $result['PublicationJas'] = $this->collPublicationJas->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $this->setVolumedescription($value);
                break;
            case 2:
                $this->setVolumenumeric($value);
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
        if (array_key_exists($keys[1], $arr)) $this->setVolumedescription($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setVolumenumeric($arr[$keys[2]]);
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
        if ($this->isColumnModified(VolumePeer::VOLUMEDESCRIPTION)) $criteria->add(VolumePeer::VOLUMEDESCRIPTION, $this->volumedescription);
        if ($this->isColumnModified(VolumePeer::VOLUMENUMERIC)) $criteria->add(VolumePeer::VOLUMENUMERIC, $this->volumenumeric);

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
        $copyObj->setVolumedescription($this->getVolumedescription());
        $copyObj->setVolumenumeric($this->getVolumenumeric());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPublicationMms() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationMm($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationDss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationDs($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPublicationJas() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPublicationJa($relObj->copy($deepCopy));
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('PublicationMm' == $relationName) {
            $this->initPublicationMms();
        }
        if ('PublicationDs' == $relationName) {
            $this->initPublicationDss();
        }
        if ('PublicationJa' == $relationName) {
            $this->initPublicationJas();
        }
        if ('PublicationMms' == $relationName) {
            $this->initPublicationMmss();
        }
    }

    /**
     * Clears out the collPublicationMms collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Volume The current object (for fluent API support)
     * @see        addPublicationMms()
     */
    public function clearPublicationMms()
    {
        $this->collPublicationMms = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationMmsPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationMms collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationMms($v = true)
    {
        $this->collPublicationMmsPartial = $v;
    }

    /**
     * Initializes the collPublicationMms collection.
     *
     * By default this just sets the collPublicationMms collection to an empty array (like clearcollPublicationMms());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationMms($overrideExisting = true)
    {
        if (null !== $this->collPublicationMms && !$overrideExisting) {
            return;
        }
        $this->collPublicationMms = new PropelObjectCollection();
        $this->collPublicationMms->setModel('PublicationMm');
    }

    /**
     * Gets an array of PublicationMm objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Volume is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PublicationMm[] List of PublicationMm objects
     * @throws PropelException
     */
    public function getPublicationMms($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationMmsPartial && !$this->isNew();
        if (null === $this->collPublicationMms || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationMms) {
                // return empty collection
                $this->initPublicationMms();
            } else {
                $collPublicationMms = PublicationMmQuery::create(null, $criteria)
                    ->filterByVolume($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationMmsPartial && count($collPublicationMms)) {
                      $this->initPublicationMms(false);

                      foreach($collPublicationMms as $obj) {
                        if (false == $this->collPublicationMms->contains($obj)) {
                          $this->collPublicationMms->append($obj);
                        }
                      }

                      $this->collPublicationMmsPartial = true;
                    }

                    $collPublicationMms->getInternalIterator()->rewind();
                    return $collPublicationMms;
                }

                if($partial && $this->collPublicationMms) {
                    foreach($this->collPublicationMms as $obj) {
                        if($obj->isNew()) {
                            $collPublicationMms[] = $obj;
                        }
                    }
                }

                $this->collPublicationMms = $collPublicationMms;
                $this->collPublicationMmsPartial = false;
            }
        }

        return $this->collPublicationMms;
    }

    /**
     * Sets a collection of PublicationMm objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationMms A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Volume The current object (for fluent API support)
     */
    public function setPublicationMms(PropelCollection $publicationMms, PropelPDO $con = null)
    {
        $publicationMmsToDelete = $this->getPublicationMms(new Criteria(), $con)->diff($publicationMms);

        $this->publicationMmsScheduledForDeletion = unserialize(serialize($publicationMmsToDelete));

        foreach ($publicationMmsToDelete as $publicationMmRemoved) {
            $publicationMmRemoved->setVolume(null);
        }

        $this->collPublicationMms = null;
        foreach ($publicationMms as $publicationMm) {
            $this->addPublicationMm($publicationMm);
        }

        $this->collPublicationMms = $publicationMms;
        $this->collPublicationMmsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PublicationMm objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PublicationMm objects.
     * @throws PropelException
     */
    public function countPublicationMms(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationMmsPartial && !$this->isNew();
        if (null === $this->collPublicationMms || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationMms) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationMms());
            }
            $query = PublicationMmQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByVolume($this)
                ->count($con);
        }

        return count($this->collPublicationMms);
    }

    /**
     * Method called to associate a PublicationMm object to this object
     * through the PublicationMm foreign key attribute.
     *
     * @param    PublicationMm $l PublicationMm
     * @return Volume The current object (for fluent API support)
     */
    public function addPublicationMm(PublicationMm $l)
    {
        if ($this->collPublicationMms === null) {
            $this->initPublicationMms();
            $this->collPublicationMmsPartial = true;
        }
        if (!in_array($l, $this->collPublicationMms->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationMm($l);
        }

        return $this;
    }

    /**
     * @param	PublicationMm $publicationMm The publicationMm object to add.
     */
    protected function doAddPublicationMm($publicationMm)
    {
        $this->collPublicationMms[]= $publicationMm;
        $publicationMm->setVolume($this);
    }

    /**
     * @param	PublicationMm $publicationMm The publicationMm object to remove.
     * @return Volume The current object (for fluent API support)
     */
    public function removePublicationMm($publicationMm)
    {
        if ($this->getPublicationMms()->contains($publicationMm)) {
            $this->collPublicationMms->remove($this->collPublicationMms->search($publicationMm));
            if (null === $this->publicationMmsScheduledForDeletion) {
                $this->publicationMmsScheduledForDeletion = clone $this->collPublicationMms;
                $this->publicationMmsScheduledForDeletion->clear();
            }
            $this->publicationMmsScheduledForDeletion[]= clone $publicationMm;
            $publicationMm->setVolume(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Volume is new, it will return
     * an empty collection; or if this Volume has previously
     * been saved, it will retrieve related PublicationMms from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Volume.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationMm[] List of PublicationMm objects
     */
    public function getPublicationMmsJoinPublication($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationMmQuery::create(null, $criteria);
        $query->joinWith('Publication', $join_behavior);

        return $this->getPublicationMms($query, $con);
    }

    /**
     * Clears out the collPublicationDss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Volume The current object (for fluent API support)
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
     * If this Volume is new, it will return
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
                    ->filterByVolume($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationDssPartial && count($collPublicationDss)) {
                      $this->initPublicationDss(false);

                      foreach($collPublicationDss as $obj) {
                        if (false == $this->collPublicationDss->contains($obj)) {
                          $this->collPublicationDss->append($obj);
                        }
                      }

                      $this->collPublicationDssPartial = true;
                    }

                    $collPublicationDss->getInternalIterator()->rewind();
                    return $collPublicationDss;
                }

                if($partial && $this->collPublicationDss) {
                    foreach($this->collPublicationDss as $obj) {
                        if($obj->isNew()) {
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
     * @return Volume The current object (for fluent API support)
     */
    public function setPublicationDss(PropelCollection $publicationDss, PropelPDO $con = null)
    {
        $publicationDssToDelete = $this->getPublicationDss(new Criteria(), $con)->diff($publicationDss);

        $this->publicationDssScheduledForDeletion = unserialize(serialize($publicationDssToDelete));

        foreach ($publicationDssToDelete as $publicationDsRemoved) {
            $publicationDsRemoved->setVolume(null);
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

            if($partial && !$criteria) {
                return count($this->getPublicationDss());
            }
            $query = PublicationDsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByVolume($this)
                ->count($con);
        }

        return count($this->collPublicationDss);
    }

    /**
     * Method called to associate a PublicationDs object to this object
     * through the PublicationDs foreign key attribute.
     *
     * @param    PublicationDs $l PublicationDs
     * @return Volume The current object (for fluent API support)
     */
    public function addPublicationDs(PublicationDs $l)
    {
        if ($this->collPublicationDss === null) {
            $this->initPublicationDss();
            $this->collPublicationDssPartial = true;
        }
        if (!in_array($l, $this->collPublicationDss->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationDs($l);
        }

        return $this;
    }

    /**
     * @param	PublicationDs $publicationDs The publicationDs object to add.
     */
    protected function doAddPublicationDs($publicationDs)
    {
        $this->collPublicationDss[]= $publicationDs;
        $publicationDs->setVolume($this);
    }

    /**
     * @param	PublicationDs $publicationDs The publicationDs object to remove.
     * @return Volume The current object (for fluent API support)
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
            $publicationDs->setVolume(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Volume is new, it will return
     * an empty collection; or if this Volume has previously
     * been saved, it will retrieve related PublicationDss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Volume.
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
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Volume is new, it will return
     * an empty collection; or if this Volume has previously
     * been saved, it will retrieve related PublicationDss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Volume.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationDs[] List of PublicationDs objects
     */
    public function getPublicationDssJoinSeries($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationDsQuery::create(null, $criteria);
        $query->joinWith('Series', $join_behavior);

        return $this->getPublicationDss($query, $con);
    }

    /**
     * Clears out the collPublicationJas collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Volume The current object (for fluent API support)
     * @see        addPublicationJas()
     */
    public function clearPublicationJas()
    {
        $this->collPublicationJas = null; // important to set this to null since that means it is uninitialized
        $this->collPublicationJasPartial = null;

        return $this;
    }

    /**
     * reset is the collPublicationJas collection loaded partially
     *
     * @return void
     */
    public function resetPartialPublicationJas($v = true)
    {
        $this->collPublicationJasPartial = $v;
    }

    /**
     * Initializes the collPublicationJas collection.
     *
     * By default this just sets the collPublicationJas collection to an empty array (like clearcollPublicationJas());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPublicationJas($overrideExisting = true)
    {
        if (null !== $this->collPublicationJas && !$overrideExisting) {
            return;
        }
        $this->collPublicationJas = new PropelObjectCollection();
        $this->collPublicationJas->setModel('PublicationJa');
    }

    /**
     * Gets an array of PublicationJa objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Volume is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PublicationJa[] List of PublicationJa objects
     * @throws PropelException
     */
    public function getPublicationJas($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPublicationJasPartial && !$this->isNew();
        if (null === $this->collPublicationJas || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPublicationJas) {
                // return empty collection
                $this->initPublicationJas();
            } else {
                $collPublicationJas = PublicationJaQuery::create(null, $criteria)
                    ->filterByVolume($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationJasPartial && count($collPublicationJas)) {
                      $this->initPublicationJas(false);

                      foreach($collPublicationJas as $obj) {
                        if (false == $this->collPublicationJas->contains($obj)) {
                          $this->collPublicationJas->append($obj);
                        }
                      }

                      $this->collPublicationJasPartial = true;
                    }

                    $collPublicationJas->getInternalIterator()->rewind();
                    return $collPublicationJas;
                }

                if($partial && $this->collPublicationJas) {
                    foreach($this->collPublicationJas as $obj) {
                        if($obj->isNew()) {
                            $collPublicationJas[] = $obj;
                        }
                    }
                }

                $this->collPublicationJas = $collPublicationJas;
                $this->collPublicationJasPartial = false;
            }
        }

        return $this->collPublicationJas;
    }

    /**
     * Sets a collection of PublicationJa objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $publicationJas A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Volume The current object (for fluent API support)
     */
    public function setPublicationJas(PropelCollection $publicationJas, PropelPDO $con = null)
    {
        $publicationJasToDelete = $this->getPublicationJas(new Criteria(), $con)->diff($publicationJas);

        $this->publicationJasScheduledForDeletion = unserialize(serialize($publicationJasToDelete));

        foreach ($publicationJasToDelete as $publicationJaRemoved) {
            $publicationJaRemoved->setVolume(null);
        }

        $this->collPublicationJas = null;
        foreach ($publicationJas as $publicationJa) {
            $this->addPublicationJa($publicationJa);
        }

        $this->collPublicationJas = $publicationJas;
        $this->collPublicationJasPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PublicationJa objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PublicationJa objects.
     * @throws PropelException
     */
    public function countPublicationJas(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPublicationJasPartial && !$this->isNew();
        if (null === $this->collPublicationJas || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPublicationJas) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPublicationJas());
            }
            $query = PublicationJaQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByVolume($this)
                ->count($con);
        }

        return count($this->collPublicationJas);
    }

    /**
     * Method called to associate a PublicationJa object to this object
     * through the PublicationJa foreign key attribute.
     *
     * @param    PublicationJa $l PublicationJa
     * @return Volume The current object (for fluent API support)
     */
    public function addPublicationJa(PublicationJa $l)
    {
        if ($this->collPublicationJas === null) {
            $this->initPublicationJas();
            $this->collPublicationJasPartial = true;
        }
        if (!in_array($l, $this->collPublicationJas->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationJa($l);
        }

        return $this;
    }

    /**
     * @param	PublicationJa $publicationJa The publicationJa object to add.
     */
    protected function doAddPublicationJa($publicationJa)
    {
        $this->collPublicationJas[]= $publicationJa;
        $publicationJa->setVolume($this);
    }

    /**
     * @param	PublicationJa $publicationJa The publicationJa object to remove.
     * @return Volume The current object (for fluent API support)
     */
    public function removePublicationJa($publicationJa)
    {
        if ($this->getPublicationJas()->contains($publicationJa)) {
            $this->collPublicationJas->remove($this->collPublicationJas->search($publicationJa));
            if (null === $this->publicationJasScheduledForDeletion) {
                $this->publicationJasScheduledForDeletion = clone $this->collPublicationJas;
                $this->publicationJasScheduledForDeletion->clear();
            }
            $this->publicationJasScheduledForDeletion[]= clone $publicationJa;
            $publicationJa->setVolume(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Volume is new, it will return
     * an empty collection; or if this Volume has previously
     * been saved, it will retrieve related PublicationJas from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Volume.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationJa[] List of PublicationJa objects
     */
    public function getPublicationJasJoinPublicationRelatedByPublicationId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationJaQuery::create(null, $criteria);
        $query->joinWith('PublicationRelatedByPublicationId', $join_behavior);

        return $this->getPublicationJas($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Volume is new, it will return
     * an empty collection; or if this Volume has previously
     * been saved, it will retrieve related PublicationJas from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Volume.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationJa[] List of PublicationJa objects
     */
    public function getPublicationJasJoinPublicationRelatedByParent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationJaQuery::create(null, $criteria);
        $query->joinWith('PublicationRelatedByParent', $join_behavior);

        return $this->getPublicationJas($query, $con);
    }

    /**
     * Clears out the collPublicationMmss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Volume The current object (for fluent API support)
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
     * If this Volume is new, it will return
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
                    ->filterByVolume($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPublicationMmssPartial && count($collPublicationMmss)) {
                      $this->initPublicationMmss(false);

                      foreach($collPublicationMmss as $obj) {
                        if (false == $this->collPublicationMmss->contains($obj)) {
                          $this->collPublicationMmss->append($obj);
                        }
                      }

                      $this->collPublicationMmssPartial = true;
                    }

                    $collPublicationMmss->getInternalIterator()->rewind();
                    return $collPublicationMmss;
                }

                if($partial && $this->collPublicationMmss) {
                    foreach($this->collPublicationMmss as $obj) {
                        if($obj->isNew()) {
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
     * @return Volume The current object (for fluent API support)
     */
    public function setPublicationMmss(PropelCollection $publicationMmss, PropelPDO $con = null)
    {
        $publicationMmssToDelete = $this->getPublicationMmss(new Criteria(), $con)->diff($publicationMmss);

        $this->publicationMmssScheduledForDeletion = unserialize(serialize($publicationMmssToDelete));

        foreach ($publicationMmssToDelete as $publicationMmsRemoved) {
            $publicationMmsRemoved->setVolume(null);
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

            if($partial && !$criteria) {
                return count($this->getPublicationMmss());
            }
            $query = PublicationMmsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByVolume($this)
                ->count($con);
        }

        return count($this->collPublicationMmss);
    }

    /**
     * Method called to associate a PublicationMms object to this object
     * through the PublicationMms foreign key attribute.
     *
     * @param    PublicationMms $l PublicationMms
     * @return Volume The current object (for fluent API support)
     */
    public function addPublicationMms(PublicationMms $l)
    {
        if ($this->collPublicationMmss === null) {
            $this->initPublicationMmss();
            $this->collPublicationMmssPartial = true;
        }
        if (!in_array($l, $this->collPublicationMmss->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPublicationMms($l);
        }

        return $this;
    }

    /**
     * @param	PublicationMms $publicationMms The publicationMms object to add.
     */
    protected function doAddPublicationMms($publicationMms)
    {
        $this->collPublicationMmss[]= $publicationMms;
        $publicationMms->setVolume($this);
    }

    /**
     * @param	PublicationMms $publicationMms The publicationMms object to remove.
     * @return Volume The current object (for fluent API support)
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
            $publicationMms->setVolume(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Volume is new, it will return
     * an empty collection; or if this Volume has previously
     * been saved, it will retrieve related PublicationMmss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Volume.
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
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Volume is new, it will return
     * an empty collection; or if this Volume has previously
     * been saved, it will retrieve related PublicationMmss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Volume.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PublicationMms[] List of PublicationMms objects
     */
    public function getPublicationMmssJoinSeries($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationMmsQuery::create(null, $criteria);
        $query->joinWith('Series', $join_behavior);

        return $this->getPublicationMmss($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->volumedescription = null;
        $this->volumenumeric = null;
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
            if ($this->collPublicationMms) {
                foreach ($this->collPublicationMms as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationDss) {
                foreach ($this->collPublicationDss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationJas) {
                foreach ($this->collPublicationJas as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPublicationMmss) {
                foreach ($this->collPublicationMmss as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPublicationMms instanceof PropelCollection) {
            $this->collPublicationMms->clearIterator();
        }
        $this->collPublicationMms = null;
        if ($this->collPublicationDss instanceof PropelCollection) {
            $this->collPublicationDss->clearIterator();
        }
        $this->collPublicationDss = null;
        if ($this->collPublicationJas instanceof PropelCollection) {
            $this->collPublicationJas->clearIterator();
        }
        $this->collPublicationJas = null;
        if ($this->collPublicationMmss instanceof PropelCollection) {
            $this->collPublicationMmss->clearIterator();
        }
        $this->collPublicationMmss = null;
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
