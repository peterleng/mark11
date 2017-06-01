<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/18 20:05
 */

namespace Lagee;

use Lagee\Config\Config;
use Lagee\Http\Request;
use Lagee\Http\Response;
use Lagee\Route\Router;
use Lagee\Session\SessionManager;
use Lagee\Traits\Singleton;

class Application
{
    use Singleton;

    protected $config;

    protected $router;

    protected $request;

    protected function __construct()
    {
        $this->config = Config::getInstance();
        $this->router = new Router();
        $this->request = new Request();
    }

    /**
     * 开启应用程序
     */
    public function run()
    {
        try {
            $this->router->render($this->request);
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
     * @return Response
     */
    protected function showError($e)
    {
        if ($this->request->isAjax()) {
            $info = APP_DEBUG ? $e->getMessage() : '程序出错了~~~';
            return json(['info' => $info, 'data' => [], 'status' => 'error'])->send();
        } else {
            APP_DEBUG ? var_dump($e) : print '程序出错了~~~';exit;
        }
    }

}