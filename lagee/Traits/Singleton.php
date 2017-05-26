<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/22 14:44
 */

namespace Lagee\Traits;


/**
 * 单例模式
 *
 * Class Singleton
 * @package Lagee\Traits
 */
trait Singleton
{
    protected static $instance;
    protected function __construct(){}
    protected function __clone(){}

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}