<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/20 17:55
 */

define('APP_DEBUG',true);

require_once '../app/Helper/Helper.php';
require_once './autoload.php';
require_once '../app/Lib/Application.php';

$app = App\Lib\Application::getInstance();
$app->run();