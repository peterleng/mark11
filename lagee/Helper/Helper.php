<?php

if (!function_exists('app')) {
    /**
     * 获取app应用实例
     *
     * @return \Lagee\Application
     */
    function app()
    {
        return Lagee\Application::getInstance();
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
        return dirname(dirname(dirname(__FILE__))) . $path;
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
        return dirname(dirname(dirname(__FILE__))). DIRECTORY_SEPARATOR . 'app'. DIRECTORY_SEPARATOR . $path;
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
        return Lagee\Config\Config::getInstance()->get($key,$default);
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
        return app_path('Config') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
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
        return app_path('Cache') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}


if (!function_exists('view')) {
    /**
     * 显示模板
     *
     * @param string $view
     * @param array $vars
     * @return \Lagee\Http\Response
     */
    function view($view, array $vars = [])
    {
        return Lagee\View\View::getInstance()->display($view, $vars);
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
        return app_path('views') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('json')) {
    /**
     * 返回json response
     *
     * @param array $data
     * @return \Lagee\Http\JsonResponse
     */
    function json(array $data)
    {
        return new \Lagee\Http\JsonResponse($data);
    }
}

if (!function_exists('jsonp')) {
    /**
     * 返回json response 带有callback
     *
     * @param array $data
     * @param string $callback
     * @return \Lagee\Http\JsonResponse
     */
    function jsonp(array $data,$callback = null)
    {
        return (new \Lagee\Http\JsonResponse($data))->setCallback($callback);
    }
}


if (!function_exists('cookie')) {
    /**
     * 设置 读取 cookie
     *
     * @param string $name
     * @param string $value
     * @param int $expire
     * @return bool
     */
    function cookie($name, $value = '', $expire = 1800)
    {
        if (is_null($value)) {
            return setcookie($name, $value, time() - 3600);
        }

        if (empty($value)) {
            return $_COOKIE[$name];
        }

        return setcookie($name, $value, time() + $expire,config('session.path'),
            empty(config('session.domain')) ? config('app.domain') : config('session.domain'),
            config('session.secure'),config('session.http_only'));
    }
}

if (!function_exists('session')) {
    /**
     * 获取session对象
     *
     * @return \Lagee\Http\Session
     */
    function session()
    {
        return Lagee\Http\Session::getInstance();
    }
}

if (!function_exists('session_get')) {
    /**
     * 获取session的值
     *
     * @param string $key
     * @param string $default
     * @return mixed
     */
    function session_get($key, $default = null)
    {
        return Lagee\Http\Session::getInstance()->get($key, $default);
    }
}

if (!function_exists('session_set')) {

    /**
     * 设置session值
     *
     * @param string $key
     * @param $value
     * @return void
     */
    function session_set($key, $value)
    {
        Lagee\Http\Session::getInstance()->put($key, $value);
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
        return Lagee\Route\Router::getRoute($path, $params);
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
        return str_replace([1, 2, 3, 4, 5, 6, 7], ['一', '二', '三', '四', '五', '六', '天'], $str);
    }
}
