<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:47 AM
 */

namespace app\system;

use Exception;

/**
 * Class Router.
 *
 * Handle web routers.
 */
class Router {

	/**
	 * @var Request $request Instance of the Request class
	 */
	private Request $request;
	/**
	 * @var array $routeMap Web route map
	 */
	private array $routeMap = [];

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->request = new Request();
	}

	/**
	 * The get router.
	 *
	 * @param  string  $url
	 * @param  mixed  $callback
	 *
	 * @return void
	 */
	public function get( string $url, mixed $callback ): void {
		$this->routeMap['get'][ $url ] = $callback;
	}

	/**
	 * The post router.
	 *
	 * @param  string  $url
	 * @param  mixed  $callback
	 *
	 * @return void
	 */
	public function post( string $url, mixed $callback ): void {
		$this->routeMap['post'][ $url ] = $callback;
	}

	/**
	 * The put router.
	 *
	 * @param  string  $url
	 * @param  mixed  $callback
	 *
	 * @return void
	 */
	public function put( string $url, mixed $callback ): void {
		$this->routeMap['put'][ $url ] = $callback;
	}

	/**
	 * The patch router.
	 *
	 * @param  string  $url
	 * @param  mixed  $callback
	 *
	 * @return void
	 */
	public function patch( string $url, mixed $callback ): void {
		$this->routeMap['patch'][ $url ] = $callback;
	}

	/**
	 * The delete router.
	 *
	 * @param  string  $url
	 * @param  mixed  $callback
	 *
	 * @return void
	 */
	public function delete( string $url, mixed $callback ): void {
		$this->routeMap['delete'][ $url ] = $callback;
	}

	/**
	 * Get route map.
	 *
	 * @param  string  $method
	 *
	 * @return array
	 */
	public function getRouteMap( string $method ): array {
		return $this->routeMap[ $method ] ?? [];
	}

	/**
	 * Get callback.
	 *
	 * @return false|mixed
	 */
	public function getCallback(): mixed {
		$method = $this->request->getMethod();
		$url    = trim( $this->request->getUrl(), '/' );
		$routes = $this->getRouteMap( $method );

		foreach ( $routes as $route => $callback ) {
			$route      = trim( $route, '/' );
			$routeNames = [];

			if ( ! $route ) {
				continue;
			}

			if ( preg_match_all( '/{(\w+)(:[^}]+)?}/', $route, $matches ) ) {
				$routeNames = $matches[1];
			}

			$routeRegex = "@^" . preg_replace_callback( '/{\w+(:([^}]+))?}/',
					function ( $m ) {
						return isset( $m[2] ) ? "($m[2])" : '(\w+)';
					},
					$route ) . "$@";

			if ( preg_match_all( $routeRegex, $url, $valueMatches ) ) {
				$values = [];

				for ( $i = 1; $i < count( $valueMatches ); $i ++ ) {
					$values[] = $valueMatches[ $i ][0];
				}

				$routeParams = array_combine( $routeNames, $values );
				$this->request->setRouteParams( $routeParams );

				return $callback;
			}
		}

		return false;
	}

	/**
	 * Resolve URL.
	 *
	 * @return false|mixed
	 *
	 * @throws Exception
	 */
	public function resolve(): mixed {
		$method   = $this->request->getMethod();
		$url      = $this->request->getUrl();
		$callback = $this->routeMap[ $method ][ $url ] ?? false;

		if ( ! $callback ) {
			$callback = $this->getCallback();

			if ( $callback === false ) {
				throw new Exception( 'Notfound', 404 );
			}
		}

		if ( is_string( $callback ) ) {
			return App::$app->view->renderView( $callback );
		}

		if ( is_array( $callback ) ) {
			/**
			 * @var Controller $controller
			 */
			$controller         = new $callback[0]();
			$controller->action = $callback[1];
			$middlewares        = $controller->getMiddlewares();

			foreach ( $middlewares as $middleware ) {
				/**
				 * @var Middleware $middleware
				 */
				$middleware->invoke();
			}

			$callback[0] = $controller;
		}

		return call_user_func( $callback, $this->request );
	}

}
