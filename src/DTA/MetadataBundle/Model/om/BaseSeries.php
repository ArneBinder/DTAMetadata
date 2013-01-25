<?php

namespace DTA\MetadataBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelException;
use \PropelPDO;
use DTA\MetadataBundle\Model\Datespecification;
use DTA\MetadataBundle\Model\DatespecificationQuery;
use DTA\MetadataBundle\Model\Place;
use DTA\MetadataBundle\Model\PlaceQuery;
use DTA\MetadataBundle\Model\Publishingcompany;
use DTA\MetadataBundle\Model\PublishingcompanyQuery;
use DTA\MetadataBundle\Model\Series;
use DTA\MetadataBundle\Model\SeriesPeer;
use DTA\MetadataBundle\Model\SeriesQuery;
use DTA\MetadataBundle\Model\Title;
use DTA\MetadataBundle\Model\TitleQuery;

abstract class BaseSeries extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\SeriesPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        SeriesPeer
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
     * The value for the volume field.
     * @var        string
     */
    protected $volume;

    /**
     * The value for the title_id field.
     * @var        int
     */
    protected $title_id;

    /**
     * The value for the publishingcompany_id field.
     * @var        int
     */
    protected $publishingcompany_id;

    /**
     * The value for the place_id field.
     * @var        int
     */
    protected $place_id;

    /**
     * The value for the datespecification_id field.
     * @var        int
     */
    protected $datespecification_id;

    /**
     * The value for the printrun field.
     * @var        string
     */
    protected $printrun;

    /**
     * The value for the printruncomment field.
     * @var        string
     */
    protected $printruncomment;

    /**
     * The value for the edition field.
     * @var        string
     */
    protected $edition;

    /**
     * The value for the numpages field.
     * @var        int
     */
    protected $numpages;

    /**
     * The value for the numpagesnormed field.
     * @var        int
     */
    protected $numpagesnormed;

    /**
     * The value for the bibliographiccitation field.
     * @var        string
     */
    protected $bibliographiccitation;

    /**
     * @var        Title
     */
    protected $aTitle;

    /**
     * @var        Publishingcompany
     */
    protected $aPublishingcompany;

    /**
     * @var        Place
     */
    protected $aPlace;

    /**
     * @var        Datespecification
     */
    protected $aDatespecification;

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
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [volume] column value.
     *
     * @return string
     */
    public function getVolume()
    {
        return $this->volume;
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
     * Get the [publishingcompany_id] column value.
     *
     * @return int
     */
    public function getPublishingcompanyId()
    {
        return $this->publishingcompany_id;
    }

    /**
     * Get the [place_id] column value.
     *
     * @return int
     */
    public function getPlaceId()
    {
        return $this->place_id;
    }

    /**
     * Get the [datespecification_id] column value.
     *
     * @return int
     */
    public function getDatespecificationId()
    {
        return $this->datespecification_id;
    }

    /**
     * Get the [printrun] column value.
     * Auflage
     * @return string
     */
    public function getPrintrun()
    {
        return $this->printrun;
    }

    /**
     * Get the [printruncomment] column value.
     *
     * @return string
     */
    public function getPrintruncomment()
    {
        return $this->printruncomment;
    }

    /**
     * Get the [edition] column value.
     *
     * @return string
     */
    public function getEdition()
    {
        return $this->edition;
    }

    /**
     * Get the [numpages] column value.
     *
     * @return int
     */
    public function getNumpages()
    {
        return $this->numpages;
    }

    /**
     * Get the [numpagesnormed] column value.
     *
     * @return int
     */
    public function getNumpagesnormed()
    {
        return $this->numpagesnormed;
    }

    /**
     * Get the [bibliographiccitation] column value.
     *
     * @return string
     */
    public function getBibliographiccitation()
    {
        return $this->bibliographiccitation;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = SeriesPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [volume] column.
     *
     * @param string $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setVolume($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->volume !== $v) {
            $this->volume = $v;
            $this->modifiedColumns[] = SeriesPeer::VOLUME;
        }


        return $this;
    } // setVolume()

    /**
     * Set the value of [title_id] column.
     *
     * @param int $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setTitleId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->title_id !== $v) {
            $this->title_id = $v;
            $this->modifiedColumns[] = SeriesPeer::TITLE_ID;
        }

        if ($this->aTitle !== null && $this->aTitle->getId() !== $v) {
            $this->aTitle = null;
        }


        return $this;
    } // setTitleId()

    /**
     * Set the value of [publishingcompany_id] column.
     *
     * @param int $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setPublishingcompanyId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->publishingcompany_id !== $v) {
            $this->publishingcompany_id = $v;
            $this->modifiedColumns[] = SeriesPeer::PUBLISHINGCOMPANY_ID;
        }

        if ($this->aPublishingcompany !== null && $this->aPublishingcompany->getId() !== $v) {
            $this->aPublishingcompany = null;
        }


        return $this;
    } // setPublishingcompanyId()

    /**
     * Set the value of [place_id] column.
     *
     * @param int $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setPlaceId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->place_id !== $v) {
            $this->place_id = $v;
            $this->modifiedColumns[] = SeriesPeer::PLACE_ID;
        }

        if ($this->aPlace !== null && $this->aPlace->getId() !== $v) {
            $this->aPlace = null;
        }


        return $this;
    } // setPlaceId()

    /**
     * Set the value of [datespecification_id] column.
     *
     * @param int $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setDatespecificationId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->datespecification_id !== $v) {
            $this->datespecification_id = $v;
            $this->modifiedColumns[] = SeriesPeer::DATESPECIFICATION_ID;
        }

        if ($this->aDatespecification !== null && $this->aDatespecification->getId() !== $v) {
            $this->aDatespecification = null;
        }


        return $this;
    } // setDatespecificationId()

    /**
     * Set the value of [printrun] column.
     * Auflage
     * @param string $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setPrintrun($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->printrun !== $v) {
            $this->printrun = $v;
            $this->modifiedColumns[] = SeriesPeer::PRINTRUN;
        }


        return $this;
    } // setPrintrun()

    /**
     * Set the value of [printruncomment] column.
     *
     * @param string $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setPrintruncomment($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->printruncomment !== $v) {
            $this->printruncomment = $v;
            $this->modifiedColumns[] = SeriesPeer::PRINTRUNCOMMENT;
        }


        return $this;
    } // setPrintruncomment()

    /**
     * Set the value of [edition] column.
     *
     * @param string $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setEdition($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->edition !== $v) {
            $this->edition = $v;
            $this->modifiedColumns[] = SeriesPeer::EDITION;
        }


        return $this;
    } // setEdition()

    /**
     * Set the value of [numpages] column.
     *
     * @param int $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setNumpages($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->numpages !== $v) {
            $this->numpages = $v;
            $this->modifiedColumns[] = SeriesPeer::NUMPAGES;
        }


        return $this;
    } // setNumpages()

    /**
     * Set the value of [numpagesnormed] column.
     *
     * @param int $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setNumpagesnormed($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->numpagesnormed !== $v) {
            $this->numpagesnormed = $v;
            $this->modifiedColumns[] = SeriesPeer::NUMPAGESNORMED;
        }


        return $this;
    } // setNumpagesnormed()

    /**
     * Set the value of [bibliographiccitation] column.
     *
     * @param string $v new value
     * @return Series The current object (for fluent API support)
     */
    public function setBibliographiccitation($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->bibliographiccitation !== $v) {
            $this->bibliographiccitation = $v;
            $this->modifiedColumns[] = SeriesPeer::BIBLIOGRAPHICCITATION;
        }


        return $this;
    } // setBibliographiccitation()

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
            $this->volume = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->title_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->publishingcompany_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->place_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->datespecification_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->printrun = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->printruncomment = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->edition = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->numpages = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->numpagesnormed = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->bibliographiccitation = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 12; // 12 = SeriesPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Series object", $e);
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
        if ($this->aPublishingcompany !== null && $this->publishingcompany_id !== $this->aPublishingcompany->getId()) {
            $this->aPublishingcompany = null;
        }
        if ($this->aPlace !== null && $this->place_id !== $this->aPlace->getId()) {
            $this->aPlace = null;
        }
        if ($this->aDatespecification !== null && $this->datespecification_id !== $this->aDatespecification->getId()) {
            $this->aDatespecification = null;
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
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = SeriesPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aTitle = null;
            $this->aPublishingcompany = null;
            $this->aPlace = null;
            $this->aDatespecification = null;
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
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = SeriesQuery::create()
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
            $con = Propel::getConnection(SeriesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                SeriesPeer::addInstanceToPool($this);
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

            if ($this->aPublishingcompany !== null) {
                if ($this->aPublishingcompany->isModified() || $this->aPublishingcompany->isNew()) {
                    $affectedRows += $this->aPublishingcompany->save($con);
                }
                $this->setPublishingcompany($this->aPublishingcompany);
            }

            if ($this->aPlace !== null) {
                if ($this->aPlace->isModified() || $this->aPlace->isNew()) {
                    $affectedRows += $this->aPlace->save($con);
                }
                $this->setPlace($this->aPlace);
            }

            if ($this->aDatespecification !== null) {
                if ($this->aDatespecification->isModified() || $this->aDatespecification->isNew()) {
                    $affectedRows += $this->aDatespecification->save($con);
                }
                $this->setDatespecification($this->aDatespecification);
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

        $this->modifiedColumns[] = SeriesPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SeriesPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SeriesPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(SeriesPeer::VOLUME)) {
            $modifiedColumns[':p' . $index++]  = '`volume`';
        }
        if ($this->isColumnModified(SeriesPeer::TITLE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`title_id`';
        }
        if ($this->isColumnModified(SeriesPeer::PUBLISHINGCOMPANY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`publishingCompany_id`';
        }
        if ($this->isColumnModified(SeriesPeer::PLACE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`place_id`';
        }
        if ($this->isColumnModified(SeriesPeer::DATESPECIFICATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`dateSpecification_id`';
        }
        if ($this->isColumnModified(SeriesPeer::PRINTRUN)) {
            $modifiedColumns[':p' . $index++]  = '`printRun`';
        }
        if ($this->isColumnModified(SeriesPeer::PRINTRUNCOMMENT)) {
            $modifiedColumns[':p' . $index++]  = '`printRunComment`';
        }
        if ($this->isColumnModified(SeriesPeer::EDITION)) {
            $modifiedColumns[':p' . $index++]  = '`edition`';
        }
        if ($this->isColumnModified(SeriesPeer::NUMPAGES)) {
            $modifiedColumns[':p' . $index++]  = '`numPages`';
        }
        if ($this->isColumnModified(SeriesPeer::NUMPAGESNORMED)) {
            $modifiedColumns[':p' . $index++]  = '`numPagesNormed`';
        }
        if ($this->isColumnModified(SeriesPeer::BIBLIOGRAPHICCITATION)) {
            $modifiedColumns[':p' . $index++]  = '`bibliographicCitation`';
        }

        $sql = sprintf(
            'INSERT INTO `series` (%s) VALUES (%s)',
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
                    case '`volume`':
                        $stmt->bindValue($identifier, $this->volume, PDO::PARAM_STR);
                        break;
                    case '`title_id`':
                        $stmt->bindValue($identifier, $this->title_id, PDO::PARAM_INT);
                        break;
                    case '`publishingCompany_id`':
                        $stmt->bindValue($identifier, $this->publishingcompany_id, PDO::PARAM_INT);
                        break;
                    case '`place_id`':
                        $stmt->bindValue($identifier, $this->place_id, PDO::PARAM_INT);
                        break;
                    case '`dateSpecification_id`':
                        $stmt->bindValue($identifier, $this->datespecification_id, PDO::PARAM_INT);
                        break;
                    case '`printRun`':
                        $stmt->bindValue($identifier, $this->printrun, PDO::PARAM_STR);
                        break;
                    case '`printRunComment`':
                        $stmt->bindValue($identifier, $this->printruncomment, PDO::PARAM_STR);
                        break;
                    case '`edition`':
                        $stmt->bindValue($identifier, $this->edition, PDO::PARAM_STR);
                        break;
                    case '`numPages`':
                        $stmt->bindValue($identifier, $this->numpages, PDO::PARAM_INT);
                        break;
                    case '`numPagesNormed`':
                        $stmt->bindValue($identifier, $this->numpagesnormed, PDO::PARAM_INT);
                        break;
                    case '`bibliographicCitation`':
                        $stmt->bindValue($identifier, $this->bibliographiccitation, PDO::PARAM_STR);
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

            if ($this->aTitle !== null) {
                if (!$this->aTitle->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aTitle->getValidationFailures());
                }
            }

            if ($this->aPublishingcompany !== null) {
                if (!$this->aPublishingcompany->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPublishingcompany->getValidationFailures());
                }
            }

            if ($this->aPlace !== null) {
                if (!$this->aPlace->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPlace->getValidationFailures());
                }
            }

            if ($this->aDatespecification !== null) {
                if (!$this->aDatespecification->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDatespecification->getValidationFailures());
                }
            }


            if (($retval = SeriesPeer::doValidate($this, $columns)) !== true) {
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
        $pos = SeriesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getVolume();
                break;
            case 2:
                return $this->getTitleId();
                break;
            case 3:
                return $this->getPublishingcompanyId();
                break;
            case 4:
                return $this->getPlaceId();
                break;
            case 5:
                return $this->getDatespecificationId();
                break;
            case 6:
                return $this->getPrintrun();
                break;
            case 7:
                return $this->getPrintruncomment();
                break;
            case 8:
                return $this->getEdition();
                break;
            case 9:
                return $this->getNumpages();
                break;
            case 10:
                return $this->getNumpagesnormed();
                break;
            case 11:
                return $this->getBibliographiccitation();
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
        if (isset($alreadyDumpedObjects['Series'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Series'][$this->getPrimaryKey()] = true;
        $keys = SeriesPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getVolume(),
            $keys[2] => $this->getTitleId(),
            $keys[3] => $this->getPublishingcompanyId(),
            $keys[4] => $this->getPlaceId(),
            $keys[5] => $this->getDatespecificationId(),
            $keys[6] => $this->getPrintrun(),
            $keys[7] => $this->getPrintruncomment(),
            $keys[8] => $this->getEdition(),
            $keys[9] => $this->getNumpages(),
            $keys[10] => $this->getNumpagesnormed(),
            $keys[11] => $this->getBibliographiccitation(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aTitle) {
                $result['Title'] = $this->aTitle->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPublishingcompany) {
                $result['Publishingcompany'] = $this->aPublishingcompany->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPlace) {
                $result['Place'] = $this->aPlace->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDatespecification) {
                $result['Datespecification'] = $this->aDatespecification->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = SeriesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setVolume($value);
                break;
            case 2:
                $this->setTitleId($value);
                break;
            case 3:
                $this->setPublishingcompanyId($value);
                break;
            case 4:
                $this->setPlaceId($value);
                break;
            case 5:
                $this->setDatespecificationId($value);
                break;
            case 6:
                $this->setPrintrun($value);
                break;
            case 7:
                $this->setPrintruncomment($value);
                break;
            case 8:
                $this->setEdition($value);
                break;
            case 9:
                $this->setNumpages($value);
                break;
            case 10:
                $this->setNumpagesnormed($value);
                break;
            case 11:
                $this->setBibliographiccitation($value);
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
        $keys = SeriesPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setVolume($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTitleId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPublishingcompanyId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setPlaceId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setDatespecificationId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setPrintrun($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setPrintruncomment($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setEdition($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setNumpages($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setNumpagesnormed($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setBibliographiccitation($arr[$keys[11]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(SeriesPeer::DATABASE_NAME);

        if ($this->isColumnModified(SeriesPeer::ID)) $criteria->add(SeriesPeer::ID, $this->id);
        if ($this->isColumnModified(SeriesPeer::VOLUME)) $criteria->add(SeriesPeer::VOLUME, $this->volume);
        if ($this->isColumnModified(SeriesPeer::TITLE_ID)) $criteria->add(SeriesPeer::TITLE_ID, $this->title_id);
        if ($this->isColumnModified(SeriesPeer::PUBLISHINGCOMPANY_ID)) $criteria->add(SeriesPeer::PUBLISHINGCOMPANY_ID, $this->publishingcompany_id);
        if ($this->isColumnModified(SeriesPeer::PLACE_ID)) $criteria->add(SeriesPeer::PLACE_ID, $this->place_id);
        if ($this->isColumnModified(SeriesPeer::DATESPECIFICATION_ID)) $criteria->add(SeriesPeer::DATESPECIFICATION_ID, $this->datespecification_id);
        if ($this->isColumnModified(SeriesPeer::PRINTRUN)) $criteria->add(SeriesPeer::PRINTRUN, $this->printrun);
        if ($this->isColumnModified(SeriesPeer::PRINTRUNCOMMENT)) $criteria->add(SeriesPeer::PRINTRUNCOMMENT, $this->printruncomment);
        if ($this->isColumnModified(SeriesPeer::EDITION)) $criteria->add(SeriesPeer::EDITION, $this->edition);
        if ($this->isColumnModified(SeriesPeer::NUMPAGES)) $criteria->add(SeriesPeer::NUMPAGES, $this->numpages);
        if ($this->isColumnModified(SeriesPeer::NUMPAGESNORMED)) $criteria->add(SeriesPeer::NUMPAGESNORMED, $this->numpagesnormed);
        if ($this->isColumnModified(SeriesPeer::BIBLIOGRAPHICCITATION)) $criteria->add(SeriesPeer::BIBLIOGRAPHICCITATION, $this->bibliographiccitation);

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
        $criteria = new Criteria(SeriesPeer::DATABASE_NAME);
        $criteria->add(SeriesPeer::ID, $this->id);

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
     * @param object $copyObj An object of Series (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setVolume($this->getVolume());
        $copyObj->setTitleId($this->getTitleId());
        $copyObj->setPublishingcompanyId($this->getPublishingcompanyId());
        $copyObj->setPlaceId($this->getPlaceId());
        $copyObj->setDatespecificationId($this->getDatespecificationId());
        $copyObj->setPrintrun($this->getPrintrun());
        $copyObj->setPrintruncomment($this->getPrintruncomment());
        $copyObj->setEdition($this->getEdition());
        $copyObj->setNumpages($this->getNumpages());
        $copyObj->setNumpagesnormed($this->getNumpagesnormed());
        $copyObj->setBibliographiccitation($this->getBibliographiccitation());

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
     * @return Series Clone of current object.
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
     * @return SeriesPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new SeriesPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Title object.
     *
     * @param             Title $v
     * @return Series The current object (for fluent API support)
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
            $v->addSeries($this);
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
                $this->aTitle->addSeries($this);
             */
        }

        return $this->aTitle;
    }

    /**
     * Declares an association between this object and a Publishingcompany object.
     *
     * @param             Publishingcompany $v
     * @return Series The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPublishingcompany(Publishingcompany $v = null)
    {
        if ($v === null) {
            $this->setPublishingcompanyId(NULL);
        } else {
            $this->setPublishingcompanyId($v->getId());
        }

        $this->aPublishingcompany = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Publishingcompany object, it will not be re-added.
        if ($v !== null) {
            $v->addSeries($this);
        }


        return $this;
    }


    /**
     * Get the associated Publishingcompany object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Publishingcompany The associated Publishingcompany object.
     * @throws PropelException
     */
    public function getPublishingcompany(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPublishingcompany === null && ($this->publishingcompany_id !== null) && $doQuery) {
            $this->aPublishingcompany = PublishingcompanyQuery::create()->findPk($this->publishingcompany_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPublishingcompany->addSeries($this);
             */
        }

        return $this->aPublishingcompany;
    }

    /**
     * Declares an association between this object and a Place object.
     *
     * @param             Place $v
     * @return Series The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPlace(Place $v = null)
    {
        if ($v === null) {
            $this->setPlaceId(NULL);
        } else {
            $this->setPlaceId($v->getId());
        }

        $this->aPlace = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Place object, it will not be re-added.
        if ($v !== null) {
            $v->addSeries($this);
        }


        return $this;
    }


    /**
     * Get the associated Place object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Place The associated Place object.
     * @throws PropelException
     */
    public function getPlace(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPlace === null && ($this->place_id !== null) && $doQuery) {
            $this->aPlace = PlaceQuery::create()->findPk($this->place_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPlace->addSeries($this);
             */
        }

        return $this->aPlace;
    }

    /**
     * Declares an association between this object and a Datespecification object.
     *
     * @param             Datespecification $v
     * @return Series The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDatespecification(Datespecification $v = null)
    {
        if ($v === null) {
            $this->setDatespecificationId(NULL);
        } else {
            $this->setDatespecificationId($v->getId());
        }

        $this->aDatespecification = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Datespecification object, it will not be re-added.
        if ($v !== null) {
            $v->addSeries($this);
        }


        return $this;
    }


    /**
     * Get the associated Datespecification object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Datespecification The associated Datespecification object.
     * @throws PropelException
     */
    public function getDatespecification(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDatespecification === null && ($this->datespecification_id !== null) && $doQuery) {
            $this->aDatespecification = DatespecificationQuery::create()->findPk($this->datespecification_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDatespecification->addSeries($this);
             */
        }

        return $this->aDatespecification;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->volume = null;
        $this->title_id = null;
        $this->publishingcompany_id = null;
        $this->place_id = null;
        $this->datespecification_id = null;
        $this->printrun = null;
        $this->printruncomment = null;
        $this->edition = null;
        $this->numpages = null;
        $this->numpagesnormed = null;
        $this->bibliographiccitation = null;
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
            if ($this->aTitle instanceof Persistent) {
              $this->aTitle->clearAllReferences($deep);
            }
            if ($this->aPublishingcompany instanceof Persistent) {
              $this->aPublishingcompany->clearAllReferences($deep);
            }
            if ($this->aPlace instanceof Persistent) {
              $this->aPlace->clearAllReferences($deep);
            }
            if ($this->aDatespecification instanceof Persistent) {
              $this->aDatespecification->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aTitle = null;
        $this->aPublishingcompany = null;
        $this->aPlace = null;
        $this->aDatespecification = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SeriesPeer::DEFAULT_STRING_FORMAT);
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
