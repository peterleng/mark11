<?php

namespace App\Http\Controllers\Home;


use App\Http\Repositories\CookieFavoriteRepository;
use App\Http\Repositories\FavoriteCateRepository;
use Lagee\Http\Controller as BaseController;
use Lagee\Http\Request;
use Lagee\Util\Str;


/**
 * User: Peter Leng
 * DateTime: 2017/5/22 12:49
 */
class IndexController extends BaseController
{

    public function index(Request $request)
    {
        session_set('mark11_favorite',1);

        $tabs = [];
        $user = session_get('user');
        if($user){
            //登录
            $favCateModel = new FavoriteCateRepository();
            $tabs = $favCateModel->getUserTabsFavorites($user->id);
        }else{
            //未登录
            $cookie = cookie('mark11_favorite');
            if(empty($cookie)){
                $cookie = $this->generateCookie();
                cookie('mark11_favorite',$cookie,3600*24*30);
            }else{
                cookie('mark11_favorite',$cookie,3600*24*30);
            }

            $favCookieModel = new CookieFavoriteRepository();
            $tabs = $favCookieModel->getFavorites($cookie);
        }

        return view('home.index.index',[
            'tabs' => $tabs
        ]);
    }


    /**
     * 生成随机cookie值
     *
     * @return string
     */
    protected function generateCookie()
    {
        return Str::randNumStr(40);
    }
}