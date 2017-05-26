<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/23 17:11
 */

namespace Lagee\Session;


interface SessionInterface
{
    /**
     * 获取session_id
     *
     * @return string
     */
    public function getId();

    /**
     * 设置session_id
     *
     * @param  string  $id
     * @return void
     */
    public function setId($id);

    /**
     * 开启 session
     *
     * @return bool
     */
    public function start();

    /**
     * 保存session的值
     *
     * @return bool
     */
    public function save();

    /**
     * 获取当前所有session的值
     *
     * @return array
     */
    public function all();

    /**
     * 判断当前key的session是否存在
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key);

    /**
     * 获取session值
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     *
     *
     * @param  string|array  $key
     * @param  mixed       $value
     * @return void
     */
    public function put($key, $value = null);

    /**
     * 获取CSRF的token值
     *
     * @return string
     */
    public function token();

    /**
     * 删除一个session，并返回该值
     *
     * @param  string  $key
     * @return mixed
     */
    public function remove($key);

    /**
     * 删除一个或多个session值
     *
     * @param  string|array  $keys
     * @return void
     */
    public function forget($keys);

    /**
     * 删除所有session值
     *
     * @return void
     */
    public function flush();

    /**
     * 重新生成一个session_id
     *
     * @param  bool  $destroy
     * @return bool
     */
    public function regenerate($destroy = false);

    /**
     * 判断session是否已经开启
     *
     * @return bool
     */
    public function isStarted();

    /**
     * Get the session handler instance.
     *
     * @return \SessionHandlerInterface
     */
    public function getHandler();
}