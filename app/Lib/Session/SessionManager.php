<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/23 17:11
 */

namespace App\Lib\Session;

use App\Lib\Http\Request;
use App\Lib\Http\Session;

class SessionManager
{
    protected static $driverHandler = 'App\\Lib\\Session\\SessionHandlers\\';

    protected $started = false;

    protected $config;

    public function __construct()
    {
        $this->config = config('session');
    }

    /**
     * 开启session
     *
     * @param Request $request
     * @return Session
     */
    public function startSession(Request $request)
    {
        $this->started = true;

        $session = null;
        if($this->sessionConfigured()){
            $session = $this->getSession($request);
            $session->start();

            //百分之一的概率清除过期session
            if(random_int(1,100) <= 2){
                $session->getHandler()->gc($this->config['lifetime']);
            }
        }

        return $session;
    }


    /**
     * 结束session 写入cookie保持session状态
     *
     * @param Session $session
     */
    public function destroySession(Session $session)
    {
        $session->save();

        setcookie($session->getName(),$session->getId(),time()+$this->config['lifetime'],$this->config['path'],
                            empty($this->config['domain']) ? config('app.domain') : $this->config['domain'],
                            $this->config['secure'],$this->config['http_only']);

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