<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:34 AM
 */

/**
 * Web parameter configuration.
 * This config is a multidimensional array with the key being the param name
 * and the value is param value. You should define any common param, that will
 * often appear in the whole project, like app name, home url, ...
 *
 * Example:
 * ```
 * return [
 *    "app_name" => "Lite MVC",
 * ];
 * ```
 */
return [
	'app_name'        => 'Lite MVC',
	'favicon_path'    => 'http://litemvc.local/favicon.ico',
	'logo_path'       => 'http://litemvc.local/assets/images/logo.png',
	'base_url'        => 'http://litemvc.local',
	'home_url'        => 'http://litemvc.local',
	'smtp'            => [
		'smtp_server'       => '',
		'smtp_port'         => '',
		'smtp_username'     => '',
		'smtp_password'     => '',
		'smtp_sender_email' => '',
		'smtp_sender_name'  => '',
	],
	'email_whitelist' => [
		'@gmail.com',
		'@hotmail.com',
		'@yahoo.com',
	],
];