<?php

namespace App\Http\Controllers\Home;


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
        //var_dump(Session::getInstance());

        //session_set('name','hello world');
        //var_dump(session()->all());
        //echo session_get('name');
        /*$r = new UserRepository();
        $user = $r->find(1);
        print_r($user);exit;*/

        return view('home.index.index',[]);
    }
}