<?php

namespace DTA\MetadataBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \PDO;
use \Propel;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use DTA\MetadataBundle\Model\Partner;
use DTA\MetadataBundle\Model\PartnerPeer;
use DTA\MetadataBundle\Model\PartnerQuery;

/**
 * @method PartnerQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PartnerQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method PartnerQuery orderByAdress($order = Criteria::ASC) Order by the adress column
 * @method PartnerQuery orderByPerson($order = Criteria::ASC) Order by the person column
 * @method PartnerQuery orderByMail($order = Criteria::ASC) Order by the mail column
 * @method PartnerQuery orderByWeb($order = Criteria::ASC) Order by the web column
 * @method PartnerQuery orderByComments($order = Criteria::ASC) Order by the comments column
 * @method PartnerQuery orderByPhone1($order = Criteria::ASC) Order by the phone1 column
 * @method PartnerQuery orderByPhone2($order = Criteria::ASC) Order by the phone2 column
 * @method PartnerQuery orderByPhone3($order = Criteria::ASC) Order by the phone3 column
 * @method PartnerQuery orderByFax($order = Criteria::ASC) Order by the fax column
 * @method PartnerQuery orderByLogLastChange($order = Criteria::ASC) Order by the log_last_change column
 * @method PartnerQuery orderByLogLastUser($order = Criteria::ASC) Order by the log_last_user column
 *
 * @method PartnerQuery groupById() Group by the id column
 * @method PartnerQuery groupByName() Group by the name column
 * @method PartnerQuery groupByAdress() Group by the adress column
 * @method PartnerQuery groupByPerson() Group by the person column
 * @method PartnerQuery groupByMail() Group by the mail column
 * @method PartnerQuery groupByWeb() Group by the web column
 * @method PartnerQuery groupByComments() Group by the comments column
 * @method PartnerQuery groupByPhone1() Group by the phone1 column
 * @method PartnerQuery groupByPhone2() Group by the phone2 column
 * @method PartnerQuery groupByPhone3() Group by the phone3 column
 * @method PartnerQuery groupByFax() Group by the fax column
 * @method PartnerQuery groupByLogLastChange() Group by the log_last_change column
 * @method PartnerQuery groupByLogLastUser() Group by the log_last_user column
 *
 * @method PartnerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PartnerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PartnerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method Partner findOne(PropelPDO $con = null) Return the first Partner matching the query
 * @method Partner findOneOrCreate(PropelPDO $con = null) Return the first Partner matching the query, or a new Partner object populated from the query conditions when no match is found
 *
 * @method Partner findOneByName(string $name) Return the first Partner filtered by the name column
 * @method Partner findOneByAdress(string $adress) Return the first Partner filtered by the adress column
 * @method Partner findOneByPerson(string $person) Return the first Partner filtered by the person column
 * @method Partner findOneByMail(string $mail) Return the first Partner filtered by the mail column
 * @method Partner findOneByWeb(string $web) Return the first Partner filtered by the web column
 * @method Partner findOneByComments(string $comments) Return the first Partner filtered by the comments column
 * @method Partner findOneByPhone1(string $phone1) Return the first Partner filtered by the phone1 column
 * @method Partner findOneByPhone2(string $phone2) Return the first Partner filtered by the phone2 column
 * @method Partner findOneByPhone3(string $phone3) Return the first Partner filtered by the phone3 column
 * @method Partner findOneByFax(string $fax) Return the first Partner filtered by the fax column
 * @method Partner findOneByLogLastChange(string $log_last_change) Return the first Partner filtered by the log_last_change column
 * @method Partner findOneByLogLastUser(int $log_last_user) Return the first Partner filtered by the log_last_user column
 *
 * @method array findById(int $id) Return Partner objects filtered by the id column
 * @method array findByName(string $name) Return Partner objects filtered by the name column
 * @method array findByAdress(string $adress) Return Partner objects filtered by the adress column
 * @method array findByPerson(string $person) Return Partner objects filtered by the person column
 * @method array findByMail(string $mail) Return Partner objects filtered by the mail column
 * @method array findByWeb(string $web) Return Partner objects filtered by the web column
 * @method array findByComments(string $comments) Return Partner objects filtered by the comments column
 * @method array findByPhone1(string $phone1) Return Partner objects filtered by the phone1 column
 * @method array findByPhone2(string $phone2) Return Partner objects filtered by the phone2 column
 * @method array findByPhone3(string $phone3) Return Partner objects filtered by the phone3 column
 * @method array findByFax(string $fax) Return Partner objects filtered by the fax column
 * @method array findByLogLastChange(string $log_last_change) Return Partner objects filtered by the log_last_change column
 * @method array findByLogLastUser(int $log_last_user) Return Partner objects filtered by the log_last_user column
 */
