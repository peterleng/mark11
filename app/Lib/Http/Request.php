<?php

namespace App\Lib\Http;


class Request
{

    /**
     * 请求$_SERVER参数
     *
     * @var array
     */
    protected $server = [];

    /**
     * 请求的 $_GET 参数
     *
     * @var array
     */
    protected $query = [];

    /**
     * 请求的 $_POST 参数
     *
     * @var array
     */
    protected $request = [];

    /**
     * 参数
     *
     * @var array
     */
    protected $params = [];

    /**
     * 文件
     *
     * @var array
     */
    protected $files = [];

    /**
     * 请求方式
     *
     * @var string
     */
    protected $method = null;

    /**
     * cookie的值
     *
     * @var
     */
    protected $cookies = [];

    protected $session;

    /**
     * 内容
     *
     * @var string
     */
    protected $content;

    public function __construct()
    {
        $this->server = &$_SERVER;
        $this->query = &$_GET;
        $this->request = &$_POST;
        $this->files = &$_FILES;
        $this->cookies = &$_COOKIE;
    }

    /**
     * 检测是否使用手机访问
     *
     * @return bool
     */
    public function isMobile()
    {
        if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], "wap")) {
            return true;
        } elseif (isset($_SERVER['HTTP_ACCEPT']) && strpos(strtoupper($_SERVER['HTTP_ACCEPT']), "VND.WAP.WML")) {
            return true;
        } elseif (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
            return true;
        } elseif (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 是否为cli
     *
     * @return bool
     */
    public function isCli()
    {
        return PHP_SAPI == 'cli';
    }

    /**
     * 当前是否ssl
     * @return bool
     */
    public function isSsl()
    {
        if (isset($this->server['HTTPS']) && ('1' == $this->server['HTTPS'] || 'on' == strtolower($this->server['HTTPS']))) {
            return true;
        } elseif (isset($this->server['REQUEST_SCHEME']) && 'https' == $this->server['REQUEST_SCHEME']) {
            return true;
        } elseif (isset($this->server['SERVER_PORT']) && ('443' == $this->server['SERVER_PORT'])) {
            return true;
        } elseif (isset($this->server['HTTP_X_FORWARDED_PROTO']) && 'https' == $this->server['HTTP_X_FORWARDED_PROTO']) {
            return true;
        }
        return false;
    }

    /**
     * 是否为ajax请求
     *
     * @return bool
     */
    public function isAjax()
    {
        return isset($this->server['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' == $this->server['HTTP_X_REQUESTED_WITH'];
    }

    /**
     * 是否是某种方法
     *
     * @param string $method
     * @return bool
     */
    public function isMethod($method)
    {
        return $this->getMethod() === strtoupper($method);
    }


    /**
     * 获取path
     *
     * http://mark11.dev:8080/abc/123?deee=234
     *
     * @return 返回 /abc/123
     */
    public function path()
    {
        $uri = $this->getUri();
        $uri_arr = explode('?',$uri);
        return $uri_arr[0];
    }

    /**
     * 获取uri
     *
     * http://mark11.dev:8080/abc/123?deee=234
     *
     * @return 返回 /abc/123?deee=234
     */
    public function getUri()
    {
        return $this->server['REQUEST_URI'];
    }

    /**
     * 获取完整url地址
     *
     * @return string
     */
    public function fullUrl()
    {
        return $this->getScheme().'://'.$this->getHost().$this->getUri();
    }


    /**
     * 获取客户端ip地址
     *
     * @return string
     */
    public function getIp()
    {
        $ip = '';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) {
                unset($arr[$pos]);
            }
            $ip = trim(current($arr));
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * 当前URL地址中的scheme参数
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->isSsl() ? 'https' : 'http';
    }

    /**
     * 当前请求的host
     * @access public
     * @return string
     */
    public function getHost()
    {
        return $this->server['HTTP_HOST'];
    }

    /**
     * 当前请求URL地址中的port参数
     * @access public
     * @return integer
     */
    public function getPort()
    {
        return (int)$this->server['SERVER_PORT'];
    }

    /**
     * 查询字符串
     *
     * @return mixed
     */
    public function getQueryString()
    {
        return $this->server['QUERY_STRING'];
    }

    /**
     * 获取请求方式
     *
     * @return string
     */
    public function getMethod()
    {
        if (null !== $this->method) return $this->method;

        $this->method = strtoupper($this->server['REQUEST_METHOD']) ?: 'GET';
        if ('POST' === $this->method) {
            if (isset($this->server['X-HTTP-METHOD-OVERRIDE'])) {
                $this->method = strtoupper($this->server['X-HTTP-METHOD-OVERRIDE']);
            } else {
                $this->method = strtoupper($this->input('_method', 'POST'));
            }
        }

        return $this->method;
    }


    /**
     * 获取请求值
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function input($name, $default = null)
    {
        $params = $this->isMethod('GET') ? $this->query : $this->request;

        return array_key_exists($name, $params) ? urldecode(trim($params[$name])) : $default;
    }


    /**
     * 仅获取指定名字的值
     *
     * @param array $keys
     * @return array
     */
    public function only(array $keys)
    {
        $results = [];
        $inputs = &$this->all();
        foreach ($keys as $key) {
            $results[$key] = isset($inputs[$key]) ? urldecode(trim($inputs[$key])) : null;
        }

        return $results;
    }


    /**
     * 获取排除指定名的值
     *
     * @param array $keys
     * @return array
     */
    public function except(array $keys)
    {
        $results = [];
        $inputs = $this->all();
        foreach ($inputs as $key => $value) {
            if (!in_array($key, $keys)) {
                $results[$key] = $inputs[$key] ? urldecode(trim($inputs[$key])) : null;
            }
        }

        return $results;
    }

    /**
     * 获取全部值
     *
     * @return array
     */
    public function all()
    {
        return array_replace_recursive($this->isMethod('GET') ? $this->query : $this->request, $this->files);
    }


    /**
     * 获取上传文件数组
     *
     * @param string $name
     * @return array|null
     */
    public function file($name)
    {
        return isset($this->files[$name]) ? $this->files[$name] : null;
    }

    /**
     * 获取所有文件
     *
     * @return array
     */
    public function allFiles()
    {
        return $this->files;
    }

    /**
     * 获取cookie的值
     *
     * @param string $name
     * @return mixed|null
     */
    public function cookie($name = '')
    {
        return isset($this->cookies[$name]) ? $this->cookies[$name] : null;
    }
}