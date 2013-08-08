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
use DTA\MetadataBundle\Model\Classification\Category;
use DTA\MetadataBundle\Model\Classification\CategoryQuery;
use DTA\MetadataBundle\Model\Classification\Genre;
use DTA\MetadataBundle\Model\Classification\GenreQuery;
use DTA\MetadataBundle\Model\Classification\Tag;
use DTA\MetadataBundle\Model\Classification\TagQuery;
use DTA\MetadataBundle\Model\Data\Datespecification;
use DTA\MetadataBundle\Model\Data\DatespecificationQuery;
use DTA\MetadataBundle\Model\Data\Language;
use DTA\MetadataBundle\Model\Data\LanguageQuery;
use DTA\MetadataBundle\Model\Data\Publication;
use DTA\MetadataBundle\Model\Data\PublicationQuery;
use DTA\MetadataBundle\Model\Data\Title;
use DTA\MetadataBundle\Model\Data\TitleQuery;
use DTA\MetadataBundle\Model\Data\Work;
use DTA\MetadataBundle\Model\Data\WorkPeer;
use DTA\MetadataBundle\Model\Data\WorkQuery;
use DTA\MetadataBundle\Model\Master\CategoryWork;
use DTA\MetadataBundle\Model\Master\CategoryWorkQuery;
use DTA\MetadataBundle\Model\Master\GenreWork;
use DTA\MetadataBundle\Model\Master\GenreWorkQuery;
use DTA\MetadataBundle\Model\Master\LanguageWork;
use DTA\MetadataBundle\Model\Master\LanguageWorkQuery;
use DTA\MetadataBundle\Model\Master\PersonWork;
use DTA\MetadataBundle\Model\Master\PersonWorkQuery;
use DTA\MetadataBundle\Model\Master\WorkTag;
use DTA\MetadataBundle\Model\Master\WorkTagQuery;

abstract class BaseWork extends BaseObject implements Persistent, \DTA\MetadataBundle\Model\table_row_view\TableRowViewInterface
{
    /**
     * Peer class name
     */
    const PEER = 'DTA\\MetadataBundle\\Model\\Data\\WorkPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        WorkPeer
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
     * The value for the title_id field.
     * @var        int
     */
    protected $title_id;

    /**
     * The value for the datespecification_id field.
     * @var        int
     */
    protected $datespecification_id;

    /**
     * The value for the doi field.
     * @var        string
     */
    protected $doi;

    /**
     * The value for the comments field.
     * @var        string
     */
    protected $comments;

    /**
     * The value for the format field.
     * @var        string
     */
    protected $format;

    /**
     * The value for the directoryname field.
     * @var        string
     */
    protected $directoryname;

    /**
     * @var        Title
     */
    protected $aTitle;

    /**
     * @var        Datespecification
     */
    protected $aDatespecification;

    /**
     * @var        PropelObjectCollection|Publication[] Collection to store aggregation of Publication objects.
     */
    protected $collPublications;
    protected $collPublicationsPartial;

    /**
     * @var        PropelObjectCollection|LanguageWork[] Collection to store aggregation of LanguageWork objects.
     */
    protected $collLanguageWorks;
    protected $collLanguageWorksPartial;

    /**
     * @var        PropelObjectCollection|GenreWork[] Collection to store aggregation of GenreWork objects.
     */
    protected $collGenreWorks;
    protected $collGenreWorksPartial;

    /**
     * @var        PropelObjectCollection|WorkTag[] Collection to store aggregation of WorkTag objects.
     */
    protected $collWorkTags;
    protected $collWorkTagsPartial;

    /**
     * @var        PropelObjectCollection|CategoryWork[] Collection to store aggregation of CategoryWork objects.
     */
    protected $collCategoryWorks;
    protected $collCategoryWorksPartial;

    /**
     * @var        PropelObjectCollection|PersonWork[] Collection to store aggregation of PersonWork objects.
     */
    protected $collPersonWorks;
    protected $collPersonWorksPartial;

    /**
     * @var        PropelObjectCollection|Language[] Collection to store aggregation of Language objects.
     */
    protected $collLanguages;

    /**
     * @var        PropelObjectCollection|Genre[] Collection to store aggregation of Genre objects.
     */
    protected $collGenres;

    /**
     * @var        PropelObjectCollection|Tag[] Collection to store aggregation of Tag objects.
     */
    protected $collTags;

    /**
     * @var        PropelObjectCollection|Category[] Collection to store aggregation of Category objects.
     */
    protected $collCategories;

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
    public static $tableRowViewCaptions = array('Titel', 'erster Autor', 'entstanden', );	public   $tableRowViewAccessors = array('Titel'=>'accessor:getTitle', 'erster Autor'=>'accessor:getFirstAuthor', 'entstanden'=>'accessor:getDatespecification', );
    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $languagesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $genresScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $tagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $categoriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $publicationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $languageWorksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $genreWorksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $workTagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $categoryWorksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $personWorksScheduledForDeletion = null;

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
     * Get the [title_id] column value.
     *
     * @return int
     */
    public function getTitleId()
    {
        return $this->title_id;
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
     * Get the [doi] column value.
     *
     * @return string
     */
    public function getDoi()
    {
        return $this->doi;
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
     * Get the [format] column value.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Get the [directoryname] column value.
     *
     * @return string
     */
    public function getDirectoryname()
    {
        return $this->directoryname;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = WorkPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [title_id] column.
     *
     * @param int $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setTitleId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->title_id !== $v) {
            $this->title_id = $v;
            $this->modifiedColumns[] = WorkPeer::TITLE_ID;
        }

        if ($this->aTitle !== null && $this->aTitle->getId() !== $v) {
            $this->aTitle = null;
        }


        return $this;
    } // setTitleId()

    /**
     * Set the value of [datespecification_id] column.
     *
     * @param int $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setDatespecificationId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->datespecification_id !== $v) {
            $this->datespecification_id = $v;
            $this->modifiedColumns[] = WorkPeer::DATESPECIFICATION_ID;
        }

        if ($this->aDatespecification !== null && $this->aDatespecification->getId() !== $v) {
            $this->aDatespecification = null;
        }


        return $this;
    } // setDatespecificationId()

    /**
     * Set the value of [doi] column.
     *
     * @param string $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setDoi($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->doi !== $v) {
            $this->doi = $v;
            $this->modifiedColumns[] = WorkPeer::DOI;
        }


        return $this;
    } // setDoi()

    /**
     * Set the value of [comments] column.
     *
     * @param string $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setComments($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->comments !== $v) {
            $this->comments = $v;
            $this->modifiedColumns[] = WorkPeer::COMMENTS;
        }


        return $this;
    } // setComments()

    /**
     * Set the value of [format] column.
     *
     * @param string $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setFormat($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->format !== $v) {
            $this->format = $v;
            $this->modifiedColumns[] = WorkPeer::FORMAT;
        }


        return $this;
    } // setFormat()

    /**
     * Set the value of [directoryname] column.
     *
     * @param string $v new value
     * @return Work The current object (for fluent API support)
     */
    public function setDirectoryname($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->directoryname !== $v) {
            $this->directoryname = $v;
            $this->modifiedColumns[] = WorkPeer::DIRECTORYNAME;
        }


        return $this;
    } // setDirectoryname()

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
            $this->title_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->datespecification_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->doi = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->comments = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->format = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->directoryname = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 7; // 7 = WorkPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Work object", $e);
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
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = WorkPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aTitle = null;
            $this->aDatespecification = null;
            $this->collPublications = null;

            $this->collLanguageWorks = null;

            $this->collGenreWorks = null;

            $this->collWorkTags = null;

            $this->collCategoryWorks = null;

            $this->collPersonWorks = null;

