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
 * Class Session.
 *
 * Handle web session.
 */
class Session {

	protected const string FLASH_KEY = 'flash_messages';

	/**
	 * Session constructor.
	 */
	public function __construct() {
		session_start();
		$flashMessages = $_SESSION[ self::FLASH_KEY ] ?? [];

		foreach ( $flashMessages as $key => &$flashMessage ) {
			$flashMessage['remove'] = true;
		}

		$_SESSION[ self::FLASH_KEY ] = $flashMessages;
	}

	/**
	 * Session destructor.
	 */
	public function __destruct() {
		$this->removeFlashMessages();
	}

	/**
	 * Set flash message.
	 *
	 * @param  string  $key
	 * @param  string  $message
	 *
	 * @return void
	 */
	public function setFlash( string $key, string $message ): void {
		$_SESSION[ self::FLASH_KEY ][ $key ] = [
			'remove' => false,
			'value'  => $message,
		];
	}

	/**
	 * Get flash message.
	 *
	 * @param  string  $key
	 *
	 * @return false|mixed
	 */
	public function getFlash( string $key ): mixed {
		return $_SESSION[ self::FLASH_KEY ][ $key ]['value'] ?? false;
	}

	/**
	 * Set session.
	 *
	 * @param  string  $key
	 * @param  string  $value
	 *
	 * @return void
	 */
	public function set( string $key, string $value ): void {
		$_SESSION[ $key ] = $value;
	}

	/**
	 * Get session.
	 *
	 * @param  string  $key
	 *
	 * @return false|mixed
	 */
	public function get( string $key ): mixed {
		return $_SESSION[ $key ] ?? false;
	}

	/**
	 * Remove session.
	 *
	 * @param  string  $key
	 *
	 * @return void
	 */
	public function remove( string $key ): void {
		unset( $_SESSION[ $key ] );
	}

	/**
	 * Remove flash message.
	 *
	 * @return void
	 */
	private function removeFlashMessages(): void {
		$flashMessages = $_SESSION[ self::FLASH_KEY ] ?? [];

		foreach ( $flashMessages as $key => $flashMessage ) {
			if ( $flashMessage['remove'] ) {
				unset( $flashMessages[ $key ] );
			}
		}

		$_SESSION[ self::FLASH_KEY ] = $flashMessages;
	}

}