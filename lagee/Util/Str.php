<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/23 19:32
 */

namespace Lagee\Util;


class Str
{

    /**
     * 随机字符串 只包含数字和字母
     *
     * @param int $length
     * @return string
     */
    public static function randNumStr($length = 16)
    {
        $rands = [[48, 57], [65, 90], [97, 122]];

        $string = '';
        while (strlen($string) < $length) {
            $idx = mt_rand(0, 2);
            $string .= chr(mt_rand($rands[$idx][0], $rands[$idx][1]));
        }
        return $string;
    }


    /**
     * 随机字符串 包含特殊字符
     *
     * @param int $length
     * @return string
     */
    public static function randStr($length = 16)
    {
        $string = '';
        while (strlen($string) < $length) {
            $string .= chr(mt_rand(33, 126));
        }
        return $string;
    }

}