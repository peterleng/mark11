<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/22 19:33
 */

namespace App\Http\Repositories;


use App\Http\Models\FavoriteCate;

/**
 * 收藏分类
 *
 * Class FavoriteCateRepository
 * @package App\Http\Repositories
 */
class FavoriteCateRepository
{
    protected $favoriteCateModel;

    public function __construct()
    {
        $this->favoriteCateModel = new FavoriteCate();
    }

    /**
     * 通过id查找单条记录
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->favoriteCateModel->find($id);
    }

    /**
     * 获取用户的收藏分类
     *
     * @param $uid
     * @param $limit
     * @return mixed
     */
    public function getUserCatesList($uid,$limit = 5)
    {
        return $this->favoriteCateModel->getList(['uid' => $uid],'`orderby` DESC,`create_time` DESC',$limit);
    }


    /**
     * 获取用户首页tab切换的收藏网址显示内容
     *
     * @param $uid
     * @return array
     */
    public function getUserTabsFavorites($uid)
    {
        $result = [];

        $cates = $this->getUserCatesList($uid,8);

        $favoriteModel = new FavoriteRepository();
        foreach ($cates as $cate) {
            $temp['name'] = $cate->name;
            $temp['ename'] = $cate->ename;
            $temp['sites'] = $favoriteModel->getUserCateFavorites($uid,$cate->id);
            $result[] = $temp;
        }
        return $result;
    }
}