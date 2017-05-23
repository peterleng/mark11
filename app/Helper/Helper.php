<?php

if (!function_exists('app')) {
    /**
     * 获取app应用实例
     *
     * @return \App\Lib\Application
     */
    function app()
    {
        return \App\Lib\Application::getInstance();
    }
}

if (!function_exists('root_path')) {
    /**
     * root文件夹路径
     *
     * @param string $path
     * @return string
     */
    function root_path($path = '')
    {
        return dirname(dirname(dirname(__FILE__))).$path;
    }
}


if (!function_exists('app_path')) {
    /**
     * app文件夹路径
     *
     * @param string $path
     * @return string
     */
    function app_path($path = '')
    {
        return dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.$path;
    }
}


if (!function_exists('config')) {
    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    function config($key, $default = null)
    {
        return app()->config($key,$default);
    }
}


if (!function_exists('config_path')) {
    /**
     * app/Config目录的路径
     *
     * @param string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app_path('Config').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}


if (!function_exists('cache_path')) {
    /**
     * app/Cache
     *
     * @param string $path
     * @return string
     */
    function cache_path($path = '')
    {
        return app_path('Cache').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}


if (!function_exists('view')) {
    /**
     * 显示模板
     *
     * @param string $view
     * @param array $vars
     * @return \App\Lib\Http\Response
     */
    function view($view,array $vars)
    {
        return App\Lib\View\View::getInstance()->display($view,$vars);
    }
}

if (!function_exists('view_path')) {
    /**
     * 模板路径
     *
     * @param string $path
     * @return string
     */
    function view_path($path = '')
    {
        return app_path('views').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (!function_exists('cookie')) {
    /**
     * 设置 读取 cookie
     *
     * @param $name
     * @param null $value
     * @return bool|mixed
     */
    function cookie($name, $value = null)
    {
        if(is_null($value)){
            return setcookie($name,$value,time()-3600);
        }

        if(empty($value)){
            return $_COOKIE[$name];
        }

        return setcookie($name,$value);
    }
}

if (!function_exists('route')) {
    /**
     * 路由
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    function route($path, $params = [])
    {
        return (new \App\Lib\Route\Router())->getRoute($path,$params);
    }
}


if (!function_exists('week')) {
    /**
     * 替换星期成中文
     *
     * @param $str
     * @return string
     */
    function week($str)
    {
        return str_replace([1,2,3,4,5,6,7],['一','二','三','四','五','六','天'],$str);
    }
}


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