<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/22 12:34
 */

namespace App\Lib\Http;

use App\Lib\Exception\Logic\LogicException;
use App\Lib\View\View;

/**
 * Controller基类
 *
 * Class Controller
 * @package App\Lib\Http
 */
class Controller
{
    protected $view;

    public function __construct()
    {
        $this->view = View::getInstance();
    }


    public function notFound()
    {
        return view('errors.404');
    }


    /**
     * 方法不存在时报错
     *
     * @param string $method
     * @param $params
     * @throws LogicException
     */
    public function __call($method,$params)
    {
        throw new LogicException(get_class($this).'中找不到方法：'.$method);
    }
}