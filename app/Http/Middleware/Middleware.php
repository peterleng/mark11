<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/31 14:08
 */

namespace App\Http\Middleware;


use Closure;
use Lagee\Http\Request;
use Lagee\Http\Response;
use Lagee\Middleware\MiddlewareManager;


class Middleware
{
    protected $manager;

    protected $middleware = [
        'session' => \Lagee\Session\SessionManager::class,
        'token' => \Lagee\Middleware\Handlers\VerifyCsrfToken::class,
    ];

    public function __construct()
    {
        $this->manager = new MiddlewareManager($this->middleware);
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
        return $this->manager->handle($request,$next);
    }


    /**
     * 获取全部中间件
     *
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }


    /**
     * 新增中间件
     *
     * @param array $middleware
     */
    public function put(array $middleware)
    {
        $this->middleware = array_merge($this->middleware,$middleware);
    }
}