<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/23 17:47
 */

namespace Lagee\Session\SessionHandlers;

use SessionHandlerInterface;

/**
 * Redis Session处理器
 *
 * TODO  未完成
 *
 * Class RedisSessionHandler
 * @package Lagee\Session\SessionHandlers
 */
class RedisSessionHandler implements SessionHandlerInterface
{
    protected $lifetime;

    protected $redis;


    public function __construct()
    {
        $this->lifetime = config('session.lifetime');
        $this->redis = new \Redis();
    }


    /**
     * Close the session
     *
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * Destroy a session
     *
     * @param string $session_id The session ID being destroyed.
     * @return bool
     */
    public function destroy($session_id)
    {
        //$this->newQuery()->deleteOne($session_id);
        //TODO
        return true;
    }

    /**
     * Cleanup old sessions
     *
     * @param int $maxlifetime
     * @return bool
     */
    public function gc($maxlifetime)
    {
        //$where = '`time` <= ' . time() - $this->lifetime;
        //$this->newQuery()->delete($where);

        //TODO
        return true;
    }

    /**
     * Initialize session
     *
     * @param string $save_path The path where to store/retrieve the session.
     * @param string $name The session name.
     * @return bool
     */
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * Read session data
     *
     * @param string $session_id The session id to read data for.
     * @return string
     */
    public function read($session_id)
    {
        /*$session = $this->newQuery()->find($session_id);
        if (empty($session) || $this->expired($session)) {
            return null;
        }
        return base64_decode($session->data);*/
        //TODO
        return '';
    }

    /**
     * Write session data
     *
     * @param string $session_id The session id.
     * @param string $session_data
     * @return bool
     */
    public function write($session_id, $session_data)
    {
        /*$session = $this->newQuery()->find($session_id);
        if (!empty($session)) {
            $this->newQuery()->update(['id' => $session_id], ['time' => time(), 'data' => base64_encode($session_data)]);
        } else {
            $this->newQuery()->insert(['id' => $session_id, 'data' => base64_encode($session_data), 'time' => time()]);
        }*/
        //TODO

        return true;
    }


    /**
     * Determine if the session is expired.
     *
     * @param  \StdClass $session
     * @return bool
     */
    protected function expired($session)
    {
        return isset($session->time) && $session->time < time() - $this->lifetime;
    }
}