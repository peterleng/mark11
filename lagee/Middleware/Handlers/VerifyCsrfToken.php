<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/31 14:24
 */

namespace Lagee\Middleware\Handlers;


use Closure;
use Lagee\Exception\Program\TokenException;
use Lagee\Http\Cookie;
use Lagee\Http\Request;
use Lagee\Http\Response;
use Lagee\Middleware\Middleware as BaseMiddleware;

class VerifyCsrfToken extends BaseMiddleware
{
    /**
     * 排除的路由
     *
     * @var array
     */
    protected $except = [

    ];

    /**
     * 中间件前置方法
     *
     * @param Request $request
     * @return bool
     * @throws TokenException
     */
    public function before(Request $request)
    {
        if($request->isMethod('get') || $this->shouldRoutePass($request) || $this->tokensMatch($request)){
            return true;
        }

        throw new TokenException('CSRF token验证失败');
    }

    /**
     * 中间件后置方法
     *
     * @param Response $response
     * @return Response
     */
    public function after(Response $response)
    {
        return $this->addCookieToResponse($response);
    }


    /**
     * token匹配
     *
     * @param Request $request
     * @return bool
     */
    protected function tokensMatch($request)
    {
        $sessionToken = $request->session()->token();

        $token = $request->input('_token') ?: $request->server('X-CSRF-TOKEN');

        $token = empty($token) ? $request->server('X-XSRF-TOKEN') : $token;

        if (! is_string($sessionToken) || ! is_string($token)) {
            return false;
        }

        return $sessionToken == $token;
    }


    /**
     * 添加CSRF token到响应cookie中
     *
     * @param Response $response
     * @return mixed
     */
    protected function addCookieToResponse($response)
    {
        $config = config('session');

        $response->withCookie(
            new Cookie(
                'XSRF-TOKEN', session()->token(), time() + 60 * 120,
                $config['path'], empty($config['domain']) ? config('app.domain') : $config['domain'], $config['secure'], false
            )
        );

        return $response;
    }
}