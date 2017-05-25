<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/22 19:33
 */

namespace App\Http\Repositories;


use App\Http\Models\User;
use App\Lib\Exception\Logic\BusinessException;

/**
 * 用户仓库
 *
 * Class UserRepository
 * @package App\Http\Repositories
 */
class UserRepository
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }


    /**
     * 通过id查找单条记录
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->userModel->find($id);
    }

    /**
     * 通过手机号查询用户
     *
     * @param $phone
     * @return mixed
     */
    public function findByPhone($phone)
    {
        return $this->userModel->findBy(['phone' => $phone]);
    }

    /**
     * 注册
     *
     * 通过手机 密码 注册用户
     *
     * @param string $phone
     * @param string $passwd
     * @return mixed
     * @throws BusinessException
     */
    public function registerBy($phone, $passwd)
    {
        $result = $this->userModel->insert(['phone' => $phone,'passwd'=>$passwd,'status'=>1]);
        if (!$result) throw new BusinessException('注册失败，请重试');
        return $this->findByPhone($phone);
    }

    /**
     * 登录
     *
     * 通过手机号密码登录
     *
     * @return mixed
     */
    public function login($phone,$passwd)
    {
        $user = $this->userModel->findBy(['phone'=>$phone]);
        //password_verify()
        return $user;
    }

}