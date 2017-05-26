<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/22 12:34
 */

namespace Lagee\Http;

use Lagee\Exception\Logic\LogicException;
use Lagee\View\View;

/**
 * Controller基类
 *
 * Class Controller
 * @package Lagee\Http
 */
class Controller
{
    protected $view;

    public function __construct()
    {
        $this->view = View::getInstance();
    }


    /**
     * 404页面
     *
     * @return Response
     */
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