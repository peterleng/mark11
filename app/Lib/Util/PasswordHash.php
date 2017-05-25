<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/25 10:45
 */

namespace App\Lib\Util;


use RuntimeException;

class PasswordHash
{
    const COST = 10;

    /**
     * 生成hash串
     *
     * @param string $password 原生密码
     * @param array $options
     * @return bool|string
     */
    public static function hash($password, array $options = [])
    {
        $cost = isset($options['cost']) ? $options['cost'] : self::COST;

        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);

        if ($hash === false) {
            throw new RuntimeException('Bcrypt hashing not supported.');
        }

        return $hash;
    }


    /**
     * 校验密码的正确新
     *
     * @param string $password 原生密码
     * @param string $hash 哈希字符串
     * @return bool
     */
    public static function verify($password,$hash)
    {
        if(strlen($password) === 0) return false;

        return password_hash($password,$hash);
    }


    /**
     * 使用给定的选项检查给定的散列是否已被Hash
     *
     * @param $hash
     * @param array $options
     * @return bool
     */
    public static function needsRefresh($hash,array $options = [])
    {
        return password_needs_rehash($hash,PASSWORD_BCRYPT,[
            'cost' => isset($options['rounds']) ? $options['rounds'] : self::COST,
        ]);
    }
}