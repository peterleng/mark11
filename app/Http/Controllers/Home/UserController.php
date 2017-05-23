<?php

namespace App\Http\Controllers\Home;

use App\Http\Repositories\UserRepository;
use App\Lib\Http\Controller as BaseController;
use App\Lib\Http\Request;

/**
 * User: Peter Leng
 * DateTime: 2017/5/22 12:49
 */
class UserController extends BaseController
{

    public function login(Request $request)
    {
        return view('home.user.login',[]);
    }

    public function register(Request $request)
    {
        return view('home.user.login',[]);
    }
}