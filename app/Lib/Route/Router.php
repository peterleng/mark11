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
        $path_tmp = explode('/', $path);
        $idx = 0;

        $group = $controller = $action = '';

        //子域名
        $host = $request->getHost();
        $subdomains = config('app.sub_domain');
        $sub = explode('.', $host)[0];
        if (array_key_exists($sub, $subdomains)) {
            $group = str_replace('/', '\\', $subdomains[$sub]);
        }else{
            $group = empty($path_tmp[$idx]) ? config('app.default_group') : $path_tmp[$idx];
            $idx++;
        }
        $controller = empty($path_tmp[$idx]) ? 'index' : $path_tmp[$idx];
        $idx++;
        $action = empty($path_tmp[$idx]) ? 'index' : $path_tmp[$idx];

        $this->dispatch($group, $controller, $action, [$request]);
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
     * 通过路径构建一个路由
     *
     * @param string $path 例如：home.user.login
     * @param array $params
     * @return string
     */
    public function getRoute($path = '', $params = [])
    {
        return '/' . implode('/', explode('.', $path)) . (empty($params) ? '' : '?' . implode('&', $params));
    }
}