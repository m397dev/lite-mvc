<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:42 AM
 */

namespace app\http\controllers;

use app\http\middlewares\Auth;
use app\system\App;
use app\system\Controller;

/**
 * Default controller.
 */
class Home extends Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->registerMiddleware( new Auth( [ 'index' ] ) );
	}

	/**
	 * Default action.
	 *
	 * @return string
	 */
	public function index(): string {
		return App::$app->view->renderView( 'welcome' );
	}

}