<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/22 10:41
 */

namespace Lagee\Route;


use Lagee\Http\Controller;
use Lagee\Http\Request;
use Lagee\Http\Response;
use App\Http\Middleware\Middleware;

class Router
{

    protected $controller = 'App\\Http\\Controllers\\';

    protected $middlewareManager;

    public function __construct()
    {
        $this->middlewareManager = new Middleware();
    }


    /**
     * 导向controller中执行
     *
     * @param Request $request
     * @return Response
     */
    public function render($request)
    {
        $response = $this->middlewareManager->handle($request, function ($request) {
            return $this->dispatch($request);
        });

        return $response->send();
    }


    /**
     * @param Request $request
     * @return array
     */
    protected function getRoutePart($request)
    {
        $path = trim($request->path(), '/');
        $path_tmp = explode('/', $path);
        $idx = 0;

        $group = $controller = $action = '';

        //子域名
        $host = $request->getHost();
        $subdomains = config('app.sub_domain');
        $sub = explode('.', $host)[0];
        if (array_key_exists($sub, $subdomains)) {
            $group = str_replace('/', '\\', $subdomains[$sub]);
        } else {
            $group = empty($path_tmp[$idx]) ? config('app.default_group') : $path_tmp[$idx];
            $idx++;
        }
        $controller = empty($path_tmp[$idx]) ? 'index' : $path_tmp[$idx];
        $idx++;
        $action = empty($path_tmp[$idx]) ? 'index' : $path_tmp[$idx];

        return ['group' => $group, 'controller' => $controller, 'action' => $action];
    }


    /**
     * 定位到对应的 Controller
     *
     * @param Request $request
     * @return Response
     */
    protected function dispatch($request)
    {
        $parts = $this->getRoutePart($request);

        $file = $this->getControllerFile($parts);
        if (file_exists($file)) {
            $controller = $this->getControllerClassWithNamespace($parts);
            return call_user_func_array([new $controller(), strtolower($parts['action'])], [$request]);
        } else {
            return call_user_func_array([new Controller(), 'notFound'], []);
        }
    }

    /**
     * 获取controller路径
     *
     * @param array $parts
     * @return string
     */
    protected function getControllerFile(array $parts)
    {
        return app_path('Http/Controllers') . DIRECTORY_SEPARATOR . ucfirst($parts['group']) . DIRECTORY_SEPARATOR . ucfirst($parts['controller']) . 'Controller.php';
    }

    /**
     * 获取controller namespace的类
     *
     * @param array $parts
     * @return string
     */
    protected function getControllerClassWithNamespace(array $parts)
    {
        return $this->controller . ucfirst($parts['group']) . '\\' . ucfirst($parts['controller']) . 'Controller';
    }


    /**
     * 通过路径构建一个路由
     *
     * @param string $path 例如：home.user.login
     * @param array $params
     * @return string
     */
    public static function getRoute($path = '', $params = [])
    {
        return '/' . implode('/', explode('.', $path)) . (empty($params) ? '' : '?' . implode('&', $params));
    }
}