<?php

if (!function_exists('week')) {
    /**
     * 替换星期成中文
     *
     * @param $str
     * @return string
     */
    function week($str)
    {
        return str_replace([1, 2, 3, 4, 5, 6, 7], ['一', '二', '三', '四', '五', '六', '天'], $str);
    }
}
