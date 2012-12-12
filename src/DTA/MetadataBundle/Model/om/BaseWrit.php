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
use DTA\MetadataBundle\Model\Corpus;
use DTA\MetadataBundle\Model\CorpusQuery;
use DTA\MetadataBundle\Model\Printer;
use DTA\MetadataBundle\Model\PrinterQuery;
use DTA\MetadataBundle\Model\Publication;
use DTA\MetadataBundle\Model\PublicationQuery;
use DTA\MetadataBundle\Model\Publisher;
use DTA\MetadataBundle\Model\PublisherQuery;
use DTA\MetadataBundle\Model\Relatedset;
use DTA\MetadataBundle\Model\RelatedsetQuery;
use DTA\MetadataBundle\Model\Source;
use DTA\MetadataBundle\Model\SourceQuery;
use DTA\MetadataBundle\Model\Task;
use DTA\MetadataBundle\Model\TaskQuery;
use DTA\MetadataBundle\Model\Translator;
use DTA\MetadataBundle\Model\TranslatorQuery;
use DTA\MetadataBundle\Model\Work;
use DTA\MetadataBundle\Model\WorkQuery;
use DTA\MetadataBundle\Model\Writ;
use DTA\MetadataBundle\Model\WritPeer;
use DTA\MetadataBundle\Model\WritQuery;
use DTA\MetadataBundle\Model\WritWritgroup;
use DTA\MetadataBundle\Model\WritWritgroupQuery;
use DTA\MetadataBundle\Model\Writgroup;
use DTA\MetadataBundle\Model\WritgroupQuery;

