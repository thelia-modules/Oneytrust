<?php

namespace OneytrustScore\Model\Base;

use \Exception;
use \PDO;
use OneytrustScore\Model\Oneytrust as ChildOneytrust;
use OneytrustScore\Model\OneytrustQuery as ChildOneytrustQuery;
use OneytrustScore\Model\Map\OneytrustTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'Oneytrust' table.
 *
 *
 *
 * @method     ChildOneytrustQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildOneytrustQuery orderByCommande($order = Criteria::ASC) Order by the commande column
 * @method     ChildOneytrustQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildOneytrustQuery orderByValidation($order = Criteria::ASC) Order by the validation column
 * @method     ChildOneytrustQuery orderByMotifs($order = Criteria::ASC) Order by the motifs column
 * @method     ChildOneytrustQuery orderByEvaldate($order = Criteria::ASC) Order by the evaldate column
 * @method     ChildOneytrustQuery orderByCustomerip($order = Criteria::ASC) Order by the customerIp column
 *
 * @method     ChildOneytrustQuery groupById() Group by the id column
 * @method     ChildOneytrustQuery groupByCommande() Group by the commande column
 * @method     ChildOneytrustQuery groupByStatus() Group by the status column
 * @method     ChildOneytrustQuery groupByValidation() Group by the validation column
 * @method     ChildOneytrustQuery groupByMotifs() Group by the motifs column
 * @method     ChildOneytrustQuery groupByEvaldate() Group by the evaldate column
 * @method     ChildOneytrustQuery groupByCustomerip() Group by the customerIp column
 *
 * @method     ChildOneytrustQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildOneytrustQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildOneytrustQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildOneytrust findOne(ConnectionInterface $con = null) Return the first ChildOneytrust matching the query
 * @method     ChildOneytrust findOneOrCreate(ConnectionInterface $con = null) Return the first ChildOneytrust matching the query, or a new ChildOneytrust object populated from the query conditions when no match is found
 *
 * @method     ChildOneytrust findOneById(int $id) Return the first ChildOneytrust filtered by the id column
 * @method     ChildOneytrust findOneByCommande(string $commande) Return the first ChildOneytrust filtered by the commande column
 * @method     ChildOneytrust findOneByStatus(string $status) Return the first ChildOneytrust filtered by the status column
 * @method     ChildOneytrust findOneByValidation(string $validation) Return the first ChildOneytrust filtered by the validation column
 * @method     ChildOneytrust findOneByMotifs(string $motifs) Return the first ChildOneytrust filtered by the motifs column
 * @method     ChildOneytrust findOneByEvaldate(string $evaldate) Return the first ChildOneytrust filtered by the evaldate column
 * @method     ChildOneytrust findOneByCustomerip(string $customerIp) Return the first ChildOneytrust filtered by the customerIp column
 *
 * @method     array findById(int $id) Return ChildOneytrust objects filtered by the id column
 * @method     array findByCommande(string $commande) Return ChildOneytrust objects filtered by the commande column
 * @method     array findByStatus(string $status) Return ChildOneytrust objects filtered by the status column
 * @method     array findByValidation(string $validation) Return ChildOneytrust objects filtered by the validation column
 * @method     array findByMotifs(string $motifs) Return ChildOneytrust objects filtered by the motifs column
 * @method     array findByEvaldate(string $evaldate) Return ChildOneytrust objects filtered by the evaldate column
 * @method     array findByCustomerip(string $customerIp) Return ChildOneytrust objects filtered by the customerIp column
 *
 */
