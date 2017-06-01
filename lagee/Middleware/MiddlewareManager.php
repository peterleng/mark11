<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/31 14:08
 */

namespace Lagee\Middleware;


use Closure;
use Lagee\Exception\Program\MiddlewareException;
use Lagee\Http\Request;
use Lagee\Http\Response;
use Lagee\Middleware\Handlers\VerifyCsrfToken;
use Lagee\Session\SessionManager;

class MiddlewareManager
{
    protected $middlewareClass = [];

    protected $middleware;

    public function __construct($middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * 处理中间件
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        $this->before($request);
        $response = $next($request);
        return $this->after($response);
    }

    /**
     * 中间件前置方法
     *
     * @param $request
     * @throws MiddlewareException
     */
    protected function before($request)
    {
        foreach ($this->middleware as $name => $middleware) {

            $middlewareClass = $this->getMiddlewareClass($name);
            if(!$middlewareClass->before($request)){
                throw new MiddlewareException();
            }
        }
    }

    /**
     * 中间件后置方法
     *
     * @param Response $response
     * @return Response
     */
    protected function after(Response $response)
    {
        foreach ($this->middleware as $name => $middleware) {
            $middlewareClass = $this->getMiddlewareClass($name);

            $response = $middlewareClass->after($response);
        }
        return $response;
    }


    /**
     * 获取中间件实例
     *
     * @param $name
     * @return Middleware
     */
    protected function getMiddlewareClass($name)
    {
        if(!isset($this->middlewareClass[$name])) {
            $class = $this->middleware[$name];
            $this->middlewareClass[$name] = new $class;
        }
        return $this->middlewareClass[$name];
    }
}