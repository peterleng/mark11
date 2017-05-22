<?php

namespace App\Http\Controllers\Home;

use App\Lib\Http\Controller as BaseController;
use App\Lib\Http\Request;

/**
 * User: Peter Leng
 * DateTime: 2017/5/22 12:49
 */
class IndexController extends BaseController
{

    public function index(Request $request)
    {


        return view('home.index.index',['abc' => 123456]);
    }
}