abstract class BaseWrit extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\WritPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        WritPeer
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
     * The value for the publisher_id field.
     * @var        int
     */
    protected $publisher_id;

    /**
     * The value for the printer_id field.
     * @var        int
     */
    protected $printer_id;

    /**
     * The value for the translator_id field.
     * @var        int
     */
    protected $translator_id;

    /**
     * The value for the numpages field.
     * @var        int
     */
    protected $numpages;

    /**
     * The value for the relatedset_id field.
     * @var        int
     */
    protected $relatedset_id;

    /**
     * @var        Work
     */
    protected $aWork;

    /**
     * @var        Publisher
     */
    protected $aPublisher;

    /**
     * @var        Printer
     */
    protected $aPrinter;

    /**
     * @var        Translator
     */
    protected $aTranslator;

    /**
     * @var        Publication
     */
    protected $aPublication;

    /**
     * @var        Relatedset
     */
    protected $aRelatedset;

    /**
     * @var        PropelObjectCollection|Corpus[] Collection to store aggregation of Corpus objects.
     */
    protected $collCorpuses;
    protected $collCorpusesPartial;

    /**
     * @var        PropelObjectCollection|Source[] Collection to store aggregation of Source objects.
     */
    protected $collSources;
    protected $collSourcesPartial;

    /**
     * @var        PropelObjectCollection|Task[] Collection to store aggregation of Task objects.
     */
    protected $collTasks;
    protected $collTasksPartial;

    /**
     * @var        PropelObjectCollection|WritWritgroup[] Collection to store aggregation of WritWritgroup objects.
     */
    protected $collWritWritgroups;
    protected $collWritWritgroupsPartial;

    /**
     * @var        PropelObjectCollection|Writgroup[] Collection to store aggregation of Writgroup objects.
     */
    protected $collWritgroups;

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
    protected $writgroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $corpusesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $sourcesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $tasksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $writWritgroupsScheduledForDeletion = null;

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
     * Get the [publisher_id] column value.
     *
     * @return int
     */
    public function getPublisherId()
    {
        return $this->publisher_id;
    }

    /**
     * Get the [printer_id] column value.
     *
     * @return int
     */
    public function getPrinterId()
    {
        return $this->printer_id;
    }

    /**
     * Get the [translator_id] column value.
     *
     * @return int
     */
    public function getTranslatorId()
    {
        return $this->translator_id;
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
     * Get the [relatedset_id] column value.
     *
     * @return int
     */
    public function getRelatedsetId()
    {
        return $this->relatedset_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Writ The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = WritPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [work_id] column.
     *
     * @param int $v new value
     * @return Writ The current object (for fluent API support)
     */
    public function setWorkId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->work_id !== $v) {
            $this->work_id = $v;
            $this->modifiedColumns[] = WritPeer::WORK_ID;
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
     * @return Writ The current object (for fluent API support)
     */
    public function setPublicationId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->publication_id !== $v) {
            $this->publication_id = $v;
            $this->modifiedColumns[] = WritPeer::PUBLICATION_ID;
        }

        if ($this->aPublication !== null && $this->aPublication->getId() !== $v) {
            $this->aPublication = null;
        }


        return $this;
    } // setPublicationId()

    /**
     * Set the value of [publisher_id] column.
     *
     * @param int $v new value
     * @return Writ The current object (for fluent API support)
     */
    public function setPublisherId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->publisher_id !== $v) {
            $this->publisher_id = $v;
            $this->modifiedColumns[] = WritPeer::PUBLISHER_ID;
        }

        if ($this->aPublisher !== null && $this->aPublisher->getId() !== $v) {
            $this->aPublisher = null;
        }


        return $this;
    } // setPublisherId()

    /**
     * Set the value of [printer_id] column.
     *
     * @param int $v new value
     * @return Writ The current object (for fluent API support)
     */
    public function setPrinterId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->printer_id !== $v) {
            $this->printer_id = $v;
            $this->modifiedColumns[] = WritPeer::PRINTER_ID;
        }

        if ($this->aPrinter !== null && $this->aPrinter->getId() !== $v) {
            $this->aPrinter = null;
        }


        return $this;
    } // setPrinterId()

    /**
     * Set the value of [translator_id] column.
     *
     * @param int $v new value
     * @return Writ The current object (for fluent API support)
     */
    public function setTranslatorId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->translator_id !== $v) {
            $this->translator_id = $v;
            $this->modifiedColumns[] = WritPeer::TRANSLATOR_ID;
        }

        if ($this->aTranslator !== null && $this->aTranslator->getId() !== $v) {
            $this->aTranslator = null;
        }


        return $this;
    } // setTranslatorId()

    /**
     * Set the value of [numpages] column.
     *
     * @param int $v new value
     * @return Writ The current object (for fluent API support)
     */
    public function setNumpages($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->numpages !== $v) {
            $this->numpages = $v;
            $this->modifiedColumns[] = WritPeer::NUMPAGES;
        }


        return $this;
    } // setNumpages()

    /**
     * Set the value of [relatedset_id] column.
     *
     * @param int $v new value
     * @return Writ The current object (for fluent API support)
     */
    public function setRelatedsetId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->relatedset_id !== $v) {
            $this->relatedset_id = $v;
            $this->modifiedColumns[] = WritPeer::RELATEDSET_ID;
        }

        if ($this->aRelatedset !== null && $this->aRelatedset->getId() !== $v) {
            $this->aRelatedset = null;
        }


        return $this;
    } // setRelatedsetId()

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
            $this->work_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->publication_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->publisher_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->printer_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->translator_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->numpages = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->relatedset_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 8; // 8 = WritPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Writ object", $e);
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
        if ($this->aPublisher !== null && $this->publisher_id !== $this->aPublisher->getId()) {
            $this->aPublisher = null;
        }
        if ($this->aPrinter !== null && $this->printer_id !== $this->aPrinter->getId()) {
            $this->aPrinter = null;
        }
        if ($this->aTranslator !== null && $this->translator_id !== $this->aTranslator->getId()) {
            $this->aTranslator = null;
        }
        if ($this->aRelatedset !== null && $this->relatedset_id !== $this->aRelatedset->getId()) {
            $this->aRelatedset = null;
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
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = WritPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aWork = null;
            $this->aPublisher = null;
            $this->aPrinter = null;
            $this->aTranslator = null;
            $this->aPublication = null;
            $this->aRelatedset = null;
            $this->collCorpuses = null;

            $this->collSources = null;

            $this->collTasks = null;

            $this->collWritWritgroups = null;

            $this->collWritgroups = null;
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
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = WritQuery::create()
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
            $con = Propel::getConnection(WritPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                WritPeer::addInstanceToPool($this);
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

            if ($this->aWork !== null) {
                if ($this->aWork->isModified() || $this->aWork->isNew()) {
                    $affectedRows += $this->aWork->save($con);
                }
                $this->setWork($this->aWork);
            }

            if ($this->aPublisher !== null) {
                if ($this->aPublisher->isModified() || $this->aPublisher->isNew()) {
                    $affectedRows += $this->aPublisher->save($con);
                }
                $this->setPublisher($this->aPublisher);
            }

            if ($this->aPrinter !== null) {
                if ($this->aPrinter->isModified() || $this->aPrinter->isNew()) {
                    $affectedRows += $this->aPrinter->save($con);
                }
                $this->setPrinter($this->aPrinter);
            }

            if ($this->aTranslator !== null) {
                if ($this->aTranslator->isModified() || $this->aTranslator->isNew()) {
                    $affectedRows += $this->aTranslator->save($con);
                }
                $this->setTranslator($this->aTranslator);
            }

            if ($this->aPublication !== null) {
                if ($this->aPublication->isModified() || $this->aPublication->isNew()) {
                    $affectedRows += $this->aPublication->save($con);
                }
                $this->setPublication($this->aPublication);
            }

            if ($this->aRelatedset !== null) {
                if ($this->aRelatedset->isModified() || $this->aRelatedset->isNew()) {
                    $affectedRows += $this->aRelatedset->save($con);
                }
                $this->setRelatedset($this->aRelatedset);
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

            if ($this->writgroupsScheduledForDeletion !== null) {
                if (!$this->writgroupsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->writgroupsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    WritWritgroupQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->writgroupsScheduledForDeletion = null;
                }

                foreach ($this->getWritgroups() as $writgroup) {
                    if ($writgroup->isModified()) {
                        $writgroup->save($con);
                    }
                }
            }

            if ($this->corpusesScheduledForDeletion !== null) {
                if (!$this->corpusesScheduledForDeletion->isEmpty()) {
                    foreach ($this->corpusesScheduledForDeletion as $corpus) {
                        // need to save related object because we set the relation to null
                        $corpus->save($con);
                    }
                    $this->corpusesScheduledForDeletion = null;
                }
            }

            if ($this->collCorpuses !== null) {
                foreach ($this->collCorpuses as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->sourcesScheduledForDeletion !== null) {
                if (!$this->sourcesScheduledForDeletion->isEmpty()) {
                    SourceQuery::create()
                        ->filterByPrimaryKeys($this->sourcesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->sourcesScheduledForDeletion = null;
                }
            }

            if ($this->collSources !== null) {
                foreach ($this->collSources as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
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
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->writWritgroupsScheduledForDeletion !== null) {
                if (!$this->writWritgroupsScheduledForDeletion->isEmpty()) {
                    WritWritgroupQuery::create()
                        ->filterByPrimaryKeys($this->writWritgroupsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->writWritgroupsScheduledForDeletion = null;
                }
            }

            if ($this->collWritWritgroups !== null) {
                foreach ($this->collWritWritgroups as $referrerFK) {
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

        $this->modifiedColumns[] = WritPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . WritPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(WritPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(WritPeer::WORK_ID)) {
            $modifiedColumns[':p' . $index++]  = '`work_id`';
        }
        if ($this->isColumnModified(WritPeer::PUBLICATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`publication_id`';
        }
        if ($this->isColumnModified(WritPeer::PUBLISHER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`publisher_id`';
        }
        if ($this->isColumnModified(WritPeer::PRINTER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`printer_id`';
        }
        if ($this->isColumnModified(WritPeer::TRANSLATOR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`translator_id`';
        }
        if ($this->isColumnModified(WritPeer::NUMPAGES)) {
            $modifiedColumns[':p' . $index++]  = '`numPages`';
        }
        if ($this->isColumnModified(WritPeer::RELATEDSET_ID)) {
            $modifiedColumns[':p' . $index++]  = '`relatedSet_id`';
        }

        $sql = sprintf(
            'INSERT INTO `writ` (%s) VALUES (%s)',
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
                    case '`work_id`':
                        $stmt->bindValue($identifier, $this->work_id, PDO::PARAM_INT);
                        break;
                    case '`publication_id`':
                        $stmt->bindValue($identifier, $this->publication_id, PDO::PARAM_INT);
                        break;
                    case '`publisher_id`':
                        $stmt->bindValue($identifier, $this->publisher_id, PDO::PARAM_INT);
                        break;
                    case '`printer_id`':
                        $stmt->bindValue($identifier, $this->printer_id, PDO::PARAM_INT);
                        break;
                    case '`translator_id`':
                        $stmt->bindValue($identifier, $this->translator_id, PDO::PARAM_INT);
                        break;
                    case '`numPages`':
                        $stmt->bindValue($identifier, $this->numpages, PDO::PARAM_INT);
                        break;
                    case '`relatedSet_id`':
                        $stmt->bindValue($identifier, $this->relatedset_id, PDO::PARAM_INT);
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

            if ($this->aWork !== null) {
                if (!$this->aWork->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aWork->getValidationFailures());
                }
            }

            if ($this->aPublisher !== null) {
                if (!$this->aPublisher->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPublisher->getValidationFailures());
                }
            }

            if ($this->aPrinter !== null) {
                if (!$this->aPrinter->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPrinter->getValidationFailures());
                }
            }

            if ($this->aTranslator !== null) {
                if (!$this->aTranslator->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aTranslator->getValidationFailures());
                }
            }

            if ($this->aPublication !== null) {
                if (!$this->aPublication->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPublication->getValidationFailures());
                }
            }

            if ($this->aRelatedset !== null) {
                if (!$this->aRelatedset->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aRelatedset->getValidationFailures());
                }
            }


            if (($retval = WritPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCorpuses !== null) {
                    foreach ($this->collCorpuses as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collSources !== null) {
                    foreach ($this->collSources as $referrerFK) {
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

                if ($this->collWritWritgroups !== null) {
                    foreach ($this->collWritWritgroups as $referrerFK) {
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
        $pos = WritPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getWorkId();
                break;
            case 2:
                return $this->getPublicationId();
                break;
            case 3:
                return $this->getPublisherId();
                break;
            case 4:
                return $this->getPrinterId();
                break;
            case 5:
                return $this->getTranslatorId();
                break;
            case 6:
                return $this->getNumpages();
                break;
            case 7:
                return $this->getRelatedsetId();
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
        if (isset($alreadyDumpedObjects['Writ'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Writ'][$this->getPrimaryKey()] = true;
        $keys = WritPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getWorkId(),
            $keys[2] => $this->getPublicationId(),
            $keys[3] => $this->getPublisherId(),
            $keys[4] => $this->getPrinterId(),
            $keys[5] => $this->getTranslatorId(),
            $keys[6] => $this->getNumpages(),
            $keys[7] => $this->getRelatedsetId(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aWork) {
                $result['Work'] = $this->aWork->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPublisher) {
                $result['Publisher'] = $this->aPublisher->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPrinter) {
                $result['Printer'] = $this->aPrinter->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aTranslator) {
                $result['Translator'] = $this->aTranslator->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPublication) {
                $result['Publication'] = $this->aPublication->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aRelatedset) {
                $result['Relatedset'] = $this->aRelatedset->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCorpuses) {
                $result['Corpuses'] = $this->collCorpuses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSources) {
                $result['Sources'] = $this->collSources->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTasks) {
                $result['Tasks'] = $this->collTasks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collWritWritgroups) {
                $result['WritWritgroups'] = $this->collWritWritgroups->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = WritPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setWorkId($value);
                break;
            case 2:
                $this->setPublicationId($value);
                break;
            case 3:
                $this->setPublisherId($value);
                break;
            case 4:
                $this->setPrinterId($value);
                break;
            case 5:
                $this->setTranslatorId($value);
                break;
            case 6:
                $this->setNumpages($value);
                break;
            case 7:
                $this->setRelatedsetId($value);
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
        $keys = WritPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setWorkId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPublicationId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPublisherId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setPrinterId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setTranslatorId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setNumpages($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setRelatedsetId($arr[$keys[7]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(WritPeer::DATABASE_NAME);

        if ($this->isColumnModified(WritPeer::ID)) $criteria->add(WritPeer::ID, $this->id);
        if ($this->isColumnModified(WritPeer::WORK_ID)) $criteria->add(WritPeer::WORK_ID, $this->work_id);
        if ($this->isColumnModified(WritPeer::PUBLICATION_ID)) $criteria->add(WritPeer::PUBLICATION_ID, $this->publication_id);
        if ($this->isColumnModified(WritPeer::PUBLISHER_ID)) $criteria->add(WritPeer::PUBLISHER_ID, $this->publisher_id);
        if ($this->isColumnModified(WritPeer::PRINTER_ID)) $criteria->add(WritPeer::PRINTER_ID, $this->printer_id);
        if ($this->isColumnModified(WritPeer::TRANSLATOR_ID)) $criteria->add(WritPeer::TRANSLATOR_ID, $this->translator_id);
        if ($this->isColumnModified(WritPeer::NUMPAGES)) $criteria->add(WritPeer::NUMPAGES, $this->numpages);
        if ($this->isColumnModified(WritPeer::RELATEDSET_ID)) $criteria->add(WritPeer::RELATEDSET_ID, $this->relatedset_id);

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
        $criteria = new Criteria(WritPeer::DATABASE_NAME);
        $criteria->add(WritPeer::ID, $this->id);

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
     * @param object $copyObj An object of Writ (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setWorkId($this->getWorkId());
        $copyObj->setPublicationId($this->getPublicationId());
        $copyObj->setPublisherId($this->getPublisherId());
        $copyObj->setPrinterId($this->getPrinterId());
        $copyObj->setTranslatorId($this->getTranslatorId());
        $copyObj->setNumpages($this->getNumpages());
        $copyObj->setRelatedsetId($this->getRelatedsetId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getCorpuses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCorpus($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSources() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSource($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTasks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTask($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getWritWritgroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addWritWritgroup($relObj->copy($deepCopy));
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
     * @return Writ Clone of current object.
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
     * @return WritPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new WritPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Work object.
     *
     * @param             Work $v
     * @return Writ The current object (for fluent API support)
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
            $v->addWrit($this);
        }


        return $this;
    }


    /**
     * Get the associated Work object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Work The associated Work object.
     * @throws PropelException
     */
    public function getWork(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aWork === null && ($this->work_id !== null) && $doQuery) {
            $this->aWork = WorkQuery::create()->findPk($this->work_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aWork->addWrits($this);
             */
        }

        return $this->aWork;
    }

    /**
     * Declares an association between this object and a Publisher object.
     *
     * @param             Publisher $v
     * @return Writ The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPublisher(Publisher $v = null)
    {
        if ($v === null) {
            $this->setPublisherId(NULL);
        } else {
            $this->setPublisherId($v->getId());
        }

        $this->aPublisher = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Publisher object, it will not be re-added.
        if ($v !== null) {
            $v->addWrit($this);
        }


        return $this;
    }


    /**
     * Get the associated Publisher object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Publisher The associated Publisher object.
     * @throws PropelException
     */
    public function getPublisher(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPublisher === null && ($this->publisher_id !== null) && $doQuery) {
            $this->aPublisher = PublisherQuery::create()
                ->filterByWrit($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPublisher->addWrits($this);
             */
        }

        return $this->aPublisher;
    }

    /**
     * Declares an association between this object and a Printer object.
     *
     * @param             Printer $v
     * @return Writ The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPrinter(Printer $v = null)
    {
        if ($v === null) {
            $this->setPrinterId(NULL);
        } else {
            $this->setPrinterId($v->getId());
        }

        $this->aPrinter = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Printer object, it will not be re-added.
        if ($v !== null) {
            $v->addWrit($this);
        }


        return $this;
    }


    /**
     * Get the associated Printer object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Printer The associated Printer object.
     * @throws PropelException
     */
    public function getPrinter(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPrinter === null && ($this->printer_id !== null) && $doQuery) {
            $this->aPrinter = PrinterQuery::create()
                ->filterByWrit($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPrinter->addWrits($this);
             */
        }

        return $this->aPrinter;
    }

    /**
     * Declares an association between this object and a Translator object.
     *
     * @param             Translator $v
     * @return Writ The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTranslator(Translator $v = null)
    {
        if ($v === null) {
            $this->setTranslatorId(NULL);
        } else {
            $this->setTranslatorId($v->getId());
        }

        $this->aTranslator = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Translator object, it will not be re-added.
        if ($v !== null) {
            $v->addWrit($this);
        }


        return $this;
    }


    /**
     * Get the associated Translator object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Translator The associated Translator object.
     * @throws PropelException
     */
    public function getTranslator(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aTranslator === null && ($this->translator_id !== null) && $doQuery) {
            $this->aTranslator = TranslatorQuery::create()
                ->filterByWrit($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTranslator->addWrits($this);
             */
        }

        return $this->aTranslator;
    }

    /**
     * Declares an association between this object and a Publication object.
     *
     * @param             Publication $v
     * @return Writ The current object (for fluent API support)
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
            $v->addWrit($this);
        }


        return $this;
    }


    /**
     * Get the associated Publication object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Publication The associated Publication object.
     * @throws PropelException
     */
    public function getPublication(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPublication === null && ($this->publication_id !== null) && $doQuery) {
            $this->aPublication = PublicationQuery::create()->findPk($this->publication_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPublication->addWrits($this);
             */
        }

        return $this->aPublication;
    }

    /**
     * Declares an association between this object and a Relatedset object.
     *
     * @param             Relatedset $v
     * @return Writ The current object (for fluent API support)
     * @throws PropelException
     */
    public function setRelatedset(Relatedset $v = null)
    {
        if ($v === null) {
            $this->setRelatedsetId(NULL);
        } else {
            $this->setRelatedsetId($v->getId());
        }

        $this->aRelatedset = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Relatedset object, it will not be re-added.
        if ($v !== null) {
            $v->addWrit($this);
        }


        return $this;
    }


    /**
     * Get the associated Relatedset object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Relatedset The associated Relatedset object.
     * @throws PropelException
     */
    public function getRelatedset(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aRelatedset === null && ($this->relatedset_id !== null) && $doQuery) {
            $this->aRelatedset = RelatedsetQuery::create()->findPk($this->relatedset_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aRelatedset->addWrits($this);
             */
        }

        return $this->aRelatedset;
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
        if ('Corpus' == $relationName) {
            $this->initCorpuses();
        }
        if ('Source' == $relationName) {
            $this->initSources();
        }
        if ('Task' == $relationName) {
            $this->initTasks();
        }
        if ('WritWritgroup' == $relationName) {
            $this->initWritWritgroups();
        }
    }

    /**
     * Clears out the collCorpuses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Writ The current object (for fluent API support)
     * @see        addCorpuses()
     */
    public function clearCorpuses()
    {
        $this->collCorpuses = null; // important to set this to null since that means it is uninitialized
        $this->collCorpusesPartial = null;

        return $this;
    }

    /**
     * reset is the collCorpuses collection loaded partially
     *
     * @return void
     */
    public function resetPartialCorpuses($v = true)
    {
        $this->collCorpusesPartial = $v;
    }

    /**
     * Initializes the collCorpuses collection.
     *
     * By default this just sets the collCorpuses collection to an empty array (like clearcollCorpuses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCorpuses($overrideExisting = true)
    {
        if (null !== $this->collCorpuses && !$overrideExisting) {
            return;
        }
        $this->collCorpuses = new PropelObjectCollection();
        $this->collCorpuses->setModel('Corpus');
    }

    /**
     * Gets an array of Corpus objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Writ is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Corpus[] List of Corpus objects
     * @throws PropelException
     */
    public function getCorpuses($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCorpusesPartial && !$this->isNew();
        if (null === $this->collCorpuses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCorpuses) {
                // return empty collection
                $this->initCorpuses();
            } else {
                $collCorpuses = CorpusQuery::create(null, $criteria)
                    ->filterByWrit($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCorpusesPartial && count($collCorpuses)) {
                      $this->initCorpuses(false);

                      foreach($collCorpuses as $obj) {
                        if (false == $this->collCorpuses->contains($obj)) {
                          $this->collCorpuses->append($obj);
                        }
                      }

                      $this->collCorpusesPartial = true;
                    }

                    return $collCorpuses;
                }

                if($partial && $this->collCorpuses) {
                    foreach($this->collCorpuses as $obj) {
                        if($obj->isNew()) {
                            $collCorpuses[] = $obj;
                        }
                    }
                }

                $this->collCorpuses = $collCorpuses;
                $this->collCorpusesPartial = false;
            }
        }

        return $this->collCorpuses;
    }

    /**
     * Sets a collection of Corpus objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $corpuses A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Writ The current object (for fluent API support)
     */
    public function setCorpuses(PropelCollection $corpuses, PropelPDO $con = null)
    {
        $this->corpusesScheduledForDeletion = $this->getCorpuses(new Criteria(), $con)->diff($corpuses);

        foreach ($this->corpusesScheduledForDeletion as $corpusRemoved) {
            $corpusRemoved->setWrit(null);
        }

        $this->collCorpuses = null;
        foreach ($corpuses as $corpus) {
            $this->addCorpus($corpus);
        }

        $this->collCorpuses = $corpuses;
        $this->collCorpusesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Corpus objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Corpus objects.
     * @throws PropelException
     */
    public function countCorpuses(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCorpusesPartial && !$this->isNew();
        if (null === $this->collCorpuses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCorpuses) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getCorpuses());
                }
                $query = CorpusQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByWrit($this)
                    ->count($con);
            }
        } else {
            return count($this->collCorpuses);
        }
    }

    /**
     * Method called to associate a Corpus object to this object
     * through the Corpus foreign key attribute.
     *
     * @param    Corpus $l Corpus
     * @return Writ The current object (for fluent API support)
     */
    public function addCorpus(Corpus $l)
    {
        if ($this->collCorpuses === null) {
            $this->initCorpuses();
            $this->collCorpusesPartial = true;
        }
        if (!in_array($l, $this->collCorpuses->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCorpus($l);
        }

        return $this;
    }

    /**
     * @param	Corpus $corpus The corpus object to add.
     */
    protected function doAddCorpus($corpus)
    {
        $this->collCorpuses[]= $corpus;
        $corpus->setWrit($this);
    }

    /**
     * @param	Corpus $corpus The corpus object to remove.
     * @return Writ The current object (for fluent API support)
     */
    public function removeCorpus($corpus)
    {
        if ($this->getCorpuses()->contains($corpus)) {
            $this->collCorpuses->remove($this->collCorpuses->search($corpus));
            if (null === $this->corpusesScheduledForDeletion) {
                $this->corpusesScheduledForDeletion = clone $this->collCorpuses;
                $this->corpusesScheduledForDeletion->clear();
            }
            $this->corpusesScheduledForDeletion[]= $corpus;
            $corpus->setWrit(null);
        }

        return $this;
    }

    /**
     * Clears out the collSources collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Writ The current object (for fluent API support)
     * @see        addSources()
     */
    public function clearSources()
    {
        $this->collSources = null; // important to set this to null since that means it is uninitialized
        $this->collSourcesPartial = null;

        return $this;
    }

    /**
     * reset is the collSources collection loaded partially
     *
     * @return void
     */
    public function resetPartialSources($v = true)
    {
        $this->collSourcesPartial = $v;
    }

    /**
     * Initializes the collSources collection.
     *
     * By default this just sets the collSources collection to an empty array (like clearcollSources());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSources($overrideExisting = true)
    {
        if (null !== $this->collSources && !$overrideExisting) {
            return;
        }
        $this->collSources = new PropelObjectCollection();
        $this->collSources->setModel('Source');
    }

    /**
     * Gets an array of Source objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Writ is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Source[] List of Source objects
     * @throws PropelException
     */
    public function getSources($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSourcesPartial && !$this->isNew();
        if (null === $this->collSources || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSources) {
                // return empty collection
                $this->initSources();
            } else {
                $collSources = SourceQuery::create(null, $criteria)
                    ->filterByWrit($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSourcesPartial && count($collSources)) {
                      $this->initSources(false);

                      foreach($collSources as $obj) {
                        if (false == $this->collSources->contains($obj)) {
                          $this->collSources->append($obj);
                        }
                      }

                      $this->collSourcesPartial = true;
                    }

                    return $collSources;
                }

                if($partial && $this->collSources) {
                    foreach($this->collSources as $obj) {
                        if($obj->isNew()) {
                            $collSources[] = $obj;
                        }
                    }
                }

                $this->collSources = $collSources;
                $this->collSourcesPartial = false;
            }
        }

        return $this->collSources;
    }

    /**
     * Sets a collection of Source objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $sources A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Writ The current object (for fluent API support)
     */
    public function setSources(PropelCollection $sources, PropelPDO $con = null)
    {
        $this->sourcesScheduledForDeletion = $this->getSources(new Criteria(), $con)->diff($sources);

        foreach ($this->sourcesScheduledForDeletion as $sourceRemoved) {
            $sourceRemoved->setWrit(null);
        }

        $this->collSources = null;
        foreach ($sources as $source) {
            $this->addSource($source);
        }

        $this->collSources = $sources;
        $this->collSourcesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Source objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Source objects.
     * @throws PropelException
     */
    public function countSources(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSourcesPartial && !$this->isNew();
        if (null === $this->collSources || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSources) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getSources());
                }
                $query = SourceQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByWrit($this)
                    ->count($con);
            }
        } else {
            return count($this->collSources);
        }
    }

    /**
     * Method called to associate a Source object to this object
     * through the Source foreign key attribute.
     *
     * @param    Source $l Source
     * @return Writ The current object (for fluent API support)
     */
    public function addSource(Source $l)
    {
        if ($this->collSources === null) {
            $this->initSources();
            $this->collSourcesPartial = true;
        }
        if (!in_array($l, $this->collSources->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSource($l);
        }

        return $this;
    }

    /**
     * @param	Source $source The source object to add.
     */
    protected function doAddSource($source)
    {
        $this->collSources[]= $source;
        $source->setWrit($this);
    }

    /**
     * @param	Source $source The source object to remove.
     * @return Writ The current object (for fluent API support)
     */
    public function removeSource($source)
    {
        if ($this->getSources()->contains($source)) {
            $this->collSources->remove($this->collSources->search($source));
            if (null === $this->sourcesScheduledForDeletion) {
                $this->sourcesScheduledForDeletion = clone $this->collSources;
                $this->sourcesScheduledForDeletion->clear();
            }
            $this->sourcesScheduledForDeletion[]= $source;
            $source->setWrit(null);
        }

        return $this;
    }

    /**
     * Clears out the collTasks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Writ The current object (for fluent API support)
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
     * If this Writ is new, it will return
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
                    ->filterByWrit($this)
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
     * @return Writ The current object (for fluent API support)
     */
    public function setTasks(PropelCollection $tasks, PropelPDO $con = null)
    {
        $this->tasksScheduledForDeletion = $this->getTasks(new Criteria(), $con)->diff($tasks);

        foreach ($this->tasksScheduledForDeletion as $taskRemoved) {
            $taskRemoved->setWrit(null);
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
            } else {
                if($partial && !$criteria) {
                    return count($this->getTasks());
                }
                $query = TaskQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByWrit($this)
                    ->count($con);
            }
        } else {
            return count($this->collTasks);
        }
    }

    /**
     * Method called to associate a Task object to this object
     * through the Task foreign key attribute.
     *
     * @param    Task $l Task
     * @return Writ The current object (for fluent API support)
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
        $task->setWrit($this);
    }

    /**
     * @param	Task $task The task object to remove.
     * @return Writ The current object (for fluent API support)
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
            $task->setWrit(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Writ is new, it will return
     * an empty collection; or if this Writ has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Writ.
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
     * Otherwise if this Writ is new, it will return
     * an empty collection; or if this Writ has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Writ.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Task[] List of Task objects
     */
    public function getTasksJoinWritgroup($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TaskQuery::create(null, $criteria);
        $query->joinWith('Writgroup', $join_behavior);

        return $this->getTasks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Writ is new, it will return
     * an empty collection; or if this Writ has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Writ.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Task[] List of Task objects
     */
    public function getTasksJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TaskQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getTasks($query, $con);
    }

    /**
     * Clears out the collWritWritgroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Writ The current object (for fluent API support)
     * @see        addWritWritgroups()
     */
    public function clearWritWritgroups()
    {
        $this->collWritWritgroups = null; // important to set this to null since that means it is uninitialized
        $this->collWritWritgroupsPartial = null;

        return $this;
    }

    /**
     * reset is the collWritWritgroups collection loaded partially
     *
     * @return void
     */
    public function resetPartialWritWritgroups($v = true)
    {
        $this->collWritWritgroupsPartial = $v;
    }

    /**
     * Initializes the collWritWritgroups collection.
     *
     * By default this just sets the collWritWritgroups collection to an empty array (like clearcollWritWritgroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initWritWritgroups($overrideExisting = true)
    {
        if (null !== $this->collWritWritgroups && !$overrideExisting) {
            return;
        }
        $this->collWritWritgroups = new PropelObjectCollection();
        $this->collWritWritgroups->setModel('WritWritgroup');
    }

    /**
     * Gets an array of WritWritgroup objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Writ is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|WritWritgroup[] List of WritWritgroup objects
     * @throws PropelException
     */
    public function getWritWritgroups($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collWritWritgroupsPartial && !$this->isNew();
        if (null === $this->collWritWritgroups || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collWritWritgroups) {
                // return empty collection
                $this->initWritWritgroups();
            } else {
                $collWritWritgroups = WritWritgroupQuery::create(null, $criteria)
                    ->filterByWrit($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collWritWritgroupsPartial && count($collWritWritgroups)) {
                      $this->initWritWritgroups(false);

                      foreach($collWritWritgroups as $obj) {
                        if (false == $this->collWritWritgroups->contains($obj)) {
                          $this->collWritWritgroups->append($obj);
                        }
                      }

                      $this->collWritWritgroupsPartial = true;
                    }

                    return $collWritWritgroups;
                }

                if($partial && $this->collWritWritgroups) {
                    foreach($this->collWritWritgroups as $obj) {
                        if($obj->isNew()) {
                            $collWritWritgroups[] = $obj;
                        }
                    }
                }

                $this->collWritWritgroups = $collWritWritgroups;
                $this->collWritWritgroupsPartial = false;
            }
        }

        return $this->collWritWritgroups;
    }

    /**
     * Sets a collection of WritWritgroup objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $writWritgroups A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Writ The current object (for fluent API support)
     */
    public function setWritWritgroups(PropelCollection $writWritgroups, PropelPDO $con = null)
    {
        $this->writWritgroupsScheduledForDeletion = $this->getWritWritgroups(new Criteria(), $con)->diff($writWritgroups);

        foreach ($this->writWritgroupsScheduledForDeletion as $writWritgroupRemoved) {
            $writWritgroupRemoved->setWrit(null);
        }

        $this->collWritWritgroups = null;
        foreach ($writWritgroups as $writWritgroup) {
            $this->addWritWritgroup($writWritgroup);
        }

        $this->collWritWritgroups = $writWritgroups;
        $this->collWritWritgroupsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related WritWritgroup objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related WritWritgroup objects.
     * @throws PropelException
     */
    public function countWritWritgroups(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collWritWritgroupsPartial && !$this->isNew();
        if (null === $this->collWritWritgroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collWritWritgroups) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getWritWritgroups());
                }
                $query = WritWritgroupQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByWrit($this)
                    ->count($con);
            }
        } else {
            return count($this->collWritWritgroups);
        }
    }

    /**
     * Method called to associate a WritWritgroup object to this object
     * through the WritWritgroup foreign key attribute.
     *
     * @param    WritWritgroup $l WritWritgroup
     * @return Writ The current object (for fluent API support)
     */
    public function addWritWritgroup(WritWritgroup $l)
    {
        if ($this->collWritWritgroups === null) {
            $this->initWritWritgroups();
            $this->collWritWritgroupsPartial = true;
        }
        if (!in_array($l, $this->collWritWritgroups->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddWritWritgroup($l);
        }

        return $this;
    }

    /**
     * @param	WritWritgroup $writWritgroup The writWritgroup object to add.
     */
    protected function doAddWritWritgroup($writWritgroup)
    {
        $this->collWritWritgroups[]= $writWritgroup;
        $writWritgroup->setWrit($this);
    }

    /**
     * @param	WritWritgroup $writWritgroup The writWritgroup object to remove.
     * @return Writ The current object (for fluent API support)
     */
    public function removeWritWritgroup($writWritgroup)
    {
        if ($this->getWritWritgroups()->contains($writWritgroup)) {
            $this->collWritWritgroups->remove($this->collWritWritgroups->search($writWritgroup));
            if (null === $this->writWritgroupsScheduledForDeletion) {
                $this->writWritgroupsScheduledForDeletion = clone $this->collWritWritgroups;
                $this->writWritgroupsScheduledForDeletion->clear();
            }
            $this->writWritgroupsScheduledForDeletion[]= $writWritgroup;
            $writWritgroup->setWrit(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Writ is new, it will return
     * an empty collection; or if this Writ has previously
     * been saved, it will retrieve related WritWritgroups from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Writ.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|WritWritgroup[] List of WritWritgroup objects
     */
    public function getWritWritgroupsJoinWritgroup($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WritWritgroupQuery::create(null, $criteria);
        $query->joinWith('Writgroup', $join_behavior);

        return $this->getWritWritgroups($query, $con);
    }

    /**
     * Clears out the collWritgroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Writ The current object (for fluent API support)
     * @see        addWritgroups()
     */
    public function clearWritgroups()
    {
        $this->collWritgroups = null; // important to set this to null since that means it is uninitialized
        $this->collWritgroupsPartial = null;

        return $this;
    }

    /**
     * Initializes the collWritgroups collection.
     *
     * By default this just sets the collWritgroups collection to an empty collection (like clearWritgroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initWritgroups()
    {
        $this->collWritgroups = new PropelObjectCollection();
        $this->collWritgroups->setModel('Writgroup');
    }

    /**
     * Gets a collection of Writgroup objects related by a many-to-many relationship
     * to the current object by way of the writ_writGroup cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Writ is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Writgroup[] List of Writgroup objects
     */
    public function getWritgroups($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collWritgroups || null !== $criteria) {
            if ($this->isNew() && null === $this->collWritgroups) {
                // return empty collection
                $this->initWritgroups();
            } else {
                $collWritgroups = WritgroupQuery::create(null, $criteria)
                    ->filterByWrit($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collWritgroups;
                }
                $this->collWritgroups = $collWritgroups;
            }
        }

        return $this->collWritgroups;
    }

    /**
     * Sets a collection of Writgroup objects related by a many-to-many relationship
     * to the current object by way of the writ_writGroup cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $writgroups A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Writ The current object (for fluent API support)
     */
    public function setWritgroups(PropelCollection $writgroups, PropelPDO $con = null)
    {
        $this->clearWritgroups();
        $currentWritgroups = $this->getWritgroups();

        $this->writgroupsScheduledForDeletion = $currentWritgroups->diff($writgroups);

        foreach ($writgroups as $writgroup) {
            if (!$currentWritgroups->contains($writgroup)) {
                $this->doAddWritgroup($writgroup);
            }
        }

        $this->collWritgroups = $writgroups;

        return $this;
    }

    /**
     * Gets the number of Writgroup objects related by a many-to-many relationship
     * to the current object by way of the writ_writGroup cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Writgroup objects
     */
    public function countWritgroups($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collWritgroups || null !== $criteria) {
            if ($this->isNew() && null === $this->collWritgroups) {
                return 0;
            } else {
                $query = WritgroupQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByWrit($this)
                    ->count($con);
            }
        } else {
            return count($this->collWritgroups);
        }
    }

    /**
     * Associate a Writgroup object to this object
     * through the writ_writGroup cross reference table.
     *
     * @param  Writgroup $writgroup The WritWritgroup object to relate
     * @return Writ The current object (for fluent API support)
     */
    public function addWritgroup(Writgroup $writgroup)
    {
        if ($this->collWritgroups === null) {
            $this->initWritgroups();
        }
        if (!$this->collWritgroups->contains($writgroup)) { // only add it if the **same** object is not already associated
            $this->doAddWritgroup($writgroup);

            $this->collWritgroups[]= $writgroup;
        }

        return $this;
    }

    /**
     * @param	Writgroup $writgroup The writgroup object to add.
     */
    protected function doAddWritgroup($writgroup)
    {
        $writWritgroup = new WritWritgroup();
        $writWritgroup->setWritgroup($writgroup);
        $this->addWritWritgroup($writWritgroup);
    }

    /**
     * Remove a Writgroup object to this object
     * through the writ_writGroup cross reference table.
     *
     * @param Writgroup $writgroup The WritWritgroup object to relate
     * @return Writ The current object (for fluent API support)
     */
    public function removeWritgroup(Writgroup $writgroup)
    {
        if ($this->getWritgroups()->contains($writgroup)) {
            $this->collWritgroups->remove($this->collWritgroups->search($writgroup));
            if (null === $this->writgroupsScheduledForDeletion) {
                $this->writgroupsScheduledForDeletion = clone $this->collWritgroups;
                $this->writgroupsScheduledForDeletion->clear();
            }
            $this->writgroupsScheduledForDeletion[]= $writgroup;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->work_id = null;
        $this->publication_id = null;
        $this->publisher_id = null;
        $this->printer_id = null;
        $this->translator_id = null;
        $this->numpages = null;
        $this->relatedset_id = null;
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
            if ($this->collCorpuses) {
                foreach ($this->collCorpuses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSources) {
                foreach ($this->collSources as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTasks) {
                foreach ($this->collTasks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWritWritgroups) {
                foreach ($this->collWritWritgroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWritgroups) {
                foreach ($this->collWritgroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collCorpuses instanceof PropelCollection) {
            $this->collCorpuses->clearIterator();
        }
        $this->collCorpuses = null;
        if ($this->collSources instanceof PropelCollection) {
            $this->collSources->clearIterator();
        }
        $this->collSources = null;
        if ($this->collTasks instanceof PropelCollection) {
            $this->collTasks->clearIterator();
        }
        $this->collTasks = null;
        if ($this->collWritWritgroups instanceof PropelCollection) {
            $this->collWritWritgroups->clearIterator();
        }
        $this->collWritWritgroups = null;
        if ($this->collWritgroups instanceof PropelCollection) {
            $this->collWritgroups->clearIterator();
        }
        $this->collWritgroups = null;
        $this->aWork = null;
        $this->aPublisher = null;
        $this->aPrinter = null;
        $this->aTranslator = null;
        $this->aPublication = null;
        $this->aRelatedset = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(WritPeer::DEFAULT_STRING_FORMAT);
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
