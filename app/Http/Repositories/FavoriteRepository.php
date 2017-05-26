<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/22 19:33
 */

namespace App\Http\Repositories;


use App\Http\Models\Favorite;


/**
 * 收藏网址
 *
 * Class FavoriteRepository
 * @package App\Http\Repositories
 */
class FavoriteRepository
{
    protected $favoriteModel;

    public function __construct()
    {
        $this->favoriteModel = new Favorite();
    }

    /**
     * 通过id查找单条记录
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->favoriteModel->find($id);
    }


    /**
     * 获取用户分类下的所有收藏网址
     *
     * @param $uid
     * @param $cid
     * @param int $limit
     * @return mixed
     */
    public function getUserCateFavorites($uid,$cid,$limit = 30)
    {
        return $this->favoriteModel->getList(['uid' => $uid,'cid'=>$cid,'status' => 1],'`orderby` DESC,`create_time` DESC',$limit);
    }
}