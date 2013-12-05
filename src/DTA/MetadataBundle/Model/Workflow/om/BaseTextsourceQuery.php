<?php

namespace DTA\MetadataBundle\Model\Workflow\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use DTA\MetadataBundle\Model\Data\Publication;
use DTA\MetadataBundle\Model\Workflow\License;
use DTA\MetadataBundle\Model\Workflow\Partner;
use DTA\MetadataBundle\Model\Workflow\Textsource;
use DTA\MetadataBundle\Model\Workflow\TextsourcePeer;
use DTA\MetadataBundle\Model\Workflow\TextsourceQuery;

/**
 * @method TextsourceQuery orderById($order = Criteria::ASC) Order by the id column
 * @method TextsourceQuery orderByPublicationId($order = Criteria::ASC) Order by the publication_id column
 * @method TextsourceQuery orderByPartnerId($order = Criteria::ASC) Order by the partner_id column
 * @method TextsourceQuery orderByTexturl($order = Criteria::ASC) Order by the texturl column
 * @method TextsourceQuery orderByLicenseId($order = Criteria::ASC) Order by the license_id column
 * @method TextsourceQuery orderByAttribution($order = Criteria::ASC) Order by the attribution column
 *
 * @method TextsourceQuery groupById() Group by the id column
 * @method TextsourceQuery groupByPublicationId() Group by the publication_id column
 * @method TextsourceQuery groupByPartnerId() Group by the partner_id column
 * @method TextsourceQuery groupByTexturl() Group by the texturl column
 * @method TextsourceQuery groupByLicenseId() Group by the license_id column
 * @method TextsourceQuery groupByAttribution() Group by the attribution column
 *
 * @method TextsourceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method TextsourceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method TextsourceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method TextsourceQuery leftJoinPublication($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publication relation
 * @method TextsourceQuery rightJoinPublication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publication relation
 * @method TextsourceQuery innerJoinPublication($relationAlias = null) Adds a INNER JOIN clause to the query using the Publication relation
 *
 * @method TextsourceQuery leftJoinLicense($relationAlias = null) Adds a LEFT JOIN clause to the query using the License relation
 * @method TextsourceQuery rightJoinLicense($relationAlias = null) Adds a RIGHT JOIN clause to the query using the License relation
 * @method TextsourceQuery innerJoinLicense($relationAlias = null) Adds a INNER JOIN clause to the query using the License relation
 *
 * @method TextsourceQuery leftJoinPartner($relationAlias = null) Adds a LEFT JOIN clause to the query using the Partner relation
 * @method TextsourceQuery rightJoinPartner($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Partner relation
 * @method TextsourceQuery innerJoinPartner($relationAlias = null) Adds a INNER JOIN clause to the query using the Partner relation
 *
 * @method Textsource findOne(PropelPDO $con = null) Return the first Textsource matching the query
 * @method Textsource findOneOrCreate(PropelPDO $con = null) Return the first Textsource matching the query, or a new Textsource object populated from the query conditions when no match is found
 *
 * @method Textsource findOneByPublicationId(int $publication_id) Return the first Textsource filtered by the publication_id column
 * @method Textsource findOneByPartnerId(int $partner_id) Return the first Textsource filtered by the partner_id column
 * @method Textsource findOneByTexturl(string $texturl) Return the first Textsource filtered by the texturl column
 * @method Textsource findOneByLicenseId(int $license_id) Return the first Textsource filtered by the license_id column
 * @method Textsource findOneByAttribution(string $attribution) Return the first Textsource filtered by the attribution column
 *
 * @method array findById(int $id) Return Textsource objects filtered by the id column
 * @method array findByPublicationId(int $publication_id) Return Textsource objects filtered by the publication_id column
 * @method array findByPartnerId(int $partner_id) Return Textsource objects filtered by the partner_id column
 * @method array findByTexturl(string $texturl) Return Textsource objects filtered by the texturl column
 * @method array findByLicenseId(int $license_id) Return Textsource objects filtered by the license_id column
 * @method array findByAttribution(string $attribution) Return Textsource objects filtered by the attribution column
 */
