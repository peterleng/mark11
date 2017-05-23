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
        $fullpath = $request->fullUrl();

        $path_arr = empty($path) ? [] : explode('/', $path);

        //TODO  子域名

        $route = $this->buildRoute($path_arr);

        $this->dispatch($route[0], $route[1], $route[2], [$request]);
    }


    /**
     * 定位到对应的 Controller
     *
     * @param $group
     * @param $controller
     * @param $method
     * @param $params
     */
    protected function dispatch($group, $controller, $method, $params)
    {
        $controller = 'App\\Http\\Controllers\\' . ucfirst($group) . '\\' . ucfirst($controller) . 'Controller';
        call_user_func_array([new $controller(), strtolower($method)], $params);
    }


    /**
     * 构建路由
     *
     * @param array $pathArr
     * @return array
     */
    protected function buildRoute(array $pathArr)
    {
        $group = empty($pathArr[0]) || count($pathArr) < 3 ? config('app.default_group') : $pathArr[0];
        $controller = isset($pathArr[1]) ? $pathArr[1] : 'index';
        $action = isset($pathArr[2]) ? $pathArr[2] : 'index';

        return [$group, $controller, $action];
    }


    /**
     * 通过路径构建一个路由
     *
     * @param string $path   例如：home.user.login
     * @param array $params
     * @return string
     */
    public function getRoute($path = '', $params = [])
    {
        return '/' . implode('/', $this->buildRoute(explode('.', $path))) . (empty($params) ? '' : '?' . implode('&', $params));
    }
}