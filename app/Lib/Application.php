<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/18 20:05
 */

namespace App\Lib;

use App\Lib\Config\Config;
use App\Lib\Http\Request;
use App\Lib\Route\Router;
use App\Lib\Traits\Singleton;

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
        try{
            $this->router->render($this->request);
        }catch (\Exception $e){
            $this->showError($e);
        }catch (\Throwable $t){
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
        if(APP_DEBUG){
            var_dump($e);
        }else{
            echo '程序出错了~~~';
        }
        exit;
    }

    /**
     * 获取配置项
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return $this->config->get($key,$default);
    }

}