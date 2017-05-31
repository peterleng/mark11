<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/31 14:08
 */

namespace App\Http\Middleware;


use Closure;
use Lagee\Http\Request;
use Lagee\Http\Response;
use Lagee\Middleware\Handlers\VerifyCsrfToken;
use Lagee\Session\SessionManager;

class MiddlewareManager
{
    protected $middleware = [
        'session' => SessionManager::class,
        'token' => VerifyCsrfToken::class,
    ];


    /**
     * 处理中间件
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        $response = null;
        foreach ($this->middleware as $name => $middleware) {
            $response = $this->middleware($middleware,$request,$next);
        }

        $response = $response ?: $next($request);
        return $response;
    }


    /**
     * 中间件执行
     *
     * @param string $middleware
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    protected function middleware($middleware,$request,$next)
    {
        $instance = new $middleware();
        return $instance->handle($request,$next);
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