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
        //var_dump(Session::getInstance());

        //session_set('name','hello world');
        //echo session('name');exit;
        /*$r = new UserRepository();
        $user = $r->find(1);
        print_r($user);exit;*/

        return view('home.index.index',[]);
    }
}