            $this->collLanguages = null;
            $this->collGenres = null;
            $this->collTags = null;
            $this->collCategories = null;
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
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = WorkQuery::create()
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
            $con = Propel::getConnection(WorkPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                WorkPeer::addInstanceToPool($this);
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

            if ($this->languagesScheduledForDeletion !== null) {
                if (!$this->languagesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->languagesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    LanguageWorkQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->languagesScheduledForDeletion = null;
                }

                foreach ($this->getLanguages() as $language) {
                    if ($language->isModified()) {
                        $language->save($con);
                    }
                }
            } elseif ($this->collLanguages) {
                foreach ($this->collLanguages as $language) {
                    if ($language->isModified()) {
                        $language->save($con);
                    }
                }
            }

            if ($this->genresScheduledForDeletion !== null) {
                if (!$this->genresScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->genresScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    GenreWorkQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->genresScheduledForDeletion = null;
                }

                foreach ($this->getGenres() as $genre) {
                    if ($genre->isModified()) {
                        $genre->save($con);
                    }
                }
            } elseif ($this->collGenres) {
                foreach ($this->collGenres as $genre) {
                    if ($genre->isModified()) {
                        $genre->save($con);
                    }
                }
            }

            if ($this->tagsScheduledForDeletion !== null) {
                if (!$this->tagsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->tagsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    WorkTagQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->tagsScheduledForDeletion = null;
                }

                foreach ($this->getTags() as $tag) {
                    if ($tag->isModified()) {
                        $tag->save($con);
                    }
                }
            } elseif ($this->collTags) {
                foreach ($this->collTags as $tag) {
                    if ($tag->isModified()) {
                        $tag->save($con);
                    }
                }
            }

            if ($this->categoriesScheduledForDeletion !== null) {
                if (!$this->categoriesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->categoriesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    CategoryWorkQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->categoriesScheduledForDeletion = null;
                }

                foreach ($this->getCategories() as $category) {
                    if ($category->isModified()) {
                        $category->save($con);
                    }
                }
            } elseif ($this->collCategories) {
                foreach ($this->collCategories as $category) {
                    if ($category->isModified()) {
                        $category->save($con);
                    }
                }
            }

            if ($this->publicationsScheduledForDeletion !== null) {
                if (!$this->publicationsScheduledForDeletion->isEmpty()) {
                    PublicationQuery::create()
                        ->filterByPrimaryKeys($this->publicationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
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

            if ($this->languageWorksScheduledForDeletion !== null) {
                if (!$this->languageWorksScheduledForDeletion->isEmpty()) {
                    LanguageWorkQuery::create()
                        ->filterByPrimaryKeys($this->languageWorksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->languageWorksScheduledForDeletion = null;
                }
            }

            if ($this->collLanguageWorks !== null) {
                foreach ($this->collLanguageWorks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->genreWorksScheduledForDeletion !== null) {
                if (!$this->genreWorksScheduledForDeletion->isEmpty()) {
                    GenreWorkQuery::create()
                        ->filterByPrimaryKeys($this->genreWorksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->genreWorksScheduledForDeletion = null;
                }
            }

            if ($this->collGenreWorks !== null) {
                foreach ($this->collGenreWorks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->workTagsScheduledForDeletion !== null) {
                if (!$this->workTagsScheduledForDeletion->isEmpty()) {
                    WorkTagQuery::create()
                        ->filterByPrimaryKeys($this->workTagsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->workTagsScheduledForDeletion = null;
                }
            }

            if ($this->collWorkTags !== null) {
                foreach ($this->collWorkTags as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->categoryWorksScheduledForDeletion !== null) {
                if (!$this->categoryWorksScheduledForDeletion->isEmpty()) {
                    CategoryWorkQuery::create()
                        ->filterByPrimaryKeys($this->categoryWorksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->categoryWorksScheduledForDeletion = null;
                }
            }

            if ($this->collCategoryWorks !== null) {
                foreach ($this->collCategoryWorks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->personWorksScheduledForDeletion !== null) {
                if (!$this->personWorksScheduledForDeletion->isEmpty()) {
                    PersonWorkQuery::create()
                        ->filterByPrimaryKeys($this->personWorksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->personWorksScheduledForDeletion = null;
                }
            }

            if ($this->collPersonWorks !== null) {
                foreach ($this->collPersonWorks as $referrerFK) {
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

        $this->modifiedColumns[] = WorkPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . WorkPeer::ID . ')');
        }
        if (null === $this->id) {
            try {
                $stmt = $con->query("SELECT nextval('work_id_seq')");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $this->id = $row[0];
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(WorkPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '"id"';
        }
        if ($this->isColumnModified(WorkPeer::TITLE_ID)) {
            $modifiedColumns[':p' . $index++]  = '"title_id"';
        }
        if ($this->isColumnModified(WorkPeer::DATESPECIFICATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '"datespecification_id"';
        }
        if ($this->isColumnModified(WorkPeer::DOI)) {
            $modifiedColumns[':p' . $index++]  = '"doi"';
        }
        if ($this->isColumnModified(WorkPeer::COMMENTS)) {
            $modifiedColumns[':p' . $index++]  = '"comments"';
        }
        if ($this->isColumnModified(WorkPeer::FORMAT)) {
            $modifiedColumns[':p' . $index++]  = '"format"';
        }
        if ($this->isColumnModified(WorkPeer::DIRECTORYNAME)) {
            $modifiedColumns[':p' . $index++]  = '"directoryname"';
        }

        $sql = sprintf(
            'INSERT INTO "work" (%s) VALUES (%s)',
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
                    case '"title_id"':
                        $stmt->bindValue($identifier, $this->title_id, PDO::PARAM_INT);
                        break;
                    case '"datespecification_id"':
                        $stmt->bindValue($identifier, $this->datespecification_id, PDO::PARAM_INT);
                        break;
                    case '"doi"':
                        $stmt->bindValue($identifier, $this->doi, PDO::PARAM_STR);
                        break;
                    case '"comments"':
                        $stmt->bindValue($identifier, $this->comments, PDO::PARAM_STR);
                        break;
                    case '"format"':
                        $stmt->bindValue($identifier, $this->format, PDO::PARAM_STR);
                        break;
                    case '"directoryname"':
                        $stmt->bindValue($identifier, $this->directoryname, PDO::PARAM_STR);
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

            if ($this->aDatespecification !== null) {
                if (!$this->aDatespecification->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDatespecification->getValidationFailures());
                }
            }


            if (($retval = WorkPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collPublications !== null) {
                    foreach ($this->collPublications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collLanguageWorks !== null) {
                    foreach ($this->collLanguageWorks as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collGenreWorks !== null) {
                    foreach ($this->collGenreWorks as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collWorkTags !== null) {
                    foreach ($this->collWorkTags as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCategoryWorks !== null) {
                    foreach ($this->collCategoryWorks as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPersonWorks !== null) {
                    foreach ($this->collPersonWorks as $referrerFK) {
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
        $pos = WorkPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getTitleId();
                break;
            case 2:
                return $this->getDatespecificationId();
                break;
            case 3:
                return $this->getDoi();
                break;
            case 4:
                return $this->getComments();
                break;
            case 5:
                return $this->getFormat();
                break;
            case 6:
                return $this->getDirectoryname();
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
        if (isset($alreadyDumpedObjects['Work'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Work'][$this->getPrimaryKey()] = true;
        $keys = WorkPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitleId(),
            $keys[2] => $this->getDatespecificationId(),
            $keys[3] => $this->getDoi(),
            $keys[4] => $this->getComments(),
            $keys[5] => $this->getFormat(),
            $keys[6] => $this->getDirectoryname(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aTitle) {
                $result['Title'] = $this->aTitle->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDatespecification) {
                $result['Datespecification'] = $this->aDatespecification->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPublications) {
                $result['Publications'] = $this->collPublications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLanguageWorks) {
                $result['LanguageWorks'] = $this->collLanguageWorks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collGenreWorks) {
                $result['GenreWorks'] = $this->collGenreWorks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collWorkTags) {
                $result['WorkTags'] = $this->collWorkTags->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCategoryWorks) {
                $result['CategoryWorks'] = $this->collCategoryWorks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPersonWorks) {
                $result['PersonWorks'] = $this->collPersonWorks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = WorkPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setTitleId($value);
                break;
            case 2:
                $this->setDatespecificationId($value);
                break;
            case 3:
                $this->setDoi($value);
                break;
            case 4:
                $this->setComments($value);
                break;
            case 5:
                $this->setFormat($value);
                break;
            case 6:
                $this->setDirectoryname($value);
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
        $keys = WorkPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setTitleId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDatespecificationId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDoi($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setComments($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setFormat($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setDirectoryname($arr[$keys[6]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(WorkPeer::DATABASE_NAME);

        if ($this->isColumnModified(WorkPeer::ID)) $criteria->add(WorkPeer::ID, $this->id);
        if ($this->isColumnModified(WorkPeer::TITLE_ID)) $criteria->add(WorkPeer::TITLE_ID, $this->title_id);
        if ($this->isColumnModified(WorkPeer::DATESPECIFICATION_ID)) $criteria->add(WorkPeer::DATESPECIFICATION_ID, $this->datespecification_id);
        if ($this->isColumnModified(WorkPeer::DOI)) $criteria->add(WorkPeer::DOI, $this->doi);
        if ($this->isColumnModified(WorkPeer::COMMENTS)) $criteria->add(WorkPeer::COMMENTS, $this->comments);
        if ($this->isColumnModified(WorkPeer::FORMAT)) $criteria->add(WorkPeer::FORMAT, $this->format);
        if ($this->isColumnModified(WorkPeer::DIRECTORYNAME)) $criteria->add(WorkPeer::DIRECTORYNAME, $this->directoryname);

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
        $criteria = new Criteria(WorkPeer::DATABASE_NAME);
        $criteria->add(WorkPeer::ID, $this->id);

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
     * @param object $copyObj An object of Work (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitleId($this->getTitleId());
        $copyObj->setDatespecificationId($this->getDatespecificationId());
        $copyObj->setDoi($this->getDoi());
        $copyObj->setComments($this->getComments());
        $copyObj->setFormat($this->getFormat());
        $copyObj->setDirectoryname($this->getDirectoryname());

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

            foreach ($this->getLanguageWorks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLanguageWork($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getGenreWorks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGenreWork($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getWorkTags() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addWorkTag($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCategoryWorks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCategoryWork($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPersonWorks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPersonWork($relObj->copy($deepCopy));
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
     * @return Work Clone of current object.
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
     * @return WorkPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new WorkPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Title object.
     *
     * @param             Title $v
     * @return Work The current object (for fluent API support)
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
            $v->addWork($this);
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
                $this->aTitle->addWorks($this);
             */
        }

        return $this->aTitle;
    }

    /**
     * Declares an association between this object and a Datespecification object.
     *
     * @param             Datespecification $v
     * @return Work The current object (for fluent API support)
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
            $v->addWork($this);
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
                $this->aDatespecification->addWorks($this);
             */
        }

        return $this->aDatespecification;
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
        if ('LanguageWork' == $relationName) {
            $this->initLanguageWorks();
        }
        if ('GenreWork' == $relationName) {
            $this->initGenreWorks();
        }
        if ('WorkTag' == $relationName) {
            $this->initWorkTags();
        }
        if ('CategoryWork' == $relationName) {
            $this->initCategoryWorks();
        }
        if ('PersonWork' == $relationName) {
            $this->initPersonWorks();
        }
    }

    /**
     * Clears out the collPublications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Work The current object (for fluent API support)
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
     * If this Work is new, it will return
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
                    ->filterByWork($this)
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
     * @return Work The current object (for fluent API support)
     */
    public function setPublications(PropelCollection $publications, PropelPDO $con = null)
    {
        $publicationsToDelete = $this->getPublications(new Criteria(), $con)->diff($publications);

        $this->publicationsScheduledForDeletion = unserialize(serialize($publicationsToDelete));

        foreach ($publicationsToDelete as $publicationRemoved) {
            $publicationRemoved->setWork(null);
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
                ->filterByWork($this)
                ->count($con);
        }

        return count($this->collPublications);
    }

    /**
     * Method called to associate a Publication object to this object
     * through the Publication foreign key attribute.
     *
     * @param    Publication $l Publication
     * @return Work The current object (for fluent API support)
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
        $publication->setWork($this);
    }

    /**
     * @param	Publication $publication The publication object to remove.
     * @return Work The current object (for fluent API support)
     */
    public function removePublication($publication)
    {
        if ($this->getPublications()->contains($publication)) {
            $this->collPublications->remove($this->collPublications->search($publication));
            if (null === $this->publicationsScheduledForDeletion) {
                $this->publicationsScheduledForDeletion = clone $this->collPublications;
                $this->publicationsScheduledForDeletion->clear();
            }
            $this->publicationsScheduledForDeletion[]= clone $publication;
            $publication->setWork(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
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
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
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
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinPrintrun($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Printrun', $join_behavior);

        return $this->getPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
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
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinDatespecificationRelatedByPublicationdateId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('DatespecificationRelatedByPublicationdateId', $join_behavior);

        return $this->getPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinDatespecificationRelatedByFirstpublicationdateId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('DatespecificationRelatedByFirstpublicationdateId', $join_behavior);

        return $this->getPublications($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related Publications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Publication[] List of Publication objects
     */
    public function getPublicationsJoinFont($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PublicationQuery::create(null, $criteria);
        $query->joinWith('Font', $join_behavior);

        return $this->getPublications($query, $con);
    }

    /**
     * Clears out the collLanguageWorks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Work The current object (for fluent API support)
     * @see        addLanguageWorks()
     */
    public function clearLanguageWorks()
    {
        $this->collLanguageWorks = null; // important to set this to null since that means it is uninitialized
        $this->collLanguageWorksPartial = null;

        return $this;
    }

    /**
     * reset is the collLanguageWorks collection loaded partially
     *
     * @return void
     */
    public function resetPartialLanguageWorks($v = true)
    {
        $this->collLanguageWorksPartial = $v;
    }

    /**
     * Initializes the collLanguageWorks collection.
     *
     * By default this just sets the collLanguageWorks collection to an empty array (like clearcollLanguageWorks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLanguageWorks($overrideExisting = true)
    {
        if (null !== $this->collLanguageWorks && !$overrideExisting) {
            return;
        }
        $this->collLanguageWorks = new PropelObjectCollection();
        $this->collLanguageWorks->setModel('LanguageWork');
    }

    /**
     * Gets an array of LanguageWork objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Work is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|LanguageWork[] List of LanguageWork objects
     * @throws PropelException
     */
    public function getLanguageWorks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collLanguageWorksPartial && !$this->isNew();
        if (null === $this->collLanguageWorks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLanguageWorks) {
                // return empty collection
                $this->initLanguageWorks();
            } else {
                $collLanguageWorks = LanguageWorkQuery::create(null, $criteria)
                    ->filterByWork($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collLanguageWorksPartial && count($collLanguageWorks)) {
                      $this->initLanguageWorks(false);

                      foreach($collLanguageWorks as $obj) {
                        if (false == $this->collLanguageWorks->contains($obj)) {
                          $this->collLanguageWorks->append($obj);
                        }
                      }

                      $this->collLanguageWorksPartial = true;
                    }

                    $collLanguageWorks->getInternalIterator()->rewind();
                    return $collLanguageWorks;
                }

                if($partial && $this->collLanguageWorks) {
                    foreach($this->collLanguageWorks as $obj) {
                        if($obj->isNew()) {
                            $collLanguageWorks[] = $obj;
                        }
                    }
                }

                $this->collLanguageWorks = $collLanguageWorks;
                $this->collLanguageWorksPartial = false;
            }
        }

        return $this->collLanguageWorks;
    }

    /**
     * Sets a collection of LanguageWork objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $languageWorks A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Work The current object (for fluent API support)
     */
    public function setLanguageWorks(PropelCollection $languageWorks, PropelPDO $con = null)
    {
        $languageWorksToDelete = $this->getLanguageWorks(new Criteria(), $con)->diff($languageWorks);

        $this->languageWorksScheduledForDeletion = unserialize(serialize($languageWorksToDelete));

        foreach ($languageWorksToDelete as $languageWorkRemoved) {
            $languageWorkRemoved->setWork(null);
        }

        $this->collLanguageWorks = null;
        foreach ($languageWorks as $languageWork) {
            $this->addLanguageWork($languageWork);
        }

        $this->collLanguageWorks = $languageWorks;
        $this->collLanguageWorksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related LanguageWork objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related LanguageWork objects.
     * @throws PropelException
     */
    public function countLanguageWorks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collLanguageWorksPartial && !$this->isNew();
        if (null === $this->collLanguageWorks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLanguageWorks) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getLanguageWorks());
            }
            $query = LanguageWorkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByWork($this)
                ->count($con);
        }

        return count($this->collLanguageWorks);
    }

    /**
     * Method called to associate a LanguageWork object to this object
     * through the LanguageWork foreign key attribute.
     *
     * @param    LanguageWork $l LanguageWork
     * @return Work The current object (for fluent API support)
     */
    public function addLanguageWork(LanguageWork $l)
    {
        if ($this->collLanguageWorks === null) {
            $this->initLanguageWorks();
            $this->collLanguageWorksPartial = true;
        }
        if (!in_array($l, $this->collLanguageWorks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddLanguageWork($l);
        }

        return $this;
    }

    /**
     * @param	LanguageWork $languageWork The languageWork object to add.
     */
    protected function doAddLanguageWork($languageWork)
    {
        $this->collLanguageWorks[]= $languageWork;
        $languageWork->setWork($this);
    }

    /**
     * @param	LanguageWork $languageWork The languageWork object to remove.
     * @return Work The current object (for fluent API support)
     */
    public function removeLanguageWork($languageWork)
    {
        if ($this->getLanguageWorks()->contains($languageWork)) {
            $this->collLanguageWorks->remove($this->collLanguageWorks->search($languageWork));
            if (null === $this->languageWorksScheduledForDeletion) {
                $this->languageWorksScheduledForDeletion = clone $this->collLanguageWorks;
                $this->languageWorksScheduledForDeletion->clear();
            }
            $this->languageWorksScheduledForDeletion[]= clone $languageWork;
            $languageWork->setWork(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related LanguageWorks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|LanguageWork[] List of LanguageWork objects
     */
    public function getLanguageWorksJoinLanguage($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = LanguageWorkQuery::create(null, $criteria);
        $query->joinWith('Language', $join_behavior);

        return $this->getLanguageWorks($query, $con);
    }

    /**
     * Clears out the collGenreWorks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Work The current object (for fluent API support)
     * @see        addGenreWorks()
     */
    public function clearGenreWorks()
    {
        $this->collGenreWorks = null; // important to set this to null since that means it is uninitialized
        $this->collGenreWorksPartial = null;

        return $this;
    }

    /**
     * reset is the collGenreWorks collection loaded partially
     *
     * @return void
     */
    public function resetPartialGenreWorks($v = true)
    {
        $this->collGenreWorksPartial = $v;
    }

    /**
     * Initializes the collGenreWorks collection.
     *
     * By default this just sets the collGenreWorks collection to an empty array (like clearcollGenreWorks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGenreWorks($overrideExisting = true)
    {
        if (null !== $this->collGenreWorks && !$overrideExisting) {
            return;
        }
        $this->collGenreWorks = new PropelObjectCollection();
        $this->collGenreWorks->setModel('GenreWork');
    }

    /**
     * Gets an array of GenreWork objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Work is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|GenreWork[] List of GenreWork objects
     * @throws PropelException
     */
    public function getGenreWorks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collGenreWorksPartial && !$this->isNew();
        if (null === $this->collGenreWorks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGenreWorks) {
                // return empty collection
                $this->initGenreWorks();
            } else {
                $collGenreWorks = GenreWorkQuery::create(null, $criteria)
                    ->filterByWork($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collGenreWorksPartial && count($collGenreWorks)) {
                      $this->initGenreWorks(false);

                      foreach($collGenreWorks as $obj) {
                        if (false == $this->collGenreWorks->contains($obj)) {
                          $this->collGenreWorks->append($obj);
                        }
                      }

                      $this->collGenreWorksPartial = true;
                    }

                    $collGenreWorks->getInternalIterator()->rewind();
                    return $collGenreWorks;
                }

                if($partial && $this->collGenreWorks) {
                    foreach($this->collGenreWorks as $obj) {
                        if($obj->isNew()) {
                            $collGenreWorks[] = $obj;
                        }
                    }
                }

                $this->collGenreWorks = $collGenreWorks;
                $this->collGenreWorksPartial = false;
            }
        }

        return $this->collGenreWorks;
    }

    /**
     * Sets a collection of GenreWork objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $genreWorks A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Work The current object (for fluent API support)
     */
    public function setGenreWorks(PropelCollection $genreWorks, PropelPDO $con = null)
    {
        $genreWorksToDelete = $this->getGenreWorks(new Criteria(), $con)->diff($genreWorks);

        $this->genreWorksScheduledForDeletion = unserialize(serialize($genreWorksToDelete));

        foreach ($genreWorksToDelete as $genreWorkRemoved) {
            $genreWorkRemoved->setWork(null);
        }

        $this->collGenreWorks = null;
        foreach ($genreWorks as $genreWork) {
            $this->addGenreWork($genreWork);
        }

        $this->collGenreWorks = $genreWorks;
        $this->collGenreWorksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related GenreWork objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related GenreWork objects.
     * @throws PropelException
     */
    public function countGenreWorks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collGenreWorksPartial && !$this->isNew();
        if (null === $this->collGenreWorks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGenreWorks) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getGenreWorks());
            }
            $query = GenreWorkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByWork($this)
                ->count($con);
        }

        return count($this->collGenreWorks);
    }

    /**
     * Method called to associate a GenreWork object to this object
     * through the GenreWork foreign key attribute.
     *
     * @param    GenreWork $l GenreWork
     * @return Work The current object (for fluent API support)
     */
    public function addGenreWork(GenreWork $l)
    {
        if ($this->collGenreWorks === null) {
            $this->initGenreWorks();
            $this->collGenreWorksPartial = true;
        }
        if (!in_array($l, $this->collGenreWorks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddGenreWork($l);
        }

        return $this;
    }

    /**
     * @param	GenreWork $genreWork The genreWork object to add.
     */
    protected function doAddGenreWork($genreWork)
    {
        $this->collGenreWorks[]= $genreWork;
        $genreWork->setWork($this);
    }

    /**
     * @param	GenreWork $genreWork The genreWork object to remove.
     * @return Work The current object (for fluent API support)
     */
    public function removeGenreWork($genreWork)
    {
        if ($this->getGenreWorks()->contains($genreWork)) {
            $this->collGenreWorks->remove($this->collGenreWorks->search($genreWork));
            if (null === $this->genreWorksScheduledForDeletion) {
                $this->genreWorksScheduledForDeletion = clone $this->collGenreWorks;
                $this->genreWorksScheduledForDeletion->clear();
            }
            $this->genreWorksScheduledForDeletion[]= clone $genreWork;
            $genreWork->setWork(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related GenreWorks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|GenreWork[] List of GenreWork objects
     */
    public function getGenreWorksJoinGenre($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = GenreWorkQuery::create(null, $criteria);
        $query->joinWith('Genre', $join_behavior);

        return $this->getGenreWorks($query, $con);
    }

    /**
     * Clears out the collWorkTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Work The current object (for fluent API support)
     * @see        addWorkTags()
     */
    public function clearWorkTags()
    {
        $this->collWorkTags = null; // important to set this to null since that means it is uninitialized
        $this->collWorkTagsPartial = null;

        return $this;
    }

    /**
     * reset is the collWorkTags collection loaded partially
     *
     * @return void
     */
    public function resetPartialWorkTags($v = true)
    {
        $this->collWorkTagsPartial = $v;
    }

    /**
     * Initializes the collWorkTags collection.
     *
     * By default this just sets the collWorkTags collection to an empty array (like clearcollWorkTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initWorkTags($overrideExisting = true)
    {
        if (null !== $this->collWorkTags && !$overrideExisting) {
            return;
        }
        $this->collWorkTags = new PropelObjectCollection();
        $this->collWorkTags->setModel('WorkTag');
    }

    /**
     * Gets an array of WorkTag objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Work is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|WorkTag[] List of WorkTag objects
     * @throws PropelException
     */
    public function getWorkTags($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collWorkTagsPartial && !$this->isNew();
        if (null === $this->collWorkTags || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collWorkTags) {
                // return empty collection
                $this->initWorkTags();
            } else {
                $collWorkTags = WorkTagQuery::create(null, $criteria)
                    ->filterByWork($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collWorkTagsPartial && count($collWorkTags)) {
                      $this->initWorkTags(false);

                      foreach($collWorkTags as $obj) {
                        if (false == $this->collWorkTags->contains($obj)) {
                          $this->collWorkTags->append($obj);
                        }
                      }

                      $this->collWorkTagsPartial = true;
                    }

                    $collWorkTags->getInternalIterator()->rewind();
                    return $collWorkTags;
                }

                if($partial && $this->collWorkTags) {
                    foreach($this->collWorkTags as $obj) {
                        if($obj->isNew()) {
                            $collWorkTags[] = $obj;
                        }
                    }
                }

                $this->collWorkTags = $collWorkTags;
                $this->collWorkTagsPartial = false;
            }
        }

        return $this->collWorkTags;
    }

    /**
     * Sets a collection of WorkTag objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $workTags A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Work The current object (for fluent API support)
     */
    public function setWorkTags(PropelCollection $workTags, PropelPDO $con = null)
    {
        $workTagsToDelete = $this->getWorkTags(new Criteria(), $con)->diff($workTags);

        $this->workTagsScheduledForDeletion = unserialize(serialize($workTagsToDelete));

        foreach ($workTagsToDelete as $workTagRemoved) {
            $workTagRemoved->setWork(null);
        }

        $this->collWorkTags = null;
        foreach ($workTags as $workTag) {
            $this->addWorkTag($workTag);
        }

        $this->collWorkTags = $workTags;
        $this->collWorkTagsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related WorkTag objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related WorkTag objects.
     * @throws PropelException
     */
    public function countWorkTags(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collWorkTagsPartial && !$this->isNew();
        if (null === $this->collWorkTags || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collWorkTags) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getWorkTags());
            }
            $query = WorkTagQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByWork($this)
                ->count($con);
        }

        return count($this->collWorkTags);
    }

    /**
     * Method called to associate a WorkTag object to this object
     * through the WorkTag foreign key attribute.
     *
     * @param    WorkTag $l WorkTag
     * @return Work The current object (for fluent API support)
     */
    public function addWorkTag(WorkTag $l)
    {
        if ($this->collWorkTags === null) {
            $this->initWorkTags();
            $this->collWorkTagsPartial = true;
        }
        if (!in_array($l, $this->collWorkTags->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddWorkTag($l);
        }

        return $this;
    }

    /**
     * @param	WorkTag $workTag The workTag object to add.
     */
    protected function doAddWorkTag($workTag)
    {
        $this->collWorkTags[]= $workTag;
        $workTag->setWork($this);
    }

    /**
     * @param	WorkTag $workTag The workTag object to remove.
     * @return Work The current object (for fluent API support)
     */
    public function removeWorkTag($workTag)
    {
        if ($this->getWorkTags()->contains($workTag)) {
            $this->collWorkTags->remove($this->collWorkTags->search($workTag));
            if (null === $this->workTagsScheduledForDeletion) {
                $this->workTagsScheduledForDeletion = clone $this->collWorkTags;
                $this->workTagsScheduledForDeletion->clear();
            }
            $this->workTagsScheduledForDeletion[]= clone $workTag;
            $workTag->setWork(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related WorkTags from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|WorkTag[] List of WorkTag objects
     */
    public function getWorkTagsJoinTag($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = WorkTagQuery::create(null, $criteria);
        $query->joinWith('Tag', $join_behavior);

        return $this->getWorkTags($query, $con);
    }

    /**
     * Clears out the collCategoryWorks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Work The current object (for fluent API support)
     * @see        addCategoryWorks()
     */
    public function clearCategoryWorks()
    {
        $this->collCategoryWorks = null; // important to set this to null since that means it is uninitialized
        $this->collCategoryWorksPartial = null;

        return $this;
    }

    /**
     * reset is the collCategoryWorks collection loaded partially
     *
     * @return void
     */
    public function resetPartialCategoryWorks($v = true)
    {
        $this->collCategoryWorksPartial = $v;
    }

    /**
     * Initializes the collCategoryWorks collection.
     *
     * By default this just sets the collCategoryWorks collection to an empty array (like clearcollCategoryWorks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCategoryWorks($overrideExisting = true)
    {
        if (null !== $this->collCategoryWorks && !$overrideExisting) {
            return;
        }
        $this->collCategoryWorks = new PropelObjectCollection();
        $this->collCategoryWorks->setModel('CategoryWork');
    }

    /**
     * Gets an array of CategoryWork objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Work is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CategoryWork[] List of CategoryWork objects
     * @throws PropelException
     */
    public function getCategoryWorks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCategoryWorksPartial && !$this->isNew();
        if (null === $this->collCategoryWorks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCategoryWorks) {
                // return empty collection
                $this->initCategoryWorks();
            } else {
                $collCategoryWorks = CategoryWorkQuery::create(null, $criteria)
                    ->filterByWork($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCategoryWorksPartial && count($collCategoryWorks)) {
                      $this->initCategoryWorks(false);

                      foreach($collCategoryWorks as $obj) {
                        if (false == $this->collCategoryWorks->contains($obj)) {
                          $this->collCategoryWorks->append($obj);
                        }
                      }

                      $this->collCategoryWorksPartial = true;
                    }

                    $collCategoryWorks->getInternalIterator()->rewind();
                    return $collCategoryWorks;
                }

                if($partial && $this->collCategoryWorks) {
                    foreach($this->collCategoryWorks as $obj) {
                        if($obj->isNew()) {
                            $collCategoryWorks[] = $obj;
                        }
                    }
                }

                $this->collCategoryWorks = $collCategoryWorks;
                $this->collCategoryWorksPartial = false;
            }
        }

        return $this->collCategoryWorks;
    }

    /**
     * Sets a collection of CategoryWork objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $categoryWorks A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Work The current object (for fluent API support)
     */
    public function setCategoryWorks(PropelCollection $categoryWorks, PropelPDO $con = null)
    {
        $categoryWorksToDelete = $this->getCategoryWorks(new Criteria(), $con)->diff($categoryWorks);

        $this->categoryWorksScheduledForDeletion = unserialize(serialize($categoryWorksToDelete));

        foreach ($categoryWorksToDelete as $categoryWorkRemoved) {
            $categoryWorkRemoved->setWork(null);
        }

        $this->collCategoryWorks = null;
        foreach ($categoryWorks as $categoryWork) {
            $this->addCategoryWork($categoryWork);
        }

        $this->collCategoryWorks = $categoryWorks;
        $this->collCategoryWorksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CategoryWork objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CategoryWork objects.
     * @throws PropelException
     */
    public function countCategoryWorks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCategoryWorksPartial && !$this->isNew();
        if (null === $this->collCategoryWorks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCategoryWorks) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getCategoryWorks());
            }
            $query = CategoryWorkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByWork($this)
                ->count($con);
        }

        return count($this->collCategoryWorks);
    }

    /**
     * Method called to associate a CategoryWork object to this object
     * through the CategoryWork foreign key attribute.
     *
     * @param    CategoryWork $l CategoryWork
     * @return Work The current object (for fluent API support)
     */
    public function addCategoryWork(CategoryWork $l)
    {
        if ($this->collCategoryWorks === null) {
            $this->initCategoryWorks();
            $this->collCategoryWorksPartial = true;
        }
        if (!in_array($l, $this->collCategoryWorks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCategoryWork($l);
        }

        return $this;
    }

    /**
     * @param	CategoryWork $categoryWork The categoryWork object to add.
     */
    protected function doAddCategoryWork($categoryWork)
    {
        $this->collCategoryWorks[]= $categoryWork;
        $categoryWork->setWork($this);
    }

    /**
     * @param	CategoryWork $categoryWork The categoryWork object to remove.
     * @return Work The current object (for fluent API support)
     */
    public function removeCategoryWork($categoryWork)
    {
        if ($this->getCategoryWorks()->contains($categoryWork)) {
            $this->collCategoryWorks->remove($this->collCategoryWorks->search($categoryWork));
            if (null === $this->categoryWorksScheduledForDeletion) {
                $this->categoryWorksScheduledForDeletion = clone $this->collCategoryWorks;
                $this->categoryWorksScheduledForDeletion->clear();
            }
            $this->categoryWorksScheduledForDeletion[]= clone $categoryWork;
            $categoryWork->setWork(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related CategoryWorks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CategoryWork[] List of CategoryWork objects
     */
    public function getCategoryWorksJoinCategory($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CategoryWorkQuery::create(null, $criteria);
        $query->joinWith('Category', $join_behavior);

        return $this->getCategoryWorks($query, $con);
    }

    /**
     * Clears out the collPersonWorks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Work The current object (for fluent API support)
     * @see        addPersonWorks()
     */
    public function clearPersonWorks()
    {
        $this->collPersonWorks = null; // important to set this to null since that means it is uninitialized
        $this->collPersonWorksPartial = null;

        return $this;
    }

    /**
     * reset is the collPersonWorks collection loaded partially
     *
     * @return void
     */
    public function resetPartialPersonWorks($v = true)
    {
        $this->collPersonWorksPartial = $v;
    }

    /**
     * Initializes the collPersonWorks collection.
     *
     * By default this just sets the collPersonWorks collection to an empty array (like clearcollPersonWorks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPersonWorks($overrideExisting = true)
    {
        if (null !== $this->collPersonWorks && !$overrideExisting) {
            return;
        }
        $this->collPersonWorks = new PropelObjectCollection();
        $this->collPersonWorks->setModel('PersonWork');
    }

    /**
     * Gets an array of PersonWork objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Work is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PersonWork[] List of PersonWork objects
     * @throws PropelException
     */
    public function getPersonWorks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPersonWorksPartial && !$this->isNew();
        if (null === $this->collPersonWorks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPersonWorks) {
                // return empty collection
                $this->initPersonWorks();
            } else {
                $collPersonWorks = PersonWorkQuery::create(null, $criteria)
                    ->filterByWork($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPersonWorksPartial && count($collPersonWorks)) {
                      $this->initPersonWorks(false);

                      foreach($collPersonWorks as $obj) {
                        if (false == $this->collPersonWorks->contains($obj)) {
                          $this->collPersonWorks->append($obj);
                        }
                      }

                      $this->collPersonWorksPartial = true;
                    }

                    $collPersonWorks->getInternalIterator()->rewind();
                    return $collPersonWorks;
                }

                if($partial && $this->collPersonWorks) {
                    foreach($this->collPersonWorks as $obj) {
                        if($obj->isNew()) {
                            $collPersonWorks[] = $obj;
                        }
                    }
                }

                $this->collPersonWorks = $collPersonWorks;
                $this->collPersonWorksPartial = false;
            }
        }

        return $this->collPersonWorks;
    }

    /**
     * Sets a collection of PersonWork objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $personWorks A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Work The current object (for fluent API support)
     */
    public function setPersonWorks(PropelCollection $personWorks, PropelPDO $con = null)
    {
        $personWorksToDelete = $this->getPersonWorks(new Criteria(), $con)->diff($personWorks);

        $this->personWorksScheduledForDeletion = unserialize(serialize($personWorksToDelete));

        foreach ($personWorksToDelete as $personWorkRemoved) {
            $personWorkRemoved->setWork(null);
        }

        $this->collPersonWorks = null;
        foreach ($personWorks as $personWork) {
            $this->addPersonWork($personWork);
        }

        $this->collPersonWorks = $personWorks;
        $this->collPersonWorksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PersonWork objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PersonWork objects.
     * @throws PropelException
     */
    public function countPersonWorks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPersonWorksPartial && !$this->isNew();
        if (null === $this->collPersonWorks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPersonWorks) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPersonWorks());
            }
            $query = PersonWorkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByWork($this)
                ->count($con);
        }

        return count($this->collPersonWorks);
    }

    /**
     * Method called to associate a PersonWork object to this object
     * through the PersonWork foreign key attribute.
     *
     * @param    PersonWork $l PersonWork
     * @return Work The current object (for fluent API support)
     */
    public function addPersonWork(PersonWork $l)
    {
        if ($this->collPersonWorks === null) {
            $this->initPersonWorks();
            $this->collPersonWorksPartial = true;
        }
        if (!in_array($l, $this->collPersonWorks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPersonWork($l);
        }

        return $this;
    }

    /**
     * @param	PersonWork $personWork The personWork object to add.
     */
    protected function doAddPersonWork($personWork)
    {
        $this->collPersonWorks[]= $personWork;
        $personWork->setWork($this);
    }

    /**
     * @param	PersonWork $personWork The personWork object to remove.
     * @return Work The current object (for fluent API support)
     */
    public function removePersonWork($personWork)
    {
        if ($this->getPersonWorks()->contains($personWork)) {
            $this->collPersonWorks->remove($this->collPersonWorks->search($personWork));
            if (null === $this->personWorksScheduledForDeletion) {
                $this->personWorksScheduledForDeletion = clone $this->collPersonWorks;
                $this->personWorksScheduledForDeletion->clear();
            }
            $this->personWorksScheduledForDeletion[]= clone $personWork;
            $personWork->setWork(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related PersonWorks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PersonWork[] List of PersonWork objects
     */
    public function getPersonWorksJoinPerson($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PersonWorkQuery::create(null, $criteria);
        $query->joinWith('Person', $join_behavior);

        return $this->getPersonWorks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Work is new, it will return
     * an empty collection; or if this Work has previously
     * been saved, it will retrieve related PersonWorks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Work.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PersonWork[] List of PersonWork objects
     */
    public function getPersonWorksJoinPersonrole($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PersonWorkQuery::create(null, $criteria);
        $query->joinWith('Personrole', $join_behavior);

        return $this->getPersonWorks($query, $con);
    }

    /**
     * Clears out the collLanguages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Work The current object (for fluent API support)
     * @see        addLanguages()
     */
    public function clearLanguages()
    {
        $this->collLanguages = null; // important to set this to null since that means it is uninitialized
        $this->collLanguagesPartial = null;

        return $this;
    }

    /**
     * Initializes the collLanguages collection.
     *
     * By default this just sets the collLanguages collection to an empty collection (like clearLanguages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initLanguages()
    {
        $this->collLanguages = new PropelObjectCollection();
        $this->collLanguages->setModel('Language');
    }

    /**
     * Gets a collection of Language objects related by a many-to-many relationship
     * to the current object by way of the language_work cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Work is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Language[] List of Language objects
     */
    public function getLanguages($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collLanguages || null !== $criteria) {
            if ($this->isNew() && null === $this->collLanguages) {
                // return empty collection
                $this->initLanguages();
            } else {
                $collLanguages = LanguageQuery::create(null, $criteria)
                    ->filterByWork($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collLanguages;
                }
                $this->collLanguages = $collLanguages;
            }
        }

        return $this->collLanguages;
    }

    /**
     * Sets a collection of Language objects related by a many-to-many relationship
     * to the current object by way of the language_work cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $languages A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Work The current object (for fluent API support)
     */
    public function setLanguages(PropelCollection $languages, PropelPDO $con = null)
    {
        $this->clearLanguages();
        $currentLanguages = $this->getLanguages();

        $this->languagesScheduledForDeletion = $currentLanguages->diff($languages);

        foreach ($languages as $language) {
            if (!$currentLanguages->contains($language)) {
                $this->doAddLanguage($language);
            }
        }

        $this->collLanguages = $languages;

        return $this;
    }

    /**
     * Gets the number of Language objects related by a many-to-many relationship
     * to the current object by way of the language_work cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Language objects
     */
    public function countLanguages($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collLanguages || null !== $criteria) {
            if ($this->isNew() && null === $this->collLanguages) {
                return 0;
            } else {
                $query = LanguageQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByWork($this)
                    ->count($con);
            }
        } else {
            return count($this->collLanguages);
        }
    }

    /**
     * Associate a Language object to this object
     * through the language_work cross reference table.
     *
     * @param  Language $language The LanguageWork object to relate
     * @return Work The current object (for fluent API support)
     */
    public function addLanguage(Language $language)
    {
        if ($this->collLanguages === null) {
            $this->initLanguages();
        }
        if (!$this->collLanguages->contains($language)) { // only add it if the **same** object is not already associated
            $this->doAddLanguage($language);

            $this->collLanguages[]= $language;
        }

        return $this;
    }

    /**
     * @param	Language $language The language object to add.
     */
    protected function doAddLanguage($language)
    {
        $languageWork = new LanguageWork();
        $languageWork->setLanguage($language);
        $this->addLanguageWork($languageWork);
    }

    /**
     * Remove a Language object to this object
     * through the language_work cross reference table.
     *
     * @param Language $language The LanguageWork object to relate
     * @return Work The current object (for fluent API support)
     */
    public function removeLanguage(Language $language)
    {
        if ($this->getLanguages()->contains($language)) {
            $this->collLanguages->remove($this->collLanguages->search($language));
            if (null === $this->languagesScheduledForDeletion) {
                $this->languagesScheduledForDeletion = clone $this->collLanguages;
                $this->languagesScheduledForDeletion->clear();
            }
            $this->languagesScheduledForDeletion[]= $language;
        }

        return $this;
    }

    /**
     * Clears out the collGenres collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Work The current object (for fluent API support)
     * @see        addGenres()
     */
    public function clearGenres()
    {
        $this->collGenres = null; // important to set this to null since that means it is uninitialized
        $this->collGenresPartial = null;

        return $this;
    }

    /**
     * Initializes the collGenres collection.
     *
     * By default this just sets the collGenres collection to an empty collection (like clearGenres());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initGenres()
    {
        $this->collGenres = new PropelObjectCollection();
        $this->collGenres->setModel('Genre');
    }

    /**
     * Gets a collection of Genre objects related by a many-to-many relationship
     * to the current object by way of the genre_work cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Work is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Genre[] List of Genre objects
     */
    public function getGenres($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collGenres || null !== $criteria) {
            if ($this->isNew() && null === $this->collGenres) {
                // return empty collection
                $this->initGenres();
            } else {
                $collGenres = GenreQuery::create(null, $criteria)
                    ->filterByWork($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collGenres;
                }
                $this->collGenres = $collGenres;
            }
        }

        return $this->collGenres;
    }

    /**
     * Sets a collection of Genre objects related by a many-to-many relationship
     * to the current object by way of the genre_work cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $genres A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Work The current object (for fluent API support)
     */
    public function setGenres(PropelCollection $genres, PropelPDO $con = null)
    {
        $this->clearGenres();
        $currentGenres = $this->getGenres();

        $this->genresScheduledForDeletion = $currentGenres->diff($genres);

        foreach ($genres as $genre) {
            if (!$currentGenres->contains($genre)) {
                $this->doAddGenre($genre);
            }
        }

        $this->collGenres = $genres;

        return $this;
    }

    /**
     * Gets the number of Genre objects related by a many-to-many relationship
     * to the current object by way of the genre_work cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Genre objects
     */
    public function countGenres($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collGenres || null !== $criteria) {
            if ($this->isNew() && null === $this->collGenres) {
                return 0;
            } else {
                $query = GenreQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByWork($this)
                    ->count($con);
            }
        } else {
            return count($this->collGenres);
        }
    }

    /**
     * Associate a Genre object to this object
     * through the genre_work cross reference table.
     *
     * @param  Genre $genre The GenreWork object to relate
     * @return Work The current object (for fluent API support)
     */
    public function addGenre(Genre $genre)
    {
        if ($this->collGenres === null) {
            $this->initGenres();
        }
        if (!$this->collGenres->contains($genre)) { // only add it if the **same** object is not already associated
            $this->doAddGenre($genre);

            $this->collGenres[]= $genre;
        }

        return $this;
    }

    /**
     * @param	Genre $genre The genre object to add.
     */
    protected function doAddGenre($genre)
    {
        $genreWork = new GenreWork();
        $genreWork->setGenre($genre);
        $this->addGenreWork($genreWork);
    }

    /**
     * Remove a Genre object to this object
     * through the genre_work cross reference table.
     *
     * @param Genre $genre The GenreWork object to relate
     * @return Work The current object (for fluent API support)
     */
    public function removeGenre(Genre $genre)
    {
        if ($this->getGenres()->contains($genre)) {
            $this->collGenres->remove($this->collGenres->search($genre));
            if (null === $this->genresScheduledForDeletion) {
                $this->genresScheduledForDeletion = clone $this->collGenres;
                $this->genresScheduledForDeletion->clear();
            }
            $this->genresScheduledForDeletion[]= $genre;
        }

        return $this;
    }

    /**
     * Clears out the collTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Work The current object (for fluent API support)
     * @see        addTags()
     */
    public function clearTags()
    {
        $this->collTags = null; // important to set this to null since that means it is uninitialized
        $this->collTagsPartial = null;

        return $this;
    }

    /**
     * Initializes the collTags collection.
     *
     * By default this just sets the collTags collection to an empty collection (like clearTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initTags()
    {
        $this->collTags = new PropelObjectCollection();
        $this->collTags->setModel('Tag');
    }

    /**
     * Gets a collection of Tag objects related by a many-to-many relationship
     * to the current object by way of the work_tag cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Work is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Tag[] List of Tag objects
     */
    public function getTags($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collTags || null !== $criteria) {
            if ($this->isNew() && null === $this->collTags) {
                // return empty collection
                $this->initTags();
            } else {
                $collTags = TagQuery::create(null, $criteria)
                    ->filterByWork($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collTags;
                }
                $this->collTags = $collTags;
            }
        }

        return $this->collTags;
    }

    /**
     * Sets a collection of Tag objects related by a many-to-many relationship
     * to the current object by way of the work_tag cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $tags A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Work The current object (for fluent API support)
     */
    public function setTags(PropelCollection $tags, PropelPDO $con = null)
    {
        $this->clearTags();
        $currentTags = $this->getTags();

        $this->tagsScheduledForDeletion = $currentTags->diff($tags);

        foreach ($tags as $tag) {
            if (!$currentTags->contains($tag)) {
                $this->doAddTag($tag);
            }
        }

        $this->collTags = $tags;

        return $this;
    }

    /**
     * Gets the number of Tag objects related by a many-to-many relationship
     * to the current object by way of the work_tag cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Tag objects
     */
    public function countTags($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collTags || null !== $criteria) {
            if ($this->isNew() && null === $this->collTags) {
                return 0;
            } else {
                $query = TagQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByWork($this)
                    ->count($con);
            }
        } else {
            return count($this->collTags);
        }
    }

    /**
     * Associate a Tag object to this object
     * through the work_tag cross reference table.
     *
     * @param  Tag $tag The WorkTag object to relate
     * @return Work The current object (for fluent API support)
     */
    public function addTag(Tag $tag)
    {
        if ($this->collTags === null) {
            $this->initTags();
        }
        if (!$this->collTags->contains($tag)) { // only add it if the **same** object is not already associated
            $this->doAddTag($tag);

            $this->collTags[]= $tag;
        }

        return $this;
    }

    /**
     * @param	Tag $tag The tag object to add.
     */
    protected function doAddTag($tag)
    {
        $workTag = new WorkTag();
        $workTag->setTag($tag);
        $this->addWorkTag($workTag);
    }

    /**
     * Remove a Tag object to this object
     * through the work_tag cross reference table.
     *
     * @param Tag $tag The WorkTag object to relate
     * @return Work The current object (for fluent API support)
     */
    public function removeTag(Tag $tag)
    {
        if ($this->getTags()->contains($tag)) {
            $this->collTags->remove($this->collTags->search($tag));
            if (null === $this->tagsScheduledForDeletion) {
                $this->tagsScheduledForDeletion = clone $this->collTags;
                $this->tagsScheduledForDeletion->clear();
            }
            $this->tagsScheduledForDeletion[]= $tag;
        }

        return $this;
    }

    /**
     * Clears out the collCategories collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Work The current object (for fluent API support)
     * @see        addCategories()
     */
    public function clearCategories()
    {
        $this->collCategories = null; // important to set this to null since that means it is uninitialized
        $this->collCategoriesPartial = null;

        return $this;
    }

    /**
     * Initializes the collCategories collection.
     *
     * By default this just sets the collCategories collection to an empty collection (like clearCategories());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initCategories()
    {
        $this->collCategories = new PropelObjectCollection();
        $this->collCategories->setModel('Category');
    }

    /**
     * Gets a collection of Category objects related by a many-to-many relationship
     * to the current object by way of the category_work cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Work is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Category[] List of Category objects
     */
    public function getCategories($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collCategories || null !== $criteria) {
            if ($this->isNew() && null === $this->collCategories) {
                // return empty collection
                $this->initCategories();
            } else {
                $collCategories = CategoryQuery::create(null, $criteria)
                    ->filterByWork($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collCategories;
                }
                $this->collCategories = $collCategories;
            }
        }

        return $this->collCategories;
    }

    /**
     * Sets a collection of Category objects related by a many-to-many relationship
     * to the current object by way of the category_work cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $categories A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Work The current object (for fluent API support)
     */
    public function setCategories(PropelCollection $categories, PropelPDO $con = null)
    {
        $this->clearCategories();
        $currentCategories = $this->getCategories();

        $this->categoriesScheduledForDeletion = $currentCategories->diff($categories);

        foreach ($categories as $category) {
            if (!$currentCategories->contains($category)) {
                $this->doAddCategory($category);
            }
        }

        $this->collCategories = $categories;

        return $this;
    }

    /**
     * Gets the number of Category objects related by a many-to-many relationship
     * to the current object by way of the category_work cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Category objects
     */
    public function countCategories($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collCategories || null !== $criteria) {
            if ($this->isNew() && null === $this->collCategories) {
                return 0;
            } else {
                $query = CategoryQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByWork($this)
                    ->count($con);
            }
        } else {
            return count($this->collCategories);
        }
    }

    /**
     * Associate a Category object to this object
     * through the category_work cross reference table.
     *
     * @param  Category $category The CategoryWork object to relate
     * @return Work The current object (for fluent API support)
     */
    public function addCategory(Category $category)
    {
        if ($this->collCategories === null) {
            $this->initCategories();
        }
        if (!$this->collCategories->contains($category)) { // only add it if the **same** object is not already associated
            $this->doAddCategory($category);

            $this->collCategories[]= $category;
        }

        return $this;
    }

    /**
     * @param	Category $category The category object to add.
     */
    protected function doAddCategory($category)
    {
        $categoryWork = new CategoryWork();
        $categoryWork->setCategory($category);
        $this->addCategoryWork($categoryWork);
    }

    /**
     * Remove a Category object to this object
     * through the category_work cross reference table.
     *
     * @param Category $category The CategoryWork object to relate
     * @return Work The current object (for fluent API support)
     */
    public function removeCategory(Category $category)
    {
        if ($this->getCategories()->contains($category)) {
            $this->collCategories->remove($this->collCategories->search($category));
            if (null === $this->categoriesScheduledForDeletion) {
                $this->categoriesScheduledForDeletion = clone $this->collCategories;
                $this->categoriesScheduledForDeletion->clear();
            }
            $this->categoriesScheduledForDeletion[]= $category;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->title_id = null;
        $this->datespecification_id = null;
        $this->doi = null;
        $this->comments = null;
        $this->format = null;
        $this->directoryname = null;
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
            if ($this->collLanguageWorks) {
                foreach ($this->collLanguageWorks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGenreWorks) {
                foreach ($this->collGenreWorks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWorkTags) {
                foreach ($this->collWorkTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCategoryWorks) {
                foreach ($this->collCategoryWorks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPersonWorks) {
                foreach ($this->collPersonWorks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLanguages) {
                foreach ($this->collLanguages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGenres) {
                foreach ($this->collGenres as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTags) {
                foreach ($this->collTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCategories) {
                foreach ($this->collCategories as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aTitle instanceof Persistent) {
              $this->aTitle->clearAllReferences($deep);
            }
            if ($this->aDatespecification instanceof Persistent) {
              $this->aDatespecification->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPublications instanceof PropelCollection) {
            $this->collPublications->clearIterator();
        }
        $this->collPublications = null;
        if ($this->collLanguageWorks instanceof PropelCollection) {
            $this->collLanguageWorks->clearIterator();
        }
        $this->collLanguageWorks = null;
        if ($this->collGenreWorks instanceof PropelCollection) {
            $this->collGenreWorks->clearIterator();
        }
        $this->collGenreWorks = null;
        if ($this->collWorkTags instanceof PropelCollection) {
            $this->collWorkTags->clearIterator();
        }
        $this->collWorkTags = null;
        if ($this->collCategoryWorks instanceof PropelCollection) {
            $this->collCategoryWorks->clearIterator();
        }
        $this->collCategoryWorks = null;
        if ($this->collPersonWorks instanceof PropelCollection) {
            $this->collPersonWorks->clearIterator();
        }
        $this->collPersonWorks = null;
        if ($this->collLanguages instanceof PropelCollection) {
            $this->collLanguages->clearIterator();
        }
        $this->collLanguages = null;
        if ($this->collGenres instanceof PropelCollection) {
            $this->collGenres->clearIterator();
        }
        $this->collGenres = null;
        if ($this->collTags instanceof PropelCollection) {
            $this->collTags->clearIterator();
        }
        $this->collTags = null;
        if ($this->collCategories instanceof PropelCollection) {
            $this->collCategories->clearIterator();
        }
        $this->collCategories = null;
        $this->aTitle = null;
        $this->aDatespecification = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(WorkPeer::DEFAULT_STRING_FORMAT);
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
