<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/22 14:11
 */

namespace Lagee\View;

use Lagee\Exception\Program\NotFoundException;
use Lagee\Http\Response;
use Lagee\Traits\Singleton;

class View
{
    use Singleton;

    /**
     * 显示模板
     *
     * @param $view
     * @param array $var
     * @return Response
     * @throws NotFoundException
     */
    public function display($view,array $var)
    {
        $view = implode(DIRECTORY_SEPARATOR,explode('.',$view)).'.tpl.'.config('view.ext');

        if(!file_exists(view_path($view))){
            throw new NotFoundException('模板文件不存在：'.view_path($view));
        }

        $response = new Response($this->render(view_path($view),$var));
        return $response;
    }


    /**
     * 渲染模板
     *
     * @return string
     * @throws \Exception
     */
    protected function render($viewpath,array $var)
    {
        $obLevel = ob_get_level();

        ob_start();

        extract($var, EXTR_SKIP);
        try {
            include $viewpath;
        } catch (\Exception $e) {
            $this->handleViewException($e, $obLevel);

            throw $e;
        }

        $content = ltrim(ob_get_clean());

        return $content;
    }


    /**
     * ob_clean处理
     *
     * @param \Exception $e
     * @param $obLevel
     * @throws \Exception
     */
    protected function handleViewException(\Exception $e, $obLevel)
    {
        while (ob_get_level() > $obLevel) {
            ob_end_clean();
        }

        throw $e;
    }
}