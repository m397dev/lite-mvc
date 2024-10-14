<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:35 AM
 */

use app\http\controllers\Home;

/* Routes:
Valid methods: get, post, put, patch, delete

e.g:
// Basic usage:
$app->router->get('url', [Controller::class, 'method']);

// Route has 1 or more parameters
$app->router->get('url/{param1}/{param2}/{param3}', [Controller::class, 'method']);

// Route get param is integer
$app->router->get('url/{param:\d+}', [Controller::class, 'method']);
*/

/**
 * @var \app\system\App $app
 */
$app->router->get( '/', [ Home::class, 'index' ] );