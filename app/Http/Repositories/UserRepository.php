<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/22 19:33
 */

namespace App\Http\Repositories;


use App\Http\Models\User;

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
    
}