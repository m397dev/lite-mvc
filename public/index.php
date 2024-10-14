<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:37 AM
 */

use app\events\AfterRequest;
use app\events\BeforeRequest;
use app\system\App;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new App( dirname( __DIR__ ) );

$app->setEvent( App::EVENT_BEFORE_REQUEST, function () {
	BeforeRequest::invoke();
} );

require_once __DIR__ . '/../config/routes.php';

$app->setEvent( App::EVENT_AFTER_REQUEST, function () {
	AfterRequest::invoke();
} );

$app->run();