<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/22 19:33
 */

namespace App\Http\Repositories;


use App\Http\Models\CookieFavorite;


/**
 * 未登录用户收藏网址
 *
 * Class FavoriteRepository
 * @package App\Http\Repositories
 */
class CookieFavoriteRepository
{
    protected $cookieFavoriteModel;

    public function __construct()
    {
        $this->cookieFavoriteModel = new CookieFavorite();
    }

    /**
     * 通过id查找单条记录
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->cookieFavoriteModel->find($id);
    }


    /**
     * 获取未登录用户的常用网址
     *
     * @param $cookie
     * @param int $limit
     * @return mixed
     */
    public function getFavorites($cookie,$limit = 30)
    {
        $result = [];
        $favs = $this->cookieFavoriteModel->getList(['cookie' => $cookie],'`hits` DESC,`create_time` DESC',$limit);
        if(empty($favs)){
            //显示最常用的一些网站
        }

        $result[] = ['name' => '我的常用网址','ename' => '__favorite_','sites' => $favs];
        return $result;
    }
}