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

    /*
     * 登录页
     */
    public function login(Request $request)
    {
        return view('home.user.login',[]);
    }

    /**
     * 执行登录
     */
    public function do_login(Request $request)
    {
        $phone = $request->input('phone');
        $passwd = $request->input('passwd');

        $query = new UserRepository();
        $user = $query->findByPhone($phone);

        if(!empty($user) && password_verify($user->passwd,$passwd)){
            unset($user->passwd);
            session_set('user',$user);
        }

        //TODO AJAX
    }
    

    /*
     * 注册页
     */
    public function register(Request $request)
    {
        return view('home.user.register',[]);
    }


    /*
     * 执行注册
     */
    public function do_register(Request $request)
    {
        $phone = $request->input('phone');
        $passwd = $request->input('passwd');
        $imgcode = $request->input('imgcode');
        //TODO 验证码

        //TODO 每次实例化？
        $query = new UserRepository();
        $user = $query->registerBy($phone,$passwd);

        //TODO ajax
        
    }
}