abstract class OneytrustQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \OneytrustScore\Model\Base\OneytrustQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\OneytrustScore\\Model\\Oneytrust', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildOneytrustQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildOneytrustQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \OneytrustScore\Model\OneytrustQuery) {
            return $criteria;
        }
        $query = new \OneytrustScore\Model\OneytrustQuery();
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
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildOneytrust|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = OneytrustTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(OneytrustTableMap::DATABASE_NAME);
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
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildOneytrust A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, COMMANDE, STATUS, VALIDATION, MOTIFS, EVALDATE, CUSTOMERIP FROM Oneytrust WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildOneytrust();
            $obj->hydrate($row);
            OneytrustTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildOneytrust|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildOneytrustQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(OneytrustTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildOneytrustQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(OneytrustTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOneytrustQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(OneytrustTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(OneytrustTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OneytrustTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the commande column
     *
     * Example usage:
     * <code>
     * $query->filterByCommande('fooValue');   // WHERE commande = 'fooValue'
     * $query->filterByCommande('%fooValue%'); // WHERE commande LIKE '%fooValue%'
     * </code>
     *
     * @param     string $commande The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOneytrustQuery The current query, for fluid interface
     */
    public function filterByCommande($commande = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($commande)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $commande)) {
                $commande = str_replace('*', '%', $commande);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(OneytrustTableMap::COMMANDE, $commande, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus('fooValue');   // WHERE status = 'fooValue'
     * $query->filterByStatus('%fooValue%'); // WHERE status LIKE '%fooValue%'
     * </code>
     *
     * @param     string $status The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOneytrustQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($status)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $status)) {
                $status = str_replace('*', '%', $status);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(OneytrustTableMap::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the validation column
     *
     * Example usage:
     * <code>
     * $query->filterByValidation('fooValue');   // WHERE validation = 'fooValue'
     * $query->filterByValidation('%fooValue%'); // WHERE validation LIKE '%fooValue%'
     * </code>
     *
     * @param     string $validation The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOneytrustQuery The current query, for fluid interface
     */
    public function filterByValidation($validation = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($validation)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $validation)) {
                $validation = str_replace('*', '%', $validation);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(OneytrustTableMap::VALIDATION, $validation, $comparison);
    }

    /**
     * Filter the query on the motifs column
     *
     * Example usage:
     * <code>
     * $query->filterByMotifs('fooValue');   // WHERE motifs = 'fooValue'
     * $query->filterByMotifs('%fooValue%'); // WHERE motifs LIKE '%fooValue%'
     * </code>
     *
     * @param     string $motifs The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOneytrustQuery The current query, for fluid interface
     */
    public function filterByMotifs($motifs = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($motifs)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $motifs)) {
                $motifs = str_replace('*', '%', $motifs);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(OneytrustTableMap::MOTIFS, $motifs, $comparison);
    }

    /**
     * Filter the query on the evaldate column
     *
     * Example usage:
     * <code>
     * $query->filterByEvaldate('fooValue');   // WHERE evaldate = 'fooValue'
     * $query->filterByEvaldate('%fooValue%'); // WHERE evaldate LIKE '%fooValue%'
     * </code>
     *
     * @param     string $evaldate The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOneytrustQuery The current query, for fluid interface
     */
    public function filterByEvaldate($evaldate = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($evaldate)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $evaldate)) {
                $evaldate = str_replace('*', '%', $evaldate);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(OneytrustTableMap::EVALDATE, $evaldate, $comparison);
    }

    /**
     * Filter the query on the customerIp column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomerip('fooValue');   // WHERE customerIp = 'fooValue'
     * $query->filterByCustomerip('%fooValue%'); // WHERE customerIp LIKE '%fooValue%'
     * </code>
     *
     * @param     string $customerip The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOneytrustQuery The current query, for fluid interface
     */
    public function filterByCustomerip($customerip = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($customerip)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $customerip)) {
                $customerip = str_replace('*', '%', $customerip);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(OneytrustTableMap::CUSTOMERIP, $customerip, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildOneytrust $oneytrust Object to remove from the list of results
     *
     * @return ChildOneytrustQuery The current query, for fluid interface
     */
    public function prune($oneytrust = null)
    {
        if ($oneytrust) {
            $this->addUsingAlias(OneytrustTableMap::ID, $oneytrust->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the Oneytrust table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OneytrustTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            OneytrustTableMap::clearInstancePool();
            OneytrustTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildOneytrust or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildOneytrust object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OneytrustTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(OneytrustTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        OneytrustTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            OneytrustTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // OneytrustQuery
