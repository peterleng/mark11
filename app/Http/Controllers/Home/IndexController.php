<?php

namespace App\Http\Controllers\Home;

use App\Http\Repositories\UserRepository;
use App\Lib\Http\Controller as BaseController;
use App\Lib\Http\Request;

/**
 * User: Peter Leng
 * DateTime: 2017/5/22 12:49
 */
class IndexController extends BaseController
{

    public function index(Request $request)
    {
        $id = $request->input('id');

        $userQuery = new UserRepository();
        $user = $userQuery->find($id);

        return view('home.index.index',['user' => $user]);
    }
}