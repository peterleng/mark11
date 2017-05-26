<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/22 10:06
 */

namespace Lagee\DB;

use Closure;

class SqlBuilder
{
    /**
     * mysql连接
     *
     * @var
     */
    protected $mysql;

    /**
     * model类
     *
     * @var Model
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    /**
     * mysql数据库连接
     */
    public function mysql()
    {
        if($this->mysql == null){
            $this->mysql = Mysql::getInstance();
        }
        return $this->mysql;
    }

    /**
     * 通过主键查找单条记录
     *
     * @param int $id
     * @return mixed
     */
    public function find($id)
    {
        $sql = 'SELECT * FROM `' . $this->model->getTable() . '` WHERE `' . $this->model->getPrimary() . '` = ? LIMIT 1';

        return $this->mysql()->query($sql, [$id]);
    }

    /**
     * 查找单条记录
     *
     * @param string|array $where
     * @return mixed
     */
    public function findBy($where)
    {
        $whereStr = '';
        $params = $whereArr = [];
        if (is_array($where)) {
            foreach ($where as $key => $item) {
                $whereArr[] = '`' . $key . '` = ?';
                $params[] = $item;
            }
            $whereStr = implode(' and ', $whereArr);
        } else {
            $whereStr = $where;
        }

        $sql = 'SELECT * FROM `' . $this->model->getTable() . '` WHERE ' . $whereStr . ' LIMIT 1';

        return $this->mysql()->query($sql, $params);
    }


    /**
     * 获取全部记录
     *
     * @param string $field
     * @param string $order
     * @return mixed
     */
    public function all($field = '*', $order = '`id` DESC')
    {
        $sql = 'SELECT ' . $field . ' FROM `' . $this->model->getTable() . ' ORDER BY ' . $order;

        return $this->mysql()->query($sql, []);
    }

    /**
     * 查询记录集合
     *
     * @param string|array $where
     * @param string $order
     * @param int $limit
     * @param string $field
     * @return mixed
     */
    public function getList($where, $order = '`id` DESC', $limit = null,$field = '*')
    {
        $whereStr = '';
        $params = $whereArr = [];
        if (is_array($where)) {
            foreach ($where as $key => $item) {
                $whereArr[] = '`' . $key . '` = ?';
                $params[] = $item;
            }
            $whereStr = implode(' and ', $whereArr);
        } else {
            $whereStr = $where;
        }

        $sql = 'SELECT ' . $field . ' FROM `' . $this->model->getTable() . 'WHERE '.$whereStr.' ORDER BY ' . $order. ($limit > 0 ? ' LIMIT ' . $limit : '');

        return $this->mysql()->query($sql, []);
    }



    /**
     * 插入数据
     *
     * @param array $values
     * @return int insert_id
     */
    public function insert(array $values)
    {
        $values = array_merge($values,$this->model->generateTime(false));

        $fields = $wh = '';
        $params = [];
        foreach ($values as $key => $value) {
            $fields .= '`'.$key.'`,';
            $wh .= '?,';
            $params[] = $value;
        }

        $sql = 'INSERT INTO `' . $this->model->getTable() . '` ('.rtrim($fields,',').') VALUES  (' . rtrim($wh, ',') . ')';

        return $this->mysql()->insert($sql, $params);
    }


    /**
     * 修改数据
     *
     * @param string|array $where
     * @param array $values
     * @param int $limit
     * @return int
     */
    public function update($where, array $values, $limit = null)
    {
        $values = array_merge($values,$this->model->generateTime(true));

        $whereStr = $valStr = '';
        $params = $whereArr = $valueArr = [];

        foreach ($values as $key => $val) {
            $valueArr[] = '`' . $key . '` = ?';
            $params[] = $val;
        }
        $valStr = implode(',', $valueArr);

        if (is_array($where)) {
            foreach ($where as $key => $item) {
                $whereArr[] = '`' . $key . '` = ?';
                $params[] = $item;
            }
            $whereStr = implode(' and ', $whereArr);
        } else {
            $whereStr = $where;
        }

        $sql = 'UPDATE `' . $this->model->getTable() . '` SET ' . $valStr . ' WHERE ' . $whereStr . ($limit > 0 ? ' LIMIT ' . $limit : '');

        return $this->mysql()->update($sql, $params);
    }


    /**
     * 删除操作
     *
     * @param string|array $where
     * @param int $limit
     * @return int
     */
    public function delete($where, $limit = null)
    {
        $whereStr = '';
        $params = $whereArr = [];
        if (is_array($where)) {
            foreach ($where as $key => $item) {
                $whereArr[] = '`' . $key . '` = ?';
                $params[] = $item;
            }
            $whereStr = implode(' and ', $whereArr);
        } else {
            $whereStr = $where;
        }

        $sql = 'DELETE FROM `' . $this->model->getTable() . '` WHERE ' . $whereStr . ($limit > 0 ? ' LIMIT ' . $limit : '');

        return $this->mysql()->delete($sql, $params);
    }


    /**
     * 删除单条
     *
     * @param $id
     * @return int
     */
    public function deleteOne($id)
    {
        $sql = 'DELETE FROM `' . $this->model->getTable() . '` WHERE `' . $this->model->getPrimary() . '` = ? LIMIT 1';

        return $this->mysql()->delete($sql, [$id]);
    }


    /**
     * 执行事务
     *
     * @param Closure $callback
     * @return mixed
     */
    public function transaction($callback)
    {
        return $this->mysql()->transaction($callback);
    }

    /**
     * 获取最后一条sql语句
     *
     * @return array
     */
    public function getLastSql()
    {
        return $this->mysql()->getLastSql();
    }

    /**
     * 获取sql语句日志
     *
     * @return array
     */
    public function sql()
    {
        return $this->mysql()->getQueryLog();
    }
}