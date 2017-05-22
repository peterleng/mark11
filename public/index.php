<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/20 17:55
 */

require_once '../app/Helper/Helper.php';
require_once './define.php';

require_once '../app/Lib/Application.php';


$app = App\Lib\Application::getInstance();

$app->run();