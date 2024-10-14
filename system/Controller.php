<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:44 AM
 */

namespace app\system;

/**
 * Class Controller
 *
 * This is the base controller.
 * This provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *
 * ```
 *     class Home extends BaseController
 *```
 *
 * For security be sure to declare any new methods as protected or private.
 */
class Controller {

	/**
	 * @var string $action Action name
	 */
	public string $action = 'index';
	/**
	 * @var array $params Web params configuration
	 */
	public mixed $params = [];
	/**
	 * @var string $error Path to error file
	 */
	public string $error = '';
	/**
	 * @var array|string[] $patterns SQL injection patterns
	 */
	private array $patterns = [
		"union",
		"cookie",
		"concat",
		"alter",
		"table",
		"from",
		"where",
		"exec",
		"shell",
		"wget",
		"**/",
		"/**",
		"0x3a",
		"null",
		"DR/**/OP/",
		"drop",
		"/*",
		"*/",
		"*",
		"--",
		";",
		"||",
		"'",
		"' #",
		"or 1=1",
		"'1'='1",
		"BUN",
		"S@BUN",
		"char",
		"OR%",
		"`",
		"[",
		"]",
		"<",
		">",
		"++",
		"script",
		"select",
		"1,1",
		"substring",
		"ascii",
		"sleep(",
		"&&",
		"and",
		"insert",
		"between",
		"values",
		"truncate",
		"benchmark",
		"sql",
		"mysql",
		"%27",
		"%22",
		"(",
		")",
		"<?",
		"<?php",
		"?>",
		"../",
		"/localhost",
		"127.0.0.1",
		"loopback",
		":",
		"%0A",
		"%0D",
		"%3C",
		"%3E",
		"%00",
		"%2e%2e",
		"input_file",
		"execute",
		"msconfig",
		"environ",
		"scanner",
		"path=.",
		"mod=.",
		"eval\(",
		"javascript:",
		"base64_",
		"boot.ini",
		"etc/passwd",
		"self/environ",
		"md5",
		"echo.*kae",
		"=%27$",
	];
	/**
	 * @var array $middlewares List of middlewares
	 */
	protected array $middlewares = [];

	/**
	 * Controller constructor.
	 */
	public function __construct() {
		$this->secureHttp();
	}

	/**
	 * Register new middleware.
	 *
	 * @param  Middleware  $middleware
	 *
	 * @return void
	 */
	public function registerMiddleware( Middleware $middleware ): void {
		$this->middlewares[] = $middleware;
	}

	/**
	 * Return list of current middlewares.
	 *
	 * @return array
	 */
	public function getMiddlewares(): array {
		return $this->middlewares;
	}

	/**
	 * Provide HTTP security.
	 *
	 * @return void
	 */
	private function secureHttp(): void {
		$_POST    = $this->sanitize( $_POST );
		$_GET     = $this->sanitize( $_GET );
		$_REQUEST = $this->sanitize( $_REQUEST );
		$_COOKIE  = $this->sanitize( $_COOKIE );

		if ( isset( $_SESSION ) ) {
			$_SESSION = $this->sanitize( $_SESSION );
		}

		$queryString = $_SERVER['QUERY_STRING'];

		foreach ( $this->patterns as $pattern ) {
			if ( strlen( $queryString ) > 255 || strpos( strtolower( $queryString ),
					strtolower( $pattern ) ) ) {
				http_response_code( 503 );

				exit();
			}
		}
	}

	/**
	 * Sanitized the given input.
	 *
	 * @param $input
	 *
	 * @return array|string
	 */
	private function sanitize( $input ): array|string {
		if ( is_array( $input ) ) {
			$output = [];
			foreach ( $input as $key => $value ) {
				$output[ $key ] = $this->cleaninput( $value );
			}
		} else {
			$output = htmlentities( $this->cleaninput( $input ), ENT_QUOTES );
		}

		return @$output;
	}

	/**
	 * Cleaned the given input.
	 *
	 * @param $input
	 *
	 * @return array|string|null
	 */
	private function cleanInput( $input ): array|string|null {
		$input  = str_replace( '"', "", $input );
		$input  = str_replace( "'", "", $input );
		$search = [
			'@<script[^>]*?>.*?</script>@si',
			'@<[/!]*?[^<>]*?>@si',
			'@<style[^>]*?>.*?</style>@siU',
			'@<![\s\S]*?--[ \t\n\r]*>@',
			'/[^\\S ]/',
		];

		return preg_replace( $search, '', $input );
	}

}