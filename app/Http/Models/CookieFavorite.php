<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/22 18:48
 */

namespace App\Http\Models;


use Lagee\DB\Model;

class CookieFavorite extends Model
{
    protected $table = 'cookie_favorite';

    protected $timestamps = true;

    protected $create_time = null;//无修改时间
}