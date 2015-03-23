<?php
/**
 * @Author: danmarinescu
 * @Date:   2015-01-10 02:41:08
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-22 22:46:20
 */
namespace Db\Service\Invokable;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect as paginatorIterator;

class AbstractTable extends AbstractTableGateway implements ServiceLocatorAwareInterface
{
    protected $select = null;
    protected $tableName = null;
    protected $serviceLocator;
    protected $columnNames;

    public function init($tableName)
    {
        if (empty($this->serviceLocator)) {
            $this->setServiceLocator($serviceLocator);
        }
        if ($tableName) {
            $this->tableName = $tableName;
        } else {
            $this->tableName = end(explode('\\', get_called_class()));
        }

        $this->checkRefreshAdapter($tableName);

        $db = $this->serviceLocator->get('adapter');

        $resultPrototype = new ResultSet();

        // $this->getTablePrimaryKey($this->tableName);

        $this->table = $this->tableName;
        $this->featureSet = new Feature\FeatureSet();
        // $this->featureSet->addFeature(new Feature\RowGatewayFeature($this->getTablePrimaryKey($this->tableName)));
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize($resultPrototype);

        return $this;
    }

    public function checkRefreshAdapter($name)
    {
        if ($this->serviceLocator->has('refreshAdapter')) {
            $refreshed = $this->serviceLocator->get('refreshedTables');
            $this->serviceLocator->setAllowOverride(true);
            if (!isset($refreshed[$name])) {
                $refreshed[$name] = 1 ;
                $this->serviceLocator->setService('refreshedTables', $refreshed);
            } else {
                $this->serviceLocator->setService('refreshedTables', array( $name => 1));
            }
            $this->serviceLocator->setAllowOverride(false);
            return true;
        } else {
            return false;
        }
    }

    public function tableEnumToArray($column)
    {
        $sql = 'SHOW COLUMNS FROM '.$this->tableName.' WHERE field="'.$column.'"';

        $adapter = $this->getAdapter();
        $row = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE);

        // \Zend\Debug\Debug::dump($sql);

