<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:37 AM
 */

use app\http\Home;
use app\system\App;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new App( dirname( __DIR__ ) );

/* Routes:
e.g:
// If the page has get method:
$app->router->get('url', [Controller::class, 'method']);
$app->router->get('url/{id}', [Controller::class, 'method']);
$app->router->get('url/{id:\d+}/{username}', [Controller::class, 'method']);

// If the page has post method:
$app->router->post('url', [Controller::class, 'method']));
*/

/**
 * --------------------------------------------------------
 * Web routes.
 * --------------------------------------------------------
 */
$app->router->get( 'home', [ Home::class, 'index' ] );

/**
 * Execute.
 */
$app->run();