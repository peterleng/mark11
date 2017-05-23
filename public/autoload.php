<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/23 14:32
 */

//$include_path = [dirname(dirname(__FILE__)).'/app'];
/*$include_path = dirname(dirname(__FILE__)).'/app';
set_include_path( $include_path );
spl_autoload_register();*/

spl_autoload_register( 'auto_load' );