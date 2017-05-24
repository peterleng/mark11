<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/23 16:59
 */

namespace App\Lib\Http;

use App\Lib\Traits\Singleton;
use App\Lib\Session\SessionInterface;
use App\Lib\Util\Str;
use SessionHandlerInterface;

class Session implements SessionInterface
{
    use Singleton;
    /**
     * session id.
     *
     * @var string
     */
    protected $id;

    /**
     * The session name.
     *
     * @var string
     */
    protected $name;

    /**
     * The session attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The session handler implementation.
     *
     * @var \SessionHandlerInterface
     */
    protected $handler;

    /**
     * Session store started status.
     *
     * @var bool
     */
    protected $started = false;


    protected function __construct()
    {
        $this->setId();
    }

    /**
     * 设置session处理器
     *
     * @param string $name
     * @param SessionHandlerInterface $handler
     * @return $this
     */
    public function initHandler($name, SessionHandlerInterface $handler)
    {
        $this->name = $name;
        $this->handler = $handler;

        session_set_save_handler($this->handler, false);
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * 获取session_id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 设置session_id
     *
     * @param  string $id
     * @return void
     */
    public function setId($id = '')
    {
        if (strlen($id) == 40 && ctype_alnum($id)) {
            $this->id = $id;
        } else {
            $this->id = $this->generateSessionId();
        }
    }

    /**
     * 开启 session
     *
     * @return bool
     */
    public function start()
    {
        $this->attributes = array_merge($this->attributes, $this->readFromHandler());

        if (!$this->has('_token')) {
            $this->regenerateToken();
        }

        return $this->started = true;
    }

    /**
     * 读取session
     *
     * @return array
     */
    protected function readFromHandler()
    {
        if ($data = $this->handler->read($this->getId())) {
            $data = @unserialize($data);

            if ($data !== false && !is_null($data) && is_array($data)) {
                return $data;
            }
        }
        return [];
    }

    /**
     * 重新生成token值
     */
    public function regenerateToken()
    {
        $this->put('_token', Str::randNumStr(40));
    }

    /**
     * 保存session的值
     *
     * @return bool
     */
    public function save()
    {
        $this->handler->write($this->getId(), serialize($this->attributes));

        $this->started = false;
    }

    /**
     * 获取当前所有session的值
     *
     * @return array
     */
    public function all()
    {
        return $this->attributes;
    }

    /**
     * 判断当前key的session是否存在
     *
     * @param  string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * 获取session值
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : $default;
    }

    /**
     * 设置session的值
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function put($key, $value = null)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * 获取CSRF的token值
     *
     * @return string
     */
    public function token()
    {
        return $this->get('_token');
    }

    /**
     * 删除一个session，并返回该值
     *
     * @param  string $key
     * @return mixed
     */
    public function remove($key)
    {
        unset($this->attributes[$key]);
    }

    /**
     * 删除一个或多个session值
     *
     * @param  string|array $keys
     * @return void
     */
    public function forget($keys)
    {
        if (is_string($keys)) {
            unset($this->attributes[$keys]);
        } elseif (is_array($keys)) {
            foreach ($keys as $item) {
                unset($this->attributes[$item]);
            }
        }
    }

    /**
     * 删除所有session值
     *
     * @return void
     */
    public function flush()
    {
        $this->attributes = [];
    }

    /**
     * 重新生成一个session_id
     *
     * @param  bool $destroy
     * @return bool
     */
    public function regenerate($destroy = false)
    {
        if ($destroy) {
            $this->handler->destroy($this->getId());
        }

        $this->setId($this->generateSessionId());

        return true;
    }

    /**
     * 判断session是否已经开启
     *
     * @return bool
     */
    public function isStarted()
    {
        return $this->started;
    }

    /**
     * 获取session处理器
     *
     * @return \SessionHandlerInterface
     */
    public function getHandler()
    {
        return $this->handler;
    }


    /**
     * @return string
     */
    protected function generateSessionId()
    {
        return Str::randNumStr(40);
    }
}