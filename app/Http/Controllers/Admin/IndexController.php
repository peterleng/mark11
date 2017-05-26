<?php

namespace App\Http\Controllers\Admin;


use Lagee\Http\Controller as BaseController;
use Lagee\Http\Request;

/**
 * User: Peter Leng
 * DateTime: 2017/5/22 12:49
 */
class IndexController extends BaseController
{
    public function index(Request $request)
    {
        return view('admin.index',[]);
    }
}