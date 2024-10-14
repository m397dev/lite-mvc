<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        6:10 AM
 */

namespace app\system\interfaces;

/**
 * MiddlewareInterface class.
 */
interface MiddlewareInterface {

	/**
	 * Execute the middleware.
	 */
	public function invoke();

}