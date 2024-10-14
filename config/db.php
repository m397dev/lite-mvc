<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:35 AM
 */

/**
 * Database configuration.
 *
 * [host]:          Server host name
 * [driver]:        Specify database vendor implementation
 * [database]:      Database name
 * [username]:      Server host username
 * [password]:      Server host password
 * [charset]:       Database default charset
 * [collation]:     Database default collation
 * [options]:       Database config options
 * [port]:          Database running port
 * [cache]:         Caching config
 * [cacheDir]:      Path to the cache storage
 * [debug]:         Turn on/off PDO debug mode
 */
return [
	'host'      => 'localhost',
	'driver'    => 'mysql',
	'database'  => 'lite_mvc',
	'username'  => 'root',
	'password'  => '',
	'charset'   => 'utf8',
	'collation' => 'utf8_unicode_ci',
	'options'   => '',
	'port'      => 3306,
	'cache'     => '',
	'cacheDir'  => '',
	'debug'     => true,
];