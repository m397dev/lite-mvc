<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:34 AM
 */

/**
 * Assets configuration.
 * This config is a multidimensional array with the key being the asset type
 * and the value is an array of paths to asset files.
 *
 * [css]:           Path to CSS files
 * [js]:            Path to JavaScript files
 * [jquery]:        Path to jQuery files
 *
 * Example:
 * ```
 * return [
 *    "css" => [
 *       "css/style.css",
 *    ],
 *    "js" => [
 *       "js/script.js",
 *    ],
 *    "jquery" => [
 *       "js/vendor/jquery.js",
 *    ],
 * ];
 * ```
 */
return [
	'css'    => [
		'vendor/bootstrap.min.css',
		'css/style.css',
	],
	'js'     => [
		'vendor/bootstrap.bundle.min.js',
		'js/main.js',
	],
	'jquery' => [
		'vendor/jquery.min.js',
	],
];