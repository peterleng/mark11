<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/18 20:05
 */

namespace App\Lib;

use App\Lib\Config\Config;
use App\Lib\Http\Request;
use App\Lib\Route\Router;
use App\Lib\Session\SessionManager;
use App\Lib\Traits\Singleton;

class Application
{
    use Singleton;

    protected $config;

    protected $router;

    protected $request;

    protected $sessionManager;

    protected function __construct()
    {
        $this->config = Config::getInstance();
        $this->router = new Router();
        $this->request = new Request();
        $this->sessionManager = new SessionManager();
    }

    /**
     * 开启应用程序
     */
    public function run()
    {
        try {
            $session = $this->sessionManager->startSession($this->request);
            $response = $this->router->render($this->request);
            $this->sessionManager->destroySession($session);
            $response->send();
        } catch (\Exception $e) {
            $this->showError($e);
        } catch (\Throwable $t) {
            $this->showError($t);
        }
    }


    /**
     * 显示错误信息
     *
     * @param $e
     */
    protected function showError($e)
    {
        if (APP_DEBUG) {
            var_dump($e);
        } else {
            echo '程序出错了~~~';
        }
        exit;
    }

}