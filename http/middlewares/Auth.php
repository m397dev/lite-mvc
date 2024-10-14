<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        5:47 AM
 */

namespace app\http\middlewares;

use app\system\Middleware;
use Exception;

/**
 * Class Auth.
 *
 * Authentication middleware.
 */
class Auth extends Middleware {

	/**
	 * @var bool $isGuest Check this current access has logged in or not
	 */
	public bool $isGuest = false;

	/**
	 * @inheritDoc
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function invoke(): void {
		if ( $this->isGuest === true ) {
			http_response_code( 403 );

			exit();
		}
	}

}