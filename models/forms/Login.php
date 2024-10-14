<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:37 AM
 */

namespace app\models\forms;

use app\system\Model;

/**
 * Class Login.
 *
 * This model performs login form.
 */
class Login extends Model {

	/**
	 * @var string $username
	 */
	public string $username;

	/**
	 * @var string $password
	 */
	public string $password;

	/**
	 * @var bool $rememberMe
	 */
	public bool $rememberMe = false;

}