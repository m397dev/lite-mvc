<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:42 AM
 */

namespace app\http;

use app\system\App;
use app\system\Controller;

/**
 * Default controller.
 */
class Home extends Controller {

	/**
	 * Default action.
	 *
	 * @return string
	 */
	public function index(): string {
		return App::$app->view->renderView( 'home', [] );
	}

}