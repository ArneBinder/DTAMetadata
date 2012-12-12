<?php

namespace DTA\MetadataBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelDateTime;
use \PropelException;
use \PropelPDO;
use DTA\MetadataBundle\Model\Partner;
use DTA\MetadataBundle\Model\PartnerPeer;
use DTA\MetadataBundle\Model\PartnerQuery;

abstract class BasePartner extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\PartnerPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PartnerPeer
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
     * The value for the adress field.
     * @var        string
     */
    protected $adress;

    /**
     * The value for the person field.
     * @var        string
     */
    protected $person;

    /**
     * The value for the mail field.
     * @var        string
     */
    protected $mail;

    /**
     * The value for the web field.
     * @var        string
     */
    protected $web;

    /**
     * The value for the comments field.
     * @var        string
     */
    protected $comments;

    /**
     * The value for the phone1 field.
     * @var        string
     */
    protected $phone1;

    /**
     * The value for the phone2 field.
     * @var        string
     */
    protected $phone2;

    /**
     * The value for the phone3 field.
     * @var        string
     */
    protected $phone3;

    /**
     * The value for the fax field.
     * @var        string
     */
    protected $fax;

    /**
     * The value for the log_last_change field.
     * @var        string
     */
    protected $log_last_change;

    /**
     * The value for the log_last_user field.
     * @var        int
     */
    protected $log_last_user;

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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [adress] column value.
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Get the [person] column value.
     *
     * @return string
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Get the [mail] column value.
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Get the [web] column value.
     *
     * @return string
     */
    public function getWeb()
    {
        return $this->web;
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
     * Get the [phone1] column value.
     *
     * @return string
     */
    public function getPhone1()
    {
        return $this->phone1;
    }

    /**
     * Get the [phone2] column value.
     *
     * @return string
     */
    public function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * Get the [phone3] column value.
     *
     * @return string
     */
    public function getPhone3()
    {
        return $this->phone3;
    }

    /**
     * Get the [fax] column value.
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Get the [optionally formatted] temporal [log_last_change] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getLogLastChange($format = null)
    {
        if ($this->log_last_change === null) {
            return null;
        }

        if ($this->log_last_change === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        } else {
            try {
                $dt = new DateTime($this->log_last_change);
            } catch (Exception $x) {
                throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->log_last_change, true), $x);
            }
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        } elseif (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        } else {
            return $dt->format($format);
        }
    }

    /**
     * Get the [log_last_user] column value.
     *
     * @return int
     */
    public function getLogLastUser()
    {
        return $this->log_last_user;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PartnerPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = PartnerPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [adress] column.
     *
     * @param string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setAdress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->adress !== $v) {
            $this->adress = $v;
            $this->modifiedColumns[] = PartnerPeer::ADRESS;
        }


        return $this;
    } // setAdress()

    /**
     * Set the value of [person] column.
     *
     * @param string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setPerson($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->person !== $v) {
            $this->person = $v;
            $this->modifiedColumns[] = PartnerPeer::PERSON;
        }


        return $this;
    } // setPerson()

    /**
     * Set the value of [mail] column.
     *
     * @param string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setMail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mail !== $v) {
            $this->mail = $v;
            $this->modifiedColumns[] = PartnerPeer::MAIL;
        }


        return $this;
    } // setMail()

    /**
     * Set the value of [web] column.
     *
     * @param string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setWeb($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->web !== $v) {
            $this->web = $v;
            $this->modifiedColumns[] = PartnerPeer::WEB;
        }


        return $this;
    } // setWeb()

    /**
     * Set the value of [comments] column.
     *
     * @param string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setComments($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->comments !== $v) {
            $this->comments = $v;
            $this->modifiedColumns[] = PartnerPeer::COMMENTS;
        }


        return $this;
    } // setComments()

    /**
     * Set the value of [phone1] column.
     *
     * @param string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setPhone1($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone1 !== $v) {
            $this->phone1 = $v;
            $this->modifiedColumns[] = PartnerPeer::PHONE1;
        }


        return $this;
    } // setPhone1()

    /**
     * Set the value of [phone2] column.
     *
     * @param string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setPhone2($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone2 !== $v) {
            $this->phone2 = $v;
            $this->modifiedColumns[] = PartnerPeer::PHONE2;
        }


        return $this;
    } // setPhone2()

    /**
     * Set the value of [phone3] column.
     *
     * @param string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setPhone3($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone3 !== $v) {
            $this->phone3 = $v;
            $this->modifiedColumns[] = PartnerPeer::PHONE3;
        }


        return $this;
    } // setPhone3()

    /**
     * Set the value of [fax] column.
     *
     * @param string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setFax($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->fax !== $v) {
            $this->fax = $v;
            $this->modifiedColumns[] = PartnerPeer::FAX;
        }


        return $this;
    } // setFax()

    /**
     * Sets the value of [log_last_change] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Partner The current object (for fluent API support)
     */
    public function setLogLastChange($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->log_last_change !== null || $dt !== null) {
            $currentDateAsString = ($this->log_last_change !== null && $tmpDt = new DateTime($this->log_last_change)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->log_last_change = $newDateAsString;
                $this->modifiedColumns[] = PartnerPeer::LOG_LAST_CHANGE;
            }
        } // if either are not null


        return $this;
    } // setLogLastChange()

    /**
     * Set the value of [log_last_user] column.
     *
     * @param int $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setLogLastUser($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->log_last_user !== $v) {
            $this->log_last_user = $v;
            $this->modifiedColumns[] = PartnerPeer::LOG_LAST_USER;
        }


        return $this;
    } // setLogLastUser()

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
            $this->adress = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->person = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->mail = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->web = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->comments = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->phone1 = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->phone2 = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->phone3 = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->fax = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->log_last_change = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->log_last_user = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 13; // 13 = PartnerPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Partner object", $e);
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
            $con = Propel::getConnection(PartnerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PartnerPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

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
            $con = Propel::getConnection(PartnerPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PartnerQuery::create()
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
            $con = Propel::getConnection(PartnerPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                PartnerPeer::addInstanceToPool($this);
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

        $this->modifiedColumns[] = PartnerPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PartnerPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PartnerPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PartnerPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(PartnerPeer::ADRESS)) {
            $modifiedColumns[':p' . $index++]  = '`adress`';
        }
        if ($this->isColumnModified(PartnerPeer::PERSON)) {
            $modifiedColumns[':p' . $index++]  = '`person`';
        }
        if ($this->isColumnModified(PartnerPeer::MAIL)) {
            $modifiedColumns[':p' . $index++]  = '`mail`';
        }
        if ($this->isColumnModified(PartnerPeer::WEB)) {
            $modifiedColumns[':p' . $index++]  = '`web`';
        }
        if ($this->isColumnModified(PartnerPeer::COMMENTS)) {
            $modifiedColumns[':p' . $index++]  = '`comments`';
        }
        if ($this->isColumnModified(PartnerPeer::PHONE1)) {
            $modifiedColumns[':p' . $index++]  = '`phone1`';
        }
        if ($this->isColumnModified(PartnerPeer::PHONE2)) {
            $modifiedColumns[':p' . $index++]  = '`phone2`';
        }
        if ($this->isColumnModified(PartnerPeer::PHONE3)) {
            $modifiedColumns[':p' . $index++]  = '`phone3`';
        }
        if ($this->isColumnModified(PartnerPeer::FAX)) {
            $modifiedColumns[':p' . $index++]  = '`fax`';
        }
        if ($this->isColumnModified(PartnerPeer::LOG_LAST_CHANGE)) {
            $modifiedColumns[':p' . $index++]  = '`log_last_change`';
        }
        if ($this->isColumnModified(PartnerPeer::LOG_LAST_USER)) {
            $modifiedColumns[':p' . $index++]  = '`log_last_user`';
        }

        $sql = sprintf(
            'INSERT INTO `partner` (%s) VALUES (%s)',
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
                    case '`adress`':
                        $stmt->bindValue($identifier, $this->adress, PDO::PARAM_STR);
                        break;
                    case '`person`':
                        $stmt->bindValue($identifier, $this->person, PDO::PARAM_STR);
                        break;
                    case '`mail`':
                        $stmt->bindValue($identifier, $this->mail, PDO::PARAM_STR);
                        break;
                    case '`web`':
                        $stmt->bindValue($identifier, $this->web, PDO::PARAM_STR);
                        break;
                    case '`comments`':
                        $stmt->bindValue($identifier, $this->comments, PDO::PARAM_STR);
                        break;
                    case '`phone1`':
                        $stmt->bindValue($identifier, $this->phone1, PDO::PARAM_STR);
                        break;
                    case '`phone2`':
                        $stmt->bindValue($identifier, $this->phone2, PDO::PARAM_STR);
                        break;
                    case '`phone3`':
                        $stmt->bindValue($identifier, $this->phone3, PDO::PARAM_STR);
                        break;
                    case '`fax`':
                        $stmt->bindValue($identifier, $this->fax, PDO::PARAM_STR);
                        break;
                    case '`log_last_change`':
                        $stmt->bindValue($identifier, $this->log_last_change, PDO::PARAM_STR);
                        break;
                    case '`log_last_user`':
                        $stmt->bindValue($identifier, $this->log_last_user, PDO::PARAM_INT);
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


            if (($retval = PartnerPeer::doValidate($this, $columns)) !== true) {
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
        $pos = PartnerPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getAdress();
                break;
            case 3:
                return $this->getPerson();
                break;
            case 4:
                return $this->getMail();
                break;
            case 5:
                return $this->getWeb();
                break;
            case 6:
                return $this->getComments();
                break;
            case 7:
                return $this->getPhone1();
                break;
            case 8:
                return $this->getPhone2();
                break;
            case 9:
                return $this->getPhone3();
                break;
            case 10:
                return $this->getFax();
                break;
            case 11:
                return $this->getLogLastChange();
                break;
            case 12:
                return $this->getLogLastUser();
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
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array())
    {
        if (isset($alreadyDumpedObjects['Partner'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Partner'][$this->getPrimaryKey()] = true;
        $keys = PartnerPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getAdress(),
            $keys[3] => $this->getPerson(),
            $keys[4] => $this->getMail(),
            $keys[5] => $this->getWeb(),
            $keys[6] => $this->getComments(),
            $keys[7] => $this->getPhone1(),
            $keys[8] => $this->getPhone2(),
            $keys[9] => $this->getPhone3(),
            $keys[10] => $this->getFax(),
            $keys[11] => $this->getLogLastChange(),
            $keys[12] => $this->getLogLastUser(),
        );

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
        $pos = PartnerPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setAdress($value);
                break;
            case 3:
                $this->setPerson($value);
                break;
            case 4:
                $this->setMail($value);
                break;
            case 5:
                $this->setWeb($value);
                break;
            case 6:
                $this->setComments($value);
                break;
            case 7:
                $this->setPhone1($value);
                break;
            case 8:
                $this->setPhone2($value);
                break;
            case 9:
                $this->setPhone3($value);
                break;
            case 10:
                $this->setFax($value);
                break;
            case 11:
                $this->setLogLastChange($value);
                break;
            case 12:
                $this->setLogLastUser($value);
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
        $keys = PartnerPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setAdress($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPerson($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setMail($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setWeb($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setComments($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setPhone1($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setPhone2($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setPhone3($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setFax($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setLogLastChange($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setLogLastUser($arr[$keys[12]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PartnerPeer::DATABASE_NAME);

        if ($this->isColumnModified(PartnerPeer::ID)) $criteria->add(PartnerPeer::ID, $this->id);
        if ($this->isColumnModified(PartnerPeer::NAME)) $criteria->add(PartnerPeer::NAME, $this->name);
        if ($this->isColumnModified(PartnerPeer::ADRESS)) $criteria->add(PartnerPeer::ADRESS, $this->adress);
        if ($this->isColumnModified(PartnerPeer::PERSON)) $criteria->add(PartnerPeer::PERSON, $this->person);
        if ($this->isColumnModified(PartnerPeer::MAIL)) $criteria->add(PartnerPeer::MAIL, $this->mail);
        if ($this->isColumnModified(PartnerPeer::WEB)) $criteria->add(PartnerPeer::WEB, $this->web);
        if ($this->isColumnModified(PartnerPeer::COMMENTS)) $criteria->add(PartnerPeer::COMMENTS, $this->comments);
        if ($this->isColumnModified(PartnerPeer::PHONE1)) $criteria->add(PartnerPeer::PHONE1, $this->phone1);
        if ($this->isColumnModified(PartnerPeer::PHONE2)) $criteria->add(PartnerPeer::PHONE2, $this->phone2);
        if ($this->isColumnModified(PartnerPeer::PHONE3)) $criteria->add(PartnerPeer::PHONE3, $this->phone3);
        if ($this->isColumnModified(PartnerPeer::FAX)) $criteria->add(PartnerPeer::FAX, $this->fax);
        if ($this->isColumnModified(PartnerPeer::LOG_LAST_CHANGE)) $criteria->add(PartnerPeer::LOG_LAST_CHANGE, $this->log_last_change);
        if ($this->isColumnModified(PartnerPeer::LOG_LAST_USER)) $criteria->add(PartnerPeer::LOG_LAST_USER, $this->log_last_user);

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
        $criteria = new Criteria(PartnerPeer::DATABASE_NAME);
        $criteria->add(PartnerPeer::ID, $this->id);

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
     * @param object $copyObj An object of Partner (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setAdress($this->getAdress());
        $copyObj->setPerson($this->getPerson());
        $copyObj->setMail($this->getMail());
        $copyObj->setWeb($this->getWeb());
        $copyObj->setComments($this->getComments());
        $copyObj->setPhone1($this->getPhone1());
        $copyObj->setPhone2($this->getPhone2());
        $copyObj->setPhone3($this->getPhone3());
        $copyObj->setFax($this->getFax());
        $copyObj->setLogLastChange($this->getLogLastChange());
        $copyObj->setLogLastUser($this->getLogLastUser());
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
     * @return Partner Clone of current object.
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
     * @return PartnerPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PartnerPeer();
        }

        return self::$peer;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->adress = null;
        $this->person = null;
        $this->mail = null;
        $this->web = null;
        $this->comments = null;
        $this->phone1 = null;
        $this->phone2 = null;
        $this->phone3 = null;
        $this->fax = null;
        $this->log_last_change = null;
        $this->log_last_user = null;
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

    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PartnerPeer::DEFAULT_STRING_FORMAT);
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