<?php

namespace Lagee\Util;

use Lagee\Exception\Program\CurlException;

/**
 * User: Peter Leng
 * DateTime: 2017/5/23 13:43
 */
class Curl
{
    protected $ch;
    protected $timeout = 10;//设置curl超时时间
    protected $userAgent = '';//模拟用户使用的浏览器，默认不模拟

    public function __construct()
    {
        $this->ch = curl_init();
    }

    /**
     * 设置超时
     *
     * @param int $timeout
     * @return $this
     */
    public function setTimeOut($timeout)
    {
        if (intval($timeout) > 0) {
            $this->timeout = $timeout;
        }
        return $this;
    }

    /**
     * 设置伪造ip
     *
     * @param string $ip
     * @return string
     */
    public function setIP($ip = '')
    {
        if (!empty($ip)) {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, ["X-FORWARDED-FOR:$ip", "CLIENT-IP:$ip"]);
        }
        return $ip;
    }

    /**
     * 设置来源页面
     *
     * @param string $referer
     * @return $this
     */
    public function setReferer($referer = '')
    {
        if (!empty($referer)) {
            curl_setopt($this->ch, CURLOPT_REFERER, $referer);
        }
        return $this;
    }

    /**
     * 设置请求头信息
     *
     * @param string $userAgent
     * @return $this
     */
    public function setUserAgent($userAgent = '')
    {
        if (!empty($userAgent)) {
            curl_setopt($this->ch, CURLOPT_USERAGENT, $this->userAgent); // 模拟用户使用的浏览器
        }
        return $this;
    }


    /**
     * 执行curl请求
     *
     * @param string $url
     * @return mixed
     * @throws CurlException
     */
    protected function exec($url)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);    //获取的信息以文件流的形式返回
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($this->ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);  //超时设置
        curl_setopt($this->ch, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($this->ch, CURLOPT_NOBODY, 0);//不返回response body内容
        curl_setopt($this->ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);//设置默认访问为ipv4
        $res = curl_exec($this->ch);

        if (curl_errno($this->ch)) {
            throw new CurlException(curl_error($this->ch), curl_errno($this->ch));
        }

        return $res;
    }


    /**
     * 发送get请求
     *
     * @param string $url
     * @param array $data
     * @return mixed
     */
    public function get($url, array $data)
    {
        $url .= '?' . http_build_query($data);

        //curl_setopt($this->ch, CURLOPT_HTTPGET, 1); // 发送一个常规的GET请求，默认值
        return $this->exec($url);
    }


    /**
     * 发送post请求
     *
     * @param string $url
     * @param array $data
     * @return mixed
     */
    public function post($url, array $data = [])
    {
        curl_setopt($this->ch, CURLOPT_POST, 1);// 发送一个常规的POST请求
        if (!empty($data)) {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);//post请求参数
        }

        return $this->exec($url);
    }

    /**
     * 关闭curl
     */
    public function __destruct()
    {
        curl_close($this->ch);
    }

}