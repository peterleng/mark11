<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/22 15:16
 */

namespace App\Lib\Http;


class Response
{
    /**
     * 状态文本
     *
     * @var array
     */
    public static $statusTexts = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',            // RFC2518
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',          // RFC4918
        208 => 'Already Reported',      // RFC5842
        226 => 'IM Used',               // RFC3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',    // RFC7238
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',                                               // RFC2324
        421 => 'Misdirected Request',                                         // RFC7540
        422 => 'Unprocessable Entity',                                        // RFC4918
        423 => 'Locked',                                                      // RFC4918
        424 => 'Failed Dependency',                                           // RFC4918
        425 => 'Reserved for WebDAV advanced collections expired proposal',   // RFC2817
        426 => 'Upgrade Required',                                            // RFC2817
        428 => 'Precondition Required',                                       // RFC6585
        429 => 'Too Many Requests',                                           // RFC6585
        431 => 'Request Header Fields Too Large',                             // RFC6585
        451 => 'Unavailable For Legal Reasons',                               // RFC7725
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates (Experimental)',                      // RFC2295
        507 => 'Insufficient Storage',                                        // RFC4918
        508 => 'Loop Detected',                                               // RFC5842
        510 => 'Not Extended',                                                // RFC2774
        511 => 'Network Authentication Required',                             // RFC6585
    );

    /**
     * 状态码
     *
     * @var int
     */
    protected $statusCode;

    /**
     * 状态文字
     *
     * @var string
     */
    protected $statusText;

    /**
     * 内容
     *
     * @var string
     */
    protected $content;

    /**
     * 协议版本
     *
     * @var string
     */
    protected $version;

    /**
     * 头信息
     *
     * @var array
     */
    protected $headers = [];

    /**
     * 设置cookie
     *
     * @var array
     */
    protected $cookies = [];


    public function __construct($content='',$status = 200,$headers = [])
    {
        $this->content = $content;
        $this->statusCode = $status;
        $this->statusText = isset(self::$statusTexts[$status]) ? self::$statusTexts[$status] : 'unknown status';
        $this->version = '1.0';
        $this->headers = $this->mergeHeaders($headers);
    }

    /**
     * 设置头部信息
     */
    protected function setHeaders($name,$value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * 合并头信息数组
     *
     * @param array $headers
     * @return array
     */
    protected function mergeHeaders(array $headers)
    {
        return array_merge($this->headers,$headers);
    }


    /**
     * 设置cookie
     *
     * @param Cookie $cookie
     */
    public function withCookie(Cookie $cookie)
    {
        $this->cookies[$cookie->getName()] = $cookie;
    }


    /**
     * 获取状态码
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * 发送头部信息
     *
     * @return $this
     */
    protected function sendHeaders()
    {
        if (headers_sent()) {
            return $this;
        }

        if (!isset($this->headers['Date'])) {
            $date = new \DateTime();
            $date->setTimezone(new \DateTimeZone('UTC'));
            $this->headers['Date'] = $date->format('D, d M Y H:i:s').' GMT';
        }

        // headers
        foreach ($this->headers as $name => $value) {
            //foreach ($values as $value) {
                header($name.': '.$value, false, $this->statusCode);
            //}
        }

        // status
        header(sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText), true, $this->statusCode);

        // cookies
        foreach ($this->cookies as $name => $cookie) {
            setcookie($cookie->getName(), $cookie->getValue(), $cookie->getExpiresTime(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
        }

        return $this;
    }

    /**
     * 发送内容
     *
     * @return $this
     */
    protected function sendContent()
    {
        echo $this->content;

        return $this;
    }

    /**
     * 发送响应
     *
     * @return $this
     */
    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } elseif ('cli' !== PHP_SAPI) {
            static::closeOutputBuffers(0, true);
        }

        return $this;
    }

    /**
     * 设置内容
     *
     * @param mixed $content
     * @return $this
     */
    protected function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * 清除输出缓存到制定级别
     *
     * @param $targetLevel
     * @param $flush
     */
    public static function closeOutputBuffers($targetLevel, $flush)
    {
        $status = ob_get_status(true);
        $level = count($status);
        // PHP_OUTPUT_HANDLER_* are not defined on HHVM 3.3
        $flags = defined('PHP_OUTPUT_HANDLER_REMOVABLE') ? PHP_OUTPUT_HANDLER_REMOVABLE | ($flush ? PHP_OUTPUT_HANDLER_FLUSHABLE : PHP_OUTPUT_HANDLER_CLEANABLE) : -1;

        while ($level-- > $targetLevel && ($s = $status[$level]) && (!isset($s['del']) ? !isset($s['flags']) || $flags === ($s['flags'] & $flags) : $s['del'])) {
            if ($flush) {
                ob_end_flush();
            } else {
                ob_end_clean();
            }
        }
    }
}