<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:37 AM
 */

namespace app\models;

use app\system\Model;
use app\system\ModelInterface;

/**
 * Class User.
 *
 * This is the model class for table "users".
 */
class User extends Model implements ModelInterface {

	/**
	 * {@inheritdoc }
	 */
	public function represent(): string {
		return 'users';
	}

	/**
	 * {@inheritdoc }
	 */
	public function validate(): bool {
		return true;
	}

	/**
	 * {@inheritdoc }
	 */
	public function insert(): bool {
		return true;
	}

	/**
	 * {@inheritdoc }
	 */
	public function update(): bool {
		return true;
	}

	/**
	 * {@inheritdoc }
	 */
	public function delete(): bool {
		return true;
	}

}