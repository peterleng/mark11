<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/31 14:15
 */

namespace Lagee\Middleware;


use Closure;
use Lagee\Http\Request;
use Lagee\Http\Response;

abstract class Middleware
{
    /**
     * 排除的路由
     *
     * @var array
     */
    protected $except = [];


    /**
     * 中间件前置方法
     *
     * @param Request $request
     * @return bool
     */
    abstract public function before(Request $request);

    /**
     * 中间件后置方法
     *
     * @param Response $response
     * @return Response
     */
    abstract public function after(Response $response);


    /**
     * 此次访问地址的路由是否在except中有匹配
     *
     * @param Request $request
     * @return bool
     */
    protected function shouldRoutePass(Request $request)
    {
        $path = $request->path();

        foreach ($this->except as $item) {
            $item = $item !== '/' ? trim($item, '/') : $item;

            if (strpos($path, $item) !== false) {
                return true;
            }
        }

        return false;
    }
}