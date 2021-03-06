<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/24 23:44
 */

namespace Lagee\Traits;


use Lagee\Http\JsonResponse;

trait AjaxTraits
{

    /**
     * ajax成功返回
     *
     * @param string $info
     * @param string $data
     * @return JsonResponse
     */
    public function ajaxSuccess($info = '',$data= '')
    {
        return json(['info'=>$info,'data'=>$data,'status'=>'success']);
    }

    /**
     * ajax错误返回
     *
     * @param string $info
     * @param string $data
     * @return JsonResponse
     */
    public function ajaxError($info = '',$data= '')
    {
        return json(['info'=>$info,'data'=>$data,'status'=>'error']);
    }

    /**
     * jsonp请求响应
     * @param $callback
     * @param $status
     * @param null $info
     * @param null $data
     * @return JsonResponse
     */
    protected function ajaxJsonp($callback,$status, $info = null, $data = null)
    {
        return json(['info'=>$info,'data'=>$data,'status'=>$status])->setCallback($callback);
    }
}