        foreach ($row as $option) {
            preg_match("/^enum\(\'(.*)\'\)$/", $option->Type, $matches);
            $enum = explode("','", $matches[1]);
            return $enum;
        }

    }

    public function getTablePrimaryKey($table)
    {
        $sql = "SHOW KEYS FROM {$table} WHERE Key_name='PRIMARY'";

        $adapter = $this->getServiceLocator()->get('adapter');

        $row = $adapter->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        return $row->current()->Column_name;
    }

    public function distinct()
    {
        $this->getSelect()->quantifier(\Zend\Db\Sql\Select::QUANTIFIER_DISTINCT);
        return $this;
    }

    public function __toString()
    {
        return $this->getSqlString();
    }

    public function select($where = null)
    {
        $this->select = $this->newSelect();
        return $this;
    }

    public function where($where = null)
    {
        $this->getSelect()->where($where);
        return $this;
    }

    protected function newSelect()
    {
        $sql = new Sql($this->getAdapter());
        $select = $sql->select();
        $select->from($this->tableName);
        return $select;
    }

    public function group($group)
    {
        $this->getSelect()->group($group);
        return $this;
    }

    public function having($having)
    {
        if (!$this->checkSelect()) {
            return false;
        }
        $this->getSelect()->having($having);
        return $this;
    }

    public function order($order)
    {
        $this->getSelect()->order($order);
        return $this;
    }

    public function limit($limit)
    {
        $this->getSelect()->limit($limit);
        return $this;
    }

    public function offset($offset)
    {
        $this->getSelect()->offset($offset);
        return $this;
    }

    public function join(array $tables = array())
    {
        if (isset($tables[0]) && is_array($tables[0])) {
            foreach ($tables as $table) {
                $this->select = $this->addTable($table, $this->select);
            }
        } else if (is_array($tables)) {
            $this->select = $this->addTable($tables, $this->select);
        }
        return $this;
    }

    protected function addTable($table, $select)
    {
        return $select->join(array((isset($table['alias']) ? $table['alias'] : $table['table_name']) => $table['table_name']), $table['join_condition'], isset($table['columns']) ? $table['columns'] : array(), (isset($table['join']) ? $table['join'] : null));
    }

    /**
     * @return array
     */
    public function getColumnNames()
    {
        if (!isset($this->columnNames[$this->tableName])) {
            $columns = $this->getAdapter()->query('Describe ' . $this->getTable(), \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
            $tableColumns = array();
            while ($column = $columns->current()) {
                $tableColumns[] = $column['Field'];
                $columns->next();
            }
            $this->columnNames[$this->tableName] = $tableColumns;
        }
        return $this->columnNames[$this->tableName];
    }

    public function filterData($post)
    {
        $data = array();
        $columns = $this->getColumnNames();
        foreach ($post as $columnName => $value) {
            if (in_array($columnName, $columns)) {
                $data[$columnName] = $value;
            }
        }
        return $data;
    }

    /**
     * default update method
     */
    public function update($data, $where = null, $allowNull = false)
    {
        $select     = $this->select()->where($where);
        $predicates = $select->getSelect()->where->getPredicates();



        if (sizeof($predicates) == 0) {
            throw new \Exception("Where condition is invalid or was not provided");
        }

        if (is_object($data)) {
            $data = $this->extractToArray($data, $allowNull);
        }

        if (!is_array($data)) {
            throw new \Exception('Param provided should be array or prototype object.');
        }
        $identity = $this->serviceLocator->get('AuthService')->getIdentity();
        // allow ONLY table columns
        $dbData = $this->filterData($data);

        // setting the LastUpdatedByUserId and timestamp
        $dbData['LastUpdatedByUserId'] = isset($identity->UserId) ? $identity->UserId : 0;
        $dbData['LastUpdatedTimestamp'] = date('Y-m-d H:i:s');

        return parent::update($dbData, $where);
    }

    public function insert($data, $allowNull = false)
    {
        if (is_object($data)) {
            $data = $this->extractToArray($data, $allowNull);
        }
        if (!is_array($data)) {
            throw new \Exception('Param provided should be array.');
        }

        $identity = $this->getServiceLocator()->get('AuthService')->getIdentity();

        // allow ONLY table columns
        $dbData = $this->filterData($data);

        // setting the CreatedByUserId and timestamp
        if (!isset($dbData['CreatedByUserId']) || empty($dbData['CreatedByUserId'])) {
            $dbData['CreatedByUserId'] = (isset($identity->UserId) ? $identity->UserId : (isset($data['CreatedByUserId']) ? $data['CreatedByUserId'] : 0));
        }
        if (!isset($dbData['CreatedTimestamp']) || empty($dbData['CreatedTimestamp'])) {
            $dbData['CreatedTimestamp'] = date('Y-m-d H:i:s');
        }

        parent::insert($dbData);
        return $this->lastInsertValue;
    }

    protected function extractToArray($object, $allowNull = false)
    {
        $array = get_object_vars($object);
        if (!$allowNull) {
            foreach ($array as $var => $value) {
                if ($object->$var === null) {
                    unset($array[$var]);
                }
            }
        }
        return $array;
    }

    public function fetchKeyValue($columns, $where = null)
    {
        /** @var \Iterator $results */
        if ($this->select == null) {
            $this->select = $this->newSelect();
        }
        $this->getSelect()->columns($columns, false);
        if ($where !== null) {
            $this->select->where($where);
        }
        $sql = $this->select->getSqlString($this->getAdapter()->getPlatform());
        $sql = str_replace('"', '', $sql);
        $results = $this->getAdapter()->query($sql, array(5));
        $this->select = null;
        if (!isset($columns[0])) {
            $columns = array_keys($columns);
        }
        $resultArray = array();
        foreach ($results as $row) {
            $resultArray[$row->$columns[0]] = $row->$columns[1];
        }

        if (!empty($resultArray)) {
            return $resultArray;
        }
        return false;
    }


    public function fetchPaginated($paginator)
    {
        $result = new Paginator(new paginatorIterator($this->getSelect(), $this->getAdapter()));
        $result->setCurrentPageNumber(isset($paginator['page']) ? $paginator['page'] : 0)
            ->setItemCountPerPage((isset($paginator['countPerPage']) ? $paginator['countPerPage'] : 20 ))
            ->setPageRange((isset($paginator['pageRange']) ? $paginator['pageRange'] : 10 ));
        return $result;
    }

    public function fetchRow($where = null)
    {
        /** @var \Iterator $result */
        if ($where === null) {
            $this->getSelect()->limit(1);
            $result = $this->selectWith($this->getSelect());
        } else {
            $sql = new Sql($this->getAdapter());
            $select = $sql->select();
            $select->from($this->tableName);
            $select->where($where);
            $result = $this->selectWith($select);
        }
        $this->select = null;
        return $result->current();
    }

    public function fetchAll($where = null)
    {
        if ($this->select === null) {
            $this->select();
        }
        if ($where !== null) {
            $this->getSelect()->where($where);
        }
        $results = $this->selectWith($this->select);
        $this->select = null;
        return $results;
    }

    public function columns(array $columns = array())
    {
        $this->getSelect()->columns($columns);
        return $this;
    }

    public function getSqlString()
    {
        $sql = new Sql($this->getAdapter());
        $selectString = $sql->getSqlStringForSqlObject($this->select);
        return $selectString;
    }

    protected function checkSelect()
    {
        if (!is_object($this->select)) {
            return false;
        }
        if (get_class($this->select) != 'Zend\Db\Sql\Select') {
            return false;
        }
        return true;
    }

    protected function getSelect()
    {
        if (!$this->checkSelect()) {
            $this->select = $this->newSelect();
        }
        return $this->select;
    }

    // transactions
    public function beginTransaction()
    {
        $this->getAdapter()->getDriver()->getConnection()->beginTransaction();
    }

    // s transaction
    public function commit()
    {
        $this->getAdapter()->getDriver()->getConnection()->commit();
        // the method above is not allways  executing the commit
        $this->getAdapter()->query('COMMIT')->execute();
    }

    // rollback transaction
    public function rollback()
    {
        $this->getAdapter()->getDriver()->getConnection()->rollback();
    }

    /**
     * Gets the value of tableName.
     *
     * @return mixed
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Sets the value of tableName.
     *
     * @param mixed $tableName the table name
     *
     * @return self
     */
    protected function setTableName($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /* (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

    }

    /* (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
