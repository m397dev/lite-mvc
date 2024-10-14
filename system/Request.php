<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:47 AM
 */

namespace app\system;

use JetBrains\PhpStorm\Pure;

/**
 * Class Request.
 *
 * Handle all HTTP requests.
 */
class Request {

	const int BAD_REQUEST = 400;

	const int UNAUTHORIZED = 401;

	const int FORBIDDEN = 403;

	const int NOT_FOUND = 404;

	const int INTERNAL_SERVER_ERROR = 500;

	/**
	 * @var array $routeParams Route parameters
	 */
	private array $routeParams = [];

	/**
	 * Return HTTP response.
	 *
	 * @return array
	 */
	public function getError(): array {
		return match ( http_response_code() ) {
			self::BAD_REQUEST => [
				'code'    => self::BAD_REQUEST,
				'status'  => 'Bad Request',
				'message' => 'Something went wrong, please try again later.',
			],
			self::UNAUTHORIZED => [
				'code'    => self::UNAUTHORIZED,
				'status'  => 'Unauthorized',
				'message' => 'Something went wrong, please try again later.',
			],
			self::FORBIDDEN => [
				'code'    => self::FORBIDDEN,
				'status'  => 'Forbidden',
				'message' => 'You do not have permission to access this page.',
			],
			self::NOT_FOUND => [
				'code'    => self::NOT_FOUND,
				'status'  => 'Page Not Found',
				'message' => 'We have a problem. We are having trouble loading the page you are looking for.',
			],
			self::INTERNAL_SERVER_ERROR => [
				'code'    => self::INTERNAL_SERVER_ERROR,
				'status'  => 'Internal Server Error',
				'message' => 'Something went wrong, please try again later.',
			],
			default => [],
		};
	}

	/**
	 * Detecting the HTTP method.
	 *
	 * @return string
	 */
	public function getMethod(): string {
		return strtolower( $_SERVER['REQUEST_METHOD'] );
	}

	/**
	 * Get request url.
	 *
	 * @return false|mixed|string
	 */
	public function getUrl(): mixed {
		$path     = $_SERVER['REQUEST_URI'];
		$position = strpos( $path, '?' );

		if ( $position !== false ) {
			$path = substr( $path, 0, $position );
		}

		return $path;
	}

	/**
	 * Detecting the HTTP method is the "GET" method no not.
	 *
	 * @return bool
	 */
	#[Pure] public function isGet(): bool {
		return $this->getMethod() === 'get';
	}

	/**
	 * Detecting the HTTP method is the "POST" method no not.
	 *
	 * @return bool
	 */
	#[Pure] public function isPost(): bool {
		return $this->getMethod() === 'post';
	}

	/**
	 * Get body.
	 *
	 * @return array
	 */
	#[Pure] public function getBody(): array {
		$data = [];

		if ( $this->isGet() ) {
			foreach ( $_GET as $key => $value ) {
				$data[ $key ] = filter_input( INPUT_GET,
					$key,
					FILTER_SANITIZE_SPECIAL_CHARS );
			}
		}

		if ( $this->isPost() ) {
			foreach ( $_POST as $key => $value ) {
				$data[ $key ] = filter_input( INPUT_POST,
					$key,
					FILTER_SANITIZE_SPECIAL_CHARS );
			}
		}

		return $data;
	}

	/**
	 * Set route params.
	 *
	 * @param  mixed  $params
	 *
	 * @return Request
	 */
	public function setRouteParams( mixed $params ): Request {
		$this->routeParams = $params;

		return $this;
	}

	/**
	 * Get route params.
	 *
	 * @return array
	 */
	public function getRouteParams(): array {
		return $this->routeParams;
	}

	/**
	 * Get single route param.
	 *
	 * @param  mixed  $param
	 * @param  mixed|null  $default
	 *
	 * @return mixed|null
	 */
	public function getSingleRouteParam(
		mixed $param,
		mixed $default = null
	): mixed {
		return $this->routeParams[ $param ] ?? $default;
	}

}

