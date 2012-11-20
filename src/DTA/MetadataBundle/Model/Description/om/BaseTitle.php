<?php

namespace DTA\MetadataBundle\Model\Description\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelException;
use \PropelPDO;
use DTA\MetadataBundle\Model\Description\Title;
use DTA\MetadataBundle\Model\Description\TitlePeer;
use DTA\MetadataBundle\Model\Description\TitleQuery;
use DTA\MetadataBundle\Model\Description\Titletype;
use DTA\MetadataBundle\Model\Description\TitletypeQuery;
use DTA\MetadataBundle\Model\Publication\Publication;
use DTA\MetadataBundle\Model\Publication\PublicationQuery;
use DTA\MetadataBundle\Model\Publication\Volume;
use DTA\MetadataBundle\Model\Publication\VolumeQuery;
use DTA\MetadataBundle\Model\Publication\Work;
use DTA\MetadataBundle\Model\Publication\WorkQuery;

abstract class BaseTitle extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\Description\\TitlePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        TitlePeer
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
     * The value for the sequenceindex field.
     * @var        int
     */
    protected $sequenceindex;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the work_id field.
     * @var        int
     */
    protected $work_id;

    /**
     * The value for the publication_id field.
     * @var        int
     */
    protected $publication_id;

    /**
     * The value for the volume_id field.
     * @var        int
     */
    protected $volume_id;

    /**
     * The value for the titletype_id field.
     * @var        int
     */
    protected $titletype_id;

    /**
     * @var        Titletype
     */
    protected $aTitletype;

    /**
     * @var        Publication
     */
    protected $aPublication;

    /**
     * @var        Volume
     */
    protected $aVolume;

    /**
     * @var        Work
     */
    protected $aWork;

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
     * Get the [sequenceindex] column value.
     *
     * @return int
     */
    public function getSequenceindex()
    {
        return $this->sequenceindex;
    }

    /**
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [work_id] column value.
     *
     * @return int
     */
    public function getWorkId()
    {
        return $this->work_id;
    }

    /**
     * Get the [publication_id] column value.
     *
     * @return int
     */
    public function getPublicationId()
    {
        return $this->publication_id;
    }

    /**
     * Get the [volume_id] column value.
     *
     * @return int
     */
    public function getVolumeId()
    {
        return $this->volume_id;
    }

    /**
     * Get the [titletype_id] column value.
     *
     * @return int
     */
    public function getTitletypeId()
    {
        return $this->titletype_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Title The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = TitlePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [sequenceindex] column.
     *
     * @param int $v new value
     * @return Title The current object (for fluent API support)
     */
    public function setSequenceindex($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->sequenceindex !== $v) {
            $this->sequenceindex = $v;
            $this->modifiedColumns[] = TitlePeer::SEQUENCEINDEX;
        }


        return $this;
    } // setSequenceindex()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return Title The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = TitlePeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [work_id] column.
     *
     * @param int $v new value
     * @return Title The current object (for fluent API support)
     */
    public function setWorkId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->work_id !== $v) {
            $this->work_id = $v;
            $this->modifiedColumns[] = TitlePeer::WORK_ID;
        }

        if ($this->aWork !== null && $this->aWork->getId() !== $v) {
            $this->aWork = null;
        }


        return $this;
    } // setWorkId()

    /**
     * Set the value of [publication_id] column.
     *
     * @param int $v new value
     * @return Title The current object (for fluent API support)
     */
    public function setPublicationId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->publication_id !== $v) {
            $this->publication_id = $v;
            $this->modifiedColumns[] = TitlePeer::PUBLICATION_ID;
        }

        if ($this->aPublication !== null && $this->aPublication->getId() !== $v) {
            $this->aPublication = null;
        }


        return $this;
    } // setPublicationId()

    /**
     * Set the value of [volume_id] column.
     *
     * @param int $v new value
     * @return Title The current object (for fluent API support)
     */
    public function setVolumeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->volume_id !== $v) {
            $this->volume_id = $v;
            $this->modifiedColumns[] = TitlePeer::VOLUME_ID;
        }

        if ($this->aVolume !== null && $this->aVolume->getId() !== $v) {
            $this->aVolume = null;
        }


        return $this;
    } // setVolumeId()

    /**
     * Set the value of [titletype_id] column.
     *
     * @param int $v new value
     * @return Title The current object (for fluent API support)
     */
    public function setTitletypeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->titletype_id !== $v) {
            $this->titletype_id = $v;
            $this->modifiedColumns[] = TitlePeer::TITLETYPE_ID;
        }

        if ($this->aTitletype !== null && $this->aTitletype->getId() !== $v) {
            $this->aTitletype = null;
        }


        return $this;
    } // setTitletypeId()

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
            $this->sequenceindex = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->title = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->work_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->publication_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->volume_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->titletype_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 7; // 7 = TitlePeer::NUM_HYDRATE_COLUMNS.

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

        if ($this->aWork !== null && $this->work_id !== $this->aWork->getId()) {
            $this->aWork = null;
        }
        if ($this->aPublication !== null && $this->publication_id !== $this->aPublication->getId()) {
            $this->aPublication = null;
        }
        if ($this->aVolume !== null && $this->volume_id !== $this->aVolume->getId()) {
            $this->aVolume = null;
        }
        if ($this->aTitletype !== null && $this->titletype_id !== $this->aTitletype->getId()) {
            $this->aTitletype = null;
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

            $this->aTitletype = null;
            $this->aPublication = null;
            $this->aVolume = null;
            $this->aWork = null;
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aTitletype !== null) {
                if ($this->aTitletype->isModified() || $this->aTitletype->isNew()) {
                    $affectedRows += $this->aTitletype->save($con);
                }
                $this->setTitletype($this->aTitletype);
            }

            if ($this->aPublication !== null) {
                if ($this->aPublication->isModified() || $this->aPublication->isNew()) {
                    $affectedRows += $this->aPublication->save($con);
                }
                $this->setPublication($this->aPublication);
            }

            if ($this->aVolume !== null) {
                if ($this->aVolume->isModified() || $this->aVolume->isNew()) {
                    $affectedRows += $this->aVolume->save($con);
                }
                $this->setVolume($this->aVolume);
            }

            if ($this->aWork !== null) {
                if ($this->aWork->isModified() || $this->aWork->isNew()) {
                    $affectedRows += $this->aWork->save($con);
                }
                $this->setWork($this->aWork);
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

        $this->modifiedColumns[] = TitlePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . TitlePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(TitlePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(TitlePeer::SEQUENCEINDEX)) {
            $modifiedColumns[':p' . $index++]  = '`SEQUENCEINDEX`';
        }
        if ($this->isColumnModified(TitlePeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`TITLE`';
        }
        if ($this->isColumnModified(TitlePeer::WORK_ID)) {
            $modifiedColumns[':p' . $index++]  = '`WORK_ID`';
        }
        if ($this->isColumnModified(TitlePeer::PUBLICATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`PUBLICATION_ID`';
        }
        if ($this->isColumnModified(TitlePeer::VOLUME_ID)) {
            $modifiedColumns[':p' . $index++]  = '`VOLUME_ID`';
        }
        if ($this->isColumnModified(TitlePeer::TITLETYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`TITLETYPE_ID`';
        }

        $sql = sprintf(
            'INSERT INTO `title` (%s) VALUES (%s)',
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
                    case '`SEQUENCEINDEX`':
                        $stmt->bindValue($identifier, $this->sequenceindex, PDO::PARAM_INT);
                        break;
                    case '`TITLE`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`WORK_ID`':
                        $stmt->bindValue($identifier, $this->work_id, PDO::PARAM_INT);
                        break;
                    case '`PUBLICATION_ID`':
                        $stmt->bindValue($identifier, $this->publication_id, PDO::PARAM_INT);
                        break;
                    case '`VOLUME_ID`':
                        $stmt->bindValue($identifier, $this->volume_id, PDO::PARAM_INT);
                        break;
                    case '`TITLETYPE_ID`':
                        $stmt->bindValue($identifier, $this->titletype_id, PDO::PARAM_INT);
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

            if ($this->aTitletype !== null) {
                if (!$this->aTitletype->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aTitletype->getValidationFailures());
                }
            }

            if ($this->aPublication !== null) {
                if (!$this->aPublication->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPublication->getValidationFailures());
                }
            }

            if ($this->aVolume !== null) {
                if (!$this->aVolume->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aVolume->getValidationFailures());
                }
            }

            if ($this->aWork !== null) {
                if (!$this->aWork->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aWork->getValidationFailures());
                }
            }


            if (($retval = TitlePeer::doValidate($this, $columns)) !== true) {
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
                return $this->getSequenceindex();
                break;
            case 2:
                return $this->getTitle();
                break;
            case 3:
                return $this->getWorkId();
                break;
            case 4:
                return $this->getPublicationId();
                break;
            case 5:
                return $this->getVolumeId();
                break;
            case 6:
                return $this->getTitletypeId();
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
            $keys[1] => $this->getSequenceindex(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getWorkId(),
            $keys[4] => $this->getPublicationId(),
            $keys[5] => $this->getVolumeId(),
            $keys[6] => $this->getTitletypeId(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aTitletype) {
                $result['Titletype'] = $this->aTitletype->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPublication) {
                $result['Publication'] = $this->aPublication->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aVolume) {
                $result['Volume'] = $this->aVolume->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aWork) {
                $result['Work'] = $this->aWork->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
                $this->setSequenceindex($value);
                break;
            case 2:
                $this->setTitle($value);
                break;
            case 3:
                $this->setWorkId($value);
                break;
            case 4:
                $this->setPublicationId($value);
                break;
            case 5:
                $this->setVolumeId($value);
                break;
            case 6:
                $this->setTitletypeId($value);
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
        if (array_key_exists($keys[1], $arr)) $this->setSequenceindex($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setWorkId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setPublicationId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setVolumeId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setTitletypeId($arr[$keys[6]]);
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
        if ($this->isColumnModified(TitlePeer::SEQUENCEINDEX)) $criteria->add(TitlePeer::SEQUENCEINDEX, $this->sequenceindex);
        if ($this->isColumnModified(TitlePeer::TITLE)) $criteria->add(TitlePeer::TITLE, $this->title);
        if ($this->isColumnModified(TitlePeer::WORK_ID)) $criteria->add(TitlePeer::WORK_ID, $this->work_id);
        if ($this->isColumnModified(TitlePeer::PUBLICATION_ID)) $criteria->add(TitlePeer::PUBLICATION_ID, $this->publication_id);
        if ($this->isColumnModified(TitlePeer::VOLUME_ID)) $criteria->add(TitlePeer::VOLUME_ID, $this->volume_id);
        if ($this->isColumnModified(TitlePeer::TITLETYPE_ID)) $criteria->add(TitlePeer::TITLETYPE_ID, $this->titletype_id);

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
        $copyObj->setSequenceindex($this->getSequenceindex());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setWorkId($this->getWorkId());
        $copyObj->setPublicationId($this->getPublicationId());
        $copyObj->setVolumeId($this->getVolumeId());
        $copyObj->setTitletypeId($this->getTitletypeId());

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
     * Declares an association between this object and a Titletype object.
     *
     * @param             Titletype $v
     * @return Title The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTitletype(Titletype $v = null)
    {
        if ($v === null) {
            $this->setTitletypeId(NULL);
        } else {
            $this->setTitletypeId($v->getId());
        }

        $this->aTitletype = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Titletype object, it will not be re-added.
        if ($v !== null) {
            $v->addTitle($this);
        }


        return $this;
    }


    /**
     * Get the associated Titletype object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Titletype The associated Titletype object.
     * @throws PropelException
     */
    public function getTitletype(PropelPDO $con = null)
    {
        if ($this->aTitletype === null && ($this->titletype_id !== null)) {
            $this->aTitletype = TitletypeQuery::create()->findPk($this->titletype_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTitletype->addTitles($this);
             */
        }

        return $this->aTitletype;
    }

    /**
     * Declares an association between this object and a Publication object.
     *
     * @param             Publication $v
     * @return Title The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPublication(Publication $v = null)
    {
        if ($v === null) {
            $this->setPublicationId(NULL);
        } else {
            $this->setPublicationId($v->getId());
        }

        $this->aPublication = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Publication object, it will not be re-added.
        if ($v !== null) {
            $v->addTitle($this);
        }


        return $this;
    }


    /**
     * Get the associated Publication object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Publication The associated Publication object.
     * @throws PropelException
     */
    public function getPublication(PropelPDO $con = null)
    {
        if ($this->aPublication === null && ($this->publication_id !== null)) {
            $this->aPublication = PublicationQuery::create()->findPk($this->publication_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPublication->addTitles($this);
             */
        }

        return $this->aPublication;
    }

    /**
     * Declares an association between this object and a Volume object.
     *
     * @param             Volume $v
     * @return Title The current object (for fluent API support)
     * @throws PropelException
     */
    public function setVolume(Volume $v = null)
    {
        if ($v === null) {
            $this->setVolumeId(NULL);
        } else {
            $this->setVolumeId($v->getId());
        }

        $this->aVolume = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Volume object, it will not be re-added.
        if ($v !== null) {
            $v->addTitle($this);
        }


        return $this;
    }


    /**
     * Get the associated Volume object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Volume The associated Volume object.
     * @throws PropelException
     */
    public function getVolume(PropelPDO $con = null)
    {
        if ($this->aVolume === null && ($this->volume_id !== null)) {
            $this->aVolume = VolumeQuery::create()->findPk($this->volume_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aVolume->addTitles($this);
             */
        }

        return $this->aVolume;
    }

    /**
     * Declares an association between this object and a Work object.
     *
     * @param             Work $v
     * @return Title The current object (for fluent API support)
     * @throws PropelException
     */
    public function setWork(Work $v = null)
    {
        if ($v === null) {
            $this->setWorkId(NULL);
        } else {
            $this->setWorkId($v->getId());
        }

        $this->aWork = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Work object, it will not be re-added.
        if ($v !== null) {
            $v->addTitle($this);
        }


        return $this;
    }


    /**
     * Get the associated Work object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Work The associated Work object.
     * @throws PropelException
     */
    public function getWork(PropelPDO $con = null)
    {
        if ($this->aWork === null && ($this->work_id !== null)) {
            $this->aWork = WorkQuery::create()->findPk($this->work_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aWork->addTitles($this);
             */
        }

        return $this->aWork;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->sequenceindex = null;
        $this->title = null;
        $this->work_id = null;
        $this->publication_id = null;
        $this->volume_id = null;
        $this->titletype_id = null;
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

        $this->aTitletype = null;
        $this->aPublication = null;
        $this->aVolume = null;
        $this->aWork = null;
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

}
