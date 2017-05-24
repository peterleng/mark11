<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/23 22:20
 */
return [
    'driver' => 'database',//session驱动类型
    'lifetime' => 3600,//存活时间 秒
    'cookie' => 'mark11_session',//session读取cookie的名称
    'path' => '/',
    'domain' => null,
    'secure' =>false,
    'http_only' => true,
];