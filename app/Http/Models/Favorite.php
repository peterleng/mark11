<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/22 18:48
 */

namespace App\Http\Models;


use Lagee\DB\Model;

class Favorite extends Model
{
    protected $table = 'favorite';

    protected $timestamps = true;
}