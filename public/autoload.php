<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/23 14:32
 */

//$include_path = [dirname(dirname(__FILE__)).'/app'];
/*$include_path = dirname(dirname(__FILE__)).'/app';
set_include_path( $include_path );
spl_autoload_register();*/


if (!function_exists('auto_load')) {

    /**
     * 自动加载所需的php文件
     *
     * @param $class
     */
    function auto_load($class)
    {
        require_once root_path().DIRECTORY_SEPARATOR.str_replace('\\','/',lcfirst($class)).'.php';
    }
}

spl_autoload_register( 'auto_load' );