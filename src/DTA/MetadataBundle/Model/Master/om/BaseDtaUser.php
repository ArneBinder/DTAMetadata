<?php

namespace DTA\MetadataBundle\Model\Master\om;

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
use DTA\MetadataBundle\Model\Data\Publication;
use DTA\MetadataBundle\Model\Data\PublicationQuery;
use DTA\MetadataBundle\Model\Master\DtaUser;
use DTA\MetadataBundle\Model\Master\DtaUserPeer;
use DTA\MetadataBundle\Model\Master\DtaUserQuery;
use DTA\MetadataBundle\Model\Master\RecentUse;
use DTA\MetadataBundle\Model\Master\RecentUseQuery;
use DTA\MetadataBundle\Model\Workflow\Task;
use DTA\MetadataBundle\Model\Workflow\TaskQuery;

abstract class BaseDtaUser extends BaseObject implements Persistent, \DTA\MetadataBundle\Model\table_row_view\TableRowViewInterface
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\Master\\DtaUserPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        DtaUserPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinit loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the username field.
     * @var        string
     */
    protected $username;

    /**
     * The value for the password field.
     * @var        string
     */
    protected $password;

    /**
     * The value for the salt field.
     * @var        string
     */
    protected $salt;

    /**
     * The value for the mail field.
     * @var        string
     */
    protected $mail;

    /**
     * The value for the admin field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $admin;

    /**
     * The value for the legacy_user_id field.
     * @var        int
     */
    protected $legacy_user_id;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * @var        PropelObjectCollection|Publication[] Collection to store aggregation of Publication objects.
     */
    protected $collLastChangedPublications;
    protected $collLastChangedPublicationsPartial;

    /**
     * @var        PropelObjectCollection|RecentUse[] Collection to store aggregation of RecentUse objects.
     */
    protected $collRecentUses;
    protected $collRecentUsesPartial;

    /**
     * @var        PropelObjectCollection|Task[] Collection to store aggregation of Task objects.
     */
    protected $collTasks;
    protected $collTasksPartial;

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
    public static $tableRowViewCaptions = array('id', 'benutzername', 'mail', 'administratorrechte', );	public   $tableRowViewAccessors = array('id'=>'Id', 'benutzername'=>'Username', 'mail'=>'Mail', 'administratorrechte'=>'accessor:adminToString', );
    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $lastChangedPublicationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $recentUsesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $tasksScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->admin = false;
    }

    /**
     * Initializes internal state of BaseDtaUser object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
    }

    /**
     * Get the [username] column value.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the [salt] column value.
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
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
     * Get the [admin] column value.
     *
     * @return boolean
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Get the [legacy_user_id] column value.
     * id_user der alten Datenbank, die dem Datensatz zugrundeliegt.
     * @return int
     */
    public function getLegacyUserId()
    {
        return $this->legacy_user_id;
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
     * Set the value of [username] column.
     *
     * @param string $v new value
     * @return DtaUser The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[] = DtaUserPeer::USERNAME;
        }


        return $this;
    } // setUsername()

    /**
     * Set the value of [password] column.
     *
     * @param string $v new value
     * @return DtaUser The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[] = DtaUserPeer::PASSWORD;
        }


        return $this;
    } // setPassword()

    /**
     * Set the value of [salt] column.
     *
     * @param string $v new value
     * @return DtaUser The current object (for fluent API support)
     */
    public function setSalt($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->salt !== $v) {
            $this->salt = $v;
            $this->modifiedColumns[] = DtaUserPeer::SALT;
        }


        return $this;
    } // setSalt()

    /**
     * Set the value of [mail] column.
     *
     * @param string $v new value
     * @return DtaUser The current object (for fluent API support)
     */
    public function setMail($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->mail !== $v) {
            $this->mail = $v;
            $this->modifiedColumns[] = DtaUserPeer::MAIL;
        }


        return $this;
    } // setMail()

    /**
     * Sets the value of the [admin] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return DtaUser The current object (for fluent API support)
     */
    public function setAdmin($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->admin !== $v) {
            $this->admin = $v;
            $this->modifiedColumns[] = DtaUserPeer::ADMIN;
        }


        return $this;
    } // setAdmin()

    /**
     * Set the value of [legacy_user_id] column.
     * id_user der alten Datenbank, die dem Datensatz zugrundeliegt.
     * @param int $v new value
     * @return DtaUser The current object (for fluent API support)
     */
    public function setLegacyUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->legacy_user_id !== $v) {
            $this->legacy_user_id = $v;
            $this->modifiedColumns[] = DtaUserPeer::LEGACY_USER_ID;
        }


        return $this;
    } // setLegacyUserId()

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return DtaUser The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = DtaUserPeer::ID;
        }


        return $this;
    } // setId()

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
            if ($this->admin !== false) {
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

            $this->username = ($row[$startcol + 0] !== null) ? (string) $row[$startcol + 0] : null;
            $this->password = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->salt = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->mail = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->admin = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
            $this->legacy_user_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 7; // 7 = DtaUserPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating DtaUser object", $e);
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
            $con = Propel::getConnection(DtaUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = DtaUserPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collLastChangedPublications = null;

            $this->collRecentUses = null;

            $this->collTasks = null;

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
            $con = Propel::getConnection(DtaUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = DtaUserQuery::create()
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
            $con = Propel::getConnection(DtaUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                DtaUserPeer::addInstanceToPool($this);
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

            if ($this->lastChangedPublicationsScheduledForDeletion !== null) {
                if (!$this->lastChangedPublicationsScheduledForDeletion->isEmpty()) {
                    foreach ($this->lastChangedPublicationsScheduledForDeletion as $lastChangedPublication) {
                        // need to save related object because we set the relation to null
                        $lastChangedPublication->save($con);
                    }
                    $this->lastChangedPublicationsScheduledForDeletion = null;
                }
            }

            if ($this->collLastChangedPublications !== null) {
                foreach ($this->collLastChangedPublications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->recentUsesScheduledForDeletion !== null) {
                if (!$this->recentUsesScheduledForDeletion->isEmpty()) {
                    RecentUseQuery::create()
                        ->filterByPrimaryKeys($this->recentUsesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->recentUsesScheduledForDeletion = null;
                }
            }

            if ($this->collRecentUses !== null) {
                foreach ($this->collRecentUses as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->tasksScheduledForDeletion !== null) {
                if (!$this->tasksScheduledForDeletion->isEmpty()) {
                    foreach ($this->tasksScheduledForDeletion as $task) {
                        // need to save related object because we set the relation to null
                        $task->save($con);
                    }
                    $this->tasksScheduledForDeletion = null;
                }
            }

            if ($this->collTasks !== null) {
                foreach ($this->collTasks as $referrerFK) {
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

        $this->modifiedColumns[] = DtaUserPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DtaUserPeer::ID . ')');
        }
        if (null === $this->id) {
            try {
                $stmt = $con->query("SELECT nextval('dta_user_id_seq')");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $this->id = $row[0];
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DtaUserPeer::USERNAME)) {
            $modifiedColumns[':p' . $index++]  = '"username"';
        }
        if ($this->isColumnModified(DtaUserPeer::PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = '"password"';
        }
        if ($this->isColumnModified(DtaUserPeer::SALT)) {
            $modifiedColumns[':p' . $index++]  = '"salt"';
        }
        if ($this->isColumnModified(DtaUserPeer::MAIL)) {
            $modifiedColumns[':p' . $index++]  = '"mail"';
        }
        if ($this->isColumnModified(DtaUserPeer::ADMIN)) {
            $modifiedColumns[':p' . $index++]  = '"admin"';
        }
        if ($this->isColumnModified(DtaUserPeer::LEGACY_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '"legacy_user_id"';
        }
        if ($this->isColumnModified(DtaUserPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '"id"';
        }

        $sql = sprintf(
            'INSERT INTO "dta_user" (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '"username"':
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case '"password"':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case '"salt"':
                        $stmt->bindValue($identifier, $this->salt, PDO::PARAM_STR);
                        break;
                    case '"mail"':
                        $stmt->bindValue($identifier, $this->mail, PDO::PARAM_STR);
                        break;
                    case '"admin"':
                        $stmt->bindValue($identifier, $this->admin, PDO::PARAM_BOOL);
                        break;
                    case '"legacy_user_id"':
                        $stmt->bindValue($identifier, $this->legacy_user_id, PDO::PARAM_INT);
                        break;
                    case '"id"':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
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


            if (($retval = DtaUserPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collLastChangedPublications !== null) {
                    foreach ($this->collLastChangedPublications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collRecentUses !== null) {
                    foreach ($this->collRecentUses as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collTasks !== null) {
                    foreach ($this->collTasks as $referrerFK) {
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
        $pos = DtaUserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getUsername();
                break;
            case 1:
                return $this->getPassword();
                break;
            case 2:
                return $this->getSalt();
                break;
            case 3:
                return $this->getMail();
                break;
            case 4:
                return $this->getAdmin();
                break;
            case 5:
                return $this->getLegacyUserId();
                break;
            case 6:
                return $this->getId();
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
        if (isset($alreadyDumpedObjects['DtaUser'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['DtaUser'][$this->getPrimaryKey()] = true;
        $keys = DtaUserPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getUsername(),
            $keys[1] => $this->getPassword(),
            $keys[2] => $this->getSalt(),
            $keys[3] => $this->getMail(),
            $keys[4] => $this->getAdmin(),
            $keys[5] => $this->getLegacyUserId(),
            $keys[6] => $this->getId(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->collLastChangedPublications) {
                $result['LastChangedPublications'] = $this->collLastChangedPublications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collRecentUses) {
                $result['RecentUses'] = $this->collRecentUses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTasks) {
                $result['Tasks'] = $this->collTasks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = DtaUserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setUsername($value);
                break;
            case 1:
                $this->setPassword($value);
                break;
            case 2:
                $this->setSalt($value);
                break;
            case 3:
                $this->setMail($value);
                break;
            case 4:
                $this->setAdmin($value);
                break;
            case 5:
                $this->setLegacyUserId($value);
                break;
            case 6:
                $this->setId($value);
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
        $keys = DtaUserPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setUsername($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setPassword($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setSalt($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setMail($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setAdmin($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setLegacyUserId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setId($arr[$keys[6]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(DtaUserPeer::DATABASE_NAME);

        if ($this->isColumnModified(DtaUserPeer::USERNAME)) $criteria->add(DtaUserPeer::USERNAME, $this->username);
        if ($this->isColumnModified(DtaUserPeer::PASSWORD)) $criteria->add(DtaUserPeer::PASSWORD, $this->password);
        if ($this->isColumnModified(DtaUserPeer::SALT)) $criteria->add(DtaUserPeer::SALT, $this->salt);
        if ($this->isColumnModified(DtaUserPeer::MAIL)) $criteria->add(DtaUserPeer::MAIL, $this->mail);
        if ($this->isColumnModified(DtaUserPeer::ADMIN)) $criteria->add(DtaUserPeer::ADMIN, $this->admin);
        if ($this->isColumnModified(DtaUserPeer::LEGACY_USER_ID)) $criteria->add(DtaUserPeer::LEGACY_USER_ID, $this->legacy_user_id);
        if ($this->isColumnModified(DtaUserPeer::ID)) $criteria->add(DtaUserPeer::ID, $this->id);

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
        $criteria = new Criteria(DtaUserPeer::DATABASE_NAME);
        $criteria->add(DtaUserPeer::ID, $this->id);

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
     * @param object $copyObj An object of DtaUser (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUsername($this->getUsername());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setSalt($this->getSalt());
        $copyObj->setMail($this->getMail());
        $copyObj->setAdmin($this->getAdmin());
        $copyObj->setLegacyUserId($this->getLegacyUserId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getLastChangedPublications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLastChangedPublication($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRecentUses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRecentUse($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTasks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTask($relObj->copy($deepCopy));
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
     * @return DtaUser Clone of current object.
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
     * @return DtaUserPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new DtaUserPeer();
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
        if ('LastChangedPublication' == $relationName) {
            $this->initLastChangedPublications();
        }
        if ('RecentUse' == $relationName) {
            $this->initRecentUses();
        }
        if ('Task' == $relationName) {
            $this->initTasks();
        }
    }

    /**
     * Clears out the collLastChangedPublications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return DtaUser The current object (for fluent API support)
     * @see        addLastChangedPublications()
     */
    public function clearLastChangedPublications()
    {
        $this->collLastChangedPublications = null; // important to set this to null since that means it is uninitialized
        $this->collLastChangedPublicationsPartial = null;

        return $this;
    }

    /**
     * reset is the collLastChangedPublications collection loaded partially
     *
     * @return void
     */
    public function resetPartialLastChangedPublications($v = true)
    {
        $this->collLastChangedPublicationsPartial = $v;
    }

    /**
     * Initializes the collLastChangedPublications collection.
     *
     * By default this just sets the collLastChangedPublications collection to an empty array (like clearcollLastChangedPublications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLastChangedPublications($overrideExisting = true)
    {
        if (null !== $this->collLastChangedPublications && !$overrideExisting) {
            return;
        }
        $this->collLastChangedPublications = new PropelObjectCollection();
        $this->collLastChangedPublications->setModel('Publication');
    }

    /**
     * Gets an array of Publication objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this DtaUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Publication[] List of Publication objects
     * @throws PropelException
     */
    public function getLastChangedPublications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collLastChangedPublicationsPartial && !$this->isNew();
        if (null === $this->collLastChangedPublications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLastChangedPublications) {
                // return empty collection
                $this->initLastChangedPublications();
            } else {
                $collLastChangedPublications = PublicationQuery::create(null, $criteria)
                    ->filterByLastChangedByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collLastChangedPublicationsPartial && count($collLastChangedPublications)) {
                      $this->initLastChangedPublications(false);

                      foreach($collLastChangedPublications as $obj) {
                        if (false == $this->collLastChangedPublications->contains($obj)) {
                          $this->collLastChangedPublications->append($obj);
                        }
                      }

                      $this->collLastChangedPublicationsPartial = true;
                    }

                    $collLastChangedPublications->getInternalIterator()->rewind();
                    return $collLastChangedPublications;
                }

                if($partial && $this->collLastChangedPublications) {
                    foreach($this->collLastChangedPublications as $obj) {
                        if($obj->isNew()) {
                            $collLastChangedPublications[] = $obj;
                        }
                    }
                }

                $this->collLastChangedPublications = $collLastChangedPublications;
                $this->collLastChangedPublicationsPartial = false;
            }
        }

        return $this->collLastChangedPublications;
    }

    /**
     * Sets a collection of LastChangedPublication objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $lastChangedPublications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return DtaUser The current object (for fluent API support)
     */
    public function setLastChangedPublications(PropelCollection $lastChangedPublications, PropelPDO $con = null)
    {
        $lastChangedPublicationsToDelete = $this->getLastChangedPublications(new Criteria(), $con)->diff($lastChangedPublications);

        $this->lastChangedPublicationsScheduledForDeletion = unserialize(serialize($lastChangedPublicationsToDelete));

        foreach ($lastChangedPublicationsToDelete as $lastChangedPublicationRemoved) {
            $lastChangedPublicationRemoved->setLastChangedByUser(null);
        }

        $this->collLastChangedPublications = null;
        foreach ($lastChangedPublications as $lastChangedPublication) {
            $this->addLastChangedPublication($lastChangedPublication);
        }

        $this->collLastChangedPublications = $lastChangedPublications;
        $this->collLastChangedPublicationsPartial = false;

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
    public function countLastChangedPublications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collLastChangedPublicationsPartial && !$this->isNew();
        if (null === $this->collLastChangedPublications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLastChangedPublications) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getLastChangedPublications());
            }
            $query = PublicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLastChangedByUser($this)
                ->count($con);
        }

        return count($this->collLastChangedPublications);
    }

    /**
     * Method called to associate a Publication object to this object
     * through the Publication foreign key attribute.
     *
     * @param    Publication $l Publication
     * @return DtaUser The current object (for fluent API support)
     */
    public function addLastChangedPublication(Publication $l)
    {
        if ($this->collLastChangedPublications === null) {
            $this->initLastChangedPublications();
            $this->collLastChangedPublicationsPartial = true;
        }
        if (!in_array($l, $this->collLastChangedPublications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddLastChangedPublication($l);
        }

        return $this;
    }

    /**
     * @param	LastChangedPublication $lastChangedPublication The lastChangedPublication object to add.
     */
    protected function doAddLastChangedPublication($lastChangedPublication)
    {
        $this->collLastChangedPublications[]= $lastChangedPublication;
        $lastChangedPublication->setLastChangedByUser($this);
    }

    /**
     * @param	LastChangedPublication $lastChangedPublication The lastChangedPublication object to remove.
     * @return DtaUser The current object (for fluent API support)
     */
    public function removeLastChangedPublication($lastChangedPublication)
    {
        if ($this->getLastChangedPublications()->contains($lastChangedPublication)) {
            $this->collLastChangedPublications->remove($this->collLastChangedPublications->search($lastChangedPublication));
            if (null === $this->lastChangedPublicationsScheduledForDeletion) {
                $this->lastChangedPublicationsScheduledForDeletion = clone $this->collLastChangedPublications;
                $this->lastChangedPublicationsScheduledForDeletion->clear();
            }
            $this->lastChangedPublicationsScheduledForDeletion[]= $lastChangedPublication;
            $lastChangedPublication->setLastChangedByUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DtaUser is new, it will return
     * an empty collection; or if this DtaUser has previously
     * been saved, it will retrieve related LastChangedPublications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DtaUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getLastChangedPublicationsJoinTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Title', $join_behavior);

        return $this->getLastChangedPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DtaUser is new, it will return
     * an empty collection; or if this DtaUser has previously
     * been saved, it will retrieve related LastChangedPublications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DtaUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getLastChangedPublicationsJoinPublishingcompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Publishingcompany', $join_behavior);

        return $this->getLastChangedPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DtaUser is new, it will return
     * an empty collection; or if this DtaUser has previously
     * been saved, it will retrieve related LastChangedPublications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DtaUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getLastChangedPublicationsJoinPlace($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Place', $join_behavior);

        return $this->getLastChangedPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DtaUser is new, it will return
     * an empty collection; or if this DtaUser has previously
     * been saved, it will retrieve related LastChangedPublications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DtaUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getLastChangedPublicationsJoinDatespecificationRelatedByPublicationdateId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('DatespecificationRelatedByPublicationdateId', $join_behavior);

        return $this->getLastChangedPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DtaUser is new, it will return
     * an empty collection; or if this DtaUser has previously
     * been saved, it will retrieve related LastChangedPublications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DtaUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getLastChangedPublicationsJoinDatespecificationRelatedByCreationdateId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('DatespecificationRelatedByCreationdateId', $join_behavior);

        return $this->getLastChangedPublications($query, $con);
    }

    /**
     * Clears out the collRecentUses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return DtaUser The current object (for fluent API support)
     * @see        addRecentUses()
     */
    public function clearRecentUses()
    {
        $this->collRecentUses = null; // important to set this to null since that means it is uninitialized
        $this->collRecentUsesPartial = null;

        return $this;
    }

    /**
     * reset is the collRecentUses collection loaded partially
     *
     * @return void
     */
    public function resetPartialRecentUses($v = true)
    {
        $this->collRecentUsesPartial = $v;
    }

    /**
     * Initializes the collRecentUses collection.
     *
     * By default this just sets the collRecentUses collection to an empty array (like clearcollRecentUses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRecentUses($overrideExisting = true)
    {
        if (null !== $this->collRecentUses && !$overrideExisting) {
            return;
        }
        $this->collRecentUses = new PropelObjectCollection();
        $this->collRecentUses->setModel('RecentUse');
    }

    /**
     * Gets an array of RecentUse objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this DtaUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|RecentUse[] List of RecentUse objects
     * @throws PropelException
     */
    public function getRecentUses($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collRecentUsesPartial && !$this->isNew();
        if (null === $this->collRecentUses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collRecentUses) {
                // return empty collection
                $this->initRecentUses();
            } else {
                $collRecentUses = RecentUseQuery::create(null, $criteria)
                    ->filterByDtaUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collRecentUsesPartial && count($collRecentUses)) {
                      $this->initRecentUses(false);

                      foreach($collRecentUses as $obj) {
                        if (false == $this->collRecentUses->contains($obj)) {
                          $this->collRecentUses->append($obj);
                        }
                      }

                      $this->collRecentUsesPartial = true;
                    }

                    $collRecentUses->getInternalIterator()->rewind();
                    return $collRecentUses;
                }

                if($partial && $this->collRecentUses) {
                    foreach($this->collRecentUses as $obj) {
                        if($obj->isNew()) {
                            $collRecentUses[] = $obj;
                        }
                    }
                }

                $this->collRecentUses = $collRecentUses;
                $this->collRecentUsesPartial = false;
            }
        }

        return $this->collRecentUses;
    }

    /**
     * Sets a collection of RecentUse objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $recentUses A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return DtaUser The current object (for fluent API support)
     */
    public function setRecentUses(PropelCollection $recentUses, PropelPDO $con = null)
    {
        $recentUsesToDelete = $this->getRecentUses(new Criteria(), $con)->diff($recentUses);

        $this->recentUsesScheduledForDeletion = unserialize(serialize($recentUsesToDelete));

        foreach ($recentUsesToDelete as $recentUseRemoved) {
            $recentUseRemoved->setDtaUser(null);
        }

        $this->collRecentUses = null;
        foreach ($recentUses as $recentUse) {
            $this->addRecentUse($recentUse);
        }

        $this->collRecentUses = $recentUses;
        $this->collRecentUsesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related RecentUse objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related RecentUse objects.
     * @throws PropelException
     */
    public function countRecentUses(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collRecentUsesPartial && !$this->isNew();
        if (null === $this->collRecentUses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRecentUses) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getRecentUses());
            }
            $query = RecentUseQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDtaUser($this)
                ->count($con);
        }

        return count($this->collRecentUses);
    }

    /**
     * Method called to associate a RecentUse object to this object
     * through the RecentUse foreign key attribute.
     *
     * @param    RecentUse $l RecentUse
     * @return DtaUser The current object (for fluent API support)
     */
    public function addRecentUse(RecentUse $l)
    {
        if ($this->collRecentUses === null) {
            $this->initRecentUses();
            $this->collRecentUsesPartial = true;
        }
        if (!in_array($l, $this->collRecentUses->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddRecentUse($l);
        }

        return $this;
    }

    /**
     * @param	RecentUse $recentUse The recentUse object to add.
     */
    protected function doAddRecentUse($recentUse)
    {
        $this->collRecentUses[]= $recentUse;
        $recentUse->setDtaUser($this);
    }

    /**
     * @param	RecentUse $recentUse The recentUse object to remove.
     * @return DtaUser The current object (for fluent API support)
     */
    public function removeRecentUse($recentUse)
    {
        if ($this->getRecentUses()->contains($recentUse)) {
            $this->collRecentUses->remove($this->collRecentUses->search($recentUse));
            if (null === $this->recentUsesScheduledForDeletion) {
                $this->recentUsesScheduledForDeletion = clone $this->collRecentUses;
                $this->recentUsesScheduledForDeletion->clear();
            }
            $this->recentUsesScheduledForDeletion[]= clone $recentUse;
            $recentUse->setDtaUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DtaUser is new, it will return
     * an empty collection; or if this DtaUser has previously
     * been saved, it will retrieve related RecentUses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DtaUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|RecentUse[] List of RecentUse objects
     */
    public function getRecentUsesJoinPublication($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = RecentUseQuery::create(null, $criteria);
        $query->joinWith('Publication', $join_behavior);

        return $this->getRecentUses($query, $con);
    }

    /**
     * Clears out the collTasks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return DtaUser The current object (for fluent API support)
     * @see        addTasks()
     */
    public function clearTasks()
    {
        $this->collTasks = null; // important to set this to null since that means it is uninitialized
        $this->collTasksPartial = null;

        return $this;
    }

    /**
     * reset is the collTasks collection loaded partially
     *
     * @return void
     */
    public function resetPartialTasks($v = true)
    {
        $this->collTasksPartial = $v;
    }

    /**
     * Initializes the collTasks collection.
     *
     * By default this just sets the collTasks collection to an empty array (like clearcollTasks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTasks($overrideExisting = true)
    {
        if (null !== $this->collTasks && !$overrideExisting) {
            return;
        }
        $this->collTasks = new PropelObjectCollection();
        $this->collTasks->setModel('Task');
    }

    /**
     * Gets an array of Task objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this DtaUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Task[] List of Task objects
     * @throws PropelException
     */
    public function getTasks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collTasksPartial && !$this->isNew();
        if (null === $this->collTasks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTasks) {
                // return empty collection
                $this->initTasks();
            } else {
                $collTasks = TaskQuery::create(null, $criteria)
                    ->filterByDtaUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collTasksPartial && count($collTasks)) {
                      $this->initTasks(false);

                      foreach($collTasks as $obj) {
                        if (false == $this->collTasks->contains($obj)) {
                          $this->collTasks->append($obj);
                        }
                      }

                      $this->collTasksPartial = true;
                    }

                    $collTasks->getInternalIterator()->rewind();
                    return $collTasks;
                }

                if($partial && $this->collTasks) {
                    foreach($this->collTasks as $obj) {
                        if($obj->isNew()) {
                            $collTasks[] = $obj;
                        }
                    }
                }

                $this->collTasks = $collTasks;
                $this->collTasksPartial = false;
            }
        }

        return $this->collTasks;
    }

    /**
     * Sets a collection of Task objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $tasks A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return DtaUser The current object (for fluent API support)
     */
    public function setTasks(PropelCollection $tasks, PropelPDO $con = null)
    {
        $tasksToDelete = $this->getTasks(new Criteria(), $con)->diff($tasks);

        $this->tasksScheduledForDeletion = unserialize(serialize($tasksToDelete));

        foreach ($tasksToDelete as $taskRemoved) {
            $taskRemoved->setDtaUser(null);
        }

        $this->collTasks = null;
        foreach ($tasks as $task) {
            $this->addTask($task);
        }

        $this->collTasks = $tasks;
        $this->collTasksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Task objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Task objects.
     * @throws PropelException
     */
    public function countTasks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collTasksPartial && !$this->isNew();
        if (null === $this->collTasks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTasks) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getTasks());
            }
            $query = TaskQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDtaUser($this)
                ->count($con);
        }

        return count($this->collTasks);
    }

    /**
     * Method called to associate a Task object to this object
     * through the Task foreign key attribute.
     *
     * @param    Task $l Task
     * @return DtaUser The current object (for fluent API support)
     */
    public function addTask(Task $l)
    {
        if ($this->collTasks === null) {
            $this->initTasks();
            $this->collTasksPartial = true;
        }
        if (!in_array($l, $this->collTasks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddTask($l);
        }

        return $this;
    }

    /**
     * @param	Task $task The task object to add.
     */
    protected function doAddTask($task)
    {
        $this->collTasks[]= $task;
        $task->setDtaUser($this);
    }

    /**
     * @param	Task $task The task object to remove.
     * @return DtaUser The current object (for fluent API support)
     */
    public function removeTask($task)
    {
        if ($this->getTasks()->contains($task)) {
            $this->collTasks->remove($this->collTasks->search($task));
            if (null === $this->tasksScheduledForDeletion) {
                $this->tasksScheduledForDeletion = clone $this->collTasks;
                $this->tasksScheduledForDeletion->clear();
            }
            $this->tasksScheduledForDeletion[]= $task;
            $task->setDtaUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DtaUser is new, it will return
     * an empty collection; or if this DtaUser has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DtaUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Task[] List of Task objects
     */
    public function getTasksJoinTasktype($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TaskQuery::create(null, $criteria);
        $query->joinWith('Tasktype', $join_behavior);

        return $this->getTasks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DtaUser is new, it will return
     * an empty collection; or if this DtaUser has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DtaUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Task[] List of Task objects
     */
    public function getTasksJoinPublicationgroup($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TaskQuery::create(null, $criteria);
        $query->joinWith('Publicationgroup', $join_behavior);

        return $this->getTasks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DtaUser is new, it will return
     * an empty collection; or if this DtaUser has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DtaUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Task[] List of Task objects
     */
    public function getTasksJoinPublication($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TaskQuery::create(null, $criteria);
        $query->joinWith('Publication', $join_behavior);

        return $this->getTasks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DtaUser is new, it will return
     * an empty collection; or if this DtaUser has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DtaUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Task[] List of Task objects
     */
    public function getTasksJoinPartner($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TaskQuery::create(null, $criteria);
        $query->joinWith('Partner', $join_behavior);

        return $this->getTasks($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->username = null;
        $this->password = null;
        $this->salt = null;
        $this->mail = null;
        $this->admin = null;
        $this->legacy_user_id = null;
        $this->id = null;
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
            if ($this->collLastChangedPublications) {
                foreach ($this->collLastChangedPublications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRecentUses) {
                foreach ($this->collRecentUses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTasks) {
                foreach ($this->collTasks as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collLastChangedPublications instanceof PropelCollection) {
            $this->collLastChangedPublications->clearIterator();
        }
        $this->collLastChangedPublications = null;
        if ($this->collRecentUses instanceof PropelCollection) {
            $this->collRecentUses->clearIterator();
        }
        $this->collRecentUses = null;
        if ($this->collTasks instanceof PropelCollection) {
            $this->collTasks->clearIterator();
        }
        $this->collTasks = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(DtaUserPeer::DEFAULT_STRING_FORMAT);
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
