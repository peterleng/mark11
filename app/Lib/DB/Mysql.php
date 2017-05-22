<?php

namespace App\Lib\DB;

use App\Lib\Exception\Program\DBConnectionException;
use App\Lib\Traits\Singleton;
use Closure;
use Exception;
use Mysqli;
use Throwable;

/**
 * User: Peter Leng
 * DateTime: 2017/5/18 19:52
 */
class Mysql
{
    use Singleton;
    /**
     * @var Mysqli
     */
    protected $mysqli;

    /**
     * 调试模式开始sql语句日志
     *
     * @var array
     */
    protected $sqls = [];


    protected function __construct()
    {
        $this->connect()->setCharset();
    }

    /**
     * 连接数据库
     *
     * @return $this
     * @throws DBConnectionException
     */
    protected function connect()
    {
        $this->mysqli = new Mysqli(config('db.host'), config('db.username'), config('db.passwd'), config('db.dbname'), config('db.port', 3306));
        if ($this->mysqli->connect_errno) {
            throw new DBConnectionException($this->mysqli->connect_error, $this->mysqli->connect_errno);
        }
        return $this;
    }

    /**
     * 设置字符编码
     *
     * @return $this
     */
    protected function setCharset()
    {
        $this->mysqli->set_charset(config('db.charset', 'utf8'));
        return $this;
    }


    /**
     * 预备执行
     *
     * @param string $sql
     * @param array $params
     * @param Closure $callback
     * @return bool|mixed
     * @throws DBConnectionException
     */
    protected function prepare($sql,array $params,$callback=null)
    {
        $this->logSql($sql,$params);

        $result = false;
        $stmt = $this->mysqli->stmt_init();
        if ($stmt->prepare($sql)) {

            $stmt->bind_param($this->getTypeString($params), ...$params);

            $flag = $stmt->execute();

            if (!$flag) {
                throw new DBConnectionException($stmt->error, $stmt->errno);
            }

            if ($callback instanceof \Closure) {
                $result = call_user_func($callback,$stmt);
            }
        }

        $stmt->free_result();
        $stmt->close();

        return $result;
    }

    /**
     * 获取查询时的type字符串
     *
     * @param array $params
     * @return string
     */
    protected function getTypeString(array $params)
    {
        $type = '';
        foreach ($params as $column) {
            if(is_string($column)){
                $type .= 's';
            }elseif (is_integer($column)){
                $type .= 'i';
            }elseif (is_float($column) || is_double($column)){
                $type .= 'd';
            }
        }
        return $type;
    }

    /**
     * 记录sql语句
     *
     * @param $sql
     * @param array $params
     */
    protected function logSql($sql,array $params)
    {
        if(APP_DEBUG){
            $this->sqls[] = ['sql' => $sql,'binds' => $params];
        }
    }
    

    /**
     * 执行单条sql 查询操作
     *
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function query($sql, array $params)
    {
        return $this->prepare($sql,$params,function ($stmt){
            $result = $stmt->get_result();
            return $result->fetch_object();
        });
    }

    /**
     * 执行单条sql 更新操作
     *
     * @param string $sql
     * @param array $params
     * @return int
     */
    public function update($sql, array $params)
    {
        return $this->prepare($sql,$params,function ($stmt){
            return $stmt->affected_rows;
        });
    }

    /**
     * 执行单条sql 删除操作
     *
     * @param string $sql
     * @param array $params
     * @return int
     */
    public function delete($sql, array $params)
    {
        return $this->prepare($sql,$params,function ($stmt){
            return $stmt->affected_rows;
        });
    }

    /**
     * 执行单条sql 插入操作
     *
     * @param string $sql
     * @param array $params
     * @return int  返回插入后的id
     */
    public function insert($sql, array $params)
    {
        return $this->prepare($sql,$params,function ($stmt){
            return $stmt->insert_id;
        });
    }

    /**
     * 执行事务
     *
     * @param Closure $callback
     * @return mixed
     * @throws \Exception
     * @throws \Throwable
     */
    public function transaction($callback)
    {
        $this->mysqli->autocommit(false);

        try {
            $result = $callback($this);

            $this->mysqli->commit();
        } catch (\Exception $e) {
            $this->mysqli->rollBack();

            throw $e;
        } catch (\Throwable $e) {
            $this->mysqli->rollBack();

            throw $e;
        }

        return $result;
    }


    /**
     * 获取sql语句
     *
     * @return array
     */
    public function getQueryLog()
    {
        return $this->sqls;
    }

    /**
     * 获取最后一条查询语句
     *
     * @return string|false
     */
    public function getLastSql()
    {
        return end($this->sqls);
    }

    /**
     * 析构函数  释放数据库连接
     */
    public function __destruct()
    {
        $this->mysqli->close();
    }

}