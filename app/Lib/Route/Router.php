<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/22 10:41
 */

namespace App\Lib\Route;


use App\Lib\Http\Request;

class Router
{

    /**
     * 导向controller中执行
     *
     * @param Request $request
     */
    public function render($request)
    {
        $path = trim($request->path(), '/');
        $path_arr = empty($path) ? [] : explode('/', $path);

        //TODO  子域名

        $group = empty($path_arr[0]) || count($path_arr) < 3 ? config('app.default_group') : $path_arr[0];
        $controller = isset($path_arr[1]) ? $path_arr[1] : 'index';
        $action = isset($path_arr[2]) ? $path_arr[2] : 'index';

        $this->dispatch($group, $controller, $action, [$request]);
    }


    protected function dispatch($group, $controller, $method, $params)
    {
        $controller = 'App\\Http\\Controllers\\' . ucfirst($group) . '\\' . ucfirst($controller) . 'Controller';
        call_user_func_array([new $controller(), strtolower($method)], $params);
    }
}