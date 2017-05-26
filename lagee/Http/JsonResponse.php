<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/24 22:46
 */

namespace Lagee\Http;


class JsonResponse extends Response
{
    protected $callback;
    protected $json;

    public function __construct(array $data, $status = 200, $headers = [])
    {
        $headers['Cache-Control'] = 'no-cache';
        $this->setJson($data);

        parent::__construct($this->json, $status, $headers);
    }

    /**
     * 设置json字符串
     *
     * @param array $data
     * @return $this
     */
    public function setJson(array $data)
    {
        $this->json = json_encode($data);
        return $this->update();
    }

    /**
     * Sets the JSONP callback.
     *
     * @param  string|null $callback
     * @return $this
     */
    public function setCallback($callback = null)
    {
        $this->callback = $callback;
        return $this;
    }


    /**
     * 更新json内容及头信息
     *
     * @return $this
     */
    public function update()
    {
        if ($this->callback !== null) {
            $this->setHeaders('Content-Type', 'text/javascript');

            return $this->setContent(sprintf('/**/%s(%s);', $this->callback, $this->json));
        }

        $this->setHeaders('Content-Type', 'application/json');
        return $this->setContent($this->json);
    }

}