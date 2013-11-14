<?php

namespace DTA\MetadataBundle\Model\Data\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelException;
use \PropelPDO;
use DTA\MetadataBundle\Model\Classification\Titlefragmenttype;
use DTA\MetadataBundle\Model\Classification\TitlefragmenttypeQuery;
use DTA\MetadataBundle\Model\Data\Title;
use DTA\MetadataBundle\Model\Data\TitleQuery;
use DTA\MetadataBundle\Model\Data\Titlefragment;
use DTA\MetadataBundle\Model\Data\TitlefragmentPeer;
use DTA\MetadataBundle\Model\Data\TitlefragmentQuery;

abstract class BaseTitlefragment extends BaseObject implements Persistent, \DTA\MetadataBundle\Model\table_row_view\TableRowViewInterface, \DTA\MetadataBundle\Model\reconstructed_flaggable\ReconstructedFlaggableInterface
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\Data\\TitlefragmentPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        TitlefragmentPeer
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
     * The value for the title_id field.
     * @var        int
     */
    protected $title_id;

    /**
     * The value for the titlefragmenttype_id field.
     * @var        int
     */
    protected $titlefragmenttype_id;

    /**
     * The value for the sortable_rank field.
     * @var        int
     */
    protected $sortable_rank;

    /**
     * The value for the name_is_reconstructed field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $name_is_reconstructed;

    /**
     * @var        Title
     */
    protected $aTitle;

    /**
     * @var        Titlefragmenttype
     */
    protected $aTitlefragmenttype;

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

    // sortable behavior

    /**
     * Queries to be executed in the save transaction
     * @var        array
     */
    protected $sortableQueries = array();

    /**
     * The old scope value.
     * @var        int
     */
    protected $oldScope;

    // table_row_view behavior
    public static $tableRowViewCaptions = array('Id', 'Name', 'TitleId', 'TitlefragmenttypeId', 'SortableRank', 'NameIsReconstructed', );	public   $tableRowViewAccessors = array('Id'=>'Id', 'Name'=>'Name', 'TitleId'=>'TitleId', 'TitlefragmenttypeId'=>'TitlefragmenttypeId', 'SortableRank'=>'SortableRank', 'NameIsReconstructed'=>'NameIsReconstructed', );
    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->name_is_reconstructed = false;
    }

    /**
     * Initializes internal state of BaseTitlefragment object.
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
     * Get the [title_id] column value.
     *
     * @return int
     */
    public function getTitleId()
    {
        return $this->title_id;
    }

    /**
     * Get the [titlefragmenttype_id] column value.
     *
     * @return int
     */
    public function getTitlefragmenttypeId()
    {
        return $this->titlefragmenttype_id;
    }

    /**
     * Get the [sortable_rank] column value.
     *
     * @return int
     */
    public function getSortableRank()
    {
        return $this->sortable_rank;
    }

    /**
     * Get the [name_is_reconstructed] column value.
     *
     * @return boolean
     */
    public function getNameIsReconstructed()
    {
        return $this->name_is_reconstructed;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Titlefragment The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = TitlefragmentPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return Titlefragment The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = TitlefragmentPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [title_id] column.
     *
     * @param int $v new value
     * @return Titlefragment The current object (for fluent API support)
     */
    public function setTitleId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->title_id !== $v) {
            // sortable behavior
            $this->oldScope = $this->getTitleId();

            $this->title_id = $v;
            $this->modifiedColumns[] = TitlefragmentPeer::TITLE_ID;
        }

        if ($this->aTitle !== null && $this->aTitle->getId() !== $v) {
            $this->aTitle = null;
        }


        return $this;
    } // setTitleId()

    /**
     * Set the value of [titlefragmenttype_id] column.
     *
     * @param int $v new value
     * @return Titlefragment The current object (for fluent API support)
     */
    public function setTitlefragmenttypeId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->titlefragmenttype_id !== $v) {
            $this->titlefragmenttype_id = $v;
            $this->modifiedColumns[] = TitlefragmentPeer::TITLEFRAGMENTTYPE_ID;
        }

        if ($this->aTitlefragmenttype !== null && $this->aTitlefragmenttype->getId() !== $v) {
            $this->aTitlefragmenttype = null;
        }


        return $this;
    } // setTitlefragmenttypeId()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param int $v new value
     * @return Titlefragment The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = TitlefragmentPeer::SORTABLE_RANK;
        }


        return $this;
    } // setSortableRank()

    /**
     * Sets the value of the [name_is_reconstructed] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Titlefragment The current object (for fluent API support)
     */
    public function setNameIsReconstructed($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->name_is_reconstructed !== $v) {
            $this->name_is_reconstructed = $v;
            $this->modifiedColumns[] = TitlefragmentPeer::NAME_IS_RECONSTRUCTED;
        }


        return $this;
    } // setNameIsReconstructed()

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
            if ($this->name_is_reconstructed !== false) {
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

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->title_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->titlefragmenttype_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->sortable_rank = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->name_is_reconstructed = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 6; // 6 = TitlefragmentPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Titlefragment object", $e);
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

        if ($this->aTitle !== null && $this->title_id !== $this->aTitle->getId()) {
            $this->aTitle = null;
        }
        if ($this->aTitlefragmenttype !== null && $this->titlefragmenttype_id !== $this->aTitlefragmenttype->getId()) {
            $this->aTitlefragmenttype = null;
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
            $con = Propel::getConnection(TitlefragmentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = TitlefragmentPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aTitle = null;
            $this->aTitlefragmenttype = null;
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
            $con = Propel::getConnection(TitlefragmentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = TitlefragmentQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            TitlefragmentPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->getTitleId(), $con);
            TitlefragmentPeer::clearInstancePool();

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
            $con = Propel::getConnection(TitlefragmentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sortable behavior
            $this->processSortableQueries($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // sortable behavior
                if (!$this->isColumnModified(TitlefragmentPeer::RANK_COL)) {
                    $this->setSortableRank(TitlefragmentQuery::create()->getMaxRank($this->getTitleId(), $con) + 1);
                }

            } else {
                $ret = $ret && $this->preUpdate($con);
                // sortable behavior
                // if scope has changed and rank was not modified (if yes, assuming superior action)
                // insert object to the end of new scope and cleanup old one
                if ($this->isColumnModified(TitlefragmentPeer::SCOPE_COL) && !$this->isColumnModified(TitlefragmentPeer::RANK_COL)) {
                    TitlefragmentPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->oldScope, $con);
                    $this->insertAtBottom($con);
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
                TitlefragmentPeer::addInstanceToPool($this);
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

            if ($this->aTitle !== null) {
                if ($this->aTitle->isModified() || $this->aTitle->isNew()) {
                    $affectedRows += $this->aTitle->save($con);
                }
                $this->setTitle($this->aTitle);
            }

            if ($this->aTitlefragmenttype !== null) {
                if ($this->aTitlefragmenttype->isModified() || $this->aTitlefragmenttype->isNew()) {
                    $affectedRows += $this->aTitlefragmenttype->save($con);
                }
                $this->setTitlefragmenttype($this->aTitlefragmenttype);
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

        $this->modifiedColumns[] = TitlefragmentPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . TitlefragmentPeer::ID . ')');
        }
        if (null === $this->id) {
            try {
                $stmt = $con->query("SELECT nextval('titlefragment_id_seq')");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $this->id = $row[0];
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(TitlefragmentPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '"id"';
        }
        if ($this->isColumnModified(TitlefragmentPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '"name"';
        }
        if ($this->isColumnModified(TitlefragmentPeer::TITLE_ID)) {
            $modifiedColumns[':p' . $index++]  = '"title_id"';
        }
        if ($this->isColumnModified(TitlefragmentPeer::TITLEFRAGMENTTYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = '"titlefragmenttype_id"';
        }
        if ($this->isColumnModified(TitlefragmentPeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '"sortable_rank"';
        }
        if ($this->isColumnModified(TitlefragmentPeer::NAME_IS_RECONSTRUCTED)) {
            $modifiedColumns[':p' . $index++]  = '"name_is_reconstructed"';
        }

        $sql = sprintf(
            'INSERT INTO "titlefragment" (%s) VALUES (%s)',
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
                    case '"title_id"':
                        $stmt->bindValue($identifier, $this->title_id, PDO::PARAM_INT);
                        break;
                    case '"titlefragmenttype_id"':
                        $stmt->bindValue($identifier, $this->titlefragmenttype_id, PDO::PARAM_INT);
                        break;
                    case '"sortable_rank"':
                        $stmt->bindValue($identifier, $this->sortable_rank, PDO::PARAM_INT);
                        break;
                    case '"name_is_reconstructed"':
                        $stmt->bindValue($identifier, $this->name_is_reconstructed, PDO::PARAM_BOOL);
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

            if ($this->aTitle !== null) {
                if (!$this->aTitle->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aTitle->getValidationFailures());
                }
            }

            if ($this->aTitlefragmenttype !== null) {
                if (!$this->aTitlefragmenttype->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aTitlefragmenttype->getValidationFailures());
                }
            }


            if (($retval = TitlefragmentPeer::doValidate($this, $columns)) !== true) {
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
        $pos = TitlefragmentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getTitleId();
                break;
            case 3:
                return $this->getTitlefragmenttypeId();
                break;
            case 4:
                return $this->getSortableRank();
                break;
            case 5:
                return $this->getNameIsReconstructed();
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
        if (isset($alreadyDumpedObjects['Titlefragment'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Titlefragment'][$this->getPrimaryKey()] = true;
        $keys = TitlefragmentPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getTitleId(),
            $keys[3] => $this->getTitlefragmenttypeId(),
            $keys[4] => $this->getSortableRank(),
            $keys[5] => $this->getNameIsReconstructed(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aTitle) {
                $result['Title'] = $this->aTitle->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aTitlefragmenttype) {
                $result['Titlefragmenttype'] = $this->aTitlefragmenttype->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = TitlefragmentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setTitleId($value);
                break;
            case 3:
                $this->setTitlefragmenttypeId($value);
                break;
            case 4:
                $this->setSortableRank($value);
                break;
            case 5:
                $this->setNameIsReconstructed($value);
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
        $keys = TitlefragmentPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTitleId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setTitlefragmenttypeId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setSortableRank($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setNameIsReconstructed($arr[$keys[5]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(TitlefragmentPeer::DATABASE_NAME);

        if ($this->isColumnModified(TitlefragmentPeer::ID)) $criteria->add(TitlefragmentPeer::ID, $this->id);
        if ($this->isColumnModified(TitlefragmentPeer::NAME)) $criteria->add(TitlefragmentPeer::NAME, $this->name);
        if ($this->isColumnModified(TitlefragmentPeer::TITLE_ID)) $criteria->add(TitlefragmentPeer::TITLE_ID, $this->title_id);
        if ($this->isColumnModified(TitlefragmentPeer::TITLEFRAGMENTTYPE_ID)) $criteria->add(TitlefragmentPeer::TITLEFRAGMENTTYPE_ID, $this->titlefragmenttype_id);
        if ($this->isColumnModified(TitlefragmentPeer::SORTABLE_RANK)) $criteria->add(TitlefragmentPeer::SORTABLE_RANK, $this->sortable_rank);
        if ($this->isColumnModified(TitlefragmentPeer::NAME_IS_RECONSTRUCTED)) $criteria->add(TitlefragmentPeer::NAME_IS_RECONSTRUCTED, $this->name_is_reconstructed);

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
        $criteria = new Criteria(TitlefragmentPeer::DATABASE_NAME);
        $criteria->add(TitlefragmentPeer::ID, $this->id);

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
     * @param object $copyObj An object of Titlefragment (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setTitleId($this->getTitleId());
        $copyObj->setTitlefragmenttypeId($this->getTitlefragmenttypeId());
        $copyObj->setSortableRank($this->getSortableRank());
        $copyObj->setNameIsReconstructed($this->getNameIsReconstructed());

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
     * @return Titlefragment Clone of current object.
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
     * @return TitlefragmentPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new TitlefragmentPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Title object.
     *
     * @param             Title $v
     * @return Titlefragment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTitle(Title $v = null)
    {
        if ($v === null) {
            $this->setTitleId(NULL);
        } else {
            $this->setTitleId($v->getId());
        }

        $this->aTitle = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Title object, it will not be re-added.
        if ($v !== null) {
            $v->addTitlefragment($this);
        }


        return $this;
    }


    /**
     * Get the associated Title object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Title The associated Title object.
     * @throws PropelException
     */
    public function getTitle(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aTitle === null && ($this->title_id !== null) && $doQuery) {
            $this->aTitle = TitleQuery::create()->findPk($this->title_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTitle->addTitlefragments($this);
             */
        }

        return $this->aTitle;
    }

    /**
     * Declares an association between this object and a Titlefragmenttype object.
     *
     * @param             Titlefragmenttype $v
     * @return Titlefragment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTitlefragmenttype(Titlefragmenttype $v = null)
    {
        if ($v === null) {
            $this->setTitlefragmenttypeId(NULL);
        } else {
            $this->setTitlefragmenttypeId($v->getId());
        }

        $this->aTitlefragmenttype = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Titlefragmenttype object, it will not be re-added.
        if ($v !== null) {
            $v->addTitlefragment($this);
        }


        return $this;
    }


    /**
     * Get the associated Titlefragmenttype object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Titlefragmenttype The associated Titlefragmenttype object.
     * @throws PropelException
     */
    public function getTitlefragmenttype(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aTitlefragmenttype === null && ($this->titlefragmenttype_id !== null) && $doQuery) {
            $this->aTitlefragmenttype = TitlefragmenttypeQuery::create()->findPk($this->titlefragmenttype_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTitlefragmenttype->addTitlefragments($this);
             */
        }

        return $this->aTitlefragmenttype;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->title_id = null;
        $this->titlefragmenttype_id = null;
        $this->sortable_rank = null;
        $this->name_is_reconstructed = null;
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
            if ($this->aTitle instanceof Persistent) {
              $this->aTitle->clearAllReferences($deep);
            }
            if ($this->aTitlefragmenttype instanceof Persistent) {
              $this->aTitlefragmenttype->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aTitle = null;
        $this->aTitlefragmenttype = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(TitlefragmentPeer::DEFAULT_STRING_FORMAT);
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

    // sortable behavior

    /**
     * Wrap the getter for rank value
     *
     * @return    int
     */
    public function getRank()
    {
        return $this->sortable_rank;
    }

    /**
     * Wrap the setter for rank value
     *
     * @param     int
     * @return    Titlefragment
     */
    public function setRank($v)
    {
        return $this->setSortableRank($v);
    }


    /**
     * Wrap the getter for scope value
     *
     * @return    int
     */
    public function getScopeValue()
    {
        return $this->title_id;
    }

    /**
     * Wrap the setter for scope value
     *
     * @param     int
     * @return    Titlefragment
     */
    public function setScopeValue($v)
    {
        return $this->setTitleId($v);
    }

    /**
     * Check if the object is first in the list, i.e. if it has 1 for rank
     *
     * @return    boolean
     */
    public function isFirst()
    {
        return $this->getSortableRank() == 1;
    }

    /**
     * Check if the object is last in the list, i.e. if its rank is the highest rank
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    boolean
     */
    public function isLast(PropelPDO $con = null)
    {
        return $this->getSortableRank() == TitlefragmentQuery::create()->getMaxRank($this->getTitleId(), $con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Titlefragment
     */
    public function getNext(PropelPDO $con = null)
    {

        return TitlefragmentQuery::create()->findOneByRank($this->getSortableRank() + 1, $this->getTitleId(), $con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Titlefragment
     */
    public function getPrevious(PropelPDO $con = null)
    {

        return TitlefragmentQuery::create()->findOneByRank($this->getSortableRank() - 1, $this->getTitleId(), $con);
    }

    /**
     * Insert at specified rank
     * The modifications are not persisted until the object is saved.
     *
     * @param     integer    $rank rank value
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Titlefragment the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        $maxRank = TitlefragmentQuery::create()->getMaxRank($this->getTitleId(), $con);
        if ($rank < 1 || $rank > $maxRank + 1) {
            throw new PropelException('Invalid rank ' . $rank);
        }
        // move the object in the list, at the given rank
        $this->setSortableRank($rank);
        if ($rank != $maxRank + 1) {
            // Keep the list modification query for the save() transaction
            $this->sortableQueries []= array(
                'callable'  => array(self::PEER, 'shiftRank'),
                'arguments' => array(1, $rank, null, $this->getTitleId())
            );
        }

        return $this;
    }

    /**
     * Insert in the last rank
     * The modifications are not persisted until the object is saved.
     *
     * @param PropelPDO $con optional connection
     *
     * @return    Titlefragment the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        $this->setSortableRank(TitlefragmentQuery::create()->getMaxRank($this->getTitleId(), $con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    Titlefragment the current object
     */
    public function insertAtTop()
    {
        return $this->insertAtRank(1);
    }

    /**
     * Move the object to a new rank, and shifts the rank
     * Of the objects inbetween the old and new rank accordingly
     *
     * @param     integer   $newRank rank value
     * @param     PropelPDO $con optional connection
     *
     * @return    Titlefragment the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(TitlefragmentPeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > TitlefragmentQuery::create()->getMaxRank($this->getTitleId(), $con)) {
            throw new PropelException('Invalid rank ' . $newRank);
        }

        $oldRank = $this->getSortableRank();
        if ($oldRank == $newRank) {
            return $this;
        }

        $con->beginTransaction();
        try {
            // shift the objects between the old and the new rank
            $delta = ($oldRank < $newRank) ? -1 : 1;
            TitlefragmentPeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $this->getTitleId(), $con);

            // move the object to its new rank
            $this->setSortableRank($newRank);
            $this->save($con);

            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Exchange the rank of the object with the one passed as argument, and saves both objects
     *
     * @param     Titlefragment $object
     * @param     PropelPDO $con optional connection
     *
     * @return    Titlefragment the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(TitlefragmentPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $oldScope = $this->getTitleId();
            $newScope = $object->getTitleId();
            if ($oldScope != $newScope) {
                $this->setTitleId($newScope);
                $object->setTitleId($oldScope);
            }
            $oldRank = $this->getSortableRank();
            $newRank = $object->getSortableRank();
            $this->setSortableRank($newRank);
            $this->save($con);
            $object->setSortableRank($oldRank);
            $object->save($con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the previous object
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Titlefragment the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(TitlefragmentPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $prev = $this->getPrevious($con);
            $this->swapWith($prev, $con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the next object
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Titlefragment the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(TitlefragmentPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $next = $this->getNext($con);
            $this->swapWith($next, $con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object to the top of the list
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Titlefragment the current object
     */
    public function moveToTop(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }

        return $this->moveToRank(1, $con);
    }

    /**
     * Move the object to the bottom of the list
     *
     * @param     PropelPDO $con optional connection
     *
     * @return integer the old object's rank
     */
    public function moveToBottom(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return false;
        }
        if ($con === null) {
            $con = Propel::getConnection(TitlefragmentPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = TitlefragmentQuery::create()->getMaxRank($this->getTitleId(), $con);
            $res = $this->moveToRank($bottom, $con);
            $con->commit();

            return $res;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Removes the current object from the list (moves it to the null scope).
     * The modifications are not persisted until the object is saved.
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Titlefragment the current object
     */
    public function removeFromList(PropelPDO $con = null)
    {
        // check if object is already removed
        if ($this->getTitleId() === null) {
            throw new PropelException('Object is already removed (has null scope)');
        }

        // move the object to the end of null scope
        $this->setTitleId(null);
    //    $this->insertAtBottom($con);

        return $this;
    }

    /**
     * Execute queries that were saved to be run inside the save transaction
     */
    protected function processSortableQueries($con)
    {
        foreach ($this->sortableQueries as $query) {
            $query['arguments'][]= $con;
            call_user_func_array($query['callable'], $query['arguments']);
        }
        $this->sortableQueries = array();
    }

    // reconstructed_flaggable behavior
    /**
    * Returns all columns that can be flagged as reconstructed.
    */
    public function getReconstructedFlaggableColumns(){
        return array('Name', );
    }

    /**
    * Returns whether a column is flagged as reconstructed.
    * @param $column the PHP name of the column as defined in the schema
    */
    public function isReconstructedByName($column){
        return $this->getByName($column."IsReconstructed");
    }

    /**
    * Returns the marked column value, e.g. in brackets to denote that the value is reconstructed.
    * e.g. getMarkedByName('name') return '[<name>]'.
    * @param $column the PHP name of the column as defined in the schema
    */
    public function getMarkedByName($column){
        if($this->isReconstructedByName($column))
            return '[' . $this->getByName($column) . ']';
        else
            return $this->getByName($column);
    }

    /**
    * Returns all columns that are flagged as reconstructed.
    */
    public function getReconstructedFlaggedColumns(){
        $flaggableColumns = $this->getReconstructedFlaggableColumns();
        $flaggedColumns = array();
        foreach($flaggableColumns as $column){
            if($this->isReconstructedByName($column))
            $flaggedColumns[] = $column;
        }
        return $flaggedColumns;
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
