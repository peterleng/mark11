<?php

namespace App\Http\Controllers\Home;

use App\Http\Repositories\UserRepository;
use App\Lib\Http\Controller as BaseController;
use App\Lib\Http\Request;
use App\Lib\Util\ImageCode;

/**
 * User: Peter Leng
 * DateTime: 2017/5/22 12:49
 */
class PublicController extends BaseController
{
    public function imgCode(Request $request)
    {
        $imageCode = new ImageCode();
        $imageCode->show();
    }

    public function register(Request $request)
    {
        return view('home.user.register',[]);
    }
}