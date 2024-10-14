<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:48 AM
 */

namespace app\system;

/**
 * Class Cookie.
 *
 * Handle web cookie.
 */
class Cookie {

	/**
	 * Set cookie.
	 *
	 * @param  string  $name
	 * @param  string  $value
	 * @param  int  $time
	 *
	 * @return void
	 */
	public function setCookie(
		string $name,
		string $value,
		int $time = 86400
	): void {
		if ( empty( $this->getCookie( $name ) ) ) {
			setcookie( $name, $value, time() + $time );
		}
	}

	/**
	 * Get cookie by given name.
	 *
	 * @param  string  $name
	 *
	 * @return mixed
	 */
	public function getCookie( string $name ): mixed {
		return $_COOKIE[ $name ];
	}

}
