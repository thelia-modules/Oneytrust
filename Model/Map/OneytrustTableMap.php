<?php

namespace OneytrustScore\Model\Map;

use OneytrustScore\Model\Oneytrust;
use OneytrustScore\Model\OneytrustQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'Oneytrust' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class OneytrustTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'OneytrustScore.Model.Map.OneytrustTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'Oneytrust';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\OneytrustScore\\Model\\Oneytrust';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'OneytrustScore.Model.Oneytrust';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the ID field
     */
    const ID = 'Oneytrust.ID';

    /**
     * the column name for the COMMANDE field
     */
    const COMMANDE = 'Oneytrust.COMMANDE';

    /**
     * the column name for the STATUS field
     */
    const STATUS = 'Oneytrust.STATUS';

    /**
     * the column name for the VALIDATION field
     */
    const VALIDATION = 'Oneytrust.VALIDATION';

    /**
     * the column name for the MOTIFS field
     */
    const MOTIFS = 'Oneytrust.MOTIFS';

    /**
     * the column name for the EVALDATE field
     */
    const EVALDATE = 'Oneytrust.EVALDATE';

    /**
     * the column name for the CUSTOMERIP field
     */
    const CUSTOMERIP = 'Oneytrust.CUSTOMERIP';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Commande', 'Status', 'Validation', 'Motifs', 'Evaldate', 'Customerip', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'commande', 'status', 'validation', 'motifs', 'evaldate', 'customerip', ),
        self::TYPE_COLNAME       => array(OneytrustTableMap::ID, OneytrustTableMap::COMMANDE, OneytrustTableMap::STATUS, OneytrustTableMap::VALIDATION, OneytrustTableMap::MOTIFS, OneytrustTableMap::EVALDATE, OneytrustTableMap::CUSTOMERIP, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'COMMANDE', 'STATUS', 'VALIDATION', 'MOTIFS', 'EVALDATE', 'CUSTOMERIP', ),
        self::TYPE_FIELDNAME     => array('id', 'commande', 'status', 'validation', 'motifs', 'evaldate', 'customerIp', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Commande' => 1, 'Status' => 2, 'Validation' => 3, 'Motifs' => 4, 'Evaldate' => 5, 'Customerip' => 6, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'commande' => 1, 'status' => 2, 'validation' => 3, 'motifs' => 4, 'evaldate' => 5, 'customerip' => 6, ),
        self::TYPE_COLNAME       => array(OneytrustTableMap::ID => 0, OneytrustTableMap::COMMANDE => 1, OneytrustTableMap::STATUS => 2, OneytrustTableMap::VALIDATION => 3, OneytrustTableMap::MOTIFS => 4, OneytrustTableMap::EVALDATE => 5, OneytrustTableMap::CUSTOMERIP => 6, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'COMMANDE' => 1, 'STATUS' => 2, 'VALIDATION' => 3, 'MOTIFS' => 4, 'EVALDATE' => 5, 'CUSTOMERIP' => 6, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'commande' => 1, 'status' => 2, 'validation' => 3, 'motifs' => 4, 'evaldate' => 5, 'customerIp' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('Oneytrust');
        $this->setPhpName('Oneytrust');
        $this->setClassName('\\OneytrustScore\\Model\\Oneytrust');
        $this->setPackage('OneytrustScore.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('COMMANDE', 'Commande', 'LONGVARCHAR', false, null, null);
        $this->addColumn('STATUS', 'Status', 'LONGVARCHAR', false, null, null);
        $this->addColumn('VALIDATION', 'Validation', 'LONGVARCHAR', false, null, null);
        $this->addColumn('MOTIFS', 'Motifs', 'LONGVARCHAR', false, null, null);
        $this->addColumn('EVALDATE', 'Evaldate', 'LONGVARCHAR', false, null, null);
        $this->addColumn('CUSTOMERIP', 'Customerip', 'LONGVARCHAR', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {

            return (int) $row[
                            $indexType == TableMap::TYPE_NUM
                            ? 0 + $offset
                            : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
                        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? OneytrustTableMap::CLASS_DEFAULT : OneytrustTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (Oneytrust object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = OneytrustTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = OneytrustTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + OneytrustTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = OneytrustTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            OneytrustTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = OneytrustTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = OneytrustTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                OneytrustTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(OneytrustTableMap::ID);
            $criteria->addSelectColumn(OneytrustTableMap::COMMANDE);
            $criteria->addSelectColumn(OneytrustTableMap::STATUS);
            $criteria->addSelectColumn(OneytrustTableMap::VALIDATION);
            $criteria->addSelectColumn(OneytrustTableMap::MOTIFS);
            $criteria->addSelectColumn(OneytrustTableMap::EVALDATE);
            $criteria->addSelectColumn(OneytrustTableMap::CUSTOMERIP);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.COMMANDE');
            $criteria->addSelectColumn($alias . '.STATUS');
            $criteria->addSelectColumn($alias . '.VALIDATION');
            $criteria->addSelectColumn($alias . '.MOTIFS');
            $criteria->addSelectColumn($alias . '.EVALDATE');
            $criteria->addSelectColumn($alias . '.CUSTOMERIP');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(OneytrustTableMap::DATABASE_NAME)->getTable(OneytrustTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(OneytrustTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(OneytrustTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new OneytrustTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a Oneytrust or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Oneytrust object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OneytrustTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \OneytrustScore\Model\Oneytrust) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(OneytrustTableMap::DATABASE_NAME);
            $criteria->add(OneytrustTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = OneytrustQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { OneytrustTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { OneytrustTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the Oneytrust table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return OneytrustQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Oneytrust or Criteria object.
     *
     * @param mixed               $criteria Criteria or Oneytrust object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OneytrustTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Oneytrust object
        }

        if ($criteria->containsKey(OneytrustTableMap::ID) && $criteria->keyContainsValue(OneytrustTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.OneytrustTableMap::ID.')');
        }


        // Set the correct dbName
        $query = OneytrustQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // OneytrustTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
OneytrustTableMap::buildTableMap();