abstract class BasePartnerQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePartnerQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'DTAMetadata', $modelName = 'DTA\\MetadataBundle\\Model\\Partner', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PartnerQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PartnerQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PartnerQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PartnerQuery) {
            return $criteria;
        }
        $query = new PartnerQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Partner|Partner[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PartnerPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PartnerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Partner A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Partner A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `adress`, `person`, `mail`, `web`, `comments`, `phone1`, `phone2`, `phone3`, `fax`, `log_last_change`, `log_last_user` FROM `partner` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Partner();
            $obj->hydrate($row);
            PartnerPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Partner|Partner[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Partner[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PartnerPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PartnerPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PartnerPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PartnerPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PartnerPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PartnerPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the adress column
     *
     * Example usage:
     * <code>
     * $query->filterByAdress('fooValue');   // WHERE adress = 'fooValue'
     * $query->filterByAdress('%fooValue%'); // WHERE adress LIKE '%fooValue%'
     * </code>
     *
     * @param     string $adress The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByAdress($adress = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($adress)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $adress)) {
                $adress = str_replace('*', '%', $adress);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PartnerPeer::ADRESS, $adress, $comparison);
    }

    /**
     * Filter the query on the person column
     *
     * Example usage:
     * <code>
     * $query->filterByPerson('fooValue');   // WHERE person = 'fooValue'
     * $query->filterByPerson('%fooValue%'); // WHERE person LIKE '%fooValue%'
     * </code>
     *
     * @param     string $person The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByPerson($person = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($person)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $person)) {
                $person = str_replace('*', '%', $person);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PartnerPeer::PERSON, $person, $comparison);
    }

    /**
     * Filter the query on the mail column
     *
     * Example usage:
     * <code>
     * $query->filterByMail('fooValue');   // WHERE mail = 'fooValue'
     * $query->filterByMail('%fooValue%'); // WHERE mail LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mail The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByMail($mail = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mail)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $mail)) {
                $mail = str_replace('*', '%', $mail);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PartnerPeer::MAIL, $mail, $comparison);
    }

    /**
     * Filter the query on the web column
     *
     * Example usage:
     * <code>
     * $query->filterByWeb('fooValue');   // WHERE web = 'fooValue'
     * $query->filterByWeb('%fooValue%'); // WHERE web LIKE '%fooValue%'
     * </code>
     *
     * @param     string $web The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByWeb($web = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($web)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $web)) {
                $web = str_replace('*', '%', $web);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PartnerPeer::WEB, $web, $comparison);
    }

    /**
     * Filter the query on the comments column
     *
     * Example usage:
     * <code>
     * $query->filterByComments('fooValue');   // WHERE comments = 'fooValue'
     * $query->filterByComments('%fooValue%'); // WHERE comments LIKE '%fooValue%'
     * </code>
     *
     * @param     string $comments The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByComments($comments = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($comments)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $comments)) {
                $comments = str_replace('*', '%', $comments);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PartnerPeer::COMMENTS, $comments, $comparison);
    }

    /**
     * Filter the query on the phone1 column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone1('fooValue');   // WHERE phone1 = 'fooValue'
     * $query->filterByPhone1('%fooValue%'); // WHERE phone1 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone1 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByPhone1($phone1 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone1)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $phone1)) {
                $phone1 = str_replace('*', '%', $phone1);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PartnerPeer::PHONE1, $phone1, $comparison);
    }

    /**
     * Filter the query on the phone2 column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone2('fooValue');   // WHERE phone2 = 'fooValue'
     * $query->filterByPhone2('%fooValue%'); // WHERE phone2 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone2 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByPhone2($phone2 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone2)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $phone2)) {
                $phone2 = str_replace('*', '%', $phone2);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PartnerPeer::PHONE2, $phone2, $comparison);
    }

    /**
     * Filter the query on the phone3 column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone3('fooValue');   // WHERE phone3 = 'fooValue'
     * $query->filterByPhone3('%fooValue%'); // WHERE phone3 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone3 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByPhone3($phone3 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone3)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $phone3)) {
                $phone3 = str_replace('*', '%', $phone3);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PartnerPeer::PHONE3, $phone3, $comparison);
    }

    /**
     * Filter the query on the fax column
     *
     * Example usage:
     * <code>
     * $query->filterByFax('fooValue');   // WHERE fax = 'fooValue'
     * $query->filterByFax('%fooValue%'); // WHERE fax LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fax The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByFax($fax = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fax)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $fax)) {
                $fax = str_replace('*', '%', $fax);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PartnerPeer::FAX, $fax, $comparison);
    }

    /**
     * Filter the query on the log_last_change column
     *
     * Example usage:
     * <code>
     * $query->filterByLogLastChange('2011-03-14'); // WHERE log_last_change = '2011-03-14'
     * $query->filterByLogLastChange('now'); // WHERE log_last_change = '2011-03-14'
     * $query->filterByLogLastChange(array('max' => 'yesterday')); // WHERE log_last_change > '2011-03-13'
     * </code>
     *
     * @param     mixed $logLastChange The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByLogLastChange($logLastChange = null, $comparison = null)
    {
        if (is_array($logLastChange)) {
            $useMinMax = false;
            if (isset($logLastChange['min'])) {
                $this->addUsingAlias(PartnerPeer::LOG_LAST_CHANGE, $logLastChange['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($logLastChange['max'])) {
                $this->addUsingAlias(PartnerPeer::LOG_LAST_CHANGE, $logLastChange['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PartnerPeer::LOG_LAST_CHANGE, $logLastChange, $comparison);
    }

    /**
     * Filter the query on the log_last_user column
     *
     * Example usage:
     * <code>
     * $query->filterByLogLastUser(1234); // WHERE log_last_user = 1234
     * $query->filterByLogLastUser(array(12, 34)); // WHERE log_last_user IN (12, 34)
     * $query->filterByLogLastUser(array('min' => 12)); // WHERE log_last_user >= 12
     * $query->filterByLogLastUser(array('max' => 12)); // WHERE log_last_user <= 12
     * </code>
     *
     * @param     mixed $logLastUser The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function filterByLogLastUser($logLastUser = null, $comparison = null)
    {
        if (is_array($logLastUser)) {
            $useMinMax = false;
            if (isset($logLastUser['min'])) {
                $this->addUsingAlias(PartnerPeer::LOG_LAST_USER, $logLastUser['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($logLastUser['max'])) {
                $this->addUsingAlias(PartnerPeer::LOG_LAST_USER, $logLastUser['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PartnerPeer::LOG_LAST_USER, $logLastUser, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   Partner $partner Object to remove from the list of results
     *
     * @return PartnerQuery The current query, for fluid interface
     */
    public function prune($partner = null)
    {
        if ($partner) {
            $this->addUsingAlias(PartnerPeer::ID, $partner->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
