<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:44 AM
 */

namespace app\system;

use Exception;

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
	 * @var string $rootDir Root directory
	 */
	public static string $rootDir;
	/**
	 * @var string $layout Layout file name
	 */
	public string $layout = 'main';
	/**
	 * @var Router $router Instance of the Router class
	 */
	public Router $router;
	/**
	 * @var Request $request Instance of the Request class
	 */
	public Request $request;
	/**
	 * @var Session $session Instance of the Session class
	 */
	public Session $session;
	/**
	 * @var Cookie $cookie Instance of the Cookie class
	 */
	public Cookie $cookie;
	/**
	 * @var Controller|null $controller Instance of the Controller class
	 */
	public ?Controller $controller = null;
	/**
	 * @var Database $db Instance of the Database class
	 */
	public Database $db;
	/**
	 * @var View $view Instance of the View class
	 */
	public View $view;
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
		self::$rootDir = $rootDir;
		self::$app     = $this;
		$this->request = new Request();
		$this->router  = new Router( $this->request );
		$this->session = new Session();
		$this->cookie  = new Cookie();
		$this->db      = new Database();
		$this->view    = new View( $rootDir );
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
		} catch ( Exception $e ) {
			$this->layout = '_blank';

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

}
