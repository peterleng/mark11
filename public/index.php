<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/20 17:55
 */

define('APP_DEBUG',true);

require_once '../bootstrap/autoload.php';
require_once '../lagee/Application.php';

$app = require_once '../bootstrap/app.php';
$app->run();