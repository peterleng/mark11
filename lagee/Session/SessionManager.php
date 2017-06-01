<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/23 17:11
 */

namespace Lagee\Session;

use Closure;
use Lagee\Http\Cookie;
use Lagee\Http\Request;
use Lagee\Http\Response;
use Lagee\Http\Session;
use Lagee\Middleware\Middleware as BaseMiddleware;

class SessionManager extends BaseMiddleware
{
    protected static $driverHandler = 'Lagee\\Session\\SessionHandlers\\';

    protected $started = false;

    protected $config;

    /**
     * 排除的路由
     *
     * @var array
     */
    protected $except = [

    ];


    public function __construct()
    {
        $this->config = config('session');
    }

    /**
     * 中间件前置方法
     *
     * @param Request $request
     * @return bool
     */
    public function before(Request $request)
    {
        if($this->shouldRoutePass($request)){
            return true;
        }

        $this->startSession($request);
        return true;
    }

    /**
     * 中间件后置方法
     *
     * @param Response $response
     * @return Response
     */
    public function after(Response $response)
    {
        $session = Session::getInstance();

        return $this->terminate($response,$session);
    }

    /**
     * 开启session
     *
     * @param Request $request
     * @return Session
     */
    protected function startSession(Request $request)
    {
        $this->started = true;

        $session = null;
        if($this->sessionConfigured()){
            $session = $this->getSession($request);
            $session->start();

            $request->setSession($session);
        }

        return $session;
    }

    /**
     * 收集垃圾
     */
    protected function collectGarbage(SessionInterface $session)
    {
        //百分之一的概率清除过期session
        if(random_int(1,100) <= 2){
            $session->getHandler()->gc($this->config['lifetime']);
        }
    }

    /**
     * 结束session 写入cookie保持session状态
     *
     * @param Response $response
     * @param Session $session
     * @return Response
     */
    protected function terminate($response,Session $session)
    {
        $this->collectGarbage($session);

        $session->save();

        $response->withCookie(
            new Cookie($session->getName(),$session->getId(),time()+$this->config['lifetime'],$this->config['path'],
                empty($this->config['domain']) ? config('app.domain') : $this->config['domain'],
                $this->config['secure'],$this->config['http_only'])
        );
        return $response;
    }

    /**
     * 检查session配置
     *
     * @return bool
     */
    protected function sessionConfigured()
    {
        return isset($this->config['driver']) && isset($this->config['cookie']);
    }

    /**
     * @param Request $request
     * @return Session
     */
    protected function getSession(Request $request)
    {
        $session = $this->buildSession();
        $session->setId($request->cookie($session->getName()));
        return $session;
    }

    /**
     * 获取session处理器
     *
     * @return Session
     */
    protected function buildSession()
    {
        $cookie = config('session.cookie');

        return Session::getInstance()->initHandler($cookie,self::callCustomHandler($this->config['driver']));
    }

    /**
     * 使用自定义session处理器
     *
     * @param string $driver
     * @return \SessionHandlerInterface
     */
    protected static function callCustomHandler($driver)
    {
        $sessionHandler = self::$driverHandler.ucfirst($driver).'SessionHandler';
        return new $sessionHandler;
    }

}