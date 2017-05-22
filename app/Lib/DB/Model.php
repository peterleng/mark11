<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/20 16:56
 */

namespace App\Lib\DB;

use Closure;

/**
 * Class Model
 *
 * @method mixed find(int $id)
 * @method mixed findBy(string|array $where)
 * @method mixed all(string $field,string $order)
 * @method mixed getList(string|array $where,string $order,int $limit,string $field)
 * @method int insert(array $values)
 * @method int update(string|array $where,array $values,int $limit)
 * @method int delete(string|array $where,int $limit)
 * @method int deleteOne(int $id)
 * @method mixed transaction(Closure $callback)
 * @method array getLastSql()
 * @method array sql()
 *
 * @package App\Lib\DB
 */
class Model
{
    protected $table = '';

    protected $primary = 'id';

    protected $prefix = '';

    protected $sqlBuilder;

    /**
     * 获取表名
     *
     * @return string
     */
    public function getTable()
    {
        if (isset($this->table)) {
            return $this->table;
        }

        return $this->getPrefix() . strtolower(get_class($this));
    }


    /**
     * 获取表名前缀
     *
     * @return mixed|string
     */
    public function getPrefix()
    {
        if (!isset($this->prefix)) {
            $this->prefix = config('db.prefix');
        }

        return $this->prefix;
    }

    /**
     * 获取主键字段名
     *
     * @return string
     */
    public function getPrimary()
    {
        return $this->primary;
    }


    /**
     * 获取查询单例
     *
     * @return SqlBuilder
     */
    public function newQuery()
    {
        if($this->sqlBuilder == null){
            $this->sqlBuilder = new SqlBuilder($this);
        }
        return $this->sqlBuilder;
    }


    /**
     * sql执行方法
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        $query = $this->newQuery();

        return call_user_func_array([$query, $method], $params);
    }
}