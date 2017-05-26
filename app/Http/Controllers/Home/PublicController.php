<?php

namespace App\Http\Controllers\Home;

use Lagee\Http\Controller as BaseController;
use Lagee\Http\Request;
use Lagee\Util\ImageCode;

/**
 * User: Peter Leng
 * DateTime: 2017/5/22 12:49
 */
class PublicController extends BaseController
{
    /**
     * 验证码
     *
     * @param Request $request
     */
    public function imgCode(Request $request)
    {
        $w = $request->input('w',80);
        $h = $request->input('h',30);
        $l = $request->input('l',4);

        $imageCode = new ImageCode();
        $imageCode->build($w,$h,$l);
        session_set('_imgCode',$imageCode->getCode());
        $imageCode->show();
    }
}