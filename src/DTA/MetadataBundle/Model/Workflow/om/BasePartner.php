<?php

namespace DTA\MetadataBundle\Model\Workflow\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use DTA\MetadataBundle\Model\Workflow\CopyLocation;
use DTA\MetadataBundle\Model\Workflow\CopyLocationQuery;
use DTA\MetadataBundle\Model\Workflow\Imagesource;
use DTA\MetadataBundle\Model\Workflow\ImagesourceQuery;
use DTA\MetadataBundle\Model\Workflow\Partner;
use DTA\MetadataBundle\Model\Workflow\PartnerPeer;
use DTA\MetadataBundle\Model\Workflow\PartnerQuery;
use DTA\MetadataBundle\Model\Workflow\Task;
use DTA\MetadataBundle\Model\Workflow\TaskQuery;
use DTA\MetadataBundle\Model\Workflow\Textsource;
use DTA\MetadataBundle\Model\Workflow\TextsourceQuery;

abstract class BasePartner extends BaseObject implements Persistent, \DTA\MetadataBundle\Model\table_row_view\TableRowViewInterface
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\Workflow\\PartnerPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PartnerPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
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
     * The value for the contact_person field.
     * @var        string
     */
    protected $contact_person;

    /**
     * The value for the contactdata field.
     * @var        string
     */
    protected $contactdata;

    /**
     * The value for the comments field.
     * @var        string
     */
    protected $comments;

    /**
     * The value for the is_organization field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_organization;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        PropelObjectCollection|Task[] Collection to store aggregation of Task objects.
     */
    protected $collTasks;
    protected $collTasksPartial;

    /**
     * @var        PropelObjectCollection|CopyLocation[] Collection to store aggregation of CopyLocation objects.
     */
    protected $collCopyLocations;
    protected $collCopyLocationsPartial;

    /**
     * @var        PropelObjectCollection|Imagesource[] Collection to store aggregation of Imagesource objects.
     */
    protected $collImagesources;
    protected $collImagesourcesPartial;

    /**
     * @var        PropelObjectCollection|Textsource[] Collection to store aggregation of Textsource objects.
     */
    protected $collTextsources;
    protected $collTextsourcesPartial;

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
    public static $tableRowViewCaptions = array('Name', 'Ansprechpartner', 'Mail', 'Web', );	public   $tableRowViewAccessors = array('Name'=>'Name', 'Ansprechpartner'=>'ContactPerson', 'Mail'=>'Mail', 'Web'=>'Web', );	public static $queryConstructionString = NULL;
    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $tasksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $copyLocationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $imagesourcesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $textsourcesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->is_organization = false;
    }

    /**
     * Initializes internal state of BasePartner object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
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
     * Get the [contact_person] column value.
     * Ansprechpartner
     * @return string
     */
    public function getContactPerson()
    {

        return $this->contact_person;
    }

    /**
     * Get the [contactdata] column value.
     *
     * @return string
     */
    public function getContactdata()
    {

        return $this->contactdata;
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
     * Get the [is_organization] column value.
     *
     * @return boolean
     */
    public function getIsOrganization()
    {

        return $this->is_organization;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }


        try {
            $dt = new DateTime($this->created_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($this->updated_at === null) {
            return null;
        }


        try {
            $dt = new DateTime($this->updated_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
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
     * @param  string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = PartnerPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [mail] column.
     *
     * @param  string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setMail($v)
    {
        if ($v !== null && is_numeric($v)) {
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
     * @param  string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setWeb($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->web !== $v) {
            $this->web = $v;
            $this->modifiedColumns[] = PartnerPeer::WEB;
        }


        return $this;
    } // setWeb()

    /**
     * Set the value of [contact_person] column.
     * Ansprechpartner
     * @param  string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setContactPerson($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->contact_person !== $v) {
            $this->contact_person = $v;
            $this->modifiedColumns[] = PartnerPeer::CONTACT_PERSON;
        }


        return $this;
    } // setContactPerson()

    /**
     * Set the value of [contactdata] column.
     *
     * @param  string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setContactdata($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->contactdata !== $v) {
            $this->contactdata = $v;
            $this->modifiedColumns[] = PartnerPeer::CONTACTDATA;
        }


        return $this;
    } // setContactdata()

    /**
     * Set the value of [comments] column.
     *
     * @param  string $v new value
     * @return Partner The current object (for fluent API support)
     */
    public function setComments($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->comments !== $v) {
            $this->comments = $v;
            $this->modifiedColumns[] = PartnerPeer::COMMENTS;
        }


        return $this;
    } // setComments()

    /**
     * Sets the value of the [is_organization] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Partner The current object (for fluent API support)
     */
    public function setIsOrganization($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_organization !== $v) {
            $this->is_organization = $v;
            $this->modifiedColumns[] = PartnerPeer::IS_ORGANIZATION;
        }


        return $this;
    } // setIsOrganization()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Partner The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PartnerPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Partner The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PartnerPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

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
            if ($this->is_organization !== false) {
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
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->mail = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->web = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->contact_person = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->contactdata = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->comments = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->is_organization = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
            $this->created_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->updated_at = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 10; // 10 = PartnerPeer::NUM_HYDRATE_COLUMNS.

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

            $this->collTasks = null;

            $this->collCopyLocations = null;

            $this->collImagesources = null;

            $this->collTextsources = null;

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
                // timestampable behavior
                if (!$this->isColumnModified(PartnerPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PartnerPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PartnerPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
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

            if ($this->copyLocationsScheduledForDeletion !== null) {
                if (!$this->copyLocationsScheduledForDeletion->isEmpty()) {
                    foreach ($this->copyLocationsScheduledForDeletion as $copyLocation) {
                        // need to save related object because we set the relation to null
                        $copyLocation->save($con);
                    }
                    $this->copyLocationsScheduledForDeletion = null;
                }
            }

            if ($this->collCopyLocations !== null) {
                foreach ($this->collCopyLocations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->imagesourcesScheduledForDeletion !== null) {
                if (!$this->imagesourcesScheduledForDeletion->isEmpty()) {
                    foreach ($this->imagesourcesScheduledForDeletion as $imagesource) {
                        // need to save related object because we set the relation to null
                        $imagesource->save($con);
                    }
                    $this->imagesourcesScheduledForDeletion = null;
                }
            }

            if ($this->collImagesources !== null) {
                foreach ($this->collImagesources as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->textsourcesScheduledForDeletion !== null) {
                if (!$this->textsourcesScheduledForDeletion->isEmpty()) {
                    foreach ($this->textsourcesScheduledForDeletion as $textsource) {
                        // need to save related object because we set the relation to null
                        $textsource->save($con);
                    }
                    $this->textsourcesScheduledForDeletion = null;
                }
            }

            if ($this->collTextsources !== null) {
                foreach ($this->collTextsources as $referrerFK) {
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


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PartnerPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '"id"';
        }
        if ($this->isColumnModified(PartnerPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '"name"';
        }
        if ($this->isColumnModified(PartnerPeer::MAIL)) {
            $modifiedColumns[':p' . $index++]  = '"mail"';
        }
        if ($this->isColumnModified(PartnerPeer::WEB)) {
            $modifiedColumns[':p' . $index++]  = '"web"';
        }
        if ($this->isColumnModified(PartnerPeer::CONTACT_PERSON)) {
            $modifiedColumns[':p' . $index++]  = '"contact_person"';
        }
        if ($this->isColumnModified(PartnerPeer::CONTACTDATA)) {
            $modifiedColumns[':p' . $index++]  = '"contactdata"';
        }
        if ($this->isColumnModified(PartnerPeer::COMMENTS)) {
            $modifiedColumns[':p' . $index++]  = '"comments"';
        }
        if ($this->isColumnModified(PartnerPeer::IS_ORGANIZATION)) {
            $modifiedColumns[':p' . $index++]  = '"is_organization"';
        }
        if ($this->isColumnModified(PartnerPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '"created_at"';
        }
        if ($this->isColumnModified(PartnerPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '"updated_at"';
        }

        $sql = sprintf(
            'INSERT INTO "partner" (%s) VALUES (%s)',
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
                    case '"mail"':
                        $stmt->bindValue($identifier, $this->mail, PDO::PARAM_STR);
                        break;
                    case '"web"':
                        $stmt->bindValue($identifier, $this->web, PDO::PARAM_STR);
                        break;
                    case '"contact_person"':
                        $stmt->bindValue($identifier, $this->contact_person, PDO::PARAM_STR);
                        break;
                    case '"contactdata"':
                        $stmt->bindValue($identifier, $this->contactdata, PDO::PARAM_STR);
                        break;
                    case '"comments"':
                        $stmt->bindValue($identifier, $this->comments, PDO::PARAM_STR);
                        break;
                    case '"is_organization"':
                        $stmt->bindValue($identifier, $this->is_organization, PDO::PARAM_BOOL);
                        break;
                    case '"created_at"':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '"updated_at"':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
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
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
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


                if ($this->collTasks !== null) {
                    foreach ($this->collTasks as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCopyLocations !== null) {
                    foreach ($this->collCopyLocations as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collImagesources !== null) {
                    foreach ($this->collImagesources as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collTextsources !== null) {
                    foreach ($this->collTextsources as $referrerFK) {
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
                return $this->getMail();
                break;
            case 3:
                return $this->getWeb();
                break;
            case 4:
                return $this->getContactPerson();
                break;
            case 5:
                return $this->getContactdata();
                break;
            case 6:
                return $this->getComments();
                break;
            case 7:
                return $this->getIsOrganization();
                break;
            case 8:
                return $this->getCreatedAt();
                break;
            case 9:
                return $this->getUpdatedAt();
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
        if (isset($alreadyDumpedObjects['Partner'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Partner'][$this->getPrimaryKey()] = true;
        $keys = PartnerPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getMail(),
            $keys[3] => $this->getWeb(),
            $keys[4] => $this->getContactPerson(),
            $keys[5] => $this->getContactdata(),
            $keys[6] => $this->getComments(),
            $keys[7] => $this->getIsOrganization(),
            $keys[8] => $this->getCreatedAt(),
            $keys[9] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collTasks) {
                $result['Tasks'] = $this->collTasks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCopyLocations) {
                $result['CopyLocations'] = $this->collCopyLocations->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collImagesources) {
                $result['Imagesources'] = $this->collImagesources->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTextsources) {
                $result['Textsources'] = $this->collTextsources->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $this->setMail($value);
                break;
            case 3:
                $this->setWeb($value);
                break;
            case 4:
                $this->setContactPerson($value);
                break;
            case 5:
                $this->setContactdata($value);
                break;
            case 6:
                $this->setComments($value);
                break;
            case 7:
                $this->setIsOrganization($value);
                break;
            case 8:
                $this->setCreatedAt($value);
                break;
            case 9:
                $this->setUpdatedAt($value);
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
        if (array_key_exists($keys[2], $arr)) $this->setMail($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setWeb($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setContactPerson($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setContactdata($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setComments($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setIsOrganization($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setCreatedAt($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setUpdatedAt($arr[$keys[9]]);
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
        if ($this->isColumnModified(PartnerPeer::MAIL)) $criteria->add(PartnerPeer::MAIL, $this->mail);
        if ($this->isColumnModified(PartnerPeer::WEB)) $criteria->add(PartnerPeer::WEB, $this->web);
        if ($this->isColumnModified(PartnerPeer::CONTACT_PERSON)) $criteria->add(PartnerPeer::CONTACT_PERSON, $this->contact_person);
        if ($this->isColumnModified(PartnerPeer::CONTACTDATA)) $criteria->add(PartnerPeer::CONTACTDATA, $this->contactdata);
        if ($this->isColumnModified(PartnerPeer::COMMENTS)) $criteria->add(PartnerPeer::COMMENTS, $this->comments);
        if ($this->isColumnModified(PartnerPeer::IS_ORGANIZATION)) $criteria->add(PartnerPeer::IS_ORGANIZATION, $this->is_organization);
        if ($this->isColumnModified(PartnerPeer::CREATED_AT)) $criteria->add(PartnerPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PartnerPeer::UPDATED_AT)) $criteria->add(PartnerPeer::UPDATED_AT, $this->updated_at);

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
        $copyObj->setMail($this->getMail());
        $copyObj->setWeb($this->getWeb());
        $copyObj->setContactPerson($this->getContactPerson());
        $copyObj->setContactdata($this->getContactdata());
        $copyObj->setComments($this->getComments());
        $copyObj->setIsOrganization($this->getIsOrganization());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getTasks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTask($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCopyLocations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCopyLocation($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getImagesources() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addImagesource($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTextsources() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTextsource($relObj->copy($deepCopy));
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Task' == $relationName) {
            $this->initTasks();
        }
        if ('CopyLocation' == $relationName) {
            $this->initCopyLocations();
        }
        if ('Imagesource' == $relationName) {
            $this->initImagesources();
        }
        if ('Textsource' == $relationName) {
            $this->initTextsources();
        }
    }

    /**
     * Clears out the collTasks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Partner The current object (for fluent API support)
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
     * If this Partner is new, it will return
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
                    ->filterByPartner($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collTasksPartial && count($collTasks)) {
                      $this->initTasks(false);

                      foreach ($collTasks as $obj) {
                        if (false == $this->collTasks->contains($obj)) {
                          $this->collTasks->append($obj);
                        }
                      }

                      $this->collTasksPartial = true;
                    }

                    $collTasks->getInternalIterator()->rewind();

                    return $collTasks;
                }

                if ($partial && $this->collTasks) {
                    foreach ($this->collTasks as $obj) {
                        if ($obj->isNew()) {
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
     * @return Partner The current object (for fluent API support)
     */
    public function setTasks(PropelCollection $tasks, PropelPDO $con = null)
    {
        $tasksToDelete = $this->getTasks(new Criteria(), $con)->diff($tasks);


        $this->tasksScheduledForDeletion = $tasksToDelete;

        foreach ($tasksToDelete as $taskRemoved) {
            $taskRemoved->setPartner(null);
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

            if ($partial && !$criteria) {
                return count($this->getTasks());
            }
            $query = TaskQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPartner($this)
                ->count($con);
        }

        return count($this->collTasks);
    }

    /**
     * Method called to associate a Task object to this object
     * through the Task foreign key attribute.
     *
     * @param    Task $l Task
     * @return Partner The current object (for fluent API support)
     */
    public function addTask(Task $l)
    {
        if ($this->collTasks === null) {
            $this->initTasks();
            $this->collTasksPartial = true;
        }

        if (!in_array($l, $this->collTasks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddTask($l);

            if ($this->tasksScheduledForDeletion and $this->tasksScheduledForDeletion->contains($l)) {
                $this->tasksScheduledForDeletion->remove($this->tasksScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Task $task The task object to add.
     */
    protected function doAddTask($task)
    {
        $this->collTasks[]= $task;
        $task->setPartner($this);
    }

    /**
     * @param	Task $task The task object to remove.
     * @return Partner The current object (for fluent API support)
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
            $task->setPartner(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Partner is new, it will return
     * an empty collection; or if this Partner has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Partner.
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
     * Otherwise if this Partner is new, it will return
     * an empty collection; or if this Partner has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Partner.
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
     * Otherwise if this Partner is new, it will return
     * an empty collection; or if this Partner has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Partner.
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
     * Otherwise if this Partner is new, it will return
     * an empty collection; or if this Partner has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Partner.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Task[] List of Task objects
     */
    public function getTasksJoinDtaUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TaskQuery::create(null, $criteria);
        $query->joinWith('DtaUser', $join_behavior);

        return $this->getTasks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Partner is new, it will return
     * an empty collection; or if this Partner has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Partner.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Task[] List of Task objects
     */
    public function getTasksJoinCopyLocation($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TaskQuery::create(null, $criteria);
        $query->joinWith('CopyLocation', $join_behavior);

        return $this->getTasks($query, $con);
    }

    /**
     * Clears out the collCopyLocations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Partner The current object (for fluent API support)
     * @see        addCopyLocations()
     */
    public function clearCopyLocations()
    {
        $this->collCopyLocations = null; // important to set this to null since that means it is uninitialized
        $this->collCopyLocationsPartial = null;

        return $this;
    }

    /**
     * reset is the collCopyLocations collection loaded partially
     *
     * @return void
     */
    public function resetPartialCopyLocations($v = true)
    {
        $this->collCopyLocationsPartial = $v;
    }

    /**
     * Initializes the collCopyLocations collection.
     *
     * By default this just sets the collCopyLocations collection to an empty array (like clearcollCopyLocations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCopyLocations($overrideExisting = true)
    {
        if (null !== $this->collCopyLocations && !$overrideExisting) {
            return;
        }
        $this->collCopyLocations = new PropelObjectCollection();
        $this->collCopyLocations->setModel('CopyLocation');
    }

    /**
     * Gets an array of CopyLocation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Partner is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CopyLocation[] List of CopyLocation objects
     * @throws PropelException
     */
    public function getCopyLocations($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCopyLocationsPartial && !$this->isNew();
        if (null === $this->collCopyLocations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCopyLocations) {
                // return empty collection
                $this->initCopyLocations();
            } else {
                $collCopyLocations = CopyLocationQuery::create(null, $criteria)
                    ->filterByPartner($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCopyLocationsPartial && count($collCopyLocations)) {
                      $this->initCopyLocations(false);

                      foreach ($collCopyLocations as $obj) {
                        if (false == $this->collCopyLocations->contains($obj)) {
                          $this->collCopyLocations->append($obj);
                        }
                      }

                      $this->collCopyLocationsPartial = true;
                    }

                    $collCopyLocations->getInternalIterator()->rewind();

                    return $collCopyLocations;
                }

                if ($partial && $this->collCopyLocations) {
                    foreach ($this->collCopyLocations as $obj) {
                        if ($obj->isNew()) {
                            $collCopyLocations[] = $obj;
                        }
                    }
                }

                $this->collCopyLocations = $collCopyLocations;
                $this->collCopyLocationsPartial = false;
            }
        }

        return $this->collCopyLocations;
    }

    /**
     * Sets a collection of CopyLocation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $copyLocations A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Partner The current object (for fluent API support)
     */
    public function setCopyLocations(PropelCollection $copyLocations, PropelPDO $con = null)
    {
        $copyLocationsToDelete = $this->getCopyLocations(new Criteria(), $con)->diff($copyLocations);


        $this->copyLocationsScheduledForDeletion = $copyLocationsToDelete;

        foreach ($copyLocationsToDelete as $copyLocationRemoved) {
            $copyLocationRemoved->setPartner(null);
        }

        $this->collCopyLocations = null;
        foreach ($copyLocations as $copyLocation) {
            $this->addCopyLocation($copyLocation);
        }

        $this->collCopyLocations = $copyLocations;
        $this->collCopyLocationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CopyLocation objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CopyLocation objects.
     * @throws PropelException
     */
    public function countCopyLocations(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCopyLocationsPartial && !$this->isNew();
        if (null === $this->collCopyLocations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCopyLocations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCopyLocations());
            }
            $query = CopyLocationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPartner($this)
                ->count($con);
        }

        return count($this->collCopyLocations);
    }

    /**
     * Method called to associate a CopyLocation object to this object
     * through the CopyLocation foreign key attribute.
     *
     * @param    CopyLocation $l CopyLocation
     * @return Partner The current object (for fluent API support)
     */
    public function addCopyLocation(CopyLocation $l)
    {
        if ($this->collCopyLocations === null) {
            $this->initCopyLocations();
            $this->collCopyLocationsPartial = true;
        }

        if (!in_array($l, $this->collCopyLocations->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCopyLocation($l);

            if ($this->copyLocationsScheduledForDeletion and $this->copyLocationsScheduledForDeletion->contains($l)) {
                $this->copyLocationsScheduledForDeletion->remove($this->copyLocationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CopyLocation $copyLocation The copyLocation object to add.
     */
    protected function doAddCopyLocation($copyLocation)
    {
        $this->collCopyLocations[]= $copyLocation;
        $copyLocation->setPartner($this);
    }

    /**
     * @param	CopyLocation $copyLocation The copyLocation object to remove.
     * @return Partner The current object (for fluent API support)
     */
    public function removeCopyLocation($copyLocation)
    {
        if ($this->getCopyLocations()->contains($copyLocation)) {
            $this->collCopyLocations->remove($this->collCopyLocations->search($copyLocation));
            if (null === $this->copyLocationsScheduledForDeletion) {
                $this->copyLocationsScheduledForDeletion = clone $this->collCopyLocations;
                $this->copyLocationsScheduledForDeletion->clear();
            }
            $this->copyLocationsScheduledForDeletion[]= $copyLocation;
            $copyLocation->setPartner(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Partner is new, it will return
     * an empty collection; or if this Partner has previously
     * been saved, it will retrieve related CopyLocations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Partner.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CopyLocation[] List of CopyLocation objects
     */
    public function getCopyLocationsJoinPublication($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CopyLocationQuery::create(null, $criteria);
        $query->joinWith('Publication', $join_behavior);

        return $this->getCopyLocations($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Partner is new, it will return
     * an empty collection; or if this Partner has previously
     * been saved, it will retrieve related CopyLocations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Partner.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CopyLocation[] List of CopyLocation objects
     */
    public function getCopyLocationsJoinLicense($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CopyLocationQuery::create(null, $criteria);
        $query->joinWith('License', $join_behavior);

        return $this->getCopyLocations($query, $con);
    }

    /**
     * Clears out the collImagesources collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Partner The current object (for fluent API support)
     * @see        addImagesources()
     */
    public function clearImagesources()
    {
        $this->collImagesources = null; // important to set this to null since that means it is uninitialized
        $this->collImagesourcesPartial = null;

        return $this;
    }

    /**
     * reset is the collImagesources collection loaded partially
     *
     * @return void
     */
    public function resetPartialImagesources($v = true)
    {
        $this->collImagesourcesPartial = $v;
    }

    /**
     * Initializes the collImagesources collection.
     *
     * By default this just sets the collImagesources collection to an empty array (like clearcollImagesources());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initImagesources($overrideExisting = true)
    {
        if (null !== $this->collImagesources && !$overrideExisting) {
            return;
        }
        $this->collImagesources = new PropelObjectCollection();
        $this->collImagesources->setModel('Imagesource');
    }

    /**
     * Gets an array of Imagesource objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Partner is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Imagesource[] List of Imagesource objects
     * @throws PropelException
     */
    public function getImagesources($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collImagesourcesPartial && !$this->isNew();
        if (null === $this->collImagesources || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collImagesources) {
                // return empty collection
                $this->initImagesources();
            } else {
                $collImagesources = ImagesourceQuery::create(null, $criteria)
                    ->filterByPartner($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collImagesourcesPartial && count($collImagesources)) {
                      $this->initImagesources(false);

                      foreach ($collImagesources as $obj) {
                        if (false == $this->collImagesources->contains($obj)) {
                          $this->collImagesources->append($obj);
                        }
                      }

                      $this->collImagesourcesPartial = true;
                    }

                    $collImagesources->getInternalIterator()->rewind();

                    return $collImagesources;
                }

                if ($partial && $this->collImagesources) {
                    foreach ($this->collImagesources as $obj) {
                        if ($obj->isNew()) {
                            $collImagesources[] = $obj;
                        }
                    }
                }

                $this->collImagesources = $collImagesources;
                $this->collImagesourcesPartial = false;
            }
        }

        return $this->collImagesources;
    }

    /**
     * Sets a collection of Imagesource objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $imagesources A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Partner The current object (for fluent API support)
     */
    public function setImagesources(PropelCollection $imagesources, PropelPDO $con = null)
    {
        $imagesourcesToDelete = $this->getImagesources(new Criteria(), $con)->diff($imagesources);


        $this->imagesourcesScheduledForDeletion = $imagesourcesToDelete;

        foreach ($imagesourcesToDelete as $imagesourceRemoved) {
            $imagesourceRemoved->setPartner(null);
        }

        $this->collImagesources = null;
        foreach ($imagesources as $imagesource) {
            $this->addImagesource($imagesource);
        }

        $this->collImagesources = $imagesources;
        $this->collImagesourcesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Imagesource objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Imagesource objects.
     * @throws PropelException
     */
    public function countImagesources(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collImagesourcesPartial && !$this->isNew();
        if (null === $this->collImagesources || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collImagesources) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getImagesources());
            }
            $query = ImagesourceQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPartner($this)
                ->count($con);
        }

        return count($this->collImagesources);
    }

    /**
     * Method called to associate a Imagesource object to this object
     * through the Imagesource foreign key attribute.
     *
     * @param    Imagesource $l Imagesource
     * @return Partner The current object (for fluent API support)
     */
    public function addImagesource(Imagesource $l)
    {
        if ($this->collImagesources === null) {
            $this->initImagesources();
            $this->collImagesourcesPartial = true;
        }

        if (!in_array($l, $this->collImagesources->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddImagesource($l);

            if ($this->imagesourcesScheduledForDeletion and $this->imagesourcesScheduledForDeletion->contains($l)) {
                $this->imagesourcesScheduledForDeletion->remove($this->imagesourcesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Imagesource $imagesource The imagesource object to add.
     */
    protected function doAddImagesource($imagesource)
    {
        $this->collImagesources[]= $imagesource;
        $imagesource->setPartner($this);
    }

    /**
     * @param	Imagesource $imagesource The imagesource object to remove.
     * @return Partner The current object (for fluent API support)
     */
    public function removeImagesource($imagesource)
    {
        if ($this->getImagesources()->contains($imagesource)) {
            $this->collImagesources->remove($this->collImagesources->search($imagesource));
            if (null === $this->imagesourcesScheduledForDeletion) {
                $this->imagesourcesScheduledForDeletion = clone $this->collImagesources;
                $this->imagesourcesScheduledForDeletion->clear();
            }
            $this->imagesourcesScheduledForDeletion[]= $imagesource;
            $imagesource->setPartner(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Partner is new, it will return
     * an empty collection; or if this Partner has previously
     * been saved, it will retrieve related Imagesources from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Partner.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Imagesource[] List of Imagesource objects
     */
    public function getImagesourcesJoinPublication($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ImagesourceQuery::create(null, $criteria);
        $query->joinWith('Publication', $join_behavior);

        return $this->getImagesources($query, $con);
    }

    /**
     * Clears out the collTextsources collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Partner The current object (for fluent API support)
     * @see        addTextsources()
     */
    public function clearTextsources()
    {
        $this->collTextsources = null; // important to set this to null since that means it is uninitialized
        $this->collTextsourcesPartial = null;

        return $this;
    }

    /**
     * reset is the collTextsources collection loaded partially
     *
     * @return void
     */
    public function resetPartialTextsources($v = true)
    {
        $this->collTextsourcesPartial = $v;
    }

    /**
     * Initializes the collTextsources collection.
     *
     * By default this just sets the collTextsources collection to an empty array (like clearcollTextsources());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTextsources($overrideExisting = true)
    {
        if (null !== $this->collTextsources && !$overrideExisting) {
            return;
        }
        $this->collTextsources = new PropelObjectCollection();
        $this->collTextsources->setModel('Textsource');
    }

    /**
     * Gets an array of Textsource objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Partner is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Textsource[] List of Textsource objects
     * @throws PropelException
     */
    public function getTextsources($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collTextsourcesPartial && !$this->isNew();
        if (null === $this->collTextsources || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTextsources) {
                // return empty collection
                $this->initTextsources();
            } else {
                $collTextsources = TextsourceQuery::create(null, $criteria)
                    ->filterByPartner($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collTextsourcesPartial && count($collTextsources)) {
                      $this->initTextsources(false);

                      foreach ($collTextsources as $obj) {
                        if (false == $this->collTextsources->contains($obj)) {
                          $this->collTextsources->append($obj);
                        }
                      }

                      $this->collTextsourcesPartial = true;
                    }

                    $collTextsources->getInternalIterator()->rewind();

                    return $collTextsources;
                }

                if ($partial && $this->collTextsources) {
                    foreach ($this->collTextsources as $obj) {
                        if ($obj->isNew()) {
                            $collTextsources[] = $obj;
                        }
                    }
                }

                $this->collTextsources = $collTextsources;
                $this->collTextsourcesPartial = false;
            }
        }

        return $this->collTextsources;
    }

    /**
     * Sets a collection of Textsource objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $textsources A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Partner The current object (for fluent API support)
     */
    public function setTextsources(PropelCollection $textsources, PropelPDO $con = null)
    {
        $textsourcesToDelete = $this->getTextsources(new Criteria(), $con)->diff($textsources);


        $this->textsourcesScheduledForDeletion = $textsourcesToDelete;

        foreach ($textsourcesToDelete as $textsourceRemoved) {
            $textsourceRemoved->setPartner(null);
        }

        $this->collTextsources = null;
        foreach ($textsources as $textsource) {
            $this->addTextsource($textsource);
        }

        $this->collTextsources = $textsources;
        $this->collTextsourcesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Textsource objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Textsource objects.
     * @throws PropelException
     */
    public function countTextsources(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collTextsourcesPartial && !$this->isNew();
        if (null === $this->collTextsources || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTextsources) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTextsources());
            }
            $query = TextsourceQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPartner($this)
                ->count($con);
        }

        return count($this->collTextsources);
    }

    /**
     * Method called to associate a Textsource object to this object
     * through the Textsource foreign key attribute.
     *
     * @param    Textsource $l Textsource
     * @return Partner The current object (for fluent API support)
     */
    public function addTextsource(Textsource $l)
    {
        if ($this->collTextsources === null) {
            $this->initTextsources();
            $this->collTextsourcesPartial = true;
        }

        if (!in_array($l, $this->collTextsources->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddTextsource($l);

            if ($this->textsourcesScheduledForDeletion and $this->textsourcesScheduledForDeletion->contains($l)) {
                $this->textsourcesScheduledForDeletion->remove($this->textsourcesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Textsource $textsource The textsource object to add.
     */
    protected function doAddTextsource($textsource)
    {
        $this->collTextsources[]= $textsource;
        $textsource->setPartner($this);
    }

    /**
     * @param	Textsource $textsource The textsource object to remove.
     * @return Partner The current object (for fluent API support)
     */
    public function removeTextsource($textsource)
    {
        if ($this->getTextsources()->contains($textsource)) {
            $this->collTextsources->remove($this->collTextsources->search($textsource));
            if (null === $this->textsourcesScheduledForDeletion) {
                $this->textsourcesScheduledForDeletion = clone $this->collTextsources;
                $this->textsourcesScheduledForDeletion->clear();
            }
            $this->textsourcesScheduledForDeletion[]= $textsource;
            $textsource->setPartner(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Partner is new, it will return
     * an empty collection; or if this Partner has previously
     * been saved, it will retrieve related Textsources from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Partner.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Textsource[] List of Textsource objects
     */
    public function getTextsourcesJoinPublication($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TextsourceQuery::create(null, $criteria);
        $query->joinWith('Publication', $join_behavior);

        return $this->getTextsources($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Partner is new, it will return
     * an empty collection; or if this Partner has previously
     * been saved, it will retrieve related Textsources from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Partner.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Textsource[] List of Textsource objects
     */
    public function getTextsourcesJoinLicense($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TextsourceQuery::create(null, $criteria);
        $query->joinWith('License', $join_behavior);

        return $this->getTextsources($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->mail = null;
        $this->web = null;
        $this->contact_person = null;
        $this->contactdata = null;
        $this->comments = null;
        $this->is_organization = null;
        $this->created_at = null;
        $this->updated_at = null;
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
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collTasks) {
                foreach ($this->collTasks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCopyLocations) {
                foreach ($this->collCopyLocations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collImagesources) {
                foreach ($this->collImagesources as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTextsources) {
                foreach ($this->collTextsources as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collTasks instanceof PropelCollection) {
            $this->collTasks->clearIterator();
        }
        $this->collTasks = null;
        if ($this->collCopyLocations instanceof PropelCollection) {
            $this->collCopyLocations->clearIterator();
        }
        $this->collCopyLocations = null;
        if ($this->collImagesources instanceof PropelCollection) {
            $this->collImagesources->clearIterator();
        }
        $this->collImagesources = null;
        if ($this->collTextsources instanceof PropelCollection) {
            $this->collTextsources->clearIterator();
        }
        $this->collTextsources = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string The value of the 'name' column
     */
    public function __toString()
    {
        return (string) $this->getName();
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

    /**
     * @return The propel query object for retrieving the records.
     */
    public static function getRowViewQueryObject(){
        $rc = new \ReflectionClass(get_called_class());
        $queryConstructionString = $rc->getStaticPropertyValue("queryConstructionString");
        if($queryConstructionString === NULL){
            $classShortName = $rc->getShortName();
            $package = \DTA\MetadataBundle\Controller\ORMController::getPackageName($rc->getName());
            $queryClass = \DTA\MetadataBundle\Controller\ORMController::relatedClassNames($package, $classShortName)['query'];
            return new $queryClass;
        } else {
            return eval('return '.$queryConstructionString);
        }
    }


    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     Partner The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PartnerPeer::UPDATED_AT;

        return $this;
    }

}