abstract class BaseTextsourceQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseTextsourceQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'dtametadata';
        }
        if (null === $modelName) {
            $modelName = 'DTA\\MetadataBundle\\Model\\Workflow\\Textsource';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new TextsourceQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   TextsourceQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return TextsourceQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof TextsourceQuery) {
            return $criteria;
        }
        $query = new TextsourceQuery(null, null, $modelAlias);

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
     * @return   Textsource|Textsource[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TextsourcePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(TextsourcePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Textsource A model object, or null if the key is not found
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
     * @return                 Textsource A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT "id", "publication_id", "partner_id", "texturl", "license_id", "attribution" FROM "textsource" WHERE "id" = :p0';
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
            $obj = new Textsource();
            $obj->hydrate($row);
            TextsourcePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Textsource|Textsource[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Textsource[]|mixed the list of results, formatted by the current formatter
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
     * @return TextsourceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TextsourcePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return TextsourceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TextsourcePeer::ID, $keys, Criteria::IN);
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
     * @return TextsourceQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(TextsourcePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TextsourcePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TextsourcePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the publication_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublicationId(1234); // WHERE publication_id = 1234
     * $query->filterByPublicationId(array(12, 34)); // WHERE publication_id IN (12, 34)
     * $query->filterByPublicationId(array('min' => 12)); // WHERE publication_id >= 12
     * $query->filterByPublicationId(array('max' => 12)); // WHERE publication_id <= 12
     * </code>
     *
     * @see       filterByPublication()
     *
     * @param     mixed $publicationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TextsourceQuery The current query, for fluid interface
     */
    public function filterByPublicationId($publicationId = null, $comparison = null)
    {
        if (is_array($publicationId)) {
            $useMinMax = false;
            if (isset($publicationId['min'])) {
                $this->addUsingAlias(TextsourcePeer::PUBLICATION_ID, $publicationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publicationId['max'])) {
                $this->addUsingAlias(TextsourcePeer::PUBLICATION_ID, $publicationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TextsourcePeer::PUBLICATION_ID, $publicationId, $comparison);
    }

    /**
     * Filter the query on the partner_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPartnerId(1234); // WHERE partner_id = 1234
     * $query->filterByPartnerId(array(12, 34)); // WHERE partner_id IN (12, 34)
     * $query->filterByPartnerId(array('min' => 12)); // WHERE partner_id >= 12
     * $query->filterByPartnerId(array('max' => 12)); // WHERE partner_id <= 12
     * </code>
     *
     * @see       filterByPartner()
     *
     * @param     mixed $partnerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TextsourceQuery The current query, for fluid interface
     */
    public function filterByPartnerId($partnerId = null, $comparison = null)
    {
        if (is_array($partnerId)) {
            $useMinMax = false;
            if (isset($partnerId['min'])) {
                $this->addUsingAlias(TextsourcePeer::PARTNER_ID, $partnerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($partnerId['max'])) {
                $this->addUsingAlias(TextsourcePeer::PARTNER_ID, $partnerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TextsourcePeer::PARTNER_ID, $partnerId, $comparison);
    }

    /**
     * Filter the query on the texturl column
     *
     * Example usage:
     * <code>
     * $query->filterByTexturl('fooValue');   // WHERE texturl = 'fooValue'
     * $query->filterByTexturl('%fooValue%'); // WHERE texturl LIKE '%fooValue%'
     * </code>
     *
     * @param     string $texturl The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TextsourceQuery The current query, for fluid interface
     */
    public function filterByTexturl($texturl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($texturl)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $texturl)) {
                $texturl = str_replace('*', '%', $texturl);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TextsourcePeer::TEXTURL, $texturl, $comparison);
    }

    /**
     * Filter the query on the license_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLicenseId(1234); // WHERE license_id = 1234
     * $query->filterByLicenseId(array(12, 34)); // WHERE license_id IN (12, 34)
     * $query->filterByLicenseId(array('min' => 12)); // WHERE license_id >= 12
     * $query->filterByLicenseId(array('max' => 12)); // WHERE license_id <= 12
     * </code>
     *
     * @see       filterByLicense()
     *
     * @param     mixed $licenseId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TextsourceQuery The current query, for fluid interface
     */
    public function filterByLicenseId($licenseId = null, $comparison = null)
    {
        if (is_array($licenseId)) {
            $useMinMax = false;
            if (isset($licenseId['min'])) {
                $this->addUsingAlias(TextsourcePeer::LICENSE_ID, $licenseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($licenseId['max'])) {
                $this->addUsingAlias(TextsourcePeer::LICENSE_ID, $licenseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TextsourcePeer::LICENSE_ID, $licenseId, $comparison);
    }

    /**
     * Filter the query on the attribution column
     *
     * Example usage:
     * <code>
     * $query->filterByAttribution('fooValue');   // WHERE attribution = 'fooValue'
     * $query->filterByAttribution('%fooValue%'); // WHERE attribution LIKE '%fooValue%'
     * </code>
     *
     * @param     string $attribution The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TextsourceQuery The current query, for fluid interface
     */
    public function filterByAttribution($attribution = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($attribution)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $attribution)) {
                $attribution = str_replace('*', '%', $attribution);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TextsourcePeer::ATTRIBUTION, $attribution, $comparison);
    }

    /**
     * Filter the query by a related Publication object
     *
     * @param   Publication|PropelObjectCollection $publication The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 TextsourceQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPublication($publication, $comparison = null)
    {
        if ($publication instanceof Publication) {
            return $this
                ->addUsingAlias(TextsourcePeer::PUBLICATION_ID, $publication->getId(), $comparison);
        } elseif ($publication instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TextsourcePeer::PUBLICATION_ID, $publication->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPublication() only accepts arguments of type Publication or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Publication relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return TextsourceQuery The current query, for fluid interface
     */
    public function joinPublication($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Publication');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Publication');
        }

        return $this;
    }

    /**
     * Use the Publication relation Publication object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Data\PublicationQuery A secondary query class using the current class as primary query
     */
    public function usePublicationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPublication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publication', '\DTA\MetadataBundle\Model\Data\PublicationQuery');
    }

    /**
     * Filter the query by a related License object
     *
     * @param   License|PropelObjectCollection $license The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 TextsourceQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByLicense($license, $comparison = null)
    {
        if ($license instanceof License) {
            return $this
                ->addUsingAlias(TextsourcePeer::LICENSE_ID, $license->getId(), $comparison);
        } elseif ($license instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TextsourcePeer::LICENSE_ID, $license->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLicense() only accepts arguments of type License or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the License relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return TextsourceQuery The current query, for fluid interface
     */
    public function joinLicense($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('License');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'License');
        }

        return $this;
    }

    /**
     * Use the License relation License object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Workflow\LicenseQuery A secondary query class using the current class as primary query
     */
    public function useLicenseQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLicense($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'License', '\DTA\MetadataBundle\Model\Workflow\LicenseQuery');
    }

    /**
     * Filter the query by a related Partner object
     *
     * @param   Partner|PropelObjectCollection $partner The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 TextsourceQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPartner($partner, $comparison = null)
    {
        if ($partner instanceof Partner) {
            return $this
                ->addUsingAlias(TextsourcePeer::PARTNER_ID, $partner->getId(), $comparison);
        } elseif ($partner instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TextsourcePeer::PARTNER_ID, $partner->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPartner() only accepts arguments of type Partner or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Partner relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return TextsourceQuery The current query, for fluid interface
     */
    public function joinPartner($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Partner');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Partner');
        }

        return $this;
    }

    /**
     * Use the Partner relation Partner object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DTA\MetadataBundle\Model\Workflow\PartnerQuery A secondary query class using the current class as primary query
     */
    public function usePartnerQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPartner($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Partner', '\DTA\MetadataBundle\Model\Workflow\PartnerQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Textsource $textsource Object to remove from the list of results
     *
     * @return TextsourceQuery The current query, for fluid interface
     */
    public function prune($textsource = null)
    {
        if ($textsource) {
            $this->addUsingAlias(TextsourcePeer::ID, $textsource->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
