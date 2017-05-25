<?php

namespace App\Http\Controllers\Home;

use App\Http\Repositories\UserRepository;
use App\Lib\Exception\Logic\BusinessException;
use App\Lib\Exception\Logic\LogicException;
use App\Lib\Http\Controller as BaseController;
use App\Lib\Http\Request;
use App\Lib\Traits\AjaxTraits;

/**
 * User: Peter Leng
 * DateTime: 2017/5/22 12:49
 */
class UserController extends BaseController
{
    use AjaxTraits;

    /**
     * 登录页
     *
     * @param Request $request
     * @return \App\Lib\Http\Response
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
        try{
            if(!$request->isAjax()) throw new BusinessException('请求方式错误');

            $times = session_get('login_times',1);
            if($times > 3){
                $this->verifyImgCode($request->input('imgcode'));
            }

            $phone = $request->input('phone');
            $passwd = $request->input('passwd');

            $query = new UserRepository();
            $user = $query->login($phone,$passwd);

            $this->remember_login($user);

            session_set('login_times',null);

            return $this->ajaxSuccess('登录成功',$user);
        }catch (LogicException $e){
            $code = $e->getCode();
            if($code > 500){
                $times = session_get('login_times',1);
                session_set('login_times',$times+1);
            }

            return $this->ajaxError($e->getMessage(),['code' =>$code]);
        }catch (\Exception $e){
            return $this->ajaxError('登录失败，请重试');
        }
    }

    /**
     * 注册页
     *
     * @param Request $request
     * @return \App\Lib\Http\Response
     */
    public function register(Request $request)
    {
        return view('home.user.register',[]);
    }


    /**
     * 执行注册
     *
     * @param Request $request
     * @return \App\Lib\Http\JsonResponse
     */
    public function do_register(Request $request)
    {
        try{
            if(!$request->isAjax()) throw new BusinessException('请求方式错误');
            $this->verifyRegister($request);

            $phone = $request->input('phone');
            $passwd = $request->input('passwd');

            $query = new UserRepository();
            $query->registerBy($phone,$passwd);

            $user = $query->login($phone,$passwd);
            $this->remember_login($user);

            return $this->ajaxSuccess('注册成功',$user);
        }catch (LogicException $e){
            return $this->ajaxError($e->getMessage());
        }catch (\Exception $e){
            return $this->ajaxError('注册失败，请重试');
        }
    }

    /*
     * 记住登录状态
     */
    protected function remember_login($user)
    {
        unset($user->passwd);
        session_set('user',$user);
    }

    /*
     * 验证注册内容
     */
    protected function verifyRegister(Request $request)
    {
        $this->verifyImgCode($request->input('imgcode'));

        if(!preg_match('/^1[3578]\d{9}$/i',$request->input('phone'))){
            throw new BusinessException('手机格式错误');
        }
    }

    /*
     * 验证码校验
     */
    protected function verifyImgCode($imgcode)
    {
        if(strcasecmp($imgcode, session_get('_imgCode')) !== 0){
            throw new BusinessException('验证码填写错误',505);
        }
    }


    /**
     * 退出登录
     *
     * @param Request $request
     * @return \App\Lib\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try{
            if(!$request->isAjax()) throw new BusinessException('请求方式错误');

            session()->flush();

            return $this->ajaxSuccess('退出成功',[]);
        }catch (\Exception $e){
            return $this->ajaxError('退出成功，请重试');
        }
    }
}