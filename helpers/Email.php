<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:36 AM
 */

namespace app\helpers;

use app\system\App;

/**
 * Email helper.
 */
class Email {

	/**
	 * Check if an email is on the white-list or not.
	 *
	 * @param  string  $email
	 *
	 * @return bool
	 */
	public static function isInWhiteList( string $email ): bool {
		$whitelist = self::getEmailWhitelist();
		$suffix    = self::getEmailSuffix( $email );

		if ( ! in_array( $suffix, $whitelist ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Returns the email suffix.
	 *
	 * @param  string  $email
	 *
	 * @return string
	 */
	public static function getEmailSuffix( string $email ): string {
		$suffix = explode( '@', $email );

		return '@' . $suffix[1];
	}

	/**
	 * Get email whitelist config.
	 *
	 * @return array|null
	 */
	private static function getEmailWhitelist(): ?array {
		return App::$app->params['email_whitelist'];
	}

	/**
	 * Get SMTP config.
	 *
	 * @return array|null
	 */
	private static function getSmtp(): ?array {
		return App::$app->params['smtp'];
	}

}