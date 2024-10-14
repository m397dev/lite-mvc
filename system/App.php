<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:44 AM
 */

namespace app\system;

use EasyCSRF\EasyCSRF;
use EasyCSRF\NativeSessionProvider;
use Exception;
use voku\helper\AntiXSS;

/**
 * Class App.
 *
 * This is the main class in this framework.
 */
class App {

	const string EVENT_BEFORE_REQUEST = 'beforeRequest';

	const string EVENT_AFTER_REQUEST = 'afterRequest';

	/**
	 * @var App $app Instance of this class
	 */
	public static App $app;
	/**
	 * @var Router $router Instance of the Router class
	 */
	public Router $router;
	/**
	 * @var View $view Instance of the View class
	 */
	public View $view;
	/**
	 * @var array $params Web parameters configuration
	 */
	public mixed $params = [];
	/**
	 * @var array $assets Web assets configuration
	 */
	public mixed $assets = [];
	/**
	 * @var EasyCSRF $csrf Csrf instance
	 */
	public EasyCSRF $csrf;
	/**
	 * @var AntiXSS $xss AntiXss instance
	 */
	public AntiXSS $xss;
	/**
	 * @var array $eventListeners Event listeners
	 */
	protected array $eventListeners = [];

	/**
	 * Application constructor.
	 *
	 * @param $rootDir
	 */
	public function __construct( $rootDir ) {
		self::$app    = $this;
		$this->router = new Router();
		$this->view   = new View( $rootDir );
		$this->csrf   = new EasyCSRF( new NativeSessionProvider() );
		$this->xss    = new AntiXSS();
		$this->params = require $rootDir . '/config/params.php';
		$this->assets = require $rootDir . '/config/assets.php';
	}

	/**
	 * Run.
	 *
	 * @return void
	 */
	public function run(): void {
		$this->triggerEvent( self::EVENT_BEFORE_REQUEST );

		try {
			echo $this->router->resolve();

			$this->triggerEvent( self::EVENT_AFTER_REQUEST );
		} catch ( Exception $e ) {
			$this->view->layout = '_blank';

			echo $this->view->renderView( '/error', [ 'exception' => $e ] );
		}
	}

	/**
	 * Trigger an event.
	 *
	 * @param  string  $eventName
	 *
	 * @return void
	 */
	public function triggerEvent( string $eventName ): void {
		$callbacks = $this->eventListeners[ $eventName ] ?? [];

		foreach ( $callbacks as $callback ) {
			call_user_func( $callback );
		}
	}

	/**
	 * Setup list of events.
	 *
	 * @param $eventName
	 * @param $callback
	 *
	 * @return void
	 */
	public function setEvent( $eventName, $callback ): void {
		$this->eventListeners[ $eventName ][] = $callback;
	}

}
