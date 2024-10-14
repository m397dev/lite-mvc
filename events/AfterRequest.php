<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        5:36 AM
 */

namespace app\events;

/**
 * Class AfterRequest.
 *
 * This class handle the after request event.
 */
class AfterRequest {

	/**
	 * Run after request event.
	 *
	 * @return void
	 */
	public static function invoke(): void {
		echo 'After request';
	}

}