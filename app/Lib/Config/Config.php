<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/18 20:22
 */

namespace App\Lib\Config;
use App\Lib\Traits\Singleton;


/**
 * 配置管理类
 *
 * Class Config
 * @package App\Lib\Config
 */
class Config
{
    use Singleton;

    /**
     * 配置内容
     *
     * @var array
     */
    protected static $config = [];

    protected function __construct()
    {
        $this->setConfig();
    }

    /**
     * 获取配置内容
     */
    protected function setConfig()
    {
        if (APP_DEBUG) {
            self::$config = $this->mergeConfig();
            return;
        }

        if(file_exists(cache_path('config.php'))){
            self::$config = require_once cache_path('config.php');
        }else{
            self::$config = $this->buildConfig();
        }
    }


    /**
     * 生成配置文件
     *
     * @param array $configArray
     * @return array
     */
    protected function buildConfig($configArray = [])
    {
        $configArray = $configArray ?: $this->mergeConfig();

        $text = '<?php return '.var_export($configArray,true).';';

        file_put_contents(cache_path('config.php'), $text);//写入缓存

        return $configArray;
    }

    /**
     * 合并配置数组
     *
     * @return array
     */
    protected function mergeConfig()
    {
        $config_arr = [];
        if ($dh = opendir(config_path())) {
            while (($file = readdir($dh)) !== false) {
                if($file == '.' || $file == '..'){
                    continue;
                }
                $key = substr($file, 0, strpos($file, '.'));
                $config_arr[$key] = require_once config_path($file);
            }
            closedir($dh);
        }
        return $config_arr;
    }

    /**
     * 获取所有配置
     *
     * @return array
     */
    public function getConfig()
    {
        return self::$config;
    }


    /**
     * 获取配置项
     *
     * @param string $key 配置的key值  不同配置文件下的配置项，使用 . 连接，例如 get('database.host')
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (isset(self::$config[$key])) {
            return self::$config[$key];
        }

        $result = null;

        $list = explode('.', $key);

        foreach ($list as $key => $segment) {
            if($key == 0){
                $result = isset(self::$config[$segment]) ? self::$config[$segment] : [];
            }else{
                $result = isset($result[$segment]) ? $result[$segment] : null;
            }

            if (!is_array($result)) {
                break;
            }
        }

        return is_null($result) ? $default : $result;
    }

}