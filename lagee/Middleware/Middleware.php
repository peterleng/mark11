<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/31 14:15
 */

namespace Lagee\Middleware;


use Closure;
use Lagee\Http\Request;

abstract class Middleware
{
    /**
     * 排除的路由
     *
     * @var array
     */
    protected $except = [];


    abstract public function handle(Request $request, Closure $next);


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